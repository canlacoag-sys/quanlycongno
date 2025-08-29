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

function updateRow(tr) {
  var ma = tr.find('.ma_sp_combo').val();
  var soLuong = parseInt(tr.find('.so_luong').val()) || 1;
  var sp = getSanPhamByMa(ma);
  var donGia = sp ? Number(sp.gia) : 0;
  tr.find('.don_gia').val(donGia);
  var thanhTien = donGia * soLuong;
  tr.find('.thanh_tien').val(thanhTien);
  updateTong();
}

function updateTong() {
  var tong = 0;
  $('#chitietDonHang .chitiet-row').each(function() {
    tong += Number($(this).find('.thanh_tien').val() || 0);
  });
  $('#tongtien').val(tong);
  var datra = Number($('#datra').val() || 0);
  var conno = tong - datra;
  if (conno < 0) conno = 0;
  $('#conno').val(conno);
}

$(document).ready(function(){
  // Khi chọn sản phẩm
  $('#chitietDonHang').on('change', '.ma_sp_combo', function(){
    updateRow($(this).closest('.chitiet-row'));
  });
  // Khi thay đổi số lượng
  $('#chitietDonHang').on('input', '.so_luong', function(){
    updateRow($(this).closest('.chitiet-row'));
  });
  // Khi nhập đã trả
  $('#datra').on('input', function(){
    updateTong();
  });
  // Thêm dòng mới
  $(document).on('click', '.btnAddRow', function(){
    var $row = $(this).closest('.chitiet-row');
    var $clone = $row.clone();
    $clone.find('input,select').val('');
    $clone.find('.so_luong').val(1);
    $('#chitietDonHang').append($clone);
  });
  // Xoá dòng
  $(document).on('click', '.btnRemoveRow', function(){
    if ($('#chitietDonHang .chitiet-row').length > 1)
      $(this).closest('.chitiet-row').remove();
    updateTong();
  });
  // Tính lại tổng khi mở modal
  $('#modalAddDonHang').on('shown.bs.modal', function(){
    updateTong();
  });
});