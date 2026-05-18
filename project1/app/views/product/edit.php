<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4" style="max-width: 600px;">
    <div class="card shadow-sm border-0 p-4 bg-white rounded-lg">
        <h2 class="h4 font-weight-bold text-dark mb-4"><i class="fas fa-edit text-danger mr-2"></i>Chỉnh sửa sản phẩm</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger shadow-sm rounded-lg">
                <ul class="mb-0 pl-3">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- QUAN TRỌNG: Đã thêm enctype="multipart/form-data" để gửi được file ảnh -->
        <form method="POST" action="/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
            <input type="hidden" name="id" value="<?php echo $product->id; ?>">

            <div class="form-group">
                <label for="name" class="font-weight-bold">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="description" class="font-weight-bold">Mô tả:</label>
                <textarea id="description" name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price" class="font-weight-bold">Giá bán (đ):</label>
                <!-- Đổi step thành 0 để phù hợp tiền Việt, bạn có thể giữ step="0.01" nếu là USD -->
                <input type="number" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="category_id" class="font-weight-bold">Danh mục:</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- KHU VỰC QUẢN LÝ VÀ SỬA ĐỔI HÌNH ẢNH -->
            <div class="form-group mt-4">
                <label class="font-weight-bold d-block">Hình ảnh sản phẩm:</label>
                
                <!-- Hiển thị ảnh hiện tại nếu có -->
                <?php if (!empty($product->image)): ?>
                    <div class="mb-3 p-2 border rounded d-inline-block bg-light text-center">
                        <span class="d-block text-muted small mb-1">Ảnh hiện tại</span>
                        <img src="/public/uploads/products/<?php echo $product->image; ?>" alt="Product Image" class="img-thumbnail" style="max-height: 120px; object-fit: contain;">
                    </div>
                <?php else: ?>
                    <div class="mb-3 text-muted small">
                        <i class="fas fa-image mr-1"></i> Sản phẩm này hiện chưa có ảnh.
                    </div>
                <?php endif; ?>

                <!-- Ô chọn ảnh mới thay thế -->
                <div class="custom-file">
                    <input type="file" name="image" class="custom-file-input" id="productImage" accept="image/*">
                    <label class="custom-file-label" for="productImage">Chọn ảnh mới để thay đổi...</label>
                </div>
                <small class="form-text text-muted">Bỏ qua ô này nếu bạn không muốn thay đổi hình ảnh hiện tại.</small>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-danger btn-block font-weight-bold shadow-sm">Lưu thay đổi</button>
                <a href="/" class="btn btn-light btn-block text-secondary border mt-2">Quay lại trang chủ</a>
            </div>
        </form>
    </div>
</div>

<!-- Script xử lý hiển thị tên file khi chọn ảnh mới -->
<script>
    document.getElementById('productImage').onchange = function () {
        var filename = this.value.split('\\').pop();
        this.nextElementSibling.innerText = filename ? filename : "Chọn ảnh mới để thay đổi...";
    };
</script>

<?php include 'app/views/shares/footer.php'; ?>