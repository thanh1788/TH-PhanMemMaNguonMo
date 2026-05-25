<?php include 'app/views/shares/header.php'; ?>

<style>
    .form-card { background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); border: 1px solid #eee; overflow: hidden; }
    .form-card-header { background: linear-gradient(135deg, #d70018, #ff424e); color: #fff; padding: 20px 28px; }
    .form-card-header h4 { margin: 0; font-weight: 800; font-size: 18px; }
    .form-card-body { padding: 28px; }
    .form-label { font-weight: 600; font-size: 14px; color: #333; margin-bottom: 6px; }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e0e0e0;
        padding: 10px 14px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
        border-color: #d70018;
        box-shadow: 0 0 0 3px rgba(215,0,24,0.1);
        outline: none;
    }
    .btn-submit { background: #d70018; color: #fff; border: none; border-radius: 10px; padding: 13px 28px; font-size: 15px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.2s; }
    .btn-submit:hover { background: #b5001a; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(215,0,24,0.3); }
    .btn-cancel { background: #f5f5f5; color: #555; border: 1.5px solid #ddd; border-radius: 10px; padding: 12px 28px; font-size: 14px; font-weight: 600; width: 100%; text-decoration: none; display: block; text-align: center; transition: all 0.2s; }
    .btn-cancel:hover { background: #eee; color: #333; }
</style>

<div class="container mt-4 mb-5" style="max-width: 560px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background: none; padding: 0; font-size: 13px;">
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/Category" style="color: #d70018; text-decoration: none;">Danh mục</a></li>
            <li class="breadcrumb-item active text-muted">Thêm mới</li>
        </ol>
    </nav>

    <div class="form-card">
        <div class="form-card-header">
            <h4><i class="fas fa-folder-plus me-2"></i>Thêm danh mục mới</h4>
            <p class="mb-0 mt-1" style="font-size: 13px; opacity: 0.85;">Tạo danh mục để phân loại sản phẩm</p>
        </div>
        <div class="form-card-body">
            <form action="/Category/save" method="POST">
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-tags me-1 text-danger"></i>Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="VD: Điện thoại, Laptop, Phụ kiện..." required>
                </div>
                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-pen-nib me-1 text-danger"></i>Mô tả</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả ngắn về danh mục này..."></textarea>
                </div>
                <div class="row g-2">
                    <div class="col-5">
                        <a href="/Category" class="btn-cancel">
                            <i class="fas fa-arrow-left me-2"></i>Hủy
                        </a>
                    </div>
                    <div class="col-7">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save me-2"></i>Lưu danh mục
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
