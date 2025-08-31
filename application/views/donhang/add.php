<!-- Layout Thêm đơn hàng -->
<div class="card shadow-sm">
  <div class="card-header bg-primary text-white rounded-top">
    <strong><i class="fas fa-plus"></i> Thêm đơn hàng</strong>
  </div>
  <div class="card-body">
    <!-- Khách hàng -->
    <div class="form-group row align-items-center mb-4">
      <label class="col-sm-2 col-form-label font-weight-bold">Khách hàng</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" placeholder="Nhập tên hoặc số điện thoại khách hàng...">
      </div>
      <div class="col-sm-4">
        <button type="button" class="btn btn-success">
          <i class="fas fa-user-plus"></i> Thêm khách hàng mới
        </button>
      </div>
    </div>
    <hr>
    <!-- Chi tiết sản phẩm -->
    <div class="mb-2 font-weight-bold">Chi tiết sản phẩm</div>
    <div class="table-responsive">
      <table class="table table-bordered mb-0">
        <thead>
          <tr>
            <th style="width:160px;">Mã SP</th>
            <th>Tên sản phẩm</th>
            <th style="width:120px;">Đơn giá</th>
            <th style="width:110px;">Số lượng</th>
            <th style="width:120px;">Thành tiền</th>
            <th style="width:60px;"></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <input type="text" class="form-control" placeholder="MĐ: 5,7,24">
            </td>
            <td></td>
            <td class="text-right">0</td>
            <td>
              <input type="number" class="form-control" min="1" value="1" style="width:80px;">
            </td>
            <td class="text-right text-danger font-weight-bold">0</td>
            <td class="text-center">
              <button type="button" class="btn btn-danger"><i class="fas fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <button type="button" class="btn btn-primary mt-2"><i class="fas fa-plus"></i> Thêm dòng</button>
    <hr>
    <!-- Tổng tiền, trả trước, còn nợ -->
    <div class="row align-items-center">
      <div class="col-md-8"></div>
      <div class="col-md-4">
        <table class="table table-borderless mb-0">
          <tr>
            <td class="font-weight-bold text-right">Tổng tiền:</td>
            <td class="text-right text-primary font-weight-bold" style="width:100px;">0</td>
          </tr>
          <tr>
            <td class="font-weight-bold text-right">Trả trước:</td>
            <td>
              <input type="number" class="form-control" min="0" value="0">
            </td>
          </tr>
          <tr>
            <td class="font-weight-bold text-right">Còn nợ:</td>
            <td class="text-right text-danger font-weight-bold">0</td>
          </tr>
        </table>
      </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
      <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save"></i> Lưu đơn hàng</button>
      <a href="#" class="btn btn-secondary">Quay lại</a>
    </div>
  </div>
</div>