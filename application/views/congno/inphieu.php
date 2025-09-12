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

        <h4>Có chiết khấu</h4>
        <table>
                <thead>
                    <tr>
                        <th class="text-center">Mã sản phẩm</th>
                        <th class="text-right">Đơn giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $subtotal_chiet = 0;
                    foreach ($items as $item) {
                        if (isset($item['co_chiet_khau']) && $item['co_chiet_khau']) {
                            echo '<tr>';
                            echo '<td class="text-center">' . htmlspecialchars($item['ma_sp']) . '</td>';
                            echo '<td class="text-right">' . number_format($item['don_gia']) . ' đ</td>';
                            echo '<td class="text-center">' . $item['so_luong'] . '</td>';
                            echo '<td class="text-right">' . number_format($item['thanh_tien']) . ' đ</td>';
                            echo '</tr>';
                            $subtotal_chiet += intval($item['thanh_tien']);
                        }
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Tổng (trước chiết khấu)</th>
                        <th class="text-right total"><?= number_format($congno->tong_chietkhau_truoc ?? $subtotal_chiet) ?> đ</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">Chiết khấu (<?= htmlspecialchars($congno->chietkhau_percent ?? 0) ?>%)</th>
                        <th class="text-right total"><?= number_format($congno->chietkhau_amount ?? round(($subtotal_chiet * ($congno->chietkhau_percent ?? 0)/100))) ?> đ</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">Tổng sau chiết khấu</th>
                        <th class="text-right total"><?= number_format($congno->tong_chietkhau_sau ?? (($congno->tong_chietkhau_truoc ?? $subtotal_chiet) - ($congno->chietkhau_amount ?? round($subtotal_chiet * (($congno->chietkhau_percent ?? 0)/100))))) ?> đ</th>
                    </tr>
                </tfoot>
        </table>

        <h4>Không chiết khấu</h4>
        <table>
                <thead>
                    <tr>
                        <th class="text-center">Mã sản phẩm</th>
                        <th class="text-right">Đơn giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $subtotal_non = 0;
                    foreach ($items as $item) {
                        if (!isset($item['co_chiet_khau']) || !$item['co_chiet_khau']) {
                            echo '<tr>';
                            echo '<td class="text-center">' . htmlspecialchars($item['ma_sp']) . '</td>';
                            echo '<td class="text-right">' . number_format($item['don_gia']) . ' đ</td>';
                            echo '<td class="text-center">' . $item['so_luong'] . '</td>';
                            echo '<td class="text-right">' . number_format($item['thanh_tien']) . ' đ</td>';
                            echo '</tr>';
                            $subtotal_non += intval($item['thanh_tien']);
                        }
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Tổng không chiết khấu</th>
                        <th class="text-right total"><?= number_format($congno->tong_khong_chiet ?? $subtotal_non) ?> đ</th>
                    </tr>
                </tfoot>
        </table>

        <table>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Tổng cộng</th>
                    <th class="text-right total"><?= number_format($congno->tong_cong ?? (($congno->tong_chietkhau_sau ?? (($congno->tong_chietkhau_truoc ?? $subtotal_chiet) - ($congno->chietkhau_amount ?? round($subtotal_chiet * (($congno->chietkhau_percent ?? 0)/100))))) + ($congno->tong_khong_chiet ?? $subtotal_non))) ?> đ</th>
                </tr>
            </tfoot>
        </table>
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
