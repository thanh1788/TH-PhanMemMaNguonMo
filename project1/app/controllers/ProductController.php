<?php

// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $db;
    private $uploadDir = 'public/uploads/products/'; // Thư mục lưu trữ ảnh sản phẩm

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // Trang chủ hiển thị danh sách
    public function index()
    {
        // Lấy tất cả danh mục để làm thanh điều hướng
        $categories = (new CategoryModel($this->db))->getCategories();
        
        // Lấy tất cả sản phẩm
        $products = $this->productModel->getProducts();
        
        // Tách sản phẩm theo nhóm danh mục trong mảng
        $productsByCategory = [];
        foreach ($products as $product) {
            $categoryName = $product->category_name ?? 'Khác';
            $productsByCategory[$categoryName][] = $product;
        }
    
        include 'app/views/product/list.php';
    }

    // // Xem chi tiết sản phẩm
    // public function show($id)
    // {
    //     $product = $this->productModel->getProductById($id);
        
    //     if ($product) {
    //         include 'app/views/product/show.php';
    //     } else {
    //         echo "Không thấy sản phẩm.";
    //     }
    // }

    // Giao diện thêm sản phẩm
    public function add()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    // Xử lý lưu sản phẩm mới kèm tải ảnh lên
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $image = null;

            // Xử lý upload hình ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                if (!is_dir($this->uploadDir)) {
                    mkdir($this->uploadDir, 0777, true);
                }

                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    // Tạo tên file ngẫu nhiên duy nhất để tránh trùng file
                    $fileName = uniqid('prod_', true) . '.' . $fileExtension;
                    $targetFilePath = $this->uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $image = $fileName;
                    }
                }
            }

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if (is_array($result)) {
                // Nếu xảy ra lỗi Validate, xóa file ảnh vừa up tạm lên (nếu có) để tránh rác server
                if ($image && file_exists($this->uploadDir . $image)) {
                    unlink($this->uploadDir . $image);
                }

                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /'); // Điều hướng thẳng về trang chủ localhost:8080
                exit;
            }
        }
    }

    // Giao diện chỉnh sửa sản phẩm
    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    // Xử lý cập nhật sản phẩm kèm đổi ảnh
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $image = null;

            // Lấy thông tin sản phẩm hiện tại để xử lý file ảnh cũ
            $currentProduct = $this->productModel->getProductById($id);

            // Kiểm tra xem người dùng có chọn upload file ảnh mới không
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = uniqid('prod_', true) . '.' . $fileExtension;
                    $targetFilePath = $this->uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $image = $fileName;

                        // Xóa file ảnh cũ trên server nếu có ảnh mới thay thế
                        if ($currentProduct && !empty($currentProduct->image)) {
                            $oldImagePath = $this->uploadDir . $currentProduct->image;
                            if (file_exists($oldImagePath)) {
                                unlink($oldImagePath);
                            }
                        }
                    }
                }
            }

            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            
            if ($edit) {
                header('Location: /');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    // Xóa sản phẩm và dọn sạch ảnh tương ứng trên server
    public function delete($id)
    {
        $product = $this->productModel->getProductById($id);

        if ($product) {
            // Xóa dữ liệu trong database trước
            if ($this->productModel->deleteProduct($id)) {
                // Nếu xóa trong DB thành công, tiến hành xóa file ảnh trên ổ đĩa
                if (!empty($product->image)) {
                    $imagePath = $this->uploadDir . $product->image;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                header('Location: /');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi xóa sản phẩm.";
            }
        } else {
            echo "Sản phẩm không tồn tại.";
        }
    }

    public function show($id) {
        // Gọi Model để lấy dữ liệu sản phẩm theo ID từ URL
        $product = $this->productModel->getProductById($id);
    
        // Nếu sản phẩm tồn tại thì nạp file View show.php
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            // Nếu không thấy sản phẩm, quay về danh sách
            header('Location: /Product/index');
        }
    }
}