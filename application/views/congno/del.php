<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-body text-center">
          <h3 class="text-danger mb-3">Xác nhận xóa công nợ</h3>
          <p>Bạn có chắc chắn muốn xóa công nợ này không?</p>
          <form method="post">
            <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
            <button type="submit" class="btn btn-danger">Xóa</button>
            <a href="<?= site_url('congno') ?>" class="btn btn-secondary ml-2">Hủy</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
