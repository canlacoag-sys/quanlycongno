<div class="content-wrapper">
  <section class="content">
	<div class="container-fluid">

	  <div class="card">
		<div class="card-header d-flex justify-content-between align-items-center flex-wrap">
		<h3 class="card-title mb-0">Danh sách sản phẩm</h3>
		<div class="ml-auto">
			<a href="<?= site_url('sanpham/add'); ?>" class="btn btn-success">
			<i class="fas fa-plus mr-1"></i> Thêm sản phẩm
			</a>
		</div>
		</div>

		<div class="card-body">

		  <!-- Ô tìm kiếm -->
		  <form method="get" action="<?= site_url('sanpham'); ?>" class="form-inline mb-3">
			<div class="input-group mr-2">
			  <input type="text" name="keyword" value="<?= isset($keyword) ? html_escape($keyword) : '' ?>" class="form-control" style="min-width:300px;width:350px;" placeholder="Tìm mã hoặc tên sản phẩm">
			  <div class="input-group-append">
				<button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
			  </div>
			</div>
			<?php if (!empty($keyword)): ?>
			  <a href="<?= site_url('sanpham'); ?>" class="btn btn-secondary ml-2">Xóa tìm</a>
			<?php endif; ?>
		  </form>
		  <!-- Hết ô tìm kiếm -->

		  <div class="table-responsive">
			<table class="table table-bordered table-striped">
			  <thead>
				<tr>
				  <th class="text-center" style="width:120px;">Mã SP</th>
				  <th>Tên sản phẩm</th>
				  <th class="text-right" style="width:120px;">Giá bán</th>
				  <th style="width:110px">Tác vụ</th>
				</tr>
			  </thead>
			  <tbody>
				<?php foreach($list as $sp): ?>
				<tr>
				  <td class="text-center"><?= $sp->ma_sp ?></td>
				  <td><?= $sp->ten_sp ?></td>
				  <td class="text-right"><?= number_format($sp->gia) ?></td>
				  <td>
					<a href="<?= site_url('sanpham/edit/'.$sp->id); ?>" class="btn btn-sm btn-info" title="Sửa"><i class="fas fa-edit"></i></a>
					<a href="<?= site_url('sanpham/delete/'.$sp->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa sản phẩm này?')" title="Xoá">
					  <i class="fas fa-trash-alt"></i>
					</a>
				  </td>
				</tr>
				<?php endforeach; ?>
			  </tbody>
			</table>
		  </div>

		  <div class="mt-3">
			<?= isset($pagination) ? $pagination : '' ?>
		  </div>

		</div>
	  </div>
	</div>
  </section>
</div>
