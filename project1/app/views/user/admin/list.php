<?php include 'app/views/shares/header.php'; ?>

<div class="container-fluid mt-4 mb-5 px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-users-cog me-2 text-danger"></i>Quản lý người dùng</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="/" class="text-danger">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý người dùng</li>
                </ol>
            </nav>
        </div>
        <span class="badge bg-danger fs-6 py-2 px-3">
            <i class="fas fa-users me-1"></i><?= $total ?> tài khoản
        </span>
    </div>

    <!-- Search + Filter -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-body py-3">
            <form method="GET" action="/User/adminList" class="d-flex gap-2 align-items-center flex-wrap">
                <div class="input-group" style="max-width:340px;">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Tìm theo tên hoặc email..."
                           value="<?= htmlspecialchars($search) ?>" style="border-radius:0 8px 8px 0;">
                </div>
                <select name="role" class="form-select" style="max-width:150px; border-radius:8px;">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin" <?= ($roleFilter ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user"  <?= ($roleFilter ?? '') === 'user'  ? 'selected' : '' ?>>User</option>
                </select>
                <select name="status" class="form-select" style="max-width:160px; border-radius:8px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="locked" <?= ($statusFilter ?? '') === 'locked' ? 'selected' : '' ?>>Đã khóa</option>
                </select>
                <button class="btn btn-danger px-4" style="border-radius:8px;">
                    <i class="fas fa-search me-1"></i>Lọc
                </button>
                <?php if (!empty($search) || !empty($roleFilter) || !empty($statusFilter)): ?>
                    <a href="/User/adminList" class="btn btn-light" style="border-radius:8px;">
                        <i class="fas fa-times me-1"></i>Xóa bộ lọc
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Xác thực</th>
                        <th>Ngày tạo</th>
                        <th class="text-center pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-users fa-2x mb-2 d-block opacity-25"></i>
                                Không có người dùng nào phù hợp.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $u->id ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php
                                        $ava = !empty($u->avatar)
                                            ? '/public/uploads/avatars/' . htmlspecialchars($u->avatar)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($u->full_name) . '&size=40&background=d70018&color=fff&bold=true';
                                        ?>
                                        <img src="<?= $ava ?>" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                                        <div>
                                            <div class="fw-semibold" style="font-size:14px;"><?= htmlspecialchars($u->full_name) ?></div>
                                            <?php if ($u->id == AuthHelper::getUserId()): ?>
                                                <small class="text-danger">(Tài khoản của bạn)</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="small text-muted"><?= htmlspecialchars($u->email) ?></td>
                                <td class="small"><?= htmlspecialchars($u->phone ?? '—') ?></td>
                                <td>
                                    <?php if ($u->role === 'admin'): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">User</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u->status === 'active'): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-lock me-1"></i>Đã khóa</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($u->is_verified): ?>
                                        <i class="fas fa-check-circle text-success" title="Đã xác thực"></i>
                                    <?php else: ?>
                                        <i class="fas fa-times-circle text-danger" title="Chưa xác thực"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted"><?= date('d/m/Y', strtotime($u->created_at)) ?></td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="/User/adminDetail/<?= $u->id ?>"
                                           class="btn btn-sm btn-outline-primary px-2 py-1" title="Chi tiết"
                                           style="border-radius:6px; font-size:12px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($u->id != AuthHelper::getUserId()): ?>
                                        <a href="/User/toggleStatus/<?= $u->id ?>"
                                           class="btn btn-sm px-2 py-1 <?= $u->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                           title="<?= $u->status === 'active' ? 'Khóa tài khoản' : 'Mở khóa' ?>"
                                           style="border-radius:6px; font-size:12px;"
                                           onclick="return confirm('<?= $u->status === 'active' ? 'Khóa tài khoản này?' : 'Mở khóa tài khoản này?' ?>')">
                                            <i class="fas <?= $u->status === 'active' ? 'fa-lock' : 'fa-lock-open' ?>"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center py-3 px-4">
                <small class="text-muted">
                    Trang <?= $page ?> / <?= $totalPages ?> &nbsp;·&nbsp; Tổng <?= $total ?> người dùng
                </small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="/User/adminList?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter ?? '') ?>&status=<?= urlencode($statusFilter ?? '') ?>">«</a>
                            </li>
                        <?php endif; ?>
                        <?php
                        $start = max(1, $page - 2);
                        $end   = min($totalPages, $page + 2);
                        for ($i = $start; $i <= $end; $i++):
                        ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="/User/adminList?page=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter ?? '') ?>&status=<?= urlencode($statusFilter ?? '') ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="/User/adminList?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter ?? '') ?>&status=<?= urlencode($statusFilter ?? '') ?>">»</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
