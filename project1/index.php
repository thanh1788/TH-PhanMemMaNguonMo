<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. LẤY VÀ LÀM SẠCH URL
$urlInput = $_GET['url'] ?? '';
$urlInput = rtrim($urlInput, '/');
$urlInput = filter_var($urlInput, FILTER_SANITIZE_URL);

// Nếu URL trống (Trang chủ), tạo mảng mặc định, ngược lại thì cắt chuỗi thành mảng
$url = empty($urlInput) ? [] : explode('/', $urlInput);

// 2. XỬ LÝ CONTROLLER & ACTION MẶC ĐỊNH
if (empty($url[0])) {
    $controllerName = 'ProductController';
    $action = 'index';
} else {
    $controllerName = ucfirst($url[0]) . 'Controller';
    // Kiểm tra nếu hành động không tồn tại hoặc trống thì mặc định là 'index'
    $action = (isset($url[1]) && $url[1] !== '') ? $url[1] : 'index';
}

// 3. KIỂM TRA FILE CONTROLLER TỒN TẠI
$controllerPath = 'app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerPath)) {
    header("HTTP/1.0 404 Not Found");
    die('Lỗi 404: Controller <b>' . $controllerName . '</b> không tồn tại.');
}

require_once $controllerPath;

// 4. KHỞI TẠO CONTROLLER
if (!class_exists($controllerName)) {
    die('Lỗi: Lớp <b>' . $controllerName . '</b> không được định nghĩa trong file.');
}

$controller = new $controllerName();

// 5. KIỂM TRA PHƯƠNG THỨC (ACTION) TRONG CONTROLLER
if (!method_exists($controller, $action)) {
    die('Lỗi: Hành động <b>' . $action . '</b> không tồn tại trong ' . $controllerName);
}

// 6. GỌI ACTION & TRUYỀN THAM SỐ AN TOÀN
// Đảm bảo lấy mảng tham số từ vị trí thứ 2 trở đi, nếu không có sẽ trả về mảng rỗng []
$params = count($url) > 2 ? array_slice($url, 2) : [];

call_user_func_array([$controller, $action], $params);