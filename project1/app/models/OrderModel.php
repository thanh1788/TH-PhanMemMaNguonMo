<?php
class OrderModel {
    private $conn;

    // Danh sách trạng thái đơn hàng
    public static $statuses = [
        'pending'   => ['label' => 'Chờ xác nhận', 'color' => 'warning',  'icon' => 'fa-clock'],
        'confirmed' => ['label' => 'Đã xác nhận',  'color' => 'info',     'icon' => 'fa-check-circle'],
        'shipping'  => ['label' => 'Đang giao',    'color' => 'primary',  'icon' => 'fa-truck'],
        'delivered' => ['label' => 'Đã giao',      'color' => 'success',  'icon' => 'fa-box-open'],
        'cancelled' => ['label' => 'Đã huỷ',       'color' => 'danger',   'icon' => 'fa-times-circle'],
    ];

    public function __construct($db) {
        $this->conn = $db;
    }

    // ============================================================
    // CUSTOMER METHODS
    // ============================================================

    public function createOrder($customer_name, $phone, $address, $total_price, $cartItems) {
        try {
            $this->conn->beginTransaction();

            $queryOrder = "INSERT INTO orders (customer_name, phone, address, total_price)
                           VALUES (:customer_name, :phone, :address, :total_price)";
            $stmtOrder = $this->conn->prepare($queryOrder);
            $customer_name = htmlspecialchars(strip_tags($customer_name));
            $phone         = htmlspecialchars(strip_tags($phone));
            $address       = htmlspecialchars(strip_tags($address));
            $stmtOrder->bindParam(':customer_name', $customer_name);
            $stmtOrder->bindParam(':phone',         $phone);
            $stmtOrder->bindParam(':address',       $address);
            $stmtOrder->bindParam(':total_price',   $total_price);
            $stmtOrder->execute();
            $orderId = $this->conn->lastInsertId();

            $queryDetail = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity)
                            VALUES (:order_id, :product_id, :product_name, :price, :quantity)";
            $stmtDetail = $this->conn->prepare($queryDetail);
            foreach ($cartItems as $productId => $item) {
                $productName = $item['name'] ?? '';
                $stmtDetail->bindParam(':order_id',     $orderId);
                $stmtDetail->bindParam(':product_id',   $productId);
                $stmtDetail->bindParam(':product_name', $productName);
                $stmtDetail->bindParam(':price',        $item['price']);
                $stmtDetail->bindParam(':quantity',     $item['quantity']);
                $stmtDetail->execute();
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getOrdersByPhone($phone) {
        $query = "SELECT * FROM orders WHERE phone = :phone ORDER BY created_at DESC";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOrderById($id) {
        $query = "SELECT * FROM orders WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getOrderDetails($orderId) {
        $query = "SELECT od.*,
                         COALESCE(p.name, od.product_name) AS product_name,
                         p.image, p.id AS product_id_ref
                  FROM order_details od
                  LEFT JOIN product p ON od.product_id = p.id
                  WHERE od.order_id = :order_id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOrderByIdAndPhone($id, $phone) {
        $query = "SELECT * FROM orders WHERE id = :id AND phone = :phone";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id',    $id);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // ============================================================
    // ADMIN METHODS
    // ============================================================

    // Lấy tất cả đơn hàng, hỗ trợ filter + search + phân trang
    public function getAllOrders($status = '', $search = '', $page = 1, $perPage = 15) {
        $where  = [];
        $params = [];

        if (!empty($status)) {
            $where[]          = "o.status = :status";
            $params[':status'] = $status;
        }
        if (!empty($search)) {
            $where[]           = "(o.customer_name LIKE :search OR o.phone LIKE :search OR o.id = :search_id)";
            $params[':search']    = '%' . $search . '%';
            $params[':search_id'] = is_numeric($search) ? (int)$search : 0;
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $offset   = ($page - 1) * $perPage;

        $query = "SELECT o.*,
                         COUNT(od.id) AS item_count
                  FROM orders o
                  LEFT JOIN order_details od ON od.order_id = o.id
                  $whereSQL
                  GROUP BY o.id
                  ORDER BY o.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Đếm tổng số đơn (dùng cho phân trang)
    public function countOrders($status = '', $search = '') {
        $where  = [];
        $params = [];

        if (!empty($status)) {
            $where[]           = "status = :status";
            $params[':status'] = $status;
        }
        if (!empty($search)) {
            $where[]              = "(customer_name LIKE :search OR phone LIKE :search OR id = :search_id)";
            $params[':search']    = '%' . $search . '%';
            $params[':search_id'] = is_numeric($search) ? (int)$search : 0;
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders $whereSQL");
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // Thống kê số đơn theo từng trạng thái
    public function countByStatus() {
        $stmt = $this->conn->query(
            "SELECT status, COUNT(*) AS cnt FROM orders GROUP BY status"
        );
        $rows   = $stmt->fetchAll(PDO::FETCH_OBJ);
        $result = ['all' => 0];
        foreach (array_keys(self::$statuses) as $s) {
            $result[$s] = 0;
        }
        foreach ($rows as $row) {
            $result[$row->status] = (int)$row->cnt;
            $result['all']       += (int)$row->cnt;
        }
        return $result;
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status) {
        if (!array_key_exists($status, self::$statuses)) return false;
        $stmt = $this->conn->prepare(
            "UPDATE orders SET status = :status WHERE id = :id"
        );
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id',     $id);
        return $stmt->execute();
    }

    // Cập nhật ghi chú nội bộ
    public function updateNote($id, $note) {
        $stmt = $this->conn->prepare(
            "UPDATE orders SET note = :note WHERE id = :id"
        );
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':id',   $id);
        return $stmt->execute();
    }

    // Xoá đơn hàng (cascade xoá order_details)
    public function deleteOrder($id) {
        $stmt = $this->conn->prepare("DELETE FROM orders WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Thống kê doanh thu 7 ngày gần nhất
    public function revenueLastDays($days = 7) {
        $stmt = $this->conn->prepare(
            "SELECT DATE(created_at) AS day, SUM(total_price) AS revenue, COUNT(*) AS orders
             FROM orders
             WHERE status != 'cancelled'
               AND created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
             GROUP BY DATE(created_at)
             ORDER BY day ASC"
        );
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Tổng doanh thu (không tính đơn huỷ)
    public function totalRevenue() {
        $stmt = $this->conn->query(
            "SELECT SUM(total_price) FROM orders WHERE status != 'cancelled'"
        );
        return (float)$stmt->fetchColumn();
    }
}
