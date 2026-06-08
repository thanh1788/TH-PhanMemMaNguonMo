<?php
// ============================================================
// 1. CẤU HÌNH CORS VÀ TIÊU ĐỀ PHẢN HỒI API (BẮT BUỘC)
// ============================================================
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept");
header("Access-Control-Allow-Credentials: true"); 

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ============================================================
// 2. KHỞI TẠO SESSION & LOGIC AUTO-LOGIN CỦA BẠN
// ============================================================
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) && !empty($_COOKIE['remember_token'])) {
    require_once 'app/config/database.php';
    require_once 'app/models/UserModel.php';
    require_once 'app/helpers/AuthHelper.php';
    $__db    = (new Database())->getConnection();
    $__um    = new UserModel($__db);
    $__user  = $__um->findByRememberToken($_COOKIE['remember_token']);
    if ($__user && $__user->status === 'active' && $__user->is_verified) {
        AuthHelper::loginSession($__user);
    }
    unset($__db, $__um, $__user);
}

// ============================================================
// 3. LẤY VÀ LÀM SẠCH URL
// ============================================================
$urlInput = $_GET['url'] ?? '';
$urlInput = rtrim($urlInput, '/');
$urlInput = filter_var($urlInput, FILTER_SANITIZE_URL);

$url = empty($urlInput) ? [] : explode('/', $urlInput);

// ============================================================
// 4. XỬ LÝ CONTROLLER & ACTION MẶC ĐỊNH (ĐÃ CẬP NHẬT)
// ============================================================
if (empty($url[0])) {
    // Mặc định gọi đến ProductApiController khi vào trang gốc
    $controllerName = 'ProductApiController'; 
    $action = 'index';
} else {
    // Tự động nối chuỗi 'ApiController' thay vì 'Controller' cũ
    $controllerName = ucfirst($url[0]) . 'ApiController'; 
    $action = (isset($url[1]) && $url[1] !== '') ? $url[1] : 'index';
}

// ============================================================
// 5. KIỂM TRA FILE CONTROLLER TỒN TẠI
// ============================================================
$controllerPath = 'app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerPath)) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => "Tệp tin API Controller '{$controllerName}.php' không tồn tại trên hệ thống."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once $controllerPath;

// ============================================================
// 6. KIỂM TRA LỚP (CLASS) CONTROLLER TỒN TẠI
// ============================================================
if (!class_exists($controllerName)) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => "Lớp API (Class) '{$controllerName}' không được định nghĩa trong file."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$controller = new $controllerName();

// ============================================================
// 7. KIỂM TRA PHƯƠNG THỨC (ACTION) TRONG CONTROLLER
// ============================================================
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => "Hành động (Action) '{$action}' không tồn tại trong bộ điều khiển '{$controllerName}'."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ============================================================
// 8. GỌI ACTION & TRUYỀN THAM SỐ AN TOÀN TRẢ VỀ JSON
// ============================================================
$params = count($url) > 2 ? array_slice($url, 2) : [];

call_user_func_array([$controller, $action], $params);