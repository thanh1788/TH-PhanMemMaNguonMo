-- ============================================================
-- MixiTech Store - Database Setup Script
-- ============================================================

CREATE DATABASE IF NOT EXISTS my_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE my_store;

-- ============================================================
-- 1. BẢNG DANH MỤC
-- ============================================================
CREATE TABLE IF NOT EXISTS `category` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(100) NOT NULL,
    `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. BẢNG SẢN PHẨM
-- ============================================================
CREATE TABLE IF NOT EXISTS `product` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price`       DECIMAL(15,2) NOT NULL,
    `image`       VARCHAR(255) DEFAULT NULL,
    `category_id` INT,
    FOREIGN KEY (`category_id`) REFERENCES `category`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. BẢNG ĐƠN HÀNG
-- ============================================================
CREATE TABLE IF NOT EXISTS `orders` (
    `id`            INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(255)  NOT NULL,
    `phone`         VARCHAR(20)   NOT NULL,
    `address`       TEXT          NOT NULL,
    `total_price`   DECIMAL(15,2) NOT NULL,
    `status`        ENUM('pending','confirmed','shipping','delivered','cancelled')
                    NOT NULL DEFAULT 'pending',
    `note`          TEXT          DEFAULT NULL,
    `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. BẢNG CHI TIẾT ĐƠN HÀNG
-- ============================================================
CREATE TABLE IF NOT EXISTS `order_details` (
    `id`           INT AUTO_INCREMENT PRIMARY KEY,
    `order_id`     INT           NOT NULL,
    `product_id`   INT           NOT NULL,
    `product_name` VARCHAR(255)  NOT NULL DEFAULT '',
    `price`        DECIMAL(15,2) NOT NULL,
    `quantity`     INT           NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. DỮ LIỆU MẪU - DANH MỤC (id cố định để INSERT product dễ)
-- ============================================================
INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Điện thoại',    'Các dòng smartphone mới nhất'),
(2, 'Laptop',        'Máy tính xách tay làm việc và chơi game'),
(3, 'Máy tính bảng', 'iPad và các dòng Tablet Android'),
(4, 'Phụ kiện',      'Tai nghe, sạc dự phòng, cáp sạc');

-- ============================================================
-- 6. DỮ LIỆU MẪU - SẢN PHẨM
-- Lưu ý: các file ảnh phải có sẵn trong public/uploads/products/
-- ============================================================
INSERT INTO `product` (`name`, `description`, `price`, `category_id`, `image`) VALUES
-- Điện thoại (category_id = 1)
(
    'iPhone 15 Pro Max 256GB',
    'Chip A17 Pro hiệu năng vượt trội, khung Titan siêu bền, camera 48MP với zoom quang học 5x. Màn hình Super Retina XDR 6.7 inch, pin cả ngày dài.',
    29490000,
    1,
    'iphone-15-pro-max.webp'
),
(
    'Samsung Galaxy S24 Ultra',
    'Camera 200MP với AI tích hợp thông minh, bút S-Pen tích hợp, chip Snapdragon 8 Gen 3. Màn hình Dynamic AMOLED 6.8 inch 120Hz.',
    26990000,
    1,
    'ss-s24-ultra-xam-222_1.webp'
),

-- Laptop (category_id = 2)
(
    'MacBook Air M3 13 inch',
    'Chip Apple M3 mạnh mẽ, thiết kế mỏng nhẹ đẳng cấp chỉ 1.24kg, màn hình Liquid Retina 13.6 inch, pin lên đến 18 giờ.',
    27990000,
    2,
    'mac-air-m3.webp'
),
(
    'Laptop ASUS ROG Strix G16',
    'Laptop Gaming RTX 4060 8GB, CPU Intel Core i7-13650HX, RAM 16GB DDR5, màn hình 16 inch 165Hz FHD.',
    34490000,
    2,
    'asus-rog-g16.webp'
),

-- Máy tính bảng (category_id = 3)
(
    'iPad Air 6 M2 11 inch',
    'Chip Apple M2 hiệu năng cao, hỗ trợ Apple Pencil Pro và Magic Keyboard, màn hình Liquid Retina 11 inch.',
    16490000,
    3,
    'ipad-air-6.jpg'
),
(
    'Samsung Galaxy Tab S9 FE',
    'Kháng nước IP68, kèm bút S-Pen trong hộp, màn hình 10.9 inch 90Hz, pin 8000mAh.',
    9990000,
    3,
    'tab-s9-fe.webp'
),

-- Phụ kiện (category_id = 4)
(
    'AirPods Pro 2 USB-C',
    'Chống ồn chủ động thế hệ 2, âm thanh không gian Lossless, cổng sạc USB-C, pin lên đến 30 giờ với hộp sạc.',
    5890000,
    4,
    'air-pod-pro.webp'
),
(
    'Sạc Anker Prime 200W',
    'Sạc dự phòng 20000mAh, công suất 200W, sạc nhanh cho Laptop và điện thoại, 3 cổng USB-C + 1 USB-A.',
    2350000,
    4,
    'sac-du-phong-anker.webp'
);

-- ============================================================
-- MIGRATION: Thêm cột status & note vào orders (nếu DB đã tồn tại)
-- Chạy phần này nếu bạn đã có bảng orders từ trước
-- ============================================================
-- ALTER TABLE `orders`
--   ADD COLUMN `status` ENUM('pending','confirmed','shipping','delivered','cancelled')
--               NOT NULL DEFAULT 'pending' AFTER `total_price`,
--   ADD COLUMN `note` TEXT DEFAULT NULL AFTER `status`,
--   ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;
