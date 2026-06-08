<?php

require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/AuthHelper.php');

class ProductApiController
{
    private $productModel;
    private $db;
    private $uploadDir = 'public/uploads/products/'; // Thư mục lưu trữ ảnh sản phẩm

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    /**
     * Hàm helper trả về phản hồi JSON chuẩn hóa
     */
    private function jsonResponse(bool $success, string $message, array $data = [], int $statusCode = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Hàm helper đọc dữ liệu đầu vào linh hoạt cho các API không chứa File (Raw JSON hoặc POST)
     */
    private function getRequestInput()
    {
        return json_decode(file_get_contents('php://input'), true) ?? $_POST;
    }

    // ============================================================
    // [GET] LẤY DANH SÁCH SẢN PHẨM (Phân nhóm theo danh mục)
    // ============================================================
    public function index()
    {
        $products = $this->productModel->getProducts();

        $productsByCategory = [];
        foreach ($products as $product) {
            $categoryName = $product->category_name ?? 'Khác';
            $productsByCategory[$categoryName][] = $product;
        }

        return $this->jsonResponse(true, 'Lấy danh sách sản phẩm thành công.', [
            'products_by_category' => $productsByCategory
        ], 200);
    }

    // ============================================================
    // [GET] TÌM KIẾM SẢN PHẨM: /Product/search?q=tên_sản_phẩm
    // ============================================================
    public function search()
    {
        $q = trim($_GET['q'] ?? '');

        if (empty($q)) {
            return $this->jsonResponse(false, 'Từ khóa tìm kiếm không được để trống (Thêm tham số ?q=...).', [], 400);
        }

        $products = $this->productModel->searchProducts($q);

        $productsByCategory = [];
        foreach ($products as $product) {
            $categoryName = $product->category_name ?? 'Khác';
            $productsByCategory[$categoryName][] = $product;
        }

        return $this->jsonResponse(true, 'Tìm kiếm sản phẩm thành công.', [
            'keyword' => $q,
            'results' => $productsByCategory
        ], 200);
    }

    // ============================================================
    // [GET] XEM CHI TIẾT MỘT SẢN PHẨM: /Product/show/5
    // ============================================================
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        
        if ($product) {
            return $this->jsonResponse(true, 'Lấy chi tiết sản phẩm thành công.', [
                'product' => $product
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Sản phẩm không tồn tại.', [], 404);
        }
    }

    // ============================================================
    // [POST] XỬ LÝ LƯU SẢN PHẨM MỚI KÈM TẢI ẢNH (form-data)
    // ============================================================
    public function save()
    {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = trim($_POST['price'] ?? '');
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $image       = null;

        if (empty($name) || empty($price)) {
            return $this->jsonResponse(false, 'Tên và giá sản phẩm không được để trống.', [], 400);
        }

        // Xử lý upload hình ảnh
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0777, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = uniqid('prod_', true) . '.' . $fileExtension;
                $targetFilePath = $this->uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $image = $fileName;
                }
            } else {
                return $this->jsonResponse(false, 'Định dạng ảnh không hợp lệ (Chỉ chấp nhận jpg, jpeg, png, gif, webp).', [], 422);
            }
        }

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

        // Nếu Model trả về mảng, tức là có lỗi validate từ phía DB/Model
        if (is_array($result)) {
            if ($image && file_exists($this->uploadDir . $image)) {
                unlink($this->uploadDir . $image); // Xóa ảnh vừa upload nếu lưu DB thất bại
            }
            return $this->jsonResponse(false, 'Dữ liệu không hợp lệ.', ['errors' => $result], 422);
        }

        return $this->jsonResponse(true, 'Thêm sản phẩm mới thành công.', [
            'product_id' => $result,
            'product' => [
                'name'        => $name,
                'description' => $description,
                'price'       => $price,
                'category_id' => $category_id,
                'image'       => $image
            ]
        ], 201);
    }

    // ============================================================
    // [POST] XỬ LÝ CẬP NHẬT SẢN PHẨM KÈM ĐỔI ẢNH (form-data)
    // ============================================================
    public function update()
    {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ. Hãy dùng POST truyền form-data.', [], 405);
        }

        $id          = $_POST['id'] ?? null;
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = trim($_POST['price'] ?? '');
        $category_id = $_POST['category_id'] ?? null;
        $image       = null;

        if (!$id || empty($name) || empty($price)) {
            return $this->jsonResponse(false, 'Thiếu thông tin cập nhật sản phẩm bắt buộc (id, name, price).', [], 400);
        }

        $currentProduct = $this->productModel->getProductById($id);
        if (!$currentProduct) {
            return $this->jsonResponse(false, 'Sản phẩm cần cập nhật không tồn tại.', [], 404);
        }

        // Kiểm tra xử lý tải ảnh mới lên
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = uniqid('prod_', true) . '.' . $fileExtension;
                $targetFilePath = $this->uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $image = $fileName;

                    // Dọn dẹp ảnh cũ khỏi hệ thống
                    if (!empty($currentProduct->image)) {
                        $oldImagePath = $this->uploadDir . $currentProduct->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
            } else {
                return $this->jsonResponse(false, 'Định dạng ảnh mới không hợp lệ.', [], 422);
            }
        }

        $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
        
        if ($edit) {
            return $this->jsonResponse(true, 'Cập nhật sản phẩm thành công.', [
                'product' => [
                    'id'          => $id,
                    'name'        => $name,
                    'description' => $description,
                    'price'       => $price,
                    'category_id' => $category_id,
                    'image'       => $image ?? $currentProduct->image
                ]
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Lưu dữ liệu cập nhật sản phẩm thất bại hoặc dữ liệu không thay đổi.', [], 500);
        }
    }

    // ============================================================
    // [DELETE/POST] XÓA SẢN PHẨM VÀ FILE ẢNH ĐI KÈM
    // ============================================================
    public function delete($id)
    {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $product = $this->productModel->getProductById($id);

        if (!$product) {
            return $this->jsonResponse(false, 'Sản phẩm không tồn tại.', [], 404);
        }

        if ($this->productModel->deleteProduct($id)) {
            // Xóa file vật lý lưu trên ổ đĩa cứng server
            if (!empty($product->image)) {
                $imagePath = $this->uploadDir . $product->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            return $this->jsonResponse(true, 'Đã xóa hoàn toàn sản phẩm và tệp tin đa phương tiện thành công.', [
                'deleted_id' => $id
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Đã xảy ra lỗi hệ thống khi thực hiện xóa sản phẩm.', [], 500);
        }
    }
}