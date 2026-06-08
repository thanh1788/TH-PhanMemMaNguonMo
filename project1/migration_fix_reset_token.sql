-- ============================================================
-- FIX: Mở rộng cột reset_token từ VARCHAR(64) lên VARCHAR(128)
-- Chạy file này trong phpMyAdmin hoặc MySQL CLI nếu bảng đã tồn tại
-- ============================================================

USE my_store;

ALTER TABLE `users`
    MODIFY COLUMN `reset_token` VARCHAR(128) DEFAULT NULL;

-- Xác nhận kết quả
SELECT COLUMN_NAME, COLUMN_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'my_store' 
  AND TABLE_NAME = 'users' 
  AND COLUMN_NAME = 'reset_token';
