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
                <th style="width:44px;">Click</th>
                <th>Tên</th>
                <th>Điện thoại</th>
                <th>Địa chỉ</th>
                <th class="text-center" style="width:155px;">Tác vụ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($list)): 
                $i = 0;
                foreach ($list as $row): 
                  $rowId = (int)$row->id;
                  $collapseId = "khachhang-row-{$rowId}";
                  $isEven = $i % 2 === 0;
                  $rowClass = $isEven ? '' : 'table-active';
              ?>
              <!-- Hàng chính -->
              <tr class="<?= $rowClass ?>">
                <td class="align-middle">
                  <button class="btn btn-sm btn-light border toggle-row" type="button"
                          data-toggle="collapse" data-target="#<?= $collapseId ?>"
                          aria-expanded="false" aria-controls="<?= $collapseId ?>">
                    <i class="fas fa-chevron-down"></i>
                  </button>
                </td>
                <td class="align-middle"><?= html_escape($row->ten) ?></td>
                <?php $this->load->helper('phone'); ?>
                <td class="align-middle"><?= html_escape(phones_pretty($row->dienthoai)) ?></td>
                <td class="align-middle"><?= html_escape($row->diachi) ?></td>
                <td class="text-center align-middle">
                  <a href="#" class="btn btn-sm btn-info btn-edit-customer" data-toggle="modal" data-target="#editCustomerModal" data-id="<?= $row->id ?>"><i class="fas fa-edit"></i> Sửa</a>
                  <a href="#" class="btn btn-sm btn-danger btn-delete-customer" data-toggle="modal" data-target="#confirmDeleteCustomerModal" data-id="<?= $row->id ?>"><i class="fas fa-trash-alt"></i> Xóa</a>
                </td>
              </tr>
              <!-- Hàng chi tiết (collapse) -->
              <tr class="<?= $rowClass ?> collapse-detail">
                <td colspan="5" class="p-0">
                  <div id="<?= $collapseId ?>" class="collapse" data-parent="">
                    <div class="p-3"> <!-- BỎ <?= $rowClass ?> ở đây -->
                      <div class="row">
                        <div class="col-md-4">
                          <h6 class="mb-2"><i class="fas fa-id-card mr-1"></i> Thông tin</h6>
                          <ul class="list-unstyled mb-0 font-weight-bold">
                            <li><strong>Tên:</strong> <?= html_escape($row->ten) ?></li>
                            <li><strong>Điện thoại:</strong> <?= html_escape(phones_pretty($row->dienthoai)) ?></li>
                            <li><strong>Địa chỉ:</strong> <?= html_escape($row->diachi) ?></li>
                          </ul>
                        </div>
                        <div class="col-md-4">
                          <h6 class="mb-2"><i class="fas fa-shopping-cart mr-1"></i> Đơn hàng gần đây</h6>
                          <div class="text-muted">Chưa có dữ liệu — tích hợp sau.</div>
                        </div>
                        <div class="col-md-4">
                          <h6 class="mb-2"><i class="fas fa-sticky-note mr-1"></i> Ghi chú</h6>
                          <div class="text-muted">—</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <?php $i++; endforeach; else: ?>
                <tr><td colspan="5" class="text-center text-muted">Chưa có khách hàng</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer clearfix">
          <?= $pagination ?? '' ?>
        </div>
      </div>
    </div>
  </section>
</div>