<?php include 'app/views/shares/header.php'; ?>
<!-- FontAwesome phục vụ icon cho giống website công nghệ -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container my-5">
    <!-- Card bo góc lớn (rounded-lg / rounded-3) và đổ bóng nhẹ (shadow-sm) -->
    <div class="card border-light shadow-sm mx-auto p-4" style="max-width: 500px; border-radius: 12px;">
        
        <!-- Tiêu đề có độ đậm (font-weight-bold / fw-bold) và vệt đỏ CellphoneS bên trái -->
        <h4 class="font-weight-bold mb-4 px-2 text-dark" style="border-left: 4px solid #d70018; font-size: 1.25rem;">
            THÊM DANH MỤC MỚI
        </h4>
        
        <form action="/Category/save" method="POST">
            <div class="form-group mb-3">
                <label class="text-secondary font-weight-bold small"><i class="fa-solid fa-tags mr-1"></i> Tên danh mục</label>
                <input type="text" name="name" class="form-control form-control-lg text-sm" style="border-radius: 8px; font-size: 0.95rem;" placeholder="Ví dụ: Điện thoại, Laptop..." required>
            </div>
            
            <div class="form-group mb-4">
                <label class="text-secondary font-weight-bold small"><i class="fa-solid fa-pen-nib mr-1"></i> Mô tả</label>
                <textarea name="description" class="form-control" rows="4" style="border-radius: 8px; font-size: 0.95rem;" placeholder="Nhập mô tả ngắn về danh mục này..."></textarea>
            </div>
            
            <!-- Nút bấm phẳng bo góc, chia 2 cột bằng Grid Bootstrap -->
            <div class="row no-gutters mx-n1">
                <div class="col px-1">
                    <a href="/Category" class="btn btn-light btn-block font-weight-bold text-secondary py-2" style="border-radius: 8px; background-color: #f3f4f6;">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Hủy
                    </a>
                </div>
                <div class="col px-1">
                    <button type="submit" class="btn btn-block text-white font-weight-bold py-2" style="background-color: #d70018; border-radius: 8px;">
                        <i class="fa-solid fa-floppy-disk mr-1"></i> Lưu lại
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>