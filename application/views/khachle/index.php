<?php $this->load->helper('money'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <br />
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <form class="form-inline mb-2" method="get">
          <div class="input-group" style="width:420px; max-width:100%;">
            <input type="text" name="keyword" class="form-control"
                   value="<?= html_escape($keyword ?? '') ?>"
                   placeholder="Tìm tên, mã đơn, điện thoại, địa chỉ...">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary ml-2">
                <i class="fas fa-search"></i> Tìm kiếm
              </button>
              <?php if (!empty($keyword)): ?>
                <a href="<?= site_url('khachle'); ?>" class="btn btn-secondary ml-2">
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
                <th class="text-center" style="width:150px;">Ngày lập</th>
                <th class="text-center">Tên khách</th>
                <th class="text-center" >Điện thoại</th>
                <th class="text-center" style="width:350px;">Địa chỉ</th>
                <th>Tổng tiền</th>
                <th>Ghi chú</th>
                <th class="text-center" style="width:170px;">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($list)):
                $i = 1 + ($offset ?? 0);
                foreach ($list as $row):
              ?>
              <tr>
                <td class="text-center"><?= $i++ ?></td>
                <td><?= htmlspecialchars($row->madon_id) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row->ngaylap)) ?></td>
                <td><?= htmlspecialchars($row->ten) ?></td>
                <td class="text-center"><?= htmlspecialchars($row->dienthoai) ?></td>
                <td><?= htmlspecialchars($row->diachi) ?></td>
                <td class="text-right"><?= number_format($row->tongcong_tien) ?></td>
                <td><?= htmlspecialchars($row->ghi_chu) ?></td>
                <td class="text-center">
                  
                  <a href="<?= site_url('khachle/detail/'.$row->id) ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> </a>
                  <a href="<?= site_url('khachle/edit/'.$row->id); ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> </a>
                  <a href="<?= site_url('khachle/pos/'.$row->id); ?>" class="btn btn-warning btn-sm" target="_blank"><i class="fas fa-print"></i> </a>
                  <?php if (isset($user_role) && $user_role === 'admin'): ?>
                  <button type="button" class="btn btn-danger btn-sm btn-delete-khachle" data-id="<?= $row->id ?>" data-toggle="modal" data-target="#delKhachLeModal"><i class="fas fa-trash-alt"></i> </button>
                  <?php endif; ?>
                </td>
              </tr> 
              <?php endforeach; else: ?>
                <tr><td colspan="9" class="text-center text-muted">Chưa có đơn khách lẻ nào.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer clearfix">
         <?= isset($pagination) ? $pagination : '' ?>
      </div>
    </div>
  </section>
</div>
<!-- Modal xoá đơn khách lẻ -->
<div class="modal fade" id="delKhachLeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-trash-alt mr-2"></i>Xoá đơn khách lẻ</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Bạn có chắc chắn muốn xoá đơn khách lẻ này?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <button type="button" id="btnConfirmDeleteKhachLe" class="btn btn-danger">Xoá đơn</button>
      </div>
    </div>
  </div>
</div>
<script>
$(function() {
  var deleteId = 0;
  $('.btn-delete-khachle').click(function() {
    deleteId = $(this).data('id');
  });
  $('#btnConfirmDeleteKhachLe').click(function() {
    if (deleteId) {
      window.location.href = '<?= site_url('khachle/delete/') ?>' + deleteId;
    }
  });
});
</script>
