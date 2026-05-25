<?php include 'app/views/shares/header.php'; ?>
<style>
.section-card { background:#fff; border-radius:14px; border:1px solid #eee; box-shadow:0 2px 12px rgba(0,0,0,.05); overflow:hidden; margin-bottom:20px; }
.section-head { padding:15px 22px; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; gap:10px; font-size:15px; font-weight:800; color:#1a1a1a; }
.section-head i { color:#d70018; }
.section-body { padding:20px 22px; }
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
@media(max-width:576px){.info-grid{grid-template-columns:1fr;}}
.info-item label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#999; margin-bottom:4px; display:block; }
.info-item p { font-size:14px; font-weight:600; color:#1a1a1a; margin:0; }
.prod-row { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #f5f5f5; }
.prod-row:last-child { border-bottom:none; }
.prod-row img { width:60px; height:60px; object-fit:contain; border-radius:8px; border:1px solid #eee; padding:4px; background:#fafafa; flex-shrink:0; }
.prod-name { font-size:13.5px; font-weight:600; color:#1a1a1a; flex:1; }
.prod-name a { color:inherit; text-decoration:none; }
.prod-name a:hover { color:#d70018; }
.prod-qty { font-size:12px; color:#777; margin-top:3px; }
.prod-price { font-size:14px; font-weight:800; color:#d70018; white-space:nowrap; }
.sum-row { display:flex; justify-content:space-between; font-size:14px; margin-bottom:9px; }
.sum-row .lbl { color:#666; }
.sum-row.grand { font-size:17px; font-weight:800; color:#d70018; padding-top:10px; border-top:2px solid #f0f0f0; margin-top:4px; }
/* Status selector */
.status-selector { display:flex; flex-wrap:wrap; gap:8px; }
.status-opt { display:none; }
.status-opt + label {
  display:inline-flex; align-items:center; gap:7px;
  padding:9px 16px; border-radius:8px; border:2px solid #eee;
  font-size:13px; font-weight:600; cursor:pointer; transition:all .2s;
  color:#555;
}
.status-opt:checked + label { border-color:currentColor; }
.status-opt[value="pending"]   + label { color:#856404; }
.status-opt[value="confirmed"] + label { color:#055160; }
.status-opt[value="shipping"]  + label { color:#084298; }
.status-opt[value="delivered"] + label { color:#0a3622; }
.status-opt[value="cancelled"] + label { color:#842029; }
.status-opt[value="pending"]:checked   + label { background:#fff3cd; border-color:#ffc107; }
.status-opt[value="confirmed"]:checked + label { background:#cff4fc; border-color:#0dcaf0; }
.status-opt[value="shipping"]:checked  + label { background:#cfe2ff; border-color:#0d6efd; }
.status-opt[value="delivered"]:checked + label { background:#d1e7dd; border-color:#198754; }
.status-opt[value="cancelled"]:checked + label { background:#f8d7da; border-color:#dc3545; }
.btn-save-status { background:#d70018; color:#fff; border:none; border-radius:9px; padding:11px 28px; font-size:14px; font-weight:700; cursor:pointer; transition:all .2s; margin-top:14px; }
.btn-save-status:hover { background:#b5001a; transform:translateY(-1px); box-shadow:0 5px 16px rgba(215,0,24,.3); }
.note-area { border:1.5px solid #e0e0e0; border-radius:9px; padding:10px 14px; font-size:13.5px; width:100%; resize:vertical; min-height:90px; font-family:inherit; transition:border-color .2s; }
.note-area:focus { border-color:#d70018; outline:none; }
.btn-save-note { background:#1a1a1a; color:#fff; border:none; border-radius:9px; padding:9px 22px; font-size:13px; font-weight:700; cursor:pointer; transition:background .2s; margin-top:10px; }
.btn-save-note:hover { background:#333; }
.timeline { position:relative; padding-left:28px; }
.timeline::before { content:''; position:absolute; left:9px; top:0; bottom:0; width:2px; background:#eee; }
.tl-item { position:relative; margin-bottom:16px; }
.tl-item:last-child { margin-bottom:0; }
.tl-dot { position:absolute; left:-24px; top:3px; width:14px; height:14px; border-radius:50%; border:2px solid #eee; background:#fff; }
.tl-dot.done { background:#d70018; border-color:#d70018; }
.tl-time { font-size:11px; color:#aaa; }
.tl-text { font-size:13px; font-weight:600; color:#333; }
.back-btn { color:#d70018; text-decoration:none; font-size:13.5px; font-weight:600; display:inline-flex; align-items:center; gap:6px; margin-bottom:14px; }
.back-btn:hover { text-decoration:underline; color:#b5001a; }
</style>

<div class="container mt-4 mb-5" style="max-width:960px;">

  <nav class="mb-2"><ol class="breadcrumb" style="background:none;padding:0;font-size:13px;">
    <li class="breadcrumb-item"><a href="/" style="color:#d70018;text-decoration:none;">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="/Order/admin" style="color:#d70018;text-decoration:none;">Quản lý đơn hàng</a></li>
    <li class="breadcrumb-item active text-muted">Đơn #<?= str_pad($order->id,6,'0',STR_PAD_LEFT) ?></li>
  </ol></nav>

  <a href="/Order/admin" class="back-btn"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>

  <!-- ORDER BANNER -->
  <div style="background:linear-gradient(135deg,#d70018,#ff424e);border-radius:14px;padding:18px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
      <div style="font-size:20px;font-weight:800;color:#fff;">
        <i class="fas fa-receipt me-2"></i>Đơn hàng #<?= str_pad($order->id,6,'0',STR_PAD_LEFT) ?>
      </div>
      <div style="font-size:13px;color:rgba(255,255,255,.85);margin-top:4px;">
        <i class="fas fa-clock me-1"></i><?= date('H:i - d/m/Y', strtotime($order->created_at)) ?>
        <?php if (!empty($order->updated_at) && $order->updated_at !== $order->created_at): ?>
          &nbsp;·&nbsp; Cập nhật: <?= date('H:i d/m/Y', strtotime($order->updated_at)) ?>
        <?php endif; ?>
      </div>
    </div>
    <?php $s = $statuses[$order->status] ?? ['label'=>$order->status,'icon'=>'fa-circle']; ?>
    <div style="background:rgba(255,255,255,.2);border:1.5px solid rgba(255,255,255,.4);border-radius:20px;padding:7px 18px;font-size:13px;font-weight:700;color:#fff;display:flex;align-items:center;gap:7px;">
      <i class="fas <?= $s['icon'] ?>"></i> <?= $s['label'] ?>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-7">

      <!-- SẢN PHẨM -->
      <div class="section-card">
        <div class="section-head"><i class="fas fa-shopping-bag"></i>Sản phẩm đã đặt <span style="font-size:13px;color:#999;font-weight:500;">(<?= count($orderDetails) ?>)</span></div>
        <div class="section-body" style="padding-top:8px;padding-bottom:8px;">
          <?php foreach ($orderDetails as $item): ?>
            <div class="prod-row">
              <?php $img = !empty($item->image) ? "/public/uploads/products/{$item->image}" : "https://via.placeholder.com/60"; ?>
              <img src="<?= $img ?>" alt="">
              <div style="flex:1;">
                <div class="prod-name">
                  <?php if (!empty($item->product_id_ref)): ?>
                    <a href="/Product/show/<?= $item->product_id_ref ?>"><?= htmlspecialchars($item->product_name) ?></a>
                  <?php else: ?>
                    <?= htmlspecialchars($item->product_name) ?>
                  <?php endif; ?>
                </div>
                <div class="prod-qty"><?= number_format($item->price,0,',','.') ?>đ &times; <?= $item->quantity ?></div>
              </div>
              <div class="prod-price"><?= number_format($item->price * $item->quantity,0,',','.') ?>đ</div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- THÔNG TIN GIAO HÀNG -->
      <div class="section-card">
        <div class="section-head"><i class="fas fa-map-marker-alt"></i>Thông tin giao hàng</div>
        <div class="section-body">
          <div class="info-grid">
            <div class="info-item"><label>Người nhận</label><p><?= htmlspecialchars($order->customer_name) ?></p></div>
            <div class="info-item"><label>Số điện thoại</label><p><?= htmlspecialchars($order->phone) ?></p></div>
            <div class="info-item" style="grid-column:1/-1;"><label>Địa chỉ</label><p><?= htmlspecialchars($order->address) ?></p></div>
          </div>
        </div>
      </div>

      <!-- GHI CHÚ NỘI BỘ -->
      <div class="section-card">
        <div class="section-head"><i class="fas fa-sticky-note"></i>Ghi chú nội bộ</div>
        <div class="section-body">
          <form method="POST" action="/Order/saveNote">
            <input type="hidden" name="id" value="<?= $order->id ?>">
            <textarea name="note" class="note-area" placeholder="Ghi chú dành cho nhân viên xử lý đơn..."><?= htmlspecialchars($order->note ?? '') ?></textarea>
            <button type="submit" class="btn-save-note"><i class="fas fa-save me-2"></i>Lưu ghi chú</button>
          </form>
        </div>
      </div>

    </div>

    <div class="col-md-5">

      <!-- ĐỔI TRẠNG THÁI -->
      <div class="section-card">
        <div class="section-head"><i class="fas fa-exchange-alt"></i>Cập nhật trạng thái</div>
        <div class="section-body">
          <form method="POST" action="/Order/updateStatus">
            <input type="hidden" name="id"  value="<?= $order->id ?>">
            <input type="hidden" name="ref" value="/Order/adminDetail/<?= $order->id ?>">
            <div class="status-selector">
              <?php foreach ($statuses as $k => $s): ?>
                <input type="radio" name="status" value="<?= $k ?>" id="st_<?= $k ?>" class="status-opt"
                  <?= $order->status === $k ? 'checked' : '' ?>>
                <label for="st_<?= $k ?>">
                  <i class="fas <?= $s['icon'] ?>"></i> <?= $s['label'] ?>
                </label>
              <?php endforeach; ?>
            </div>
            <button type="submit" class="btn-save-status">
              <i class="fas fa-check me-2"></i>Lưu trạng thái
            </button>
          </form>
        </div>
      </div>

      <!-- TỔNG KẾT -->
      <div class="section-card">
        <div class="section-head"><i class="fas fa-calculator"></i>Tổng kết đơn hàng</div>
        <div class="section-body">
          <?php
          $sub = array_sum(array_map(fn($i) => $i->price * $i->quantity, $orderDetails));
          $ship = $sub >= 500000 ? 0 : 30000;
          ?>
          <div class="sum-row"><span class="lbl">Tạm tính</span><span><?= number_format($sub,0,',','.') ?>đ</span></div>
          <div class="sum-row">
            <span class="lbl">Phí vận chuyển</span>
            <span style="color:#198754;font-weight:600;"><?= $ship===0 ? 'Miễn phí' : number_format($ship,0,',','.').'đ' ?></span>
          </div>
          <div class="sum-row grand"><span>Tổng thanh toán</span><span><?= number_format($order->total_price,0,',','.') ?>đ</span></div>
        </div>
      </div>

      <!-- LỊCH SỬ TRẠNG THÁI (placeholder) -->
      <div class="section-card">
        <div class="section-head"><i class="fas fa-history"></i>Lịch sử xử lý</div>
        <div class="section-body">
          <div class="timeline">
            <div class="tl-item">
              <div class="tl-dot done"></div>
              <div class="tl-time"><?= date('H:i d/m/Y', strtotime($order->created_at)) ?></div>
              <div class="tl-text">Đơn hàng được tạo</div>
            </div>
            <?php if ($order->status !== 'pending'): ?>
            <div class="tl-item">
              <div class="tl-dot done"></div>
              <div class="tl-time"><?= date('H:i d/m/Y', strtotime($order->updated_at ?? $order->created_at)) ?></div>
              <div class="tl-text">Trạng thái: <?= $statuses[$order->status]['label'] ?? $order->status ?></div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- XOÁ ĐƠN -->
      <div class="text-end">
        <a href="/Order/delete/<?= $order->id ?>" class="btn btn-outline-danger btn-sm fw-bold"
           onclick="return confirm('Xoá vĩnh viễn đơn hàng này?')">
          <i class="fas fa-trash me-1"></i>Xoá đơn hàng
        </a>
      </div>

    </div>
  </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
