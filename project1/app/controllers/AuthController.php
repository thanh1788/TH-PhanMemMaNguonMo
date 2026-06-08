<?php
require_once 'app/config/database.php';
require_once 'app/models/UserModel.php';
require_once 'app/helpers/AuthHelper.php';
require_once 'app/helpers/MailHelper.php';

class AuthController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->db        = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    // ============================================================
    // ĐĂNG KÝ
    // ============================================================

    public function register()
    {
        if (AuthHelper::isLoggedIn()) { header('Location: /'); exit; }
        include 'app/views/auth/register.php';
    }

    public function saveRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /Auth/register'); exit; }

        $full_name        = trim($_POST['full_name'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $password         = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $errors           = [];

        // Validate
        if (empty($full_name))                              $errors['full_name'] = 'Họ tên không được để trống.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))     $errors['email']    = 'Email không hợp lệ.';
        if (strlen($password) < 6)                          $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        if ($password !== $password_confirm)                $errors['password_confirm'] = 'Mật khẩu xác nhận không khớp.';

        if (empty($errors) && $this->userModel->findByEmail($email)) {
            $errors['email'] = 'Email này đã được đăng ký.';
        }

        if (!empty($errors)) {
            include 'app/views/auth/register.php';
            return;
        }

        $userId = $this->userModel->register($full_name, $email, $password);

        if ($userId) {
            $user = $this->userModel->findById($userId);
            // Gửi email xác thực
            MailHelper::sendVerification($email, $full_name, $user->verify_token);
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản. (Xem file log trong app/logs/emails/ nếu dùng localhost)'
            ];
            header('Location: /Auth/login');
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Đăng ký thất bại. Vui lòng thử lại.'];
            header('Location: /Auth/register');
        }
        exit;
    }

    // ============================================================
    // XÁC THỰC EMAIL
    // ============================================================

    public function verify($token)
    {
        $user = $this->userModel->findByVerifyToken($token);

        if (!$user) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Link xác thực không hợp lệ hoặc đã được sử dụng.'];
            header('Location: /Auth/login');
            exit;
        }

        $this->userModel->verifyEmail($user->id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Xác thực email thành công! Bạn có thể đăng nhập.'];
        header('Location: /Auth/login');
        exit;
    }

    // ============================================================
    // ĐĂNG NHẬP
    // ============================================================

    public function login()
    {
        if (AuthHelper::isLoggedIn()) { header('Location: /'); exit; }
        include 'app/views/auth/login.php';
    }

    public function doLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /Auth/login'); exit; }

        $email       = trim($_POST['email'] ?? '');
        $password    = $_POST['password'] ?? '';
        $remember    = isset($_POST['remember']);
        $errors      = [];

        if (empty($email))    $errors['email']    = 'Vui lòng nhập email.';
        if (empty($password)) $errors['password'] = 'Vui lòng nhập mật khẩu.';

        if (!empty($errors)) {
            include 'app/views/auth/login.php';
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            $errors['general'] = 'Email hoặc mật khẩu không đúng.';
            include 'app/views/auth/login.php';
            return;
        }

        if ($user->status === 'locked') {
            $errors['general'] = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
            include 'app/views/auth/login.php';
            return;
        }

        if (!$user->is_verified) {
            $errors['general'] = 'Tài khoản chưa được xác thực email. Vui lòng kiểm tra hộp thư.';
            include 'app/views/auth/login.php';
            return;
        }

        // Lưu session
        AuthHelper::loginSession($user);

        // Remember Me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->setRememberToken($user->id, $token);
            setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
        }

        // Chuyển hướng về trang trước nếu có (chỉ cho phép redirect nội bộ)
        $redirect = $_SESSION['redirect_after_login'] ?? '/';
        unset($_SESSION['redirect_after_login']);
        // Bảo mật: chỉ redirect nếu là đường dẫn nội bộ (bắt đầu bằng /)
        if (!str_starts_with($redirect, '/') || str_starts_with($redirect, '//')) {
            $redirect = '/';
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đăng nhập thành công! Chào mừng, <strong>' . htmlspecialchars($user->full_name) . '</strong>.'];
        header('Location: ' . $redirect);
        exit;
    }

    // ============================================================
    // ĐĂNG XUẤT
    // ============================================================

    public function logout()
    {
        $userId = AuthHelper::getUserId();
        if ($userId) {
            $this->userModel->clearRememberToken($userId);
        }

        AuthHelper::logout();
        setcookie('remember_token', '', time() - 3600, '/');

        $_SESSION['flash'] = ['type' => 'info', 'message' => 'Bạn đã đăng xuất thành công.'];
        header('Location: /Auth/login');
        exit;
    }

    // ============================================================
    // QUÊN MẬT KHẨU
    // ============================================================

    public function forgotPassword()
    {
        include 'app/views/auth/forgot_password.php';
    }

    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /Auth/forgotPassword'); exit; }

        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
            include 'app/views/auth/forgot_password.php';
            return;
        }

        $user = $this->userModel->findByEmail($email);

        // Không tiết lộ email có tồn tại không (bảo mật)
        if ($user && $user->status !== 'locked') {
            $token = bin2hex(random_bytes(32));
            $this->userModel->setResetToken($user->id, $token);
            MailHelper::sendResetPassword($email, $user->full_name, $token);
        }

        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi link đặt lại mật khẩu. Vui lòng kiểm tra hộp thư (hoặc xem app/logs/emails/).'
        ];
        header('Location: /Auth/forgotPassword');
        exit;
    }

    // ============================================================
    // ĐẶT LẠI MẬT KHẨU
    // ============================================================

    public function resetPassword($token = '')
    {
        $user = $this->userModel->findByResetToken($token);
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.'];
            header('Location: /Auth/forgotPassword');
            exit;
        }
        include 'app/views/auth/reset_password.php';
    }

    public function doResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /'); exit; }

        $token            = $_POST['token'] ?? '';
        $password         = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $errors           = [];

        $user = $this->userModel->findByResetToken($token);
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.'];
            header('Location: /Auth/forgotPassword');
            exit;
        }

        if (strlen($password) < 6) $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        if ($password !== $password_confirm) $errors['password_confirm'] = 'Mật khẩu xác nhận không khớp.';

        if (!empty($errors)) {
            include 'app/views/auth/reset_password.php';
            return;
        }

        $this->userModel->updatePassword($user->id, $password);
        $this->userModel->clearResetToken($user->id);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mật khẩu đã được đặt lại thành công! Vui lòng đăng nhập.'];
        header('Location: /Auth/login');
        exit;
    }

    // ============================================================
    // ĐỔI MẬT KHẨU (Đã đăng nhập)
    // ============================================================

    public function changePassword()
    {
        AuthHelper::requireLogin();
        include 'app/views/auth/change_password.php';
    }

    public function doChangePassword()
    {
        AuthHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /Auth/changePassword'); exit; }

        $currentPassword  = $_POST['current_password'] ?? '';
        $newPassword      = $_POST['new_password'] ?? '';
        $confirmPassword  = $_POST['confirm_password'] ?? '';
        $errors           = [];

        $user = $this->userModel->findById(AuthHelper::getUserId());

        if (!password_verify($currentPassword, $user->password)) {
            $errors['current_password'] = 'Mật khẩu hiện tại không đúng.';
        }
        if (strlen($newPassword) < 6) {
            $errors['new_password'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp.';
        }

        if (!empty($errors)) {
            include 'app/views/auth/change_password.php';
            return;
        }

        $this->userModel->updatePassword($user->id, $newPassword);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đổi mật khẩu thành công!'];
        header('Location: /User/profile');
        exit;
    }

}
