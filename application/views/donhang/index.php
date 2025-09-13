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
          <table class="table table-bordered table-hover mb-0">
            <thead>
              <tr>
                <!-- <th style="width:44px;">Click</th> -->
                <th>Mã đơn</th>
                <th>Ngày tạo</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Loại bánh</th>
                <th class="text-center" style="width:235px;">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($list)):
                $i = 0;
                foreach ($list as $dh):
                  $rowId = (int)$dh->id;
                  $collapseId = "donhang-row-{$rowId}";
                  $isEven = $i % 2 === 0;
                  $rowClass = $isEven ? '' : 'table-active';
              ?>
              <!-- Hàng chính -->
              <tr class="<?= $rowClass ?>">
                <!-- td class="align-middle">
                  <button class="btn btn-sm btn-light border toggle-row" type="button"
                          data-toggle="collapse" data-target="#<?= $collapseId ?>"
                          aria-expanded="false" aria-controls="<?= $collapseId ?>">
                    <i class="fas fa-chevron-down"></i>
                  </button>
                </td -->
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
                <td class="text-center"> 
                  <a href="<?= site_url('donhang/edit/'.$dh->id); ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Sửa</a>
                  <?php if ($user_role === 'admin'): ?>
                  <button type="button" class="btn btn-danger btn-sm btn-delete-donhang" data-id="<?= $dh->id ?>" data-toggle="modal" data-target="#delDonHangModal"><i class="fas fa-trash-alt"></i> Xoá</button>
                  <?php endif; ?>
                  <a href="<?= site_url('donhang/pos/'.$dh->id); ?>" class="btn btn-warning btn-sm" target="_blank"><i class="fas fa-print"></i> In POS</a>
                </td>
              </tr>
              <!-- Hàng chi tiết (collapse, nếu muốn hiển thị thêm thông tin đơn hàng) -->
              <!-- <tr class="<?= $rowClass ?> collapse-detail">
                <td colspan="7" class="p-0">
                  <div id="<?= $collapseId ?>" class="collapse" data-parent="">
                    <div class="p-3">
                      <div class="row">
                        <div class="col-md-4">
                          <h6 class="mb-2"><i class="fas fa-info-circle mr-1"></i> Thông tin đơn hàng</h6>
                          <ul class="list-unstyled mb-0 font-weight-bold">
                            <li><strong>Mã đơn:</strong> <?= htmlspecialchars($dh->madon_id) ?></li>
                            <li><strong>Khách hàng:</strong> <?= htmlspecialchars($dh->ten_khachhang ?? '') ?></li>
                            <li><strong>Ngày tạo:</strong> <?= date('d/m/Y H:i', strtotime($dh->ngaylap)) ?></li>
                          </ul>
                        </div>
                        <div class="col-md-4">
                          <h6 class="mb-2"><i class="fas fa-sticky-note mr-1"></i> Ghi chú</h6>
                          <div><?= htmlspecialchars($dh->ghi_chu ?? '—') ?></div>
                        </div>
                        <div class="col-md-4">
                          <h6 class="mb-2"><i class="fas fa-box mr-1"></i> Loại bánh</h6>
                          <div><?= htmlspecialchars($dh->co_chiet_khau ? 'Có chiết khấu' : 'Không chiết khấu') ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr> -->
              <?php $i++; endforeach; else: ?>
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