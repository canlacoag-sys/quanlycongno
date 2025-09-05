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
                <input type="text" class="form-control" id="khachhang_autocomplete"
                  value="<?php
                    // Sửa lỗi: kiểm tra $khachhang là object trước khi truy cập thuộc tính
                    if (isset($khachhang) && is_object($khachhang)) {
                      echo htmlspecialchars($khachhang->ten . ($khachhang->dienthoai ? ' (' . $khachhang->dienthoai . ')' : ''));
                    } elseif (isset($donhang->ten_khachhang)) {
                      echo htmlspecialchars($donhang->ten_khachhang);
                    } else {
                      echo '';
                    }
                  ?>"
                  placeholder="Nhập tên hoặc số điện thoại khách hàng..." autocomplete="off" required>
              </div>
              <div class="col-sm-4">
                <button type="button" class="btn btn-success" id="btnAddKhachHang">
                  <i class="fas fa-user-plus"></i> Thêm khách hàng mới
                </button>
              </div>
            </div>
            <hr>
            <!-- Chọn loại bánh -->
            <div class="form-group row align-items-center mb-2" style="display:none;">
              <label class="col-sm-2 col-form-label font-weight-bold">Loại bánh</label>
              <div class="col-sm-10">
                <input type="hidden" name="chietkhau" value="">
                <!-- Nếu cần giữ giá trị, có thể set value="1" hoặc "0" tùy đơn hàng -->
              </div>
            </div>
            <!-- end loại bánh -->
            <!-- Chi tiết sản phẩm -->
            <div class="mb-2 font-weight-bold">Chi tiết sản phẩm</div>
            <div class="table-responsive">
              <table class="table table-bordered mb-0" id="tableSanPham">
                <thead>
                  <tr>
                    <th style="width:280px;">Mã bánh</th>
                    <th style="max-width:300px;">Tên sản phẩm</th>
                    <th style="width:120px;" class="text-right">Đơn giá</th>
                    <th style="width:70px;">Số lượng</th>
                    <th style="width:150px;" class="text-right">Thành tiền</th>
                    <th style="width:40px;"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($chitiet as $ct): ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <input type="text" name="ma_sp[]" class="form-control ma_sp_combo" style="min-width:120px;width:250px;" autocomplete="off" placeholder="VD: 5,7,24" value="<?= htmlspecialchars($ct->ma_sp) ?>">
                        <span class="badge badge-info d-none tooltip-combo ml-2" style="cursor:pointer;min-width:30px;"></span>
                      </div>
                    </td>
                    <td>
                      <div class="ten_sp_combo small text-dark" style="max-width:260px;white-space:pre-line;overflow-x:auto;"></div>
                    </td>
                    <td>
                      <div class="font-weight-bold text-right don_gia_show" style="font-size:1.15em"></div>
                      <input type="hidden" name="don_gia[]" class="don_gia" readonly value="<?= (int)$ct->don_gia ?>">
                    </td>
                    <td>
                      <input type="number" name="so_luong[]" class="form-control text-center so_luong" min="1" value="<?= $ct->so_luong ?>" required style="width:70px;">
                    </td>
                    <td>
                      <div class="font-weight-bold text-danger text-right thanh_tien_show" style="font-size:1.25em"></div>
                      <input type="hidden" name="thanh_tien[]" class="thanh_tien" readonly value="<?= (int)$ct->thanh_tien ?>">
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm btnXoaDong"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <button type="button" id="btnThemDong" class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i> Thêm dòng</button>
            </div>
            <hr>
            <!-- Tổng tiền, ngày giờ -->
            <div class="row align-items-center">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label font-weight-bold" for="giaoHangInput">Giao hàng</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="giaoHangInput" name="giao_hang" value="<?= htmlspecialchars($donhang->giao_hang ?? '') ?>" placeholder="Thông tin giao hàng">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label font-weight-bold" for="nguoiNhanInput">Người nhận</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nguoiNhanInput" name="nguoi_nhan" value="<?= htmlspecialchars($donhang->nguoi_nhan ?? '') ?>" placeholder="Tên người nhận hàng">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label font-weight-bold" for="ghiChuInput">Ghi chú</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" id="ghiChuInput" name="ghi_chu" rows="2" placeholder="Ghi chú thêm (nếu có)"><?= htmlspecialchars($donhang->ghi_chu ?? '') ?></textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <table class="table table-borderless mb-0">
                  <tr>
                    <td class="font-weight-bold text-right tong-tien-label">Tổng tiền:</td>
                    <td class="text-right tong-tien-value" style="width:100px;" id="tongTienView"><?= number_format($donhang->tongtien) ?></td>
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
<script>
var dsSanPham = <?= json_encode($sanpham); ?>;
$(function() {
  // Autocomplete khách hàng
  $("#khachhang_autocomplete").autocomplete({
    minLength: 2,
    delay: 200,
    source: function(request, response) {
      $.ajax({
        url: "<?= site_url('khachhang/autocomplete'); ?>",
        dataType: "json",
        data: { term: request.term },
        success: function(data) {
          response($.map(data, function(item) {
            return {
              label: item.ten + (item.dienthoai ? ' (' + item.dienthoai + ')' : ''),
              value: item.ten + (item.dienthoai ? ' (' + item.dienthoai + ')' : ''),
              id: item.id
            };
          }));
        }
      });
    },
    select: function(event, ui) {
      $("#khachhang_id").val(ui.item.id);
      $(this).val(ui.item.label);
      return false;
    }
  });

  // Mở modal thêm khách hàng mới cho đơn hàng
  $("#btnAddKhachHang").off('click').on('click', function(e) {
    e.preventDefault();
    $("#addCustomerOrderForm")[0].reset();
    $("#orderDupHelpAdd").addClass('d-none').text('');
    $("#orderPhoneTagsAdd .badge").remove();
    $("#orderPhonesHiddenAdd").val('');
    $("#addCustomerOrderModal").modal('show');
  });

  function numberFormat(x) {
    return Number(x).toLocaleString('vi-VN');
  }
  function getSanPhamByMa(ma) {
    ma = ma.trim();
    for (let i = 0; i < dsSanPham.length; i++) {
      if (dsSanPham[i].ma_sp == ma) return dsSanPham[i];
    }
    return null;
  }
  function updateCombo(tr) {
    var ma = tr.find('.ma_sp_combo').val();
    var soLuong = parseInt(tr.find('.so_luong').val()) || 1;
    var arrMa = ma.split(',').map(x => x.trim()).filter(x => x);
    var tooltipList = [], nameList = [];
    var tongGia = 0, hasError = false;

    // Kiểm tra nếu là sản phẩm combo (combo=1) thì chỉ cho phép 1 mã
    let comboFound = false, comboError = false;
    arrMa.forEach(function(m) {
      var sp = dsSanPham.find(x => x.ma_sp == m && x.co_chiet_khau == 0);
      if (sp && sp.combo == 1) comboFound = true;
      if (sp && sp.combo == 1 && arrMa.length > 1) comboError = true;
    });
    if (comboError) {
      tr.find('.ten_sp_combo').html('<span class="text-danger">Chỉ được nhập 1 mã cho sản phẩm combo!</span>');
      tr.find('.don_gia_show').html('0 <span class="donvi">đ</span>');
      tr.find('.don_gia').val(0);
      tr.find('.thanh_tien_show').html('0 <span class="donvi">đ</span>');
      tr.find('.thanh_tien').val(0);
      tr.find('.tooltip-combo').html('Lỗi mã!').removeClass('d-none badge-info').addClass('badge-danger');
      // Disable submit
      $('#formDonHang button[type=submit]').prop('disabled', true);
      capNhatTong();
      return;
    }

    arrMa.forEach(function(m) {
      var sp = getSanPhamByMa(m);
      if (sp) {
        var line = sp.ten_sp + ' (' + numberFormat(sp.gia) + ')';
        tooltipList.push(line); nameList.push(line); tongGia += Number(sp.gia);
      } else {
        var err = '<span class="text-danger">Mã ['+m+'] không có hoặc không phải bánh có chiết khấu!</span>';
        tooltipList.push(err); nameList.push(err); hasError = true;
      }
    });

    var tooltip = tr.find('.tooltip-combo');
    if (arrMa.length > 0 && hasError) {
      tooltip.html('Lỗi mã!').removeClass('d-none badge-info').addClass('badge-danger');
      // Disable submit nếu có lỗi mã
      $('#formDonHang button[type=submit]').prop('disabled', true);
      // Nếu có lỗi, không tính tổng tiền cho dòng này
      tongGia = 0;
    } else if (comboFound && arrMa.length === 1) {
      tooltip.html('Combo').removeClass('d-none badge-danger').addClass('badge-info');
      $('#formDonHang button[type=submit]').prop('disabled', false);
    } else if (arrMa.length === 1 && !comboFound && !hasError) {
      tooltip.html('Cái').removeClass('d-none badge-danger').addClass('badge-info');
      $('#formDonHang button[type=submit]').prop('disabled', false);
    } else if (arrMa.length > 1 && !hasError) {
      tooltip.html('Hộp ' + arrMa.length + ' bánh').removeClass('d-none badge-danger').addClass('badge-info');
      $('#formDonHang button[type=submit]').prop('disabled', false);
    } else {
      tooltip.addClass('d-none');
      $('#formDonHang button[type=submit]').prop('disabled', false);
    }

    tr.find('.ten_sp_combo').html(nameList.join('<br>'));
    tr.find('.don_gia_show').html(numberFormat(tongGia) + ' <span class="donvi">đ</span>');
    tr.find('.don_gia').val(tongGia);

    // Nếu có lỗi mã thì thành tiền = 0
    var thanhTien = hasError ? 0 : tongGia * soLuong;
    tr.find('.thanh_tien_show').html(numberFormat(thanhTien) + ' <span class="donvi">đ</span>');
    tr.find('.thanh_tien').val(thanhTien);

    // Luôn cập nhật tổng tiền sau mỗi lần update dòng
    capNhatTong();
  }

  function capNhatTong() {
    var tong = 0;
    $('#tableSanPham tbody tr').each(function() {
      tong += Number($(this).find('.thanh_tien').val() || 0);
    });
    $('#tongTienView').html('<span>' + numberFormat(tong) + ' <span class="donvi">đ</span></span>');
    $('#tongtien').val(tong);
  }
  // Nhập mã combo
  $('#tableSanPham').on('input', '.ma_sp_combo', function(){
    updateCombo($(this).closest('tr'));
  });
  // Thay đổi số lượng
  $('#tableSanPham').on('input', '.so_luong', function(){
    updateCombo($(this).closest('tr'));
  });
  // Thêm dòng mới
  $('#btnThemDong').off('click').on('click', function(){
    var dong = $('#tableSanPham tbody tr:first').clone();
    dong.find('input').val('');
    dong.find('.so_luong').val(1);
    dong.find('.tooltip-combo').html('').addClass('d-none badge-info').removeClass('badge-danger');
    dong.find('.ten_sp_combo,.don_gia_show,.thanh_tien_show').html('');
    $('#tableSanPham tbody').append(dong);
  });
  // Xoá dòng
  $('#tableSanPham').on('click', '.btnXoaDong', function(){
    if($('#tableSanPham tbody tr').length > 1) {
      $(this).closest('tr').remove();
      capNhatTong();
    }
  });

  // Tính tổng tiền khi load lại form
  $('#tableSanPham tbody tr').each(function(){
    updateCombo($(this));
  });
});
</script>
