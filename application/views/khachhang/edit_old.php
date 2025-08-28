<div class="content-wrapper">
  <section class="content">
	<div class="container">
	  <div class="row justify-content-center">
		<div class="col-md-6 col-sm-12">
		  <div class="card card-primary shadow">
			<div class="card-header">
			  <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Sửa khách hàng</h3>
			</div>
			<form method="post" id="formKhachHang" autocomplete="off">
			  <?php $this->load->view('khachhang/_form'); ?>
			  <div class="form-group mt-3">
				<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Lưu khách hàng</button>
				<a href="<?= site_url('khachhang'); ?>" class="btn btn-secondary ml-2"><i class="fas fa-chevron-left"></i> Quay lại</a>
			  </div>
			</form>

		  </div>
		</div>
	  </div>
	</div>
  </section>
</div>
