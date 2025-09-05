<?php
// ...copy toàn bộ nội dung từ donhang/edit.php, đổi tiêu đề, đường dẫn form, các biến liên quan thành khachle...
?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <br />
      <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" action="<?= site_url('khachle/edit/'.$row->id); ?>">
            <div class="form-group">
              <label>Mã đơn</label>
              <input type="text" name="madon_id" class="form-control" value="<?= htmlspecialchars($row->madon_id) ?>" required>
            </div>
            <div class="form-group">
              <label>Tên khách</label>
              <input type="text" name="ten" class="form-control" value="<?= htmlspecialchars($row->ten) ?>" required>
            </div>
            <div class="form-group">
              <label>Điện thoại</label>
              <input type="text" name="dienthoai" class="form-control" value="<?= htmlspecialchars($row->dienthoai) ?>">
            </div>
            <div class="form-group">
              <label>Địa chỉ</label>
              <input type="text" name="diachi" class="form-control" value="<?= htmlspecialchars($row->diachi) ?>">
            </div>
            <div class="form-group">
              <label>Ngày lập</label>
              <input type="datetime-local" name="ngaylap" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($row->ngaylap)) ?>">
            </div>
            <div class="form-group">
              <label>Tổng tiền</label>
              <input type="number" name="tongtien" class="form-control" value="<?= $row->tongtien ?>" required>
            </div>
            <div class="form-group">
              <label>Ghi chú</label>
              <textarea name="ghi_chu" class="form-control"><?= htmlspecialchars($row->ghi_chu) ?></textarea>
            </div>
            <div class="d-flex justify-content-end">
              <a href="<?= site_url('khachle'); ?>" class="btn btn-secondary mr-2">Quay lại</a>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
