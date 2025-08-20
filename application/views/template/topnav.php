<nav class="main-header navbar navbar-expand-md navbar-light navbar-white border-bottom">
  <div class="container">
	<!-- Logo/Brand -->
	<a href="<?= base_url(); ?>" class="navbar-brand d-flex align-items-center">
	  <img src="<?= base_url('assets/dist/img/AdminLTELogo.png'); ?>" alt="Logo" class="brand-image img-circle elevation-3 mr-2" style="height:32px;width:32px;">
	  <span class="brand-text font-weight-bold">Công Nợ Admin</span>
	</a>

	<!-- Toggle for mobile -->
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMain"
			aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
	</button>

	<!-- Menu -->
	<div class="collapse navbar-collapse" id="navbarMain">
	  <ul class="navbar-nav ml-auto">

		<li class="nav-item">
		  <a href="<?= site_url('dashboard'); ?>" class="nav-link">
			<i class="fas fa-home mr-1"></i>Trang chủ
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('khachhang'); ?>" class="nav-link">
			<i class="fas fa-users mr-1"></i>Khách hàng
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('sanpham'); ?>" class="nav-link">
			<i class="fas fa-box mr-1"></i>Sản phẩm
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('donhang'); ?>" class="nav-link">
			<i class="fas fa-receipt mr-1"></i>Đơn hàng
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('congno'); ?>" class="nav-link">
			<i class="fas fa-money-bill-wave mr-1"></i>Công nợ
		  </a>
		</li>
		<li class="nav-item d-none d-md-block mx-2">
		  <div style="height:30px;width:1px;background:#eee;margin-top:10px"></div>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('auth/logout'); ?>" class="nav-link text-danger font-weight-bold">
			<i class="fas fa-sign-out-alt mr-1"></i>Đăng xuất
		  </a>
		</li>
	  </ul>
	</div>
  </div>
</nav>
