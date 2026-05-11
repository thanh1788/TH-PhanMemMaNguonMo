<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cửa Hàng Trực Tuyến</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .product-card { 
            transition: transform 0.2s, box-shadow 0.2s; 
            border: none; 
            border-radius: 15px; 
            overflow: hidden;
            height: 100%;
        }
        .product-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.12); 
        }
        .img-container {
            height: 250px;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .price { color: #ee4d2d; font-size: 1.25rem; font-weight: bold; }
        .btn-add-custom { border-radius: 20px; padding: 10px 25px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-cart4"></i> MY STORE</a>
            <a href="/project1/Product/add" class="btn btn-outline-light btn-add-custom">
                <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm
            </a>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4 fw-bold text-center text-uppercase">Danh Sách Sản Phẩm</h2>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card shadow-sm">
                    <div class="img-container">
                        <?php if ($product->getImage()): ?>
                            <img src="/project1/public/images/<?php echo $product->getImage(); ?>" alt="Product">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x300?text=No+Image" alt="No Image">
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate" title="<?php echo htmlspecialchars($product->getName()); ?>">
                            <?php echo htmlspecialchars($product->getName()); ?>
                        </h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?php echo mb_strimwidth(htmlspecialchars($product->getDescription()), 0, 80, "..."); ?>
                        </p>
                        <div class="price mb-3 text-center">
                            <?php echo number_format($product->getPrice(), 0, ',', '.'); ?> VNĐ
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/project1/Product/edit/<?php echo $product->getID(); ?>" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <a href="/project1/Product/delete/<?php echo $product->getID(); ?>" 
                               class="btn btn-outline-danger btn-sm flex-fill"
                               onclick="return confirm('Bạn có chắc muốn xóa?');">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>