<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/AuthHelper.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index()
    {
        AuthHelper::requireAdmin();
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        AuthHelper::requireAdmin();
        include 'app/views/category/add.php';
    }

    public function save()
    {
        AuthHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            if ($this->categoryModel->addCategory($name, $description)) {
                header('Location: /Category');
            } else {
                echo "Lỗi khi thêm danh mục.";
            }
        }
    }

    public function edit($id)
    {
        AuthHelper::requireAdmin();
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Danh mục không tồn tại.";
        }
    }

    public function update()
    {
        AuthHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            if ($this->categoryModel->updateCategory($id, $name, $description)) {
                header('Location: /Category');
            } else {
                echo "Lỗi khi cập nhật danh mục.";
            }
        }
    }

    public function delete($id)
    {
        AuthHelper::requireAdmin();
        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: /Category');
        } else {
            echo "Lỗi khi xóa danh mục.";
        }
    }
}