<?php
require_once __DIR__ . '/../app/init.php';
require_admin();
?>
<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Categories</h3>
      <p class="text-muted mb-0">Manage categories (view-only in this stable demo).</p>
    </div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/dashboard.php">Back to Dashboard</a>
  </div>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <div class="table-responsive">
        <?php
$rows=array();
$r=db()->query("SELECT id,name,slug,is_active FROM categories ORDER BY id DESC");
if($r){ while($row=$r->fetch_assoc()) $rows[]=$row; }
?>
<table class="table align-middle">
  <thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Active</th></tr></thead>
  <tbody>
    <?php foreach($rows as $x): ?>
      <tr>
        <td><?php echo (int)$x['id']; ?></td><td class='fw-bold'><?php echo esc($x['name']); ?></td><td><?php echo esc($x['slug']); ?></td><td><?php echo ((int)$x['is_active']===1)?'Yes':'No'; ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if(count($rows)===0): ?><tr><td colspan="4" class="text-muted">No data found.</td></tr><?php endif; ?>
  </tbody>
</table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
