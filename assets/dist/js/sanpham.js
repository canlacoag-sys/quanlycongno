$(function() {

  function fillProductForm(data, prefix) {
  $('#' + prefix + 'IdSP').val(data.id || '');
  $('#' + prefix + 'MaSP').val(data.ma_sp || '');
  $('#' + prefix + 'TenSP').val(data.ten_sp || '');
  $('#' + prefix + 'GiaSP').val(data.gia ? Number(data.gia).toLocaleString('en-US') : '');
  if (prefix === 'edit') {
    $('#coChietKhau').prop('checked', data.co_chiet_khau == 1);
    $('#coChietKhau').bootstrapSwitch('state', data.co_chiet_khau == 1, true);
  } else if (prefix === 'add') {
    $('#addCoChietKhau').prop('checked', false);
  }
}

function resetProductForm(prefix) {
  $('#' + prefix + 'IdSP').val('');
  $('#' + prefix + 'MaSP').val('');
  $('#' + prefix + 'TenSP').val('');
  $('#' + prefix + 'GiaSP').val('');
  if (prefix === 'edit') {
    $('#coChietKhau').prop('checked', false);
    $('#coChietKhau').bootstrapSwitch('state', false, true);
  } else if (prefix === 'add') {
    $('#addCoChietKhau').prop('checked', false);
  }
  $('#' + (prefix === 'add' ? 'dupMaSPHelpAdd' : 'dupMaSPHelpEdit')).addClass('d-none');
  $('#' + (prefix === 'add' ? 'btnSaveProductAdd' : 'btnSaveProductEdit')).prop('disabled', false);
}

  // Khi đổi radio chiết khấu, submit form (KHÔNG xoá keyword)
  $('input[name="chietkhau"]').on('change', function() {
    $('#formSearchProduct').submit();
  });

  // Mở modal Thêm sản phẩm
  $('#btnAddProduct').on('click', function() {
    resetProductForm('add');
    $('#addProductModal').modal('show');
  });

  // Submit Thêm sản phẩm
  $('#addProductForm').on('submit', function(e) {
    e.preventDefault();
    let ma_sp = $('#addMaSP').val().trim();
    let ten_sp = $('#addTenSP').val().trim();
    let gia = $('#addGiaSP').val().trim();
    let co_chiet_khau = $('#addCoChietKhau').is(':checked') ? 1 : 0;
    let $btnSave = $('#btnSaveProductAdd');
    let $dupHelp = $('#dupMaSPHelpAdd');

    $.get(APP.routes.check_ma_sp, {ma_sp: ma_sp}, function(res) {
      if (res.exists) {
        $dupHelp.removeClass('d-none');
        $btnSave.prop('disabled', true);
        return;
      } else {
        $dupHelp.addClass('d-none');
        $btnSave.prop('disabled', false);
        // Gửi AJAX thêm sản phẩm
        $.post(APP.routes.ajax_add_product, {ma_sp, ten_sp, gia, co_chiet_khau}, function(res) {
          if(res.success) {
            location.reload();
          } else {
            alert(res.msg || 'Có lỗi xảy ra!');
          }
        }, 'json');
      }
    }, 'json');
  });

  // Mở modal Sửa sản phẩm
  $('.btn-edit-product').on('click', function() {
    let id = $(this).data('id');
    $.get(APP.routes.sanpham_get + '/' + id, function(res) {
      if (!res || !res.success) {
        alert(res && res.msg ? res.msg : 'Không thể tải dữ liệu sản phẩm');
        return;
      }
      fillProductForm(res.data, 'edit');
      $('#editProductModal').modal('show');
    }, 'json');
  });

  // Submit Sửa sản phẩm
  $('#editProductForm').on('submit', function(e) {
    e.preventDefault();
    let id = $('#editIdSP').val();
    let ma_sp = $('#editMaSP').val().trim();
    let ten_sp = $('#editTenSP').val().trim();
    let gia = $('#editGiaSP').val().trim();
    let co_chiet_khau = $('#coChietKhau').is(':checked') ? 1 : 0;
    $.post(APP.routes.ajax_edit_product, {id, ma_sp, ten_sp, gia, co_chiet_khau}, function(res) {
      if(res.success) {
        location.reload();
      } else {
        alert(res.msg || 'Có lỗi xảy ra!');
      }
    }, 'json');
  });

  // Mở modal Xoá sản phẩm
  $('.btn-delete-product').on('click', function() {
    let row = $(this).closest('tr');
    let id = $(this).data('id');
    $('#delMaSP').text(row.find('.col-ma-sp').text().trim());
    $('#delTenSP').text(row.find('.col-ten-sp').text().trim());
    $('#btnConfirmDeleteProduct').data('id', id);
    $('#confirmDeleteProductModal').modal('show');
  });

  // Xác nhận xoá sản phẩm
  $('#btnConfirmDeleteProduct').on('click', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    $.post(APP.routes.ajax_delete_product, {id: id}, function(res) {
      if(res.success) {
        location.reload();
      } else {
        alert(res.msg || 'Không xoá được sản phẩm!');
      }
    }, 'json');
  });

  // Kiểm tra trùng mã sản phẩm khi nhập (chỉ dùng 'input' để check realtime)
  $('#addMaSP').on('input', function() {
    let ma_sp = $(this).val().trim();
    let $btnSave = $('#btnSaveProductAdd');
    let $dupHelp = $('#dupMaSPHelpAdd');
    if (!ma_sp) {
      $dupHelp.addClass('d-none');
      $btnSave.prop('disabled', false);
      return;
    }
    $.get(APP.routes.check_ma_sp, {ma_sp: ma_sp}, function(res) {
      if (res.exists) {
        $dupHelp.removeClass('d-none');
        $btnSave.prop('disabled', true);
      } else {
        $dupHelp.addClass('d-none');
        $btnSave.prop('disabled', false);
      }
    }, 'json');
  });

  // Hiển thị label động cho checkbox chiết khấu khi bật/tắt (hỗ trợ Bootstrap Switch)
  function updateChietKhauLabel($checkbox, $label) {
    if ($checkbox.is(':checked')) {
      $label.text('Bánh có chiết khấu');
    } else {
      $label.text('Bánh không có chiết khấu');
    }
  }

  // Khởi tạo lại label khi mở modal Thêm sản phẩm
  $('#addProductModal').on('shown.bs.modal', function() {
    var $ckAdd = $('#addCoChietKhau');
    var $lbAdd = $('label[for="addCoChietKhau"]');
    updateChietKhauLabel($ckAdd, $lbAdd);
    // Đảm bảo sự kiện không bị gán nhiều lần
    $ckAdd.off('switchChange.bootstrapSwitch._label').on('switchChange.bootstrapSwitch._label', function() {
      updateChietKhauLabel($ckAdd, $lbAdd);
    });
  });

  // Khởi tạo lại label khi mở modal Sửa sản phẩm
  $('#editProductModal').on('shown.bs.modal', function() {
    var $ckEdit = $('#coChietKhau');
    var $lbEdit = $('label[for="coChietKhau"]');
    updateChietKhauLabel($ckEdit, $lbEdit);
    $ckEdit.off('switchChange.bootstrapSwitch._label').on('switchChange.bootstrapSwitch._label', function() {
      updateChietKhauLabel($ckEdit, $lbEdit);
    });
  });
});

$(function() {
  $("input[data-bootstrap-switch]").each(function(){
    $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });
});


$('#addProductModal').on('shown.bs.modal', function () {
  $("input[data-bootstrap-switch]").bootstrapSwitch('destroy'); // Xóa switch cũ nếu có
  $("input[data-bootstrap-switch]").bootstrapSwitch();
});

$('#addGiaSP').on('input', function() {
  let val = $(this).val().replace(/,/g, '');
  if (!isNaN(val) && val !== '') {
    $(this).val(Number(val).toLocaleString('en-US'));
  }
});
$('#editGiaSP').on('input', function() {
  let val = $(this).val().replace(/,/g, '');
  if (!isNaN(val) && val !== '') {
    $(this).val(Number(val).toLocaleString('en-US'));
  }
});

// Khi submit, loại bỏ dấu phẩy
$('#addProductForm').on('submit', function() {
  let gia = $('#addGiaSP').val().replace(/,/g, '');
  $('#addGiaSP').val(gia);
  // Tiếp tục submit...
});

// Khi submit, loại bỏ dấu phẩy
$('#editProductForm').on('submit', function() {
  let gia = $('#editGiaSP').val().replace(/,/g, '');
  $('#editGiaSP').val(gia);
  // Tiếp tục submit...
});