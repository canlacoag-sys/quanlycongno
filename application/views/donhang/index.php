<!-- filepath: application/views/donhang/index.php -->
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
        <br>
      <!-- Tìm kiếm + Nút thêm -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <form class="form-inline" method="get">
    <div class="input-group" style="width:420px; max-width:100%;">
      <input type="text" name="keyword" class="form-control"
             value="<?= html_escape($keyword ?? '') ?>"
             placeholder="Tìm khách hàng, mã đơn, ngày lập...">
      <div class="input-group-append">
        <button type="submit" class="btn btn-primary ml-2">
          <i class="fas fa-search"></i> Tìm kiếm
        </button>
        <?php if (!empty($keyword)): ?>
          <a href="<?= site_url('donhang'); ?>" class="btn btn-secondary ml-2">
            Xóa tìm
          </a>
        <?php endif; ?>
      </div>
    </div>
  </form>
  <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddDonHang">
    <i class="fas fa-plus"></i> Thêm đơn hàng
  </button>
</div>

      <div class="card">
        <div class="card-body p-0">
          <table class="table table-bordered table-hover mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Khách hàng</th>
                <th>Ngày lập</th>
                <th>Tổng tiền</th>
                <th>Đã trả</th>
                <th>Còn nợ</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($list)): foreach ($list as $i => $dh): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($dh->ten_khachhang ?? '') ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($dh->ngaylap)) ?></td>
                  <td class="text-right"><?= number_format($dh->tongtien) ?></td>
                  <td class="text-right"><?= number_format($dh->datra) ?></td>
                  <td class="text-right"><?= number_format($dh->conno) ?></td>
                  <td>
                    <!-- Thêm các nút xem/sửa/xóa nếu cần -->
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center">Chưa có đơn hàng nào.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Thêm đơn hàng -->
<div class="modal fade" id="modalAddDonHang" tabindex="-1" role="dialog" aria-labelledby="modalAddDonHangLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <form method="post" action="<?= site_url('donhang/add'); ?>" id="formDonHang">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAddDonHangLabel"><i class="fas fa-plus"></i> Thêm đơn hàng</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <script>
            var dsSanPham = <?= json_encode($sanpham); ?>;
          </script>
          <div class="form-group">
            <label>Khách hàng</label>
            <select name="khachhang_id" class="form-control" required>
              <option value="">-- Chọn khách hàng --</option>
              <?php foreach($khachhang as $kh): ?>
                <option value="<?= $kh->id ?>"><?= htmlspecialchars($kh->ten) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Tổng tiền</label>
              <input type="number" name="tongtien" id="tongtien" class="form-control" required readonly>
            </div>
            <div class="form-group col-md-4">
              <label>Đã trả</label>
              <input type="number" name="datra" id="datra" class="form-control" required value="0">
            </div>
            <div class="form-group col-md-4">
              <label>Còn nợ</label>
              <input type="number" name="conno" id="conno" class="form-control" required readonly>
            </div>
          </div>
          <hr>
          <h5>Chi tiết sản phẩm</h5>
          <div id="chitietDonHang">
            <div class="form-row mb-2 chitiet-row">
              <div class="col-md-4">
                <select name="ma_sp[]" class="form-control ma_sp_combo" required>
                  <option value="">-- Chọn sản phẩm --</option>
                  <?php foreach($sanpham as $sp): ?>
                    <option value="<?= htmlspecialchars($sp->ma_sp) ?>"><?= htmlspecialchars($sp->ten_sp) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2">
                <input type="number" name="so_luong[]" class="form-control so_luong" placeholder="Số lượng" min="1" value="1" required>
              </div>
              <div class="col-md-2">
                <input type="number" name="don_gia[]" class="form-control don_gia" placeholder="Đơn giá" min="0" required readonly>
              </div>
              <div class="col-md-2">
                <input type="number" name="thanh_tien[]" class="form-control thanh_tien" placeholder="Thành tiền" min="0" required readonly>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-success btnAddRow">+</button>
                <button type="button" class="btn btn-danger btnRemoveRow">-</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu đơn hàng</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Nhúng file JS riêng -->
<script src="<?= base_url('assets/dist/js/donhang.js') ?>"></script>