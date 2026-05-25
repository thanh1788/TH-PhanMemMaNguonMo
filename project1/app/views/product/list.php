<?php include 'app/views/shares/header.php'; ?>

<style>
    /* ===== SIDEBAR ===== */
    .sidebar-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
        border: 1px solid #eee;
    }
    .sidebar-header {
        background: #d70018;
        color: #fff;
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-bottom: 1px solid #f5f5f5;
        transition: all 0.2s;
    }
    .sidebar-link:hover, .sidebar-link.active {
        background: #fff0f0;
        color: #d70018;
        padding-left: 22px;
    }
    .sidebar-link i { width: 20px; color: #999; font-size: 14px; }
    .sidebar-link:hover i, .sidebar-link.active i { color: #d70018; }

    /* ===== BANNER ===== */
    .banner-carousel {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .banner-carousel .carousel-item img {
        height: 300px;
        object-fit: cover;
        width: 100%;
    }

    /* ===== PROMO STRIP ===== */
    .promo-strip {
        background: #fff;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }
    .promo-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #333;
    }
    .promo-item i { font-size: 22px; color: #d70018; }

    /* ===== SECTION TITLE ===== */
    .section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 28px 0 14px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    .section-title h2 {
        font-size: 18px;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0;
        text-transform: uppercase;
        position: relative;
        padding-left: 14px;
    }
    .section-title h2::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #d70018;
        border-radius: 2px;
    }
    .section-title a {
        font-size: 13px;
        color: #d70018;
        text-decoration: none;
        font-weight: 600;
    }
    .section-title a:hover { text-decoration: underline; }

    /* ===== PRODUCT CARD ===== */
    .product-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        position: relative;
    }
    .product-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transform: translateY(-5px);
        border-color: #e0e0e0;
    }
    .product-card-img {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: #fafafa;
        overflow: hidden;
    }
    .product-card-img img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }
    .product-card:hover .product-card-img img { transform: scale(1.06); }

    .product-card-body {
        padding: 12px 14px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .product-card-name {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.4;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 8px;
        text-decoration: none;
        transition: color 0.2s;
    }
    .product-card-name:hover { color: #d70018; }
    .product-card:hover .product-card-name { color: #d70018; }

    .price-now { color: #d70018; font-weight: 700; font-size: 16px; }
    .price-old { color: #999; text-decoration: line-through; font-size: 12px; margin-left: 6px; }
    .discount-badge {
        background: #d70018;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 6px;
    }

    .promo-tag {
        background: #f8f9fa;
        border: 1px dashed #ddd;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 11px;
        color: #555;
        margin-top: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn-add-cart {
        background: #d70018;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 10px;
    }
    .btn-add-cart:hover { background: #b5001a; color: #fff; }

    .admin-bar {
        padding: 6px 14px 10px;
        display: flex;
        justify-content: flex-end;
        gap: 6px;
        border-top: 1px solid #f5f5f5;
    }
    .admin-bar a {
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
    }
    .btn-edit { background: #fff3cd; color: #856404; }
    .btn-edit:hover { background: #ffc107; color: #000; }
    .btn-del { background: #f8d7da; color: #842029; }
    .btn-del:hover { background: #dc3545; color: #fff; }

    /* ===== SEARCH RESULT HEADER ===== */
    .search-result-header {
        background: #fff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 16px;
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        background: #fff;
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
        border: 1px solid #eee;
    }
    .empty-state img { width: 80px; opacity: 0.3; margin-bottom: 16px; }
</style>

<div class="container mt-4 mb-5">
    <div class="row g-3">

        <!-- SIDEBAR -->
        <div class="col-lg-2 d-none d-lg-block">
            <div class="sidebar-card sticky-top" style="top: 80px;">
                <div class="sidebar-header">
                    <i class="fas fa-th-large"></i> Danh mục
                </div>
                <a href="/" class="sidebar-link <?= empty($_GET['cat']) && empty($_GET['q']) ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Tất cả sản phẩm
                </a>
                <?php foreach (array_keys($productsByCategory) as $catName): ?>
                    <a class="sidebar-link" href="#cat-<?= md5($catName) ?>">
                        <i class="fas fa-mobile-alt"></i> <?= htmlspecialchars($catName) ?>
                    </a>
                <?php endforeach; ?>
                <div style="border-top: 2px solid #f0f0f0; margin-top: 4px; padding-top: 4px;">
                    <a href="/Category" class="sidebar-link" style="color: #0d6efd;">
                        <i class="fas fa-cog"></i> Quản lý danh mục
                    </a>
                    <a href="/Product/add" class="sidebar-link" style="color: #198754;">
                        <i class="fas fa-plus-circle"></i> Thêm sản phẩm
                    </a>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-lg-10">

            <?php if (!empty($_GET['q'])): ?>
                <!-- SEARCH RESULT HEADER -->
                <div class="search-result-header">
                    <div>
                        <i class="fas fa-search me-2 text-muted"></i>
                        Kết quả tìm kiếm cho: <strong>"<?= htmlspecialchars($_GET['q']) ?>"</strong>
                    </div>
                    <a href="/" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Xóa tìm kiếm
                    </a>
                </div>
            <?php else: ?>
                <!-- BANNER CAROUSEL -->
                <div id="mainBanner" class="carousel slide banner-carousel mb-4" data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="2"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=1200&q=80" alt="Banner iPhone">
                            <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.4); border-radius: 10px; padding: 12px 20px;">
                                <h5 class="fw-bold">iPhone 16 Series</h5>
                                <p class="small mb-0">Trải nghiệm đỉnh cao - Giá ưu đãi nhất</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1200&q=80" alt="Banner Laptop">
                            <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.4); border-radius: 10px; padding: 12px 20px;">
                                <h5 class="fw-bold">Laptop Gaming & Văn phòng</h5>
                                <p class="small mb-0">Hiệu năng mạnh mẽ - Thiết kế sang trọng</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=1200&q=80" alt="Banner Phụ kiện">
                            <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.4); border-radius: 10px; padding: 12px 20px;">
                                <h5 class="fw-bold">Phụ kiện chính hãng</h5>
                                <p class="small mb-0">Đa dạng - Chất lượng - Giá tốt</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#mainBanner" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#mainBanner" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

                <!-- PROMO STRIP -->
                <div class="promo-strip mb-4">
                    <div class="promo-item">
                        <i class="fas fa-truck"></i>
                        <div><strong>Miễn phí vận chuyển</strong><br><small class="text-muted">Đơn hàng từ 500K</small></div>
                    </div>
                    <div class="promo-item">
                        <i class="fas fa-shield-alt"></i>
                        <div><strong>Bảo hành chính hãng</strong><br><small class="text-muted">1 đổi 1 trong 30 ngày</small></div>
                    </div>
                    <div class="promo-item">
                        <i class="fas fa-credit-card"></i>
                        <div><strong>Trả góp 0%</strong><br><small class="text-muted">Qua thẻ tín dụng</small></div>
                    </div>
                    <div class="promo-item">
                        <i class="fas fa-headset"></i>
                        <div><strong>Hỗ trợ 24/7</strong><br><small class="text-muted">Hotline: 1800 2097</small></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- PRODUCT SECTIONS -->
            <?php if (empty($productsByCategory)): ?>
                <div class="empty-state">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="empty">
                    <h5 class="text-muted fw-normal">
                        <?= !empty($_GET['q']) ? 'Không tìm thấy sản phẩm phù hợp.' : 'Chưa có sản phẩm nào trong hệ thống.' ?>
                    </h5>
                    <?php if (!empty($_GET['q'])): ?>
                        <a href="/" class="btn btn-danger mt-3">Xem tất cả sản phẩm</a>
                    <?php else: ?>
                        <a href="/Product/add" class="btn btn-danger mt-3"><i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($productsByCategory as $categoryName => $items): ?>
                    <div class="section-title" id="cat-<?= md5($categoryName) ?>">
                        <h2><?= htmlspecialchars($categoryName) ?></h2>
                        <a href="#">Xem tất cả <i class="fas fa-chevron-right ms-1" style="font-size: 10px;"></i></a>
                    </div>

                    <div class="row g-2 mb-2">
                        <?php foreach ($items as $product): ?>
                            <div class="col-xl-3 col-lg-4 col-md-4 col-6">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <?php
                                        $imgSrc = !empty($product->image)
                                            ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : "/public/uploads/products/" . $product->image)
                                            : "https://via.placeholder.com/200x200?text=No+Image";
                                        ?>
                                        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($product->name) ?>" loading="lazy">
                                    </div>
                                    <div class="product-card-body">
                                        <a href="/Product/show/<?= $product->id ?>" class="product-card-name">
                                            <?= htmlspecialchars($product->name) ?>
                                        </a>
                                        <div class="d-flex align-items-baseline flex-wrap">
                                            <span class="price-now"><?= number_format($product->price, 0, ',', '.') ?>đ</span>
                                            <span class="price-old"><?= number_format($product->price * 1.15, 0, ',', '.') ?>đ</span>
                                            <span class="discount-badge">-15%</span>
                                        </div>
                                        <div class="promo-tag">
                                            <i class="fas fa-gift me-1" style="color: #d70018;"></i> Thu cũ đổi mới - Trợ giá 1 triệu
                                        </div>
                                        <a href="/Cart/add/<?= $product->id ?>" class="btn-add-cart">
                                            <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                                        </a>
                                    </div>
                                    <div class="admin-bar">
                                        <a href="/Product/edit/<?= $product->id ?>" class="btn-edit">
                                            <i class="fas fa-edit me-1"></i>Sửa
                                        </a>
                                        <a href="/Product/delete/<?= $product->id ?>" class="btn-del"
                                           onclick="return confirm('Xóa sản phẩm này?')">
                                            <i class="fas fa-trash me-1"></i>Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
