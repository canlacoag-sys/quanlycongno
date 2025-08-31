<!-- Modal: Thêm khách hàng -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="addCustomerForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Thêm khách hàng</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="ten">Tên khách hàng</label>
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" id="addName" name="ten" class="form-control" required placeholder="Nhập tên khách hàng">
            </div>
        </div>

        <div class="form-group">
            <label for="dienthoai">Điện thoại</label>
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input type="text" id="phoneInputAdd" name="dienthoai" class="form-control" placeholder="Nhập số điện thoại">
            </div>
            <input type="hidden" name="dienthoai" id="phonesHiddenAdd" value="">
            <small class="form-text text-muted">
                Gõ đủ 10 số sẽ tự tách thành 1 số; có thể dán nhiều số, cách nhau bằng dấu phẩy.
            </small>
            <small id="dupHelpAdd" class="text-danger d-none">
                Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.
            </small>
        </div>
    
        <div class="form-group">
            <label for="diachi">Địa chỉ</label>
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
        <button type="submit" id="btnSaveCustomerAdd" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Lưu khách hàng</button>
      </div>
    </form>
  </div>
</div>

        <div class="form-group">
            <label for="dienthoai">Điện thoại</label>
            <div id="phoneTagsAdd" class="input-group form-control d-flex flex-wrap align-items-center" style="min-height:52px;gap:.35rem;">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input id="phoneInputAdd" type="text" class="border-0 flex-grow-1" style="outline:none;min-width:140px;"
                placeholder="Nhập 10 số và nhấn Enter">
            </div>
            <input type="hidden" name="dienthoai" id="phonesHiddenAdd" value="">
            <small class="form-text text-muted">
                Gõ đủ 10 số sẽ tự tách thành 1 số; có thể dán nhiều số, cách nhau bằng dấu phẩy.
            </small>
            <small id="dupHelpAdd" class="text-danger d-none">
                Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.
            </small>
        </div>
