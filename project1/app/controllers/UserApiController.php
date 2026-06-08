<?php

require_once('app/config/database.php');
require_once('app/models/UserModel.php');
require_once('app/helpers/AuthHelper.php');

class UserApiController
{
    private $db;
    private $userModel;
    private $avatarDir = 'public/uploads/avatars/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db        = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    /**
     * Hàm helper xuất phản hồi JSON chuẩn hóa
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
     * Hàm helper lấy dữ liệu đầu vào linh hoạt (Raw JSON body hoặc $_POST)
     */
    private function getRequestInput()
    {
        return json_decode(file_get_contents('php://input'), true) ?? $_POST;
    }

    // ============================================================
    // HỒ SƠ CÁ NHÂN
    // ============================================================

    // [GET] Xem hồ sơ cá nhân hiện tại
    public function profile()
    {
        AuthHelper::requireLogin(); // Lưu ý: Cần đảm bảo hàm này trả về JSON 401 nếu chưa login thay vì redirect

        $user = $this->userModel->findById(AuthHelper::getUserId());
        if (!$user) {
            return $this->jsonResponse(false, 'Không tìm thấy thông tin tài khoản người dùng.', [], 404);
        }

        return $this->jsonResponse(true, 'Lấy thông tin hồ sơ thành công.', [
            'user' => $user
        ], 200);
    }

    // [POST/PUT] Cập nhật thông tin cá nhân cơ bản
    public function updateProfile()
    {
        AuthHelper::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $id       = AuthHelper::getUserId();
        $input    = $this->getRequestInput();
        $fullName = trim($input['full_name'] ?? '');
        $phone    = trim($input['phone'] ?? '');
        $address  = trim($input['address'] ?? '');
        $errors   = [];

        if (empty($fullName)) {
            $errors['full_name'] = 'Họ tên không được để trống.';
        }
        if (!empty($phone) && !preg_match('/^[0-9]{9,11}$/', $phone)) {
            $errors['phone'] = 'Số điện thoại không hợp lệ (yêu cầu từ 9-11 số).';
        }

        if (!empty($errors)) {
            return $this->jsonResponse(false, 'Dữ liệu nhập vào không hợp lệ.', ['errors' => $errors], 422);
        }

        if ($this->userModel->updateProfile($id, $fullName, $phone, $address)) {
            $_SESSION['user_name'] = $fullName; // Đồng bộ Session nếu ứng dụng có dùng song song
            
            return $this->jsonResponse(true, 'Cập nhật thông tin hồ sơ thành công.', [
                'user' => [
                    'id'        => $id,
                    'full_name' => $fullName,
                    'phone'     => $phone,
                    'address'   => $address
                ]
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Cập nhật thất bại hoặc dữ liệu không có thay đổi.', [], 500);
        }
    }

    // ============================================================
    // UPLOAD / ĐỔI ẢNH ĐẠI DIỆN
    // ============================================================

    // [POST] Upload ảnh đại diện mới (Nhận dữ liệu dạng form-data)
    public function uploadAvatar()
    {
        AuthHelper::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ. Hãy dùng POST truyền form-data.', [], 405);
        }

        $id = AuthHelper::getUserId();

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            return $this->jsonResponse(false, 'Vui lòng chọn hoặc đính kèm tệp tin ảnh hợp lệ.', [], 400);
        }

        $file     = $_FILES['avatar'];
        $maxSize  = 2 * 1024 * 1024; // 2MB
        $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Kiểm tra MIME type thực sự
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($ext, $allowed) || !in_array($mimeType, $allowedMimes)) {
            return $this->jsonResponse(false, 'Định dạng file không được chấp nhận (Chỉ hỗ trợ JPG, PNG, GIF, WEBP).', [], 422);
        }

        if ($file['size'] > $maxSize) {
            return $this->jsonResponse(false, 'Kích thước tệp tin ảnh vượt quá giới hạn cho phép (Tối đa 2MB).', [], 422);
        }

        if (!is_dir($this->avatarDir)) {
            mkdir($this->avatarDir, 0777, true);
        }

        $fileName   = 'avatar_' . $id . '_' . time() . '.' . $ext;
        $targetPath = $this->avatarDir . $fileName;

