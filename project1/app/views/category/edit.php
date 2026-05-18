<?php include 'app/views/shares/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container my-5">
    <!-- Đồng bộ style giống trang Add nhưng đổi tông màu nhấn sang Xanh để phân biệt trạng thái Edit -->
    <div class="card border-light shadow-sm mx-auto p-4" style="max-width: 500px; border-radius: 12px;">
        
        <h4 class="font-weight-bold mb-4 px-2 text-dark" style="border-left: 4px solid #007bff; font-size: 1.25rem;">
            CẬP NHẬT DANH MỤC
        </h4>
        
        <form action="/Category/update" method="POST">
            <input type="hidden" name="id" value="<?= $category->id ?>">
            
            <div class="form-group mb-3">
                <label class="text-secondary font-weight-bold small"><i class="fa-solid fa-tags mr-1"></i> Tên danh mục</label>
                <input type="text" name="name" class="form-control form-control-lg" style="border-radius: 8px; font-size: 0.95rem;" value="<?= htmlspecialchars($category->name) ?>" required>
            </div>
            
            <div class="form-group mb-4">
                <label class="text-secondary font-weight-bold small"><i class="fa-solid fa-pen-nib mr-1"></i> Mô tả</label>
                <textarea name="description" class="form-control" rows="4" style="border-radius: 8px; font-size: 0.95rem;"><?= htmlspecialchars($category->description) ?></textarea>
            </div>
            
            <div class="row no-gutters mx-n1">
                <div class="col px-1">
                    <a href="/Category" class="btn btn-light btn-block font-weight-bold text-secondary py-2" style="border-radius: 8px; background-color: #f3f4f6;">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
                <div class="col px-1">
                    <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2" style="border-radius: 8px;">
                        <i class="fa-solid fa-pen-to-square mr-1"></i> Cập nhật
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>