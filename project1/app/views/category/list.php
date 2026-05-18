<?php include 'app/views/shares/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container my-4">
    <!-- Thanh tiêu đề & Nút thêm mới tách biệt thanh lịch -->
    <div class="d-flex justify-content-between align-items-center flex-wrap bg-white p-3 mb-3 shadow-sm border" style="border-radius: 12px;">
        <h3 class="font-weight-bold text-dark m-0 style-title" style="font-size: 1.4rem;">
            <i class="fa-solid fa-layer-group text-danger mr-2"></i>Quản lý danh mục
        </h3>
        <a href="/Category/add" class="btn text-white font-weight-bold px-3 py-2" style="background-color: #d70018; border-radius: 8px; font-size: 0.9rem;">
            <i class="fa-solid fa-plus mr-1"></i> Thêm danh mục mới
        </a>
    </div>

    <!-- Bảng danh sách làm mờ viền, tăng padding cho giống danh sách cấu hình của CellphoneS -->
    <div class="table-responsive border bg-white shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <table class="table table-hover m-0">
            <thead class="thead-light">
                <tr class="text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <th class="border-0 text-center py-3" width="10%">ID</th>
                    <th class="border-0 py-3" width="30%">Tên danh mục</th>
                    <th class="border-0 py-3" width="40%">Mô tả</th>
                    <th class="border-0 text-center py-3" width="20%">Thao tác</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.95rem;">
                <?php if(!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <!-- Badge ID nhẹ nhàng -->
                            <td class="text-center align-middle py-3">
                                <span class="badge badge-light text-secondary px-2 py-1.5" style="border-radius: 4px; font-size: 0.85rem;">
                                    #<?= $category->id ?>
                                </span>
                            </td>
                            <!-- Tên đậm đà -->
                            <td class="align-middle py-3">
                                <span class="font-weight-bold text-dark"><?= htmlspecialchars($category->name) ?></span>
                            </td>
                            <!-- Mô tả chữ xám mờ -->
                            <td class="align-middle text-secondary py-3">
                                <?= htmlspecialchars($category->description ?: 'Chưa có mô tả cụ thể.') ?>
                            </td>
                            <!-- Nút Thao tác dạng nhẹ (Ghost/Soft Button) -->
                            <td class="text-center align-middle py-3">
                                <a href="/Category/edit/<?= $category->id ?>" class="btn btn-sm font-weight-bold mx-1" style="background-color: #e1f5fe; color: #0288d1; border-radius: 6px;">
                                    <i class="fa-solid fa-pen"></i> Sửa
                                </a>
                                <a href="/Category/delete/<?= $category->id ?>" class="btn btn-sm font-weight-bold mx-1" style="background-color: #ffebee; color: #c62828; border-radius: 6px;" 
                                   onclick="return confirm('Xóa danh mục này sẽ ảnh hưởng đến các sản phẩm thuộc danh mục. Bạn chắc chứ?')">
                                    <i class="fa-solid fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-box-open fa-2x mb-2 d-block text-light"></i>
                            Không có dữ liệu danh mục nào để hiển thị.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>