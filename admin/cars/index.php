<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$rows = array();
$sql = "SELECT c.id,c.title,c.price,c.status,c.car_year,c.location,c.is_deleted,cat.name category
        FROM cars c
        LEFT JOIN categories cat ON cat.id=c.category_id
        WHERE c.is_deleted=0
        ORDER BY c.id DESC";
$r = db()->query($sql);
if ($r) { while($row=$r->fetch_assoc()) $rows[]=$row; }

function badge_class($status){
  if ($status==='Published') return 'success';
  if ($status==='Sold') return 'secondary';
  return 'warning';
}
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Cars</h3>
      <p class="text-muted mb-0">Inventory management (Draft/Published/Sold)</p>
    </div>
    <a class="btn btn-dark" href="<?php echo BASE_URL; ?>admin/cars/create.php">+ Add Car</a>
  </div>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Category</th>
              <th>Year</th>
              <th>Location</th>
              <th>Price</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($rows as $x): ?>
              <tr>
                <td><?php echo (int)$x['id']; ?></td>
                <td class="fw-bold"><?php echo esc($x['title']); ?></td>
                <td><?php echo esc($x['category'] ?: '-'); ?></td>
                <td><?php echo (int)$x['car_year']; ?></td>
                <td><?php echo esc($x['location']); ?></td>
                <td>₹<?php echo number_format((float)$x['price']); ?></td>
                <td>
                  <span class="badge text-bg-<?php echo badge_class($x['status']); ?>">
                    <?php echo esc($x['status']); ?>
                  </span>
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/edit.php?id=<?php echo (int)$x['id']; ?>">Edit</a>
                  <a class="btn btn-sm btn-outline-primary" href="<?php echo BASE_URL; ?>admin/cars/images.php?id=<?php echo (int)$x['id']; ?>">Images</a>

                  <div class="btn-group btn-group-sm">
                    <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>admin/cars/status.php?id=<?php echo (int)$x['id']; ?>&status=Draft">Draft</a>
                    <a class="btn btn-outline-success" href="<?php echo BASE_URL; ?>admin/cars/status.php?id=<?php echo (int)$x['id']; ?>&status=Published">Publish</a>
                    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/status.php?id=<?php echo (int)$x['id']; ?>&status=Sold">Sold</a>
                  </div>

                  <a class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Soft delete this car?');"
                     href="<?php echo BASE_URL; ?>admin/cars/delete.php?id=<?php echo (int)$x['id']; ?>">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if(count($rows)===0): ?>
              <tr><td colspan="8" class="text-muted">No cars found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>