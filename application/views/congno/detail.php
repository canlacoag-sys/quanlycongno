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
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Mã đơn</th>
                <th>Ngày lập</th>
                <th>Tổng tiền</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($donhangs as $dh): ?>
              <tr>
                <td><?= htmlspecialchars($dh->madon_id) ?></td>
                <td><?= date('d/m/Y', strtotime($dh->ngaylap)) ?></td>
                <td class="text-right"><?= number_format($dh->tongtien) ?> đ</td>
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
                <td class="text-center"><?= $sp['ma_sp'] ?></td>
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
                      <table class="table table-bordered" id="tableKetSo">
                        <thead>
                          <tr>
                            <th class="text-center">Mã sản phẩm</th>
                            <th class="text-center">Loại sản phẩm</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-right">Thành tiền</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($sanpham_tonghop as $idx => $sp): ?>
                          <tr>
                            <td class="text-center"><?= $sp['ma_sp'] ?><input type="hidden" name="items[<?= $idx ?>][ma_sp]" value="<?= $sp['ma_sp'] ?>"></td>
                            <td class="text-center"><?php
                              // Hiển thị loại sản phẩm: Có chiết khấu hoặc Không chiết khấu
                              if (isset($sp['co_chiet_khau']) && $sp['co_chiet_khau']) {
                                echo '<span class="badge badge-success">Có chiết khấu</span>';
                              } else {
                                echo '<span class="badge badge-secondary">Không chiết khấu</span>';
                              }
                            ?></td>
                            <td class="text-right">
                              <?php if (isset($sp['co_chiet_khau']) && $sp['co_chiet_khau']): ?>
                                <input type="number" class="form-control text-right don-gia" name="items[<?= $idx ?>][don_gia]" value="<?= $sp['don_gia'] ?>" min="0" step="1000">
                              <?php else: ?>
                                <input type="number" class="form-control text-right don-gia" name="items[<?= $idx ?>][don_gia]" value="<?= $sp['don_gia'] ?>" readonly>
                              <?php endif; ?>
                            </td>
                            <td class="text-center">
                             
                              <span><?= $sp['so_luong'] ?></span>
                               <input type="hidden" class="form-control so-luong text-center" name="items[<?= $idx ?>][so_luong]" value="<?= $sp['so_luong'] ?>" min="1" step="1" readonly>
                            </td>
                            <td class="text-right text-danger font-weight-bold">
                              <span class="thanh-tien-label"><?= number_format($sp['thanh_tien']) ?> đ</span>
                              <input type="hidden" class="thanh-tien" name="items[<?= $idx ?>][thanh_tien]" value="<?= $sp['thanh_tien'] ?>">
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="4" class="text-right">Tổng tiền</th>
                            <th class="text-right text-danger font-weight-bold"><span id="tongTienKetSoLabel"></span></th>
                          </tr>
                        </tfoot>
                      </table>
                      <script>
                      function updateThanhTienAndTongTien() {
                        var tong = 0;
                        $('#tableKetSo tbody tr').each(function() {
                          var don_gia = parseInt($(this).find('.don-gia').val()) || 0;
                          var so_luong = parseInt($(this).find('.so-luong').val()) || 0;
                          var thanh_tien = don_gia * so_luong;
                          $(this).find('.thanh-tien').val(thanh_tien);
                          $(this).find('.thanh-tien-label').text(thanh_tien.toLocaleString() + ' đ');
                          tong += thanh_tien;
                          $(this).find('.so-luong').addClass('text-center');
                        });
                        $('#tongTienKetSoLabel').text(tong.toLocaleString() + ' đ');
                      }
                      $(document).on('input', '.don-gia, .so-luong', updateThanhTienAndTongTien);
                      $(document).ready(function(){
                        updateThanhTienAndTongTien();
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