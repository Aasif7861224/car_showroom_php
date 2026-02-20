<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$rows = array();

$sql = "SELECT i.*, 
               u.full_name, 
               c.title AS car_title
        FROM inquiries i
        LEFT JOIN users u ON u.id = i.user_id
        LEFT JOIN cars c ON c.id = i.car_id
        ORDER BY i.id DESC";

$r = db()->query($sql);
if($r){
  while($row = $r->fetch_assoc()) $rows[] = $row;
}

function badgeInquiry($s){
  if($s==='Replied') return 'success';
  if($s==='Closed') return 'secondary';
  return 'warning';
}
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Inquiries</h3>
      <p class="text-muted mb-0">Customer enquiries about cars.</p>
    </div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/dashboard.php">Back</a>
  </div>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Car</th>
              <th>Message</th>
              <th>Status</th>
              <th>Date</th>
              <th class="text-end">Update</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($rows as $x): ?>
              <tr>
                <td><?php echo (int)$x['id']; ?></td>

                <td>
                  <div class="fw-bold">
                    <?php echo esc($x['full_name'] ?: $x['name']); ?>
                  </div>
                  <div class="text-muted small">
                    <?php echo esc($x['email']); ?>
                  </div>
                </td>

                <td><?php echo esc($x['car_title'] ?: '—'); ?></td>

                <td class="small" style="max-width:250px;">
                  <?php echo esc(substr($x['message'],0,100)); ?>...
                </td>

                <td>
                  <span class="badge text-bg-<?php echo badgeInquiry($x['status']); ?>">
                    <?php echo esc($x['status']); ?>
                  </span>
                </td>

                <td class="text-muted small">
                  <?php echo esc($x['created_at']); ?>
                </td>

                <td class="text-end">
                  <form method="post" action="<?php echo BASE_URL; ?>admin/inquiries/update_status.php" class="d-flex gap-2">
                    <input type="hidden" name="id" value="<?php echo (int)$x['id']; ?>">
                    <select name="status" class="form-select form-select-sm">
                      <option <?php if($x['status']==='New') echo 'selected'; ?>>New</option>
                      <option <?php if($x['status']==='Replied') echo 'selected'; ?>>Replied</option>
                      <option <?php if($x['status']==='Closed') echo 'selected'; ?>>Closed</option>
                    </select>
                    <button class="btn btn-sm btn-dark">Save</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if(count($rows)===0): ?>
              <tr><td colspan="7" class="text-muted">No inquiries.</td></tr>
            <?php endif; ?>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>