<?php $this->load->helper('money'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
       <br />
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <form method="get" action="<?= site_url('donhang/index') ?>">
          <div class="input-group" style="width:420px; max-width:100%;">
            <input type="text" name="keyword" class="form-control"
                   value="<?= html_escape($keyword ?? '') ?>"
                   placeholder="Tìm khách hàng, mã đơn, ngày lập...">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary ml-2">
                <i class="fas fa-search"></i> Tìm kiếm
              </button>
              <?php if (!empty($keyword)): ?>
                <a href="<?= site_url('donhang'); ?>" class="btn btn-secondary ml-2">
                  Xóa tìm
                </a>
              <?php endif; ?>
            </div>
          </div>
        </form>
      </div>
      <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped table-hover mb-0 table-products">
            <thead>
              <tr>
                <th class="text-center" style="width:44px;">STT</th>
                <th>Mã đơn</th>
                <th>Ngày tạo</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Loại bánh</th>
                <th>Ghi chú</th>
                <th class="text-center" style="width:170px;">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($list)): $i = 1 + ($offset ?? 0); foreach ($list as $dh): ?>
              <tr>
                <td class="text-center"><?= $i++ ?></td>
                <td><?= htmlspecialchars($dh->madon_id) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($dh->ngaylap)) ?></td>
                <td><?= htmlspecialchars($dh->ten_khachhang ?? '') ?></td>
                <td class="text-right tong-tien-value"><?= money_vnd($dh->tongtien) ?></td>
                <td>
                  <?php
                    // Hiển thị loại sản phẩm: Có chiết khấu hoặc Không chiết khấu
                    if (isset($dh->co_chiet_khau) && $dh->co_chiet_khau) {
                      echo '<span class="badge badge-success">Có chiết khấu</span>';
                    } else {
                      echo '<span class="badge badge-secondary">Không chiết khấu</span>';
                    }
                  ?>
                </td>
                <td><?= nl2br(htmlspecialchars($dh->ghi_chu ?? '')) ?></td>
                <td class="text-center">
                  <a href="<?= site_url('donhang/detail/'.$dh->id) ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> </a>
                  <a href="<?= site_url('donhang/edit/'.$dh->id); ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> </a>
                  <a href="<?= site_url('donhang/pos/'.$dh->id); ?>" class="btn btn-warning btn-sm" target="_blank"><i class="fas fa-print"></i> </a>
                  <?php if ($user_role === 'admin'): ?>
                  <button type="button" class="btn btn-danger btn-sm btn-delete-donhang" data-id="<?= $dh->id ?>" data-toggle="modal" data-target="#delDonHangModal"><i class="fas fa-trash-alt"></i> </button>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center text-muted">Chưa có đơn hàng nào.</td></tr>
              <?php endif; ?>
              
            </tbody>
          </table>
        </div>
        <div class="card-footer py-2">
          <?= isset($pagination) ? $pagination : '' ?>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('donhang/del'); ?>
<script>
$(function() {
  // Xoá đơn hàng bằng modal ajax
  $('.btn-delete-donhang').click(function() {
    var id = $(this).data('id');
    // Tìm hàng đơn hàng theo id
    var row = $(this).closest('tr');
    var madon = row.find('td').eq(0).text().trim();
    var tenkh = row.find('td').eq(2).text().trim();
    $('#delMaDH').text(madon);
    $('#delTenKH').text(tenkh);
    $('#btnConfirmDeleteDonHang').data('id', id);
  });
  $('#btnConfirmDeleteDonHang').click(function() {
    var id = $(this).data('id');
    window.location.href = '<?= site_url('donhang/delete/') ?>' + id;
  });
});
</script>