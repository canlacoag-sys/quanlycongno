/* ================== Toggle chevron ở bảng ================== */
$(document).on('click', '.toggle-row', function () {
  var $i = $(this).find('i');
  setTimeout(function () { $i.toggleClass('fa-chevron-down fa-chevron-up'); }, 150);
});

/* ================== Xoá bằng modal xác nhận ================== */
$(function () {
  var deleteUrl = "#";
  $(document).on('click', '.btn-delete', function (e) {
	e.preventDefault();
	var $btn = $(this);
	deleteUrl = $btn.attr('href');
	$('#delName').text($btn.data('name') || '');
	$('#delPhone').text($btn.data('phone') || '');
	$('#confirmDeleteModal').modal('show');
  });
  $('#btnConfirmDelete').on('click', function (e) {
	e.preventDefault();
	window.location.href = deleteUrl;
  });
});

/* ================== MODULE Tags số điện thoại ================== */
(function () {
  /* ---- Helpers ---- */
  function normalizePhone(raw) {
	raw = (raw || '').replace(/[^\d\+]/g, '').trim();
	if (!raw) return '';
	if (raw.indexOf('+84') === 0) raw = '0' + raw.slice(3);
	else if (raw.indexOf('84') === 0 && raw.length >= 10) raw = '0' + raw.slice(2);
	return raw;
  }
  function onlyDigits(s) { return (s || '').replace(/\D/g, ''); }

  /* ---- DOM ---- */
  var $wrap   = $('#phoneTags');       // khung chứa tag + input
  var $input  = $('#phoneInput');      // input nhập số
  var $hidden = $('#phonesHidden');    // hidden CSV để submit
  var $save   = $('#btnSaveCustomer'); // nút Lưu trong modal
  var $dupMsg = $('#dupHelp');         // thông báo “có số trùng”
  var phones  = [];                    // mảng số hiện tại

  /* ---- Public API để reset khi mở modal ---- */
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

  // AJAX kiểm tra trùng
  function checkDuplicate(number, excludeId) {
	return $.getJSON(window.APP.routes.check_phone, {
	  phone: number,
	  exclude_id: excludeId || ''
	});
  }

  // Khi đủ 10 số -> tạo tag (sau khi hỏi server)
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

  /* ---- Sự kiện nhập & xoá ---- */
  $input.on('input', function () {
	var v = $input.val();
	// dán nhiều số: tách theo dấu phẩy / khoảng trắng / chấm phẩy
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

  /* ---- Khởi tạo từ hidden khi trang vừa load (nếu có) ---- */
  function initFromHidden() {
	var csv = ($hidden.val() || '').trim();
	if (!csv) return;
	csv.split(',').map(function (p) { return normalizePhone(p); })
	  .filter(Boolean)
	  .forEach(function (p) { addPhoneTagSilent(p, 'ok'); });
  }
  initFromHidden();
})();

/* ================== Mở modal Sửa & nạp dữ liệu ================== */
// Khuyến nghị: dùng nút có data-toggle="modal" data-target="#editCustomerModal"
$(document).on('click', '.btn-edit', function (e) {
  e.preventDefault();
  var $btn = $(this);

  // action & id
  $('#editCustomerForm').attr('action', $btn.data('action') || '#');
  $('#editId').val($btn.data('id') || '');

  // tên / địa chỉ
  $('#editName').val($btn.data('name') || '');
  $('#editAddress').val($btn.data('address') || '');

  // số điện thoại CSV -> dựng tag
  var csv = $btn.data('phones') || '';
  $('#phonesHidden').val(csv);
  if (window.PhoneTag && window.PhoneTag.resetFromCSV) {
	window.PhoneTag.resetFromCSV(csv);
  }

  $('#editCustomerModal').modal('show');
});

/* ================== Validate nhẹ trước khi submit ================== */
$('#editCustomerForm').on('submit', function () {
  var name = $('#editName').val().trim();
  if (!name) { $('#editName').focus(); return false; }
  // Nếu còn số trùng (nút Lưu vẫn disable), tránh submit bằng phím Enter:
  if ($('#btnSaveCustomer').prop('disabled')) return false;
  return true;
});

// ===== PhoneTag factory cho nhiều modal =====
function initPhoneTags(prefix, excludeIdSelector) {
  function normalizePhone(raw){
	raw = (raw || '').replace(/[^\d\+]/g,'').trim();
	if(!raw) return '';
	if(raw.indexOf('+84') === 0) raw = '0' + raw.slice(3);
	else if(raw.indexOf('84') === 0 && raw.length >= 10) raw = '0' + raw.slice(2);
	return raw;
  }
  function onlyDigits(s){ return (s||'').replace(/\D/g,''); }

  var $wrap   = $('#phoneTags'    + prefix);
  var $input  = $('#phoneInput'   + prefix);
  var $hidden = $('#phonesHidden' + prefix);
  var $save   = $('#btnSaveCustomer' + prefix);
  var $dupMsg = $('#dupHelp' + prefix);
  var phones  = [];

  function updateSaveState(){
	var hasDup = $wrap.find('.badge').filter(function(){
	  return $(this).data('status') === 'dup';
	}).length > 0;
	$save.prop('disabled', hasDup);
	if (hasDup) $dupMsg.removeClass('d-none'); else $dupMsg.addClass('d-none');
  }
  function syncHidden(){ $hidden.val(phones.join(',')); updateSaveState(); }
  function tagTemplate(num, status){
	var color = status === 'dup' ? 'badge-danger' : 'badge-primary';
	return $('<span class="badge '+color+' d-inline-flex align-items-center px-2 py-1">' +
			  '<span class="mr-2">'+ num +'</span>' +
			  '<button type="button" class="btn btn-sm btn-light ml-1 p-0 px-1 remove-phone"><i class="fas fa-times"></i></button>'+
			'</span>').data('number', num).data('status', status);
  }
  function addTag(num, status){
	if(phones.indexOf(num) !== -1) return;
	phones.push(num);
	tagTemplate(num, status || 'ok').insertBefore($input);
	syncHidden();
  }
  function removeTag(num){
	var i = phones.indexOf(num);
	if(i !== -1) phones.splice(i,1);
	$wrap.find('.badge').filter(function(){ return $(this).data('number') === num; }).remove();
	syncHidden();
  }
  function checkDuplicate(num){
	var excludeId = excludeIdSelector ? $(excludeIdSelector).val() : '';
	return $.getJSON(window.APP.routes.check_phone, { phone:num, exclude_id: excludeId });
  }
  function handleCandidate(candidate){
	var n = normalizePhone(candidate);
	if(!n) return;
	var d = onlyDigits(n);
	if(d.length !== 10 || n[0] !== '0') return;
	if(phones.indexOf(n) !== -1){ $input.val(''); return; }

	checkDuplicate(n).done(function(res){
	  addTag(n, (res && res.exists) ? 'dup' : 'ok');
	  $input.val('');
	}).fail(function(){
	  addTag(n, 'ok');
	  $input.val('');
	});
  }

  // events
  $input.on('input', function(){
	var v = $input.val();
	if(/[,\s;]+/.test(v)){
	  v.split(/[,\s;]+/).forEach(function(piece){
		var d = onlyDigits(piece);
		if(d.length === 10) handleCandidate(piece);
	  });
	  $input.val('');
	  return;
	}
	var d = onlyDigits(v);
	if(d.length === 10) handleCandidate(v);
  });
  $input.on('keydown', function(e){
	if(e.key === 'Enter'){
	  e.preventDefault();
	  var d = onlyDigits($input.val());
	  if(d.length === 10) handleCandidate($input.val());
	}
	if(e.key === 'Backspace' && !$input.val() && phones.length){
	  removeTag(phones[phones.length-1]);
	}
  });
  $wrap.on('click', '.remove-phone', function(){
	removeTag($(this).closest('.badge').data('number'));
  });

  // API
  return {
	resetFromCSV: function(csv){
	  phones = [];
	  $wrap.find('.badge').remove();
	  $input.val('');
	  $hidden.val(csv || '');
	  (csv || '').split(',').map(function(p){ return normalizePhone(p); })
				 .filter(Boolean).forEach(function(p){ addTag(p,'ok'); });
	  updateSaveState();
	},
	getCSV: function(){ return $hidden.val() || ''; },
	hasDup: function(){
	  return $wrap.find('.badge').filter(function(){ return $(this).data('status') === 'dup'; }).length > 0;
	}
  };
}

// ===== Khởi tạo cho MODAL THÊM (prefix "Add") =====
var PTAdd = initPhoneTags('Add'); // dùng các id: phoneTagsAdd, phoneInputAdd, phonesHiddenAdd...

$('#addCustomerModal').on('show.bs.modal', function(){
  $('#addCustomerForm')[0].reset();
  PTAdd.resetFromCSV(''); // rỗng khi thêm mới
});

// Submit thêm mới
$('#addCustomerForm').on('submit', function(e){
  e.preventDefault();
  var name = $('#addName').val().trim();
  if(!name){ $('#addName').focus(); return; }
  if(PTAdd.hasDup()) return; // còn số trùng => không cho submit

  $.ajax({
	url: window.APP.routes.ajax_add,
	type: 'POST',
	dataType: 'json',
	data: {
	  ten: $('#addName').val().trim(),
	  dienthoai: PTAdd.getCSV(),   // CSV từ tag
	  diachi: $('#addAddress').val().trim()
	},
	success: function(res){
	  if(res && res.success){
		$('#addCustomerModal').modal('hide');
		location.reload(); // đơn giản nhất
	  }else{
		alert(res && res.msg ? res.msg : 'Không thể thêm khách hàng.');
	  }
	},
	error: function(){ alert('Lỗi mạng, vui lòng thử lại.'); }
  });
});
