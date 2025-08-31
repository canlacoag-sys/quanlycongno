<!-- Modal: Xác nhận xoá sản phẩm -->
<div class="modal fade" id="confirmDeleteProductModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
          <i class="fas fa-exclamation-triangle mr-2"></i>Xác nhận xoá bánh
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá bánh này?
        <div class="mt-2 p-2 bg-secondary rounded">
          <div><strong>Mã bánh:</strong> <span id="delMaSP"></span></div>
          <div><strong>Tên bánh:</strong> <span id="delTenSP"></span></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <a href="#" id="btnConfirmDeleteProduct" class="btn btn-danger">Xoá bánh</a>
      </div>
    </div>
  </div>
</div>