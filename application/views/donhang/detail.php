<?php $this->load->view('khachhang/add'); ?>
<div class="content-wrapper">

  <section class="content">
    <div class="container-fluid">

      <!-- Thông tin đơn hàng & khách hàng -->
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h5 class="mb-3">Thông tin đơn hàng</h5>
              <p><strong>Mã đơn hàng:</strong> <?= htmlspecialchars($donhang->madon_id) ?></p>
              <p><strong>Ngày lập:</strong> <?= date('d/m/Y H:i', strtotime($donhang->ngaylap)) ?></p>
              <p><strong>Tổng tiền:</strong> <span class="text-danger font-weight-bold"><?= number_format($donhang->tongtien) ?> đ</span></p>
              <p><strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($donhang->ghi_chu ?? '')) ?></p>
            </div>
            <div class="col-md-6">
              <h5 class="mb-3">Thông tin khách hàng</h5>
              <p><strong>Tên khách hàng:</strong> <?= htmlspecialchars($khachhang->ten) ?></p>
              <p><strong>Điện thoại:</strong> <?= htmlspecialchars($khachhang->dienthoai) ?></p>
              <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($khachhang->diachi) ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Bảng chi tiết sản phẩm -->
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="mb-3">Chi tiết sản phẩm đã mua</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th class="text-center">STT</th>
                  <th class="text-center">Mã sản phẩm</th>
                  <th class="text-center">Tên sản phẩm</th>
                  <th class="text-center">Đơn giá</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-center">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1; foreach ($chitiet as $ct): ?>
                  <?php
                    $ten_sp = '';
                    $ma_sps = array_map('trim', explode(',', $ct->ma_sp));
                    foreach ($ma_sps as $ma_sp) {
                      foreach ($sanpham as $sp) {
                        if ($sp->ma_sp == $ma_sp) {
                          $ten_sp .= htmlspecialchars($sp->ten_sp) . '<br>';
                          break;
                        }
                      }
                    }
                    $ten_sp = rtrim($ten_sp, ', ');
                  ?>
                  <tr>
                    <td class="text-center"><?= $i++ ?></td>
                    <td class="text-center"><?= htmlspecialchars($ct->ma_sp) ?></td>
                    <td><?= $ten_sp ?></td>
                    <td class="text-right"><?= number_format($ct->don_gia) ?> đ</td>
                    <td class="text-center"><?= $ct->so_luong ?></td>
                    <td class="text-right text-danger font-weight-bold"><?= number_format($ct->don_gia * $ct->so_luong) ?> đ</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Tác vụ -->
      <div class="card shadow-sm mb-3">
        <div class="card-body text-right">
          <a href="<?= site_url('donhang/edit/' . $donhang->id) ?>" class="btn btn-warning">Sửa đơn hàng</a>
          <a href="<?= site_url('donhang') ?>" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
      </div>

    </div>
  </section>
</div>