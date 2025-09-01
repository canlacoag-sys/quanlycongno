  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
	<div class="p-3">Nội dung Control Sidebar</div>
  </aside>

<footer class="main-footer">
  <strong>Copyright &copy; 2025 <a href="https://angiangvn.com">AnGiangVN.com</a>.</strong>
  All rights reserved.
  <div class="float-right d-sm-inline-block">
    <b>Version</b> 3.2.0
  </div>
</footer>
</div> <!-- ./wrapper -->


<!-- jQuery -->
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script> $.widget.bridge('uibutton', $.ui.button); </script>

<!-- Bootstrap 4 Bundle (gồm Popper) -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- OverlayScrollbars -->
<script src="<?= base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>

<!-- AdminLTE -->
<script src="<?= base_url('assets/dist/js/adminlte.min.js'); ?>"></script>

<!-- Bootstrap Switch -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css'); ?>">
<script src="<?= base_url('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>"></script>

<!-- Các script riêng -->
<script>
  window.APP = window.APP || {};
  window.APP.routes = window.APP.routes || {};
  window.APP.routes.khachhang_get = "<?= site_url('khachhang/get') ?>";
  window.APP.routes.check_phone = "<?= site_url('khachhang/check_phone') ?>";
  window.APP.routes.khachhang_ajax_add = "<?= site_url('khachhang/ajax_add') ?>";
  window.APP.routes.khachhang_ajax_edit = "<?= site_url('khachhang/ajax_edit') ?>";
  window.APP.routes.khachhang_ajax_delete = "<?= site_url('khachhang/ajax_delete') ?>";
  window.APP.routes.sanpham_get = "<?= site_url('sanpham/get') ?>";
  window.APP.routes.ajax_add_product    = "<?= site_url('sanpham/ajax_add') ?>";
  window.APP.routes.ajax_edit_product   = "<?= site_url('sanpham/ajax_edit') ?>";
  window.APP.routes.ajax_delete_product = "<?= site_url('sanpham/ajax_delete') ?>";
  window.APP.routes.check_ma_sp = "<?= site_url('sanpham/check_ma_sp') ?>";
</script>
<script src="<?= base_url('assets/dist/js/khachhang.js') ?>"></script>
<script src="<?= base_url('assets/dist/js/sanpham.js') ?>"></script>

</body>
</html>
