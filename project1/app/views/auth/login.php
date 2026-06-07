<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                <div class="card-header text-center py-4" style="background:#d70018;">
                    <h4 class="mb-0 text-white fw-bold"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</h4>
                    <p class="text-white mb-0 mt-1" style="opacity:.85; font-size:13px;">Chào mừng trở lại MixiTech!</p>
                </div>
                <div class="card-body p-4">

                    <?php if (!empty($errors['general'])): ?>
                        <div class="alert alert-danger py-2"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($errors['general']) ?></div>
                    <?php endif; ?>

                    <form action="/Auth/doLogin" method="POST" novalidate>
                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
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
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label fw-semibold mb-0">Mật khẩu</label>
                                <a href="/Auth/forgotPassword" class="small text-danger" style="font-size:12px;">Quên mật khẩu?</a>
                            </div>
                            <div class="input-group mt-1">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="loginPassword"
                                       class="form-control border-start-0 border-end-0 <?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
                                       placeholder="Nhập mật khẩu" autocomplete="current-password" required>
                                <button type="button" class="input-group-text bg-light" onclick="togglePwd('loginPassword', this)" title="Hiện/ẩn mật khẩu">
                                    <i class="fas fa-eye text-muted"></i>
                                </button>
                                <?php if (!empty($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input"
                                       <?= !empty($_POST['remember']) ? 'checked' : '' ?>>
                                <label for="remember" class="form-check-label small">Ghi nhớ đăng nhập (30 ngày)</label>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold py-2" style="background:#d70018; color:#fff; border-radius:8px;">
                            <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center mb-0 small">
                        Chưa có tài khoản? <a href="/Auth/register" class="text-danger fw-semibold">Đăng ký ngay</a>
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
</script>

<?php include 'app/views/shares/footer.php'; ?>
