<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5 text-center">
    <div class="py-5">
        <div style="font-size:100px; font-weight:900; color:#f0f0f0; line-height:1; letter-spacing:-4px;">404</div>
        <div style="margin-top:-20px;">
            <i class="fas fa-search" style="font-size:48px; color:#d70018; opacity:.6;"></i>
        </div>
        <h3 class="fw-bold mt-4 mb-2">Trang không tồn tại</h3>
        <p class="text-muted mb-4">
            Trang bạn đang tìm kiếm không tồn tại, đã bị xóa, hoặc URL không đúng.<br>
            <code class="text-danger" style="font-size:13px;"><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') ?></code>
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="/" class="btn btn-danger px-4 py-2 fw-bold" style="border-radius:10px;">
                <i class="fas fa-home me-2"></i>Về trang chủ
            </a>
            <a href="javascript:history.back()" class="btn btn-light px-4 py-2 fw-semibold" style="border-radius:10px;">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
