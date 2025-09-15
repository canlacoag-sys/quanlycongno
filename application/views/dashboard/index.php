<!-- application/views/dashboard/index.php -->
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">

      <!-- Thống kê nhanh -->
      <div class="row">
        <div class="col-md">
          <div class="card text-white bg-primary mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-money-bill"></i> Tổng tiền đơn lẻ</h5>
              <p class="card-text display-4 text-right"><?= number_format($tong_tien_khachle ?? 0) ?> đ</p>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="card text-white bg-info mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-file-invoice"></i> Tổng số đơn lẻ</h5>
              <p class="card-text display-4 text-right"><?= $tong_don_khachle ?? 0 ?></p>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="card text-white bg-success mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-user"></i> Tổng khách hàng lẻ</h5>
              <p class="card-text display-4 text-right"><?= $tong_khachhang_le ?? 0 ?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
  		<div class="col-md">
          <div class="card text-white bg-warning mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Tổng tiền đơn sỉ</h5>
              <p class="card-text display-4 text-right"><?= number_format($tong_tien_khachsi ?? 0) ?> đ</p>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="card text-white bg-secondary mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Tổng đơn sỉ</h5>
              <p class="card-text display-4 text-right"><?= $tong_don_khachsi ?? 0 ?></p>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="card text-white bg-dark mb-3">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-users"></i> Tổng số khách sỉ</h5>
              <p class="card-text display-4 text-right"><?= $tong_khachhang_si ?? 0 ?></p>
            </div>
          </div>
        </div>
      </div>


      <!-- Bảng dữ liệu nhanh -->
      <div class="row">
        <div class="col-md">
          <div class="card mb-3">
            <div class="card-header">Đơn khách lẻ mới nhất</div>
            <div class="card-body p-0">
              <table class="table table-sm table-bordered mb-0">
                <thead>
                  <tr>
                    <th class="text-center">Mã đơn</th>
                    <th class="text-center">Khách lẻ</th>
                    <th class="text-right">Tổng tiền</th>
                    <th class="text-center">Ngày lập</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($khachle_moi ?? [] as $row): ?>
                  <tr>
                    <td class="text-center"><?= htmlspecialchars($row->madon_id) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row->ten) ?></td>
                    <td class="text-right"><?= number_format($row->tongcong_tien) ?> đ</td>
                    <td class="text-center"><?= date('d/m/Y H:i', strtotime($row->created_at ?? $row->ngaylap)) ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Quản lý nhanh -->
      <div class="row">
        <div class="col-md-12 text-right">
          <a href="<?= site_url('khachle/add') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Lên toa khách lẻ</a>
          <a href="<?= site_url('khachle') ?>" class="btn btn-warning"><i class="fas fa-shopping-cart"></i> Bán tại quầy</a>
          <br>
        </div>
      </div>
    </div>

	<div class="container-fluid">

      <!-- Bảng dữ liệu nhanh -->
      <div class="row">
        
        <div class="col-md">
          <div class="card mb-3">
            <div class="card-header">Đơn khách sỉ mới nhất</div>
            <div class="card-body p-0">
              <table class="table table-sm table-bordered mb-0">
                <thead>
                  <tr>
                    <th class="text-center">Mã đơn</th>
                    <th class="text-center">Khách hàng</th>
                    <th class="text-right">Tổng tiền</th>
                    <th class="text-center">Ngày lập</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($khachsi_moi ?? [] as $row): ?>
                  <tr>
                    <td class="text-center"><?= htmlspecialchars($row->madon_id) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row->ten_khach) ?></td>
                    <td class="text-right"><?= number_format($row->tongtien) ?> đ</td>
                    <td class="text-center"><?= date('d/m/Y H:i', strtotime($row->created_at ?? $row->ngaylap)) ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Quản lý nhanh -->
      <div class="row">
        <div class="col-md-12 text-right">
          <a href="<?= site_url('donhang/addcochietkhau') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Bánh có chiết khấu</a>
          <a href="<?= site_url('donhang/addkochietkhau') ?>" class="btn btn-info"><i class="fas fa-plus"></i> Bánh không chiết khấu</a>
          <a href="<?= site_url('donhang') ?>" class="btn btn-warning"><i class="fas fa-shopping-cart"></i> Bán sỉ/Đại lý</a>
        </div>
      </div>
    </div>
  </section>
</div>
