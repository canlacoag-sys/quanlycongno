<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= site_url('dashboard') ?>" class="brand-link">
	<img src="<?= base_url('assets/dist/img/icon.png'); ?>"
		 alt="Logo"
		 class="brand-image elevation-3"
		 style="opacity:.8">
	<span class="brand-text font-weight-light">Quản Lý Công Nợ</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
	<!-- Sidebar Menu -->
	<nav class="mt-2">
	  <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
		<li class="nav-item">
		  <a href="<?= site_url('dashboard') ?>" class="nav-link <?= ($active=='dashboard'?'active':'') ?>">
			<i class="nav-icon fas fa-tachometer-alt"></i>
			<p>Bảng điều khiển</p>
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('khachhang') ?>" class="nav-link <?= ($active=='khachhang'?'active':'') ?>">
			<i class="nav-icon fas fa-users"></i>
			<p>Khách hàng</p>
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('sanpham') ?>" class="nav-link <?= ($active=='sanpham'?'active':'') ?>">
			<i class="nav-icon fas fa-box"></i>
			<p>Sản phẩm</p>
		  </a>
		</li>
		<li class="nav-item <?= ($active ?? '') == 'donhang' ? 'menu-open' : '' ?>">
		  <a href="#" class="nav-link <?= ($active ?? '') == 'donhang' ? 'active' : '' ?>">
			<i class="nav-icon fas fa-shopping-cart"></i>
			<p>
			  Đơn hàng
			  <i class="right fas fa-angle-left"></i>
			</p>
		  </a>
		  <ul class="nav nav-treeview">
			<li class="nav-item">
			  <a href="<?= site_url('donhang'); ?>" class="nav-link <?= ($title ?? '') == 'CHI TIẾT ĐƠN HÀNG' ? 'active' : '' ?>">
				<i class="far fa-circle nav-icon"></i>
				<p>Danh sách đơn hàng</p>
			  </a>
			</li>
			<li class="nav-item">
			  <a href="<?= site_url('donhang/add'); ?>" class="nav-link <?= ($title ?? '') == 'Thêm đơn hàng' ? 'active' : '' ?>">
				<i class="far fa-circle nav-icon"></i>
				<p>Thêm đơn hàng</p>
			  </a>
			</li>
		  </ul>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('congno') ?>" class="nav-link <?= ($active=='congno'?'active':'') ?>">
			<i class="nav-icon fas fa-money-bill-wave"></i>
			<p>Công nợ</p>
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('auth/logout') ?>" class="nav-link">
			<i class="nav-icon fas fa-sign-out-alt"></i>
			<p>Đăng xuất</p>
		  </a>
		</li>
	  </ul>
	</nav>
  </div>
</aside>
