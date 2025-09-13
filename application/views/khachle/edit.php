
<script>
// Đảm bảo khi trang sửa đơn được load, các dòng sản phẩm đều được cập nhật lại tên, loại bánh, giá, thành tiền
$(document).ready(function(){
  $('#tableSanPham tbody tr').each(function(){
    updateCombo($(this));
  });
});
</script>

<?php $this->load->helper('money'); ?>
<div class="content-wrapper">

<section class="content">
    <div class="container-fluid">
        <br />
        <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" action="<?= site_url('khachle/edit/'.$row->id); ?>" id="formKhachLe">
            <!-- Thông tin khách lẻ -->
            <input type="hidden" name="madon_id" id="madon_id" value="<?= htmlspecialchars($row->madon_id ?? '') ?>">
            <div class="form-group row align-items-center mb-4">
              <label class="col-sm-2 col-form-label font-weight-bold" for="ten">Tên khách</label>
              <div class="col-sm-6">
                <input type="text" name="ten" id="ten" class="form-control" value="<?= htmlspecialchars($row->ten) ?>" placeholder="Nhập tên khách lẻ..." required>
              </div>
              <div class="col-sm-4"></div>
            </div>
            <div class="form-group row align-items-center mb-4">
              <label class="col-sm-2 col-form-label font-weight-bold" for="dienthoai">Điện thoại</label>
              <div class="col-sm-6">
                <input type="text" name="dienthoai" id="dienthoai" class="form-control" value="<?= htmlspecialchars($row->dienthoai) ?>" placeholder="Nhập số điện thoại">
              </div>
              <div class="col-sm-4"></div>
            </div>
            <div class="form-group row align-items-center mb-4">
              <label class="col-sm-2 col-form-label font-weight-bold" for="diachi">Địa chỉ</label>
              <div class="col-sm-6">
                <input type="text" name="diachi" id="diachi" class="form-control" value="<?= htmlspecialchars($row->diachi) ?>" placeholder="Nhập địa chỉ">
              </div>
              <div class="col-sm-4"></div>
            </div>
            <!-- Ẩn ngày lập -->
            <input type="hidden" name="ngaylap" value="<?= htmlspecialchars($row->ngaylap) ?>">
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
                  <?php foreach($chitiet as $ct): ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <input type="text" name="ma_sp[]" class="form-control ma_sp_combo" style="min-width:120px;width:250px;" autocomplete="off" placeholder="VD: 5,7,2B5" value="<?= htmlspecialchars($ct->ma_sp) ?>">
                        <span class="badge badge-info tooltip-combo ml-2 d-none" style="cursor:pointer;min-width:30px;"></span>
                      </div>
                    </td>
                    <td>
                      <div class="ten_sp_combo small text-dark" style="max-width:380px;white-space:pre-line;overflow-x:auto;">
                        <?php
                        if (isset($ct->ma_sp) && !empty($ct->ma_sp) && isset($sanpham) && is_array($sanpham)) {
                          $arrMa = explode(',', $ct->ma_sp);
                          $tenList = [];
                          foreach ($arrMa as $ma) {
                            $ma = trim($ma);
                            foreach ($sanpham as $sp) {
                                if (isset($sp->ma_sp) && $sp->ma_sp == $ma) {
                                  $tenList[] = htmlspecialchars($sp->ten_sp) . ' (' . number_format($sp->gia, 0, ',', '.') . ')';
                                  break;
                                }
                              }
                          }
                          echo implode('<br>', $tenList);
                        }
                        ?>
                      </div>
                    </td>
                    <td>
                      <a href="#" class="btn btn-sm btn-olive btnChonGiamGia" tabindex="-1">
                        <div class="font-weight-bold text-right don_gia_show d-flex align-items-center justify-content-end" style="font-size:1.1em; min-width:80px; height:38px;">
                          <?= isset($ct->don_gia) ? number_format($ct->don_gia, 0, ',', '.') : '' ?>
                        </div>
                        <input type="hidden" name="don_gia[]" class="don_gia" readonly value="<?= (int)$ct->don_gia ?>">
                      </a>
                    </td>
                    <td>
                      <div class="d-flex flex-column align-items-start" style="min-width:120px;">
                        <span class="giamgia-label font-weight-bold text-info mb-1">
                          <?= (isset($ct->giamgiadg_loai) && $ct->giamgiadg_loai == 'phantram' && $ct->giamgiadg_giatri > 0) ? 'Giảm %' : ((isset($ct->giamgiadg_loai) && $ct->giamgiadg_loai == 'tienmat' && $ct->giamgiadg_giatri > 0) ? 'Giảm Tiền' : 'Không Giảm') ?>
                        </span>
                        <span class="giamgia-detail text-muted small">
                          <?php
                          if (isset($ct->giamgiadg_loai) && $ct->giamgiadg_loai == 'phantram' && $ct->giamgiadg_giatri > 0) {
                            echo '-'.$ct->giamgiadg_giatri.'%';
                          } elseif (isset($ct->giamgiadg_loai) && $ct->giamgiadg_loai == 'tienmat' && $ct->giamgiadg_giatri > 0) {
                            echo '-'.number_format($ct->giamgiadg_giatri, 0, ',', '.').'đ';
                          }
                          ?>
                        </span>
                        <input type="hidden" name="giamgiadg_loai[]" class="giamgiadg_loai" value="<?= htmlspecialchars($ct->giamgiadg_loai ?? 'none') ?>">
                        <input type="hidden" name="giamgiadg_giatri[]" class="giamgiadg_giatri" value="<?= htmlspecialchars($ct->giamgiadg_giatri ?? 0) ?>">
                      </div>
                    </td>
                    <td>
                      <div class="font-weight-bold text-right giamgiadg_thanhtien_show d-flex align-items-center justify-content-end" style="font-size:1.1em; min-width:80px; height:38px;">
                        <?= isset($ct->giamgiadg_thanhtien) ? number_format($ct->giamgiadg_thanhtien, 0, ',', '.') : '' ?>
                      </div>
                      <input type="hidden" name="giamgiadg_thanhtien[]" class="giamgiadg_thanhtien" readonly value="<?= (int)($ct->giamgiadg_thanhtien ?? 0) ?>">
                    </td>
                    <td>
                      <input type="number" name="so_luong[]" class="form-control so_luong text-center" min="1" value="<?= $ct->so_luong ?>" required style="width:90px; height:38px; text-align:center; display:flex; align-items:center; justify-content:center;">
                    </td>
                    <td>
                      <div class="font-weight-bold text-danger text-right thanh_tien_show d-flex align-items-center justify-content-end" style="font-size:1.15em; min-width:80px; height:38px;">
                        <?= isset($ct->thanh_tien) ? number_format($ct->thanh_tien, 0, ',', '.') : '' ?>
                      </div>
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
            <!-- Tổng tiền, ghi chú -->
            <div class="row align-items-center">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label font-weight-bold" for="ghiChuInput">Ghi chú</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" id="ghiChuInput" name="ghi_chu" rows="2" placeholder="Ghi chú thêm (nếu có)"><?= htmlspecialchars($row->ghi_chu) ?></textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <table class="table table-borderless mb-0">
                  <tr>
                    <td class="font-weight-bold text-right">Thành tiền:</td>
                    <td class="text-right" style="width:100px;" id="tongTienView">
                      <span><?= money_vnd($row->tongtien) ?></span>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Giảm giá toàn đơn:</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <select name="giamgiatt_loai" id="giamgiatt_loai" class="form-control form-control-sm mr-2" style="width:80px;">
                          <option value="none" <?= ($row->giamgiatt_loai ?? 'none') == 'none' ? 'selected' : '' ?>>Không</option>
                          <option value="phantram" <?= ($row->giamgiatt_loai ?? '') == 'phantram' ? 'selected' : '' ?>>%</option>
                          <option value="tienmat" <?= ($row->giamgiatt_loai ?? '') == 'tienmat' ? 'selected' : '' ?>>Tiền</option>
                        </select>
                        <input type="number" name="giamgiatt_giatri" id="giamgiatt_giatri" class="form-control form-control-sm" style="width:80px;" value="<?= (int)($row->giamgiatt_giatri ?? 0) ?>">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Phí ship:</td>
                    <td>
                      <input type="number" name="ship" id="ship" class="form-control form-control-sm" style="width:80px;" value="<?= (int)($row->ship ?? 0) ?>">
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold text-right">Tổng cộng tiền:</td>
                    <td class="text-right" style="width:100px; color:red; font-weight:bold; font-size:1.2em;" id="tongCongTienView">
                      <span><?= money_vnd($row->tongcong_tien ?? 0) ?></span>
                    </td>
                  </tr>
                </table>
                <!-- Đảm bảo input hidden tổng tiền và tổng cộng tiền nằm trong form -->
                <input type="hidden" name="tongtien" id="tongtien" value="<?= (int)($row->tongtien ?? 0) ?>">
                <input type="hidden" name="tongcong_tien" id="tongcong_tien" value="<?= (int)($row->tongcong_tien ?? 0) ?>">
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
  $('#tongcong_tien').val(tongcong); // Đảm bảo luôn cập nhật input hidden tổng cộng tiền
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
    capNhatTong();
  });
  // Thay đổi số lượng
  $('#tableSanPham').on('input', '.so_luong', function(){
    updateCombo($(this).closest('tr'));
    capNhatTong();
  });
  // Thay đổi giảm giá loại hoặc giá trị (ẩn, chỉ cập nhật lại dòng)
  $('#tableSanPham').on('change input', '.giamgiadg_loai, .giamgiadg_giatri', function(){
    updateCombo($(this).closest('tr'));
    capNhatTong();
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
    e.preventDefault();
    // Đảm bảo giá trị tổng tiền và tổng cộng tiền là số nguyên
    var tongVal = $('#tongtien').val();
    tongVal = tongVal ? tongVal.replace(/[^0-9]/g, '') : '0';
    $('#tongtien').val(tongVal);
    var tongcongVal = $('#tongcong_tien').val();
    tongcongVal = tongcongVal ? tongcongVal.replace(/[^0-9]/g, '') : '0';
    $('#tongcong_tien').val(tongcongVal);

    var $form = $(this);
    $.post($form.attr('action'), $form.serialize(), function(resp){
      if (resp && resp.id) {
        window.open('/khachle/pos/' + resp.id, '_blank');
        window.location.href = '/khachle';
      } else {
        alert('Lưu đơn thất bại!');
      }
    }, 'json');
  });
});
</script>