
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <br>
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th class="text-center">ID</th>
                  <th class="text-center">User</th>
                  <th class="text-center">Thao tác</th>
                  <th class="text-center">Đối tượng</th>
                  <th class="text-center">ID đối tượng</th>
                  <th class="text-center">Trước khi sửa/xóa</th>
                  <th class="text-center">Sau khi thêm/sửa</th>
                  <th class="text-center">Thời gian</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($logs)): ?>
                  <?php foreach ($logs as $log): ?>
                    <tr>
                      <td class="text-center"><?= $log['id'] ?></td>
                      <td class="text-center"><?= htmlspecialchars($log['username']) ?></td>
                      <td class="text-center"><?= htmlspecialchars($log['action']) ?></td>
                      <td class="text-center"><?= htmlspecialchars($log['object_type']) ?></td>
                      <td class="text-center"><?= htmlspecialchars($log['object_id']) ?></td>
                      <td><pre style="max-width:300px;white-space:pre-wrap;word-break:break-all;"><?= htmlspecialchars($log['data_before']) ?></pre></td>
                      <td><pre style="max-width:300px;white-space:pre-wrap;word-break:break-all;"><?= htmlspecialchars($log['data_after']) ?></pre></td>
                      <td class="text-center"><?= $log['created_at'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="8" class="text-center">Chưa có log thao tác nào.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
