<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>BIÊN NHẬN KHÁCH SỈ</title>
  <style>
    @media print {
      @page { size: A5 portrait; margin: 5mm 5mm 5mm 5mm; }
      html, body { width: 148mm; height: 210mm; }
    }
    body {
      font-family: 'Times New Roman', Arial, sans-serif;
      font-size: 15px;
      margin: 0;
      padding: 0;
      background: #fff;
      color: #222;
    }
    .receipt-container {
      width: 100%;
      max-width: 148mm;
      margin: 0 auto;
      padding: 0;
      background: #fff;
      min-height: 210mm;
      display: flex;
      flex-direction: column;
      height: 210mm;
      box-sizing: border-box;
    }
    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: stretch;
      margin-bottom: 0;
    }
    .shop-info, .right-info {
      font-size: 12px;
      line-height: 1.5;
      width: 38%;
      max-width: 38%;
      flex: 0 0 38%;
      color: #222;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .logo-center-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      flex: 0 0 28%;
      max-width: 28%;
      min-width: 120px;
    }
    .logo-center-header img {
      height: 62px;
      margin-bottom: 2px;
      display: block;
    }
    .right-info {
      text-align: right;
    }
    .right-info .date {
      margin-bottom: 2px;
    }
    .receipt-title {
      text-align: center;
      font-size: 1.5em;
      font-weight: bold;
      margin: 10px 0 8px 0;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .customer-row {
      font-size: 1.08em;
      margin-bottom: 2px;
    }
    .customer-row strong {
      font-weight: bold;
    }
    .border {
      border-top: 2px dashed #222;
      margin: 10px 0 8px 0;
    }
    table.chitiet {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
      background: #fff;
    }
    table.chitiet th, table.chitiet td {
      border: 1px solid #222;
      padding: 5px 8px;
      font-size: 1em;
      text-align: center;
    }
    table.chitiet th {
      background: #f5f5f5;
      font-weight: bold;
    }
    table.chitiet td.text-right {
      text-align: right !important;
    }
    .totals-row {
      font-size: 1.15em;
      font-weight: bold;
      text-align: right;
    }
    .sign-area {
      margin-top: 30px;
      width: 100%;
      display: flex;
      justify-content: space-between;
      font-size: 1em;
    }
    .sign-col {
      width: 45%;
      text-align: center;
    }
    .sign-col .sign-label {
      font-style: italic;
      color: #555;
      margin-bottom: 40px;
      margin-top: 20px;
    }
    .thank {
      margin-top: auto;
      font-size: 1.15em;
      font-weight: bold;
      text-align: center;
      letter-spacing: 1px;
      color: #222;
      padding-bottom: 5mm;
    }
    .badge {
      display: inline-block;
      padding: 0.35em 0.7em;
      font-size: 0.95em;
      font-weight: 600;
      line-height: 1;
      color: #fff;
      background-color: #007bff;
      border-radius: 0.25rem;
      margin-right: 4px;
    }
    .badge-info { background-color: #17a2b8 !important; }
    .badge-primary { background-color: #007bff !important; }
    .badge-danger { background-color: #dc3545 !important; }
    .totals-row .total-label {
      font-size: 1.15em;
      font-weight: bold;
      color: #222;
      padding-right: 10px;
    }
    .totals-row .total-value {
      font-size: 1.25em;
      font-weight: bold;
      color: #222;
      letter-spacing: 1px;
    }
  </style>
</head>
<body onload="window.print()">

<?php
  // Chuẩn bị mảng sản phẩm dạng ma_sp => object để tra cứu nhanh
  $sanpham_map = [];
  if (isset($sanpham) && is_array($sanpham)) {
    foreach ($sanpham as $sp) {
      $sanpham_map[$sp->ma_sp] = $sp;
    }
  }
  // Hàm xác định loại bánh: 1 mã -> tra db, combo=1 thì Combo, combo=0 thì Cái; nhiều mã -> Hộp N bánh
  function get_loai_banh_tooltip($ma_sp_str, $sanpham_map) {
    $arrMa = array_filter(array_map('trim', explode(',', $ma_sp_str)), function($x){ return $x !== ''; });
    $count = count($arrMa);
    if ($count === 1) {
      $m = $arrMa[0];
      if (isset($sanpham_map[$m])) {
        $sp = $sanpham_map[$m];
        if (isset($sp->combo)) {
          if ((string)$sp->combo === '1' || (int)$sp->combo === 1) {
            return 'Combo';
          } else if ((string)$sp->combo === '0' || (int)$sp->combo === 0) {
            return 'Cái';
          } else {
            return 'Không xác định';
          }
        }
        return 'Cái';
      }
      return 'Không xác định';
    }
    if ($count > 1) {
      return 'Hộp ' . $count . ' bánh';
    }
    return '';
  }
?>
<?php for ($lien = 1; $lien <= 3; $lien++): ?>
<div class="receipt-container" style="<?= ($lien === 2 || $lien === 3) ? 'page-break-before:always;' : '' ?>">
  <div>
    <div class="header-row">
      <div class="shop-info">
        <div class="shop-address">Địa chỉ: <i>3N/15 Đốc Binh Kiều, phường 2<br> Thành phố Mỹ Tho, tỉnh Tiền Giang</i></div>
        <div class="shop-phone">
          Hotline/Zalo: <strong>0903.333.265 (Nga)</strong><br>
          Điện thoại: <strong>0939.993.265 - 0908.424.777</strong>
       </div>
      </div>
      <div class="logo-center-header">
        <img src="<?= base_url('assets/dist/img/logo.png') ?>" alt="Logo Thanh Tâm">
      </div>
      <div class="right-info">
        <div class="date">Ngày <?= date('d/m/Y H:i', strtotime($donhang->ngaylap)) ?></div>
        <div>Mã Đơn: <?= htmlspecialchars($donhang->madon_id ?? $donhang->id) ?></div>
        <div>Loại bánh: <?= isset($donhang->co_chiet_khau) && $donhang->co_chiet_khau ? 'Có chiết khấu' : 'Không chiết khấu' ?></div>
        <div>Liên <?= $lien ?>: <?= $lien === 1 ? 'Giao khách hàng' : ($lien === 2 ? 'Giao nhận' : 'Lưu nội bộ') ?></div>
      </div>
    </div>
    <div class="receipt-title">BIÊN NHẬN</div>
    <div class="customer-row">
      <strong>Khách hàng :</strong> <?= htmlspecialchars($khachhang->ten) ?>
      <span style="margin-left:30px;"><strong>Điện thoại :</strong> <?= htmlspecialchars($khachhang->dienthoai) ?></span>
    </div>
    <div class="customer-row">
      <strong>Địa chỉ :</strong> <?= htmlspecialchars($khachhang->diachi) ?>
    </div>
    
    <table class="chitiet">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã bánh</th>
          <th>SL</th>
          <th class="text-right">Đơn giá</th>
          <th class="text-right">Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($chitiet as $ct): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($ct->ma_sp) ?></td>
          <td><?= $ct->so_luong ?></td>
          <td class="text-right"><?= number_format($ct->don_gia) ?></td>
          <td class="text-right"><?= number_format($ct->thanh_tien) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <table style="width:100%;margin-top:10px;">
      <tr>
        <td class="totals-row" colspan="6">
          <span class="total-label">Tổng tiền:</span>
          <span class="total-value"><?= number_format($donhang->tongtien) ?> đ</span>
        </td>
      </tr>
        <?php if (!empty($donhang->ghi_chu)): ?>
        <tr>
          <td colspan="6" style="padding:8px 0;color:#444;font-size:1em;">
            <strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($donhang->ghi_chu)) ?>
          </td>
        </tr>
        <?php endif; ?>
    </table>
    <div class="sign-area" style="margin-top:10px;">
      <?php if ($lien === 2): ?>
      <div class="sign-col">
        <div class="sign-label">Người giao hàng</div>
        <div style="height:60px;"></div>
        <div>(Ký, ghi rõ họ tên)</div>
      </div>
      <div class="sign-col">
        <div class="sign-label">Người nhận</div>
        <div style="height:60px;"></div>
        <div>(Ký, ghi rõ họ tên)</div>
      </div>
      <?php elseif ($lien === 3): ?>
      <div class="sign-col">
        <div class="sign-label">Người giao hàng</div>
        <div style="height:60px;"></div>
        <div>(Ký, ghi rõ họ tên)</div>
      </div>
      <div class="sign-col">
        <div class="sign-label"></div>
        <div style="height:60px;"></div>
        <div></div>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="thank">Xin cảm ơn Quý khách!</div>
</div>
<?php endfor; ?>
</body>
</html>
