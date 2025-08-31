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

    function updateSaveState() {
      var hasDup = $wrap.find('.badge').filter(function () {
        return $(this).data('status') === 'dup';
      }).length > 0;
      $save.prop('disabled', hasDup);
      if (hasDup) $dupMsg.removeClass('d-none'); else $dupMsg.addClass('d-none');
    }

    function syncHidden() {
      $hidden.val(phones.join(','));
      updateSaveState();
    }

    function tagTemplate(number, status) {
      var color = (status === 'dup') ? 'badge-danger' : 'badge-primary';
      var title = (status === 'dup') ? 'Bị trùng trong hệ thống' : 'Hợp lệ';
      return $('<span class="badge ' + color + ' d-inline-flex align-items-center px-2 py-1 mr-1 mb-1">' +
               '<span class="mr-2">' + number + '</span>' +
               '<button type="button" class="btn btn-sm btn-light ml-1 p-0 px-1 remove-phone" aria-label="Xoá" title="Xoá">' +
                 '<i class="fas fa-times"></i>' +
               '</button>' +
             '</span>')
        .attr('title', title)
        .data('number', number)
        .data('status', status);
    }

    function addPhoneTagSilent(number, status) {
      if (!number) return;
      if (phones.indexOf(number) !== -1) return;
      phones.push(number);
      tagTemplate(number, status || 'ok').insertBefore($input);
      syncHidden();
    }

    function removePhone(number) {
      var idx = phones.indexOf(number);
      if (idx !== -1) phones.splice(idx, 1);
      $wrap.find('.badge').filter(function () {
        return $(this).data('number') === number;
      }).remove();
      syncHidden();
    }

    function checkDuplicate(number, excludeId) {
      var url = window.APP.routes.check_phone;
      var data = { phone: number };
      if (excludeId) data.exclude_id = excludeId;
      return $.getJSON(url, data);
    }

    function handleCandidate(candidate, excludeId) {
      var n = normalizePhone(candidate);
      if (!n) return;
      var digits = onlyDigits(n);
      if (digits.length !== 10 || n[0] !== '0') { $input.val(''); return; }
      if (phones.indexOf(n) !== -1) { $input.val(''); return; }

      checkDuplicate(n, excludeId).done(function (res) {
        var status = (res && res.exists) ? 'dup' : 'ok';
        addPhoneTagSilent(n, status);
        $input.val('');
      }).fail(function () {
        addPhoneTagSilent(n, 'ok');
        $input.val('');
      });
    }

    // Events
    $input.on('input', function () {
      var v = $input.val();
      if (/[,\s;]/.test(v)) {
        v.split(/[,\s;]+/).forEach(function (piece) {
          var d = onlyDigits(piece);
          if (d.length === 10) handleCandidate(piece, cfg.excludeId && $(cfg.excludeId).val());
        });
        $input.val('');
        return;
      }
      var d = onlyDigits(v);
      if (d.length === 10) handleCandidate(v, cfg.excludeId && $(cfg.excludeId).val());
    });

    $input.on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        var d = onlyDigits($input.val());
        if (d.length === 10) handleCandidate($input.val(), cfg.excludeId && $(cfg.excludeId).val());
      }
      if (e.key === 'Backspace' && !$input.val() && phones.length) {
        removePhone(phones[phones.length - 1]);
      }
    });

    $wrap.on('click', '.remove-phone', function () {
      var number = $(this).closest('.badge').data('number');
      if (number) removePhone(number);
    });

    function resetFromCSV(csv) {
      phones = [];
      $wrap.find('.badge').remove();
      $input.val('');
      $hidden.val(csv || '');
      var raw = (csv || '').trim();
      if (!raw) { updateSaveState(); return; }
      raw.split(',').map(normalizePhone).filter(Boolean).forEach(function (p) { addPhoneTagSilent(p, 'ok'); });
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
    console.log('ID khách hàng:', id); // kiểm tra giá trị id
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
    // Gọi AJAX lấy thông tin khách hàng
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

  // ================== Kiểm tra trùng số điện thoại realtime khi nhập tag ==================
  // Đã tích hợp trong PhoneTagModule (status 'dup' sẽ disable nút lưu và hiện cảnh báo)
});