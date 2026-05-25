<?php
// Tính số lượng sản phẩm trong giỏ hàng
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MixiTech - Hệ thống bán lẻ công nghệ</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --red: #d70018;
            --red-dark: #b5001a;
            --red-light: #fff0f0;
            --dark: #1a1a1a;
            --gray: #6c757d;
            --bg: #f5f5f5;
            --white: #ffffff;
            --radius: 10px;
            --shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Be Vietnam Pro', 'Segoe UI', sans-serif;
            background-color: var(--bg);
            color: var(--dark);
            margin: 0;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: #1a1a1a;
            color: #ccc;
            font-size: 12px;
            padding: 5px 0;
        }
        .topbar a { color: #ccc; text-decoration: none; }
        .topbar a:hover { color: #fff; }

        /* ===== NAVBAR ===== */
        .main-navbar {
            background: var(--red);
            box-shadow: 0 3px 12px rgba(215,0,24,0.3);
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .navbar-brand {
            font-size: 22px;
            font-weight: 800;
            color: #fff !important;
            letter-spacing: -0.5px;
            text-decoration: none;
        }
        .navbar-brand span { color: #ffd700; }

        /* Search bar */
        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 480px;
            margin: 0 20px;
        }
        .search-wrapper input {
            border: none;
            border-radius: 8px;
            padding: 9px 44px 9px 16px;
            font-size: 14px;
            width: 100%;
            outline: none;
            font-family: inherit;
        }
        .search-wrapper button {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            background: #b5001a;
            border: none;
            border-radius: 0 8px 8px 0;
            padding: 0 14px;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-wrapper button:hover { background: #8a0012; }

        /* Nav icons */
        .nav-icon-btn {
            color: #fff;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 11px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 8px;
            transition: background 0.2s;
            position: relative;
            white-space: nowrap;
        }
        .nav-icon-btn:hover { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-icon-btn i { font-size: 18px; margin-bottom: 2px; }
        .cart-badge {
            position: absolute;
            top: 0;
            right: 4px;
            background: #ffd700;
            color: #1a1a1a;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        /* ===== CATEGORY NAV BAR ===== */
        .cat-navbar {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 0;
        }
        .cat-navbar .container {
            display: flex;
            align-items: center;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .cat-navbar .container::-webkit-scrollbar { display: none; }
        .cat-nav-link {
            color: #333;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 14px;
            white-space: nowrap;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .cat-nav-link:hover, .cat-nav-link.active {
            color: var(--red);
            border-bottom-color: var(--red);
        }
        .cat-nav-link i { font-size: 14px; }

        /* ===== MOBILE MENU ===== */
        .mobile-menu-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
            padding: 4px 8px;
        }
        .mobile-search {
            display: none;
            padding: 10px 15px;
            background: #b5001a;
        }
        .mobile-search.show { display: block; }
        .mobile-search input {
            border: none;
            border-radius: 8px 0 0 8px;
            padding: 9px 14px;
            font-size: 14px;
            width: calc(100% - 44px);
            outline: none;
        }
        .mobile-search button {
            background: #8a0012;
            border: none;
            border-radius: 0 8px 8px 0;
            padding: 9px 14px;
            color: #fff;
            cursor: pointer;
        }

        /* ===== FLASH MESSAGE ===== */
        .flash-message {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 280px;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-phone-alt me-1"></i> Hotline: <strong>1800 2097</strong>
            &nbsp;|&nbsp;
            <i class="fas fa-map-marker-alt me-1"></i> Hệ thống cửa hàng toàn quốc
        </div>
        <div>
            <a href="/Order/lookup"><i class="fas fa-truck me-1"></i> Tra cứu đơn hàng</a>
            &nbsp;|&nbsp;
            <a href="#"><i class="fas fa-headset me-1"></i> Hỗ trợ</a>
        </div>
    </div>
</div>

<!-- MAIN NAVBAR -->
<nav class="main-navbar">
    <div class="container d-flex align-items-center">
        <!-- Logo -->
        <a href="/" class="navbar-brand me-3">
            <i class="fas fa-mobile-alt me-1"></i>Mixi<span>Tech</span>
        </a>

        <!-- Search (desktop) -->
        <form class="search-wrapper d-none d-md-block" action="/Product/search" method="GET">
            <input type="text" name="q" placeholder="Tìm kiếm điện thoại, laptop, phụ kiện..." 
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>

        <!-- Right icons -->
        <div class="d-flex align-items-center ms-auto gap-1">
            <!-- Mobile search toggle -->
            <button class="mobile-menu-btn d-md-none" onclick="toggleMobileSearch()">
                <i class="fas fa-search"></i>
            </button>

            <!-- Admin dropdown -->
            <div class="dropdown d-none d-md-block">
                <a href="#" class="nav-icon-btn dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-cog"></i>
                    <span>Quản trị</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px; min-width: 200px;">
                    <li><h6 class="dropdown-header text-danger fw-bold">Quản lý hệ thống</h6></li>
                    <li><a class="dropdown-item" href="/Product/add"><i class="fas fa-plus-circle me-2 text-success"></i>Thêm sản phẩm</a></li>
                    <li><a class="dropdown-item" href="/Category"><i class="fas fa-layer-group me-2 text-primary"></i>Quản lý danh mục</a></li>
                    <li><a class="dropdown-item" href="/Category/add"><i class="fas fa-folder-plus me-2 text-warning"></i>Thêm danh mục</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/Order/admin"><i class="fas fa-list-alt me-2 text-danger"></i>Quản lý đơn hàng</a></li>
                </ul>
            </div>

            <!-- Order lookup -->
            <a href="/Order/lookup" class="nav-icon-btn d-none d-md-flex">
                <i class="fas fa-truck"></i>
                <span>Đơn hàng</span>
            </a>

            <!-- Cart -->
            <a href="/Cart" class="nav-icon-btn">
                <i class="fas fa-shopping-cart"></i>
                <span>Giỏ hàng</span>
                <?php if ($cartCount > 0): ?>
                    <span class="cart-badge"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<!-- MOBILE SEARCH -->
<div class="mobile-search d-md-none" id="mobileSearch">
    <form action="/Product/search" method="GET" class="d-flex">
        <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>

<!-- FLASH MESSAGE -->
<?php if (isset($_SESSION['flash'])): ?>
    <div class="flash-message alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible shadow" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['flash']['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<script>
function toggleMobileSearch() {
    const el = document.getElementById('mobileSearch');
    el.classList.toggle('show');
}
// Auto-hide flash after 3s
setTimeout(() => {
    const flash = document.querySelector('.flash-message');
    if (flash) flash.style.display = 'none';
}, 3000);
</script>
