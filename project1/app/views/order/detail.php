<?php include 'app/views/shares/header.php'; ?>

<style>
    .detail-section {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #eee;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .detail-section-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 800;
        color: #1a1a1a;
    }
    .detail-section-header i { color: #d70018; font-size: 16px; }
    .detail-section-body { padding: 20px 24px; }

    /* Status timeline */
    .status-timeline {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
        padding: 8px 0;
    }
    .status-timeline::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 10%;
        right: 10%;
        height: 3px;
        background: #eee;
        z-index: 0;
    }
    .status-timeline::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 10%;
        width: var(--progress, 0%); /* động theo trạng thái */
        height: 3px;
        background: #d70018;
        z-index: 1;
        transition: width 0.6s ease;
    }
    .timeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex: 1;
        position: relative;
        z-index: 2;
    }
    .timeline-dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        border: 3px solid #eee;
        background: #fff;
        transition: all 0.3s;
    }
    .timeline-dot.done  { background: #d70018; border-color: #d70018; color: #fff; }
    .timeline-dot.active { background: #fff; border-color: #d70018; color: #d70018; }
    .timeline-dot.pending { background: #fff; border-color: #ddd; color: #ccc; }
    .timeline-label { font-size: 12px; font-weight: 600; color: #999; text-align: center; }
    .timeline-label.done   { color: #d70018; }
    .timeline-label.active { color: #d70018; }

    /* Info grid */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 576px) { .info-grid { grid-template-columns: 1fr; } }
    .info-item label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
        margin-bottom: 4px;
        display: block;
    }
    .info-item p {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    /* Product rows */
    .product-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .product-row:last-child { border-bottom: none; }
    .product-row img {
        width: 64px;
        height: 64px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #eee;
        padding: 4px;
        background: #fafafa;
        flex-shrink: 0;
    }
    .product-row-name { font-size: 14px; font-weight: 600; color: #1a1a1a; flex: 1; }
    .product-row-name a { color: inherit; text-decoration: none; }
    .product-row-name a:hover { color: #d70018; }
    .product-row-qty { font-size: 13px; color: #777; margin-top: 3px; }
    .product-row-price { font-size: 15px; font-weight: 800; color: #d70018; white-space: nowrap; }

    /* Summary */
    .summary-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 10px; }
    .summary-row .lbl { color: #666; }
    .summary-row.grand { font-size: 18px; font-weight: 800; color: #d70018; padding-top: 12px; border-top: 2px solid #f0f0f0; margin-top: 4px; }

    /* Order ID badge */
    .order-id-badge {
        background: linear-gradient(135deg, #d70018, #ff424e);
        color: #fff;
        border-radius: 10px;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }
    .order-id-badge .oid { font-size: 22px; font-weight: 800; }
    .order-id-badge .odate { font-size: 13px; opacity: 0.85; margin-top: 2px; }
    .order-status-badge {
        background: rgba(255,255,255,0.2);
        border: 1.5px solid rgba(255,255,255,0.4);
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-back {
        color: #d70018;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 16px;
    }
    .btn-back:hover { color: #b5001a; text-decoration: underline; }

    .btn-reorder {
        background: #d70018;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-reorder:hover { background: #b5001a; color: #fff; transform: translateY(-1px); }
</style>

<div class="container mt-4 mb-5" style="max-width: 820px;">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb" style="background: none; padding: 0; font-size: 13px;">
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/Order/lookup" style="color: #d70018; text-decoration: none;">Tra cứu đơn hàng</a></li>
            <li class="breadcrumb-item active text-muted">Đơn #<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?></li>
        </ol>
    </nav>

    <a href="/Order/lookup?phone=<?= urlencode($order->phone) ?>" class="btn-back">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
    </a>

    <!-- ORDER ID BANNER -->
    <?php
    $allStatuses = [
        'pending'   => ['label'=>'Chờ xác nhận', 'icon'=>'fa-clock'],
        'confirmed' => ['label'=>'Đã xác nhận',  'icon'=>'fa-check-circle'],
        'shipping'  => ['label'=>'Đang giao',    'icon'=>'fa-truck'],
        'delivered' => ['label'=>'Đã giao',      'icon'=>'fa-box-open'],
        'cancelled' => ['label'=>'Đã huỷ',       'icon'=>'fa-times-circle'],
    ];
    $currentStatus = $allStatuses[$order->status] ?? $allStatuses['pending'];
    ?>
    <div class="order-id-badge">
        <div>
            <div class="oid"><i class="fas fa-receipt me-2"></i>Đơn hàng #<?= str_pad($order->id, 6, '0', STR_PAD_LEFT) ?></div>
            <div class="odate"><i class="fas fa-clock me-1"></i>Đặt lúc <?= date('H:i - d/m/Y', strtotime($order->created_at)) ?></div>
        </div>
        <div class="order-status-badge">
            <i class="fas <?= $currentStatus['icon'] ?>"></i> <?= $currentStatus['label'] ?>
        </div>
    </div>

    <!-- TRẠNG THÁI ĐƠN HÀNG -->
    <div class="detail-section">
        <div class="detail-section-header">
            <i class="fas fa-truck"></i> Trạng thái đơn hàng
        </div>
        <div class="detail-section-body">
            <?php
            // Thứ tự các bước (không tính cancelled)
            $steps = [
                'pending'   => ['label' => 'Đã đặt hàng', 'icon' => 'fa-check'],
                'confirmed' => ['label' => 'Đã xác nhận', 'icon' => 'fa-check-circle'],
                'shipping'  => ['label' => 'Đang giao',   'icon' => 'fa-truck'],
                'delivered' => ['label' => 'Đã nhận hàng','icon' => 'fa-home'],
            ];
            $stepKeys    = array_keys($steps);
            $currentIdx  = array_search($order->status, $stepKeys);
            $isCancelled = ($order->status === 'cancelled');

            // Tính % thanh tiến trình (0%, 33%, 66%, 100%)
            $progressPct = $isCancelled ? 0 : ($currentIdx !== false ? round($currentIdx / (count($steps)-1) * 100) : 0);
            ?>

            <?php if ($isCancelled): ?>
                <div class="text-center py-3">
                    <div style="width:56px;height:56px;background:#f8d7da;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:24px;color:#842029;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <p class="fw-bold mb-1" style="color:#842029;">Đơn hàng đã bị huỷ</p>
                    <p class="text-muted mb-0" style="font-size:13px;">Vui lòng liên hệ hotline nếu cần hỗ trợ.</p>
                </div>
            <?php else: ?>
                <div class="status-timeline" style="--progress:<?= $progressPct ?>%;">
                    <?php foreach ($steps as $key => $step):
                        $idx      = array_search($key, $stepKeys);
                        $curIdx   = $currentIdx !== false ? $currentIdx : 0;
                        $dotClass = $idx < $curIdx ? 'done' : ($idx === $curIdx ? 'active' : 'pending');
                        $lblClass = $dotClass;
                    ?>
                        <div class="timeline-step">
                            <div class="timeline-dot <?= $dotClass ?>">
                                <i class="fas <?= $step['icon'] ?>"></i>
                            </div>
                            <div class="timeline-label <?= $lblClass ?>"><?= $step['label'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php
                $messages = [
                    'pending'   => ['text' => 'Đơn hàng đang chờ xác nhận. Chúng tôi sẽ liên hệ trong vòng <strong>30 phút</strong>.', 'color' => '#856404'],
                    'confirmed' => ['text' => 'Đơn hàng đã được xác nhận và đang chuẩn bị hàng.', 'color' => '#055160'],
                    'shipping'  => ['text' => 'Đơn hàng đang trên đường giao đến bạn. Vui lòng chú ý điện thoại.', 'color' => '#084298'],
                    'delivered' => ['text' => 'Đơn hàng đã giao thành công. Cảm ơn bạn đã mua sắm tại MixiTech!', 'color' => '#0a3622'],
                ];
                $msg = $messages[$order->status] ?? $messages['pending'];
                ?>
                <p class="text-center mt-3 mb-0" style="font-size:13px;color:<?= $msg['color'] ?>;">
                    <i class="fas fa-info-circle me-1"></i><?= $msg['text'] ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">

            <!-- SẢN PHẨM ĐÃ ĐẶT -->
            <div class="detail-section">
                <div class="detail-section-header">
                    <i class="fas fa-shopping-bag"></i>
                    Sản phẩm đã đặt
                    <span style="font-size: 13px; color: #999; font-weight: 500;">(<?= count($orderDetails) ?> sản phẩm)</span>
                </div>
                <div class="detail-section-body" style="padding-top: 8px; padding-bottom: 8px;">
                    <?php foreach ($orderDetails as $item): ?>
                        <div class="product-row">
                            <?php
                            $imgSrc = !empty($item->image)
                                ? "/public/uploads/products/" . $item->image
                                : "https://via.placeholder.com/64";
                            ?>
                            <img src="<?= $imgSrc ?>" alt="">
                            <div class="flex-1" style="flex: 1;">
                                <div class="product-row-name">
                                    <?php if (!empty($item->product_id_ref)): ?>
                                        <a href="/Product/show/<?= $item->product_id_ref ?>">
                                            <?= htmlspecialchars($item->product_name ?? 'Sản phẩm #' . $item->product_id) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= htmlspecialchars($item->product_name ?? 'Sản phẩm #' . $item->product_id) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="product-row-qty">
                                    <?= number_format($item->price, 0, ',', '.') ?>đ &times; <?= $item->quantity ?>
                                </div>
                            </div>
                            <div class="product-row-price">
                                <?= number_format($item->price * $item->quantity, 0, ',', '.') ?>đ
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- THÔNG TIN GIAO HÀNG -->
            <div class="detail-section">
                <div class="detail-section-header">
                    <i class="fas fa-map-marker-alt"></i> Thông tin giao hàng
                </div>
                <div class="detail-section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Người nhận</label>
                            <p><?= htmlspecialchars($order->customer_name) ?></p>
                        </div>
                        <div class="info-item">
                            <label>Số điện thoại</label>
                            <p><?= htmlspecialchars($order->phone) ?></p>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <label>Địa chỉ giao hàng</label>
                            <p><?= htmlspecialchars($order->address) ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-5">

            <!-- TỔNG KẾT ĐƠN HÀNG -->
            <div class="detail-section">
                <div class="detail-section-header">
                    <i class="fas fa-calculator"></i> Tổng kết đơn hàng
                </div>
                <div class="detail-section-body">
                    <?php
                    $subtotal = 0;
                    foreach ($orderDetails as $item) {
                        $subtotal += $item->price * $item->quantity;
                    }
                    $shipping = $subtotal >= 500000 ? 0 : 30000;
                    ?>
                    <div class="summary-row">
                        <span class="lbl">Tạm tính (<?= count($orderDetails) ?> sản phẩm)</span>
                        <span><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                    </div>
                    <div class="summary-row">
                        <span class="lbl">Phí vận chuyển</span>
                        <span style="color: #198754; font-weight: 600;">
                            <?= $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . 'đ' ?>
                        </span>
                    </div>
                    <div class="summary-row grand">
                        <span>Tổng thanh toán</span>
                        <span><?= number_format($order->total_price, 0, ',', '.') ?>đ</span>
                    </div>

                    <div class="mt-3 p-3 rounded-3" style="background: #f8f9fa; font-size: 13px;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-money-bill-wave text-success"></i>
                            <span class="fw-bold">Thanh toán khi nhận hàng (COD)</span>
                        </div>
                        <div class="text-muted">Bạn sẽ thanh toán khi nhận được hàng.</div>
                    </div>
                </div>
            </div>

            <!-- HỖ TRỢ -->
            <div class="detail-section">
                <div class="detail-section-header">
                    <i class="fas fa-headset"></i> Cần hỗ trợ?
                </div>
                <div class="detail-section-body">
                    <p style="font-size: 13px; color: #666; margin-bottom: 12px;">
                        Nếu có thắc mắc về đơn hàng, vui lòng liên hệ:
                    </p>
                    <div class="d-flex flex-column gap-2">
                        <a href="tel:18002097" style="font-size: 14px; font-weight: 700; color: #d70018; text-decoration: none;">
                            <i class="fas fa-phone me-2"></i>1800 2097 (Miễn phí)
                        </a>
                        <span style="font-size: 13px; color: #777;">
                            <i class="fas fa-clock me-2 text-danger"></i>Hỗ trợ 8:00 - 22:00 mỗi ngày
                        </span>
                    </div>
                    <hr style="border-color: #f0f0f0;">
                    <a href="/" class="btn-reorder w-100 justify-content-center">
                        <i class="fas fa-redo"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
