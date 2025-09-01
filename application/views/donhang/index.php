<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-list"></i> Danh sách đơn hàng</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Đơn hàng</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <form class="form-inline mb-2" method="get">
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
        <a class="btn btn-primary" href="<?= site_url('donhang/add'); ?>">
          <i class="fas fa-plus"></i> Thêm đơn hàng
        </a>
      </div>
      <div class="card">
        <div class="card-body p-0">
          <table class="table table-bordered table-hover mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Khách hàng</th>
                <th>Ngày lập</th>
                <th>Tổng tiền</th>
                <th>Đã trả</th>
                <th>Còn nợ</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($list)): foreach ($list as $i => $dh): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($dh->ten_khachhang ?? '') ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($dh->ngaylap)) ?></td>
                  <td class="text-right"><?= number_format($dh->tongtien) ?></td>
                  <td class="text-right"><?= number_format($dh->datra) ?></td>
                  <td class="text-right"><?= number_format($dh->conno) ?></td>
                  <td>
                    <a href="<?= site_url('donhang/edit/'.$dh->id); ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Sửa</a>
                    <button type="button" class="btn btn-danger btn-sm btn-delete-donhang" data-id="<?= $dh->id ?>" data-toggle="modal" data-target="#delDonHangModal"><i class="fas fa-trash-alt"></i> Xoá</button>
                    <a href="<?= site_url('donhang/pos/'.$dh->id); ?>" class="btn btn-warning btn-sm" target="_blank"><i class="fas fa-print"></i> In POS</a>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center">Chưa có đơn hàng nào.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
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
    $('#btnConfirmDeleteDonHang').data('id', id);
  });
  $('#btnConfirmDeleteDonHang').click(function() {
    var id = $(this).data('id');
    window.location.href = '<?= site_url('donhang/delete/') ?>' + id;
  });
});
</script>