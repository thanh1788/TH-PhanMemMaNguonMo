<?php include 'app/views/shares/header.php'; ?>

<style>
    .checkout-wrap { background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #eee; overflow: hidden; }
    .checkout-header { padding: 20px 24px; border-bottom: 1px solid #f0f0f0; }
    .checkout-header h4 { margin: 0; font-size: 18px; font-weight: 800; color: #1a1a1a; display: flex; align-items: center; gap: 10px; }
    .checkout-header h4 i { color: #d70018; }
    .checkout-body { padding: 24px; }

    .form-label { font-weight: 600; font-size: 14px; color: #333; margin-bottom: 6px; }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e0e0e0;
        padding: 10px 14px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #d70018;
        box-shadow: 0 0 0 3px rgba(215,0,24,0.1);
        outline: none;
    }

    /* Steps indicator */
    .checkout-steps { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 28px; }
    .step { display: flex; flex-direction: column; align-items: center; gap: 6px; flex: 1; position: relative; }
    .step::after { content: ''; position: absolute; top: 16px; left: 50%; width: 100%; height: 2px; background: #eee; z-index: 0; }
    .step:last-child::after { display: none; }
    .step-circle { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; z-index: 1; position: relative; }
    .step.done .step-circle { background: #198754; color: #fff; }
    .step.active .step-circle { background: #d70018; color: #fff; }
    .step.pending .step-circle { background: #eee; color: #999; }
    .step-label { font-size: 11px; font-weight: 600; color: #999; }
    .step.active .step-label { color: #d70018; }
    .step.done .step-label { color: #198754; }

    /* Payment methods */
    .payment-option { border: 2px solid #eee; border-radius: 10px; padding: 14px 16px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
    .payment-option:hover { border-color: #d70018; background: #fff5f5; }
    .payment-option.selected { border-color: #d70018; background: #fff5f5; }
    .payment-option input[type="radio"] { accent-color: #d70018; width: 16px; height: 16px; }
    .payment-option-label { font-size: 14px; font-weight: 600; color: #333; }
    .payment-option-desc { font-size: 12px; color: #999; }
    .payment-icon { width: 40px; height: 28px; object-fit: contain; }

    /* Order summary */
    .order-summary { background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #eee; overflow: hidden; position: sticky; top: 80px; }
    .order-summary-header { background: #f8f9fa; padding: 16px 20px; border-bottom: 1px solid #eee; font-size: 15px; font-weight: 800; color: #1a1a1a; }
    .order-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; border-bottom: 1px solid #f5f5f5; }
    .order-item:last-of-type { border-bottom: none; }
    .order-item img { width: 52px; height: 52px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; padding: 3px; background: #fafafa; }
    .order-item-name { font-size: 13px; font-weight: 600; color: #1a1a1a; flex: 1; }
    .order-item-qty { font-size: 12px; color: #999; }
    .order-item-price { font-size: 14px; font-weight: 700; color: #d70018; white-space: nowrap; }

    .order-totals { padding: 16px 20px; }
    .total-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 8px; }
    .total-row .label { color: #666; }
    .total-row.grand { font-size: 17px; font-weight: 800; color: #d70018; padding-top: 10px; border-top: 2px solid #f0f0f0; margin-top: 4px; }

    .security-badges { display: flex; justify-content: center; gap: 16px; margin-top: 12px; flex-wrap: wrap; }
    .security-badge { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #999; }
    .security-badge i { color: #198754; }

    /* Nút đặt hàng */
    .btn-place-order {
        background: linear-gradient(135deg, #d70018, #ff424e);
        color: #fff !important;
        border: none;
        border-radius: 10px;
        padding: 15px;
        font-size: 16px;
        font-weight: 800;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.3px;
        display: block;
        text-align: center;
        text-decoration: none;
    }
    .btn-place-order:hover {
        background: linear-gradient(135deg, #b5001a, #d70018);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(215,0,24,0.35);
        color: #fff !important;
    }

    /* Select wrapper với spinner */
    .select-wrap { position: relative; }
    .select-wrap .form-select { padding-right: 36px; }
    .select-wrap .select-spinner {
        position: absolute;
        right: 36px;
        top: 50%;
        transform: translateY(-50%);
        color: #d70018;
        font-size: 13px;
        pointer-events: none;
    }
    .form-select:disabled { background-color: #f8f9fa; cursor: not-allowed; opacity: 0.7; }
</style>

<div class="container mt-4 mb-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background: none; padding: 0; font-size: 13px;">
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/Cart" style="color: #d70018; text-decoration: none;">Giỏ hàng</a></li>
            <li class="breadcrumb-item active text-muted">Thanh toán</li>
        </ol>
    </nav>

    <!-- Steps -->
    <div class="checkout-steps mb-4">
        <div class="step done">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <span class="step-label">Giỏ hàng</span>
        </div>
        <div class="step active">
            <div class="step-circle">2</div>
            <span class="step-label">Thanh toán</span>
        </div>
        <div class="step pending">
            <div class="step-circle">3</div>
            <span class="step-label">Xác nhận</span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Checkout form -->
        <div class="col-lg-7">
            <form action="/Cart/submitCheckout" method="POST" id="checkoutForm">

                <!-- Shipping info -->
                <div class="checkout-wrap mb-4">
                    <div class="checkout-header">
                        <h4><i class="fas fa-map-marker-alt"></i>Thông tin giao hàng</h4>
                    </div>
                    <div class="checkout-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-user me-1 text-danger"></i>Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Nhập họ và tên đầy đủ" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-phone me-1 text-danger"></i>Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" placeholder="VD: 0901234567" required
                                       pattern="[0-9]{10,11}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-envelope me-1 text-danger"></i>Email</label>
                                <input type="email" name="email" class="form-control" placeholder="VD: email@gmail.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-road me-1 text-danger"></i>Số nhà, tên đường <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" placeholder="VD: 123 Nguyễn Văn Linh" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-city me-1 text-danger"></i>Tỉnh/Thành phố <span class="text-danger">*</span>
                                </label>
                                <div class="select-wrap">
                                    <select name="city" id="sel-province" class="form-select" required>
                                        <option value="">-- Chọn tỉnh/thành --</option>
                                    </select>
                                    <span class="select-spinner" id="spin-province" style="display:none">
                                        <i class="fas fa-circle-notch fa-spin"></i>
                                    </span>
                                </div>
                                <!-- hidden: lưu tên hiển thị để gửi lên server -->
                                <input type="hidden" name="city_name" id="city-name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-map me-1 text-danger"></i>Quận/Huyện <span class="text-danger">*</span>
                                </label>
                                <div class="select-wrap">
                                    <select name="district" id="sel-district" class="form-select" required disabled>
                                        <option value="">-- Chọn quận/huyện --</option>
                                    </select>
                                    <span class="select-spinner" id="spin-district" style="display:none">
                                        <i class="fas fa-circle-notch fa-spin"></i>
                                    </span>
                                </div>
                                <input type="hidden" name="district_name" id="district-name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-map-pin me-1 text-danger"></i>Phường/Xã <span class="text-danger">*</span>
                                </label>
                                <div class="select-wrap">
                                    <select name="ward" id="sel-ward" class="form-select" required disabled>
                                        <option value="">-- Chọn phường/xã --</option>
                                    </select>
                                    <span class="select-spinner" id="spin-ward" style="display:none">
                                        <i class="fas fa-circle-notch fa-spin"></i>
                                    </span>
                                </div>
                                <input type="hidden" name="ward_name" id="ward-name">
                            </div>
                            <div class="col-12">
                                <label class="form-label"><i class="fas fa-sticky-note me-1 text-danger"></i>Ghi chú đơn hàng</label>
                                <textarea name="note" class="form-control" rows="2" placeholder="VD: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment method -->
                <div class="checkout-wrap mb-4">
                    <div class="checkout-header">
                        <h4><i class="fas fa-credit-card"></i>Phương thức thanh toán</h4>
                    </div>
                    <div class="checkout-body">
                        <label class="payment-option selected" onclick="selectPayment(this)">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <div style="font-size: 24px; color: #555;"><i class="fas fa-money-bill-wave"></i></div>
                            <div>
                                <div class="payment-option-label">Thanh toán khi nhận hàng (COD)</div>
                                <div class="payment-option-desc">Trả tiền mặt khi nhận được hàng</div>
                            </div>
                        </label>
                        <label class="payment-option" onclick="selectPayment(this)">
                            <input type="radio" name="payment_method" value="bank_transfer">
                            <div style="font-size: 24px; color: #0068ff;"><i class="fas fa-university"></i></div>
                            <div>
                                <div class="payment-option-label">Chuyển khoản ngân hàng</div>
                                <div class="payment-option-desc">Chuyển khoản qua tài khoản ngân hàng</div>
                            </div>
                        </label>
                        <label class="payment-option" onclick="selectPayment(this)">
                            <input type="radio" name="payment_method" value="momo">
                            <div style="font-size: 24px; color: #ae2070;"><i class="fas fa-wallet"></i></div>
                            <div>
                                <div class="payment-option-label">Ví MoMo</div>
                                <div class="payment-option-desc">Thanh toán qua ví điện tử MoMo</div>
                            </div>
                        </label>
                        <label class="payment-option" onclick="selectPayment(this)">
                            <input type="radio" name="payment_method" value="zalopay">
                            <div style="font-size: 24px; color: #0068ff;"><i class="fas fa-mobile-alt"></i></div>
                            <div>
                                <div class="payment-option-label">ZaloPay</div>
                                <div class="payment-option-desc">Thanh toán qua ví ZaloPay</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit button (mobile) -->
                <div class="d-lg-none">
                    <button type="submit" class="btn-place-order">
                        <i class="fas fa-check-circle me-2"></i>ĐẶT HÀNG NGAY
                    </button>
                    <div class="security-badges mt-3">
                        <div class="security-badge"><i class="fas fa-lock"></i> Thanh toán bảo mật</div>
                        <div class="security-badge"><i class="fas fa-shield-alt"></i> Bảo vệ người mua</div>
                        <div class="security-badge"><i class="fas fa-undo"></i> Đổi trả dễ dàng</div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Order summary -->
        <div class="col-lg-5">
            <div class="order-summary">
                <div class="order-summary-header">
                    <i class="fas fa-receipt me-2" style="color: #d70018;"></i>
                    Đơn hàng của bạn
                    <span style="font-size: 13px; color: #999; font-weight: 500;">(<?= count($cart) ?> sản phẩm)</span>
                </div>

                <?php foreach ($cart as $id => $item): ?>
                    <div class="order-item">
                        <?php $imgSrc = !empty($item['image']) ? "/public/uploads/products/" . $item['image'] : "https://via.placeholder.com/52"; ?>
                        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="order-item-name">
                            <?= htmlspecialchars($item['name']) ?>
                            <div class="order-item-qty">x<?= $item['quantity'] ?></div>
                        </div>
                        <div class="order-item-price"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</div>
                    </div>
                <?php endforeach; ?>

                <div class="order-totals">
                    <div class="total-row">
                        <span class="label">Tạm tính</span>
                        <span><?= number_format($total, 0, ',', '.') ?>đ</span>
                    </div>
                    <div class="total-row">
                        <span class="label">Phí vận chuyển</span>
                        <span style="color: #198754; font-weight: 600;">
                            <?= $total >= 500000 ? 'Miễn phí' : number_format(30000, 0, ',', '.') . 'đ' ?>
                        </span>
                    </div>
                    <div class="total-row">
                        <span class="label">Giảm giá</span>
                        <span style="color: #d70018;">-0đ</span>
                    </div>
                    <div class="total-row grand">
                        <span>Tổng thanh toán</span>
                        <span><?= number_format($total + ($total < 500000 ? 30000 : 0), 0, ',', '.') ?>đ</span>
                    </div>
                </div>

                <!-- Submit button (desktop) -->
                <div class="px-4 pb-4 d-none d-lg-block">
                    <button type="submit" form="checkoutForm" class="btn-place-order">
                        <i class="fas fa-check-circle me-2"></i>ĐẶT HÀNG NGAY
                    </button>
                    <div class="security-badges mt-3">
                        <div class="security-badge"><i class="fas fa-lock"></i> Bảo mật SSL</div>
                        <div class="security-badge"><i class="fas fa-shield-alt"></i> Bảo vệ người mua</div>
                        <div class="security-badge"><i class="fas fa-undo"></i> Đổi trả 30 ngày</div>
                    </div>
                </div>

                <div class="px-4 pb-4">
                    <a href="/Cart" style="color: #d70018; text-decoration: none; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-arrow-left"></i>Quay lại chỉnh sửa giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectPayment(label) {
    document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
    label.classList.add('selected');
}

/* ============================================================
   PROVINCES API - provinces.open-api.vn
   Luồng: load tỉnh → chọn tỉnh → load huyện → chọn huyện → load xã
   ============================================================ */
const API_BASE = 'https://provinces.open-api.vn/api';

const selProvince = document.getElementById('sel-province');
const selDistrict = document.getElementById('sel-district');
const selWard     = document.getElementById('sel-ward');
const spinProv    = document.getElementById('spin-province');
const spinDist    = document.getElementById('spin-district');
const spinWard    = document.getElementById('spin-ward');

// Hàm tiện ích: gọi API và trả về JSON
async function apiFetch(url) {
    const res = await fetch(url);
    if (!res.ok) throw new Error('API error: ' + res.status);
    return res.json();
}

// Hàm reset select về trạng thái ban đầu
function resetSelect(sel, placeholder, disabled = true) {
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    sel.disabled = disabled;
}

// Hàm điền options vào select
function fillSelect(sel, items, valueKey, labelKey) {
    items.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item[valueKey];
        opt.textContent = item[labelKey];
        sel.appendChild(opt);
    });
    sel.disabled = false;
}

// 1. Load danh sách 63 tỉnh/thành khi trang mở
async function loadProvinces() {
    spinProv.style.display = 'inline';
    try {
        const data = await apiFetch(`${API_BASE}/?depth=1`);
        // Sắp xếp theo tên
        data.sort((a, b) => a.name.localeCompare(b.name, 'vi'));
        fillSelect(selProvince, data, 'code', 'name');
    } catch (e) {
        selProvince.innerHTML = '<option value="">Lỗi tải dữ liệu, thử lại</option>';
        console.error('Load provinces failed:', e);
    } finally {
        spinProv.style.display = 'none';
    }
}

// 2. Khi chọn tỉnh → load quận/huyện
selProvince.addEventListener('change', async function () {
    const code = this.value;
    const name = this.options[this.selectedIndex].text;

    // Lưu tên tỉnh vào hidden input
    document.getElementById('city-name').value = code ? name : '';

    // Reset huyện và xã
    resetSelect(selDistrict, '-- Chọn quận/huyện --');
    resetSelect(selWard, '-- Chọn phường/xã --');
    document.getElementById('district-name').value = '';
    document.getElementById('ward-name').value = '';

    if (!code) return;

    spinDist.style.display = 'inline';
    try {
        const data = await apiFetch(`${API_BASE}/p/${code}?depth=2`);
        const districts = data.districts || [];
        districts.sort((a, b) => a.name.localeCompare(b.name, 'vi'));
        fillSelect(selDistrict, districts, 'code', 'name');
    } catch (e) {
        selDistrict.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        console.error('Load districts failed:', e);
    } finally {
        spinDist.style.display = 'none';
    }
});

// 3. Khi chọn huyện → load phường/xã
selDistrict.addEventListener('change', async function () {
    const code = this.value;
    const name = this.options[this.selectedIndex].text;

    document.getElementById('district-name').value = code ? name : '';

    resetSelect(selWard, '-- Chọn phường/xã --');
    document.getElementById('ward-name').value = '';

    if (!code) return;

    spinWard.style.display = 'inline';
    try {
        const data = await apiFetch(`${API_BASE}/d/${code}?depth=2`);
        const wards = data.wards || [];
        wards.sort((a, b) => a.name.localeCompare(b.name, 'vi'));
        fillSelect(selWard, wards, 'code', 'name');
    } catch (e) {
        selWard.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        console.error('Load wards failed:', e);
    } finally {
        spinWard.style.display = 'none';
    }
});

// 4. Khi chọn xã → lưu tên
selWard.addEventListener('change', function () {
    const name = this.options[this.selectedIndex].text;
    document.getElementById('ward-name').value = this.value ? name : '';
});

// Khởi động
loadProvinces();
</script>

<?php include 'app/views/shares/footer.php'; ?>
