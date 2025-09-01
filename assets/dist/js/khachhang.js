$(function() {
  // ================== Phone Tag Module ==================
  function normalizePhone(raw) {
    raw = (raw || '').replace(/[^\d\+]/g, '').trim();
    if (!raw) return '';
    if (raw.indexOf('+84') === 0) raw = '0' + raw.slice(3);
    else if (raw.indexOf('84') === 0 && raw.length >= 10) raw = '0' + raw.slice(2);
    return raw;
  }
  function onlyDigits(s) { return (s || '').replace(/\D/g, ''); }
  function PhoneTagModule(cfg) {
    var $wrap   = $(cfg.wrap);
    var $input  = $(cfg.input);
    var $hidden = $(cfg.hidden);
    var $save   = $(cfg.save);
    var $dupMsg = $(cfg.dupMsg);
    var phones  = [];
    var phoneStatus = {}; // {phone: 'ok'|'dup'}

    function updateSaveState() {
      var hasDup = Object.values(phoneStatus).includes('dup');
      $save.prop('disabled', hasDup);
      if (hasDup) $dupMsg.removeClass('d-none'); else $dupMsg.addClass('d-none');
    }

    function syncHidden() {
      $hidden.val(phones.join(','));
      updateSaveState();
    }

    function renderTags() {
      $wrap.find('.badge').remove();
      phones.forEach(function(phone) {
        var status = phoneStatus[phone] || 'ok';
        var badge = $('<span class="badge align-middle"></span>')
          .addClass(status === 'dup' ? 'badge-danger' : 'badge-primary')
          .css({'font-size':'1rem','display':'flex','align-items':'center','margin-right':'0.25rem','margin-bottom':'0'})
          .text(phone);
        var removeBtn = $('<button type="button" class="btn btn-sm btn-light ml-1 p-0 px-1 remove-phone" aria-label="Xoá" title="Xoá"><i class="fas fa-times"></i></button>');
        removeBtn.click(function() {
          var arr = phones.filter(function(p){return p!==phone;});
          delete phoneStatus[phone];
          phones = arr;
          $hidden.val(arr.join(','));
          renderTags();
          updateSaveState();
        });
        badge.append(removeBtn);
        $wrap.append(badge);
      });
    }

    function addPhoneTag(val, status) {
      if (!val || phones.includes(val)) return;
      phones.push(val);
      phoneStatus[val] = status;
      $hidden.val(phones.join(','));
      renderTags();
      updateSaveState();
    }

    // Check phone ngay khi nhập đủ 10 số
    $input.on('input', function() {
      var val = normalizePhone($(this).val());
      if (val.length === 10 && /^0\d{9}$/.test(val) && !phones.includes(val)) {
        $.get(window.APP.routes.check_phone, {phone: val}, function(resp){
          addPhoneTag(val, resp.exists ? 'dup' : 'ok');
        }, 'json');
        $(this).val('');
      } else if (val.length > 10) {
        $(this).val('');
      }
    });

    // Xử lý khi nhấn Enter, Tab, dấu phẩy, dấu cách
    $input.on('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ',' || e.key === ' ' || e.key === 'Tab') {
        e.preventDefault();
        var val = normalizePhone($(this).val());
        if (val.length === 10 && /^0\d{9}$/.test(val) && !phones.includes(val)) {
          $.get(window.APP.routes.check_phone, {phone: val}, function(resp){
            addPhoneTag(val, resp.exists ? 'dup' : 'ok');
          }, 'json');
        } else {
          if (val.length && !/^0\d{9}$/.test(val)) {
            $dupMsg.removeClass('d-none').text('Số điện thoại phải đủ 10 số và bắt đầu bằng 0!');
          }
        }
        $(this).val('');
      }
    });

    // Xử lý khi blur (nếu người dùng paste số rồi click ra ngoài)
    $input.on('blur', function() {
      var val = normalizePhone($(this).val());
      if (val.length === 10 && /^0\d{9}$/.test(val) && !phones.includes(val)) {
        $.get(window.APP.routes.check_phone, {phone: val}, function(resp){
          addPhoneTag(val, resp.exists ? 'dup' : 'ok');
        }, 'json');
        $(this).val('');
      } else if (val.length) {
        if (!/^0\d{9}$/.test(val)) {
          $dupMsg.removeClass('d-none').text('Số điện thoại phải đủ 10 số và bắt đầu bằng 0!');
        }
        $(this).val('');
      }
    });

    function resetFromCSV(csv) {
      phones = [];
      phoneStatus = {};
      $wrap.find('.badge').remove();
      $input.val('');
      $hidden.val(csv || '');
      var raw = (csv || '').trim();
      if (!raw) { updateSaveState(); return; }
      raw.split(',').map(normalizePhone).filter(Boolean).forEach(function (p) { addPhoneTag(p, 'ok'); });
      updateSaveState();
    }

    return {
      resetFromCSV: resetFromCSV,
      syncHidden: syncHidden,
      getPhones: function () { return phones.slice(); }
    };
  }

  // Khởi tạo cho Add & Edit
  var phoneTagAdd = PhoneTagModule({
    wrap:   '#phoneTagsAdd',
    input:  '#phoneInputAdd',
    hidden: '#phonesHiddenAdd',
    save:   $('#btnSaveCustomerAdd'),
    dupMsg: $('#dupHelpAdd')
  });

  window.phoneTagEdit = PhoneTagModule({
    wrap:   '#phoneTags',
    input:  '#phoneInput',
    hidden: '#phonesHidden',
    save:   $('#btnSaveCustomerEdit'),
    dupMsg: $('#dupHelpEdit'),
    excludeId: '#editId'
  });

  // ================== Hàm dùng chung fill/reset form ==================
  function fillCustomerForm(data, prefix) {
    $('#' + prefix + 'Id').val(data.id || '');
    $('#' + prefix + 'Name').val(data.ten || '');
    $('#' + prefix + 'Address').val(data.diachi || '');
    if (prefix === 'edit' && window.phoneTagEdit) {
      window.phoneTagEdit.resetFromCSV(data.dienthoai || '');
    }
  }

  function resetCustomerForm(prefix) {
    $('#' + prefix + 'Id').val('');
    $('#' + prefix + 'Name').val('');
    $('#' + prefix + 'Address').val('');
    $('#' + (prefix === 'add' ? 'dupHelpAdd' : 'dupHelpEdit')).addClass('d-none');
    $('#' + (prefix === 'add' ? 'btnSaveCustomerAdd' : 'btnSaveCustomerEdit')).prop('disabled', false);
    if (window['phoneTag' + prefix.charAt(0).toUpperCase() + prefix.slice(1)]) {
      window['phoneTag' + prefix.charAt(0).toUpperCase() + prefix.slice(1)].resetFromCSV('');
    }
  }

  // ================== Mở modal Thêm khách hàng ==================
  $('#btnAddCustomer').on('click', function() {
    resetCustomerForm('add');
    $('#addCustomerModal').modal('show');
  });

  // ================== Submit Thêm khách hàng ==================
  $('#addCustomerForm').on('submit', function(e) {
    e.preventDefault();
    phoneTagAdd.syncHidden();
    var $btn = $('#btnSaveCustomerAdd');
    $btn.prop('disabled', true);
    $.post(
      window.APP.routes.khachhang_ajax_add,
      $(this).serialize(),
      function(res) {
        if(res.success) {
          location.reload();
        } else {
          alert(res.msg || 'Có lỗi xảy ra!');
          $btn.prop('disabled', false);
        }
      },
      'json'
    ).fail(function() {
      alert('Lỗi kết nối hoặc server.');
      $btn.prop('disabled', false);
    });
  });

  // ================== Mở modal Sửa khách hàng ==================
  $('.btn-edit-customer').on('click', function() {
    var id = $(this).data('id');
    if (!id || isNaN(id) || id <= 0) {
      alert('ID khách hàng không hợp lệ!');
      return;
    }
    $.get(window.APP.routes.khachhang_get, {id: id}, function(res) {
      if (!res || !res.success) {
        alert(res && res.msg ? res.msg : 'Không thể tải dữ liệu khách hàng');
        return;
      }
      fillCustomerForm(res.data, 'edit');
      $('#editCustomerModal').modal('show');
    }, 'json');
  });

  // ================== Submit Sửa khách hàng ==================
  $('#editCustomerForm').on('submit', function(e) {
    e.preventDefault();
    phoneTagEdit.syncHidden();
    var $btn = $('#btnSaveCustomerEdit');
    $btn.prop('disabled', true);
    $.post(
      window.APP.routes.khachhang_ajax_edit,
      $(this).serialize(),
      function(res) {
        if(res.success) {
          location.reload();
        } else {
          alert(res.msg || 'Có lỗi xảy ra!');
          $btn.prop('disabled', false);
        }
      },
      'json'
    ).fail(function() {
      alert('Lỗi kết nối hoặc server.');
      $btn.prop('disabled', false);
    });
  });

  // ================== Mở modal Xoá khách hàng ==================
  $('.btn-delete-customer').on('click', function() {
    var id = $(this).data('id');
    $.get(window.APP.routes.khachhang_get, {id: id}, function(res) {
      if (res && res.success) {
        $('#delCustomerName').text(res.data.ten || '');
        $('#delCustomerPhone').text(res.data.dienthoai || '');
        $('#delCustomerAddress').text(res.data.diachi || '');
        $('#btnConfirmDeleteCustomer').data('id', id);
        $('#confirmDeleteCustomerModal').modal('show');
      } else {
        alert(res.msg || 'Không lấy được thông tin khách hàng!');
      }
    }, 'json');
  });

  // ================== Xác nhận xoá khách hàng ==================
  $('#btnConfirmDeleteCustomer').on('click', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.post(window.APP.routes.khachhang_ajax_delete, {id: id}, function(res) {
      if(res.success) {
        location.reload();
      } else {
        alert(res.msg || 'Không xoá được khách hàng!');
      }
    }, 'json');
  });
});