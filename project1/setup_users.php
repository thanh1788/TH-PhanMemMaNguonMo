<?php
/**
 * Setup script - Tạo bảng users và tài khoản mặc định
 * Truy cập: http://localhost/your-project/setup_users.php
 * SAU KHI CHẠY XONG, XÓA FILE NÀY ĐI!
 */

require_once 'app/config/database.php';

$db = (new Database())->getConnection();

echo '<meta charset="UTF-8">';
echo '<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} .ok{color:green;} .err{color:red;} pre{background:#1a1a1a;color:#0f0;padding:16px;border-radius:8px;}</style>';
echo '<h2>🔧 Setup - Hệ thống phân quyền MixiTech</h2>';

// 1. Tạo bảng users
$sql = "CREATE TABLE IF NOT EXISTS `users` (
    `id`             INT AUTO_INCREMENT PRIMARY KEY,
    `full_name`      VARCHAR(100)  NOT NULL,
    `email`          VARCHAR(150)  NOT NULL UNIQUE,
    `password`       VARCHAR(255)  NOT NULL,
    `phone`          VARCHAR(20)   DEFAULT NULL,
    `address`        TEXT          DEFAULT NULL,
    `avatar`         VARCHAR(255)  DEFAULT NULL,
    `role`           ENUM('admin','user') NOT NULL DEFAULT 'user',
    `status`         ENUM('active','locked') NOT NULL DEFAULT 'active',
    `is_verified`    TINYINT(1)   NOT NULL DEFAULT 0,
    `verify_token`   VARCHAR(64)  DEFAULT NULL,
    `reset_token`    VARCHAR(64)  DEFAULT NULL,
    `reset_expires`  DATETIME     DEFAULT NULL,
    `remember_token` VARCHAR(64)  DEFAULT NULL,
    `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_email`          (`email`),
    INDEX `idx_remember_token` (`remember_token`),
    INDEX `idx_verify_token`   (`verify_token`),
    INDEX `idx_reset_token`    (`reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

try {
    $db->exec($sql);
    echo '<p class="ok">✅ Bảng <code>users</code> đã sẵn sàng.</p>';
} catch (PDOException $e) {
    echo '<p class="err">❌ Lỗi tạo bảng: ' . $e->getMessage() . '</p>';
    exit;
}

// 2. Tạo tài khoản Admin
$adminHash = password_hash('admin123', PASSWORD_BCRYPT);
$stmt = $db->prepare(
    "INSERT IGNORE INTO `users` (full_name, email, password, role, status, is_verified)
     VALUES ('Quản trị viên', 'admin@mixitech.vn', :hash, 'admin', 'active', 1)"
);
$stmt->execute([':hash' => $adminHash]);
echo '<p class="ok">✅ Tài khoản Admin: <strong>admin@mixitech.vn</strong> / mật khẩu: <strong>admin123</strong></p>';

// 3. Tạo tài khoản User mẫu
$userHash = password_hash('user123', PASSWORD_BCRYPT);
$stmt = $db->prepare(
    "INSERT IGNORE INTO `users` (full_name, email, password, role, status, is_verified)
     VALUES ('Người dùng mẫu', 'user@mixitech.vn', :hash, 'user', 'active', 1)"
);
$stmt->execute([':hash' => $userHash]);
echo '<p class="ok">✅ Tài khoản User: <strong>user@mixitech.vn</strong> / mật khẩu: <strong>user123</strong></p>';

// 4. Tạo thư mục uploads/avatars
if (!is_dir('public/uploads/avatars')) {
    mkdir('public/uploads/avatars', 0777, true);
    echo '<p class="ok">✅ Đã tạo thư mục <code>public/uploads/avatars/</code></p>';
} else {
    echo '<p class="ok">✅ Thư mục <code>public/uploads/avatars/</code> đã tồn tại.</p>';
}

// 5. Tạo thư mục logs/emails
if (!is_dir('app/logs/emails')) {
    mkdir('app/logs/emails', 0777, true);
    echo '<p class="ok">✅ Đã tạo thư mục <code>app/logs/emails/</code> (lưu email mock)</p>';
}

// 6. Kiểm tra danh sách users
$stmt = $db->query("SELECT id, full_name, email, role, status FROM users ORDER BY id");
$users = $stmt->fetchAll(PDO::FETCH_OBJ);

echo '<h3>📋 Danh sách tài khoản hiện có:</h3>';
echo '<table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse;background:#fff;">';
echo '<tr style="background:#d70018;color:#fff;"><th>ID</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Trạng thái</th></tr>';
foreach ($users as $u) {
    echo "<tr><td>{$u->id}</td><td>{$u->full_name}</td><td>{$u->email}</td><td>{$u->role}</td><td>{$u->status}</td></tr>";
}
echo '</table>';

echo '<br><div style="background:#fff3cd;border:1px solid #ffc107;padding:16px;border-radius:8px;">';
echo '<strong>⚠️ Quan trọng:</strong> Hãy xóa file <code>setup_users.php</code> và <code>generate_hash.php</code> sau khi setup xong!';
echo '</div>';

echo '<br><a href="/" style="background:#d70018;color:#fff;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:bold;">→ Về trang chủ</a>';
echo '&nbsp;&nbsp;<a href="/Auth/login" style="background:#1a1a1a;color:#fff;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:bold;">→ Đăng nhập ngay</a>';
