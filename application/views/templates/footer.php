  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
	<div class="p-3">Nội dung Control Sidebar</div>
  </aside>
</div> <!-- ./wrapper -->


<!-- Modal xác nhận xoá -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title text-danger">
		  <i class="fas fa-exclamation-triangle mr-2"></i>Xác nhận xoá
		</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="modal-body">
		Bạn có chắc muốn xoá khách hàng này?
		<div class="mt-2 p-2 bg-light rounded">
		  <div><strong>Tên:</strong> <span id="delName"></span></div>
		  <div><strong>Điện thoại:</strong> <span id="delPhone"></span></div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Không xoá</button>
		<a href="#" id="btnConfirmDelete" class="btn btn-danger">Xoá</a>
	  </div>
	</div>
  </div>
</div>

<!-- Modal sửa khách hàng -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
	<div class="modal-content">
	  <form id="editCustomerForm" method="post" action="">
		<div class="modal-header">
		  <h5 class="modal-title"><i class="fas fa-user-edit mr-2"></i>Sửa khách hàng</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>

		<div class="modal-body">
		  <!-- Nếu bạn bật CSRF của CodeIgniter, thêm hidden token ở đây -->

		  <div class="form-group">
			<label for="editName">Tên</label>
			<input type="text" class="form-control" id="editName" name="ten" required>
		  </div>

		 <div class="form-group">
		   <label>Điện thoại (nhiều số)</label>
		   <div id="phoneTags" class="form-control d-flex flex-wrap align-items-center" style="min-height:38px;gap:.35rem;">
			 <input id="phoneInput" type="text" class="border-0 flex-grow-1" style="outline:none;min-width:140px;"
					placeholder="Nhập 10 số và nhấn Enter">
		   </div>
		   <input type="hidden" name="dienthoai" id="phonesHidden" value="">
		   <small class="form-text text-muted">Gõ đủ 10 số sẽ tự tách thành 1 số; có thể dán nhiều số, cách nhau bằng dấu phẩy.</small>
		   <small id="dupHelp" class="text-danger d-none">Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.</small>
		 </div>
		  <div class="form-group">
			<label for="editAddress">Địa chỉ</label>
			<input type="text" class="form-control" id="editAddress" name="diachi">
		  </div>
		</div>

		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
		  <button type="submit" id="btnSaveCustomer" class="btn btn-primary">Lưu</button>
		  <input type="hidden" id="editId" value="">
		</div>
	  </form>
	</div>
  </div>
</div>

<!-- Modal: Thêm khách hàng -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
	<form id="addCustomerForm" class="modal-content" action="javascript:void(0)">
	  <div class="modal-header">
		<h5 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Thêm khách hàng</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>

	  <div class="modal-body">
		<div class="form-group">
		  <label>Tên</label>
		  <input type="text" class="form-control" id="addName" placeholder="Tên khách hàng">
		</div>

		<div class="form-group">
		  <label>Điện thoại (nhiều số)</label>
		  <!-- Khung tags + input -->
		  <div id="phoneTagsAdd" class="form-control d-flex flex-wrap align-items-center" style="min-height:38px;gap:.35rem;">
			<input id="phoneInputAdd" type="text" class="border-0 flex-grow-1" style="outline:none;min-width:140px;"
				   placeholder="Nhập 10 số và nhấn Enter">
		  </div>
		  <!-- Hidden CSV gửi lên server -->
		  <input type="hidden" name="dienthoai" id="phonesHiddenAdd" value="">
		  <small class="form-text text-muted">
			Gõ đủ 10 số sẽ tự tách thành 1 số; có thể dán nhiều số, cách nhau bằng dấu phẩy.
		  </small>
		  <small id="dupHelpAdd" class="text-danger d-none">
			Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.
		  </small>
		</div>

		<div class="form-group">
		  <label>Địa chỉ</label>
		  <input type="text" class="form-control" id="addAddress" placeholder="Địa chỉ">
		</div>
	  </div>

	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
		<button type="submit" id="btnSaveCustomerAdd" class="btn btn-primary">Lưu</button>
	  </div>
	</form>
  </div>
</div>


<!-- jQuery -->
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script> $.widget.bridge('uibutton', $.ui.button); </script>

<!-- Bootstrap 4: nhớ dùng bundle để có Popper -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

<script src="<?= base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<script src="<?= base_url('assets/dist/js/adminlte.min.js'); ?>"></script>

<script>
  window.APP = window.APP || { routes:{} };
  window.APP.routes.check_phone = "<?= site_url('khachhang/check_phone') ?>";
  window.APP.routes.ajax_add    = "<?= site_url('khachhang/ajax_add') ?>";
</script>
<script src="<?= base_url('assets/dist/js/khachhang.js') ?>"></script>

<script>
  window.APP = window.APP || { routes:{} };
  window.APP.routes.ajax_add_product    = "<?= site_url('sanpham/ajax_add') ?>";
  window.APP.routes.ajax_edit_product   = "<?= site_url('sanpham/ajax_edit') ?>";
  window.APP.routes.ajax_delete_product = "<?= site_url('sanpham/ajax_delete') ?>";
  window.APP.routes.check_ma_sp = "<?= site_url('sanpham/check_ma_sp') ?>";
</script>
<script src="<?= base_url('assets/dist/js/sanpham.js') ?>"></script>


</body>
</html>
