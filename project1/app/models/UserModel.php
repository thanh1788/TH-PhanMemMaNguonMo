<?php
class UserModel
{
    private $conn;
    private $table = 'users';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ============================================================
    // TÌM KIẾM / LẤY USER
    // ============================================================

    public function findByEmail(string $email): ?object
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function findByRememberToken(string $token): ?object
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE remember_token = :token LIMIT 1"
        );
        $stmt->execute([':token' => $token]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function findByVerifyToken(string $token): ?object
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE verify_token = :token LIMIT 1"
        );
        $stmt->execute([':token' => $token]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function findByResetToken(string $token): ?object
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} 
             WHERE reset_token = :token 
               AND reset_expires > NOW() 
             LIMIT 1"
        );
        $stmt->execute([':token' => $token]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ?: null;
    }

    // ============================================================
    // TẠO MỚI
    // ============================================================

    public function register(string $full_name, string $email, string $password): bool|int
    {
        // Kiểm tra email đã tồn tại
        if ($this->findByEmail($email)) {
            return false;
        }

        $hash         = password_hash($password, PASSWORD_BCRYPT);
        $verifyToken  = bin2hex(random_bytes(32));

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (full_name, email, password, verify_token, is_verified, role, status, created_at)
             VALUES (:full_name, :email, :password, :verify_token, 0, 'user', 'active', NOW())"
        );
        $stmt->execute([
            ':full_name'    => $full_name,
            ':email'        => $email,
            ':password'     => $hash,
            ':verify_token' => $verifyToken,
        ]);

        return (int)$this->conn->lastInsertId();
    }

    // ============================================================
    // CẬP NHẬT
    // ============================================================

    public function updateProfile(int $id, string $full_name, string $phone, string $address): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} 
             SET full_name = :full_name, phone = :phone, address = :address, updated_at = NOW()
             WHERE id = :id"
        );
        return $stmt->execute([
            ':full_name' => $full_name,
            ':phone'     => $phone,
            ':address'   => $address,
            ':id'        => $id,
        ]);
    }

    public function updateAvatar(int $id, string $avatar): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET avatar = :avatar, updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute([':avatar' => $avatar, ':id' => $id]);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET password = :password, updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute([':password' => $hash, ':id' => $id]);
    }

    public function setRememberToken(int $id, string $token): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET remember_token = :token WHERE id = :id"
        );
        return $stmt->execute([':token' => $token, ':id' => $id]);
    }

    public function clearRememberToken(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET remember_token = NULL WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    public function setResetToken(int $id, string $token): bool
    {
        // Dùng NOW() của MySQL để tránh lệch múi giờ giữa PHP và MySQL
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} 
             SET reset_token = :token, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR)
             WHERE id = :id"
        );
        return $stmt->execute([':token' => $token, ':id' => $id]);
    }

    public function clearResetToken(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} 
             SET reset_token = NULL, reset_expires = NULL 
             WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    public function verifyEmail(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} 
             SET is_verified = 1, verify_token = NULL, updated_at = NOW()
             WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function updateRole(int $id, string $role): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET role = :role, updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute([':role' => $role, ':id' => $id]);
    }

    // ============================================================
    // DANH SÁCH (ADMIN)
    // ============================================================

    public function getAll(string $search = '', int $page = 1, int $perPage = 15, string $role = '', string $status = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $conds  = [];
        $params = [];

        if (!empty($search)) {
            $conds[] = "(full_name LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        if (!empty($role) && in_array($role, ['admin', 'user'])) {
            $conds[] = "role = :role";
            $params[':role'] = $role;
        }
        if (!empty($status) && in_array($status, ['active', 'locked'])) {
            $conds[] = "status = :status";
            $params[':status'] = $status;
        }

        $where = $conds ? 'WHERE ' . implode(' AND ', $conds) : '';

        $stmt = $this->conn->prepare(
            "SELECT id, full_name, email, phone, role, status, is_verified, avatar, created_at
             FROM {$this->table} $where
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset"
        );

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function countAll(string $search = '', string $role = '', string $status = ''): int
    {
        $conds  = [];
        $params = [];

        if (!empty($search)) {
            $conds[] = "(full_name LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        if (!empty($role) && in_array($role, ['admin', 'user'])) {
            $conds[] = "role = :role";
            $params[':role'] = $role;
        }
        if (!empty($status) && in_array($status, ['active', 'locked'])) {
            $conds[] = "status = :status";
            $params[':status'] = $status;
        }

        $where = $conds ? 'WHERE ' . implode(' AND ', $conds) : '';

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} $where");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
