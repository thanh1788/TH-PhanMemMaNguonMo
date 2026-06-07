<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/" class="text-danger">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/User/adminList" class="text-danger">Quản lý Users</a></li>
            <li class="breadcrumb-item active">Chi tiết #<?= $user->id ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Profile card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4" style="border-radius:16px;">
                <?php
                $ava = !empty($user->avatar)
                    ? '/public/uploads/avatars/' . htmlspecialchars($user->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&size=120&background=d70018&color=fff&bold=true';
                ?>
                <img src="<?= $ava ?>" alt="Avatar"
                     style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid #d70018;margin-bottom:12px;">
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($user->full_name) ?></h5>
                <p class="text-muted small mb-2"><?= htmlspecialchars($user->email) ?></p>

                <!-- Status badge -->
                <?php if ($user->status === 'active'): ?>
                    <span class="badge bg-success px-3 py-2 mb-2">
                        <i class="fas fa-check me-1"></i>Đang hoạt động
                    </span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark px-3 py-2 mb-2">
                        <i class="fas fa-lock me-1"></i>Bị khóa
                    </span>
                <?php endif; ?>

                <br>
                <!-- Email verified -->
                <?php if ($user->is_verified): ?>
                    <small class="text-success"><i class="fas fa-check-circle me-1"></i>Email đã xác thực</small>
                <?php else: ?>
                    <small class="text-danger"><i class="fas fa-times-circle me-1"></i>Email chưa xác thực</small>
                <?php endif; ?>

                <hr>
                <!-- Actions -->
                <?php if ($user->id !== AuthHelper::getUserId()): ?>
                    <a href="/User/toggleStatus/<?= $user->id ?>"
                       class="btn w-100 mb-2 <?= $user->status === 'active' ? 'btn-warning' : 'btn-success' ?>"
                       style="border-radius:8px;"
                       onclick="return confirm('<?= $user->status === 'active' ? 'Khóa tài khoản này?' : 'Mở khóa tài khoản này?' ?>')">
                        <i class="fas <?= $user->status === 'active' ? 'fa-lock' : 'fa-lock-open' ?> me-2"></i>
                        <?= $user->status === 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' ?>
                    </a>
                <?php else: ?>
                    <p class="text-muted small">(Không thể thao tác trên tài khoản chính mình)</p>
                <?php endif; ?>

                <a href="/User/adminList" class="btn btn-light w-100" style="border-radius:8px;">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Detail & Role management -->
        <div class="col-md-8">
            <!-- Info card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
                <div class="card-header py-3 border-0" style="border-bottom:2px solid #f5f5f5;">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-danger"></i>Thông tin chi tiết</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label text-muted small">Họ và tên</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($user->full_name) ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small">Email</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($user->email) ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small">Điện thoại</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($user->phone ?? '—') ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small">Vai trò hiện tại</label>
                            <p class="mb-0">
                                <?php if ($user->role === 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">User</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">Địa chỉ</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($user->address ?? '—') ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small">Ngày đăng ký</label>
                            <p class="fw-semibold mb-0"><?= date('d/m/Y H:i', strtotime($user->created_at)) ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small">Cập nhật lần cuối</label>
                            <p class="fw-semibold mb-0"><?= $user->updated_at ? date('d/m/Y H:i', strtotime($user->updated_at)) : '—' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role management -->
            <?php if ($user->id !== AuthHelper::getUserId()): ?>
            <div class="card border-0 shadow-sm" style="border-radius:16px;">
                <div class="card-header py-3 border-0" style="border-bottom:2px solid #f5f5f5;">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-user-shield me-2 text-danger"></i>Phân quyền vai trò</h6>
                </div>
                <div class="card-body p-4">
                    <form action="/User/updateRole" method="POST" class="d-flex align-items-center gap-3 flex-wrap">
                        <input type="hidden" name="id" value="<?= $user->id ?>">
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input type="radio" name="role" id="roleUser" value="user"
                                       class="form-check-input" <?= $user->role === 'user' ? 'checked' : '' ?>>
                                <label for="roleUser" class="form-check-label">
                                    <span class="badge bg-secondary px-3 py-2">User</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="role" id="roleAdmin" value="admin"
                                       class="form-check-input" <?= $user->role === 'admin' ? 'checked' : '' ?>>
                                <label for="roleAdmin" class="form-check-label">
                                    <span class="badge bg-danger px-3 py-2">Admin</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger px-4" style="border-radius:8px;"
                                onclick="return confirm('Xác nhận thay đổi vai trò?')">
                            <i class="fas fa-save me-2"></i>Cập nhật
                        </button>
                    </form>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Admin có quyền truy cập tất cả chức năng quản trị. Hãy cẩn thận khi cấp quyền này.
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
