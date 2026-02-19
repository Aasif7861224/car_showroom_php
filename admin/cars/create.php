<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

function upload_images_for_car($car_id) {
  $saved = 0;

  if (!isset($_FILES['images']) || !is_array($_FILES['images']['name'])) return 0;

  $allowed = array('jpg','jpeg','png','webp');
  $baseDir = __DIR__ . '/../../uploads/cars/originals/';
  if (!is_dir($baseDir)) @mkdir($baseDir, 0777, true);

  $names = $_FILES['images']['name'];
  $tmp   = $_FILES['images']['tmp_name'];
  $err   = $_FILES['images']['error'];
  $size  = $_FILES['images']['size'];

  // check if car already has a primary image
  $hasPrimary = 0;
  $res = db()->query("SELECT id FROM car_images WHERE car_id=".(int)$car_id." AND is_deleted=0 AND is_primary=1 LIMIT 1");
  if ($res && $res->fetch_assoc()) $hasPrimary = 1;

  for ($i=0; $i<count($names); $i++) {
    if ($err[$i] !== UPLOAD_ERR_OK) continue;
    if ($size[$i] > 5 * 1024 * 1024) continue; // 5MB limit

    $ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) continue;

    $filename = 'car_'.$car_id.'_'.date('Ymd_His').'_'.bin2hex(random_bytes(6)).'.'.$ext;
    $destAbs  = $baseDir . $filename;

    if (!move_uploaded_file($tmp[$i], $destAbs)) continue;

    $relPath = 'uploads/cars/originals/' . $filename;
    $isPrimary = ($hasPrimary ? 0 : 1);

    $stmt = db()->prepare("INSERT INTO car_images (car_id,image_path,is_primary,is_deleted) VALUES (?,?,?,0)");
    $stmt->bind_param("isi", $car_id, $relPath, $isPrimary);
    $stmt->execute();

    if ($isPrimary === 1) $hasPrimary = 1;
    $saved++;
  }

  return $saved;
}

// categories
$cats = array();
$r = db()->query("SELECT id,name FROM categories WHERE is_active=1 ORDER BY name");
if ($r) { while($row=$r->fetch_assoc()) $cats[] = $row; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_id = (int)(isset($_POST['category_id']) ? $_POST['category_id'] : 0);
  $title = trim(isset($_POST['title']) ? $_POST['title'] : '');
  $brand = trim(isset($_POST['brand']) ? $_POST['brand'] : '');
  $model = trim(isset($_POST['model']) ? $_POST['model'] : '');
  $car_year = (int)(isset($_POST['car_year']) ? $_POST['car_year'] : 0);
  $fuel = trim(isset($_POST['fuel']) ? $_POST['fuel'] : '');
  $transmission = trim(isset($_POST['transmission']) ? $_POST['transmission'] : '');
  $price = (float)(isset($_POST['price']) ? $_POST['price'] : 0);
  $mileage_km = (int)(isset($_POST['mileage_km']) ? $_POST['mileage_km'] : 0);
  $location = trim(isset($_POST['location']) ? $_POST['location'] : '');
  $description = trim(isset($_POST['description']) ? $_POST['description'] : '');
  $status = trim(isset($_POST['status']) ? $_POST['status'] : 'Draft');

  if ($title==='' || $brand==='' || $model==='' || $car_year<=0 || $fuel==='' || $transmission==='' || $price<=0 || $location==='') {
    flash_set('err', 'Please fill all required fields.');
    redirect_to('admin/cars/create.php');
  }

  $allowedStatus = array('Draft','Published','Sold');
  if (!in_array($status, $allowedStatus)) $status = 'Draft';

  $stmt = db()->prepare("INSERT INTO cars (category_id,title,brand,model,car_year,fuel,transmission,price,mileage_km,location,description,status,is_deleted,is_featured,discount_percent,view_count)
                         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,0,0,0,0)");
  $stmt->bind_param("isssissdiiss",
    $category_id, $title, $brand, $model, $car_year, $fuel, $transmission, $price, $mileage_km, $location, $description, $status
  );
  $stmt->execute();

  $car_id = db()->insert_id;

  $uploaded = upload_images_for_car($car_id);

  flash_set('ok', 'Car added successfully. Images uploaded: '.$uploaded);
  redirect_to('admin/cars/index.php');
}
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Add Car</h3>
      <p class="text-muted mb-0">Create a new inventory item with images.</p>
    </div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/index.php">Back</a>
  </div>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Category</label>
          <select class="form-select" name="category_id">
            <option value="0">None</option>
            <?php foreach($cats as $c): ?>
              <option value="<?php echo (int)$c['id']; ?>"><?php echo esc($c['name']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-8">
          <label class="form-label">Title *</label>
          <input class="form-control" name="title" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Brand *</label>
          <input class="form-control" name="brand" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Model *</label>
          <input class="form-control" name="model" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Year *</label>
          <input class="form-control" type="number" name="car_year" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Fuel *</label>
          <select class="form-select" name="fuel" required>
            <option value="">Select</option>
            <option>Petrol</option><option>Diesel</option><option>CNG</option><option>Electric</option><option>Hybrid</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Transmission *</label>
          <select class="form-select" name="transmission" required>
            <option value="">Select</option>
            <option>Manual</option><option>Automatic</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Price (₹) *</label>
          <input class="form-control" type="number" name="price" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Mileage (km)</label>
          <input class="form-control" type="number" name="mileage_km">
        </div>

        <div class="col-md-4">
          <label class="form-label">Location *</label>
          <input class="form-control" name="location" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <option>Draft</option>
            <option>Published</option>
            <option>Sold</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="4"></textarea>
        </div>

        <div class="col-12">
          <label class="form-label">Images (multiple)</label>
          <input class="form-control" type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp">
          <div class="text-muted small mt-1">First uploaded image will be Primary (if none exists).</div>
        </div>

        <div class="col-12">
          <button class="btn btn-dark">Save Car</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>