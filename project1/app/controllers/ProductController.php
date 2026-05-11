<?php
require_once 'app/models/ProductModel.php';

class ProductController
{
    private $products = [];

    public function __construct()
    {
        // Giả sử chúng ta lưu trữ sản phẩm trong session để giữ lại khi làm mới trang
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['products'])) {
            $this->products = $_SESSION['products'];
        }
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        // Hiển thị danh sách sản phẩm
        $products = $this->products;
        include 'app/views/product/list.php';
    }

    public function add()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $imageName = "";

            // Kiểm tra tên sản phẩm
            if (empty($name)) {
                $errors[] = 'Tên sản phẩm là bắt buộc.';
            } elseif (strlen($name) < 10 || strlen($name) > 100) {
                $errors[] = 'Tên sản phẩm phải có từ 10 đến 100 ký tự.';
            }

            // Kiểm tra giá
            if (!is_numeric($price) || $price <= 0) {
                $errors[] = 'Giá phải là một số dương lớn hơn 0.';
            }

            // --- XỬ LÝ UPLOAD HÌNH ẢNH ---
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                if (!in_array(strtolower($ext), $allowed)) {
                    $errors[] = 'Chỉ chấp nhận các định dạng ảnh: ' . implode(', ', $allowed);
                } else {
                    // Tạo tên file duy nhất và di chuyển vào thư mục public/images
                    $imageName = time() . '_' . $_FILES['image']['name'];
                    $targetDir = 'public/images/';
                    
                    // Tạo thư mục nếu chưa tồn tại
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName)) {
                        $errors[] = 'Không thể lưu file hình ảnh vào thư mục server.';
                    }
                }
            }

            if (empty($errors)) {
                $id = count($this->products) + 1;
                // Khởi tạo model với thuộc tính image (Cần đảm bảo ProductModel đã thêm biến này)
                $product = new ProductModel($id, $name, $description, $price, $imageName);
                $this->products[] = $product;
                $_SESSION['products'] = $this->products;
                header('Location: /project1/Product/list');
                exit();
            }
        }
        include 'app/views/product/add.php';
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($this->products as $key => $product) {
                if ($product->getID() == $id) {
                    $this->products[$key]->setName($_POST['name']);
                    $this->products[$key]->setDescription($_POST['description']);
                    $this->products[$key]->setPrice($_POST['price']);

                    // Xử lý nếu người dùng cập nhật ảnh mới
                    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        $imageName = time() . '_' . $_FILES['image']['name'];
                        if (move_uploaded_file($_FILES['image']['tmp_name'], 'public/images/' . $imageName)) {
                            $this->products[$key]->setImage($imageName);
                        }
                    }
                    break;
                }
            }
            $_SESSION['products'] = $this->products;
            header('Location: /project1/Product/list');
            exit();
        }

        foreach ($this->products as $product) {
            if ($product->getID() == $id) {
                include 'app/views/product/edit.php';
                return;
            }
        }
        die('Product not found');
    }

    public function delete($id)
    {
        foreach ($this->products as $key => $product) {
            if ($product->getID() == $id) {
                // Tùy chọn: Xóa file ảnh vật lý trong thư mục public/images nếu muốn
                $oldImage = $this->products[$key]->getImage();
                if ($oldImage && file_exists('public/images/' . $oldImage)) {
                    unlink('public/images/' . $oldImage);
                }
                
                unset($this->products[$key]);
                break;
            }
        }
        $this->products = array_values($this->products);
        $_SESSION['products'] = $this->products;
        header('Location: /project1/Product/list');
        exit();
    }
}
?>