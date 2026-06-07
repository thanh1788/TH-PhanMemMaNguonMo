<?php include 'app/views/shares/header.php'; ?>
<?php
// AuthHelper đã được load trong header
$currentUser = AuthHelper::getUser();
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="/" class="text-danger">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/User/profile" class="text-danger">Hồ sơ</a></li>
                    <li class="breadcrumb-item active">Đổi mật khẩu</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                <div class="card-header py-3" style="background:#d70018;">
                    <h5 class="mb-0 text-white fw-bold"><i class="fas fa-key me-2"></i>Đổi mật khẩu</h5>
                </div>
                <div class="card-body p-4">

                    <form action="/Auth/doChangePassword" method="POST" novalidate>
                        <!-- Mật khẩu hiện tại -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="current_password" id="curPwd"
                                       class="form-control border-start-0 border-end-0 <?= !empty($errors['current_password']) ? 'is-invalid' : '' ?>"
                                       placeholder="Mật khẩu đang dùng" required>
                                <button type="button" class="input-group-text bg-light" onclick="togglePwd('curPwd', this)">
                                    <i class="fas fa-eye text-muted"></i>
                                </button>
                                <?php if (!empty($errors['current_password'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['current_password']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Mật khẩu mới -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="new_password" id="newPwd"
                                       class="form-control border-start-0 border-end-0 <?= !empty($errors['new_password']) ? 'is-invalid' : '' ?>"
                                       placeholder="Ít nhất 6 ký tự" required>
                                <button type="button" class="input-group-text bg-light" onclick="togglePwd('newPwd', this)">
                                    <i class="fas fa-eye text-muted"></i>
                                </button>
                                <?php if (!empty($errors['new_password'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['new_password']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="confirm_password" id="confirmPwd"
                                       class="form-control border-start-0 border-end-0 <?= !empty($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                       placeholder="Nhập lại mật khẩu mới" required>
                                <button type="button" class="input-group-text bg-light" onclick="togglePwd('confirmPwd', this)">
                                    <i class="fas fa-eye text-muted"></i>
                                </button>
                                <?php if (!empty($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn flex-fill fw-bold" style="background:#d70018; color:#fff; border-radius:8px;">
                                <i class="fas fa-save me-2"></i>Lưu mật khẩu mới
                            </button>
                            <a href="/User/profile" class="btn btn-light flex-fill fw-semibold" style="border-radius:8px;">
                                Hủy
                            </a>
                        </div>
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
