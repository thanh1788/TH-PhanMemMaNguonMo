<?php include 'app/views/shares/header.php'; ?>

<style>
    .detail-wrap { background: #fff; border-radius: 14px; padding: 28px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #eee; }
    .product-title { font-size: 22px; font-weight: 800; color: #1a1a1a; line-height: 1.4; margin-bottom: 12px; }
    .img-main { width: 100%; max-height: 420px; object-fit: contain; border-radius: 10px; }
    .img-thumb-wrap { display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
    .img-thumb { width: 64px; height: 64px; object-fit: contain; border: 2px solid #eee; border-radius: 8px; cursor: pointer; padding: 4px; background: #fafafa; transition: border-color 0.2s; }
    .img-thumb:hover, .img-thumb.active { border-color: #d70018; }

    .price-block { background: #fff5f5; border-radius: 10px; padding: 16px 20px; margin-bottom: 18px; }
    .price-now { color: #d70018; font-size: 30px; font-weight: 800; }
    .price-old { text-decoration: line-through; color: #999; font-size: 16px; margin-left: 10px; }
    .discount-badge { background: #d70018; color: #fff; font-size: 12px; font-weight: 700; padding: 3px 8px; border-radius: 5px; margin-left: 8px; }

    .policy-box { border: 1px solid #eee; border-radius: 10px; padding: 16px; margin-top: 16px; }
    .policy-item { font-size: 13.5px; margin-bottom: 10px; display: flex; align-items: flex-start; gap: 10px; }
    .policy-item:last-child { margin-bottom: 0; }
    .policy-item i { color: #d70018; width: 18px; margin-top: 2px; flex-shrink: 0; }

    .btn-buy-now {
        background: linear-gradient(135deg, #ff424e, #d70018);
        color: #fff;
        font-weight: 700;
        padding: 14px;
        border-radius: 10px;
        border: none;
        width: 100%;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: block;
        text-align: center;
    }
    .btn-buy-now:hover { background: linear-gradient(135deg, #d70018, #a0001a); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(215,0,24,0.3); }

    .btn-add-cart-detail {
        background: #fff;
        color: #d70018;
        border: 2px solid #d70018;
        font-weight: 700;
        padding: 12px;
        border-radius: 10px;
        width: 100%;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: block;
        text-align: center;
    }
    .btn-add-cart-detail:hover { background: #d70018; color: #fff; }

    .tab-nav { border-bottom: 2px solid #eee; margin-bottom: 0; }
    .tab-nav .nav-link { color: #555; font-weight: 600; font-size: 14px; border: none; border-bottom: 3px solid transparent; margin-bottom: -2px; padding: 10px 18px; border-radius: 0; }
    .tab-nav .nav-link.active { color: #d70018; border-bottom-color: #d70018; background: none; }
    .tab-content-box { background: #fff; border: 1px solid #eee; border-top: none; border-radius: 0 0 10px 10px; padding: 24px; }

    .spec-table td { padding: 8px 12px; font-size: 14px; border-bottom: 1px solid #f5f5f5; }
    .spec-table td:first-child { color: #666; font-weight: 500; width: 40%; }
    .spec-table td:last-child { font-weight: 600; color: #1a1a1a; }

    .qty-control { display: flex; align-items: center; gap: 0; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; width: fit-content; }
    .qty-btn { background: #f5f5f5; border: none; width: 36px; height: 36px; font-size: 16px; cursor: pointer; transition: background 0.2s; }
    .qty-btn:hover { background: #e0e0e0; }
    .qty-input { border: none; width: 50px; text-align: center; font-size: 15px; font-weight: 600; outline: none; }
</style>

<div class="container mt-4 mb-5">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background: none; padding: 0; font-size: 13px;">
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;"><?= htmlspecialchars($product->category_name ?? 'Sản phẩm') ?></a></li>
            <li class="breadcrumb-item active text-muted"><?= htmlspecialchars($product->name) ?></li>
        </ol>
    </nav>

    <form action="/Cart/add/<?= $product->id ?>" method="POST">
        <div class="detail-wrap mb-4">
            <div class="row g-4">
                <div class="col-md-5">
                    <?php
                    $imgSrc = !empty($product->image)
                        ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : "/public/uploads/products/" . $product->image)
                        : "https://via.placeholder.com/400x400?text=No+Image";
                    ?>
                    <img src="<?= $imgSrc ?>" class="img-main" id="mainImg" alt="<?= htmlspecialchars($product->name) ?>">
                    <div class="img-thumb-wrap">
                        <img src="<?= $imgSrc ?>" class="img-thumb active" onclick="changeImg(this, '<?= $imgSrc ?>')" alt="">
                    </div>
                </div>

                <div class="col-md-7">
                    <h1 class="product-title"><?= htmlspecialchars($product->name) ?></h1>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="color: #ffc107; font-size: 14px;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span style="font-size: 13px; color: #666;">(4.5/5 - 128 đánh giá)</span>
                        <span style="font-size: 13px; color: #999;">|</span>
                        <span style="font-size: 13px; color: #666;">Đã bán: 256</span>
                    </div>

                    <div class="price-block">
                        <div class="d-flex align-items-baseline flex-wrap gap-1">
                            <span class="price-now"><?= number_format($product->price, 0, ',', '.') ?>đ</span>
                            <span class="price-old"><?= number_format($product->price * 1.15, 0, ',', '.') ?>đ</span>
                            <span class="discount-badge">-15%</span>
                        </div>
                        <div class="mt-2" style="font-size: 13px; color: #666;">
                            <i class="fas fa-tag me-1" style="color: #d70018;"></i>
                            Tiết kiệm: <strong style="color: #d70018;"><?= number_format($product->price * 0.15, 0, ',', '.') ?>đ</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="fw-bold mb-2" style="font-size: 14px;">Điểm nổi bật:</p>
                        <ul style="font-size: 13.5px; color: #444; padding-left: 20px; line-height: 2;">
                            <li>Bảo hành chính hãng 12 tháng tại tất cả cửa hàng</li>
                            <li>Hỗ trợ trả góp 0% lãi suất qua thẻ tín dụng</li>
                            <li>Miễn phí vận chuyển toàn quốc cho đơn từ 500K</li>
                            <li>Đổi trả trong 30 ngày nếu lỗi nhà sản xuất</li>
                        </ul>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span style="font-size: 14px; font-weight: 600;">Số lượng:</span>
                        <div class="qty-control">
                            <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                            <input type="number" name="quantity" class="qty-input" id="qtyInput" value="1" min="1" max="99">
                            <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <button type="submit" class="btn-buy-now">
                            <i class="fas fa-bolt me-2"></i>MUA NGAY
                        </button>
                        <button type="submit" class="btn-add-cart-detail">
                            <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                        </button>
                    </div>

                    <?php if ($_isAdmin): ?>
                    <div class="d-flex gap-2 mb-3">
                        <a href="/Product/edit/<?= $product->id ?>" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa
                        </a>
                        <a href="/Product/delete/<?= $product->id ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Xóa sản phẩm này?')">
                            <i class="fas fa-trash me-1"></i>Xóa
                        </a>
                    </div>
                    <?php endif; ?>

                    <div class="policy-box">
                        <div class="policy-item">
                            <i class="fas fa-truck"></i>
                            <span>Miễn phí giao hàng toàn quốc cho đơn hàng từ 500.000đ</span>
                        </div>
                        <div class="policy-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Bảo hành chính hãng 12 tháng, 1 đổi 1 trong 30 ngày nếu lỗi</span>
                        </div>
                        <div class="policy-item">
                            <i class="fas fa-box-open"></i>
                            <span>Hộp, Sách HDSD, Cáp sạc, Củ sạc (tùy model)</span>
                        </div>
                        <div class="policy-item">
                            <i class="fas fa-credit-card"></i>
                            <span>Trả góp 0% qua thẻ tín dụng Visa, Mastercard, JCB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="mb-4">
        <ul class="nav tab-nav" id="productTab">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabDesc">
                    <i class="fas fa-align-left me-2"></i>Mô tả sản phẩm
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabSpec">
                    <i class="fas fa-list-ul me-2"></i>Thông số kỹ thuật
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabReview">
                    <i class="fas fa-star me-2"></i>Đánh giá (128)
                </button>
            </li>
        </ul>
        <div class="tab-content tab-content-box">
            <div class="tab-pane fade show active" id="tabDesc">
                <div style="font-size: 14.5px; line-height: 1.9; color: #444;">
                    <?= nl2br(htmlspecialchars($product->description ?? 'Đang cập nhật nội dung chi tiết cho sản phẩm này...')) ?>
                </div>
            </div>
            <div class="tab-pane fade" id="tabSpec">
                <table class="table spec-table mb-0">
                    <tbody>
                        <tr><td>Danh mục</td><td><?= htmlspecialchars($product->category_name ?? 'N/A') ?></td></tr>
                        <tr><td>Giá bán</td><td style="color: #d70018; font-weight: 700;"><?= number_format($product->price, 0, ',', '.') ?>đ</td></tr>
                        <tr><td>Bảo hành</td><td>12 tháng chính hãng</td></tr>
                        <tr><td>Xuất xứ</td><td>Chính hãng</td></tr>
                        <tr><td>Tình trạng</td><td><span class="badge bg-success">Còn hàng</span></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="tabReview">
                <div class="text-center py-4">
                    <div style="font-size: 48px; font-weight: 800; color: #d70018;">4.5</div>
                    <div style="color: #ffc107; font-size: 20px; margin: 8px 0;">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="text-muted">128 đánh giá từ khách hàng</p>
                    <p class="text-muted small">Tính năng đánh giá đang được phát triển.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeImg(el, src) {
    document.getElementById('mainImg').src = src;
    document.querySelectorAll('.img-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}
function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
}
</script>

<?php include 'app/views/shares/footer.php'; ?>