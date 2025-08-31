<!-- Modal: Xoá khách hàng -->
<div class="modal fade" id="confirmDeleteCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-trash-alt mr-2"></i>Xoá khách hàng</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Bạn có chắc chắn muốn xoá khách hàng sau?</p>
        <ul class="mt-2 p-2 bg-secondary rounded">
          <li><strong>Tên:</strong> <span id="delCustomerName"></span></li>
          <li><strong>Điện thoại:</strong> <span id="delCustomerPhone"></span></li>
          <li><strong>Địa chỉ:</strong> <span id="delCustomerAddress"></span></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <a href="#" id="btnConfirmDeleteCustomer" class="btn btn-danger">Xoá khách hàng</a>
      </div>
    </div>
  </div>
</div>
