<?php include 'app/views/shares/header.php'; ?>

<style>
    :root {
        --cps-red: #d70018;
        --cps-red-light: #fef2f2;
        --cps-dark: #222222;
        --cps-gray: #707070;
        --bg-body: #f8f9fa;
        --radius-lg: 12px;
        --radius-sm: 8px;
    }

    body { 
        background-color: var(--bg-body); 
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
        color: var(--cps-dark);
    }

    /* SIDEBAR CELLPHONES STYLE */
    .sidebar-menu { 
        background: #fff; 
        border-radius: var(--radius-lg); 
        box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
        padding: 12px 0; 
        border: 1px solid #eaeaea;
    }
    .sidebar-menu .nav-link { 
        color: #4a4a4a; 
        font-weight: 600; 
        font-size: 14px; 
        padding: 10px 18px; 
        display: flex; 
        align-items: center; 
        transition: all 0.25s ease;
    }
    .sidebar-menu .nav-link:hover { 
        background: var(--cps-red-light); 
        color: var(--cps-red); 
        padding-left: 24px; 
    }
    .sidebar-menu .nav-link i { 
        width: 28px; 
        font-size: 16px; 
        color: var(--cps-gray); 
        transition: color 0.25s;
    }
    .sidebar-menu .nav-link:hover i { 
        color: var(--cps-red); 
    }

    /* CAROUSEL BANNER */
    .custom-carousel {
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    /* PRODUCT CARD STYLE */
    .product-item {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 16px;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid #f0f0f0;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-item:hover {
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        transform: translateY(-6px);
        border-color: #e0e0e0;
    }
    .product-img {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        overflow: hidden;
    }
    .product-img img { 
        max-height: 100%; 
        max-width: 100%; 
        object-fit: contain;
        transition: transform 0.4s ease; 
    }
    .product-item:hover .product-img img {
        transform: scale(1.04);
    }
    
    .product-name {
        font-size: 14.5px;
        font-weight: 600;
        color: var(--cps-dark);
        line-height: 1.4;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 10px;
        transition: color 0.2s;
    }
    .product-item:hover .product-name {
        color: var(--cps-red);
    }

    .price-box {
        margin-bottom: 8px;
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
    }
    .price-now { 
        color: var(--cps-red); 
        font-weight: 700; 
        font-size: 16px; 
    }
    .price-old { 
        color: #999; 
        text-decoration: line-through; 
        font-size: 12.5px; 
        margin-left: 6px; 
    }

    .promo-tag {
        background: #f2f4f7;
        color: #444;
        font-size: 11px;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: var(--radius-sm);
        display: inline-block;
        border: 1px dashed #d1d5db;
        width: 100%;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    /* SECTION TITLE */
    .block-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        margin-bottom: 16px;
    }
    .block-title h2 { 
        font-size: 20px; 
        font-weight: 700; 
        text-transform: uppercase; 
        margin-bottom: 0; 
        color: #333;
        position: relative;
    }

    /* ADMIN ACTIONS BAR */
    .admin-actions {
        background: #f8f9fa;
        padding: 6px 10px;
        border-radius: var(--radius-sm);
    }

    html { scroll-behavior: smooth; }
</style>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar Danh mục -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sidebar-menu sticky-top" style="top: 24px; z-index: 100;">
                <div class="px-3 py-2 font-weight-bold text-uppercase border-bottom mb-2" style="font-size: 12px; color: #888; letter-spacing: 0.5px;">
                    Danh mục nổi bật
                </div>
                <nav class="nav flex-column">
                    <?php foreach (array_keys($productsByCategory) as $catName): ?>
                        <a class="nav-link" href="#cat-<?php echo md5($catName); ?>">
                            <i class="fas fa-mobile-alt"></i> <?php echo htmlspecialchars($catName); ?>
                        </a>
                    <?php endforeach; ?>
                    <a class="nav-link text-primary mt-2 border-top pt-2" href="/Category">
                        <i class="fas fa-cog"></i> Quản lý danh mục
                    </a>
                </nav>
            </div>
        </div>

        <!-- Banner & Sản phẩm -->
        <div class="col-lg-9 col-md-12">
            <!-- Carousel Banner -->
            <div id="cpsBanner" class="carousel slide custom-carousel mb-4" data-ride="carousel" data-interval="3500">
                <ol class="carousel-indicators">
                    <li data-target="#cpsBanner" data-slide-to="0" class="active"></li>
                    <li data-target="#cpsBanner" data-slide-to="1"></li>
                    <li data-target="#cpsBanner" data-slide-to="2"></li>
                </ol>

                <div class="carousel-inner" style="height: 320px;">
                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=1200" class="d-block w-100 h-100" style="object-fit: cover;" alt="Banner 1">
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1200" class="d-block w-100 h-100" style="object-fit: cover;" alt="Banner 2">
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1200" class="d-block w-100 h-100" style="object-fit: cover;" alt="Banner 3">
                    </div>
                </div>

                <a class="carousel-control-prev" href="#cpsBanner" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Trước</span>
                </a>
                <a class="carousel-control-next" href="#cpsBanner" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Sau</span>
                </a>
            </div>

            <!-- Danh sách sản phẩm -->
            <?php if (empty($productsByCategory)): ?>
                <div class="bg-white text-center p-5 rounded-lg border shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3" style="opacity: 0.3;">
                    <h5 class="text-muted font-weight-normal">Chưa có sản phẩm nào trong hệ thống.</h5>
                </div>
            <?php else: ?>
                <?php foreach ($productsByCategory as $categoryName => $items): ?>
                    <div class="block-title" id="cat-<?php echo md5($categoryName); ?>">
                        <h2><?php echo htmlspecialchars($categoryName); ?></h2>
                        <a href="#" class="text-danger font-weight-bold small text-decoration-none">Xem tất cả <i class="fas fa-chevron-right ml-1" style="font-size: 10px;"></i></a>
                    </div>

                    <div class="row mx-n1">
                        <?php foreach ($items as $product): ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-6 px-1 mb-2">
                                <div class="product-item">
                                    <div>
                                        <!-- Hình ảnh -->
                                        <div class="product-img">
                                            <?php $imgSrc = (filter_var($product->image, FILTER_VALIDATE_URL)) ? $product->image : "/public/uploads/products/" . $product->image; ?>
                                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo $product->name; ?>" loading="lazy">
                                        </div>
                                        
                                        <!-- Tên sản phẩm -->
                                        <h3 class="product-name">
                                            <a href="/Product/show/<?php echo $product->id; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($product->name); ?>
                                            </a>
                                        </h3>
                                        
                                        <!-- Giá cả -->
                                        <div class="price-box">
                                            <span class="price-now"><?php echo number_format($product->price, 0, ',', '.'); ?>đ</span>
                                            <span class="price-old"><?php echo number_format($product->price * 1.15, 0, ',', '.'); ?>đ</span>
                                        </div>
                                        
                                        <!-- Nhãn khuyến mãi -->
                                        <div class="promo-tag mb-3">
                                            <i class="fas fa-gift text-danger mr-1"></i> Thu cũ đổi mới - Trợ giá 1 triệu
                                        </div>
                                    </div>
                                    
                                    <!-- Nút thao tác Admin -->
                                    <div class="admin-actions d-flex justify-content-between align-items-center">
                                        <small class="text-muted" style="font-size: 11px;">Hành động:</small>
                                        <div>
                                            <a href="/Product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-link text-warning p-1 mr-2" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <a href="/Product/delete/<?php echo $product->id; ?>" class="btn btn-sm btn-link text-danger p-1" onclick="return confirm('Xóa sản phẩm này?')" title="Xóa"><i class="fas fa-trash"></i></a>
                                        </div>
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

<div class="mt-5"></div> 
<?php include 'app/views/shares/footer.php'; ?>