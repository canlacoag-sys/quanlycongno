<div class="content-wrapper">
 
  <section class="content">
    <div class="container-fluid">

      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h5 class="mb-3">Thông tin đơn khách lẻ</h5>
              <p><strong>Mã đơn:</strong> <?= htmlspecialchars($khachle->madon_id) ?></p>
              <p><strong>Ngày lập:</strong> <?= date('d/m/Y H:i', strtotime($khachle->created_at ?? $khachle->ngaylap)) ?></p>
              <p><strong>Thành tiền:</strong> <span class="text-danger font-weight-bold"><?= number_format($khachle->tongtien ?? 0) ?> đ</span></p>
              <p><strong>Giảm giá toàn đơn:</strong> <span class="text-danger font-weight-bold"><?= number_format($khachle->giamgiatt_thanhtien ?? 0) ?> đ</span></p>
              <p><strong>Phí ship:</strong> <span class="text-danger font-weight-bold"><?= number_format($khachle->ship ?? 0) ?> đ</span></p>
              <p><strong>Tổng tiền:</strong> <span class="text-danger font-weight-bold"><?= number_format($khachle->tongcong_tien ?? 0) ?> đ</span></p>
            </div>
            <div class="col-md-6">
              <h5 class="mb-3">Thông tin khách lẻ</h5>
              <p><strong>Tên khách:</strong> <?= htmlspecialchars($khachle->ten) ?></p>
              <p><strong>Điện thoại:</strong> <?= htmlspecialchars($khachle->dienthoai) ?></p>
              <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($khachle->diachi) ?></p>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="mb-3">Chi tiết sản phẩm đã mua</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead class="thead-light">
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Mã bánh</th>
                    <th class="text-center">Tên bánh</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-center">Giảm giá</th>
                    <th class="text-center">Giá sau giảm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($chitiet as $ct): ?>
                    <?php
                        $ten_banh = '';
                        $ma_banhs = array_map('trim', explode(',', $ct->ma_sp));
                        foreach ($ma_banhs as $ma_sp) {
                            foreach ($sanpham as $sp) {
                                if ($sp->ma_sp == $ma_sp) {
                                    $ten_banh .= $sp->ten_sp . '<br>';
                                    break;
                                }
                            }
                        }
                        // Xử lý giảm giá
                        $giam_loai = $ct->giamgiadg_loai ?? 'none';
                        $giam_giatri = $ct->giamgiadg_giatri ?? 0;
                        if ($giam_loai == 'phantram') {
                            $gia_sau_giam = $ct->don_gia * (1 - $giam_giatri / 100);
                            $giam_gia_hienthi = $giam_giatri . ' %';
                        } elseif ($giam_loai == 'tienmat') {
                            $gia_sau_giam = $ct->don_gia - $giam_giatri;
                            $giam_gia_hienthi = number_format($giam_giatri) . ' đ';
                        } else {
                            $gia_sau_giam = $ct->don_gia;
                            $giam_gia_hienthi = '0';
                        }
                        $thanh_tien = $gia_sau_giam * $ct->so_luong;
                    ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td class="text-center"><?= htmlspecialchars($ct->ma_sp) ?></td>
                        <td><?= $ten_banh ?></td>
                        <td class="text-right"><?= number_format($ct->don_gia) ?> đ</td>
                        <td class="text-right"><?= $giam_gia_hienthi ?></td>
                        <td class="text-right"><?= number_format($gia_sau_giam) ?> đ</td>
                        <td class="text-center"><?= $ct->so_luong ?></td>
                        <td class="text-right text-danger font-weight-bold"><?= number_format($thanh_tien) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card shadow-sm mb-3">
        <div class="card-body text-right">
          <a href="<?= site_url('khachle/edit/' . $khachle->id) ?>" class="btn btn-warning">Sửa đơn khách lẻ</a>
          <a href="<?= site_url('khachle') ?>" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
      </div>

    </div>
  </section>
</div>