function dhNumberFormat(x) {
  return Number(x).toLocaleString('vi-VN');
}

function dhGetSanPhamByMa(ma) {
  ma = ma.trim();
  for (let i = 0; i < dsSanPham.length; i++) {
    if (dsSanPham[i].ma_sp == ma) return dsSanPham[i];
  }
  return null;
}

function dhUpdateRow(tr) {
  var ma = tr.find('.ma_sp_combo').val();
  var soLuong = parseInt(tr.find('.so_luong').val()) || 1;
  var sp = dhGetSanPhamByMa(ma);
  var donGia = sp ? Number(sp.gia) : 0;
  tr.find('.don_gia').val(donGia);
  var thanhTien = donGia * soLuong;
  tr.find('.thanh_tien').val(thanhTien);
  dhUpdateTong();
}

function dhUpdateTong() {
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
    dhUpdateRow($(this).closest('.chitiet-row'));
  });
  // Khi thay đổi số lượng
  $('#chitietDonHang').on('input', '.so_luong', function(){
    dhUpdateRow($(this).closest('.chitiet-row'));
  });
  // Khi nhập đã trả
  $('#datra').on('input', function(){
    dhUpdateTong();
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
    dhUpdateTong();
  });
  // Tính lại tổng khi mở modal
  $('#modalAddDonHang').on('shown.bs.modal', function(){
    dhUpdateTong();
  });
});

$(function() {
  // Autocomplete khách hàng
  $("#khachhang_autocomplete").autocomplete({
    source: function(request, response) {
      $.getJSON('/donhang/autocomplete_khachhang', {term: request.term}, response);
    },
    select: function(event, ui) {
      $("#khachhang_id").val(ui.item.id);
      $(this).val(ui.item.label);
      return false;
    }
  });

  // Thêm khách hàng mới
  $("#btnAddKhachHang").click(function() {
    $("#modalAddKhachHang").modal('show');
  });
  $("#formAddKhachHang").submit(function(e) {
    e.preventDefault();
    $.post('/donhang/add_khachhang_ajax', $(this).serialize(), function(kh) {
      $("#khachhang_id").val(kh.id);
      $("#khachhang_autocomplete").val(kh.ten + ' (' + kh.dienthoai + ')');
      $("#modalAddKhachHang").modal('hide');
    }, 'json');
  });

  // Filter loại bánh cho autocomplete
  function dhGetChietKhau() {
    return $("input[name=chietkhau]:checked").val();
  }

  // Autocomplete mã bánh (nhiều mã, cách nhau dấu phẩy)
  $(document).on('keydown.autocomplete', '.ma_sp_input', function() {
    var $input = $(this);
    $input.autocomplete({
      source: function(request, response) {
        var last = request.term.split(',').pop().trim();
        $.getJSON('/donhang/autocomplete_sanpham', {term: last, chietkhau: dhGetChietKhau()}, response);
      },
      focus: function() { return false; },
      select: function(event, ui) {
        var terms = this.value.split(',');
        terms.pop();
        terms.push(ui.item.value);
        this.value = terms.join(', ');
        $input.trigger('change');
        return false;
      }
    });
  });

  // Khi đổi loại bánh, clear autocomplete cache
  $("input[name=chietkhau]").change(function() {
    $(".ma_sp_input").val('');
    $(".don_gia_cell, .thanh_tien_cell, .loai_hop_cell").text('0');
    dhUpdateTongTien();
  });

  // Xác định loại hộp theo số mã bánh
  function dhGetLoaiHop(ma_sp_str) {
    var count = ma_sp_str.split(',').filter(x => x.trim()).length;
    if ([2,4,6,8].includes(count)) return count + ' bánh';
    return '';
  }

  // Tính đơn giá và thành tiền
  function dhUpdateRow2($row) {
    var ma_sp_str = $row.find('.ma_sp_input').val();
    var so_luong = parseInt($row.find('.so_luong_input').val()) || 1;
    var ma_arr = ma_sp_str.split(',').map(x => x.trim()).filter(x => x);
    var don_gia = 0;
    // Lấy giá từng mã bánh (giả sử dsSanPham có sẵn)
    ma_arr.forEach(function(ma) {
      var sp = dsSanPham.find(x => x.ma_sp === ma);
      if (sp) don_gia += parseInt(sp.gia);
    });
    $row.find('.loai_hop_cell').text(dhGetLoaiHop(ma_sp_str));
    $row.find('.don_gia_cell').text(don_gia);
    var thanh_tien = don_gia * so_luong;
    $row.find('.thanh_tien_cell').text(thanh_tien);
    dhUpdateTongTien();
  }

  // Khi nhập mã bánh hoặc số lượng
  $(document).on('change keyup', '.ma_sp_input, .so_luong_input', function() {
    var $row = $(this).closest('tr');
    dhUpdateRow2($row);
  });

  // Thêm dòng mới
  $("#btnAddRow").click(function() {
    var $tr = $("#tbodyChiTietSP tr:first").clone();
    $tr.find('input').val('');
    $tr.find('.don_gia_cell, .thanh_tien_cell, .loai_hop_cell').text('0');
    $("#tbodyChiTietSP").append($tr);
  });

  // Xoá dòng
  $(document).on('click', '.btnRemoveRow', function() {
    if ($("#tbodyChiTietSP tr").length > 1)
      $(this).closest('tr').remove();
    dhUpdateTongTien();
  });

  // Tính tổng tiền
  function dhUpdateTongTien() {
    var tong = 0;
    $("#tbodyChiTietSP tr").each(function() {
      tong += parseInt($(this).find('.thanh_tien_cell').text()) || 0;
    });
    $("#tongTienView").text(tong);
    var datra = parseInt($("#traTruocInput").val()) || 0;
    $("#conNoView").text(tong - datra);
  }
  $("#traTruocInput").on('input', dhUpdateTongTien);

  // Datetime picker
  $('#ngaylapInput').datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    defaultDate: moment()
  });

  // Nút in POS
  $("#btnInPOS").click(function() {
    var id = /* lấy id đơn hàng vừa lưu */;
    window.open('/donhang/pos/' + id, '_blank');
  });
});