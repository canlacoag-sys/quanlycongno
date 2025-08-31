<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <br>
      <!-- Tìm kiếm + Nút thêm -->
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <form class="form-inline mb-2" method="get">
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
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalDonHang">
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

<!-- Modal Thêm/Sửa đơn hàng -->
<div class="modal fade" id="modalDonHang" tabindex="-1" role="dialog" aria-labelledby="modalDonHangLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <form method="post" action="<?= site_url('donhang/add'); ?>" id="formDonHang">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalDonHangLabel">
            <i class="fas fa-plus"></i> Thêm đơn hàng
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pb-0">
          <script>
            var dsSanPham = <?= json_encode($sanpham); ?>;
          </script>
          <!-- Khách hàng -->
          <div class="form-group row align-items-center mb-4">
            <label class="col-sm-2 col-form-label font-weight-bold">Khách hàng</label>
            <div class="col-sm-6">
              <select name="khachhang_id" class="form-control" required>
                <option value="">-- Chọn khách hàng --</option>
                <?php foreach($khachhang as $kh): ?>
                  <option value="<?= $kh->id ?>"><?= htmlspecialchars($kh->ten) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-4">
              <button type="button" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Thêm khách hàng mới
              </button>
            </div>
          </div>
          <hr>
          <!-- Chi tiết sản phẩm -->
          <div class="mb-2 font-weight-bold">Chi tiết sản phẩm</div>
          <div class="table-responsive">
            <table class="table table-bordered mb-0" id="tableChiTietSP">
              <thead>
                <tr>
                  <th style="width:160px;">Mã SP</th>
                  <th>Tên sản phẩm</th>
                  <th style="width:120px;">Đơn giá</th>
                  <th style="width:110px;">Số lượng</th>
                  <th style="width:120px;">Thành tiền</th>
                  <th style="width:60px;"></th>
                </tr>
              </thead>
              <tbody id="tbodyChiTietSP">
                <tr>
                  <td>
                    <input type="text" class="form-control ma_sp_input" name="ma_sp[]" placeholder="MĐ: 5,7,24" autocomplete="off">
                  </td>
                  <td class="ten_sp_cell"></td>
                  <td class="text-right don_gia_cell">0</td>
                  <td>
                    <input type="number" class="form-control so_luong_input" name="so_luong[]" min="1" value="1" style="width:80px;">
                  </td>
                  <td class="text-right text-danger font-weight-bold thanh_tien_cell">0</td>
                  <td class="text-center">
                    <button type="button" class="btn btn-danger btnRemoveRow"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <button type="button" class="btn btn-primary mt-2" id="btnAddRow"><i class="fas fa-plus"></i> Thêm dòng</button>
          <hr>
          <!-- Tổng tiền, trả trước, còn nợ -->
          <div class="row align-items-center">
            <div class="col-md-8"></div>
            <div class="col-md-4">
              <table class="table table-borderless mb-0">
                <tr>
                  <td class="font-weight-bold text-right">Tổng tiền:</td>
                  <td class="text-right text-primary font-weight-bold" style="width:100px;" id="tongTienView">0</td>
                </tr>
                <tr>
                  <td class="font-weight-bold text-right">Trả trước:</td>
                  <td>
                    <input type="number" class="form-control" min="0" value="0" id="traTruocInput" name="datra">
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-bold text-right">Còn nợ:</td>
                  <td class="text-right text-danger font-weight-bold" id="conNoView">0</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save"></i> Lưu đơn hàng</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/dist/js/donhang.js') ?>"></script>