<?php
// Đảm bảo không gọi $this->load->view('users/add'); ... nếu chưa có file add.php, edit.php, del.php
// Nếu bạn muốn modal add, edit, del nằm trong file này, hãy copy nội dung modal vào đây.
// Nếu muốn tách file, hãy tạo các file sau:
// application/views/users/add.php
// application/views/users/edit.php
// application/views/users/del.php
?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <br>
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h5 class="mb-0">Danh sách tài khoản</h5>
            <?php if ($current_user->role === 'admin' || $current_user->role === 'user'): ?>
              <button class="btn btn-success" id="btnAddUser" data-toggle="modal" data-target="#addUserModal"><i class="fas fa-user-plus"></i> Thêm tài khoản</button>
            <?php endif; ?>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead>
                <tr>
                  <th class="text-center" style="width:60px;">ID</th>
                  <th>Tài khoản</th>
                  <th class="text-center" style="width:220px;">Ngày tạo</th>
                  <th class="text-center" style="width:120px;">Quyền</th>
                  <th class="text-center" style="width:160px;">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($list as $user): ?>
                <tr>
                  <td class="text-center"><?= $user->id ?></td>
                  <td><?= htmlspecialchars($user->username) ?></td>
                  <td class="text-center"><?= date('d/m/Y H:i:s', strtotime($user->created_at)) ?></td>
                  <td class="text-center">
                    <span class="badge badge-<?= $user->role === 'admin' ? 'danger' : 'primary' ?>">
                      <?= $user->role === 'admin' ? 'Admin' : 'User' ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <?php if ($current_user->role === 'admin' || $current_user->id == $user->id): ?>
                      <button class="btn btn-info btn-sm btn-edit-user"
                        data-id="<?= $user->id ?>"
                        data-username="<?= htmlspecialchars($user->username) ?>"
                        data-role="<?= $user->role ?>"
                        data-toggle="modal" data-target="#editUserModal">
                        <i class="fas fa-edit"></i> Sửa
                      </button>
                    <?php endif; ?>
                    <?php if ($current_user->role === 'admin' && $current_user->id != $user->id): ?>
                      <button class="btn btn-danger btn-sm btn-del-user"
                        data-id="<?= $user->id ?>"
                        data-username="<?= htmlspecialchars($user->username) ?>"
                        data-toggle="modal" data-target="#delUserModal">
                        <i class="fas fa-trash-alt"></i> Xoá
                      </button>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal: Thêm tài khoản -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="addUserForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Thêm tài khoản</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="addUsername">Tài khoản</label>
          <input type="text" id="addUsername" name="username" class="form-control" required placeholder="Tên đăng nhập">
          <small id="dupUserHelpAdd" class="text-danger font-weight-bold font-italic d-block mt-1 d-none">
            Tài khoản đã tồn tại hoặc không hợp lệ. Hãy thay đổi trước khi lưu.
          </small>
        </div>
        <div class="form-group">
          <label for="addPassword">Mật khẩu</label>
          <input type="password" id="addPassword" name="password" class="form-control" required placeholder="Mật khẩu">
        </div>
        <?php if ($current_user->role === 'admin'): ?>
        <div class="form-group">
          <label for="addRole">Quyền</label>
          <select id="addRole" name="role" class="form-control">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <button type="submit" id="btnSaveUserAdd" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Lưu tài khoản</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Sửa tài khoản -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="editUserForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Sửa tài khoản</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editUserId" name="id">
        <div class="form-group">
          <label for="editUsername">Tài khoản</label>
          <input type="text" id="editUsername" name="username" class="form-control" required placeholder="Tên đăng nhập" readonly>
        </div>
        <div class="form-group">
          <label for="editPassword">Mật khẩu mới (bỏ trống nếu không đổi)</label>
          <input type="password" id="editPassword" name="password" class="form-control" placeholder="Mật khẩu mới">
        </div>
        <?php if ($current_user->role === 'admin'): ?>
        <div class="form-group">
          <label for="editRole">Quyền</label>
          <select id="editRole" name="role" class="form-control">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <button type="submit" id="btnSaveUserEdit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Cập nhật</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Xoá tài khoản -->
