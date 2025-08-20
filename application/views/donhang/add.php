<!-- Chèn đoạn này vào view, sau khi đã truyền $sanpham, $khachhang từ Controller -->
<script>
var dsSanPham = <?= json_encode($sanpham); ?>;
</script>

<div class="content-wrapper">
  <section class="content">
	<div class="container">
	  <form method="post" autocomplete="off" id="formDonHang">
		<div class="card card-primary shadow mt-4">
		  <div class="card-header">
			<h3 class="card-title"><i class="fas fa-plus"></i> Thêm đơn hàng</h3>
		  </div>
		  <div class="card-body">

			<!-- Chọn khách hàng + modal -->
			<div class="form-group row">
			  <label class="col-md-2 col-form-label">Khách hàng</label>
			  <div class="col-md-6 d-flex align-items-center">
				
				<input type="text" id="khachhang_search" class="form-control" placeholder="Nhập tên hoặc số điện thoại khách hàng...">
				<input type="hidden" name="khachhang_id" id="khachhang_id">




				<a href="<?= site_url('khachhang/add'); ?>"
				   class="btn btn-success ml-3 d-flex align-items-center"
				   style="white-space:nowrap; font-size:0.8em; padding:0.5rem 1.0rem;">
				  <i class="fas fa-user-plus mr-2"></i> Thêm khách hàng mới
				</a>

			  </div>
			</div>

			

			<hr>
			<h5>Chi tiết sản phẩm</h5>
			<div class="table-responsive">
			  <table class="table table-bordered align-middle" id="tableSanPham">
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
			  <button type="button" id="btnThemDong" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Thêm dòng</button>
			</div>
			<hr>

			<!-- Tổng tiền/trả trước/còn nợ thẳng cột, sát phải -->
			<div class="row">
			  <div class="col-md-5 col-sm-12 ml-auto pr-md-4">
				<div class="form-group row mb-2 align-items-center">
				  <div class="col-6 text-left font-weight-bold">Tổng tiền:</div>
				  <div class="col-6 text-right">
					<span id="tongtien_show" class="font-weight-bold text-primary" style="font-size:1.3em;">0</span>
					<input type="hidden" name="tongtien" id="tongtien" value="0">
				  </div>
				</div>
				<div class="form-group row mb-2 align-items-center">
				  <div class="col-6 text-left font-weight-bold">Trả trước:</div>
				  <div class="col-6 text-right">
					<input type="number" name="datra" id="datra" class="form-control d-inline-block text-right" min="0" value="0" style="width:120px;display:inline-block;font-size:1.1em;">
				  </div>
				</div>
				<div class="form-group row mb-2 align-items-center">
				  <div class="col-6 text-left font-weight-bold">Còn nợ:</div>
				  <div class="col-6 text-right">
					<span id="conno_show" class="font-weight-bold text-danger" style="font-size:1.3em;">0</span>
					<input type="hidden" name="conno" id="conno" value="0">
				  </div>
				</div>
			  </div>
			</div>

		  </div>
		  <div class="card-footer bg-white text-right">
			<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Lưu đơn hàng</button>
			<a href="<?= site_url('donhang/list'); ?>" class="btn btn-secondary ml-2"><i class="fas fa-chevron-left"></i> Quay lại</a>
		  </div>
		</div>
	  </form>
	</div>
  </section>
</div>
<script>
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
  $('#tongtien').val(tong);
  $('#tongtien_show').text(numberFormat(tong));

  var datra = Number($('#datra').val() || 0);
  var conno = tong - datra;
  if (conno < 0) conno = 0;
  $('#conno').val(conno);
  $('#conno_show').text(numberFormat(conno));
}

$(document).ready(function(){
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
  $('#datra').on('input', function(){
	capNhatTong();
  });

  // Submit thêm khách hàng AJAX
  $('#formAddKH').submit(function(e){
	e.preventDefault();
	$('#msgKH').text('');
	$.ajax({
	  url: "<?= site_url('khachhang/ajax_add'); ?>",
	  type: "POST",
	  data: $(this).serialize(),
	  dataType: "json",
	  success: function(resp){
		if(resp.success){
		  let text = resp.ten + (resp.dienthoai ? ' (' + resp.dienthoai + ')' : '');
		  $('#selectKhachHang').append('<option value="'+resp.id+'" selected>'+text+'</option>').val(resp.id).trigger('change');
		  $('#modalAddKH').modal('hide');
		  $('#formAddKH')[0].reset();
		} else {
		  $('#msgKH').text(resp.msg);
		}
	  },
	  error: function(){ $('#msgKH').text('Lỗi kết nối.'); }
	});
  });
});

</script>
<script>
$(function(){
  $("#khachhang_search").autocomplete({
	minLength: 2,
	delay: 200,
	source: function(request, response) {
	  $.ajax({
		url: "<?= site_url('khachhang/autocomplete'); ?>", // Controller trả về JSON khách hàng
		dataType: "json",
		data: { q: request.term },
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
	  // Khi chọn gợi ý thì lưu id KH vào input ẩn
	  $("#khachhang_id").val(ui.item.id);
	}
  });
});
</script>


