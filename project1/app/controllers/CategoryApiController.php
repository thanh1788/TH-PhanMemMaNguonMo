<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/AuthHelper.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
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
     * Hàm helper đọc dữ liệu đầu vào (Hỗ trợ cả Raw JSON Body và Form Data)
     */
    private function getRequestInput()
    {
        return json_decode(file_get_contents('php://input'), true) ?? $_POST;
    }

    // ============================================================
    // [GET] LẤY DANH SÁCH DANH MỤC: /Category hoặc /api/categories
    // ============================================================
    public function index()
    {
        AuthHelper::requireAdmin(); // Đảm bảo hàm này trả về JSON 403 nếu fail, thay vì redirect

        $categories = $this->categoryModel->getCategories();
        
        return $this->jsonResponse(true, 'Lấy danh sách danh mục thành công.', [
            'categories' => $categories
        ], 200);
    }

    // ============================================================
    // [POST] THÊM DANH MỤC MỚI (Hàm 'save' cũ của bạn)
    // ============================================================
    public function save()
    {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $input = $this->getRequestInput();
        $name = trim($input['name'] ?? '');
        $description = trim($input['description'] ?? '');

        if (empty($name)) {
            return $this->jsonResponse(false, 'Tên danh mục không được để trống.', [], 400);
        }

        $result = $this->categoryModel->addCategory($name, $description);

        if ($result) {
            return $this->jsonResponse(true, 'Thêm danh mục mới thành công.', [
                'category' => [
                    'name' => $name,
                    'description' => $description
                ]
            ], 201); // 201 Created
        } else {
            return $this->jsonResponse(false, 'Đã xảy ra lỗi hệ thống khi thêm danh mục.', [], 500);
        }
    }

    // ============================================================
    // [GET] CHI TIẾT MỘT DANH MỤC: /Category/edit/5
    // ============================================================
    public function edit($id)
    {
        AuthHelper::requireAdmin();

        $category = $this->categoryModel->getCategoryById($id);
        
        if ($category) {
            return $this->jsonResponse(true, 'Lấy thông tin danh mục thành công.', [
                'category' => $category
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Danh mục không tồn tại.', [], 404);
        }
    }

    // ============================================================
    // [PUT/POST] CẬP NHẬT DANH MỤC
    // ============================================================
    public function update()
    {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $input = $this->getRequestInput();
        $id = $input['id'] ?? null;
        $name = trim($input['name'] ?? '');
        $description = trim($input['description'] ?? '');

        if (!$id || empty($name)) {
            return $this->jsonResponse(false, 'Thiếu tham số bắt buộc (id, name).', [], 400);
        }

        $result = $this->categoryModel->updateCategory($id, $name, $description);

        if ($result) {
            return $this->jsonResponse(true, 'Cập nhật danh mục thành công.', [
                'category' => [
                    'id' => $id,
                    'name' => $name,
                    'description' => $description
                ]
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Cập nhật danh mục thất bại hoặc không có thay đổi nào.', [], 500);
        }
    }

    // ============================================================
    // [DELETE] XÓA DANH MỤC: /Category/delete/5
    // ============================================================
    public function delete($id)
    {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $result = $this->categoryModel->deleteCategory($id);

        if ($result) {
            return $this->jsonResponse(true, 'Xóa danh mục thành công.', [
                'deleted_id' => $id
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Lỗi khi xóa danh mục hoặc danh mục không tồn tại.', [], 500);
        }
    }
}