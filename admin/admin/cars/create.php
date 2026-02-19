<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$cats=[];
$r=db()->query("SELECT id,name FROM categories WHERE is_active=1 ORDER BY name");
if($r){ while($row=$r->fetch_assoc()) $cats[]=$row; }

if($_SERVER['REQUEST_METHOD']==='POST'){
  $category_id=(int)($_POST['category_id']??0);
  $title=trim($_POST['title']??'');
  $brand=trim($_POST['brand']??'');
  $model=trim($_POST['model']??'');
  $car_year=(int)($_POST['car_year']??0);
  $fuel=trim($_POST['fuel']??'');
  $transmission=trim($_POST['transmission']??'');
  $price=(float)($_POST['price']??0);
  $mileage_km=(int)($_POST['mileage_km']??0);
  $location=trim($_POST['location']??'');
  $description=trim($_POST['description']??'');
  $status=trim($_POST['status']??'Draft');

  if($title==='' || $price<=0){
    flash_set('err','Title and price are required.');
    redirect_to('admin/cars/create.php');
  }

  $stmt=db()->prepare("INSERT INTO cars (category_id,title,brand,model,car_year,fuel,transmission,price,mileage_km,location,description,status,is_deleted,is_featured,discount_percent,view_count)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,0,0,0,0)");
  $stmt->bind_param("isssissdiiss",
    $category_id,$title,$brand,$model,$car_year,$fuel,$transmission,$price,$mileage_km,$location,$description,$status
  );
  $stmt->execute();
  $car_id=db()->insert_id;

  flash_set('ok','Car added. Now upload images.');
  redirect_to('admin/cars/images.php?id='.$car_id);
}
?>
<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div><h3 class="fw-bold mb-1">Add Car</h3><p class="text-muted mb-0">Create new car inventory item.</p></div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/index.php">Back</a>
  </div>

  <div class="card card-soft mt-3"><div class="card-body">
    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Title *</label>
        <input class="form-control" name="title" required placeholder="e.g., City Sedan 2021">
      </div>
      <div class="col-md-3">
        <label class="form-label">Category</label>
        <select class="form-select" name="category_id">
          <option value="0">-- Select --</option>
          <?php foreach($cats as $c): ?>
            <option value="<?php echo (int)$c['id']; ?>"><?php echo esc($c['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option>Draft</option>
          <option>Published</option>
          <option>Sold</option>
        </select>
      </div>

      <div class="col-md-3"><label class="form-label">Brand</label><input class="form-control" name="brand"></div>
      <div class="col-md-3"><label class="form-label">Model</label><input class="form-control" name="model"></div>
      <div class="col-md-2"><label class="form-label">Year</label><input class="form-control" name="car_year" type="number" min="1900" max="2100"></div>
      <div class="col-md-2"><label class="form-label">Fuel</label><input class="form-control" name="fuel" placeholder="Petrol/Diesel/EV"></div>
      <div class="col-md-2"><label class="form-label">Transmission</label><input class="form-control" name="transmission" placeholder="Manual/Auto"></div>

      <div class="col-md-3"><label class="form-label">Price (₹) *</label><input class="form-control" name="price" type="number" step="0.01" required></div>
      <div class="col-md-3"><label class="form-label">Mileage (km)</label><input class="form-control" name="mileage_km" type="number" min="0"></div>
      <div class="col-md-6"><label class="form-label">Location</label><input class="form-control" name="location" placeholder="Mumbai, Pune..." ></div>

      <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4"></textarea></div>

      <div class="col-12"><button class="btn btn-dark">Save & Upload Images</button></div>
    </form>
  </div></div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