<div class="modal fade" id="delUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="delUserForm" class="modal-content" action="javascript:void(0)">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-trash-alt mr-2"></i>Xoá tài khoản</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="delUserId" name="id">
        <p>Bạn có chắc chắn muốn xoá tài khoản <strong id="delUserName"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal"><i class="fas fa-chevron-left mr-1"></i>Quay lại</button>
        <button type="submit" id="btnConfirmDeleteUser" class="btn btn-danger"><i class="fas fa-trash-alt mr-1"></i>Xoá</button>
      </div>
    </form>
  </div>
</div>

<script>
$(function() {
  // Sửa user
  $('.btn-edit-user').click(function() {
    var id = $(this).data('id');
    var username = $(this).data('username');
    var role = $(this).data('role');
    $('#editUserId').val(id);
    $('#editUsername').val(username);
    $('#editRole').val(role);
    $('#editPassword').val('');
  });

  // Xoá user
  $('.btn-del-user').click(function() {
    var id = $(this).data('id');
    var username = $(this).data('username');
    $('#delUserId').val(id);
    $('#delUserName').text(username);
  });

  // Kiểm tra trùng tên tài khoản, không khoảng trắng, tự động chuyển thường khi nhập
  $('#addUsername').on('input', function() {
    var username = $(this).val();
    // Chuyển thành chữ thường, không dấu, viết liền, loại bỏ khoảng trắng
    username = username.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '').toLowerCase();
    $(this).val(username);
    if (!username || /\s/.test(username)) {
      $('#dupUserHelpAdd').removeClass('d-none').text('Tên tài khoản không được chứa khoảng trắng!');
      $('#btnSaveUserAdd').prop('disabled', true);
      return;
    }
    $.get('<?= site_url('users/check_username') ?>', {username: username}, function(resp) {
      if (resp.exists) {
        $('#dupUserHelpAdd').removeClass('d-none').text('Tài khoản đã tồn tại. Hãy thay đổi trước khi lưu.');
        $('#btnSaveUserAdd').prop('disabled', true);
      } else {
        $('#dupUserHelpAdd').addClass('d-none');
        $('#btnSaveUserAdd').prop('disabled', false);
      }
    }, 'json');
  });

  // Khi submit form, kiểm tra lại lần cuối
  $('#addUserForm').on('submit', function(e) {
    var username = $('#addUsername').val().trim();
    if (!username || /\s/.test(username)) {
      $('#dupUserHelpAdd').removeClass('d-none').text('Tên tài khoản không được chứa khoảng trắng!');
      $('#btnSaveUserAdd').prop('disabled', true);
      e.preventDefault();
      return false;
    }
    if (!$('#dupUserHelpAdd').hasClass('d-none')) {
      e.preventDefault();
      return false;
    }
    // Thêm user
    $('#addUserForm').submit(function(e) {
      e.preventDefault();
      $.post('<?= site_url('users/add') ?>', $(this).serialize(), function(resp) {
        if (resp.success) location.reload();
        else alert(resp.msg || 'Không thể thêm tài khoản!');
      }, 'json');
    });
  });

  // Sửa user
  $('#editUserForm').submit(function(e) {
    e.preventDefault();
    $.post('<?= site_url('users/edit') ?>', $(this).serialize(), function(resp) {
      if (resp.success) location.reload();
      else alert(resp.msg || 'Không thể cập nhật tài khoản!');
    }, 'json');
  });

  // Xoá user
  $('#delUserForm').submit(function(e) {
    e.preventDefault();
    $.post('<?= site_url('users/delete') ?>', $(this).serialize(), function(resp) {
      if (resp.success) location.reload();
      else alert(resp.msg || 'Không thể xoá tài khoản!');
    }, 'json');
  });
});
</script>
