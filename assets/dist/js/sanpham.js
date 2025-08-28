$('.btn-edit-product').on('click', function() {
  let id = $(this).data('id');
  $.get(APP.routes.sanpham_get + '/' + id, function(res) {
    if (!res || !res.success) {
      alert(res && res.msg ? res.msg : 'Không thể tải dữ liệu sản phẩm');
      return;
    }
    let d = res.data;
    $('#editIdSP').val(d.id);
    $('#editMaSP').val(d.ma_sp);
    $('#editTenSP').val(d.ten_sp);
    $('#editGiaSP').val(d.gia);
    $('#coChietKhau').prop('checked', d.co_chiet_khau == 1);
    $('#editProductModal').modal('show');
  }, 'json');
});

$(function() {
  // Mở modal Thêm sản phẩm
  $('#btnAddProduct').on('click', function() {
    $('#addProductForm')[0].reset();
    $('#addCoChietKhau').prop('checked', false);
    $('#dupMaSPHelpAdd').addClass('d-none');
    $('#btnSaveProductAdd').prop('disabled', false);
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
    let row = $(this).closest('tr');
    $('#editIdSP').val($(this).data('id'));
    $('#editMaSP').val(row.find('.col-ma-sp').text().trim());
    $('#editTenSP').val(row.find('.col-ten-sp').text().trim());
    $('#editGiaSP').val(row.find('.col-gia-sp').data('gia'));
    $('#coChietKhau').prop('checked', $(this).data('chietkhau') == 1);
    $('#editProductModal').modal('show');
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
});