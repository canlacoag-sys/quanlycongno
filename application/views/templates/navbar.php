<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item sm-inline-block">
      <span class="nav-link font-weight-bold">
        <?php
          switch ($active ?? '') {
            case 'dashboard': echo 'TỔNG QUAN'; break;
            case 'khachhang': echo 'DANH SÁCH KHÁCH HÀNG'; break;
            case 'sanpham':   echo 'MENU BÁNH TRUNG THU'; break;
            case 'donhang':   echo 'DANH SÁCH BIÊN NHẬN'; break;
            case 'donhang/addcochietkhau':   echo 'BÁNH CÓ CHIẾT KHẤU'; break;
            case 'donhang/addkochietkhau':   echo 'BÁNH KHÔNG CHIẾT KHẤU'; break;
            case 'congno':    echo 'DANH SÁCH CÔNG NỢ'; break;
            default:          echo 'BẢNG TRANG CHỦ';
          }
        ?>
      </span>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Search -->
    <!-- li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
      </a>
      <div class="navbar-search-block">
        <form class="form-inline" action="<?= site_url('search') ?>" method="get">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Tìm kiếm" name="q">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search"><i class="fas fa-times"></i></button>
            </div>
          </div>
        </form>
      </div>
    </li -->

    <!-- Notifications -->
    <!-- li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">15</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">15 thông báo</span>
        <div class="dropdown-divider"></div>
        <a href="<?= site_url('notifications') ?>" class="dropdown-item dropdown-footer">Xem tất cả</a>
      </div>
    </li -->

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- Control Sidebar -->
    <!-- li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fas fa-th-large"></i>
      </a>
    </li -->
  </ul>
</nav>
