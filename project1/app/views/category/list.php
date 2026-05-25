<?php include 'app/views/shares/header.php'; ?>

<style>
    .page-header { background: #fff; border-radius: 12px; padding: 20px 24px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .page-header h3 { margin: 0; font-size: 20px; font-weight: 800; color: #1a1a1a; display: flex; align-items: center; gap: 10px; }
    .page-header h3 i { color: #d70018; }
    .btn-add { background: #d70018; color: #fff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 14px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-add:hover { background: #b5001a; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(215,0,24,0.3); }

    .table-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee; overflow: hidden; }
    .table { margin: 0; }
    .table thead th { background: #f8f9fa; color: #555; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 14px 16px; border-bottom: 2px solid #eee; border-top: none; }
    .table tbody td { padding: 14px 16px; vertical-align: middle; border-bottom: 1px solid #f5f5f5; font-size: 14px; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background: #fafafa; }

    .id-badge { background: #f0f0f0; color: #666; font-size: 12px; font-weight: 600; padding: 3px 8px; border-radius: 5px; }
    .cat-name { font-weight: 700; color: #1a1a1a; }
    .cat-desc { color: #777; font-size: 13px; }

    .btn-edit { background: #e8f4fd; color: #0d6efd; border: none; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; }
    .btn-edit:hover { background: #0d6efd; color: #fff; }
    .btn-del { background: #fde8e8; color: #d70018; border: none; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; }
    .btn-del:hover { background: #d70018; color: #fff; }

    .empty-state { text-align: center; padding: 60px 20px; color: #999; }
    .empty-state i { font-size: 48px; margin-bottom: 16px; opacity: 0.3; }
</style>

<div class="container mt-4 mb-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background: none; padding: 0; font-size: 13px;">
            <li class="breadcrumb-item"><a href="/" style="color: #d70018; text-decoration: none;">Trang chủ</a></li>
            <li class="breadcrumb-item active text-muted">Quản lý danh mục</li>
        </ol>
    </nav>

    <!-- Page header -->
    <div class="page-header">
        <h3>
            <i class="fas fa-layer-group"></i>
            Quản lý danh mục
            <span style="background: #d70018; color: #fff; font-size: 12px; padding: 2px 8px; border-radius: 20px; font-weight: 600;">
                <?= count($categories) ?>
            </span>
        </h3>
        <a href="/Category/add" class="btn-add">
            <i class="fas fa-plus"></i> Thêm danh mục mới
        </a>
    </div>

    <!-- Table -->
    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 30%;">Tên danh mục</th>
                    <th>Mô tả</th>
                    <th style="width: 160px; text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><span class="id-badge">#<?= $category->id ?></span></td>
                            <td>
                                <span class="cat-name"><?= htmlspecialchars($category->name) ?></span>
                            </td>
                            <td>
                                <span class="cat-desc"><?= htmlspecialchars($category->description ?: 'Chưa có mô tả.') ?></span>
                            </td>
                            <td style="text-align: center;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="/Category/edit/<?= $category->id ?>" class="btn-edit">
                                        <i class="fas fa-pen"></i> Sửa
                                    </a>
                                    <a href="/Category/delete/<?= $category->id ?>" class="btn-del"
                                       onclick="return confirm('Xóa danh mục này sẽ ảnh hưởng đến các sản phẩm thuộc danh mục. Bạn chắc chứ?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fas fa-box-open d-block"></i>
                                <p class="mb-2">Chưa có danh mục nào.</p>
                                <a href="/Category/add" class="btn btn-danger btn-sm">
                                    <i class="fas fa-plus me-1"></i>Thêm danh mục đầu tiên
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
