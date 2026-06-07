<footer class="mt-5" style="background: #1a1a1a; color: #ccc;">
    <!-- Main footer -->
    <div class="container py-5">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-white mb-3">
                    <i class="fas fa-mobile-alt me-2" style="color: #d70018;"></i>MixiTech Việt Nam
                </h5>
                <p class="small" style="line-height: 1.8; color: #aaa;">
                    Hệ thống bán lẻ thiết bị công nghệ di động, laptop, máy tính bảng và phụ kiện chính hãng. Cam kết giá tốt nhất, bảo hành uy tín, hỗ trợ trả góp 0%.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-decoration-none" style="color: #aaa; font-size: 20px; transition: color 0.2s;" onmouseover="this.style.color='#1877f2'" onmouseout="this.style.color='#aaa'">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-decoration-none" style="color: #aaa; font-size: 20px; transition: color 0.2s;" onmouseover="this.style.color='#ff0000'" onmouseout="this.style.color='#aaa'">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="text-decoration-none" style="color: #aaa; font-size: 20px; transition: color 0.2s;" onmouseover="this.style.color='#e1306c'" onmouseout="this.style.color='#aaa'">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-decoration-none" style="color: #aaa; font-size: 20px; transition: color 0.2s;" onmouseover="this.style.color='#0a66c2'" onmouseout="this.style.color='#aaa'">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>

            <!-- Quick links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="fw-bold text-white mb-3 text-uppercase" style="font-size: 13px; letter-spacing: 0.5px;">Mua sắm</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="/" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Trang chủ</a></li>
                    <li class="mb-2"><a href="/Cart" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Giỏ hàng</a></li>
                    <li class="mb-2"><a href="/Cart/checkout" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Thanh toán</a></li>
                    <li class="mb-2"><a href="/Order/lookup" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Tra cứu đơn hàng</a></li>
                </ul>
            </div>

            <!-- Admin links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="fw-bold text-white mb-3 text-uppercase" style="font-size: 13px; letter-spacing: 0.5px;">Quản trị</h6>
                <ul class="list-unstyled small">
                    <?php if (isset($_isAdmin) && $_isAdmin): ?>
                    <li class="mb-2"><a href="/Product/add" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Thêm sản phẩm</a></li>
                    <li class="mb-2"><a href="/Category" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Danh mục</a></li>
                    <li class="mb-2"><a href="/Category/add" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Thêm danh mục</a></li>
                    <li class="mb-2"><a href="/Order/admin" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Quản lý đơn hàng</a></li>
                    <?php else: ?>
                    <li class="mb-2"><a href="/Auth/login" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Đăng nhập</a></li>
                    <li class="mb-2"><a href="/Auth/register" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Đăng ký</a></li>
                    <?php if (isset($_loggedIn) && $_loggedIn): ?>
                    <li class="mb-2"><a href="/User/profile" class="text-decoration-none" style="color: #aaa;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'"><i class="fas fa-chevron-right me-1" style="font-size: 10px;"></i>Hồ sơ cá nhân</a></li>
                    <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold text-white mb-3 text-uppercase" style="font-size: 13px; letter-spacing: 0.5px;">Liên hệ & Hỗ trợ</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <i class="fas fa-phone-alt mt-1" style="color: #d70018; width: 16px;"></i>
                        <span style="color: #aaa;">Hotline: <strong class="text-white">1800 2097</strong> (Miễn phí, 8h-22h)</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <i class="fas fa-envelope mt-1" style="color: #d70018; width: 16px;"></i>
                        <span style="color: #aaa;">support@mixitech.vn</span>
                    </li>
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <i class="fas fa-map-marker-alt mt-1" style="color: #d70018; width: 16px;"></i>
                        <span style="color: #aaa;">475A Điện Biên Phủ, P.25, Q.Bình Thạnh, TP.HCM</span>
                    </li>
                </ul>
                <!-- Badges -->
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    <span class="badge rounded-pill px-3 py-2" style="background: #2a2a2a; color: #aaa; font-size: 11px; font-weight: 500;">
                        <i class="fas fa-shield-alt me-1" style="color: #d70018;"></i> Bảo hành 12 tháng
                    </span>
                    <span class="badge rounded-pill px-3 py-2" style="background: #2a2a2a; color: #aaa; font-size: 11px; font-weight: 500;">
                        <i class="fas fa-truck me-1" style="color: #d70018;"></i> Giao hàng toàn quốc
                    </span>
                    <span class="badge rounded-pill px-3 py-2" style="background: #2a2a2a; color: #aaa; font-size: 11px; font-weight: 500;">
                        <i class="fas fa-undo me-1" style="color: #d70018;"></i> Đổi trả 30 ngày
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom bar -->
    <div style="background: #111; padding: 14px 0; border-top: 1px solid #2a2a2a;">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 small" style="color: #666;">© 2026 MixiTech Vietnam. All rights reserved.</p>
            <div class="d-flex gap-3">
                <a href="#" class="text-decoration-none small" style="color: #666;" onmouseover="this.style.color='#aaa'" onmouseout="this.style.color='#666'">Chính sách bảo mật</a>
                <a href="#" class="text-decoration-none small" style="color: #666;" onmouseover="this.style.color='#aaa'" onmouseout="this.style.color='#666'">Điều khoản sử dụng</a>
                <a href="#" class="text-decoration-none small" style="color: #666;" onmouseover="this.style.color='#aaa'" onmouseout="this.style.color='#666'">Chính sách đổi trả</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to top button -->
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" 
    style="display:none; position:fixed; bottom:24px; right:24px; background:#d70018; color:#fff; border:none; border-radius:50%; width:44px; height:44px; font-size:18px; cursor:pointer; box-shadow:0 4px 12px rgba(215,0,24,0.4); z-index:999; transition:all 0.2s;">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Back to top
window.addEventListener('scroll', function() {
    const btn = document.getElementById('backToTop');
    if (window.scrollY > 300) {
        btn.style.display = 'flex';
        btn.style.alignItems = 'center';
        btn.style.justifyContent = 'center';
    } else {
        btn.style.display = 'none';
    }
});
</script>
</body>
</html>
