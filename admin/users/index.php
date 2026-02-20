<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$rows=array();
$r=db()->query("SELECT id,full_name,email,role,is_active,created_at FROM users ORDER BY id DESC");
if($r){ while($row=$r->fetch_assoc()) $rows[]=$row; }
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <h3 class="fw-bold mb-1">Users</h3>
  <p class="text-muted mb-3">Activate/Deactivate customers.</p>

  <?php if($m=flash_get('err')): ?><div class="alert alert-danger"><?php echo esc($m); ?></div><?php endif; ?>
  <?php if($m=flash_get('ok')): ?><div class="alert alert-success"><?php echo esc($m); ?></div><?php endif; ?>

  <div class="card card-soft">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead><tr><th>ID</th><th>User</th><th>Role</th><th>Status</th><th>Date</th><th class="text-end">Action</th></tr></thead>
          <tbody>
            <?php foreach($rows as $x): ?>
              <tr>
                <td><?php echo (int)$x['id']; ?></td>
                <td><div class="fw-bold"><?php echo esc($x['full_name']); ?></div><div class="text-muted small"><?php echo esc($x['email']); ?></div></td>
                <td><span class="badge text-bg-<?php echo ($x['role']==='Admin')?'dark':'secondary'; ?>"><?php echo esc($x['role']); ?></span></td>
                <td><span class="badge text-bg-<?php echo ((int)$x['is_active']===1)?'success':'danger'; ?>"><?php echo ((int)$x['is_active']===1)?'Active':'Blocked'; ?></span></td>
                <td class="text-muted small"><?php echo esc($x['created_at']); ?></td>
                <td class="text-end">
                  <?php if($x['role']!=='Admin'): ?>
                    <a class="btn btn-sm btn-outline-dark" onclick="return confirm('Toggle user status?');" href="<?php echo BASE_URL; ?>admin/users/toggle_status.php?id=<?php echo (int)$x['id']; ?>">Toggle</a>
                  <?php else: ?><span class="text-muted small">—</span><?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if(count($rows)===0): ?><tr><td colspan="6" class="text-muted">No users.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
