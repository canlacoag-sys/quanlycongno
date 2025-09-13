<?php $this->load->view('khachhang/add'); ?>
<?php $this->load->view('khachhang/edit'); ?>
<?php $this->load->view('khachhang/del'); ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <br />
      <!-- Tìm kiếm + Nút thêm -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="form-inline" method="get">
          <div class="input-group" style="width:420px; max-width:100%;">
            <input type="text" name="keyword" class="form-control"
                   value="<?= html_escape($keyword) ?>"
                   placeholder="Tìm tên, điện thoại, địa chỉ">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary ml-2">
                <i class="fas fa-search"></i> Tìm kiếm
              </button>
              <?php if (!empty($keyword)): ?>
                <a href="<?= site_url('khachhang'); ?>" class="btn btn-secondary ml-2">
                  Xóa tìm
                </a>
              <?php endif; ?>
            </div>
          </div>
        </form>
        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addCustomerModal">
          <i class="fas fa-plus"></i> Thêm khách hàng
        </a>
      </div>

      <div class="card">
        <div class="card-body p-0">
          <table class="table table-bordered table-striped table-hover mb-0">
            <thead>
              <tr>
                <th style="width:44px;">STT</th>
                <th>Tên</th>
                <th>Điện thoại</th>
                <th>Địa chỉ</th>
                <th class="text-center" style="width:250px;">Tác vụ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($list)): 
               $i = 1 + ($offset ?? 0);
                foreach ($list as $row): 
              ?>
              <tr>
                <td class="text-center"><?= $i++ ?></td>
                <td class="align-middle"><?= html_escape($row->ten) ?></td>
                <?php $this->load->helper('phone'); ?>
                <td class="align-middle"><?= html_escape(phones_pretty($row->dienthoai)) ?></td>
                <td class="align-middle"><?= html_escape($row->diachi) ?></td>
                <td class="text-center align-middle">
                  <a href="#" class="btn btn-sm btn-info btn-edit-customer" data-toggle="modal" data-target="#editCustomerModal" data-id="<?= $row->id ?>"><i class="fas fa-edit"></i> Sửa</a>
                  <?php if (isset($user_role) && $user_role === 'admin'): ?>
                  <a href="#" class="btn btn-sm btn-danger btn-delete-customer" data-toggle="modal" data-target="#confirmDeleteCustomerModal" data-id="<?= $row->id ?>"><i class="fas fa-trash-alt"></i> Xóa</a>
                  <?php endif; ?>
                  <a href="<?= site_url('congno/detail/'.$row->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xem công nợ khách hàng này?');">Xem Công Nợ</a>
                </td>
              </tr>
              
              <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center text-muted">Chưa có khách hàng</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer clearfix">
         <?= isset($pagination) ? $pagination : '' ?>
        </div>
      </div>
    </div>
  </section>
</div>