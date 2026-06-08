<?php
/**
 * AuthHelper - Kiểm tra phân quyền và trạng thái đăng nhập cho RESTful API
 */
class AuthHelper
{
    /**
     * Hàm helper nội bộ xuất phản hồi JSON lỗi bảo mật và dừng chương trình
     */
    private static function jsonAuthError(string $message, int $statusCode): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'message' => $message
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

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
     * Yêu cầu đăng nhập - Trả về JSON 401 Unauthorized thay vì chuyển hướng HTML
     */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            self::jsonAuthError('Yêu cầu từ chối: Bạn cần đăng nhập để thực hiện hành động này.', 401);
        }
    }

    /**
     * Yêu cầu quyền Admin - Trả về JSON 403 Forbidden thay vì chuyển hướng HTML
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            self::jsonAuthError('Quyền truy cập bị từ chối: Hành động này chỉ dành cho tài khoản Quản trị viên (Admin).', 403);
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
     * Lấy thông tin người dùng từ session dưới dạng mảng để phản hồi API
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
     * Đăng nhập user vào session (Dùng khi xử lý API đăng nhập thành công hoặc Auto-login)
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