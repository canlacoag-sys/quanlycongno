<?php $this->load->view('khachhang/add'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-plus"></i> Thêm đơn hàng</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= site_url('donhang') ?>">Đơn hàng</a></li>
            <li class="breadcrumb-item active">Thêm đơn hàng</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" action="<?= site_url('donhang/add'); ?>" id="formDonHang">
            <!-- Khách hàng -->
            <div class="form-group row align-items-center mb-4">
              <label class="col-sm-2 col-form-label font-weight-bold" for="khachhang_autocomplete">Khách hàng</label>
              <div class="col-sm-6">
                <input type="hidden" name="khachhang_id" id="khachhang_id">
                <input type="text" class="form-control" id="khachhang_autocomplete" placeholder="Nhập tên hoặc số điện thoại khách hàng..." autocomplete="off" required>
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
              <label class="col-sm-2 col-form-label font-weight-bold" for="chietkhau">Loại bánh</label>
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
              <table class="table table-bordered mb-0" id="tableSanPham">
                <thead>
                  <tr>
                    <th style="width:220px;">Mã SP</th>
                    <th style="max-width:300px;">Tên sản phẩm</th>
                    <th style="width:120px;" class="text-right">Đơn giá</th>
                    <th style="width:70px;">Số lượng</th>
                    <th style="width:150px;" class="text-right">Thành tiền</th>
                    <th style="width:40px;"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <input type="text" name="ma_sp[]" class="form-control ma_sp_combo" style="min-width:120px;width:170px;" autocomplete="off" placeholder="VD: 5,7,24">
                        <span class="badge badge-info d-none tooltip-combo ml-2" style="cursor:pointer;min-width:30px;"></span>
                      </div>
                    </td>
                    <td>
                      <div class="ten_sp_combo small text-dark" style="max-width:260px;white-space:pre-line;overflow-x:auto;"></div>
                    </td>
                    <td>
                      <div class="font-weight-bold text-right don_gia_show" style="font-size:1.15em"></div>
                      <input type="hidden" name="don_gia[]" class="don_gia" readonly>
                    </td>
                    <td>
                      <input type="number" name="so_luong[]" class="form-control so_luong" min="1" value="1" required style="width:70px;">
                    </td>
                    <td>
                      <div class="font-weight-bold text-danger text-right thanh_tien_show" style="font-size:1.25em"></div>
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
            <!-- Tổng tiền, trả trước, còn nợ, ngày giờ -->
            <div class="row align-items-center">
              <div class="col-md-8"></div>
              <div class="col-md-4">
                <table class="table table-borderless mb-0">
                  <tr>
                    <td class="font-weight-bold text-right">Tổng tiền:</td>
                    <td class="text-right text-primary font-weight-bold" style="width:100px;" id="tongTienView">0</td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
              <a href="<?= site_url('donhang'); ?>" class="btn btn-secondary mr-2">Quay lại</a>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu đơn hàng</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- Modal: Thêm khách hàng mới cho đơn hàng (giao diện giống ảnh, badge màu, cảnh báo, tag xoá) -->
<div class="modal fade" id="addCustomerOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="addCustomerOrderForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Thêm khách hàng mới</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="orderAddName">Tên khách hàng</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" id="orderAddName" name="ten" class="form-control" required placeholder="Nhập tên khách hàng">
          </div>
        </div>
        <div class="form-group">
          <label for="orderPhoneInputAdd">Điện thoại (nhiều số)</label>
          <div id="orderPhoneTagsAdd" class="input-group" style="flex-wrap:wrap;">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input id="orderPhoneInputAdd" type="text" class="form-control" style="outline:none;min-width:140px;" placeholder="Nhập 10 số và nhấn Enter">
          </div>
          <input type="hidden" name="dienthoai" id="orderPhonesHiddenAdd" value="">
          <small id="orderDupHelpAdd" class="text-danger d-none">
            Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.
          </small>
        </div>
        <div class="form-group">
          <label for="orderAddAddress">Địa chỉ</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            </div>
            <input type="text" id="orderAddAddress" name="diachi" class="form-control" placeholder="Nhập địa chỉ">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <button type="submit" id="btnSaveCustomerOrderAdd" class="btn btn-primary">
          <i class="fas fa-save mr-1"></i>Lưu khách hàng
        </button>
      </div>
    </form>
  </div>
</div>
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

  // Phone tag logic: check trùng ngay khi nhập đủ 10 số, giữ đúng trạng thái badge
  function dhNormalizePhone(phone) {
    phone = (phone || '').replace(/[^0-9]/g, '');
    if (phone.startsWith('84') && phone.length === 11) phone = '0' + phone.slice(2);
    if (phone.startsWith('0') && phone.length === 10) return phone;
    if (phone.startsWith('+84') && phone.length === 12) phone = '0' + phone.slice(3);
    return phone;
  }

  // Lưu trạng thái từng số: {phone: 'ok'|'dup'}
  var dhPhoneStatus = {};

  function dhRenderPhoneTags() {
    var $tags = $("#orderPhoneTagsAdd");
    $tags.find('.badge').remove();
    var phones = dhGetPhones();
    phones.forEach(function(phone) {
      var status = dhPhoneStatus[phone] || 'ok';
      var badge = $('<span class="badge align-middle"></span>')
        .addClass(status === 'dup' ? 'badge-danger' : 'badge-primary')
        .css({'font-size':'1rem','display':'flex','align-items':'center','margin-right':'0.25rem','margin-bottom':'0'})
        .text(phone);
      var removeBtn = $('<button type="button" class="btn btn-sm btn-light ml-1 p-0 px-1 remove-phone" aria-label="Xoá" title="Xoá"><i class="fas fa-times"></i></button>');
      removeBtn.click(function() {
        var arr = dhGetPhones().filter(function(p){return p!==phone;});
        delete dhPhoneStatus[phone];
        $("#orderPhonesHiddenAdd").val(arr.join(','));
        dhRenderPhoneTags();
        dhUpdateDupHelp();
      });
      badge.append(removeBtn);
      $tags.append(badge);
    });
  }
  function dhGetPhones() {
    var val = $("#orderPhonesHiddenAdd").val();
    return val ? val.split(',').map(function(p){return p.trim();}).filter(Boolean) : [];
  }
  function dhAddPhoneTag(val, status) {
    var arr = dhGetPhones();
    arr.push(val);
    $("#orderPhonesHiddenAdd").val(arr.join(','));
    dhPhoneStatus[val] = status;
    dhRenderPhoneTags();
    dhUpdateDupHelp();
  }
  function dhUpdateDupHelp() {
    var hasDup = Object.values(dhPhoneStatus).includes('dup');
    if (hasDup) {
      $("#orderDupHelpAdd").removeClass('d-none').text('Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.');
      $("#btnSaveCustomerOrderAdd").prop('disabled', true);
    } else {
      $("#orderDupHelpAdd").addClass('d-none').text('');
      $("#btnSaveCustomerOrderAdd").prop('disabled', false);
    }
  }

  // Check phone ngay khi nhập đủ 10 số
  $("#orderPhoneInputAdd").on('input', function() {
    var val = dhNormalizePhone($(this).val());
    if (val.length === 10 && /^0\d{9}$/.test(val) && !dhGetPhones().includes(val)) {
      $.get('<?= site_url('khachhang/check_phone') ?>', {phone: val}, function(resp){
        if(resp.exists) {
          dhAddPhoneTag(val, 'dup');
        } else {
          dhAddPhoneTag(val, 'ok');
        }
      }, 'json');
      $(this).val('');
    } else if (val.length > 10) {
      $(this).val('');
    }
  });

  // Xử lý khi nhấn Enter, Tab, dấu phẩy, dấu cách
  $("#orderPhoneInputAdd").on('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',' || e.key === ' ' || e.key === 'Tab') {
      e.preventDefault();
      var val = dhNormalizePhone($(this).val());
      if (val.length === 10 && /^0\d{9}$/.test(val) && !dhGetPhones().includes(val)) {
        $.get('<?= site_url('khachhang/check_phone') ?>', {phone: val}, function(resp){
          if(resp.exists) {
            dhAddPhoneTag(val, 'dup');
          } else {
            dhAddPhoneTag(val, 'ok');
          }
        }, 'json');
      } else {
        if (val.length && !/^0\d{9}$/.test(val)) {
          $("#orderDupHelpAdd").removeClass('d-none').text('Số điện thoại phải đủ 10 số và bắt đầu bằng 0!');
        }
      }
      $(this).val('');
    }
  });

  // Xử lý khi blur (nếu người dùng paste số rồi click ra ngoài)
  $("#orderPhoneInputAdd").on('blur', function() {
    var val = dhNormalizePhone($(this).val());
    if (val.length === 10 && /^0\d{9}$/.test(val) && !dhGetPhones().includes(val)) {
      $.get('<?= site_url('khachhang/check_phone') ?>', {phone: val}, function(resp){
        if(resp.exists) {
          dhAddPhoneTag(val, 'dup');
        } else {
          dhAddPhoneTag(val, 'ok');
        }
      }, 'json');
      $(this).val('');
    } else if (val.length) {
      if (!/^0\d{9}$/.test(val)) {
        $("#orderDupHelpAdd").removeClass('d-none').text('Số điện thoại phải đủ 10 số và bắt đầu bằng 0!');
      }
      $(this).val('');
    }
  });

  // Khi submit form thêm khách hàng, kiểm tra nếu có badge-danger thì không cho submit
  $("#addCustomerOrderForm").off('submit').on('submit', function(e) {
    var hasDup = Object.values(dhPhoneStatus).includes('dup');
    if (hasDup) {
      $("#orderDupHelpAdd").removeClass('d-none').text('Có số điện thoại đã tồn tại. Hãy xoá hoặc thay đổi trước khi lưu.');
      e.preventDefault();
      return false;
    }
    if (dhGetPhones().length === 0) {
      $("#orderDupHelpAdd").removeClass('d-none').text('Vui lòng nhập ít nhất 1 số điện thoại!');
      e.preventDefault();
      return false;
    }
    $("#orderDupHelpAdd").addClass('d-none').text('');
    $("#btnSaveCustomerOrderAdd").prop('disabled', false);
    var $form = $(this);
    $.ajax({
      url: '<?= site_url('donhang/add_khachhang_ajax') ?>',
      type: 'POST',
      data: $form.serialize(),
      dataType: 'json',
      success: function(kh) {
        if (kh && kh.id) {
          $('#khachhang_id').val(kh.id);
          $('#khachhang_autocomplete').val(kh.ten + (kh.dienthoai ? ' (' + kh.dienthoai + ')' : ''));
          $('#addCustomerOrderModal').modal('hide');
        } else {
          $("#orderDupHelpAdd").removeClass('d-none').text('Không thể thêm khách hàng.');
        }
      },
      error: function(xhr) {
        var msg = 'Không thể thêm khách hàng. Vui lòng thử lại!';
        if (xhr.responseJSON && xhr.responseJSON.msg) msg = xhr.responseJSON.msg;
        $("#orderDupHelpAdd").removeClass('d-none').text(msg);
      }
    });
    return false;
  });

  // Đảm bảo chỉ có 1 form #addCustomerForm trong DOM
  // Gán lại sự kiện submit AJAX cho modal thêm khách hàng
  $('#addCustomerModal').on('show.bs.modal', function() {
    $('#addCustomerForm')[0].reset();
    $('#dupHelpAdd').addClass('d-none');
  });

  // Gán sự kiện submit AJAX chỉ 1 lần
  $(document).off('submit', '#addCustomerForm').on('submit', '#addCustomerForm', function(e) {
    e.preventDefault();
    var $form = $(this);
    $.ajax({
      url: '<?= site_url('donhang/add_khachhang_ajax') ?>',
      type: 'POST',
      data: $form.serialize(),
      dataType: 'json',
      success: function(kh) {
        if (kh && kh.id) {
          $('#khachhang_id').val(kh.id);
          $('#khachhang_autocomplete').val(kh.ten + (kh.dienthoai ? ' (' + kh.dienthoai + ')' : ''));
          $('#addCustomerModal').modal('hide');
        } else {
          $("#dupHelpAdd").removeClass('d-none').text('Không thể thêm khách hàng.');
        }
      },
      error: function(xhr) {
        var msg = 'Không thể thêm khách hàng. Vui lòng thử lại!';
        if (xhr.responseJSON && xhr.responseJSON.msg) msg = xhr.responseJSON.msg;
        $("#dupHelpAdd").removeClass('d-none').text(msg);
      }
    });
    return false; // Ngăn reload trang
  });

  $('#addCustomerModal').on('show.bs.modal', function() {
    $('#addCustomerForm')[0].reset();
    $('#dupHelpAdd').addClass('d-none');
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

    arrMa.forEach(function(m) {
      var sp = getSanPhamByMa(m);
      if (sp) {
        var line = sp.ten_sp + ' (' + numberFormat(sp.gia) + ')';
        tooltipList.push(line); nameList.push(line); tongGia += Number(sp.gia);
      } else {
        var err = '<span class="text-danger">Mã ['+m+'] không có!</span>';
        tooltipList.push(err); nameList.push(err); hasError = true;
      }
    });

    var tooltip = tr.find('.tooltip-combo');
    if (arrMa.length > 0 && hasError) {
      tooltip.html('Lỗi mã!').removeClass('d-none badge-info').addClass('badge-danger');
    } else if (arrMa.length > 1) {
      tooltip.html(arrMa.length + ' mã').removeClass('d-none badge-danger').addClass('badge-info');
    } else {
      tooltip.addClass('d-none');
    }

    tr.find('.ten_sp_combo').html(nameList.join('<br>'));
    tr.find('.don_gia_show').html(numberFormat(tongGia));
    tr.find('.don_gia').val(tongGia);

    var thanhTien = tongGia * soLuong;
    tr.find('.thanh_tien_show').html(numberFormat(thanhTien));
    tr.find('.thanh_tien').val(thanhTien);

    capNhatTong();
  }
  function capNhatTong() {
    var tong = 0;
    $('#tableSanPham tbody tr').each(function() {
      tong += Number($(this).find('.thanh_tien').val() || 0);
    });
    $('#tongTienView').text(numberFormat(tong));
    $('#tongtien').val(tong);
    var datra = Number($('#traTruocInput').val() || 0);
    var conno = tong - datra;
    if (conno < 0) conno = 0;
    $('#conNoView').text(numberFormat(conno));
    $('#conno').val(conno);
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
  // Nhập trả trước
  $('#traTruocInput').on('input', function(){
    capNhatTong();
  });
});
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" />
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>