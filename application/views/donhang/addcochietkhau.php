<?php $this->load->view('khachhang/add'); ?>
<?php $this->load->helper('money'); ?>
<div class="content-wrapper">

<section class="content">
    <div class="container-fluid">
        <br />
      <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" action="<?= site_url('donhang/addcochietkhau'); ?>" id="formDonHang">
            <input type="hidden" name="co_chiet_khau" value="1">
            <input type="hidden" name="tongtien" id="tongtien" value="0">
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
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <input type="text" name="ma_sp[]" class="form-control ma_sp_combo" style="min-width:120px;width:250px;" autocomplete="off" placeholder="VD: 5,7,24">
                        <span class="badge badge-info d-none tooltip-combo ml-2" style="cursor:pointer;min-width:30px;"></span>
                      </div>
                    </td>
                    <td>
                      <div class="ten_sp_combo small text-dark" style="max-width:300px;white-space:pre-line;overflow-x:auto;"></div>
                    </td>
                    <td>
                      <div class="font-weight-bold text-right don_gia_show" style="font-size:1.15em"><?= money_vnd(0) ?></div>
                      <input type="hidden" name="don_gia[]" class="don_gia" readonly>
                    </td>
                    <td>
                      <input type="number" name="so_luong[]" class="form-control text-center so_luong" min="1" value="1" required style="width:70px;">
                    </td>
                    <td>
                      <div class="font-weight-bold text-right thanh_tien_show" style="font-size:1.25em"><?= money_vnd(0) ?></div>
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
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label font-weight-bold" for="giaoHangInput">Người giao hàng</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="giaoHangInput" name="giao_hang" placeholder="Thông tin người giao hàng">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label font-weight-bold" for="nguoiNhanInput">Người nhận</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nguoiNhanInput" name="nguoi_nhan" placeholder="Tên người nhận hàng">
                  </div>
                </div>
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
                    <td class="font-weight-bold text-right tong-tien-label">Tổng tiền:</td>
                    <td class="text-right tong-tien-value" style="width:100px;" id="tongTienView">
                      <span><?= money_vnd(0) ?></span>
                    </td>
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
$(function(){
  $('#formDonHang').on('submit', function(e){
    e.preventDefault();
    var $form = $(this);
    $.post($form.attr('action'), $form.serialize(), function(resp){
      if (resp && resp.id) {
        window.open('/donhang/pos/' + resp.id, '_blank');
        window.location.href = '/donhang/addcochietkhau';
      } else {
        alert('Lưu đơn thất bại!');
      }
    }, 'json');
  });
});
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
    // if (dhGetPhones().length === 0) {
    //  $("#orderDupHelpAdd").removeClass('d-none').text('Vui lòng nhập ít nhất 1 số điện thoại!');
    //  e.preventDefault();
    //  return false;
    //}
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
  // Lọc chỉ lấy sản phẩm có chiết khấu
  function getSanPhamByMa(ma) {
    ma = ma.trim();
    for (let i = 0; i < dsSanPham.length; i++) {
      if (dsSanPham[i].ma_sp == ma && dsSanPham[i].co_chiet_khau == 1) return dsSanPham[i];
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
      var sp = dsSanPham.find(x => x.ma_sp == m && x.co_chiet_khau == 1);
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
      var val = Number($(this).find('.thanh_tien').val() || 0);
      tong += val;
    });
    $('#tongTienView').html('<span>' + numberFormat(tong) + ' <span class="donvi">đ</span></span>');
    $('#tongtien').val(tong);
  }

  // Đảm bảo khi có lỗi ở bất kỳ dòng nào thì không cho submit
  function checkAllRowsForError() {
    var hasError = false;
    $('#tableSanPham tbody tr').each(function() {
      var tooltip = $(this).find('.tooltip-combo');
      if (tooltip.hasClass('badge-danger') && !tooltip.hasClass('d-none')) {
        hasError = true;
      }
    });
    $('#formDonHang button[type=submit]').prop('disabled', hasError);
  }

  // Gọi lại checkAllRowsForError mỗi khi thay đổi dòng
  $('#tableSanPham').on('input', '.ma_sp_combo, .so_luong', function(){
    updateCombo($(this).closest('tr'));
    checkAllRowsForError();
  });
  $('#btnThemDong').off('click').on('click', function(){
    var dong = $('#tableSanPham tbody tr:first').clone();
    dong.find('input').val('');
    dong.find('.so_luong').val(1);
    dong.find('.tooltip-combo').html('').addClass('d-none badge-info').removeClass('badge-danger');
    dong.find('.ten_sp_combo,.don_gia_show,.thanh_tien_show').html('');
    $('#tableSanPham tbody').append(dong);
    checkAllRowsForError();
  });
  $('#tableSanPham').on('click', '.btnXoaDong', function(){
    if($('#tableSanPham tbody tr').length > 1) {
      $(this).closest('tr').remove();
      capNhatTong();
      checkAllRowsForError();
    }
  });
  // Nhập trả trước
  $('#traTruocInput').on('input', function(){
    capNhatTong();
  });
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
});
</script>