        // Tiến hành xóa ảnh đại diện cũ ra khỏi server vật lý
        $currentUser = $this->userModel->findById($id);
        if ($currentUser && !empty($currentUser->avatar)) {
            $oldPath = $this->avatarDir . $currentUser->avatar;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $this->userModel->updateAvatar($id, $fileName);
            $_SESSION['user_avatar'] = $fileName;

            return $this->jsonResponse(true, 'Cập nhật ảnh đại diện mới thành công.', [
                'avatar_name' => $fileName,
                'avatar_url'  => '/' . $this->avatarDir . $fileName
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Hệ thống không thể di chuyển tệp tin tải lên.', [], 500);
        }
    }

    // ============================================================
    // QUẢN LÝ NGƯỜI DÙNG (ADMIN)
    // ============================================================

    // [GET] Danh sách toàn bộ người dùng (Phân trang và bộ lọc)
    public function adminList()
    {
        AuthHelper::requireAdmin();

        $search       = trim($_GET['search'] ?? '');
        $roleFilter   = trim($_GET['role']   ?? '');
        $statusFilter = trim($_GET['status'] ?? '');
        $page         = max(1, (int)($_GET['page'] ?? 1));
        $perPage      = 15;

        $users      = $this->userModel->getAll($search, $page, $perPage, $roleFilter, $statusFilter);
        $total      = $this->userModel->countAll($search, $roleFilter, $statusFilter);
        $totalPages = max(1, ceil($total / $perPage));

        return $this->jsonResponse(true, 'Lấy danh sách người dùng thành công.', [
            'users'      => $users,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total_items'  => $total,
                'total_pages'  => $totalPages
            ]
        ], 200);
    }

    // [GET] Xem chi tiết một người dùng bất kỳ qua ID
    public function adminDetail($id)
    {
        AuthHelper::requireAdmin();

        $user = $this->userModel->findById((int)$id);
        if (!$user) {
            return $this->jsonResponse(false, 'Người dùng cần tìm không tồn tại.', [], 404);
        }

        return $this->jsonResponse(true, 'Lấy chi tiết thông tin tài khoản thành công.', [
            'user' => $user
        ], 200);
    }

    // [POST/PATCH] Khóa / Mở khóa tài khoản người dùng
    public function toggleStatus($id)
    {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $id   = (int)$id;
        $user = $this->userModel->findById($id);

        if (!$user) {
            return $this->jsonResponse(false, 'Tài khoản người dùng không tồn tại.', [], 404);
        }

        // Chặn nghiệp vụ: Không cho phép quản trị viên tự khóa chính mình
        if ($id === AuthHelper::getUserId()) {
            return $this->jsonResponse(false, 'Hành động bị từ chối: Bạn không thể tự khóa tài khoản của chính mình.', [], 403);
        }

        $newStatus = $user->status === 'locked' ? 'active' : 'locked';
        $this->userModel->updateStatus($id, $newStatus);

        return $this->jsonResponse(true, 'Cập nhật trạng thái tài khoản thành công.', [
            'user_id'    => $id,
            'new_status' => $newStatus
        ], 200);
    }

    // [POST/PUT] Thay đổi quyền / vai trò thành viên
    public function updateRole()
    {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $input = $this->getRequestInput();
        $id    = (int)($_POST['id'] ?? $input['id'] ?? 0);
        $role  = $_POST['role'] ?? $input['role'] ?? 'user';

        if (!in_array($role, ['admin', 'user'])) {
            return $this->jsonResponse(false, 'Vai trò gán cho tài khoản không hợp lệ (Chỉ nhận admin hoặc user).', [], 422);
        }

        // Chặn nghiệp vụ: Không cho tự hạ quyền hạ cấp của chính mình
        if ($id === AuthHelper::getUserId()) {
            return $this->jsonResponse(false, 'Hành động bị từ chối: Bạn không thể tự thay đổi vai trò của chính mình.', [], 403);
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            return $this->jsonResponse(false, 'Tài khoản cần cập nhật vai trò không tồn tại.', [], 404);
        }

        $this->userModel->updateRole($id, $role);

        return $this->jsonResponse(true, 'Đã cập nhật phân quyền vai trò tài khoản thành công.', [
            'user_id'  => $id,
            'new_role' => $role
        ], 200);
    }
}