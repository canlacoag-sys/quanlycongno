<?php
// Helper nhỏ để active menu theo $active từ controller
if (!function_exists('is_active')) {
  function is_active($name, $active) { return ($active ?? '') === $name ? ' active' : ''; }
}
?>

<nav class="main-header navbar navbar-expand-md navbar-light navbar-white border-bottom">
  <div class="container<?= !empty($fluid) ? '-fluid' : '' ?>">
	<!-- Brand -->
	<a href="<?= site_url('dashboard'); ?>" class="navbar-brand d-flex align-items-center">
	  <img src="<?= base_url('assets/dist/img/AdminLTELogo.png'); ?>"
		   alt="Logo"
		   class="brand-image img-circle elevation-3 mr-2"
		   style="height:32px;width:32px;">
	  <span class="brand-text font-weight-bold">Công Nợ Admin</span>
	</a>

	<!-- Toggle for mobile -->
	<button class="navbar-toggler order-3" type="button"
			data-toggle="collapse" data-target="#navbarMain"
			aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
	</button>

	<!-- Left navbar (nếu cần thêm) -->
	<div class="collapse navbar-collapse order-1" id="navbarMain">
	  <ul class="navbar-nav mr-auto">
		<!-- Ví dụ: ô tìm kiếm -->
		<!--
		<li class="nav-item">
		  <form class="form-inline ml-0 ml-md-3" action="<?= site_url('search') ?>" method="get">
			<div class="input-group input-group-sm">
			  <input class="form-control form-control-navbar" name="q" type="search" placeholder="Tìm kiếm" aria-label="Search">
			  <div class="input-group-append">
				<button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
			  </div>
			</div>
		  </form>
		</li>
		-->
	  </ul>

	  <!-- Right navbar -->
	  <ul class="navbar-nav ml-auto">

		<li class="nav-item">
		  <a href="<?= site_url('dashboard'); ?>" class="nav-link<?= is_active('dashboard', $active ?? '') ?>">
			<i class="fas fa-home mr-1"></i>Trang chủ
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('khachhang'); ?>" class="nav-link<?= is_active('khachhang', $active ?? '') ?>">
			<i class="fas fa-users mr-1"></i>Khách hàng
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('sanpham'); ?>" class="nav-link<?= is_active('sanpham', $active ?? '') ?>">
			<i class="fas fa-box mr-1"></i>Sản phẩm
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('donhang'); ?>" class="nav-link<?= is_active('donhang', $active ?? '') ?>">
			<i class="fas fa-receipt mr-1"></i>Đơn hàng
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('congno'); ?>" class="nav-link<?= is_active('congno', $active ?? '') ?>">
			<i class="fas fa-money-bill-wave mr-1"></i>Công nợ
		  </a>
		</li>

		<!-- Divider nhỏ -->
		<li class="nav-item d-none d-md-block mx-2">
		  <span style="display:block;height:30px;width:1px;background:#eee;margin-top:10px"></span>
		</li>

		<!-- (Tùy chọn) fullscreen & control-sidebar -->
		<!--
		<li class="nav-item">
		  <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Toàn màn hình">
			<i class="fas fa-expand-arrows-alt"></i>
		  </a>
		</li>
		<li class="nav-item">
		  <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" title="Bảng điều khiển">
			<i class="fas fa-th-large"></i>
		  </a>
		</li>
		-->

		<!-- User dropdown (nếu có đăng nhập) -->
		<!--
		<li class="nav-item dropdown">
		  <a class="nav-link" data-toggle="dropdown" href="#">
			<i class="far fa-user-circle"></i> <?= html_escape($username ?? 'Tài khoản') ?>
		  </a>
		  <div class="dropdown-menu dropdown-menu-right">
			<a href="<?= site_url('profile') ?>" class="dropdown-item"><i class="fas fa-id-badge mr-2"></i> Hồ sơ</a>
			<div class="dropdown-divider"></div>
			<a href="<?= site_url('auth/logout'); ?>" class="dropdown-item text-danger">
			  <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
			</a>
		  </div>
		</li>
		-->

		<!-- Nút đăng xuất đơn giản -->
		<li class="nav-item">
		  <a href="<?= site_url('auth/logout'); ?>" class="nav-link text-danger font-weight-bold">
			<i class="fas fa-sign-out-alt mr-1"></i>Đăng xuất
		  </a>
		</li>
	  </ul>
	</div>
  </div>
</nav>
