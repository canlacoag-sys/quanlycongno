<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <br />
      <!-- Tìm kiếm + Nút thêm -->
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <form method="get" action="<?= site_url('sanpham'); ?>" class="mb-0">
          <div class="input-group" style="width:420px; max-width:100%;">
            <input type="text" name="keyword" value="<?= isset($keyword) ? html_escape($keyword) : '' ?>" class="form-control" placeholder="Tìm mã hoặc tên sản phẩm">
            <button class="btn btn-primary ml-2" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
            <?php if (!empty($keyword)): ?>
              <a href="<?= site_url('sanpham'); ?>" class="btn btn-secondary ml-2">Xóa tìm</a>
            <?php endif; ?>
          </div>
        </form>
        <button type="button" class="btn btn-success" id="btnAddProduct" data-toggle="modal" data-target="#addProductModal">
          <i class="fas fa-plus"></i> Thêm sản phẩm
        </button>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0 table-products">
  <thead>
    <tr>
      <th class="text-center" style="width:120px;">Mã SP</th>
      <th>Tên sản phẩm</th>
      <th class="text-right" style="width:120px;">Giá bán</th>
      <th>Chiết khấu</th>
      <th class="text-center" style="width:120px;">Tác vụ</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($list as $sp): ?>
    <tr>
      <td class="text-center col-ma-sp"><?= $sp->ma_sp ?></td>
      <td class="col-ten-sp"><?= $sp->ten_sp ?></td>
      <td class="text-right col-gia-sp" data-gia="<?= $sp->gia ?>"><?= number_format($sp->gia) ?></td>
      <td><?= $sp->co_chiet_khau ? 'Có' : 'Không' ?></td>
      <td class="text-center align-middle">
        <a href="#" class="btn btn-info btn-sm btn-edit-product" data-id="<?= $sp->id ?>"><i class="fas fa-edit"></i></a>
        <a href="#" class="btn btn-danger btn-sm btn-delete-product" data-id="<?= $sp->id ?>"><i class="fas fa-trash-alt"></i></a>
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

<!-- Modal: Xác nhận xoá sản phẩm -->
<div class="modal fade" id="confirmDeleteProductModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="fas fa-exclamation-triangle mr-2"></i>Xác nhận xoá sản phẩm
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá sản phẩm này?
        <div class="mt-2 p-2 bg-light rounded">
          <div><strong>Mã SP:</strong> <span id="delMaSP"></span></div>
          <div><strong>Tên SP:</strong> <span id="delTenSP"></span></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Không xoá</button>
        <a href="#" id="btnConfirmDeleteProduct" class="btn btn-danger">Xoá</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Thêm sản phẩm -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="addProductForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus mr-2"></i>Thêm sản phẩm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="form-group">
		<label>Mã sản phẩm</label>
		<input type="text" class="form-control" id="addMaSP" name="ma_sp" required>
		<small id="dupMaSPHelpAdd" class="text-danger d-none">
			Mã sản phẩm đã tồn tại. Hãy thay đổi trước khi lưu.
		</small>
		</div>
        <div class="form-group">
          <label>Tên sản phẩm</label>
          <input type="text" class="form-control" id="addTenSP" name="ten_sp" required>
        </div>
        <div class="form-group">
          <label>Giá bán</label>
          <input type="number" class="form-control" id="addGiaSP" name="gia" min="0" required>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" id="addCoChietKhau" name="co_chiet_khau" value="1">
<label for="addCoChietKhau">Sản phẩm có chiết khấu</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="submit" id="btnSaveProductAdd" class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Sửa sản phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="editProductForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Sửa sản phẩm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Mã sản phẩm</label>
          <input type="text" class="form-control" id="editMaSP" name="ma_sp" required>
        </div>
        <div class="form-group">
          <label>Tên sản phẩm</label>
          <input type="text" class="form-control" id="editTenSP" name="ten_sp" required>
        </div>
        <div class="form-group">
          <label>Giá bán</label>
          <input type="number" class="form-control" id="editGiaSP" name="gia" min="0" required>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="coChietKhau" name="co_chiet_khau" value="1">
          <label class="form-check-label" for="coChietKhau">Sản phẩm có chiết khấu</label>
        </div>
        <input type="hidden" id="editIdSP" name="id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="submit" id="btnSaveProductEdit" class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>