<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="mb-4">Lịch sử thao tác (Audit Log)</h3>
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Thao tác</th>
                <th>Đối tượng</th>
                <th>ID đối tượng</th>
                <th>Trước khi sửa/xóa</th>
                <th>Sau khi thêm/sửa</th>
                <th>Thời gian</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($logs)): ?>
                <?php foreach ($logs as $log): ?>
                  <tr>
                    <td><?= $log['id'] ?></td>
                    <td><?= htmlspecialchars($log['user_id']) ?></td>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td><?= htmlspecialchars($log['object_type']) ?></td>
                    <td><?= htmlspecialchars($log['object_id']) ?></td>
                    <td><pre style="max-width:300px;white-space:pre-wrap;word-break:break-all;"><?= htmlspecialchars($log['data_before']) ?></pre></td>
                    <td><pre style="max-width:300px;white-space:pre-wrap;word-break:break-all;"><?= htmlspecialchars($log['data_after']) ?></pre></td>
                    <td><?= $log['created_at'] ?></td>
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
</div>
