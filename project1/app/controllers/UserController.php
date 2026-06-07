<?php
require_once 'app/config/database.php';
require_once 'app/models/UserModel.php';
require_once 'app/helpers/AuthHelper.php';

class UserController
{
    private $db;
    private $userModel;
    private $avatarDir = 'public/uploads/avatars/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->db        = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    // ============================================================
    // HỒ SƠ CÁ NHÂN
    // ============================================================

    public function profile()
    {
        AuthHelper::requireLogin();
        $user = $this->userModel->findById(AuthHelper::getUserId());
        include 'app/views/user/profile.php';
    }

    public function updateProfile()
    {
        AuthHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /User/profile');
            exit;
        }

        $id       = AuthHelper::getUserId();
        $fullName = trim($_POST['full_name'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $errors   = [];

        if (empty($fullName)) $errors['full_name'] = 'Họ tên không được để trống.';
        if (!empty($phone) && !preg_match('/^[0-9]{9,11}$/', $phone)) {
            $errors['phone'] = 'Số điện thoại không hợp lệ.';
        }

        if (!empty($errors)) {
            $user = $this->userModel->findById($id);
            include 'app/views/user/profile.php';
            return;
        }

        if ($this->userModel->updateProfile($id, $fullName, $phone, $address)) {
            // Cập nhật session
            $_SESSION['user_name'] = $fullName;
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cập nhật hồ sơ thành công!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Cập nhật thất bại. Vui lòng thử lại.'];
        }

        header('Location: /User/profile');
        exit;
    }

    // ============================================================
    // UPLOAD / ĐỔI ẢNH ĐẠI DIỆN
    // ============================================================

    public function uploadAvatar()
    {
        AuthHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /User/profile');
            exit;
        }

        $id = AuthHelper::getUserId();

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vui lòng chọn file ảnh hợp lệ.'];
            header('Location: /User/profile');
            exit;
        }

        $file     = $_FILES['avatar'];
        $maxSize  = 2 * 1024 * 1024; // 2MB
        $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Kiểm tra MIME type thực sự (không chỉ dựa vào extension)
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($ext, $allowed) || !in_array($mimeType, $allowedMimes)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Chỉ chấp nhận file ảnh JPG, PNG, GIF, WEBP.'];
            header('Location: /User/profile');
            exit;
        }

        if ($file['size'] > $maxSize) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'File ảnh không được vượt quá 2MB.'];
            header('Location: /User/profile');
            exit;
        }

        if (!is_dir($this->avatarDir)) {
            mkdir($this->avatarDir, 0777, true);
        }

        $fileName   = 'avatar_' . $id . '_' . time() . '.' . $ext;
        $targetPath = $this->avatarDir . $fileName;

        // Xóa avatar cũ nếu có
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
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cập nhật ảnh đại diện thành công!'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tải ảnh lên thất bại.'];
        }

        header('Location: /User/profile');
        exit;
    }

    // ============================================================
    // QUẢN LÝ NGƯỜI DÙNG (ADMIN)
    // ============================================================

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

        include 'app/views/user/admin/list.php';
    }

    public function adminDetail($id)
    {
        AuthHelper::requireAdmin();
        $user = $this->userModel->findById((int)$id);
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Người dùng không tồn tại.'];
            header('Location: /User/adminList');
            exit;
        }
        include 'app/views/user/admin/detail.php';
    }

    // Khóa / Mở khóa tài khoản
    public function toggleStatus($id)
    {
        AuthHelper::requireAdmin();
        $id   = (int)$id;
        $user = $this->userModel->findById($id);

        if (!$user) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Người dùng không tồn tại.'];
            header('Location: /User/adminList');
            exit;
        }

        // Không cho khóa chính mình
        if ($id === AuthHelper::getUserId()) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Bạn không thể tự khóa tài khoản của mình.'];
            header('Location: /User/adminList');
            exit;
        }

        $newStatus = $user->status === 'locked' ? 'active' : 'locked';
        $this->userModel->updateStatus($id, $newStatus);

        $msg = $newStatus === 'locked'
            ? 'Đã khóa tài khoản <strong>' . htmlspecialchars($user->full_name) . '</strong>.'
            : 'Đã mở khóa tài khoản <strong>' . htmlspecialchars($user->full_name) . '</strong>.';

        $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
        header('Location: /User/adminList');
        exit;
    }

    // Đổi vai trò Admin/User
    public function updateRole()
    {
        AuthHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /User/adminList');
            exit;
        }

        $id   = (int)($_POST['id'] ?? 0);
        $role = $_POST['role'] ?? 'user';

        if (!in_array($role, ['admin', 'user'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vai trò không hợp lệ.'];
            header('Location: /User/adminList');
            exit;
        }

        // Không cho đổi vai trò của chính mình
        if ($id === AuthHelper::getUserId()) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Bạn không thể thay đổi vai trò của chính mình.'];
            header('Location: /User/adminList');
            exit;
        }

        $this->userModel->updateRole($id, $role);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã cập nhật vai trò người dùng.'];
        header('Location: /User/adminDetail/' . $id);
        exit;
    }
}
