<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Hóa đơn POS</title>
  <style>
    body { font-family: monospace; font-size: 14px; }
    .center { text-align: center; }
    table { width: 100%; border-collapse: collapse; }
    td,th { padding: 2px 4px; }
    .border { border-top: 1px dashed #000; }
  </style>
</head>
<body onload="window.print()">
  <div class="center"><strong>HÓA ĐƠN BÁN HÀNG</strong></div>
  <div>Mã đơn: <?= $donhang->id ?></div>
  <div>Khách: <?= htmlspecialchars($khachhang->ten) ?></div>
  <div>ĐT: <?= htmlspecialchars($khachhang->dienthoai) ?></div>
  <div>Địa chỉ: <?= htmlspecialchars($khachhang->diachi) ?></div>
  <div>Ngày: <?= date('d/m/Y H:i', strtotime($donhang->ngaylap)) ?></div>
  <table>
    <thead>
      <tr><th>Mã</th><th>SL</th><th>Đơn giá</th><th>Thành tiền</th></tr>
    </thead>
    <tbody>
      <?php foreach($chitiet as $ct): ?>
      <tr>
        <td><?= htmlspecialchars($ct->ma_sp) ?></td>
        <td><?= $ct->so_luong ?></td>
        <td><?= number_format($ct->don_gia) ?></td>
        <td><?= number_format($ct->thanh_tien) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="border"></div>
  <div>Tổng tiền: <?= number_format($donhang->tongtien) ?></div>
  <div>Đã trả: <?= number_format($donhang->datra) ?></div>
  <div>Còn nợ: <?= number_format($donhang->conno) ?></div>
  <div class="center">Cảm ơn quý khách!</div>
</body>
</html>
