
<?php $this->load->view('sanpham/add'); ?>
<?php $this->load->view('sanpham/edit'); ?>
<?php $this->load->view('sanpham/del'); ?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <br />
      <!-- Tìm kiếm + Nút thêm -->
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <form method="get" action="<?= site_url('sanpham'); ?>" class="mb-0" id="formSearchProduct">
          <div class="input-group" style="width:680px; max-width:100%;">
            <input type="text" name="keyword" value="<?= isset($keyword) ? html_escape($keyword) : '' ?>" class="form-control" placeholder="Tìm mã hoặc tên bánh">
            <div class="form-check form-check-inline ml-2">
              <input class="form-check-input" type="radio" name="chietkhau" id="ckAll" value="" <?= !isset($_GET['chietkhau']) || $_GET['chietkhau'] === '' ? 'checked' : '' ?>>
              <label class="form-check-label" for="ckAll">Tất cả</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="chietkhau" id="ckCo" value="1" <?= isset($_GET['chietkhau']) && $_GET['chietkhau']=='1' ? 'checked' : '' ?>>
              <label class="form-check-label" for="ckCo">Có chiết khấu</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="chietkhau" id="ckKhong" value="0" <?= isset($_GET['chietkhau']) && $_GET['chietkhau']=='0' ? 'checked' : '' ?>>
              <label class="form-check-label" for="ckKhong">Không chiết khấu</label>
            </div>
            <button class="btn btn-primary ml-2" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
            <?php if (!empty($keyword)): ?>
              <a href="<?= site_url('sanpham'); ?>" class="btn btn-secondary ml-2">Xóa tìm</a>
            <?php endif; ?>
          </div>
        </form>
        <button type="button" class="btn btn-success" id="btnAddProduct" data-toggle="modal" data-target="#addProductModal">
          <i class="fas fa-plus"></i> Thêm bánh
        </button>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0 table-products">
              <thead>
                <tr>
                  <th class="text-center" style="width:120px;">Mã bánh</th>
                  <th>Tên sản phẩm</th>
                  <th class="text-center" style="width:120px;">Giá bán</th>
                  <th class="text-center" style="width:120px;">Loại bánh</th>
                  <th class="text-center" style="width:120px;">Chiết khấu</th>
                  <th class="text-center" style="width:120px;">Tác vụ</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($list as $sp): ?>
                <tr>
                  <td class="text-center col-ma-sp"><?= $sp->ma_sp ?></td>
                  <td class="col-ten-sp"><?= $sp->ten_sp ?></td>
                  <td class="text-right col-gia-sp" data-gia="<?= $sp->gia ?>"><?= number_format($sp->gia) ?></td>
                  <td class="text-center"><?= $sp->combo ? 'Combo' : 'Cái' ?></td>
                  <td class="text-center"><?= $sp->co_chiet_khau ? 'Có' : 'Không' ?></td>
                  <td class="text-center align-middle">
                    <a href="#" class="btn btn-info btn-sm btn-edit-product" data-id="<?= $sp->id ?>" data-toggle="modal" data-target="#editProductModal"><i class="fas fa-edit"></i></a>
                    <a href="#" class="btn btn-danger btn-sm btn-delete-product" data-id="<?= $sp->id ?>" data-toggle="modal" data-target="#confirmDeleteProductModal"><i class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="mt-3">
            <?= isset($pagination) ? $pagination : '' ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>