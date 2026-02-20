<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id=(int)($_GET['id'] ?? 0);
if($id<=0){ flash_set('err','Invalid category.'); redirect_to('admin/categories/index.php'); }
$r=db()->query("SELECT * FROM categories WHERE id=$id LIMIT 1");
$cat=$r?$r->fetch_assoc():null;
if(!$cat){ flash_set('err','Category not found.'); redirect_to('admin/categories/index.php'); }

if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name'] ?? '');
  $is_active=(int)($_POST['is_active'] ?? 1);
  if($name===''){ flash_set('err','Name required.'); redirect_to('admin/categories/edit.php?id='.$id); }
  $stmt=db()->prepare("UPDATE categories SET name=?,is_active=? WHERE id=? LIMIT 1");
  $stmt->bind_param("sii",$name,$is_active,$id);
  $stmt->execute();
  flash_set('ok','Category updated.');
  redirect_to('admin/categories/index.php');
}
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div><h3 class="fw-bold mb-1">Edit Category</h3><p class="text-muted mb-0"><?php echo esc($cat['name']); ?></p></div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/categories/index.php">Back</a>
  </div>

  <?php if($m=flash_get('err')): ?><div class="alert alert-danger mt-3"><?php echo esc($m); ?></div><?php endif; ?>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <form method="post" class="row g-3">
        <div class="col-md-8"><label class="form-label">Name *</label><input class="form-control" name="name" required value="<?php echo esc($cat['name']); ?>"></div>
        <div class="col-md-4"><label class="form-label">Status</label>
          <select class="form-select" name="is_active">
            <option value="1" <?php echo ((int)$cat['is_active']===1)?'selected':''; ?>>Active</option>
            <option value="0" <?php echo ((int)$cat['is_active']===0)?'selected':''; ?>>Inactive</option>
          </select>
        </div>
        <div class="col-12"><button class="btn btn-dark">Save</button></div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
