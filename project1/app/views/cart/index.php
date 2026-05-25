<?php include 'app/views/shares/header.php'; ?>

<style>
    /* Giữ nguyên CSS cũ của bạn */
    .cart-wrap { background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #eee; overflow: hidden; }
    .cart-header { padding: 20px 24px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
    .cart-header h4 { margin: 0; font-size: 18px; font-weight: 800; color: #1a1a1a; display: flex; align-items: center; gap: 10px; }
    .cart-header h4 i { color: #d70018; }
    .cart-table { width: 100%; border-collapse: collapse; }
    .cart-table thead th { background: #f8f9fa; color: #555; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 16px; border-bottom: 2px solid #eee; }
    .cart-table tbody td { padding: 16px; vertical-align: middle; border-bottom: 1px solid #f5f5f5; font-size: 14px; }
    .cart-table tbody tr:last-child td { border-bottom: none; }
    .cart-table tbody tr:hover { background: #fafafa; }
    .product-img-cell img { width: 72px; height: 72px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; padding: 4px; background: #fafafa; }
    .product-name-cell { font-weight: 600; color: #1a1a1a; font-size: 14px; }
    .product-name-cell a { color: inherit; text-decoration: none; }
    .product-name-cell a:hover { color: #d70018; }
    .qty-control { display: flex; align-items: center; border: 1.5px solid #ddd; border-radius: 8px; overflow: hidden; width: fit-content; }
    .qty-btn { background: #f5f5f5; border: none; width: 32px; height: 32px; font-size: 16px; cursor: pointer; transition: background 0.2s; font-weight: 600; }
    .qty-btn:hover { background: #e0e0e0; }
    .qty-input { border: none; width: 44px; text-align: center; font-size: 14px; font-weight: 700; outline: none; background: #fff; }
    .price-cell { color: #d70018; font-weight: 700; font-size: 15px; }
    .subtotal-cell { color: #d70018; font-weight: 800; font-size: 15px; }
    .btn-remove { background: #fde8e8; color: #d70018; border: none; border-radius: 6px; padding: 6px 10px; font-size: 13px; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .btn-remove:hover { background: #d70018; color: #fff; }
    .cart-summary { background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #eee; padding: 24px; position: sticky; top: 80px; }
    .summary-title { font-size: 16px; font-weight: 800; color: #1a1a1a; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #f0f0f0; }
    .summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 14px; }
    .summary-row.total { font-size: 18px; font-weight: 800; color: #d70018; padding-top: 12px; border-top: 2px solid #f0f0f0; margin-top: 4px; }
    .summary-row .label { color: #666; }
    .btn-checkout { background: #d70018; color: #fff; border: none; border-radius: 10px; padding: 14px; font-size: 15px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.2s; text-decoration: none; display: block; text-align: center; margin-top: 16px; }
    .btn-checkout:hover { background: #b5001a; color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(215,0,24,0.3); }
    .btn-continue { color: #d70018; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; margin-top: 12px; justify-content: center; }
    .btn-continue:hover { text-decoration: underline; color: #b5001a; }
    .empty-cart { text-align: center; padding: 60px 20px; }
    .empty-cart i { font-size: 64px; color: #ddd; margin-bottom: 16px; }
    .empty-cart h5 { color: #999; font-weight: 500; margin-bottom: 20px; }
    .promo-input { display: flex; gap: 8px; margin-top: 12px; }
    .promo-input input { flex: 1; border: 1.5px solid #ddd; border-radius: 8px; padding: 9px 12px; font-size: 13px; outline: none; }
    .promo-input input:focus { border-color: #d70018; }
    .promo-input button { background: #1a1a1a; color: #fff; border: none; border-radius: 8px; padding: 9px 16px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; }
</style>

<div class="container mt-4 mb-5">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background: none; padding: 0; font-size: 13px;">
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;">Trang chủ</a></li>
            <li class="breadcrumb-item active text-muted">Giỏ hàng</li>
        </ol>
    </nav>

    <div id="cartContent">
        <?php if (empty($cart)): ?>
            <div class="cart-wrap">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h5>Giỏ hàng của bạn đang trống</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">Hãy thêm sản phẩm vào giỏ hàng để tiến hành mua sắm</p>
                    <a href="/" class="btn btn-danger px-5 py-2 fw-bold">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-wrap">
                        <div class="cart-header">
                            <h4><i class="fas fa-shopping-cart"></i>Giỏ hàng của bạn</h4>
                            <span style="font-size: 13px; color: #999;"><span id="totalItems"><?= array_sum(array_column($cart, 'quantity')) ?></span> sản phẩm</span>
                        </div>
                        <div class="table-responsive">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th style="width: 90px;">Hình ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th style="width: 120px; text-align: center;">Số lượng</th>
                                        <th style="width: 130px; text-align: right;">Thành tiền</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $id => $item): ?>
                                        <tr data-id="<?= $id ?>">
                                            <td class="product-img-cell">
                                                <?php $imgSrc = !empty($item['image']) ? "/public/uploads/products/" . $item['image'] : "https://via.placeholder.com/72"; ?>
                                                <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                            </td>
                                            <td>
                                                <div class="product-name-cell">
                                                    <a href="/Product/show/<?= $id ?>"><?= htmlspecialchars($item['name']) ?></a>
                                                </div>
                                                <div style="font-size: 13px; color: #d70018; font-weight: 600; margin-top: 4px;">
                                                    <?= number_format($item['price'], 0, ',', '.') ?>đ
                                                </div>
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="qty-control mx-auto">
                                                    <button type="button" class="qty-btn" onclick="changeQty(this, -1)">−</button>
                                                    <input type="number" value="<?= $item['quantity'] ?>" min="1" max="99" class="qty-input" onchange="updateCartQuantity(<?= $id ?>, this.value)">
                                                    <button type="button" class="qty-btn" onclick="changeQty(this, 1)">+</button>
                                                </div>
                                            </td>
                                            <td style="text-align: right;">
                                                <span class="subtotal-cell row-subtotal"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="/Cart/delete/<?= $id ?>" class="btn-remove" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <a href="/" style="color: #d70018; text-decoration: none; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-arrow-left me-1"></i>Tiếp tục mua sắm
                        </a>
                        </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary">
                        <div class="summary-title">
                            <i class="fas fa-receipt me-2" style="color: #d70018;"></i>Tóm tắt đơn hàng
                        </div>

                        <div class="summary-row">
                            <span class="label">Tạm tính</span>
                            <span id="cartSubtotal"><?= number_format($total, 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Phí vận chuyển</span>
                            <span id="shippingFee" style="color: #198754; font-weight: 600;">
                                <?= $total >= 500000 ? 'Miễn phí' : number_format(30000, 0, ',', '.') . 'đ' ?>
                            </span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Giảm giá</span>
                            <span style="color: #d70018;">-0đ</span>
                        </div>

                        <div class="promo-input">
                            <input type="text" placeholder="Nhập mã giảm giá...">
                            <button type="button">Áp dụng</button>
                        </div>

                        <div class="summary-row total">
                            <span>Tổng thanh toán</span>
                            <span id="cartTotal"><?= number_format($total + ($total < 500000 ? 30000 : 0), 0, ',', '.') ?>đ</span>
                        </div>

                        <a href="/Cart/checkout" class="btn-checkout">
                            <i class="fas fa-lock me-2"></i>Tiến hành thanh toán
                        </a>

                        <div class="text-center mt-3">
                            <p style="font-size: 11px; color: #999; margin-bottom: 8px;">Chấp nhận thanh toán</p>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <span style="background: #f5f5f5; border-radius: 5px; padding: 4px 8px; font-size: 11px; font-weight: 700; color: #555;">COD</span>
                                <span style="background: #f5f5f5; border-radius: 5px; padding: 4px 8px; font-size: 11px; font-weight: 700; color: #1a73e8;">VISA</span>
                                <span style="background: #f5f5f5; border-radius: 5px; padding: 4px 8px; font-size: 11px; font-weight: 700; color: #eb001b;">MC</span>
                                <span style="background: #f5f5f5; border-radius: 5px; padding: 4px 8px; font-size: 11px; font-weight: 700; color: #00b14f;">Momo</span>
                                <span style="background: #f5f5f5; border-radius: 5px; padding: 4px 8px; font-size: 11px; font-weight: 700; color: #0068ff;">ZaloPay</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function changeQty(btn, delta) {
    const input = btn.parentElement.querySelector('.qty-input');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
    
    // Lấy ID sản phẩm từ thuộc tính data-id của thẻ tr cha
    const productId = btn.closest('tr').getAttribute('data-id');
    updateCartQuantity(productId, val);
}

// Hàm gửi AJAX lên Controller PHP để cập nhật số lượng lập tức
function updateCartQuantity(id, qty) {
    if (qty < 1 || qty > 99) return;

    // Sử dụng Fetch API có sẵn trong mọi trình duyệt hiện đại
    fetch('/Cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}&quantity=${qty}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 1. Nếu số lượng bằng 0 hoặc rỗng (bị xóa ngầm định), tải lại trang
            if (data.totalItems === 0) {
                location.reload();
                return;
            }

            // 2. Định dạng lại tiền tệ theo chuẩn Việt Nam (đ)
            const formatter = new Intl.NumberFormat('vi-VN');

            // 3. Cập nhật thành tiền của dòng sản phẩm vừa sửa
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row && data.itemSubtotal !== undefined) {
                row.querySelector('.row-subtotal').textContent = formatter.format(data.itemSubtotal) + 'đ';
            }

            // 4. Cập nhật các thông tin tổng kết giỏ hàng
            document.getElementById('totalItems').textContent = data.totalItems;
            document.getElementById('cartSubtotal').textContent = formatter.format(data.subtotal) + 'đ';
            
            // Cập nhật phí vận chuyển hiển thị
            const shippingElement = document.getElementById('shippingFee');
            if (data.shippingFee === 0) {
                shippingElement.textContent = 'Miễn phí';
                shippingElement.style.color = '#198754';
            } else {
                shippingElement.textContent = formatter.format(data.shippingFee) + 'đ';
                shippingElement.style.color = '#555';
            }
            
            // Cập nhật tổng số tiền cuối cùng
            document.getElementById('cartTotal').textContent = formatter.format(data.total) + 'đ';
        } else {
            alert(data.message || 'Có lỗi xảy ra khi cập nhật giỏ hàng.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

<?php include 'app/views/shares/footer.php'; ?>