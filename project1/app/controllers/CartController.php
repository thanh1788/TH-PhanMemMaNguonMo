<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/OrderModel.php');

class CartController {
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

    // Hiển thị giỏ hàng
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        include 'app/views/cart/index.php';
    }

    // Thêm sản phẩm vào giỏ: /Cart/add/5
    public function add($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: /');
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // ĐỌC SỐ LƯỢNG TỪ FORM GỬI LÊN (Nếu không có hoặc hợp lệ dưới 1, mặc định gán bằng 1)
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantity < 1) {
            $quantity = 1;
        }

        if (isset($_SESSION['cart'][$id])) {
            // Nếu sản phẩm đã tồn tại, cộng dồn số lượng vừa chọn vào giỏ hàng
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            // Nếu sản phẩm chưa có, thiết lập thông tin ban đầu dựa trên số lượng vừa chọn
            $_SESSION['cart'][$id] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'image'    => $product->image,
                'quantity' => $quantity // Sử dụng biến số lượng động ở đây
            ];
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm "' . $product->name . '" vào giỏ hàng!'];
        header('Location: /Cart');
        exit;
    }

    // Cập nhật số lượng tự động qua AJAX
    public function update() {
        // Chỉ xử lý nếu có request POST truyền lên từ Fetch API
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['quantity'])) {
            $id = $_POST['id'];
            $qty = (int)$_POST['quantity'];

            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]);
            } elseif (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] = $qty;
            }

            // Tính toán lại các thông số để trả về cho Client hiển thị
            $cart = $_SESSION['cart'] ?? [];
            $subtotal = 0;
            $totalItems = 0;
            
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                $totalItems += $item['quantity'];
            }

            // Tính toán phí ship dựa trên luật cấu hình của bạn (Dưới 500k phí 30k)
            $shippingFee = ($subtotal >= 500000 || $subtotal == 0) ? 0 : 30000;
            $total = $subtotal + $shippingFee;

            // Lấy riêng giá trị "Thành tiền" của item vừa thay đổi số lượng
            $itemSubtotal = isset($cart[$id]) ? ($cart[$id]['price'] * $cart[$id]['quantity']) : 0;

            // Trả JSON về cho JavaScript cập nhật DOM
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'itemSubtotal' => $itemSubtotal,
                'totalItems' => $totalItems,
                'subtotal' => $subtotal,
                'shippingFee' => $shippingFee,
                'total' => $total
            ]);
            exit;
        }

        // Trường hợp fallback nếu truy cập không hợp lệ
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
        exit;
    }

    // Xóa sản phẩm khỏi giỏ: /Cart/delete/5
    public function delete($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /Cart');
        exit;
    }

    // Trang checkout
    public function checkout() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /');
            exit;
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        include 'app/views/cart/checkout.php';
    }

    // Trang đặt hàng thành công
    public function success() {
        include 'app/views/cart/success.php';
    }

    // Xử lý đặt hàng
    public function submitCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name    = trim($_POST['customer_name'] ?? '');
            $phone   = trim($_POST['phone'] ?? '');
            $cart    = $_SESSION['cart'] ?? [];

            // Ghép địa chỉ đầy đủ từ các trường riêng lẻ
            $street       = trim($_POST['address'] ?? '');
            $wardName     = trim($_POST['ward_name'] ?? '');
            $districtName = trim($_POST['district_name'] ?? '');
            $cityName     = trim($_POST['city_name'] ?? '');

            $addressParts = array_filter([$street, $wardName, $districtName, $cityName]);
            $address = implode(', ', $addressParts);

            if (empty($name) || empty($phone) || empty($address) || empty($cart)) {
                echo "Vui lòng điền đầy đủ thông tin giao hàng!";
                exit;
            }

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $result = $this->orderModel->createOrder($name, $phone, $address, $total, $cart);

            if ($result) {
                unset($_SESSION['cart']);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đặt hàng thành công! Chúng tôi sẽ liên hệ xác nhận sớm nhất.'];
                header('Location: /Order/detail/' . $result . '?phone=' . urlencode($phone));
                exit;
            } else {
                echo "Đã xảy ra lỗi trong quá trình xử lý đơn hàng.";
            }
        }
    }
}
