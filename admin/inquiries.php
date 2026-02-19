<?php
require_once __DIR__ . '/../app/init.php';
require_admin();
?>
<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Inquiries</h3>
      <p class="text-muted mb-0">Messages from users/guests about cars or contact form.</p>
    </div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/dashboard.php">Back to Dashboard</a>
  </div>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <div class="table-responsive">
        <?php
$rows=array();
$r=db()->query("SELECT i.id,i.name,i.email,i.phone,i.status,i.created_at,c.title car_title FROM inquiries i LEFT JOIN cars c ON c.id=i.car_id ORDER BY i.id DESC");
if($r){ while($row=$r->fetch_assoc()) $rows[]=$row; }
?>
<table class="table align-middle">
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Car</th><th>Status</th><th>Date</th></tr></thead>
  <tbody>
    <?php foreach($rows as $x): ?>
      <tr>
        <td><?php echo (int)$x['id']; ?></td><td class='fw-bold'><?php echo esc($x['name']); ?></td><td><?php echo esc($x['email']); ?></td><td><?php echo esc($x['phone']); ?></td><td><?php echo esc($x['car_title'] ?: '-'); ?></td><td><?php echo esc($x['status']); ?></td><td class='text-muted small'><?php echo esc($x['created_at']); ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if(count($rows)===0): ?><tr><td colspan="7" class="text-muted">No data found.</td></tr><?php endif; ?>
  </tbody>
</table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
