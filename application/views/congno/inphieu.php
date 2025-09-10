<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Phiếu Kết Sổ Công Nợ</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .a4 { width: 210mm; min-height: 297mm; margin: auto; background: #fff; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total { font-weight: bold; color: #d00; }
        .info { margin-bottom: 10px; }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
<div class="a4">
    <h2>PHIẾU KẾT SỔ CÔNG NỢ</h2>
    <div class="info">
        <strong>Khách hàng:</strong> <?= htmlspecialchars($khachhang->ten) ?><br>
        <strong>Điện thoại:</strong> <?= htmlspecialchars($khachhang->dienthoai) ?><br>
        <strong>Địa chỉ:</strong> <?= htmlspecialchars($khachhang->diachi) ?><br>
        <strong>Ngày lập phiếu:</strong> <?= date('d/m/Y H:i', strtotime($congno->ngaylap)) ?><br>
        <strong>Ghi chú:</strong> <?= htmlspecialchars($congno->ghichu) ?><br>
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-center">Mã sản phẩm</th>
                <th class="text-center">Loại sản phẩm</th>
                <th class="text-right">Đơn giá</th>
                <th class="text-center">Số lượng</th>
                <th class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
                        <tr>
                                <td class="text-center"><?= htmlspecialchars($item['ma_sp']) ?></td>
                                <td class="text-center">
                                    <?php if (isset($item['co_chiet_khau']) && $item['co_chiet_khau']): ?>
                                        <span class="badge badge-success">Có chiết khấu</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Không chiết khấu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right"><?= number_format($item['don_gia']) ?> đ</td>
                                <td class="text-center"><?= $item['so_luong'] ?></td>
                                <td class="text-right"><?= number_format($item['thanh_tien']) ?> đ</td>
                        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Tổng tiền</th>
                <th class="text-right total"><?= number_format($congno->tong_tien) ?> đ</th>
            </tr>
        </tfoot>
    </table>
    <div style="margin-top:40px;">
        <div style="float:left; width:40%; text-align:center;">Người lập phiếu<br><br><br>__________________</div>
        <div style="float:right; width:40%; text-align:center;">Khách hàng<br><br><br>__________________</div>
        <div style="clear:both;"></div>
    </div>
    <button class="print-btn" onclick="window.print()">In phiếu</button>
</div>
</body>
</html>
