<?php include 'app/views/shares/header.php'; ?>

<style>
    :root { --cps-red: #d70018; }
    body { background-color: #f4f4f4; }
    
    .detail-container { background: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .product-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    
    .img-main { width: 100%; border-radius: 10px; object-fit: contain; max-height: 450px; }
    
    .price-block { background: #fdf2f2; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
    .price-now { color: var(--cps-red); font-size: 28px; font-weight: 700; }
    .price-old { text-decoration: line-through; color: #707070; margin-left: 10px; }
    
    .policy-box { border: 1px solid #e5e5e5; border-radius: 10px; padding: 15px; margin-top: 20px; }
    .policy-item { font-size: 14px; margin-bottom: 8px; display: flex; align-items: center; }
    .policy-item i { color: var(--cps-red); width: 25px; }

    .btn-buy-now { background: linear-gradient(180deg,#ff424e,#ff424e); color: #fff; font-weight: 700; padding: 12px; border-radius: 8px; border: none; width: 100%; text-transform: uppercase; }
    .btn-buy-now:hover { background: #d70018; color: #fff; }
    
    .product-desc { line-height: 1.8; color: #444; }
</style>

<div class="container mt-4 mb-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent p-0 mb-3">
        <li class="breadcrumb-item"><a href="/Product" class="text-danger">Trang chủ</a></li>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($product->category_name ?? 'Sản phẩm'); ?></li>
      </ol>
    </nav>

    <div class="detail-container">
        <div class="row">
            <div class="col-md-5 text-center border-right">
                <?php $imgSrc = (filter_var($product->image, FILTER_VALIDATE_URL)) ? $product->image : "/public/uploads/products/" . $product->image; ?>
                <img src="<?php echo $imgSrc; ?>" class="img-main" alt="<?php echo $product->name; ?>">
            </div>

            <div class="col-md-7 pl-md-5">
                <h1 class="product-title"><?php echo htmlspecialchars($product->name); ?></h1>
                
                <div class="price-block">
                    <span class="price-now"><?php echo number_format($product->price, 0, ',', '.'); ?>đ</span>
                    <span class="price-old"><?php echo number_format($product->price * 1.15, 0, ',', '.'); ?>đ</span>
                    <span class="badge badge-danger ml-2">-15%</span>
                </div>

                <div class="short-desc mb-4">
                    <p class="font-weight-bold mb-1">Thông số nổi bật:</p>
                    <ul class="small text-muted">
                        <li>Bảo hành chính hãng 12 tháng.</li>
                        <li>Hỗ trợ trả góp 0% lãi suất qua thẻ tín dụng.</li>
                        <li>Miễn phí vận chuyển toàn quốc.</li>
                    </ul>
                </div>

                <button class="btn-buy-now mb-2">MUA NGAY <br> <small>(Giao tận nơi hoặc nhận tại cửa hàng)</small></button>
                
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-info w-50 mr-1 py-2 font-weight-bold">THÊM GIỎ HÀNG</button>
                    <a href="/Product/edit/<?php echo $product->id; ?>" class="btn btn-outline-warning w-50 ml-1 py-2 font-weight-bold">CHỈNH SỬA</a>
                </div>

                <div class="policy-box">
                    <div class="policy-item"><i class="fas fa-truck"></i> Miễn phí giao hàng cho đơn hàng từ 500k</div>
                    <div class="policy-item"><i class="fas fa-shield-alt"></i> Bảo hành chính hãng 1 đổi 1 trong 30 ngày</div>
                    <div class="policy-item"><i class="fas fa-box-open"></i> Bộ sản phẩm gồm: Hộp, Sách HDSD, Cáp, Củ sạc</div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold text-danger" id="desc-tab" data-toggle="tab" href="#desc">ĐẶC ĐIỂM NỔI BẬT</a>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-4 bg-light product-desc rounded-bottom">
                    <div class="tab-pane fade show active" id="desc">
                        <?php echo nl2br(htmlspecialchars($product->description ?? 'Đang cập nhật nội dung chi tiết cho sản phẩm này...')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>