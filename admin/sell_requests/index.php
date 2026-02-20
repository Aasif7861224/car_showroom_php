<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

function badgeSell($s){
  if($s==='Approved') return 'success';
  if($s==='Rejected') return 'danger';
  return 'warning';
}

$rows=array();
$sql="SELECT s.id,u.full_name,
             s.car_title,s.car_year,s.fuel,s.transmission,s.mileage_km,
             s.expected_price,s.location,s.status,s.reject_reason,s.approved_car_id,s.created_at
      FROM sell_requests s
      LEFT JOIN users u ON u.id=s.user_id
      ORDER BY s.id DESC";
$r=db()->query($sql);
if($r){ while($row=$r->fetch_assoc()) $rows[]=$row; }
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container-fluid my-4 px-3 px-md-4">
  <h3 class="fw-bold mb-1">Sell Requests</h3>
  <p class="text-muted mb-3">Approve → auto add car into inventory as Draft. Reject → store reason.</p>

  <?php if($m=flash_get('err')): ?><div class="alert alert-danger"><?php echo esc($m); ?></div><?php endif; ?>
  <?php if($m=flash_get('ok')): ?><div class="alert alert-success"><?php echo esc($m); ?></div><?php endif; ?>

  <div class="card card-soft">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>ID</th><th>Customer</th><th>Car</th><th>Year</th><th>Expected</th><th>Location</th><th>Status</th><th>Date</th><th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($rows as $x): ?>
              <tr>
                <td><?php echo (int)$x['id']; ?></td>
                <td><?php echo esc($x['full_name'] ?: '—'); ?></td>
                <td>
                  <div class="fw-bold"><?php echo esc($x['car_title']); ?></div>
                  <div class="text-muted small"><?php echo esc($x['fuel']); ?> • <?php echo esc($x['transmission']); ?><?php if((int)$x['mileage_km']>0): ?> • <?php echo (int)$x['mileage_km']; ?> km<?php endif; ?></div>
                  <?php if($x['status']==='Rejected' && $x['reject_reason']): ?>
                    <div class="small text-danger mt-1"><span class="fw-semibold">Reason:</span> <?php echo esc($x['reject_reason']); ?></div>
                  <?php endif; ?>
                </td>
                <td><?php echo (int)$x['car_year']; ?></td>
                <td>₹<?php echo number_format((float)$x['expected_price']); ?></td>
                <td><?php echo esc($x['location']); ?></td>
                <td><span class="badge text-bg-<?php echo badgeSell($x['status']); ?>"><?php echo esc($x['status']); ?></span></td>
                <td class="text-muted small"><?php echo esc($x['created_at']); ?></td>

                <td class="text-end">
                  <?php if($x['status']==='Pending'): ?>
                    <a class="btn btn-sm btn-success" onclick="return confirm('Approve & add as Draft car?');" href="<?php echo BASE_URL; ?>admin/sell_requests/approve.php?id=<?php echo (int)$x['id']; ?>">Approve</a>
                    <a class="btn btn-sm btn-danger" href="<?php echo BASE_URL; ?>admin/sell_requests/reject.php?id=<?php echo (int)$x['id']; ?>">Reject</a>
                  <?php elseif($x['status']==='Approved' && (int)$x['approved_car_id']>0): ?>
                    <a class="btn btn-sm btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/edit.php?id=<?php echo (int)$x['approved_car_id']; ?>">Open Car</a>
                    <a class="btn btn-sm btn-outline-success" href="<?php echo BASE_URL; ?>admin/cars/status.php?id=<?php echo (int)$x['approved_car_id']; ?>&status=Published">Publish</a>
                  <?php else: ?>
                    <span class="text-muted small">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if(count($rows)===0): ?><tr><td colspan="9" class="text-muted">No sell requests.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
