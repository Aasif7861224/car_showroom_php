<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$rows=[];
$r=db()->query("SELECT id,full_name,email,role,is_active,created_at FROM users ORDER BY id DESC");
if($r){ while($row=$r->fetch_assoc()) $rows[]=$row; }
?>
<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>
<div class="container-fluid my-4 px-3 px-md-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div><h3 class="fw-bold mb-1">Users</h3><p class="text-muted mb-0">Manage customers & admins.</p></div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/dashboard.php">Back</a>
  </div>

  <div class="card card-soft mt-3"><div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th class="text-end">Action</th></tr></thead>
        <tbody>
          <?php foreach($rows as $u): ?>
            <tr>
              <td><?php echo (int)$u['id']; ?></td>
              <td class="fw-bold"><?php echo esc($u['full_name']); ?></td>
              <td><?php echo esc($u['email']); ?></td>
              <td><span class="badge text-bg-<?php echo ($u['role']==='Admin')?'dark':'secondary'; ?>"><?php echo esc($u['role']); ?></span></td>
              <td><span class="badge text-bg-<?php echo ((int)$u['is_active']===1)?'success':'danger'; ?>"><?php echo ((int)$u['is_active']===1)?'Active':'Blocked'; ?></span></td>
              <td class="text-end">
                <?php if($u['role']!=='Admin'): ?>
                  <a class="btn btn-sm btn-outline-dark" href="<?php echo BASE_URL; ?>admin/users/toggle_status.php?id=<?php echo (int)$u['id']; ?>" onclick="return confirm('Toggle user status?');">Toggle</a>
                <?php else: ?>
                  <span class="text-muted small">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if(count($rows)===0): ?><tr><td colspan="6" class="text-muted">No users.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div></div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
