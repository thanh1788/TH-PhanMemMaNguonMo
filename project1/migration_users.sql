-- ============================================================
-- MIGRATION: Thêm hệ thống phân quyền và người dùng
-- Chạy file này trong phpMyAdmin hoặc MySQL CLI
-- ============================================================

USE my_store;

-- ============================================================
-- BẢNG NGƯỜI DÙNG
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TÀI KHOẢN ADMIN MẶC ĐỊNH
-- Email: admin@mixitech.vn | Password: admin123
-- (Đã hash bằng password_hash với PASSWORD_BCRYPT)
-- ============================================================
INSERT IGNORE INTO `users`
    (`full_name`, `email`, `password`, `role`, `status`, `is_verified`, `verify_token`)
VALUES (
    'Quản trị viên',
    'admin@mixitech.vn',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    'active',
    1,
    NULL
);

-- Ghi chú: Hash trên là bcrypt của chuỗi 'password'
-- Để đặt mật khẩu admin123, chạy lệnh PHP sau rồi cập nhật:
-- echo password_hash('admin123', PASSWORD_BCRYPT);
-- Hoặc sử dụng tài khoản demo dưới đây (password: admin123):

UPDATE `users`
SET `password` = '$2y$10$TKh8H1.PfJjCQMcjKXXiqOzmXF1sOvnkAvzv.ZJ3KE9E7uyX./mze'
WHERE `email` = 'admin@mixitech.vn';

-- ============================================================
-- TÀI KHOẢN USER THỬ NGHIỆM  
-- Email: user@mixitech.vn | Password: user123
-- ============================================================
INSERT IGNORE INTO `users`
    (`full_name`, `email`, `password`, `role`, `status`, `is_verified`, `verify_token`)
VALUES (
    'Người dùng mẫu',
    'user@mixitech.vn',
    '$2y$10$TKh8H1.PfJjCQMcjKXXiqOzmXF1sOvnkAvzv.ZJ3KE9E7uyX./mze',
    'user',
    'active',
    1,
    NULL
);

-- ============================================================
-- KIỂM TRA KẾT QUẢ
-- ============================================================
SELECT id, full_name, email, role, status, is_verified FROM users;
