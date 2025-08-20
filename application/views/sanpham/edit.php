<div class="content-wrapper">
  <section class="content">
	<div class="container-fluid">
	  <div class="row justify-content-center">
		<div class="col-md-6 col-sm-12">
		  <div class="card card-primary shadow">
			<div class="card-header">
			  <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Sửa sản phẩm</h3>
			</div>
			<form method="post" autocomplete="off">
			  <div class="card-body">

				<div class="form-group">
				  <label for="ma_sp">Mã sản phẩm</label>
				  <div class="input-group">
					<div class="input-group-prepend">
					  <span class="input-group-text"><i class="fas fa-barcode"></i></span>
					</div>
					<input type="text" id="ma_sp" name="ma_sp" class="form-control" required value="<?= $sp->ma_sp ?>">
				  </div>
				</div>

				<div class="form-group">
				  <label for="ten_sp">Tên sản phẩm</label>
				  <div class="input-group">
					<div class="input-group-prepend">
					  <span class="input-group-text"><i class="fas fa-box"></i></span>
					</div>
					<input type="text" id="ten_sp" name="ten_sp" class="form-control" required value="<?= $sp->ten_sp ?>">
				  </div>
				</div>

				<div class="form-group">
				  <label for="gia">Giá bán (VNĐ)</label>
				  <div class="input-group">
					<div class="input-group-prepend">
					  <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
					</div>
					<input type="number" id="gia" name="gia" class="form-control" required min="0" value="<?= $sp->gia ?>">
				  </div>
				</div>

			  </div>
			  <div class="card-footer bg-white">
				<button type="submit" class="btn btn-primary">
				  <i class="fas fa-save mr-1"></i>Cập nhật
				</button>
				<a href="<?= site_url('sanpham'); ?>" class="btn btn-secondary ml-2">
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
