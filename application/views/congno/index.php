<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1 class="mb-3">Danh sách công nợ khách hàng</h1>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>STT</th>
                <th>Tên khách hàng</th>
                <th>Số điện thoại</th>
                <th>Tổng số lượng</th>
                <th>Tổng tiền</th>
                <th>Chi tiết sản phẩm</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $i => $row): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($row['khachhang']->ten) ?></td>
                <td><?= htmlspecialchars($row['khachhang']->dienthoai) ?></td>
                <td><?= $row['tong_so_luong'] ?></td>
                <td class="text-right text-danger font-weight-bold"><?= number_format($row['tong_tien']) ?> đ</td>
                <td>
                  <ul class="mb-0 pl-3">
                    <?php foreach ($row['sanpham_chitiet'] as $sp): ?>
                        <li>
                        <?= htmlspecialchars($sp['ten_sp']) ?> x <?= $sp['so_luong'] ?> 
                        (<?= number_format($sp['thanh_tien']) ?> đ)
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </td>
                <td>
                  <?php if (isset($user_role) && $user_role === 'admin'): ?>
                  <a href="<?= site_url('congno/del/'.$row['khachhang']->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa công nợ này?');">Xóa</a>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if (isset($user_role) && $user_role === 'admin'): ?>
          <a href="<?= site_url('congno/add') ?>" class="btn btn-primary mt-3">Thêm công nợ</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</div>