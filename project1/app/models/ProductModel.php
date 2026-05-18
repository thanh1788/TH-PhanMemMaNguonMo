<?php

class ProductModel
{
    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách sản phẩm (Bổ sung lấy trường p.image)
    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // Lấy chi tiết 1 sản phẩm theo ID
    public function getProductById($id)
    {
        $query = "SELECT p.*, c.name as category_name 
              FROM " . $this->table_name . " p 
              LEFT JOIN category c ON p.category_id = c.id 
              WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Thêm sản phẩm mới (Bổ sung tham số $image)
    public function addProduct($name, $description, $price, $category_id, $image = null)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        // Thêm trường image vào câu lệnh SQL
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image) 
                  VALUES (:name, :description, :price, :category_id, :image)";
        
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = $image ? htmlspecialchars(strip_tags($image)) : null;

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật sản phẩm (Bổ sung tham số $image)
    public function updateProduct($id, $name, $description, $price, $category_id, $image = null)
    {
        // Nếu có ảnh mới thì cập nhật cả ảnh, ngược lại giữ nguyên ảnh cũ trong DB
        if ($image) {
            $query = "UPDATE " . $this->table_name . " 
                      SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image 
                      WHERE id=:id";
        } else {
            $query = "UPDATE " . $this->table_name . " 
                      SET name=:name, description=:description, price=:price, category_id=:category_id 
                      WHERE id=:id";
        }
                  
        $stmt = $this->conn->prepare($query);

        $id = htmlspecialchars(strip_tags($id));
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        
        if ($image) {
            $image = htmlspecialchars(strip_tags($image));
            $stmt->bindParam(':image', $image);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa sản phẩm theo ID
    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}