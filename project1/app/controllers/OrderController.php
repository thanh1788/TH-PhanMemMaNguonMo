<?php
require_once('app/config/database.php');
require_once('app/models/OrderModel.php');

class OrderController {
    private $db;
    private $orderModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->db         = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
    }

    // ============================================================
    // CUSTOMER: Tra cứu đơn hàng  /Order/lookup
    // ============================================================
    public function lookup() {
        $orders = null;
        $phone  = '';
        $error  = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = trim($_POST['phone'] ?? '');
            if (empty($phone)) {
                $error = 'Vui lòng nhập số điện thoại.';
            } elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) {
                $error = 'Số điện thoại không hợp lệ (10-11 chữ số).';
            } else {
                $orders = $this->orderModel->getOrdersByPhone($phone);
                if (empty($orders)) {
                    $error = 'Không tìm thấy đơn hàng nào với số điện thoại <strong>'
                           . htmlspecialchars($phone) . '</strong>.';
                }
            }
        }

        include 'app/views/order/lookup.php';
    }

    // CUSTOMER: Chi tiết đơn hàng  /Order/detail/5?phone=...
    public function detail($id) {
        $phone = trim($_GET['phone'] ?? '');
        if (empty($phone)) {
            header('Location: /Order/lookup');
            exit;
        }
        $order = $this->orderModel->getOrderByIdAndPhone($id, $phone);
        if (!$order) {
            $_SESSION['flash'] = ['type' => 'danger',
                'message' => 'Không tìm thấy đơn hàng hoặc số điện thoại không khớp.'];
            header('Location: /Order/lookup');
            exit;
        }
        $orderDetails = $this->orderModel->getOrderDetails($id);
        $statuses     = OrderModel::$statuses;
        include 'app/views/order/detail.php';
    }

    // ============================================================
    // ADMIN: Danh sách đơn hàng  /Order/admin
    // ============================================================
    public function admin() {
        $status  = trim($_GET['status']  ?? '');
        $search  = trim($_GET['search']  ?? '');
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 15;

        $orders      = $this->orderModel->getAllOrders($status, $search, $page, $perPage);
        $total       = $this->orderModel->countOrders($status, $search);
        $totalPages  = max(1, ceil($total / $perPage));
        $statusCount = $this->orderModel->countByStatus();
        $statuses    = OrderModel::$statuses;
        $totalRev    = $this->orderModel->totalRevenue();

        include 'app/views/order/admin/index.php';
    }

    // ADMIN: Chi tiết + đổi trạng thái  /Order/adminDetail/5
    public function adminDetail($id) {
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Đơn hàng không tồn tại.'];
            header('Location: /Order/admin');
            exit;
        }
        $orderDetails = $this->orderModel->getOrderDetails($id);
        $statuses     = OrderModel::$statuses;
        include 'app/views/order/admin/detail.php';
    }

    // ADMIN: Cập nhật trạng thái  POST /Order/updateStatus
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Order/admin');
            exit;
        }
        $id     = (int)($_POST['id']     ?? 0);
        $status = trim($_POST['status']  ?? '');

        if ($id && $this->orderModel->updateStatus($id, $status)) {
            $label = OrderModel::$statuses[$status]['label'] ?? $status;
            $_SESSION['flash'] = ['type' => 'success',
                'message' => "Đơn #" . str_pad($id, 6, '0', STR_PAD_LEFT)
                           . " đã chuyển sang: <strong>$label</strong>"];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Cập nhật trạng thái thất bại.'];
        }

        $ref = $_POST['ref'] ?? '/Order/admin';
        header('Location: ' . $ref);
        exit;
    }

    // ADMIN: Lưu ghi chú  POST /Order/saveNote
    public function saveNote() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Order/admin');
            exit;
        }
        $id   = (int)($_POST['id']   ?? 0);
        $note = trim($_POST['note']  ?? '');

        if ($id && $this->orderModel->updateNote($id, $note)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã lưu ghi chú.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lưu ghi chú thất bại.'];
        }
        header('Location: /Order/adminDetail/' . $id);
        exit;
    }

    // ADMIN: Xoá đơn hàng  /Order/delete/5
    public function delete($id) {
        if ($this->orderModel->deleteOrder($id)) {
            $_SESSION['flash'] = ['type' => 'success',
                'message' => 'Đã xoá đơn hàng #' . str_pad($id, 6, '0', STR_PAD_LEFT)];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Xoá đơn hàng thất bại.'];
        }
        header('Location: /Order/admin');
        exit;
    }
}
