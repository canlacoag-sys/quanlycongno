<!-- Modal: Thêm khách hàng -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="addCustomerForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Thêm khách hàng</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Hidden ID (dùng cho đồng bộ hàm fill/reset nếu cần) -->
        <input type="hidden" id="addId" name="id" value="">

        <div class="form-group">
          <label for="addName">Tên khách hàng</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" id="addName" name="ten" class="form-control" required placeholder="Nhập tên khách hàng">
          </div>
        </div>

        <div class="form-group">
          <label for="phoneInputAdd">Điện thoại (nhiều số)</label>
          <div id="phoneTagsAdd" class="input-group" style="flex-wrap:wrap;">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input id="phoneInputAdd" type="text" class="form-control" style="outline:none;min-width:140px;" placeholder="Nhập 10 số và nhấn Enter">
          </div>
          <input type="hidden" name="dienthoai" id="phonesHiddenAdd" value="">
          <small id="dupHelpAdd" class="text-danger d-none">
            Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.
          </small>
        </div>

        
        <div class="form-group">
          <label for="addAddress">Địa chỉ</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            </div>
            <input type="text" id="addAddress" name="diachi" class="form-control" placeholder="Nhập địa chỉ">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <button type="submit" id="btnSaveCustomerAdd" class="btn btn-primary">
          <i class="fas fa-save mr-1"></i>Lưu khách hàng
        </button>
      </div>
    </form>
  </div>
</div>

