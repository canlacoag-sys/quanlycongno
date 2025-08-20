<div class="content-wrapper">
  <section class="content">
	<div class="container-fluid">
	  <div class="card">
		<div class="card-header d-flex justify-content-between align-items-center flex-wrap">
		<h3 class="card-title mb-0">Danh sách khách hàng</h3>
		<div class="ml-auto">
			<a href="<?= site_url('khachhang/add'); ?>" class="btn btn-success">
			<i class="fas fa-plus mr-1"></i> Thêm khách hàng
			</a>
		</div>
		</div>
		
		<div class="card-body">
		<!-- Ô tìm kiếm -->
		<form method="get" action="<?= site_url('khachhang'); ?>" class="form-inline mb-3">
		  <div class="input-group mr-2">
			<input type="text" name="keyword" value="<?= isset($keyword) ? html_escape($keyword) : '' ?>" class="form-control" style="min-width:300px;width:350px;" placeholder="Tìm tên, điện thoại, địa chỉ">
			<div class="input-group-append">
			  <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
			</div>
		  </div>
		  <?php if (!empty($keyword)): ?>
			<a href="<?= site_url('khachhang'); ?>" class="btn btn-secondary ml-2">Xóa tìm</a>
		  <?php endif; ?>
		</form>
		<!-- Hết ô tìm kiếm -->

		  <table class="table table-bordered table-striped">
			<thead>
			  <tr>
				<th>Tên</th>
				<th>Điện thoại</th>
				<th>Địa chỉ</th>
				<th style="width:110px">Tác vụ</th>
			  </tr>
			</thead>
			<tbody>
			  <?php foreach($list as $kh): ?>
			  <tr>
				<td><?= $kh->ten ?></td>
				<td><?= $kh->dienthoai ?></td>
				<td><?= $kh->diachi ?></td>
				<td>
				  <a href="<?= site_url('khachhang/edit/'.$kh->id); ?>" class="btn btn-sm btn-info" title="Sửa"><i class="fas fa-edit"></i></a>
				  <a href="<?= site_url('khachhang/delete/'.$kh->id); ?>" class="btn btn-sm btn-danger"
					 onclick="return confirm('Xác nhận xóa khách hàng này?')" title="Xoá">
					<i class="fas fa-trash-alt"></i>
				  </a>
				</td>
			  </tr>
			  <?php endforeach; ?>
			</tbody>
		  </table>
		  <div class="mt-3">
			<?= isset($pagination) ? $pagination : '' ?>
		  </div>
		</div>
	  </div>
	</div>
  </section>
</div>
