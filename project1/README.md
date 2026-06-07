# MixiTech Store — Hệ thống bán lẻ công nghệ

## Hướng dẫn cài đặt

### Yêu cầu hệ thống
- PHP >= 8.0
- MySQL >= 5.7
- Apache/Nginx với mod_rewrite
- XAMPP / Laragon / WAMP

---

### Bước 1 — Import cơ sở dữ liệu

Mở phpMyAdmin và chạy file `my_store.sql`:

```
http://localhost/phpmyadmin
```

→ **New** → đặt tên `my_store` → Import → chọn `my_store.sql` → Go

---

### Bước 2 — Cấu hình database

Mở `app/config/database.php` và điền thông tin:

```php
private $host     = "localhost";
private $db_name  = "my_store";
private $username = "root";
private $password = "";        // Mật khẩu MySQL của bạn
```

---

### Bước 3 — Tạo tài khoản hệ thống

Truy cập URL sau để tạo bảng `users` và tài khoản mặc định:

```
http://localhost/project1/setup_users.php
```

Sau khi chạy xong sẽ tạo:

| Vai trò | Email                 | Mật khẩu |
|---------|-----------------------|----------|
| Admin   | admin@mixitech.vn     | admin123 |
| User    | user@mixitech.vn      | user123  |

> ⚠️ **Xóa file `setup_users.php` và `generate_hash.php` sau khi hoàn tất!**

---

### Bước 4 — Cấu hình Apache (XAMPP)

Đảm bảo `mod_rewrite` được bật và file `.htaccess` hoạt động.

Trong `httpd.conf`, kiểm tra:
```apache
AllowOverride All
```

---

## Cấu trúc thư mục

```
project1/
├── app/
│   ├── config/
│   │   └── database.php          # Kết nối database
│   ├── controllers/
│   │   ├── AuthController.php    # Đăng nhập, đăng ký, quên mật khẩu
│   │   ├── UserController.php    # Hồ sơ, avatar, quản lý users (Admin)
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── CartController.php
│   │   └── OrderController.php
│   ├── helpers/
│   │   ├── AuthHelper.php        # Kiểm tra đăng nhập & phân quyền
│   │   └── MailHelper.php        # Gửi email (log vào app/logs/emails/)
│   ├── models/
│   │   ├── UserModel.php
│   │   ├── ProductModel.php
│   │   ├── CategoryModel.php
│   │   └── OrderModel.php
│   ├── views/
│   │   ├── auth/                 # Đăng nhập, đăng ký, quên/đặt lại/đổi mật khẩu
│   │   ├── user/                 # Hồ sơ cá nhân, admin/list, admin/detail
│   │   ├── product/
│   │   ├── category/
│   │   ├── cart/
│   │   ├── order/
│   │   ├── errors/               # Trang 404
│   │   └── shares/               # Header, footer dùng chung
│   └── logs/emails/              # Email mock (chỉ dùng ở localhost)
├── public/uploads/
│   ├── products/                 # Ảnh sản phẩm
│   └── avatars/                  # Ảnh đại diện người dùng
├── index.php                     # Front controller (router)
├── .htaccess                     # URL rewriting
├── my_store.sql                  # Schema + dữ liệu mẫu
├── setup_users.php               # Script tạo bảng users (xóa sau khi dùng)
└── README.md
```

---

## Tính năng hệ thống phân quyền

### Xác thực
| Tính năng | URL |
|-----------|-----|
| Đăng nhập | `/Auth/login` |
| Đăng ký | `/Auth/register` |
| Đăng xuất | `/Auth/logout` |
| Quên mật khẩu | `/Auth/forgotPassword` |
| Đặt lại mật khẩu | `/Auth/resetPassword/{token}` |
| Đổi mật khẩu | `/Auth/changePassword` |
| Xác thực email | `/Auth/verify/{token}` |

### Hồ sơ người dùng
| Tính năng | URL |
|-----------|-----|
| Xem & cập nhật hồ sơ | `/User/profile` |
| Upload ảnh đại diện | POST `/User/uploadAvatar` |

### Quản trị (Admin only)
| Tính năng | URL |
|-----------|-----|
| Danh sách người dùng | `/User/adminList` |
| Chi tiết người dùng | `/User/adminDetail/{id}` |
| Khóa/Mở khóa | `/User/toggleStatus/{id}` |
| Đổi vai trò | POST `/User/updateRole` |
| Quản lý sản phẩm | `/Product/add`, `/Product/edit/{id}`, `/Product/delete/{id}` |
| Quản lý danh mục | `/Category` |
| Quản lý đơn hàng | `/Order/admin` |

---

## Email trong môi trường localhost

Vì PHP `mail()` không gửi được trên localhost, tất cả email được **lưu thành file HTML** trong thư mục:

```
app/logs/emails/
```

Mở file `.html` trong trình duyệt để xem nội dung email (link xác thực, link reset mật khẩu...).

---

## Phân quyền

| Quyền | Admin | User (đã đăng nhập) | Khách |
|-------|:-----:|:-------------------:|:-----:|
| Xem sản phẩm | ✅ | ✅ | ✅ |
| Thêm/Sửa/Xóa sản phẩm | ✅ | ❌ | ❌ |
| Quản lý danh mục | ✅ | ❌ | ❌ |
| Quản lý đơn hàng | ✅ | ❌ | ❌ |
| Quản lý người dùng | ✅ | ❌ | ❌ |
| Hồ sơ cá nhân | ✅ | ✅ | ❌ |
| Đổi mật khẩu | ✅ | ✅ | ❌ |
| Đặt hàng | ✅ | ✅ | ✅ |
