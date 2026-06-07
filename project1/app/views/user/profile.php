<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/" class="text-danger">Trang chủ</a></li>
            <li class="breadcrumb-item active">Hồ sơ cá nhân</li>
        </ol>
    </nav>

    <div class="row g-4">

        <!-- Sidebar profile -->
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm text-center p-4" style="border-radius:16px;">

                <!-- Avatar -->
                <?php
                $avatarSrc = !empty($user->avatar)
                    ? '/public/uploads/avatars/' . htmlspecialchars($user->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&size=128&background=d70018&color=fff&bold=true';
                ?>
                <div class="position-relative d-inline-block mb-3">
                    <img src="<?= $avatarSrc ?>" alt="Avatar"
                         id="avatarPreview"
                         style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid #d70018;">
                    <label for="avatarInput" title="Đổi ảnh đại diện"
                           style="position:absolute; bottom:4px; right:4px; background:#d70018; color:#fff; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,.2);">
                        <i class="fas fa-camera" style="font-size:13px;"></i>
                    </label>
                </div>

                <h6 class="fw-bold mb-1"><?= htmlspecialchars($user->full_name) ?></h6>
                <span class="badge mb-2 <?= $user->role === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                    <?= $user->role === 'admin' ? 'Admin' : 'Người dùng' ?>
                </span>
                <?php if (!$user->is_verified): ?>
                    <div class="alert alert-warning py-2 mt-2" style="font-size:12px; border-radius:8px;">
                        <i class="fas fa-exclamation-triangle me-1"></i>Email chưa xác thực
                    </div>
                <?php else: ?>
                    <div class="text-success small mt-1">
                        <i class="fas fa-check-circle me-1"></i>Email đã xác thực
                    </div>
                <?php endif; ?>

                <hr>
                <div class="d-grid gap-2">
                    <a href="/Auth/changePassword" class="btn btn-outline-danger btn-sm" style="border-radius:8px;">
                        <i class="fas fa-key me-2"></i>Đổi mật khẩu
                    </a>
                    <?php if (AuthHelper::isAdmin()): ?>
                        <a href="/User/adminList" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                            <i class="fas fa-users-cog me-2"></i>Quản lý Users
                        </a>
                    <?php endif; ?>
                    <a href="/Auth/logout" class="btn btn-light btn-sm" style="border-radius:8px;"
                       onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                        <i class="fas fa-sign-out-alt me-2 text-danger"></i>Đăng xuất
                    </a>
                </div>
            </div>

            <!-- Upload avatar form ẩn -->
            <form id="avatarForm" action="/User/uploadAvatar" method="POST" enctype="multipart/form-data">
                <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;">
            </form>
        </div>

        <!-- Main content -->
        <div class="col-lg-9 col-md-8">

            <div class="card border-0 shadow-sm" style="border-radius:16px;">
                <div class="card-header py-3 border-0" style="background:#fff; border-bottom:2px solid #f5f5f5;">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2 text-danger"></i>Thông tin cá nhân</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/User/updateProfile" method="POST" novalidate>

                        <div class="row g-3">
                            <!-- Họ tên -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="full_name"
                                       class="form-control <?= !empty($errors['full_name']) ? 'is-invalid' : '' ?>"
                                       value="<?= htmlspecialchars($_POST['full_name'] ?? $user->full_name) ?>"
                                       style="border-radius:8px;" required>
                                <?php if (!empty($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['full_name']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email (chỉ đọc) -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <input type="email" class="form-control bg-light"
                                           value="<?= htmlspecialchars($user->email) ?>"
                                           style="border-radius:8px 0 0 8px;" readonly>
                                    <span class="input-group-text bg-light" title="Email không thể thay đổi">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Số điện thoại -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại</label>
                                <input type="tel" name="phone"
                                       class="form-control <?= !empty($errors['phone']) ? 'is-invalid' : '' ?>"
                                       value="<?= htmlspecialchars($_POST['phone'] ?? $user->phone ?? '') ?>"
                                       placeholder="0xxxxxxxxx" style="border-radius:8px;">
                                <?php if (!empty($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['phone']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Vai trò -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Vai trò</label>
                                <input type="text" class="form-control bg-light"
                                       value="<?= $user->role === 'admin' ? 'Quản trị viên' : 'Người dùng' ?>"
                                       style="border-radius:8px;" readonly>
                            </div>

                            <!-- Địa chỉ -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Địa chỉ</label>
                                <textarea name="address" rows="2" class="form-control"
                                          placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành..."
                                          style="border-radius:8px;"><?= htmlspecialchars($_POST['address'] ?? $user->address ?? '') ?></textarea>
                            </div>

                            <!-- Ngày tham gia -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Ngày tham gia</label>
                                <input type="text" class="form-control bg-light"
                                       value="<?= date('d/m/Y H:i', strtotime($user->created_at)) ?>"
                                       style="border-radius:8px;" readonly>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn fw-bold px-4 py-2" style="background:#d70018; color:#fff; border-radius:8px;">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                            <a href="/" class="btn btn-light fw-semibold px-4 py-2" style="border-radius:8px;">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tự submit form khi chọn ảnh
document.getElementById('avatarInput').addEventListener('change', function () {
    if (this.files && this.files[0]) {
        // Preview ảnh trước khi upload
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(this.files[0]);
        // Submit form
        document.getElementById('avatarForm').submit();
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
