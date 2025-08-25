<!-- application/views/dashboard/index.php -->
<div class="content-wrapper">
  <!-- Header (breadcrumb + title) -->
  <!-- section class="content-header">
	<div class="container-fluid">
	  <div class="row mb-2">
		<div class="col-sm-6"><h1>Chào mừng bạn, lao động là vinh quang nha !</h1></div>
		<div class="col-sm-6">
		  <ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
			<li class="breadcrumb-item active">Dashboard</li>
		  </ol>
		</div>
	  </div>
	</div>
  </section -->

  <!-- Main content -->
  <section class="content">
	<div class="container-fluid">

	  <div class="row">
		<div class="col-lg-3 col-6">
		  <div class="small-box bg-info">
			<div class="inner">
			  <h3>Khách hàng</h3>
			  <p>Quản lý khách hàng</p>
			</div>
			<div class="icon"><i class="fas fa-users"></i></div>
			<a href="<?= site_url('khachhang'); ?>" class="small-box-footer">
			  Xem <i class="fas fa-arrow-circle-right"></i>
			</a>
		  </div>
		</div>

		<div class="col-lg-3 col-6">
		  <div class="small-box bg-success">
			<div class="inner">
			  <h3>Sản phẩm</h3>
			  <p>Quản lý sản phẩm</p>
			</div>
			<div class="icon"><i class="fas fa-box"></i></div>
			<a href="<?= site_url('sanpham'); ?>" class="small-box-footer">
			  Xem <i class="fas fa-arrow-circle-right"></i>
			</a>
		  </div>
		</div>

		<div class="col-lg-3 col-6">
		  <div class="small-box bg-warning">
			<div class="inner">
			  <h3>Đơn hàng</h3>
			  <p>Quản lý đơn hàng</p>
			</div>
			<div class="icon"><i class="fas fa-receipt"></i></div>
			<a href="<?= site_url('donhang'); ?>" class="small-box-footer">
			  Xem <i class="fas fa-arrow-circle-right"></i>
			</a>
		  </div>
		</div>

		<div class="col-lg-3 col-6">
		  <div class="small-box bg-danger">
			<div class="inner">
			  <h3>Công nợ</h3>
			  <p>Quản lý công nợ</p>
			</div>
			<div class="icon"><i class="fas fa-money-bill-wave"></i></div>
			<a href="<?= site_url('congno'); ?>" class="small-box-footer">
			  Xem <i class="fas fa-arrow-circle-right"></i>
			</a>
		  </div>
		</div>
	  </div>

	</div>
  </section>
</div>
