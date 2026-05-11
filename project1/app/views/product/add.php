<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; padding-top: 50px; }
        .form-container { 
            background: #fff; 
            border-radius: 15px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            padding: 40px; 
        }
        .form-label { font-weight: 600; color: #495057; }
        .btn-submit { background: #ee4d2d; border: none; color: white; padding: 12px; border-radius: 8px; width: 100%; font-weight: bold; }
        .btn-submit:hover { background: #d73211; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 form-container">
                <h3 class="text-center mb-4 fw-bold">THÊM SẢN PHẨM MỚI</h3>
                <form method="POST" action="/project1/Product/add" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Mô tả về sản phẩm..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Giá bán (VNĐ)</label>
                        <input type="number" name="price" class="form-control" placeholder="Ví dụ: 150000" required>
                    </div>
                    <button type="submit" class="btn btn-submit mb-3">LƯU SẢN PHẨM</button>
                    <div class="text-center">
                        <a href="/project1/Product/list" class="text-decoration-none text-muted">Quay lại danh sách</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>