<?php include 'app/views/shares/header.php'; ?>
<style>
.admin-wrap { background:#fff; border-radius:14px; border:1px solid #eee; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; }
.admin-toolbar { padding:16px 20px; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.stat-card { background:#fff; border-radius:12px; border:1px solid #eee; padding:16px 20px; display:flex; align-items:center; gap:14px; }
.stat-icon { width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
.stat-val { font-size:22px; font-weight:800; color:#1a1a1a; line-height:1; }
.stat-lbl { font-size:12px; color:#999; margin-top:3px; }
.filter-tabs { display:flex; gap:0; flex-wrap:wrap; border-bottom:2px solid #f0f0f0; padding:0 20px; }
.filter-tab { padding:10px 16px; font-size:13px; font-weight:600; color:#777; text-decoration:none; border-bottom:3px solid transparent; margin-bottom:-2px; white-space:nowrap; transition:all .2s; }
.filter-tab:hover { color:#d70018; }
.filter-tab.active { color:#d70018; border-bottom-color:#d70018; }
.filter-tab .badge { background:#eee; color:#555; font-size:10px; padding:2px 6px; border-radius:10px; margin-left:4px; }
.filter-tab.active .badge { background:#d70018; color:#fff; }
.orders-table { width:100%; border-collapse:collapse; }
.orders-table thead th { background:#f8f9fa; color:#555; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; padding:12px 16px; border-bottom:2px solid #eee; white-space:nowrap; }
.orders-table tbody td { padding:13px 16px; vertical-align:middle; border-bottom:1px solid #f5f5f5; font-size:13.5px; }
.orders-table tbody tr:hover { background:#fafafa; }
.orders-table tbody tr:last-child td { border-bottom:none; }
.order-id-link { font-weight:800; color:#1a1a1a; text-decoration:none; }
.order-id-link:hover { color:#d70018; }
.status-badge { display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:700; padding:4px 10px; border-radius:20px; white-space:nowrap; }
.status-badge.pending   { background:#fff3cd; color:#856404; }
.status-badge.confirmed { background:#cff4fc; color:#055160; }
.status-badge.shipping  { background:#cfe2ff; color:#084298; }
.status-badge.delivered { background:#d1e7dd; color:#0a3622; }
.status-badge.cancelled { background:#f8d7da; color:#842029; }
.quick-status { border:1.5px solid #e0e0e0; border-radius:7px; padding:5px 8px; font-size:12px; font-weight:600; cursor:pointer; background:#fff; transition:border-color .2s; }
.quick-status:focus { border-color:#d70018; outline:none; }
.btn-detail { background:#f0f4ff; color:#0d6efd; border:none; border-radius:6px; padding:5px 10px; font-size:12px; font-weight:600; text-decoration:none; transition:all .2s; }
.btn-detail:hover { background:#0d6efd; color:#fff; }
.btn-del { background:#fde8e8; color:#d70018; border:none; border-radius:6px; padding:5px 10px; font-size:12px; font-weight:600; text-decoration:none; transition:all .2s; cursor:pointer; }
.btn-del:hover { background:#d70018; color:#fff; }
.search-box { display:flex; gap:0; }
.search-box input { border:1.5px solid #e0e0e0; border-radius:8px 0 0 8px; padding:8px 14px; font-size:13px; outline:none; width:220px; }
.search-box input:focus { border-color:#d70018; }
.search-box button { background:#d70018; color:#fff; border:none; border-radius:0 8px 8px 0; padding:8px 14px; cursor:pointer; }
.pagination-wrap { padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; border-top:1px solid #f0f0f0; }
.page-btn { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:7px; font-size:13px; font-weight:600; text-decoration:none; color:#555; border:1.5px solid #eee; transition:all .2s; }
.page-btn:hover, .page-btn.active { background:#d70018; color:#fff; border-color:#d70018; }
</style>

<div class="container-fluid mt-4 mb-5" style="max-width:1300px;">

  <!-- Breadcrumb -->
  <nav class="mb-3"><ol class="breadcrumb" style="background:none;padding:0;font-size:13px;">
    <li class="breadcrumb-item"><a href="/" style="color:#d70018;text-decoration:none;">Trang chủ</a></li>
    <li class="breadcrumb-item active text-muted">Quản lý đơn hàng</li>
  </ol></nav>

  <!-- STAT CARDS -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3 col-xl">
      <div class="stat-card">
        <div class="stat-icon" style="background:#fff3cd;color:#856404;"><i class="fas fa-clock"></i></div>
        <div><div class="stat-val"><?= $statusCount['pending'] ?></div><div class="stat-lbl">Chờ xác nhận</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="stat-card">
        <div class="stat-icon" style="background:#cff4fc;color:#055160;"><i class="fas fa-check-circle"></i></div>
        <div><div class="stat-val"><?= $statusCount['confirmed'] ?></div><div class="stat-lbl">Đã xác nhận</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="stat-card">
        <div class="stat-icon" style="background:#cfe2ff;color:#084298;"><i class="fas fa-truck"></i></div>
        <div><div class="stat-val"><?= $statusCount['shipping'] ?></div><div class="stat-lbl">Đang giao</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="stat-card">
        <div class="stat-icon" style="background:#d1e7dd;color:#0a3622;"><i class="fas fa-box-open"></i></div>
        <div><div class="stat-val"><?= $statusCount['delivered'] ?></div><div class="stat-lbl">Đã giao</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="stat-card">
        <div class="stat-icon" style="background:#f8d7da;color:#842029;"><i class="fas fa-times-circle"></i></div>
        <div><div class="stat-val"><?= $statusCount['cancelled'] ?></div><div class="stat-lbl">Đã huỷ</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-xl">
      <div class="stat-card">
        <div class="stat-icon" style="background:#fff0f0;color:#d70018;"><i class="fas fa-coins"></i></div>
        <div>
          <div class="stat-val" style="font-size:16px;"><?= number_format($totalRev, 0, ',', '.') ?>đ</div>
          <div class="stat-lbl">Tổng doanh thu</div>
        </div>
      </div>
    </div>
  </div>

  <!-- TABLE CARD -->
  <div class="admin-wrap">
    <!-- Toolbar -->
    <div class="admin-toolbar">
      <h5 class="fw-bold mb-0" style="font-size:16px;">
        <i class="fas fa-list-alt me-2 text-danger"></i>Danh sách đơn hàng
        <span style="font-size:13px;color:#999;font-weight:500;">(<?= $total ?> đơn)</span>
      </h5>
      <form method="GET" action="/Order/admin" class="search-box">
        <?php if ($status): ?><input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>"><?php endif; ?>
        <input type="text" name="search" placeholder="Tìm tên, SĐT, mã đơn..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit"><i class="fas fa-search"></i></button>
      </form>
    </div>

    <!-- Filter tabs -->
    <div class="filter-tabs">
      <?php
      $tabs = [''=>'Tất cả'] + array_map(fn($s)=>$s['label'], $statuses);
      foreach ($tabs as $key => $label):
        $cnt = $key === '' ? $statusCount['all'] : ($statusCount[$key] ?? 0);
        $qs  = http_build_query(['status'=>$key,'search'=>$search,'page'=>1]);
        $active = ($status === $key) ? 'active' : '';
      ?>
        <a href="/Order/admin?<?= $qs ?>" class="filter-tab <?= $active ?>">
          <?= $label ?> <span class="badge"><?= $cnt ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="orders-table">
        <thead>
          <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Địa chỉ</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th style="text-align:center;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
            <tr><td colspan="7" class="text-center py-5 text-muted">
              <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:.3;"></i>
              Không có đơn hàng nào.
            </td></tr>
          <?php else: ?>
            <?php foreach ($orders as $o): ?>
              <tr>
                <td>
                  <a href="/Order/adminDetail/<?= $o->id ?>" class="order-id-link">
                    #<?= str_pad($o->id, 6, '0', STR_PAD_LEFT) ?>
                  </a>
                  <?php if (!empty($o->item_count)): ?>
                    <div style="font-size:11px;color:#999;"><?= $o->item_count ?> sản phẩm</div>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="fw-bold" style="font-size:13.5px;"><?= htmlspecialchars($o->customer_name) ?></div>
                  <div style="font-size:12px;color:#777;"><?= htmlspecialchars($o->phone) ?></div>
                </td>
                <td style="max-width:200px;">
                  <span style="font-size:12.5px;color:#555;">
                    <?= htmlspecialchars(mb_strimwidth($o->address, 0, 50, '...')) ?>
                  </span>
                </td>
                <td>
                  <span style="font-weight:700;color:#d70018;">
                    <?= number_format($o->total_price, 0, ',', '.') ?>đ
                  </span>
                </td>
                <td>
                  <!-- Quick-change status inline -->
                  <form method="POST" action="/Order/updateStatus" class="d-inline">
                    <input type="hidden" name="id"  value="<?= $o->id ?>">
                    <input type="hidden" name="ref" value="/Order/admin?<?= htmlspecialchars(http_build_query(['status'=>$status,'search'=>$search,'page'=>$page])) ?>">
                    <select name="status" class="quick-status" onchange="this.form.submit()" title="Đổi trạng thái">
                      <?php foreach ($statuses as $k => $s): ?>
                        <option value="<?= $k ?>" <?= $o->status === $k ? 'selected' : '' ?>>
                          <?= $s['label'] ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </form>
                </td>
                <td style="white-space:nowrap;font-size:12px;color:#777;">
                  <?= date('H:i<br>d/m/Y', strtotime($o->created_at)) ?>
                </td>
                <td style="text-align:center;white-space:nowrap;">
                  <a href="/Order/adminDetail/<?= $o->id ?>" class="btn-detail me-1">
                    <i class="fas fa-eye me-1"></i>Chi tiết
                  </a>
                  <a href="/Order/delete/<?= $o->id ?>" class="btn-del"
                     onclick="return confirm('Xoá đơn hàng #<?= str_pad($o->id,6,'0',STR_PAD_LEFT) ?>?')">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <div class="pagination-wrap">
        <span style="font-size:13px;color:#777;">
          Trang <?= $page ?> / <?= $totalPages ?> &nbsp;·&nbsp; <?= $total ?> đơn hàng
        </span>
        <div class="d-flex gap-1">
          <?php for ($p = 1; $p <= $totalPages; $p++):
            $qs = http_build_query(['status'=>$status,'search'=>$search,'page'=>$p]);
          ?>
            <a href="/Order/admin?<?= $qs ?>" class="page-btn <?= $p === $page ? 'active' : '' ?>">
              <?= $p ?>
            </a>
          <?php endfor; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
