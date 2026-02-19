<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id=(int)($_GET['id']??0);
if($id<=0){ flash_set('err','Invalid.'); redirect_to('admin/cars/index.php'); }

$r=db()->query("SELECT id,title FROM cars WHERE id=$id LIMIT 1");
$car=$r?$r->fetch_assoc():null;
if(!$car){ flash_set('err','Not found.'); redirect_to('admin/cars/index.php'); }

$upload_dir = __DIR__ . '/../../uploads/cars/originals/';
if(!is_dir($upload_dir)) @mkdir($upload_dir,0777,true);

// Upload
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['upload_images'])){
  if(!isset($_FILES['images'])){ flash_set('err','No files.'); redirect_to('admin/cars/images.php?id='.$id); }

  $files=$_FILES['images'];
  $count = is_array($files['name']) ? count($files['name']) : 0;
  $added=0;

  for($i=0;$i<$count;$i++){
    if($files['error'][$i]!==UPLOAD_ERR_OK) continue;
    $tmp=$files['tmp_name'][$i];
    $name=$files['name'][$i];
    $ext=strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if(!in_array($ext,['jpg','jpeg','png','webp'])) continue;

    $new = 'car_'.$id.'_'.time().'_'.$i.'.'.$ext;
    $dest=$upload_dir.$new;
    if(move_uploaded_file($tmp,$dest)){
      $path='uploads/cars/originals/'.$new;
      $stmt=db()->prepare("INSERT INTO car_images (car_id,image_path,is_primary) VALUES (?,?,0)");
      $stmt->bind_param("is",$id,$path);
      $stmt->execute();
      $added++;
    }
  }

  flash_set('ok', $added.' image(s) uploaded.');
  redirect_to('admin/cars/images.php?id='.$id);
}

// Set primary
if(isset($_GET['primary'])){
  $img=(int)$_GET['primary'];
  db()->query("UPDATE car_images SET is_primary=0 WHERE car_id=$id");
  $stmt=db()->prepare("UPDATE car_images SET is_primary=1 WHERE id=? AND car_id=? LIMIT 1");
  $stmt->bind_param("ii",$img,$id);
  $stmt->execute();
  flash_set('ok','Primary image updated.');
  redirect_to('admin/cars/images.php?id='.$id);
}

// Delete image
if(isset($_GET['delete'])){
  $img=(int)$_GET['delete'];
  $r=db()->query("SELECT image_path FROM car_images WHERE id=$img AND car_id=$id LIMIT 1");
  $row=$r?$r->fetch_assoc():null;
  if($row){
    $abs=__DIR__ . '/../../' . $row['image_path'];
    if(is_file($abs)) @unlink($abs);
  }
  db()->query("DELETE FROM car_images WHERE id=$img AND car_id=$id LIMIT 1");
  flash_set('ok','Image deleted.');
  redirect_to('admin/cars/images.php?id='.$id);
}

$images=[];
$r=db()->query("SELECT id,image_path,is_primary,created_at FROM car_images WHERE car_id=$id ORDER BY is_primary DESC, id DESC");
if($r){ while($row=$r->fetch_assoc()) $images[]=$row; }
?>
<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Car Images</h3>
      <p class="text-muted mb-0"><?php echo esc($car['title']); ?></p>
    </div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/edit.php?id=<?php echo (int)$id; ?>">Back to Edit</a>
  </div>

  <div class="card card-soft mt-3"><div class="card-body">
    <form method="post" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 align-items-center">
      <input type="file" name="images[]" multiple class="form-control" style="max-width:420px;" accept="image/*" required>
      <button class="btn btn-dark" name="upload_images" value="1">Upload</button>
    </form>

    <hr>

    <div class="row g-3">
      <?php foreach($images as $im): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card">
            <img src="<?php echo BASE_URL . esc($im['image_path']); ?>" class="card-img-top" alt="">
            <div class="card-body p-2">
              <?php if((int)$im['is_primary']===1): ?>
                <span class="badge text-bg-success">Primary</span>
              <?php else: ?>
                <a class="btn btn-sm btn-outline-success" href="<?php echo BASE_URL; ?>admin/cars/images.php?id=<?php echo (int)$id; ?>&primary=<?php echo (int)$im['id']; ?>">Make Primary</a>
              <?php endif; ?>
              <a class="btn btn-sm btn-outline-danger float-end" onclick="return confirm('Delete image?');" href="<?php echo BASE_URL; ?>admin/cars/images.php?id=<?php echo (int)$id; ?>&delete=<?php echo (int)$im['id']; ?>">Delete</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if(count($images)===0): ?>
        <div class="text-muted">No images yet.</div>
      <?php endif; ?>
    </div>
  </div></div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
