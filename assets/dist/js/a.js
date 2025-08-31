/* ================== Khách hàng - Phone tag module chung + AJAX add/update ================== */
(function () {
  'use strict';

  // Helpers
  function normalizePhone(raw) {
    raw = (raw || '').replace(/[^\d\+]/g, '').trim();
    if (!raw) return '';
    if (raw.indexOf('+84') === 0) raw = '0' + raw.slice(3);
    else if (raw.indexOf('84') === 0 && raw.length >= 10) raw = '0' + raw.slice(2);
    return raw;
  }
  function onlyDigits(s) { return (s || '').replace(/\D/g, ''); }

  // Factory module for phone tags
  function PhoneTagModule(cfg) {
    var $wrap   = $(cfg.wrap);
    var $input  = $(cfg.input);
    var $hidden = $(cfg.hidden);
    var $save   = $(cfg.save ? cfg.save : null);
    var $dupMsg = $(cfg.dupMsg ? cfg.dupMsg : null);
    var phones  = [];

    function updateSaveState() {
      var hasDup = $wrap.find('.badge').filter(function () {
        return $(this).data('status') === 'dup';
      }).length > 0;
      if ($save && $save.length) $save.prop('disabled', hasDup);
      if ($dupMsg && $dupMsg.length) {
        if (hasDup) $dupMsg.removeClass('d-none'); else $dupMsg.addClass('d-none');
      }
    }

    function syncHidden() {
      if ($hidden && $hidden.length) $hidden.val(phones.join(','));
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
      var url = (window.APP && window.APP.routes && (window.APP.routes.check_phone || window.APP.routes.khachhang_check_phone)) || '/check_phone';
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
        // nếu API lỗi, vẫn thêm với status ok để không block UX
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

    function initFromCSV(csv) {
      phones = [];
      $wrap.find('.badge').remove();
      $input.val('');
      if ($hidden && $hidden.length) $hidden.val(csv || '');
      var raw = (csv || ($hidden && $hidden.val()) || '').trim();
      if (!raw) { updateSaveState(); return; }
      raw.split(',').map(normalizePhone).filter(Boolean).forEach(function (p) { addPhoneTagSilent(p, 'ok'); });
      updateSaveState();
    }

    return {
      resetFromCSV: initFromCSV,
      syncHidden: syncHidden,
      getPhones: function () { return phones.slice(); }
    };
  }

  // Instances
  window.PhoneTagAdd = PhoneTagModule({
    wrap:   '#phoneTagsAdd',
    input:  '#phoneInputAdd',
    hidden: '#phonesHiddenAdd',
    save:   '#btnSaveCustomerAdd',
    dupMsg: '#dupHelpAdd'
  });

  window.PhoneTagEdit = PhoneTagModule({
    wrap:   '#phoneTags',
    input:  '#phoneInput',
    hidden: '#phonesHidden',
    save:   '#btnSaveCustomer', // dùng #btnSaveCustomer theo dự án, nếu tên khác thêm selector bổ sung
    dupMsg: '#dupHelp',
    excludeId: '#editId'
  });

  // Modal hooks
  $('#addCustomerModal').on('show.bs.modal', function () {
    window.PhoneTagAdd.resetFromCSV('');
  });

  $('#editCustomerModal').on('show.bs.modal', function () {
    // Khi mở modal edit, gọi ajax để load data khách hàng rồi resetFromCSV với dữ liệu dienthoai
    // Ví dụ bạn có button chỉnh sửa kèm data-id; phần này tuỳ implementation của bạn.
    // Nếu bạn đã load dữ liệu trước, chỉ cần gọi:
    // window.PhoneTagEdit.resetFromCSV('0329667822,0987654321');
  });

  /* ================== Submit AJAX: Add Customer ================== */
  $(document).on('submit', '#addCustomerForm', function (e) {
    e.preventDefault();
    var $form = $(this);
    var url = $form.data('action') || (window.APP && window.APP.routes && window.APP.routes.khachhang_ajax_add) || '/khachhang/ajax_add';
    var $btn = $('#btnSaveCustomerAdd');
    if ($btn && $btn.length) $btn.prop('disabled', true);

    // ensure hidden phones synced
    if (window.PhoneTagAdd) window.PhoneTagAdd.syncHidden();

    $.ajax({
      url: url,
      method: 'POST',
      data: $form.serialize(),
      dataType: 'json'
    }).done(function (res) {
      if (res && res.success) {
        $('#addCustomerModal').modal('hide');
        location.reload();
      } else {
        alert(res && res.msg ? res.msg : 'Không thể thêm khách hàng');
        if ($btn && $btn.length) $btn.prop('disabled', false);
      }
    }).fail(function (xhr) {
      var text = 'Lỗi kết nối hoặc server.';
      try { text = (xhr.responseJSON && xhr.responseJSON.msg) ? xhr.responseJSON.msg : xhr.responseText || text; } catch (err) {}
      alert(text);
      if ($btn && $btn.length) $btn.prop('disabled', false);
      console.error('Add customer error:', xhr);
    });
  });

  /* ================== Submit AJAX: Edit Customer ================== */
  $(document).on('submit', '#editCustomerForm', function (e) {
    e.preventDefault();
    var $form = $(this);
    var url = $form.data('action') || (window.APP && window.APP.routes && window.APP.routes.khachhang_ajax_edit) || '/khachhang/ajax_edit';
    var $btn = $('#btnSaveCustomer');
    if ($btn && $btn.length) $btn.prop('disabled', true);

    if (window.PhoneTagEdit) window.PhoneTagEdit.syncHidden();

    $.ajax({
      url: url,
      method: 'POST',
      data: $form.serialize(),
      dataType: 'json'
    }).done(function (res) {
      if (res && res.success) {
        $('#editCustomerModal').modal('hide');
        location.reload();
      } else {
        // show validation errors if provided
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();
        if (res && res.errors) {
          $.each(res.errors, function (k, v) {
            var $el = $form.find('[name="'+k+'"]');
            if ($el.length) {
              $el.addClass('is-invalid');
              $el.after('<div class="invalid-feedback">'+v+'</div>');
            }
          });
        } else {
          alert(res && res.msg ? res.msg : 'Không thể cập nhật khách hàng');
        }
        if ($btn && $btn.length) $btn.prop('disabled', false);
      }
    }).fail(function (xhr) {
      var text = 'Lỗi kết nối hoặc server.';
      try { text = (xhr.responseJSON && xhr.responseJSON.msg) ? xhr.responseJSON.msg : xhr.responseText || text; } catch (err) {}
      alert(text);
      if ($btn && $btn.length) $btn.prop('disabled', false);
      console.error('Update customer error:', xhr);
    });
  });

})();