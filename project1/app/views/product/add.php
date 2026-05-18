<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4" style="max-width: 600px;">
    <div class="card shadow-sm border-0 p-4 bg-white rounded-lg">
        <h2 class="h4 font-weight-bold text-dark mb-4">Thêm sản phẩm công nghệ mới</h2>
        
        <!-- QUAN TRỌNG: Phải có enctype="multipart/form-data" -->
        <form action="/Product/save" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label class="font-weight-bold">Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Danh mục</label>
                <select name="category_id" class="form-control" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Giá bán (đ)</label>
                <input type="number" name="price" class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Mô tả sản phẩm</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            <!-- THÊM Ô CHỌN HÌNH ẢNH Ở ĐÂY -->
            <div class="form-group">
                <label class="font-weight-bold">Hình ảnh sản phẩm</label>
                <div class="custom-file">
                    <input type="file" name="image" class="custom-file-input" id="productImage" accept="image/*">
                    <label class="custom-file-label" for="productImage">Chọn file ảnh...</label>
                </div>
            </div>

            <button type="submit" class="btn btn-danger btn-block font-weight-bold shadow-sm mt-4">Đăng bán ngay</button>
        </form>
    </div>
</div>

<!-- Đoạn Script nhỏ giúp hiển thị tên file vừa chọn lên ô Input của Bootstrap -->
<script>
    document.getElementById('productImage').onchange = function () {
        var filename = this.value.split('\\').pop();
        this.nextElementSibling.innerText = filename ? filename : "Chọn file ảnh...";
    };
</script>

<?php include 'app/views/shares/footer.php'; ?>