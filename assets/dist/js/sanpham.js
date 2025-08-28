$(function() {
  // Mở modal Thêm sản phẩm
  $('#btnAddProduct').on('click', function() {
    $('#addProductForm')[0].reset();
    $('#addProductModal').modal('show');
  });

  // Submit Thêm sản phẩm
  $('#addProductForm').on('submit', function(e) {
    e.preventDefault();
    let form = $(this);
    let data = {
      ma_sp: $('#addMaSP').val(),
      ten_sp: $('#addTenSP').val(),
      gia: $('#addGiaSP').val()
    };
    $.post(APP.routes.ajax_add_product, data, function(res) {
      if(res.success) {
        location.reload();
      } else {
        alert(res.msg || 'Có lỗi xảy ra!');
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
    $('#editProductModal').modal('show');
  });

  // Submit Sửa sản phẩm
  $('#editProductForm').on('submit', function(e) {
    e.preventDefault();
    let id = $('#editIdSP').val();
    let data = {
      id: id,
      ma_sp: $('#editMaSP').val(),
      ten_sp: $('#editTenSP').val(),
      gia: $('#editGiaSP').val()
    };
    $.post(APP.routes.ajax_edit_product, data, function(res) {
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

  // Khi mở modal thêm sản phẩm, reset trạng thái cảnh báo và nút lưu
  $('#btnAddProduct').on('click', function() {
    $('#addProductForm')[0].reset();
    $('#dupMaSPHelpAdd').addClass('d-none');
    $('#btnSaveProductAdd').prop('disabled', false);
    $('#addProductModal').modal('show');
  });

  // Khi submit form thêm sản phẩm, kiểm tra lại lần nữa
  $('#addProductForm').on('submit', function(e) {
    e.preventDefault();
    let ma_sp = $('#addMaSP').val().trim();
    let ten_sp = $('#addTenSP').val().trim();
    let gia = $('#addGiaSP').val().trim();
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
        // Gửi AJAX thêm sản phẩm như cũ
        $.post(APP.routes.ajax_add_product, {ma_sp, ten_sp, gia}, function(res) {
          if(res.success) {
            location.reload();
          } else {
            alert(res.msg || 'Có lỗi xảy ra!');
          }
        }, 'json');
      }
    }, 'json');
  });

});