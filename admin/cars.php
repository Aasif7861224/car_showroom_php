<?php
require_once __DIR__ . '/../app/init.php';
require_admin();
?>
<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Cars</h3>
      <p class="text-muted mb-0">Inventory list from cars table.</p>
    </div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/dashboard.php">Back to Dashboard</a>
  </div>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <div class="table-responsive">
        <?php
$rows=array();
$r=db()->query("SELECT c.id,c.title,c.car_year,c.fuel,c.transmission,c.price,c.status,cat.name category FROM cars c LEFT JOIN categories cat ON cat.id=c.category_id ORDER BY c.id DESC");
if($r){ while($row=$r->fetch_assoc()) $rows[]=$row; }
?>
<table class="table align-middle">
  <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Year</th><th>Fuel</th><th>Trans.</th><th>Price</th><th>Status</th></tr></thead>
  <tbody>
    <?php foreach($rows as $x): ?>
      <tr>
        <td><?php echo (int)$x['id']; ?></td><td><div class='fw-bold'><?php echo esc($x['title']); ?></div><div class='text-muted small'><?php echo esc($x['category'] ?: ''); ?></div></td><td><?php echo esc($x['category'] ?: '-'); ?></td><td><?php echo (int)$x['car_year']; ?></td><td><?php echo esc($x['fuel']); ?></td><td><?php echo esc($x['transmission']); ?></td><td>₹<?php echo number_format((float)$x['price']); ?></td><td><span class='badge text-bg-<?php echo ($x['status']==='Published')?'success':'secondary'; ?>'><?php echo esc($x['status']); ?></span></td>
      </tr>
    <?php endforeach; ?>
    <?php if(count($rows)===0): ?><tr><td colspan="8" class="text-muted">No data found.</td></tr><?php endif; ?>
  </tbody>
</table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
