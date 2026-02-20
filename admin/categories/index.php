<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

function has_col($table, $col){
  $col = db()->real_escape_string($col);
  $table = db()->real_escape_string($table);
  $q = db()->query("SHOW COLUMNS FROM `$table` LIKE '$col'");
  return ($q && $q->num_rows > 0);
}

$has_is_active = has_col('categories', 'is_active');
$has_created_at = has_col('categories', 'created_at');

$fields = "id,name";
if($has_is_active) $fields .= ",is_active";
if($has_created_at) $fields .= ",created_at";

$rows = array();
$r = db()->query("SELECT $fields FROM categories ORDER BY id DESC");
if($r){
  while($row = $r->fetch_assoc()) $rows[] = $row;
} else {
  // optional debug (temporary)
  // echo "<pre>".esc(db()->error)."</pre>";
}
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Categories</h3>
      <p class="text-muted mb-0">Manage categories.</p>
    </div>
    <a class="btn btn-dark" href="<?php echo BASE_URL; ?>admin/categories/create.php">+ Add</a>
  </div>

  <?php if($m=flash_get('err')): ?><div class="alert alert-danger mt-3"><?php echo esc($m); ?></div><?php endif; ?>
  <?php if($m=flash_get('ok')): ?><div class="alert alert-success mt-3"><?php echo esc($m); ?></div><?php endif; ?>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <?php if($has_is_active): ?><th>Status</th><?php endif; ?>
              <?php if($has_created_at): ?><th>Date</th><?php endif; ?>
              <th class="text-end">Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach($rows as $x): ?>
              <tr>
                <td><?php echo (int)$x['id']; ?></td>
                <td class="fw-bold"><?php echo esc($x['name']); ?></td>

                <?php if($has_is_active): ?>
                  <td>
                    <span class="badge text-bg-<?php echo ((int)$x['is_active']===1)?'success':'secondary'; ?>">
                      <?php echo ((int)$x['is_active']===1)?'Active':'Inactive'; ?>
                    </span>
                  </td>
                <?php endif; ?>

                <?php if($has_created_at): ?>
                  <td class="text-muted small"><?php echo esc($x['created_at']); ?></td>
                <?php endif; ?>

                <td class="text-end">
                  <a class="btn btn-sm btn-outline-dark"
                     href="<?php echo BASE_URL; ?>admin/categories/edit.php?id=<?php echo (int)$x['id']; ?>">Edit</a>
                  <a class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Delete category?');"
                     href="<?php echo BASE_URL; ?>admin/categories/delete.php?id=<?php echo (int)$x['id']; ?>">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if(count($rows)===0): ?>
              <tr><td colspan="<?php echo 3 + ($has_is_active?1:0) + ($has_created_at?1:0); ?>" class="text-muted">
                No categories.
              </td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>