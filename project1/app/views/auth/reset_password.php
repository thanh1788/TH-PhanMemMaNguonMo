<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                <div class="card-header text-center py-4" style="background:#d70018;">
                    <h4 class="mb-0 text-white fw-bold"><i class="fas fa-lock me-2"></i>Đặt lại mật khẩu</h4>
                    <p class="text-white mb-0 mt-1" style="opacity:.85; font-size:13px;">Tạo mật khẩu mới cho tài khoản</p>
                </div>
                <div class="card-body p-4">

                    <form action="/Auth/doResetPassword" method="POST" novalidate>
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

                        <!-- Mật khẩu mới -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="newPwd"
                                       class="form-control border-start-0 border-end-0 <?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
                                       placeholder="Ít nhất 6 ký tự" required>
                                <button type="button" class="input-group-text bg-light" onclick="togglePwd('newPwd', this)">
                                    <i class="fas fa-eye text-muted"></i>
                                </button>
                                <?php if (!empty($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password_confirm" id="confirmPwd"
                                       class="form-control border-start-0 border-end-0 <?= !empty($errors['password_confirm']) ? 'is-invalid' : '' ?>"
                                       placeholder="Nhập lại mật khẩu" required>
                                <button type="button" class="input-group-text bg-light" onclick="togglePwd('confirmPwd', this)">
                                    <i class="fas fa-eye text-muted"></i>
                                </button>
                                <?php if (!empty($errors['password_confirm'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password_confirm']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold py-2" style="background:#d70018; color:#fff; border-radius:8px;">
                            <i class="fas fa-save me-2"></i>Lưu mật khẩu mới
                        </button>
                    </form>
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
