<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; padding-top: 50px; }
        .form-container { background: #fff; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 40px; }
        .current-img { max-width: 150px; border-radius: 10px; border: 1px solid #ddd; margin-bottom: 10px; }
        .btn-update { background: #007bff; border: none; color: white; padding: 12px; border-radius: 8px; width: 100%; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 form-container">
                <h3 class="text-center mb-4 fw-bold">CHỈNH SỬA SẢN PHẨM</h3>
                <form method="POST" action="/project1/Product/edit/<?php echo $product->getID(); ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product->getName()); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Hình ảnh sản phẩm</label>
                        <?php if ($product->getImage()): ?>
                            <img src="/project1/public/images/<?php echo $product->getImage(); ?>" class="current-img" alt="Current Image">
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Chọn ảnh mới nếu muốn thay đổi</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product->getDescription()); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                        <input type="number" name="price" class="form-control" value="<?php echo $product->getPrice(); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-update mb-3">CẬP NHẬT THAY ĐỔI</button>
                    <div class="text-center">
                        <a href="/project1/Product/list" class="text-decoration-none text-muted">Hủy và quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>