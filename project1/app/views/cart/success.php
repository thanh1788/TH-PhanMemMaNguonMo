<?php include 'app/views/shares/header.php'; ?>

<style>
    .success-wrap {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        border: 1px solid #eee;
        padding: 60px 40px;
        text-align: center;
        max-width: 560px;
        margin: 0 auto;
    }
    .success-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #198754, #20c997);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 36px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(25,135,84,0.3);
    }
    .success-title { font-size: 24px; font-weight: 800; color: #1a1a1a; margin-bottom: 12px; }
    .success-desc { font-size: 15px; color: #666; line-height: 1.7; margin-bottom: 28px; }
    .order-info { background: #f8f9fa; border-radius: 10px; padding: 16px 20px; margin-bottom: 28px; text-align: left; }
    .order-info-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 8px; }
    .order-info-row:last-child { margin-bottom: 0; }
    .order-info-row .label { color: #666; }
    .order-info-row .value { font-weight: 600; color: #1a1a1a; }
    .btn-home { background: #d70018; color: #fff; border: none; border-radius: 10px; padding: 13px 32px; font-size: 15px; font-weight: 700; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-home:hover { background: #b5001a; color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(215,0,24,0.3); }
    .btn-lookup { background: #fff; color: #d70018; border: 2px solid #d70018; border-radius: 10px; padding: 11px 28px; font-size: 14px; font-weight: 700; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-lookup:hover { background: #fff0f0; color: #d70018; }
</style>

<div class="container mt-5 mb-5">
    <div class="success-wrap">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h2 class="success-title">Đặt hàng thành công!</h2>
        <p class="success-desc">
            Cảm ơn bạn đã tin tưởng mua sắm tại <strong>MixiTech</strong>.<br>
            Đơn hàng của bạn đã được ghi nhận. Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.
        </p>

        <div class="order-info">
            <div class="order-info-row">
                <span class="label"><i class="fas fa-clock me-2" style="color: #d70018;"></i>Thời gian đặt hàng</span>
                <span class="value"><?= date('H:i - d/m/Y') ?></span>
            </div>
            <div class="order-info-row">
                <span class="label"><i class="fas fa-truck me-2" style="color: #d70018;"></i>Dự kiến giao hàng</span>
                <span class="value">2-3 ngày làm việc</span>
            </div>
            <div class="order-info-row">
                <span class="label"><i class="fas fa-headset me-2" style="color: #d70018;"></i>Hỗ trợ</span>
                <span class="value">1800 2097 (Miễn phí)</span>
            </div>
        </div>

        <a href="/" class="btn-home me-2">
            <i class="fas fa-home me-2"></i>Tiếp tục mua sắm
        </a>
        <a href="/Order/lookup" class="btn-lookup">
            <i class="fas fa-truck me-2"></i>Tra cứu đơn hàng
        </a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
