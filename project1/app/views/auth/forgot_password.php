<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                <div class="card-header text-center py-4" style="background:#d70018;">
                    <h4 class="mb-0 text-white fw-bold"><i class="fas fa-key me-2"></i>Quên mật khẩu</h4>
                    <p class="text-white mb-0 mt-1" style="opacity:.85; font-size:13px;">Nhập email để nhận link đặt lại mật khẩu</p>
                </div>
                <div class="card-body p-4">

                    <div class="alert alert-info py-2 mb-4" style="font-size:13px; border-radius:8px;">
                        <i class="fas fa-info-circle me-2"></i>
                        Chúng tôi sẽ gửi link đặt lại mật khẩu đến email của bạn. Link có hiệu lực trong <strong>1 giờ</strong>.
                    </div>

                    <form action="/Auth/sendResetLink" method="POST" novalidate>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email đăng ký</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="email"
                                       class="form-control border-start-0 <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                       placeholder="example@email.com" required autofocus>
                                <?php if (!empty($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-bold py-2" style="background:#d70018; color:#fff; border-radius:8px;">
                            <i class="fas fa-paper-plane me-2"></i>Gửi link đặt lại mật khẩu
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center mb-0 small">
                        <a href="/Auth/login" class="text-danger fw-semibold"><i class="fas fa-arrow-left me-1"></i>Quay lại đăng nhập</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
