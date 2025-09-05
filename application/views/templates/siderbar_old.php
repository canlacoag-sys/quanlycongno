<?php
// Đảm bảo helper is_active luôn tồn tại
if (!function_exists('is_active')) {
  function is_active($name, $active) { return ($active ?? '') === $name ? ' active' : ''; }
}
// Lấy thông tin user hiện tại từ session (nếu có)
$CI =& get_instance();
$CI->load->library('session');
$user_id = $CI->session->userdata('user_id');
$user_role = null;
if ($user_id) {
  $CI->load->database();
  $user = $CI->db->get_where('users', ['id' => $user_id])->row();
  $user_role = $user ? $user->role : null;
}

// Xác định active cho menu đa cấp đơn hàng (chỉ active khi controller là donhang)
$donhang_active = ($active ?? '') === 'donhang' || ($active ?? '') === 'donhang/addcochietkhau' || ($active ?? '') === 'donhang/addkochietkhau';
// Thêm active cho khách lẻ
$khachle_active = ($active ?? '') === 'khachle' || ($active ?? '') === 'khachle/add' ;
?>
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
		  <a href="<?= site_url('dashboard'); ?>" class="nav-link<?= is_active('dashboard', $active ?? '') ?>">
			<i class="nav-icon fas fa-tachometer-alt"></i>
			<p>Tổng quan</p>
		  </a>
		</li>
		
		<li class="nav-item">
		  <a href="<?= site_url('khachhang') ?>" class="nav-link<?= is_active('khachhang', $active ?? '') ?>">
			<i class="nav-icon fas fa-users"></i>
			<p>Khách hàng</p>
		  </a>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('sanpham') ?>" class="nav-link<?= is_active('sanpham', $active ?? '') ?>">
			<i class="nav-icon fas fa-box"></i>
			<p>Sản phẩm</p>
		  </a>
		</li>
		<!-- Thêm menu khách lẻ -->
		<li class="nav-item<?= $khachle_active ? ' menu-open' : '' ?>">
		  <a href="javascript:void(0);" class="nav-link<?= $khachle_active ? ' active' : '' ?> toggle-khachle-menu">
			<i class="nav-icon fas fa-user-tag"></i>
			<p>
			  Bán sỉ (khách sỉ)
			  <i class="right fas fa-angle-<?= $khachle_active ? 'down' : 'left' ?>" id="khachleMenuArrow"></i>
			</p>
		  </a>
		  <ul class="nav nav-treeview" id="khachleMenuTree"<?= $khachle_active ? ' style="display:block;"' : ' style="display:none;"' ?>>
			<li class="nav-item">
			  <a href="<?= site_url('khachle'); ?>" class="nav-link<?= ($active ?? '') === 'khachle' ? ' active' : '' ?>">
				<i class="far fa-circle nav-icon"></i>
				<p>Đơn hàng khách lẻ</p>
			  </a>
			</li>
			<li class="nav-item">
			  <a href="<?= site_url('khachle/add'); ?>" class="nav-link<?= ($active ?? '') === 'khachle/add' ? ' active' : '' ?>">
				<i class="far fa-circle nav-icon"></i>
				<p>Toa khách lẻ</p>
			  </a>
			</li>
		  </ul>
		</li>



		<li class="nav-item<?= $donhang_active ? ' menu-open' : '' ?>">
		  <a href="javascript:void(0);" class="nav-link<?= $donhang_active ? ' active' : '' ?> toggle-donhang-menu">
			<i class="nav-icon fas fa-shopping-cart"></i>
			<p>
			  Bán sỉ (khách sỉ)
			  <i class="right fas fa-angle-<?= $donhang_active ? 'down' : 'left' ?>" id="donhangMenuArrow"></i>
			</p>
		  </a>
		  <ul class="nav nav-treeview" id="donhangMenuTree"<?= $donhang_active ? ' style="display:block;"' : ' style="display:none;"' ?>>
			<li class="nav-item">
			  <a href="<?= site_url('donhang'); ?>" class="nav-link<?= ($active ?? '') === 'donhang' ? ' active' : '' ?>">
				<i class="far fa-circle nav-icon"></i>
				<p>Danh sách đơn hàng</p>
			  </a>
			</li>
			<li class="nav-item">
			  <a href="<?= site_url('donhang/addcochietkhau'); ?>" class="nav-link<?= ($active ?? '') === 'donhang/addcochietkhau' ? ' active' : '' ?>">
				<i class="far fa-circle nav-icon"></i>
				<p>Bánh có chiết khấu</p>
			  </a>
			</li>
			<li class="nav-item">
			  <a href="<?= site_url('donhang/addkochietkhau'); ?>" class="nav-link<?= ($active ?? '') === 'donhang/addkochietkhau' ? ' active' : '' ?>">
				<i class="far fa-circle nav-icon"></i>
				<p>Bánh không chiết khấu</p>
			  </a>
			</li>
		  </ul>
		</li>
		<li class="nav-item">
		  <a href="<?= site_url('congno') ?>" class="nav-link<?= is_active('congno', $active ?? '') ?>">
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
		<?php if ($user_role === 'admin'): ?>
		<li class="nav-item">
		  <a href="<?= site_url('users'); ?>" class="nav-link<?= is_active('users', $active ?? '') ?>">
			<i class="nav-icon fas fa-users-cog"></i>
			<p>Quản lý tài khoản</p>
		  </a>
		</li>
		<?php endif; ?>
	  </ul>
    </nav>
  </div>
</aside>
<script>
$(function() {
  // Toggle menu Đơn hàng khi click vào dòng menu
  $('.toggle-donhang-menu').on('click', function(e) {
    e.preventDefault();
    var $tree = $('#donhangMenuTree');
    var $arrow = $('#donhangMenuArrow');
    if ($tree.is(':visible')) {
      $tree.slideUp(150);
      $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
      $(this).parent().removeClass('menu-open');
      $(this).removeClass('active');
    } else {
      $tree.slideDown(150);
      $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
      $(this).parent().addClass('menu-open');
      $(this).addClass('active');
    }
  });
});

$(function() {
  // Toggle menu Đơn hàng khi click vào dòng menu
  $('.toggle-khachle-menu').on('click', function(e) {
    e.preventDefault();
    var $tree = $('#khachleMenuTree');
    var $arrow = $('#khachleMenuArrow');
    if ($tree.is(':visible')) {
      $tree.slideUp(150);
      $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
      $(this).parent().removeClass('menu-open');
      $(this).removeClass('active');
    } else {
      $tree.slideDown(150);
      $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
      $(this).parent().addClass('menu-open');
      $(this).addClass('active');
    }
  });
});
</script>