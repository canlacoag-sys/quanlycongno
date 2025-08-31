<!-- Modal: Sửa khách hàng -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="editCustomerForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-user-edit mr-2"></i>Sửa khách hàng</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Hidden ID -->
        <input type="hidden" id="editId" name="id" value="">

        <div class="form-group">
          <label for="editName">Tên khách hàng</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" id="editName" name="ten" class="form-control" required>
          </div>
        </div>

        <div class="form-group">
        <label for="phoneInput">Điện thoại (nhiều số)</label>
        <div id="phoneTags" class="input-group">
            <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input id="phoneInput" type="text" class="form-control" style="outline:none;min-width:140px;" placeholder="Nhập 10 số và nhấn Enter">
        </div>
        <input type="hidden" id="phonesHidden" name="dienthoai" value="">
        <small class="form-text text-muted">
            Gõ đủ 10 số sẽ tự tách thành 1 số; có thể dán nhiều số, cách nhau bằng dấu phẩy.
        </small>
        <small id="dupHelpEdit" class="text-danger d-none">
            Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.
        </small>
        </div>

        <div class="form-group">
          <label for="editAddress">Địa chỉ</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            </div>
            <input type="text" id="editAddress" name="diachi" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <button type="submit" id="btnSaveCustomerEdit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Cập nhật khách hàng</button>
      </div>
    </form>
  </div>
</div>