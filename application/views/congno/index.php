<div class="content-wrapper">
<section class="content">
    <div class="container-fluid">
        <br />
      <!-- Bảng tổng hợp khách hàng nợ -->
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="card bg-light">
          <div class="card-header font-weight-bold">Tổng hợp công nợ khách hàng</div>
          <div class="card-body p-2">
            <table class="table table-sm table-bordered mb-0">
              <tr>
                <th class="text-center">Số lượng khách hàng nợ</th>
                <th class="text-center">Tổng tiền nợ</th>
              </tr>
              <tr>
                <?php
                  $tong_khachhang = count($data);
                  $tong_tien_no = 0;
                  foreach ($data as $row) {
                    $tong_tien_no += $row['tong_tien'];
                  }
                ?>
                <td class="text-center"><b><?= $tong_khachhang ?></b></td>
                <td class="text-center text-danger font-weight-bold"><b><?= number_format($tong_tien_no) ?> đ</b></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- End bảng tổng hợp -->
      <div class="card shadow-sm">
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th class="text-center">STT</th>
                <th>Tên khách hàng</th>
                <!-- <th>Số điện thoại</th> -->
                <th class="text-center">Tổng số lượng</th>
                <th class="text-center">Tổng tiền</th>
                <th>Chi tiết sản phẩm</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $i => $row): ?>
              <tr>
                <td class="text-center"><?= $i+1 ?></td>
                <td><?= htmlspecialchars($row['khachhang']->ten) ?></td>
                <!-- <td><?= htmlspecialchars($row['khachhang']->dienthoai) ?></td> -->
                <td class="text-center"><?= $row['tong_so_luong'] ?></td>
                <td class="text-right text-danger font-weight-bold"><?= number_format($row['tong_tien']) ?> đ</td>
                <td>
                  <ul class="mb-0 pl-3">
                    <?php foreach ($row['sanpham_chitiet'] as $sp): ?>
                        <li>
                        (<?= htmlspecialchars($sp['ma_sp']) ?>) x <?= $sp['so_luong'] ?> 
                        = <?= number_format($sp['thanh_tien']) ?> đ
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </td>
                <td class="text-center">
                  <a href="<?= site_url('congno/detail/'.$row['khachhang']->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xem công nợ khách hàng này?');"><i class="fas fa-eye"></i> </a>
                  <?php if (isset($user_role) && $user_role === 'admin'): ?>


                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>