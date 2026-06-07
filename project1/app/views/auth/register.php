<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                <div class="card-header text-center py-4" style="background:#d70018;">
                    <h4 class="mb-0 text-white fw-bold"><i class="fas fa-user-plus me-2"></i>Tạo tài khoản</h4>
                    <p class="text-white mb-0 mt-1" style="opacity:.85; font-size:13px;">Tham gia MixiTech ngay hôm nay!</p>
                </div>
                <div class="card-body p-4">

                    <form action="/Auth/saveRegister" method="POST" novalidate>
                        <!-- Họ tên -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="full_name"
                                       class="form-control border-start-0 <?= !empty($errors['full_name']) ? 'is-invalid' : '' ?>"
                                       value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                                       placeholder="Nguyễn Văn A" autocomplete="name" required>
                                <?php if (!empty($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['full_name']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="email"
                                       class="form-control border-start-0 <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                       placeholder="example@email.com" autocomplete="email" required>
                                <?php if (!empty($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Mật khẩu -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="regPwd"
                                       class="form-control border-start-0 border-end-0 <?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
                                       placeholder="Ít nhất 6 ký tự" autocomplete="new-password" required>
                                <button type="button" class="input-group-text bg-light" onclick="togglePwd('regPwd', this)">
                                    <i class="fas fa-eye text-muted"></i>
                                </button>
                                <?php if (!empty($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
                                <?php endif; ?>
                            </div>
                            <!-- Thanh sức mạnh mật khẩu -->
                            <div class="mt-2">
                                <div class="progress" style="height:4px; border-radius:4px;">
                                    <div id="pwdStrength" class="progress-bar" style="width:0%; transition:.3s;"></div>
                                </div>
                                <small id="pwdStrengthText" class="text-muted"></small>
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password_confirm" id="regPwdConfirm"
                                       class="form-control border-start-0 border-end-0 <?= !empty($errors['password_confirm']) ? 'is-invalid' : '' ?>"
                                       placeholder="Nhập lại mật khẩu" autocomplete="new-password" required>
                                <button type="button" class="input-group-text bg-light" onclick="togglePwd('regPwdConfirm', this)">
                                    <i class="fas fa-eye text-muted"></i>
                                </button>
                                <?php if (!empty($errors['password_confirm'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password_confirm']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold py-2" style="background:#d70018; color:#fff; border-radius:8px;">
                            <i class="fas fa-user-plus me-2"></i>Tạo tài khoản
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center mb-0 small">
                        Đã có tài khoản? <a href="/Auth/login" class="text-danger fw-semibold">Đăng nhập</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Đánh giá độ mạnh mật khẩu
document.getElementById('regPwd').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('pwdStrength');
    const txt = document.getElementById('pwdStrengthText');
    let score = 0;
    if (val.length >= 6)               score++;
    if (val.length >= 10)              score++;
    if (/[A-Z]/.test(val))             score++;
    if (/[0-9]/.test(val))             score++;
    if (/[^A-Za-z0-9]/.test(val))      score++;

    const levels = [
        { pct: '0%',   cls: '',          text: '' },
        { pct: '25%',  cls: 'bg-danger', text: 'Rất yếu' },
        { pct: '50%',  cls: 'bg-warning', text: 'Yếu' },
        { pct: '75%',  cls: 'bg-info',   text: 'Trung bình' },
        { pct: '90%',  cls: 'bg-primary',text: 'Mạnh' },
        { pct: '100%', cls: 'bg-success',text: 'Rất mạnh' },
    ];
    const l = levels[score] || levels[0];
    bar.style.width = l.pct;
    bar.className   = 'progress-bar ' + l.cls;
    txt.textContent = l.text;
    txt.className   = 'small ' + (l.cls.replace('bg-', 'text-') || 'text-muted');
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
