<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??'');
  $is_active=(int)($_POST['is_active']??1);
  if($name===''){ flash_set('err','Name required.'); redirect_to('admin/categories/create.php'); }

  $stmt=db()->prepare("INSERT INTO categories (name,is_active) VALUES (?,?)");
  $stmt->bind_param("si",$name,$is_active);
  $stmt->execute();

  flash_set('ok','Category added.');
  redirect_to('admin/categories/index.php');
}
?>
<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>
<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div><h3 class="fw-bold mb-1">Add Category</h3><p class="text-muted mb-0">Create a new category.</p></div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/categories/index.php">Back</a>
  </div>
  <div class="card card-soft mt-3"><div class="card-body">
    <form method="post" class="row g-3">
      <div class="col-md-8"><label class="form-label">Name *</label><input class="form-control" name="name" required></div>
      <div class="col-md-4"><label class="form-label">Active</label>
        <select class="form-select" name="is_active"><option value="1">Yes</option><option value="0">No</option></select>
      </div>
      <div class="col-12"><button class="btn btn-dark">Save</button></div>
    </form>
  </div></div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
