<?php
/**
 * AuthHelper - Kiểm tra phân quyền và trạng thái đăng nhập
 */
class AuthHelper
{
    /**
     * Kiểm tra người dùng đã đăng nhập chưa
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Kiểm tra người dùng có vai trò Admin không
     */
    public static function isAdmin(): bool
    {
        return self::isLoggedIn() && ($_SESSION['user_role'] ?? '') === 'admin';
    }

    /**
     * Yêu cầu đăng nhập - chuyển hướng nếu chưa đăng nhập
     */
    public static function requireLogin(string $redirect = '/Auth/login'): void
    {
        if (!self::isLoggedIn()) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Vui lòng đăng nhập để tiếp tục.'];
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/';
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * Yêu cầu quyền Admin
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Bạn không có quyền truy cập trang này.'];
            header('Location: /');
            exit;
        }
    }

    /**
     * Lấy ID người dùng hiện tại
     */
    public static function getUserId(): ?int
    {
        return self::isLoggedIn() ? (int)$_SESSION['user_id'] : null;
    }

    /**
     * Lấy thông tin người dùng từ session
     */
    public static function getUser(): ?array
    {
        if (!self::isLoggedIn()) return null;
        return [
            'id'     => $_SESSION['user_id']     ?? null,
            'name'   => $_SESSION['user_name']   ?? '',
            'email'  => $_SESSION['user_email']  ?? '',
            'role'   => $_SESSION['user_role']   ?? 'user',
            'avatar' => $_SESSION['user_avatar'] ?? null,
        ];
    }

    /**
     * Đăng nhập user vào session
     */
    public static function loginSession(object $user): void
    {
        $_SESSION['user_id']     = $user->id;
        $_SESSION['user_name']   = $user->full_name;
        $_SESSION['user_email']  = $user->email;
        $_SESSION['user_role']   = $user->role;
        $_SESSION['user_avatar'] = $user->avatar ?? null;
    }

    /**
     * Xóa session đăng nhập
     */
    public static function logout(): void
    {
        unset(
            $_SESSION['user_id'],
            $_SESSION['user_name'],
            $_SESSION['user_email'],
            $_SESSION['user_role'],
            $_SESSION['user_avatar']
        );
    }
}
