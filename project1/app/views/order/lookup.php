<?php include 'app/views/shares/header.php'; ?>

<style>
    /* ===== LOOKUP HERO ===== */
    .lookup-hero {
        background: linear-gradient(135deg, #d70018 0%, #8a0012 100%);
        padding: 48px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .lookup-hero::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0; right: 0;
        height: 48px;
        background: #f5f5f5;
        clip-path: ellipse(55% 100% at 50% 100%);
    }
    .lookup-hero h1 {
        color: #fff;
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .lookup-hero p { color: rgba(255,255,255,0.85); font-size: 15px; margin: 0; }

    /* ===== LOOKUP CARD ===== */
    .lookup-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        padding: 36px 40px;
        margin-top: -48px;
        position: relative;
        z-index: 2;
    }
    .lookup-card .form-control {
        border: 1.5px solid #e0e0e0;
        border-radius: 10px 0 0 10px;
        padding: 13px 18px;
        font-size: 15px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .lookup-card .form-control:focus {
        border-color: #d70018;
        box-shadow: 0 0 0 3px rgba(215,0,24,0.1);
        outline: none;
    }
    .btn-lookup {
        background: #d70018;
        color: #fff;
        border: none;
        border-radius: 0 10px 10px 0;
        padding: 13px 28px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-lookup:hover { background: #b5001a; }

    /* ===== ORDER LIST ===== */
    .order-card {
        background: #fff;
        border-radius: 12px;
        border: 1.5px solid #eee;
        padding: 20px 24px;
        margin-bottom: 14px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        text-decoration: none;
        color: inherit;
    }
    .order-card:hover {
        border-color: #d70018;
        box-shadow: 0 6px 20px rgba(215,0,24,0.1);
        transform: translateY(-2px);
        color: inherit;
    }
    .order-id {
        font-size: 16px;
        font-weight: 800;
        color: #1a1a1a;
    }
    .order-id span {
        color: #d70018;
    }
    .order-meta {
        font-size: 13px;
        color: #777;
        margin-top: 4px;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .order-meta i { color: #d70018; margin-right: 4px; }
    .order-total {
        font-size: 18px;
        font-weight: 800;
        color: #d70018;
        text-align: right;
    }
    .order-total small {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: #999;
    }
    .order-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff3cd;
        color: #856404;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .btn-view-detail {
        background: #d70018;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-view-detail:hover { background: #b5001a; color: #fff; }

    /* ===== EMPTY / INFO STATES ===== */
    .info-box {
        background: #fff;
        border-radius: 12px;
        padding: 40px 24px;
        text-align: center;
        border: 1.5px dashed #ddd;
    }
    .info-box i { font-size: 48px; color: #ddd; margin-bottom: 16px; }
    .info-box p { color: #999; font-size: 15px; margin: 0; }

    /* ===== HOW TO ===== */
    .how-step {
        text-align: center;
        padding: 20px 16px;
    }
    .how-step .icon-wrap {
        width: 56px;
        height: 56px;
        background: #fff0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 22px;
        color: #d70018;
    }
    .how-step h6 { font-weight: 700; font-size: 14px; margin-bottom: 6px; }
    .how-step p { font-size: 13px; color: #777; margin: 0; }
</style>

<!-- HERO -->
<div class="lookup-hero">
    <div class="container text-center">
        <h1><i class="fas fa-search me-2"></i>Tra cứu đơn hàng</h1>
        <p>Nhập số điện thoại đặt hàng để xem toàn bộ lịch sử mua sắm của bạn</p>
    </div>
</div>

<div class="container mb-5" style="max-width: 760px;">

    <!-- LOOKUP CARD -->
    <div class="lookup-card mb-4">
        <h5 class="fw-bold mb-1" style="font-size: 16px;">
            <i class="fas fa-phone me-2 text-danger"></i>Nhập số điện thoại đặt hàng
        </h5>
        <p class="text-muted mb-4" style="font-size: 13px;">Số điện thoại bạn đã dùng khi đặt hàng tại MixiTech</p>

        <form method="POST" action="/Order/lookup">
            <div class="d-flex">
                <input type="tel" name="phone" class="form-control"
                       placeholder="VD: 0901234567"
                       value="<?= htmlspecialchars($phone ?? '') ?>"
                       maxlength="11" required>
                <button type="submit" class="btn-lookup">
                    <i class="fas fa-search me-2"></i>Tra cứu
                </button>
            </div>
        </form>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mt-3 mb-0 rounded-3" style="font-size: 14px;">
                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- KẾT QUẢ -->
    <?php if ($orders !== null && !empty($orders)): ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0" style="font-size: 15px;">
                <i class="fas fa-list me-2 text-danger"></i>
                Tìm thấy <strong><?= count($orders) ?></strong> đơn hàng với SĐT
                <span style="color: #d70018;"><?= htmlspecialchars($phone) ?></span>
            </h6>
        </div>

        <?php foreach ($orders as $order): ?>
            <a href="/Order/detail/<?= $order->id ?>?phone=<?= urlencode($phone) ?>" class="order-card">
                <div>
                    <div class="order-id">
                        Đơn hàng <span>#<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="order-meta">
                        <span><i class="fas fa-clock"></i><?= date('H:i - d/m/Y', strtotime($order->created_at)) ?></span>
                        <span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars(mb_strimwidth($order->address, 0, 45, '...')) ?></span>
                    </div>
        <div class="mt-2">
                        <?php
                        $allStatuses = [
                            'pending'   => ['label'=>'Chờ xác nhận', 'bg'=>'#fff3cd', 'color'=>'#856404', 'icon'=>'fa-clock'],
                            'confirmed' => ['label'=>'Đã xác nhận',  'bg'=>'#cff4fc', 'color'=>'#055160', 'icon'=>'fa-check-circle'],
                            'shipping'  => ['label'=>'Đang giao',    'bg'=>'#cfe2ff', 'color'=>'#084298', 'icon'=>'fa-truck'],
                            'delivered' => ['label'=>'Đã giao',      'bg'=>'#d1e7dd', 'color'=>'#0a3622', 'icon'=>'fa-box-open'],
                            'cancelled' => ['label'=>'Đã huỷ',       'bg'=>'#f8d7da', 'color'=>'#842029', 'icon'=>'fa-times-circle'],
                        ];
                        $st = $allStatuses[$order->status] ?? $allStatuses['pending'];
                        ?>
                        <span class="order-status" style="background:<?= $st['bg'] ?>;color:<?= $st['color'] ?>;">
                            <i class="fas <?= $st['icon'] ?>" style="font-size:10px;"></i>
                            <?= $st['label'] ?>
                        </span>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    <div class="order-total">
                        <?= number_format($order->total_price, 0, ',', '.') ?>đ
                        <small>Tổng thanh toán</small>
                    </div>
                    <span class="btn-view-detail">
                        Xem chi tiết <i class="fas fa-chevron-right"></i>
                    </span>
                </div>
            </a>
        <?php endforeach; ?>

    <?php elseif ($orders === null): ?>
        <!-- Trạng thái ban đầu chưa tìm kiếm -->
        <div class="info-box mb-4">
            <i class="fas fa-box-open d-block"></i>
            <p>Nhập số điện thoại ở trên để tra cứu đơn hàng của bạn</p>
        </div>

        <!-- Hướng dẫn -->
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3 text-center" style="font-size: 14px; color: #555; text-transform: uppercase; letter-spacing: 0.5px;">
                Cách tra cứu đơn hàng
            </h6>
            <div class="row g-0">
                <div class="col-4">
                    <div class="how-step">
                        <div class="icon-wrap"><i class="fas fa-phone"></i></div>
                        <h6>Nhập SĐT</h6>
                        <p>Số điện thoại bạn đã dùng khi đặt hàng</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="how-step">
                        <div class="icon-wrap"><i class="fas fa-search"></i></div>
                        <h6>Tra cứu</h6>
                        <p>Nhấn nút tra cứu để tìm đơn hàng</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="how-step">
                        <div class="icon-wrap"><i class="fas fa-eye"></i></div>
                        <h6>Xem chi tiết</h6>
                        <p>Chọn đơn hàng để xem trạng thái và sản phẩm</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include 'app/views/shares/footer.php'; ?>
