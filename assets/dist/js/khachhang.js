/* ================== Toggle chevron ở bảng ================== */
$(document).on('click', '.toggle-row', function () {
  var $i = $(this).find('i');
  setTimeout(function () { $i.toggleClass('fa-chevron-down fa-chevron-up'); }, 150);
});

/* ================== MODULE Tags số điện thoại ================== */
(function () {
  function normalizePhone(raw) {
    raw = (raw || '').replace(/[^\d\+]/g, '').trim();
    if (!raw) return '';
    if (raw.indexOf('+84') === 0) raw = '0' + raw.slice(3);
    else if (raw.indexOf('84') === 0 && raw.length >= 10) raw = '0' + raw.slice(2);
    return raw;
  }
  function onlyDigits(s) { return (s || '').replace(/\D/g, ''); }

  var $wrap   = $('#phoneTags');
  var $input  = $('#phoneInput');
  var $hidden = $('#phonesHidden');
  var $save   = $('#btnSaveCustomer');
  var $dupMsg = $('#dupHelp');
  var phones  = [];

  window.PhoneTag = {
    resetFromCSV: function (csv) {
      phones = [];
      $wrap.find('.badge').remove();
      $input.val('');
      $hidden.val(csv || '');
      initFromHidden();
      updateSaveState();
    }
  };

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
    return $('<span class="badge ' + color + ' d-inline-flex align-items-center px-2 py-1">' +
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
    return $.getJSON(window.APP.routes.check_phone, {
      phone: number,
      exclude_id: excludeId || ''
    });
  }

  function handleCandidate(candidate) {
    var n = normalizePhone(candidate);
    if (!n) return;
    var digits = onlyDigits(n);
    if (digits.length !== 10 || n[0] !== '0') return;
    if (phones.indexOf(n) !== -1) { $input.val(''); return; }

    var excludeId = $('#editId').val() || '';
    checkDuplicate(n, excludeId).done(function (res) {
      var status = (res && res.exists) ? 'dup' : 'ok';
      addPhoneTagSilent(n, status);
      $input.val('');
    }).fail(function () {
      addPhoneTagSilent(n, 'ok');
      $input.val('');
    });
  }

  $input.on('input', function () {
    var v = $input.val();
    if (/[,\s;]/.test(v)) {
      v.split(/[,\s;]+/).forEach(function (piece) {
        var d = onlyDigits(piece);
        if (d.length === 10) handleCandidate(piece);
      });
      $input.val('');
      return;
    }
    var d = onlyDigits(v);
    if (d.length === 10) handleCandidate(v);
  });

  $input.on('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      var d = onlyDigits($input.val());
      if (d.length === 10) handleCandidate($input.val());
    }
    if (e.key === 'Backspace' && !$input.val() && phones.length) {
      removePhone(phones[phones.length - 1]);
    }
  });

  $wrap.on('click', '.remove-phone', function () {
    var number = $(this).closest('.badge').data('number');
    removePhone(number);
  });

  function initFromHidden() {
    var csv = ($hidden.val() || '').trim();
    if (!csv) return;
    csv.split(',').map(function (p) { return normalizePhone(p); })
      .filter(Boolean)
      .forEach(function (p) { addPhoneTagSilent(p, 'ok'); });
  }
  initFromHidden();
})();

/* ================== Mở modal Sửa & nạp dữ liệu qua AJAX ================== */
$(document).on('click', '.btn-edit', function (e) {
  e.preventDefault();
  var id = $(this).data('id') || $(this).attr('data-id');
  if (!id) return;

  // reset modal trước
  $('#editCustomerModal').find('form')[0].reset();
  $('#editCustomerModal').find('.text-danger, .invalid-feedback').remove();
  $('#btnSaveCustomerEdit').prop('disabled', false);

  $.get(window.APP.routes.khachhang_get + '/' + id, function(res) {
    if (!res || !res.success) {
      alert(res?.msg || 'Không thể tải dữ liệu khách hàng');
      return;
    }
    var d = res.data;
    $('#editCustomerId').val(d.id || '');
    $('#editTen').val(d.ten || '');
    $('#editDienThoai').val(d.dienthoai || '');
    $('#editDiaChi').val(d.diachi || '');
    if (window.PhoneTag && window.PhoneTag.resetFromCSV) {
      window.PhoneTag.resetFromCSV(d.dienthoai || '');
    }
    $('#editCustomerModal').modal('show');
  }, 'json').fail(function() {
    alert('Lỗi kết nối, thử lại.');
  });
});

