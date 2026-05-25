<?php include 'app/views/shares/header.php'; ?>

<style>
    .form-card { background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); border: 1px solid #eee; overflow: hidden; }
    .form-card-header { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: #fff; padding: 20px 28px; }
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
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
        outline: none;
    }
    .current-img-box { border: 1.5px solid #e0e0e0; border-radius: 10px; padding: 14px; background: #fafafa; text-align: center; }
    .current-img-box img { max-height: 140px; object-fit: contain; border-radius: 8px; }
    .img-preview-box {
        border: 2px dashed #ddd;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .img-preview-box:hover { border-color: #0d6efd; background: #f0f5ff; }
    .img-preview-box img { max-height: 100px; max-width: 100%; object-fit: contain; border-radius: 8px; }
    .btn-submit { background: #0d6efd; color: #fff; border: none; border-radius: 10px; padding: 13px 28px; font-size: 15px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.2s; }
    .btn-submit:hover { background: #0a58ca; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(13,110,253,0.3); }
    .btn-cancel { background: #f5f5f5; color: #555; border: 1.5px solid #ddd; border-radius: 10px; padding: 12px 28px; font-size: 14px; font-weight: 600; width: 100%; text-decoration: none; display: block; text-align: center; transition: all 0.2s; }
    .btn-cancel:hover { background: #eee; color: #333; }
</style>

<div class="container mt-4 mb-5" style="max-width: 680px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background: none; padding: 0; font-size: 13px;">
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;">Trang chủ</a></li>
            <li class="breadcrumb-item active text-muted">Chỉnh sửa sản phẩm</li>
        </ol>
    </nav>

    <div class="form-card">
        <div class="form-card-header">
            <h4><i class="fas fa-edit me-2"></i>Chỉnh sửa sản phẩm</h4>
            <p class="mb-0 mt-1" style="font-size: 13px; opacity: 0.85;">Cập nhật thông tin sản phẩm: <strong><?= htmlspecialchars($product->name) ?></strong></p>
        </div>
        <div class="form-card-body">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 mb-4" style="font-size: 14px;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Vui lòng kiểm tra lại:</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/Product/update" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product->id ?>">

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-tag me-1 text-primary"></i>Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required
                           value="<?= htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-layer-group me-1 text-primary"></i>Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" <?= $category->id == $product->category_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-dollar-sign me-1 text-primary"></i>Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" min="0" required
                               value="<?= htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-align-left me-1 text-primary"></i>Mô tả sản phẩm <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <!-- Image management -->
                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-image me-1 text-primary"></i>Hình ảnh sản phẩm</label>

                    <?php if (!empty($product->image)): ?>
                        <div class="current-img-box mb-3">
                            <p class="text-muted mb-2" style="font-size: 12px;"><i class="fas fa-image me-1"></i>Ảnh hiện tại</p>
                            <img src="/public/uploads/products/<?= htmlspecialchars($product->image) ?>" alt="Current image">
                        </div>
                    <?php endif; ?>

                    <div class="img-preview-box" onclick="document.getElementById('productImage').click()">
                        <img id="imgPreview" src="" alt="" style="display: none;">
                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 28px; color: #ccc; margin-bottom: 6px;"></i>
                            <p class="mb-0" style="font-size: 13px; color: #999;">Nhấn để chọn ảnh mới thay thế</p>
                            <p class="mb-0" style="font-size: 11px; color: #bbb;">Bỏ qua nếu không muốn thay đổi ảnh</p>
                        </div>
                    </div>
                    <input type="file" name="image" id="productImage" accept="image/*" style="display: none;">
                </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <a href="/" class="btn-cancel">
                            <i class="fas fa-arrow-left me-2"></i>Hủy bỏ
                        </a>
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save me-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('productImage').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imgPreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
