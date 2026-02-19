<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$rows=[];
$r=db()->query("SELECT id,name,is_active,created_at FROM categories ORDER BY id DESC");
if($r){ while($row=$r->fetch_assoc()) $rows[]=$row; }
?>
<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>
<div class="container-fluid my-4 px-3 px-md-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Categories</h3>
      <p class="text-muted mb-0">Manage car categories.</p>
    </div>
    <a class="btn btn-dark" href="<?php echo BASE_URL; ?>admin/categories/create.php">+ Add Category</a>
  </div>

  <div class="card card-soft mt-3"><div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>ID</th><th>Name</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
          <?php foreach($rows as $x): ?>
            <tr>
              <td><?php echo (int)$x['id']; ?></td>
              <td class="fw-bold"><?php echo esc($x['name']); ?></td>
              <td><span class="badge text-bg-<?php echo ((int)$x['is_active']===1)?'success':'secondary'; ?>"><?php echo ((int)$x['is_active']===1)?'Active':'Inactive'; ?></span></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-dark" href="<?php echo BASE_URL; ?>admin/categories/edit.php?id=<?php echo (int)$x['id']; ?>">Edit</a>
                <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete category?');" href="<?php echo BASE_URL; ?>admin/categories/delete.php?id=<?php echo (int)$x['id']; ?>">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if(count($rows)===0): ?><tr><td colspan="4" class="text-muted">No categories.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div></div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
