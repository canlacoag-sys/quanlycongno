<?php $this->load->view('khachhang/add'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-edit"></i> Sửa đơn hàng</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= site_url('donhang') ?>">Đơn hàng</a></li>
            <li class="breadcrumb-item active">Sửa đơn hàng</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" action="<?= site_url('donhang/edit/'.$donhang->id); ?>" id="formDonHang">
            <!-- Khách hàng -->
            <div class="form-group row align-items-center mb-4">
              <label class="col-sm-2 col-form-label font-weight-bold">Khách hàng</label>
              <div class="col-sm-6">
                <input type="hidden" name="khachhang_id" id="khachhang_id" value="<?= $donhang->khachhang_id ?>">
                <input type="text" class="form-control" id="khachhang_autocomplete" value="<?= htmlspecialchars($donhang->ten_khachhang ?? '') ?>" placeholder="Nhập tên hoặc số điện thoại khách hàng..." autocomplete="off" required>
              </div>
              <div class="col-sm-4">
                <button type="button" class="btn btn-success" id="btnAddKhachHang">
                  <i class="fas fa-user-plus"></i> Thêm khách hàng mới
                </button>
              </div>
            </div>
            <hr>
            <!-- Chọn loại bánh -->
            <div class="form-group row align-items-center mb-2">
              <label class="col-sm-2 col-form-label font-weight-bold">Loại bánh</label>
              <div class="col-sm-10">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="chietkhau" id="ckAllSP" value="" checked>
                  <label class="form-check-label" for="ckAllSP">Tất cả</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="chietkhau" id="ckCoSP" value="1">
                  <label class="form-check-label" for="ckCoSP">Có chiết khấu</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="chietkhau" id="ckKhongSP" value="0">
                  <label class="form-check-label" for="ckKhongSP">Không chiết khấu</label>
                </div>
              </div>
            </div>
            <hr>
            <!-- Chi tiết sản phẩm -->
            <div class="mb-2 font-weight-bold">Chi tiết sản phẩm</div>
            <div class="table-responsive">
              <table class="table table-bordered mb-0" id="tableChiTietSP">
                <thead>
                  <tr>
                    <th style="width:180px;">Mã bánh (nhiều, cách nhau dấu phẩy)</th>
                    <th>Loại hộp</th>
                    <th style="width:120px;">Đơn giá</th>
                    <th style="width:110px;">Số lượng</th>
                    <th style="width:120px;">Thành tiền</th>
                    <th style="width:60px;"></th>
                  </tr>
                </thead>
                <tbody id="tbodyChiTietSP">
                  <?php foreach($chitiet as $ct): ?>
                  <tr>
                    <td>
                      <input type="text" class="form-control ma_sp_input" name="ma_sp[]" value="<?= htmlspecialchars($ct->ma_sp) ?>" autocomplete="off">
                    </td>
                    <td class="loai_hop_cell"></td>
                    <td class="text-right don_gia_cell"><?= number_format($ct->don_gia) ?></td>
                    <td>
                      <input type="number" class="form-control so_luong_input" name="so_luong[]" min="1" value="<?= $ct->so_luong ?>" style="width:80px;">
                    </td>
                    <td class="text-right text-danger font-weight-bold thanh_tien_cell"><?= number_format($ct->thanh_tien) ?></td>
                    <td class="text-center">
                      <button type="button" class="btn btn-danger btnRemoveRow"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <button type="button" class="btn btn-primary mt-2" id="btnAddRow"><i class="fas fa-plus"></i> Thêm dòng</button>
            <hr>
            <!-- Tổng tiền, trả trước, còn nợ, ngày giờ -->
            <div class="row align-items-center">
              <div class="col-md-8"></div>
              <div class="col-md-4">
                <table class="table table-borderless mb-0">
                  <tr>
                    <td class="font-weight-bold text-right">Tổng tiền:</td>
                    <td class="text-right text-primary font-weight-bold" style="width:100px;" id="tongTienView"><?= number_format($donhang->tongtien) ?></td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Trả trước:</td>
                    <td>
                      <input type="number" class="form-control" min="0" value="<?= $donhang->datra ?>" id="traTruocInput" name="datra">
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Còn nợ:</td>
                    <td class="text-right text-danger font-weight-bold" id="conNoView"><?= number_format($donhang->conno) ?></td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Ngày giờ:</td>
                    <td>
                      <input type="text" class="form-control" id="ngaylapInput" name="ngaylap" value="<?= $donhang->ngaylap ?>" autocomplete="off">
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
              <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save"></i> Lưu đơn hàng</button>
              <a href="<?= site_url('donhang'); ?>" class="btn btn-secondary">Quay lại</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- Thêm lại JS xử lý autocomplete, sản phẩm, tổng tiền như add.php -->
