<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id=(int)($_GET['id'] ?? 0);
if($id<=0){ flash_set('err','Invalid car.'); redirect_to('admin/cars/index.php'); }

$r=db()->query("SELECT * FROM cars WHERE id=$id AND is_deleted=0 LIMIT 1");
$car=$r?$r->fetch_assoc():null;
if(!$car){ flash_set('err','Car not found.'); redirect_to('admin/cars/index.php'); }

$cats=array();
$r=db()->query("SELECT id,name FROM categories WHERE is_active=1 ORDER BY name");
if($r){ while($row=$r->fetch_assoc()) $cats[]=$row; }

if($_SERVER['REQUEST_METHOD']==='POST'){
  $category_id=(int)($_POST['category_id'] ?? 0);
  $title=trim($_POST['title'] ?? '');
  $brand=trim($_POST['brand'] ?? '');
  $model=trim($_POST['model'] ?? '');
  $car_year=(int)($_POST['car_year'] ?? 0);
  $fuel=trim($_POST['fuel'] ?? '');
  $transmission=trim($_POST['transmission'] ?? '');
  $price=(float)($_POST['price'] ?? 0);
  $mileage_km=(int)($_POST['mileage_km'] ?? 0);
  $location=trim($_POST['location'] ?? '');
  $description=trim($_POST['description'] ?? '');
  $status=trim($_POST['status'] ?? 'Draft');

  if($title===''||$brand===''||$model===''||$car_year<=0||$fuel===''||$transmission===''||$price<=0||$location===''){
    flash_set('err','Please fill all required fields.');
    redirect_to('admin/cars/edit.php?id='.$id);
  }

  $allowedStatus=array('Draft','Published','Sold');
  if(!in_array($status,$allowedStatus)) $status='Draft';

  $stmt=db()->prepare("UPDATE cars SET category_id=?,title=?,brand=?,model=?,car_year=?,fuel=?,transmission=?,price=?,mileage_km=?,location=?,description=?,status=? WHERE id=? LIMIT 1");
  $stmt->bind_param("isssissdisssi",$category_id,$title,$brand,$model,$car_year,$fuel,$transmission,$price,$mileage_km,$location,$description,$status,$id);
  $stmt->execute();

  flash_set('ok','Car updated.');
  redirect_to('admin/cars/index.php');
}
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div><h3 class="fw-bold mb-1">Edit Car</h3><p class="text-muted mb-0"><?php echo esc($car['title']); ?></p></div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/index.php">Back</a>
  </div>

  <?php if($m=flash_get('err')): ?><div class="alert alert-danger mt-3"><?php echo esc($m); ?></div><?php endif; ?>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <form method="post" class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Category</label>
          <select class="form-select" name="category_id">
            <option value="0">None</option>
            <?php foreach($cats as $c): ?>
              <option value="<?php echo (int)$c['id']; ?>" <?php echo ((int)$car['category_id']===(int)$c['id'])?'selected':''; ?>>
                <?php echo esc($c['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-8"><label class="form-label">Title *</label><input class="form-control" name="title" required value="<?php echo esc($car['title']); ?>"></div>
        <div class="col-md-4"><label class="form-label">Brand *</label><input class="form-control" name="brand" required value="<?php echo esc($car['brand']); ?>"></div>
        <div class="col-md-4"><label class="form-label">Model *</label><input class="form-control" name="model" required value="<?php echo esc($car['model']); ?>"></div>
        <div class="col-md-4"><label class="form-label">Year *</label><input class="form-control" type="number" name="car_year" required value="<?php echo (int)$car['car_year']; ?>"></div>
        <div class="col-md-4"><label class="form-label">Fuel *</label><input class="form-control" name="fuel" required value="<?php echo esc($car['fuel']); ?>"></div>
        <div class="col-md-4"><label class="form-label">Transmission *</label><input class="form-control" name="transmission" required value="<?php echo esc($car['transmission']); ?>"></div>
        <div class="col-md-4"><label class="form-label">Price *</label><input class="form-control" type="number" name="price" required value="<?php echo (float)$car['price']; ?>"></div>
        <div class="col-md-4"><label class="form-label">Mileage</label><input class="form-control" type="number" name="mileage_km" value="<?php echo (int)$car['mileage_km']; ?>"></div>
        <div class="col-md-4"><label class="form-label">Location *</label><input class="form-control" name="location" required value="<?php echo esc($car['location']); ?>"></div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <?php foreach(array('Draft','Published','Sold') as $s): ?>
              <option <?php echo ($car['status']===$s)?'selected':''; ?>><?php echo $s; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4"><?php echo esc($car['description']); ?></textarea></div>
        <div class="col-12 d-flex gap-2">
          <button class="btn btn-dark">Save</button>
          <a class="btn btn-outline-primary" href="<?php echo BASE_URL; ?>admin/cars/images.php?id=<?php echo (int)$car['id']; ?>">Manage Images</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
