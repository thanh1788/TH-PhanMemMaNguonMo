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
    .form-control:focus, .form-select:focus {
        border-color: #d70018;
        box-shadow: 0 0 0 3px rgba(215,0,24,0.1);
        outline: none;
    }
    .img-preview-box {
        border: 2px dashed #ddd;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .img-preview-box:hover { border-color: #d70018; background: #fff5f5; }
    .img-preview-box img { max-height: 120px; max-width: 100%; object-fit: contain; border-radius: 8px; }
    .btn-submit { background: #d70018; color: #fff; border: none; border-radius: 10px; padding: 13px 28px; font-size: 15px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.2s; }
    .btn-submit:hover { background: #b5001a; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(215,0,24,0.3); }
    .btn-cancel { background: #f5f5f5; color: #555; border: 1.5px solid #ddd; border-radius: 10px; padding: 12px 28px; font-size: 14px; font-weight: 600; width: 100%; text-decoration: none; display: block; text-align: center; transition: all 0.2s; }
    .btn-cancel:hover { background: #eee; color: #333; }
    .error-msg { color: #d70018; font-size: 12.5px; margin-top: 4px; }
    .input-icon-wrap { position: relative; }
    .input-icon-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 14px; }
    .input-icon-wrap .form-control { padding-left: 36px; }
</style>

<div class="container mt-4 mb-5" style="max-width: 680px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background: none; padding: 0; font-size: 13px;">
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;">Trang chủ</a></li>
            <li class="breadcrumb-item active text-muted">Thêm sản phẩm mới</li>
        </ol>
    </nav>

    <div class="form-card">
        <div class="form-card-header">
            <h4><i class="fas fa-plus-circle me-2"></i>Thêm sản phẩm mới</h4>
            <p class="mb-0 mt-1" style="font-size: 13px; opacity: 0.85;">Điền đầy đủ thông tin để đăng bán sản phẩm</p>
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

            <form action="/Product/save" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-tag me-1 text-danger"></i>Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="VD: iPhone 16 Pro Max 256GB" required
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-layer-group me-1 text-danger"></i>Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-dollar-sign me-1 text-danger"></i>Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" placeholder="VD: 29990000" min="0" required
                               value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-align-left me-1 text-danger"></i>Mô tả sản phẩm <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả chi tiết về sản phẩm..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-image me-1 text-danger"></i>Hình ảnh sản phẩm</label>
                    <div class="img-preview-box" onclick="document.getElementById('productImage').click()">
                        <img id="imgPreview" src="" alt="" style="display: none;">
                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #ccc; margin-bottom: 8px;"></i>
                            <p class="mb-0" style="font-size: 14px; color: #999;">Nhấn để chọn ảnh hoặc kéo thả vào đây</p>
                            <p class="mb-0" style="font-size: 12px; color: #bbb;">JPG, PNG, WEBP - Tối đa 5MB</p>
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
                            <i class="fas fa-check-circle me-2"></i>Đăng bán sản phẩm
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
