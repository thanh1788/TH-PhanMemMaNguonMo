<?php
require_once('app/config/database.php');
require_once('app/models/OrderModel.php');
require_once('app/helpers/AuthHelper.php');

class OrderApiController {
    private $db;
    private $orderModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->db         = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
    }

    /**
     * Hàm helper xuất phản hồi JSON chuẩn hóa
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
     * Hàm helper lấy dữ liệu đầu vào linh hoạt (Raw JSON body hoặc Form POST)
     */
    private function getRequestInput() {
        return json_decode(file_get_contents('php://input'), true) ?? $_POST;
    }

    // ============================================================
    // CUSTOMER: Tra cứu các đơn hàng theo SĐT
    // [POST] /Order/lookup hoặc /api/orders/lookup
    // ============================================================
    public function lookup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $input = $this->getRequestInput();
        $phone = trim($input['phone'] ?? '');

        if (empty($phone)) {
            return $this->jsonResponse(false, 'Vui lòng nhập số điện thoại để tra cứu.', [], 400);
        } 
        
        if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
            return $this->jsonResponse(false, 'Số điện thoại không hợp lệ (yêu cầu từ 10-11 chữ số).', [], 422);
        }

        $orders = $this->orderModel->getOrdersByPhone($phone);
        if (empty($orders)) {
            return $this->jsonResponse(false, 'Không tìm thấy đơn hàng nào khớp với số điện thoại này.', [], 404);
        }

        return $this->jsonResponse(true, 'Tra cứu danh sách đơn hàng thành công.', [
            'orders' => $orders
        ], 200);
    }

    // ============================================================
    // CUSTOMER: Chi tiết đơn hàng cụ thể
    // [GET] /Order/detail/5?phone=0987654321
    // ============================================================
    public function detail($id) {
        $phone = trim($_GET['phone'] ?? '');
        if (empty($phone)) {
            return $this->jsonResponse(false, 'Thiếu tham số số điện thoại xác thực (?phone=...).', [], 400);
        }

        $order = $this->orderModel->getOrderByIdAndPhone($id, $phone);
        if (!$order) {
            return $this->jsonResponse(false, 'Không tìm thấy đơn hàng hoặc số điện thoại xác thực không khớp.', [], 404);
        }

        $orderDetails = $this->orderModel->getOrderDetails($id);
        
        return $this->jsonResponse(true, 'Lấy chi tiết đơn hàng thành công.', [
            'order'        => $order,
            'order_details' => $orderDetails,
            'statuses_list' => OrderModel::$statuses // Trả về bộ danh mục trạng thái để client tiện render nếu cần
        ], 200);
    }

    // ============================================================
    // ADMIN: Danh sách đơn hàng (Phân trang, bộ lọc)
    // [GET] /Order/admin
    // ============================================================
    public function admin() {
        AuthHelper::requireAdmin(); // Lưu ý: Cần chắc chắn hàm này trả về JSON 403 khi fail thay vì redirect

        $status  = trim($_GET['status']  ?? '');
        $search  = trim($_GET['search']  ?? '');
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 15;

        $orders      = $this->orderModel->getAllOrders($status, $search, $page, $perPage);
        $total       = $this->orderModel->countOrders($status, $search);
        $totalPages  = max(1, ceil($total / $perPage));
        $statusCount = $this->orderModel->countByStatus();
        $totalRev    = $this->orderModel->totalRevenue();

        return $this->jsonResponse(true, 'Lấy danh sách quản trị đơn hàng thành công.', [
            'orders'        => $orders,
            'pagination'    => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total_items'  => $total,
                'total_pages'  => $totalPages
            ],
            'status_counts' => $statusCount,
            'total_revenue' => $totalRev,
            'statuses_list' => OrderModel::$statuses
        ], 200);
    }

    // ============================================================
    // ADMIN: Xem chi tiết đơn hàng nâng cao
    // [GET] /Order/adminDetail/5
    // ============================================================
    public function adminDetail($id) {
        AuthHelper::requireAdmin();

        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            return $this->jsonResponse(false, 'Đơn hàng không tồn tại trên hệ thống.', [], 404);
        }

        $orderDetails = $this->orderModel->getOrderDetails($id);

        return $this->jsonResponse(true, 'Lấy thông tin chi tiết đơn hàng (Admin) thành công.', [
            'order'        => $order,
            'order_details' => $orderDetails,
            'statuses_list' => OrderModel::$statuses
        ], 200);
    }

    // ============================================================
    // ADMIN: Cập nhật trạng thái đơn hàng
    // [POST/PUT] /Order/updateStatus
    // ============================================================
    public function updateStatus() {
        AuthHelper::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $input  = $this->getRequestInput();
        $id     = (int)($input['id'] ?? 0);
        $status = trim($input['status'] ?? '');

        if (!$id || empty($status)) {
            return $this->jsonResponse(false, 'Thiếu tham số bắt buộc (id, status).', [], 400);
        }

        // Kiểm tra xem trạng thái truyền lên có hợp lệ trong Model không
        if (!array_key_exists($status, OrderModel::$statuses)) {
            return $this->jsonResponse(false, 'Mã trạng thái đơn hàng không hợp lệ.', [], 422);
        }

        if ($this->orderModel->updateStatus($id, $status)) {
            $label = OrderModel::$statuses[$status]['label'] ?? $status;
            return $this->jsonResponse(true, "Đơn hàng #" . str_pad($id, 6, '0', STR_PAD_LEFT) . " đã chuyển sang trạng thái: $label", [
                'order_id' => $id,
                'status'   => $status,
                'label'    => $label
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Cập nhật trạng thái đơn hàng thất bại hoặc trạng thái không đổi.', [], 500);
        }
    }

    // ============================================================
    // ADMIN: Lưu ghi chú nội bộ cho đơn hàng
    // [POST/PATCH] /Order/saveNote
    // ============================================================
    public function saveNote() {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        $input = $this->getRequestInput();
        $id    = (int)($input['id'] ?? 0);
        $note  = trim($input['note'] ?? '');

        if (!$id) {
            return $this->jsonResponse(false, 'Thiếu tham số ID đơn hàng bắt buộc.', [], 400);
        }

        if ($this->orderModel->updateNote($id, $note)) {
            return $this->jsonResponse(true, 'Đã lưu ghi chú đơn hàng thành công.', [
                'order_id' => $id,
                'note'     => $note
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Lưu ghi chú thất bại.', [], 500);
        }
    }

    // ============================================================
    // ADMIN: Xoá đơn hàng khỏi hệ thống
    // [DELETE/POST] /Order/delete/5
    // ============================================================
    public function delete($id) {
        AuthHelper::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(false, 'Phương thức không được hỗ trợ.', [], 405);
        }

        if ($this->orderModel->deleteOrder($id)) {
            return $this->jsonResponse(true, 'Đã xóa hoàn toàn đơn hàng #' . str_pad($id, 6, '0', STR_PAD_LEFT) . ' ra khỏi hệ thống.', [
                'deleted_id' => $id
            ], 200);
        } else {
            return $this->jsonResponse(false, 'Xoá đơn hàng thất bại hoặc đơn hàng không tồn tại.', [], 500);
        }
    }
}