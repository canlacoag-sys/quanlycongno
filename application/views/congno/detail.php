<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1 class="mb-3">Công nợ khách hàng</h1>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">

      <!-- Thông tin khách hàng -->
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h4>Thông tin khách hàng</h4>
          <p><strong>Tên khách hàng:</strong> <?= htmlspecialchars($khachhang->ten) ?></p>
          <p><strong>Điện thoại:</strong> <?= htmlspecialchars($khachhang->dienthoai) ?></p>
          <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($khachhang->diachi) ?></p>
          <p><strong>Tổng số đơn hàng:</strong> <?= $tong_don ?></p>
          <p><strong>Tổng nợ:</strong> <span class="text-danger font-weight-bold"><?= number_format($tong_no) ?> đ</span></p>
        </div>
      </div>

      <!-- Bảng danh sách đơn hàng -->
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h4>Danh sách đơn hàng</h4>
          <?php 
            // Sắp xếp đơn hàng theo ngày lập giảm dần
            usort($donhangs, function($a, $b) {
              return strtotime($b->ngaylap) - strtotime($a->ngaylap);
            });
          ?>
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Mã đơn</th>
                <th>Ngày lập</th>
                <th>Tổng tiền</th>
                <th class="text-center" style="width:130px;">Tác vụ</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($donhangs as $dh): ?>
              <tr>
                <td><?= htmlspecialchars($dh->madon_id) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($dh->ngaylap)) ?></td>
                <td class="text-right"><?= number_format($dh->tongtien) ?> đ</td>
                <td class="text-center">
                  <a href="<?= site_url('donhang/detail/'.$dh->id) ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Bảng tổng hợp sản phẩm -->
      <div class="card shadow-sm">
        <div class="card-body">
          <h4>Tổng hợp sản phẩm đã mua</h4>
            
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalKetSo">Kết sổ nợ</button>
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center">Mã sản phẩm</th>
                <th class="text-center">Loại sản phẩm</th>
                <th class="text-center">Đơn giá</th>
                <th class="text-center">Tổng số lượng</th>
                <th class="text-center">Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sanpham_tonghop as $sp): ?>
              <tr>
                <td class="text-center">
                  <?= $sp['ma_sp'] ?>
                  <span class="badge badge-info ml-1">x<?= $sp['so_lan_lap'] ?? 1 ?></span>
                </td>
                <td class="text-center">
                <?php
                  // Hiển thị loại sản phẩm: Có chiết khấu hoặc Không chiết khấu
                  if (isset($sp['co_chiet_khau']) && $sp['co_chiet_khau']) {
                    echo '<span class="badge badge-success">Có chiết khấu</span>';
                  } else {
                    echo '<span class="badge badge-secondary">Không chiết khấu</span>';
                  }
                ?>
                </td>
                <td class="text-right"><?= number_format($sp['don_gia']) ?> đ</td>
                <td class="text-center"><?= $sp['so_luong'] ?></td>
                <td class="text-right text-danger font-weight-bold"><?= number_format($sp['thanh_tien']) ?> đ</td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
            <!-- Modal Kết sổ nợ -->
            <div class="modal fade" id="modalKetSo" tabindex="-1" role="dialog" aria-labelledby="modalKetSoLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <form id="formKetSo" method="post" action="<?= site_url('congno/ketso/' . $khachhang->id) ?>">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalKetSoLabel">Lập phiếu kết sổ nợ</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-12">
                          <h5>Bảng: Có chiết khấu</h5>
                          <div class="form-group">
                            <label>Áp dụng chiết khấu (%)</label>
                            <input type="number" id="discountPercent" class="form-control" value="0" min="0" max="100" step="0.1">
                            <!-- Hidden field copied into on submit so server reliably receives chietkhau_percent -->
                            <input type="hidden" id="chietkhau_percent_hidden" name="chietkhau_percent" value="0">
                          </div>
                          <table class="table table-bordered" id="tableChietKhau">
                            <thead>
                              <tr>
                                <th class="text-center">Mã sản phẩm</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Thành tiền</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($sanpham_tonghop as $idx => $sp): ?>
                                <?php if (isset($sp['co_chiet_khau']) && $sp['co_chiet_khau']): ?>
                                <tr data-idx="<?= $idx ?>">
                                  <td class="text-center"><?= $sp['ma_sp'] ?><input type="hidden" name="items[<?= $idx ?>][ma_sp]" value="<?= $sp['ma_sp'] ?>"></td>
                                  <td class="text-right">
                                    <!-- Don gia for discounted products should NOT be editable -->
                                    <input type="number" class="form-control text-right don-gia-chiet" name="items[<?= $idx ?>][don_gia]" value="<?= $sp['don_gia'] ?>" min="0" step="1000" readonly>
                                  </td>
                                  <td class="text-center">
                                    <span><?= $sp['so_luong'] ?></span>
                                    <input type="hidden" class="form-control so-luong-chiet text-center" name="items[<?= $idx ?>][so_luong]" value="<?= $sp['so_luong'] ?>" readonly>
                                  </td>
                                  <td class="text-right text-danger font-weight-bold">
                                    <span class="thanh-tien-chiet-label"><?= number_format($sp['thanh_tien']) ?> đ</span>
                                    <input type="hidden" class="thanh-tien-chiet" name="items[<?= $idx ?>][thanh_tien]" value="<?= $sp['thanh_tien'] ?>">
                                  </td>
                                </tr>
                                <?php endif; ?>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th class="text-right" colspan="3">Tổng (trước chiết khấu)</th>
                                <th class="text-right text-danger font-weight-bold"><span id="tongChietKhauBefore">0 đ</span></th>
                              </tr>
                              <tr>
                                <th class="text-right" colspan="3">Chiết khấu (<span id="discountPercentLabel">0</span>%)</th>
                                <th class="text-right text-danger font-weight-bold"><span id="discountAmount">0 đ</span></th>
                              </tr>
                              <tr>
                                <th class="text-right" colspan="3">Tổng sau chiết khấu</th>
                                <th class="text-right text-danger font-weight-bold"><span id="tongChietKhauAfter">0 đ</span></th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <div class="col-12">
                          <h5>Bảng: Không chiết khấu</h5>
                          <table class="table table-bordered" id="tableKhongChiet">
                            <thead>
                              <tr>
                                <th class="text-center">Mã sản phẩm</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Thành tiền</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($sanpham_tonghop as $idx => $sp): ?>
                                <?php if (!isset($sp['co_chiet_khau']) || !$sp['co_chiet_khau']): ?>
                                <tr data-idx="<?= $idx ?>">
                                  <td class="text-center"><?= $sp['ma_sp'] ?><input type="hidden" name="items[<?= $idx ?>][ma_sp]" value="<?= $sp['ma_sp'] ?>"></td>
                                  <td class="text-right">
                                    <!-- Don gia for non-discounted products should be editable -->
                                    <input type="number" class="form-control text-right don-gia-non" name="items[<?= $idx ?>][don_gia]" value="<?= $sp['don_gia'] ?>" min="0" step="1000">
                                  </td>
                                  <td class="text-center">
                                    <span><?= $sp['so_luong'] ?></span>
                                    <input type="hidden" class="form-control so-luong-non text-center" name="items[<?= $idx ?>][so_luong]" value="<?= $sp['so_luong'] ?>" readonly>
                                  </td>
                                  <td class="text-right text-danger font-weight-bold">
                                    <span class="thanh-tien-non-label"><?= number_format($sp['thanh_tien']) ?> đ</span>
                                    <input type="hidden" class="thanh-tien-non" name="items[<?= $idx ?>][thanh_tien]" value="<?= $sp['thanh_tien'] ?>">
                                  </td>
                                </tr>
                                <?php endif; ?>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th class="text-right" colspan="3">Tổng không chiết khấu</th>
                                <th class="text-right text-danger font-weight-bold"><span id="tongKhongChiet">0 đ</span></th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12 text-right">
                          <h5>Tổng cộng: <span id="tongCongLabel">0 đ</span></h5>
                        </div>
                      </div>

                      <script>
                      function parseIntSafe(v){ return parseInt(String(v).replace(/[^0-9\-]/g,'')) || 0; }

                      function updateTables() {
                        // Chiet khau table
                        var subtotalChiet = 0;
                        $('#tableChietKhau tbody tr').each(function(){
                          var idx = $(this).data('idx');
                          var don_gia = parseFloat($(this).find('.don-gia-chiet').val()) || 0;
                          var so_luong = parseInt($(this).find('.so-luong-chiet').val()) || 0;
                          var thanh = Math.round(don_gia * so_luong);
                          $(this).find('.thanh-tien-chiet').val(thanh);
                          $(this).find('.thanh-tien-chiet-label').text(thanh.toLocaleString() + ' đ');
                          subtotalChiet += thanh;
                        });
                        $('#tongChietKhauBefore').text(subtotalChiet.toLocaleString() + ' đ');

                        var pct = parseFloat($('#discountPercent').val()) || 0;
                        $('#discountPercentLabel').text(pct);
                        var discountAmount = Math.round(subtotalChiet * (pct/100));
                        $('#discountAmount').text(discountAmount.toLocaleString() + ' đ');
                        var afterChiet = subtotalChiet - discountAmount;
                        $('#tongChietKhauAfter').text(afterChiet.toLocaleString() + ' đ');

                        // Khong chiet khau
                        var subtotalNon = 0;
                        $('#tableKhongChiet tbody tr').each(function(){
                          var don_gia = parseFloat($(this).find('.don-gia-non').val()) || 0;
                          var so_luong = parseInt($(this).find('.so-luong-non').val()) || 0;
                          var thanh = Math.round(don_gia * so_luong);
                          $(this).find('.thanh-tien-non').val(thanh);
                          $(this).find('.thanh-tien-non-label').text(thanh.toLocaleString() + ' đ');
                          subtotalNon += thanh;
                        });
                        $('#tongKhongChiet').text(subtotalNon.toLocaleString() + ' đ');

                        var tongCong = afterChiet + subtotalNon;
                        $('#tongCongLabel').text(tongCong.toLocaleString() + ' đ');
                      }

                      // Recalculate when discount percent or non-discount prices change
                      $(document).on('input', '#discountPercent, .don-gia-non', updateTables);
                      $(document).ready(function(){ updateTables(); });

                      // Ensure the discount percent is included in POST payload
                      $('#formKetSo').on('submit', function(){
                        // copy visible percent into hidden input (server reads chietkhau_percent)
                        $('#chietkhau_percent_hidden').val($('#discountPercent').val());
                        // recalc to make sure hidden item totals are up-to-date
                        updateTables();
                      });
                      </script>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                      <button type="submit" class="btn btn-success">Lập phiếu kết sổ &amp; In phiếu</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
      </div>

    </div>
  </section>
</div>