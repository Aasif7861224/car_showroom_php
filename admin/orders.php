<?php
require_once __DIR__ . '/../app/init.php';
require_admin();

function badgeOrder($s){
  if($s==='Paid') return 'success';
  if($s==='Delivered') return 'primary';
  if($s==='Cancelled') return 'danger';
  if($s==='Confirmed') return 'info';
  return 'warning'; // Pending
}

$rows=array();
$sql="SELECT o.id,u.full_name,u.email,
             c.title car_title,
             o.amount,o.status,o.created_at
      FROM orders o
      LEFT JOIN users u ON u.id=o.user_id
      LEFT JOIN cars c ON c.id=o.car_id
      ORDER BY o.id DESC";
$r=db()->query($sql);
if($r){ while($row=$r->fetch_assoc()) $rows[]=$row; }

$statuses = array('Pending','Confirmed','Paid','Delivered','Cancelled');
?>

<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../includes/admin_sidebar.php'; ?>

<div class="container-fluid my-4 px-3 px-md-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Orders</h3>
      <p class="text-muted mb-0">Manage order lifecycle & status updates.</p>
    </div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/dashboard.php">Back to Dashboard</a>
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
              <th>Amount</th>
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
                  <div class="fw-bold"><?php echo esc($x['full_name'] ?: '—'); ?></div>
                  <div class="text-muted small"><?php echo esc($x['email'] ?: ''); ?></div>
                </td>

                <td class="fw-bold"><?php echo esc($x['car_title'] ?: '—'); ?></td>

                <td>₹<?php echo number_format((float)$x['amount']); ?></td>

                <td>
                  <span class="badge text-bg-<?php echo badgeOrder($x['status']); ?>">
                    <?php echo esc($x['status']); ?>
                  </span>
                </td>

                <td class="text-muted small"><?php echo esc($x['created_at']); ?></td>

                <td class="text-end">
                  <form class="d-inline-flex gap-2 align-items-center" method="post" action="<?php echo BASE_URL; ?>admin/order_update_status.php">
                    <input type="hidden" name="id" value="<?php echo (int)$x['id']; ?>">
                    <select class="form-select form-select-sm" name="status" style="min-width: 140px;">
                      <?php foreach($statuses as $s): ?>
                        <option <?php echo ($x['status']===$s)?'selected':''; ?>><?php echo $s; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button class="btn btn-sm btn-dark">Save</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if(count($rows)===0): ?>
              <tr><td colspan="7" class="text-muted">No data found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>