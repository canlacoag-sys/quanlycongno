<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url(); ?>" class="brand-link">
	<span class="brand-text font-weight-light">Công Nợ Admin OK</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
	<!-- Sidebar Menu -->
	<nav class="mt-2">
	  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
		<li class="nav-item">
		  <a href="<?= site_url('khachhang'); ?>" class="nav-link">
			<i class="nav-icon fas fa-users"></i>
			<p>Khách hàng</p>
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('sanpham'); ?>" class="nav-link">
			<i class="nav-icon fas fa-box"></i>
			<p>Sản phẩm</p>
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('donhang'); ?>" class="nav-link">
			<i class="nav-icon fas fa-receipt"></i>
			<p>Đơn hàng</p>
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('congno'); ?>" class="nav-link">
			<i class="nav-icon fas fa-money-bill-wave"></i>
			<p>Công nợ</p>
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('auth/logout'); ?>" class="nav-link">
			<i class="nav-icon fas fa-sign-out-alt"></i>
			<p>Đăng xuất</p>
		  </a>
		</li>
	  </ul>
	</nav>
	<!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
