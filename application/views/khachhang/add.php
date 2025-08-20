<div class="content-wrapper">
  <section class="content">
	<div class="container">
	  <div class="row justify-content-center">
		<div class="col-md-6 col-sm-12">
		  <div class="card card-primary shadow">
			<div class="card-header">
			  <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Thêm khách hàng mới</h3>
			</div>
			<form method="post" autocomplete="off">
			  <div class="card-body">

				<div class="form-group">
				  <label for="ten">Tên khách hàng</label>
				  <div class="input-group">
					<div class="input-group-prepend">
					  <span class="input-group-text"><i class="fas fa-user"></i></span>
					</div>
					<input type="text" id="ten" name="ten" class="form-control" required placeholder="Nhập tên khách hàng">
				  </div>
				</div>

				<div class="form-group">
				  <label for="dienthoai">Điện thoại</label>
				  <div class="input-group">
					<div class="input-group-prepend">
					  <span class="input-group-text"><i class="fas fa-phone"></i></span>
					</div>
					<input type="text" id="dienthoai" name="dienthoai" class="form-control" placeholder="Nhập số điện thoại">
				  </div>
				</div>

				<div class="form-group">
				  <label for="diachi">Địa chỉ</label>
				  <div class="input-group">
					<div class="input-group-prepend">
					  <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
					</div>
					<input type="text" id="diachi" name="diachi" class="form-control" placeholder="Nhập địa chỉ">
				  </div>
				</div>

			  </div>
			  <div class="card-footer bg-white">
				<button type="submit" class="btn btn-primary">
				  <i class="fas fa-save mr-1"></i>Lưu khách hàng
				</button>
				<a href="<?= site_url('khachhang'); ?>" class="btn btn-secondary ml-2">
				  <i class="fas fa-chevron-left mr-1"></i>Quay lại
				</a>
			  </div>
			</form>
		  </div>
		</div>
	  </div>
	</div>
  </section>
</div>