/* ================== Xoá: nạp tên/điện thoại qua AJAX ================== */
$(document).on('click', '.btn-delete', function (e) {
  e.preventDefault();
  var id = $(this).data('id') || $(this).attr('data-id');
  $('#confirmDeleteModal').find('#deleteItemId').val(id);
  $('#confirmDeleteModal').find('.delete-name').text('');
  $('#confirmDeleteModal').find('.delete-phone').text('');
  $('#confirmDeleteModal').modal('show');

  if (!id) return;
  $.get(window.APP.routes.khachhang_get + '/' + id, function(res) {
    if (res && res.success) {
      $('#confirmDeleteModal').find('.delete-name').text(res.data.ten || '');
      $('#confirmDeleteModal').find('.delete-phone').text(res.data.dienthoai || '');
    }
  }, 'json');
});

/* ================== Khi mở modal Thêm: reset form ================== */
$(document).on('click', '#btnOpenAdd', function () {
  $('#addCustomerModal').find('form')[0].reset();
  $('#addCustomerModal').find('.text-danger, .invalid-feedback').remove();
  $('#btnSaveCustomerAdd').prop('disabled', false);
  if (window.PhoneTag && window.PhoneTag.resetFromCSV) {
    window.PhoneTag.resetFromCSV('');
  }
  $('#addCustomerModal').modal('show');
});

/* ================== Validate nhẹ trước khi submit ================== */
$('#editCustomerForm').on('submit', function () {
  var name = $('#editTen').val().trim();
  if (!name) { $('#editTen').focus(); return false; }
  if ($('#btnSaveCustomerEdit').prop('disabled')) return false;
  return true;
});

// ================== MODULE Tags số điện thoại cho modal Thêm ==================
(function () {
  function normalizePhone(raw) {
    raw = (raw || '').replace(/[^\d\+]/g, '').trim();
    if (!raw) return '';
    if (raw.indexOf('+84') === 0) raw = '0' + raw.slice(3);
    else if (raw.indexOf('84') === 0 && raw.length >= 10) raw = '0' + raw.slice(2);
    return raw;
  }
  function onlyDigits(s) { return (s || '').replace(/\D/g, ''); }

  var $wrap   = $('#phoneTagsAdd');
  var $input  = $('#phoneInputAdd');
  var $hidden = $('#phonesHiddenAdd');
  var $save   = $('#btnSaveCustomerAdd');
  var $dupMsg = $('#dupHelpAdd');
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
    return $('<span class="badge ' + color + ' d-inline-flex align-items-center px-2 py-1">' +
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

  function checkDuplicate(number) {
    return $.getJSON(window.APP.routes.check_phone, { phone: number });
  }

  function handleCandidate(candidate) {
    var n = normalizePhone(candidate);
    if (!n) return;
    var digits = onlyDigits(n);
    if (digits.length !== 10 || n[0] !== '0') return;
    if (phones.indexOf(n) !== -1) { $input.val(''); return; }

    checkDuplicate(n).done(function (res) {
      var status = (res && res.exists) ? 'dup' : 'ok';
      addPhoneTagSilent(n, status);
      $input.val('');
    }).fail(function () {
      addPhoneTagSilent(n, 'ok');
      $input.val('');
    });
  }

  $input.on('input', function () {
    var v = $input.val();
    if (/[,\s;]/.test(v)) {
      v.split(/[,\s;]+/).forEach(function (piece) {
        var d = onlyDigits(piece);
        if (d.length === 10) handleCandidate(piece);
      });
      $input.val('');
      return;
    }
    var d = onlyDigits(v);
    if (d.length === 10) handleCandidate(v);
  });

  $input.on('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      var d = onlyDigits($input.val());
      if (d.length === 10) handleCandidate($input.val());
    }
    if (e.key === 'Backspace' && !$input.val() && phones.length) {
      removePhone(phones[phones.length - 1]);
    }
  });

  $wrap.on('click', '.remove-phone', function () {
    var number = $(this).closest('.badge').data('number');
    removePhone(number);
  });

  // Reset khi mở modal Thêm
  $('#addCustomerModal').on('show.bs.modal', function () {
    phones = [];
    $wrap.find('.badge').remove();
    $input.val('');
    $hidden.val('');
    updateSaveState();
  });
})();