<?php
// Không nên require cứng ProductModel ở đây vì CategoryController không cần nó.
// Các Model nên được require bên trong Controller hoặc dùng Autoload.

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// 1. XỬ LÝ CONTROLLER & ACTION MẶC ĐỊNH
if (empty($url[0])) {
    $controllerName = 'ProductController';
    $action = 'index';
} else {
    $controllerName = ucfirst($url[0]) . 'Controller';
    // Kiểm tra nếu $url[1] trống hoặc không tồn tại thì mặc định là 'index'
    $action = (isset($url[1]) && $url[1] !== '') ? $url[1] : 'index';
}

// 2. KIỂM TRA FILE CONTROLLER
$controllerPath = 'app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerPath)) {
    // Thay vì die, bạn có thể chuyển hướng về trang 404 hoặc trang chủ
    header("HTTP/1.0 404 Not Found");
    die('Lỗi: Controller <b>' . $controllerName . '</b> không tồn tại tại ' . $controllerPath);
}

require_once $controllerPath;

// 3. KHỞI TẠO CONTROLLER
if (!class_exists($controllerName)) {
    die('Lỗi: Lớp <b>' . $controllerName . '</b> không được định nghĩa trong file.');
}

$controller = new $controllerName();

// 4. KIỂM TRA PHƯƠNG THỨC (ACTION)
// Nếu url là /Category/list nhưng trong Controller bạn đặt tên hàm là index hoặc list
// Bạn cần chắc chắn tên hàm trong Controller khớp với $url[1]
if (!method_exists($controller, $action)) {
    die('Lỗi: Hành động <b>' . $action . '</b> không tồn tại trong ' . $controllerName);
}

// 5. GỌI ACTION & TRUYỀN THAM SỐ (ID, v.v.)
// Ví dụ: /Product/edit/5 -> thì 5 sẽ được truyền vào hàm edit($id)
call_user_func_array([$controller, $action], array_slice($url, 2));