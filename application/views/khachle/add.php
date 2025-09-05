<?php $this->load->view('khachhang/add'); ?>
<?php $this->load->helper('money'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-plus"></i> Thêm đơn khách lẻ</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= site_url('khachle') ?>">Khách lẻ</a></li>
            <li class="breadcrumb-item active">Thêm đơn khách lẻ</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" action="<?= site_url('khachle/add'); ?>" id="formKhachLe">
            <!-- Thông tin khách lẻ -->
            <div class="form-group row align-items-center mb-4">
              <label class="col-sm-2 col-form-label font-weight-bold" for="ten">Tên khách</label>
              <div class="col-sm-6">
                <input type="text" name="ten" id="ten" class="form-control" placeholder="Nhập tên khách lẻ..." required>
              </div>
              <div class="col-sm-4"></div>
            </div>
            <div class="form-group row align-items-center mb-4">
              <label class="col-sm-2 col-form-label font-weight-bold" for="dienthoai">Điện thoại</label>
              <div class="col-sm-6">
                <input type="text" name="dienthoai" id="dienthoai" class="form-control" placeholder="Nhập số điện thoại">
              </div>
              <div class="col-sm-4"></div>
            </div>
            <div class="form-group row align-items-center mb-4">
              <label class="col-sm-2 col-form-label font-weight-bold" for="diachi">Địa chỉ</label>
              <div class="col-sm-6">
                <input type="text" name="diachi" id="diachi" class="form-control" placeholder="Nhập địa chỉ">
              </div>
              <div class="col-sm-4"></div>
            </div>
            <!-- Ẩn ngày lập -->
            <input type="hidden" name="ngaylap" value="<?= date('Y-m-d H:i:s') ?>">
            <hr>
            <!-- Chi tiết sản phẩm -->
            <div class="mb-2 font-weight-bold">Chi tiết sản phẩm</div>
            <div class="table-responsive">
              <table class="table table-bordered mb-0" id="tableSanPham">
                <thead>
                  <tr>
                    <th style="width:180px;">Mã bánh</th>
                    <th style="max-width:380px;">Tên bánh</th>
                    <th style="width:120px;" class="text-center">Đơn giá</th>
                    <th style="width:120px;" class="text-center">Giảm giá</th>
                    <th style="width:120px;" class="text-center">Giá sau giảm</th>
                    <th style="width:120px;" class="text-center">Số lượng</th>
                    <th style="width:120px;" class="text-center">Thành tiền</th>
                    <th style="width:40px;"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <input type="text" name="ma_sp[]" class="form-control ma_sp_combo" style="min-width:100px;width:120px;" autocomplete="off" placeholder="VD: 5,7,2B5">
                        <span class="badge badge-info tooltip-combo ml-2 align-self-start d-none" style="cursor:pointer;min-width:60px;"></span>
                      </div>
                    </td>
                    <td>
                      <div class="ten_sp_combo small text-dark" style="max-width:380px;white-space:pre-line;overflow-x:auto;"></div>
                    </td>
                    <td>
                      <a href="#" class="btn btn-sm btn-olive btnChonGiamGia" tabindex="-1">
                        <div class="font-weight-bold text-right don_gia_show d-flex align-items-center justify-content-end" style="font-size:1.1em; min-width:80px; height:38px;"></div>
                        <input type="hidden" name="don_gia[]" class="don_gia" readonly>
                      </a>
                    </td>
                    <td>
                      <div class="d-flex flex-column align-items-start" style="min-width:120px;">
                        <span class="giamgia-label font-weight-bold text-info mb-1">Không Giảm</span>
                        <span class="giamgia-detail text-muted small"></span>
                        <input type="hidden" name="giamgiadg_loai[]" class="giamgiadg_loai" value="none">
                        <input type="hidden" name="giamgiadg_giatri[]" class="giamgiadg_giatri" value="0">
                      </div>
                    </td>
                    <td>
                      <div class="font-weight-bold text-right giamgiadg_thanhtien_show d-flex align-items-center justify-content-end" style="font-size:1.1em; min-width:80px; height:38px;"></div>
                      <input type="hidden" name="giamgiadg_thanhtien[]" class="giamgiadg_thanhtien" readonly>
                    </td>
                    <td>
                      <input type="number" name="so_luong[]" class="form-control so_luong text-center" min="1" value="1" required style="width:90px; height:38px; text-align:center; display:flex; align-items:center; justify-content:center;">
                    </td>
                    <td>
                      <div class="font-weight-bold text-danger text-right thanh_tien_show d-flex align-items-center justify-content-end" style="font-size:1.15em; min-width:80px; height:38px;"></div>
                      <input type="hidden" name="thanh_tien[]" class="thanh_tien" readonly>
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm btnXoaDong"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <button type="button" id="btnThemDong" class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i> Thêm dòng</button>
            </div>
            <hr>
            <!-- Tổng tiền, ghi chú -->
            <div class="row align-items-center">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label font-weight-bold" for="ghiChuInput">Ghi chú</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" id="ghiChuInput" name="ghi_chu" rows="2" placeholder="Ghi chú thêm (nếu có)"></textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <table class="table table-borderless mb-0">
                  <tr>
                    <td class="font-weight-bold text-right tong-tien-label">Thành tiền:</td>
                    <td class="text-right tong-tien-value" style="width:100px;" id="tongTienView">
                      <span><?= money_vnd(0) ?></span>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Giảm giá toàn đơn:</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <select name="giamgiatt_loai" id="giamgiatt_loai" class="form-control form-control-sm mr-2" style="width:80px;">
                          <option value="none">Không</option>
                          <option value="phantram">%</option>
                          <option value="tienmat">Tiền</option>
                        </select>
                        <input type="number" name="giamgiatt_giatri" id="giamgiatt_giatri" class="form-control form-control-sm mr-2" min="0" value="0" style="width:80px;">
                        <span id="giamgiatt_thanhtien_view" class="font-weight-bold text-danger ml-2"></span>
                        <input type="hidden" name="giamgiatt_thanhtien" id="giamgiatt_thanhtien" value="0">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Phí ship:</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <input type="text" name="ship" id="ship" class="form-control form-control-sm text-right" min="0" value="0" style="width:80px;display:inline-block;">
                        <span id="ship_view" class="font-weight-bold text-danger ml-2"></span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Tổng tiền:</td>
                    <td class="text-right font-weight-bold" id="tongcongTienView">
                      <span>0 đ</span>
                      <input type="hidden" name="tongcong_tien" id="tongcong_tien" value="0">
                    </td>
                  </tr>
                </table>
                <input type="hidden" name="tongtien" id="tongtien" value="0">
              </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
              <a href="<?= site_url('khachle'); ?>" class="btn btn-secondary mr-2">Quay lại</a>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu đơn khách lẻ</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- Modal giảm giá dùng chung cho từng dòng -->
<div class="modal fade" id="modalGiamGia" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="formGiamGia" action="javascript:void(0)">
        <div class="modal-header bg-olive text-white">
          <h5 class="modal-title"><i class="fas fa-percent mr-2"></i>Áp dụng giảm giá</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-3">
            <label class="font-weight-bold mb-2">Chọn loại giảm giá:</label>
            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
              <label class="btn bg-olive font-weight-bold giamgia-radio active" style="flex:1;">
                <input type="radio" name="modal_giamgiadg_loai" value="none" autocomplete="off" checked> Không
              </label>
              <label class="btn bg-olive font-weight-bold giamgia-radio" style="flex:1;">
                <input type="radio" name="modal_giamgiadg_loai" value="phantram" autocomplete="off"> %
              </label>
              <label class="btn bg-olive font-weight-bold giamgia-radio" style="flex:1;">
                <input type="radio" name="modal_giamgiadg_loai" value="tienmat" autocomplete="off"> Tiền
              </label>
            </div>
          </div>
          <div class="form-group mb-3">
            <label class="font-weight-bold mb-2">Giá trị giảm giá:</label>
            <input type="number" name="modal_giamgiadg_giatri" id="modal_giamgiadg_giatri" class="form-control" min="0" value="0">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Quay lại</button>
          <button type="submit" class="btn btn-olive font-weight-bold">Áp dụng</button>
        </div>
      </form>
    </div>
  </div>
</div>
<style>
.btn-olive, .bg-olive { background-color: #4caf50 !important; color: #fff !important; }
.giamgia-radio { border-radius: 0 !important; margin-right: -1px !important; font-size: 1.05em; padding: 0.45em 1.1em; }
.giamgia-radio.active, .giamgia-radio:active, .giamgia-radio:focus { background: #218838 !important; color: #fff !important; box-shadow: none !important; }
</style>
<script>
var dsSanPham = <?= json_encode($sanpham); ?>;
var $rowGiamGia = null;
function numberFormat(x) { return Number(x).toLocaleString('vi-VN'); }
function getSanPhamByMa(ma) {
  ma = ma.trim();
  for (let i = 0; i < dsSanPham.length; i++) {
    if (dsSanPham[i].ma_sp == ma) return dsSanPham[i];
  }
  return null;
}
function calcGiamGia(donGia, loai, giatri) {
  donGia = Number(donGia) || 0;
  giatri = Number(giatri) || 0;
  if (loai === 'phantram') return Math.max(0, donGia - Math.round(donGia * giatri / 100));
  if (loai === 'tienmat') return Math.max(0, donGia - giatri);
  return donGia;
}
function capNhatTong() {
  var tong = 0;
  $('#tableSanPham tbody tr').each(function() {
    var val = $(this).find('.thanh_tien').val();
    tong += (val && !isNaN(val)) ? Number(val) : 0;
  });
  $('#tongTienView').html('<span>' + numberFormat(tong) + ' <span class="donvi">đ</span></span>');
  $('#tongtien').val(tong);

  // Tính giảm giá toàn đơn
  var tt_loai = $('#giamgiatt_loai').val();
  var tt_giatri = Number($('#giamgiatt_giatri').val()) || 0;
  var tt_thanhtien = 0;
  if (tt_loai === 'phantram' && tt_giatri > 0) {
    tt_thanhtien = Math.round(tong * tt_giatri / 100);
    $('#giamgiatt_thanhtien_view').text('-' + numberFormat(tt_thanhtien) + 'đ');
  } else if (tt_loai === 'tienmat' && tt_giatri > 0) {
    tt_thanhtien = Math.min(tt_giatri, tong);
    $('#giamgiatt_thanhtien_view').text('-' + numberFormat(tt_thanhtien) + 'đ');
  } else {
    tt_thanhtien = 0;
    $('#giamgiatt_thanhtien_view').text('');
  }
  $('#giamgiatt_thanhtien').val(tt_thanhtien);

  // Tính phí ship
  var ship = Number($('#ship').val().replace(/[^0-9]/g, '')) || 0;
  $('#ship_view').html(numberFormat(ship) + ' <span class="donvi">đ</span>');

  // Tổng tiền cuối cùng
  var tongcong = tong - tt_thanhtien + ship;
  if (tongcong < 0) tongcong = 0;
  $('#tongcongTienView').html('<span>' + numberFormat(tongcong) + ' <span class="donvi">đ</span></span>');
  $('#tongcong_tien').val(tongcong);
}
function getLoaiBanhTooltip(ma_sp_str) {
  var arrMa = ma_sp_str.split(',').map(x => x.trim()).filter(x => x);
  var count = arrMa.length;
  if (count === 1) {
    var sp = getSanPhamByMa(arrMa[0]);
    if (sp) {
      if (typeof sp.combo !== 'undefined') {
        if (String(sp.combo) === '1' || parseInt(sp.combo) === 1) return 'Combo';
        if (String(sp.combo) === '0' || parseInt(sp.combo) === 0) return 'Cái';
      }
      return 'Cái';
    }
    return 'Không xác định';
  }
  if (count > 1) return 'Hộp ' + count + ' bánh';
  return '';
}

function updateCombo(tr) {
  var ma = tr.find('.ma_sp_combo').val();
  var soLuong = parseInt(tr.find('.so_luong').val()) || 1;
  var arrMa = ma.split(',').map(x => x.trim()).filter(x => x);
  var nameList = [];
  var tongGia = 0, hasError = false;

  // Kiểm tra nếu là sản phẩm combo (combo=1) thì chỉ cho phép 1 mã
  let comboFound = false, comboError = false;
  arrMa.forEach(function(m) {
    var sp = getSanPhamByMa(m);
    if (sp && sp.combo == 1) comboFound = true;
    if (sp && sp.combo == 1 && arrMa.length > 1) comboError = true;
  });
  var tooltip = tr.find('.tooltip-combo');
  if (!ma) {
    tooltip.addClass('d-none');
  } else if (comboError) {
    tr.find('.ten_sp_combo').html('<span class="text-danger">Chỉ được nhập 1 mã cho sản phẩm combo!</span>');
    tr.find('.don_gia_show').html('0');
    tr.find('.don_gia').val(0);
    tr.find('.thanh_tien_show').html('0');
    tr.find('.thanh_tien').val(0);
    tooltip.html('Lỗi mã!').removeClass('badge-info').addClass('badge-danger').removeClass('d-none');
    capNhatTong();
    return;
  } else {
    arrMa.forEach(function(m) {
      var sp = getSanPhamByMa(m);
      if (sp) {
        var line = sp.ten_sp + ' (' + numberFormat(sp.gia) + ')';
        nameList.push(line); tongGia += Number(sp.gia);
      } else {
        nameList.push('<span class="text-danger">Mã ['+m+'] không có!</span>'); hasError = true;
      }
    });

    // Tooltip loại bánh
    var tooltipText = getLoaiBanhTooltip(ma);
    if (arrMa.length > 0 && hasError) {
      tooltip.html('Lỗi mã!').removeClass('badge-info').addClass('badge-danger').removeClass('d-none');
    } else if (comboFound && arrMa.length === 1) {
      tooltip.html('Combo').removeClass('badge-danger').addClass('badge-info').removeClass('d-none');
    } else if (arrMa.length === 1 && !comboFound && !hasError) {
      tooltip.html('Cái').removeClass('badge-danger').addClass('badge-info').removeClass('d-none');
    } else if (arrMa.length > 1 && !hasError) {
      tooltip.html('Hộp ' + arrMa.length + ' bánh').removeClass('badge-danger').addClass('badge-info').removeClass('d-none');
    } else {
      tooltip.addClass('d-none');
    }
  }

  tr.find('.ten_sp_combo').html(nameList.join('<br>'));
  tr.find('.don_gia_show').html(numberFormat(tongGia));
  tr.find('.don_gia').val(tongGia);

  // Lấy giảm giá từ hidden
  var giamgiadg_loai = tr.find('.giamgiadg_loai').val() || 'none';
  var giamgiadg_giatri = tr.find('.giamgiadg_giatri').val() || 0;
  var giamgiadg_thanhtien = calcGiamGia(tongGia, giamgiadg_loai, giamgiadg_giatri);
  tr.find('.giamgiadg_thanhtien_show').html(numberFormat(giamgiadg_thanhtien));
  tr.find('.giamgiadg_thanhtien').val(giamgiadg_thanhtien);

  // Hiển thị label giảm giá và chi tiết giảm giá
  var label = 'Không Giảm', detail = '';
  if (giamgiadg_loai === 'phantram' && giamgiadg_giatri > 0) {
    label = 'Giảm %';
    detail = '-'+giamgiadg_giatri+'% ('+numberFormat(tongGia-giamgiadg_thanhtien)+'đ)';
  } else if (giamgiadg_loai === 'tienmat' && giamgiadg_giatri > 0) {
    label = 'Giảm Tiền';
    detail = '-'+numberFormat(giamgiadg_giatri)+'đ ('+Math.round(100*(tongGia-giamgiadg_thanhtien)/tongGia)+'%)';
  }
  tr.find('.giamgia-label').text(label);
  tr.find('.giamgia-detail').text(detail);

  var thanhTien = giamgiadg_thanhtien * soLuong;
  tr.find('.thanh_tien_show').html('<span class="text-danger">' + numberFormat(thanhTien) + '</span>');
  tr.find('.thanh_tien').val(thanhTien);

  capNhatTong();
}
$(document).ready(function(){
  // Nhập mã combo
  $('#tableSanPham').on('input', '.ma_sp_combo', function(){
    // Tự động chuyển thành chữ hoa khi nhập
    var val = $(this).val();
    var upper = val.toUpperCase();
    if (val !== upper) {
      $(this).val(upper);
    }
    updateCombo($(this).closest('tr'));
  });
  // Thay đổi số lượng
  $('#tableSanPham').on('input', '.so_luong', function(){
    updateCombo($(this).closest('tr'));
  });
  // Thay đổi giảm giá loại hoặc giá trị (ẩn, chỉ cập nhật lại dòng)
  $('#tableSanPham').on('change input', '.giamgiadg_loai, .giamgiadg_giatri', function(){
    updateCombo($(this).closest('tr'));
  });
  // Thêm dòng mới
  $('#btnThemDong').off('click').on('click', function(){
    var dong = $('#tableSanPham tbody tr:first').clone();
    dong.find('input').val('');
    dong.find('.so_luong').val(1);
    dong.find('.giamgiadg_loai').val('none');
    dong.find('.giamgiadg_giatri').val(0);
    dong.find('.ten_sp_combo,.don_gia_show,.giamgiadg_thanhtien_show,.thanh_tien_show').html('');
    dong.find('.tooltip-combo').addClass('d-none').removeClass('badge-danger').addClass('badge-info');
    $('#tableSanPham tbody').append(dong);
  });
  // Xoá dòng
  $('#tableSanPham').on('click', '.btnXoaDong', function(){
    if($('#tableSanPham tbody tr').length > 1) {
      $(this).closest('tr').remove();
      capNhatTong();
    }
  });

  // Mở modal giảm giá cho dòng
  $('#tableSanPham').on('click', '.btnChonGiamGia', function(){
    $rowGiamGia = $(this).closest('tr');
    var loai = $rowGiamGia.find('.giamgiadg_loai').val() || 'none';
    var giatri = $rowGiamGia.find('.giamgiadg_giatri').val() || 0;
    $('#modalGiamGia input[name="modal_giamgiadg_loai"][value="'+loai+'"]').prop('checked', true).closest('.giamgia-radio').addClass('active').siblings().removeClass('active');
    $('#modal_giamgiadg_giatri').val(giatri);
    $('#modalGiamGia').modal('show');
  });

  // Chọn loại giảm giá trong modal
  $('#modalGiamGia').on('change', 'input[name="modal_giamgiadg_loai"]', function(){
    $('#modalGiamGia .giamgia-radio').removeClass('active');
    $(this).closest('.giamgia-radio').addClass('active');
  });

  // Áp dụng giảm giá cho dòng
  $('#formGiamGia').submit(function(e){
    e.preventDefault();
    if ($rowGiamGia) {
      var loai = $('#modalGiamGia input[name="modal_giamgiadg_loai"]:checked').val();
      var giatri = $('#modal_giamgiadg_giatri').val();
      $rowGiamGia.find('.giamgiadg_loai').val(loai);
      $rowGiamGia.find('.giamgiadg_giatri').val(giatri);

      // Hiển thị label giảm giá
      var label = 'Không Giảm';
      if (loai === 'phantram' && giatri > 0) {
        label = 'Giảm %';
      } else if (loai === 'tienmat' && giatri > 0) {
        label = 'Giảm Tiền';
      }
      $rowGiamGia.find('.giamgia-label').text(label);

      updateCombo($rowGiamGia);
      $('#modalGiamGia').modal('hide');
    }
  });
  $('#giamgiatt_loai, #giamgiatt_giatri, #ship').on('change input', function(){
    capNhatTong();
  });
  $('#ship').on('input', function() {
    var val = $(this).val().replace(/[^0-9]/g, '');
    if (val === '') val = '0';
    var formatted = Number(val).toLocaleString('vi-VN');
    $(this).val(formatted);
    $('#ship_view').html(formatted + ' <span class="donvi">đ</span>');
    capNhatTong();
  });
  $('#giamgiatt_loai, #giamgiatt_giatri').on('change input', function(){
    capNhatTong();
  });
  $('#formKhachLe').on('submit', function(e){
  // Xử lý ship và tongcong_tien về số nguyên
  var shipVal = $('#ship').val();
  shipVal = shipVal ? shipVal.replace(/[^0-9]/g, '') : '0';
  $('#ship').val(shipVal);

  var tongcongVal = $('#tongcong_tien').val();
  tongcongVal = tongcongVal ? tongcongVal.replace(/[^0-9]/g, '') : '0';
  $('#tongcong_tien').val(tongcongVal);
});
});
</script>
