<!-- Modal: Sửa sản phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="editProductForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Sửa bánh</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="addMaSP">Mã bánh</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-barcode"></i></span>
            </div>
            <input type="text" id="editMaSP" name="ma_sp" class="form-control" disabled placeholder="Ví dụ: SP01">
          </div>
        </div>
        <div class="form-group">
          <label for="addTenSP">Tên bánh</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-box"></i></span>
            </div>
            <input type="text" id="editTenSP" name="ten_sp" class="form-control" required placeholder="Tên bánh">
          </div>
        </div>
        <div class="form-group">
          <label for="addGiaSP">Giá bán (VNĐ)</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
            </div>
            <input type="text" id="editGiaSP" name="gia" class="form-control" required min="0" placeholder="Nhập giá bán">
          </div>
        </div>
        <div class="form-group">
          <input type="checkbox" id="coChietKhau" name="co_chiet_khau" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success" data-off-text="OFF" data-on-text="ON">
          <label for="coChietKhau" class="font-weight-bold ml-2">Bánh có chiết khấu</label>
        </div>
        <input type="hidden" id="editIdSP" name="id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <button type="submit" id="btnSaveProductEdit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Cập nhật</button>
      </div>
    </form>
  </div>
</div>