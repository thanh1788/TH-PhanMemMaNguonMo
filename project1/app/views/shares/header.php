<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MixiTech - Hệ thống bán lẻ công nghệ</title>
    <!-- Bootstrap 4.5.2 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .item-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15) !important;
        }
    </style>
</head>
<body style="background-color: #f4f4f4; min-height: 100vh;">

    <!-- THANH ĐIỀU HƯỚNG (NAVBAR) PHONG CÁCH CELLPHONES -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #d70018; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand font-weight-bold d-flex align-items-center" href="/">
                <i class="fas fa-tablet-alt mr-2"></i> MixiTech
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#cellphonesNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="cellphonesNav">
                <!-- Thanh tìm kiếm ở giữa -->
                <form class="form-inline my-2 my-lg-0 mx-auto w-50 d-none d-md-flex">
                    <div class="input-group w-100">
                        <input class="form-control border-0" type="search" placeholder="Bạn cần tìm gì hôm nay?..." aria-label="Search" style="border-radius: 8px 0 0 8px;">
                        <div class="input-group-append">
                            <button class="btn btn-light border-0" type="submit" style="border-radius: 0 8px 8px 0; background-color: #fff; color: #d70018;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Các nút chức năng bên phải -->
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item active mx-2">
                        <a class="nav-link text-white d-flex flex-column align-items-center py-1" href="/Product/add" style="background: rgba(255,255,255,0.2); border-radius: 8px; padding: 5px 15px !important;">
                            <span class="small"><i class="fas fa-plus-circle mr-1"></i> Quản trị viên</span>
                            <strong class="small font-weight-bold">Thêm sản phẩm</strong>
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white position-relative" href="#">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <span class="badge badge-warning position-absolute" style="top: 0; right: -5px; border-radius: 50%;">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>