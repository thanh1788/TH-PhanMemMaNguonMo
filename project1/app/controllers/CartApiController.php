<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/OrderModel.php';

class CartApiController {
    private $db;
    private $productModel;
    private $orderModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->orderModel = new OrderModel($this->db);
    }

    /**
     * Hàm helper xuất phản hồi dữ liệu JSON chuẩn hóa cho API
     */
    private function jsonResponse(bool $success, string $message, array $data = [], int $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Hàm helper lấy dữ liệu đầu vào linh hoạt (Cả từ raw JSON body hoặc Form data)
     */
    private function getRequestInput() {
        return json_decode(file_get_contents('php://input'), true) ?? $_POST;
    }

    // ============================================================
    // [GET] LẤY CHI TIẾT GIỎ HÀNG VÀ TỔNG TIỀN
    // ============================================================
    public function index() {
        $cart = $_SESSION['cart'] ?? (object)[]; // Trả về object rỗng {} nếu chưa có sản phẩm nào
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }

        $shippingFee = ($subtotal >= 500000 || $subtotal == 0) ? 0 : 30000;
        $total = $subtotal + $shippingFee;

        return $this->jsonResponse(true, 'Lấy dữ liệu giỏ hàng thành công.', [
            'cart'        => $cart,
            'totalItems'  => $totalItems,
            'subtotal'    => $subtotal,
            'shippingFee' => $shippingFee,
            'total'       => $total
        ], 200);
    }

    // ============================================================
    // [POST] THÊM SẢN PHẨM VÀO GIỎ: /Cart/add/5
    // ============================================================
    public function add($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            return $this->jsonResponse(false, 'Sản phẩm không tồn tại.', [], 404);
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $input = $this->getRequestInput();
        $quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;
        if ($quantity < 1) {
            $quantity = 1;
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'image'    => $product->image,
                'quantity' => $quantity
            ];
        }

        return $this->jsonResponse(true, 'Đã thêm "' . $product->name . '" vào giỏ hàng thành công.', [
            'cart' => $_SESSION['cart']
        ], 200);
    }

    // ============================================================
    // [POST] CẬP NHẬT SỐ LƯỢNG SẢN PHẨM TRONG GIỎ
    // ============================================================
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $input = $this->getRequestInput();
        $id = $input['id'] ?? null;
        $qty = isset($input['quantity']) ? (int)$input['quantity'] : null;

        if ($id === null || $qty === null) {
            return $this->jsonResponse(false, 'Thiếu tham số bắt buộc (id, quantity).', [], 400);
        }

        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } elseif (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        } else {
            return $this->jsonResponse(false, 'Sản phẩm không tồn tại trong giỏ hàng.', [], 404);
        }

        // Tính toán lại toàn bộ thông số giỏ hàng
        $cart = $_SESSION['cart'] ?? [];
        $subtotal = 0;
        $totalItems = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }

        $shippingFee = ($subtotal >= 500000 || $subtotal == 0) ? 0 : 30000;
        $total = $subtotal + $shippingFee;
        $itemSubtotal = isset($cart[$id]) ? ($cart[$id]['price'] * $cart[$id]['quantity']) : 0;

        return $this->jsonResponse(true, 'Cập nhật số lượng thành công.', [
            'itemSubtotal' => $itemSubtotal,
            'totalItems'   => $totalItems,
            'subtotal'     => $subtotal,
            'shippingFee'  => $shippingFee,
            'total'        => $total
        ], 200);
    }

    // ============================================================
    // [DELETE] XÓA SẢN PHẨM KHỎI GIỎ: /Cart/delete/5
    // ============================================================
    public function delete($id) {
        // Chấp nhận cả phương thức DELETE (chuẩn Rest) hoặc POST thông thường
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            return $this->jsonResponse(true, 'Đã xóa sản phẩm khỏi giỏ hàng.', [
                'cart' => $_SESSION['cart'] ?? (object)[]
            ], 200);
        }

        return $this->jsonResponse(false, 'Sản phẩm không tồn tại trong giỏ hàng.', [], 404);
    }

    // ============================================================
    // [POST] THỰC HIỆN ĐẶT HÀNG (CHECKOUT SUBMIT)
    // ============================================================
    public function submitCheckout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            return $this->jsonResponse(false, 'Không thể đặt hàng do giỏ hàng của bạn đang trống.', [], 400);
        }

        $input = $this->getRequestInput();
        $name  = trim($input['customer_name'] ?? '');
        $phone = trim($input['phone'] ?? '');

        // Xử lý các trường địa chỉ đơn lẻ
        $street       = trim($input['address'] ?? '');
        $wardName     = trim($input['ward_name'] ?? '');
        $districtName = trim($input['district_name'] ?? '');
        $cityName     = trim($input['city_name'] ?? '');

        $addressParts = array_filter([$street, $wardName, $districtName, $cityName]);
        $address = implode(', ', $addressParts);

        if (empty($name) || empty($phone) || empty($address)) {
            return $this->jsonResponse(false, 'Vui lòng điền đầy đủ thông tin giao hàng (customer_name, phone, address).', [], 400);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Tạo đơn hàng xuống Cơ sở dữ liệu
        $orderId = $this->orderModel->createOrder($name, $phone, $address, $total, $cart);

        if ($orderId) {
            unset($_SESSION['cart']); // Làm sạch giỏ hàng sau khi checkout thành công
            
            return $this->jsonResponse(true, 'Đặt hàng thành công!', [
                'order_id' => $orderId,
                'customer' => [
                    'name'    => $name,
                    'phone'   => $phone,
                    'address' => $address
                ],
                'total_amount' => $total
            ], 201); // 201 Created: Tạo bản ghi thành công
        } else {
            return $this->jsonResponse(false, 'Đã xảy ra lỗi hệ thống trong quá trình xử lý đơn hàng.', [], 500);
        }
    }
}