<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$car_id=(int)($_GET['id'] ?? 0);
if($car_id<=0){ flash_set('err','Invalid car.'); redirect_to('admin/cars/index.php'); }

$r=db()->query("SELECT id,title FROM cars WHERE id=$car_id AND is_deleted=0 LIMIT 1");
$car=$r?$r->fetch_assoc():null;
if(!$car){ flash_set('err','Car not found.'); redirect_to('admin/cars/index.php'); }

function set_primary($car_id,$img_id){
  db()->query("UPDATE car_images SET is_primary=0 WHERE car_id=".(int)$car_id." AND is_deleted=0");
  $stmt=db()->prepare("UPDATE car_images SET is_primary=1 WHERE id=? AND car_id=? AND is_deleted=0 LIMIT 1");
  $stmt->bind_param("ii",$img_id,$car_id);
  $stmt->execute();
}

function soft_delete_image($car_id,$img_id){
  $stmt=db()->prepare("UPDATE car_images SET is_deleted=1, is_primary=0 WHERE id=? AND car_id=? LIMIT 1");
  $stmt->bind_param("ii",$img_id,$car_id);
  $stmt->execute();
  $r=db()->query("SELECT id FROM car_images WHERE car_id=".(int)$car_id." AND is_deleted=0 ORDER BY id ASC LIMIT 1");
  $row=$r?$r->fetch_assoc():null;
  if($row) set_primary($car_id,(int)$row['id']);
}

function upload_more($car_id){
  $saved=0;
  if(!isset($_FILES['images']) || !is_array($_FILES['images']['name'])) return 0;
  $allowed=array('jpg','jpeg','png','webp');
  $baseDir=__DIR__ . '/../../uploads/cars/originals/';
  if(!is_dir($baseDir)) @mkdir($baseDir,0777,true);

  $hasPrimary=0;
  $res=db()->query("SELECT id FROM car_images WHERE car_id=".(int)$car_id." AND is_deleted=0 AND is_primary=1 LIMIT 1");
  if($res && $res->fetch_assoc()) $hasPrimary=1;

  $names=$_FILES['images']['name'];
  $tmp=$_FILES['images']['tmp_name'];
  $err=$_FILES['images']['error'];
  $size=$_FILES['images']['size'];

  for($i=0;$i<count($names);$i++){
    if($err[$i]!==UPLOAD_ERR_OK) continue;
    if($size[$i] > 5*1024*1024) continue;
    $ext=strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
    if(!in_array($ext,$allowed)) continue;

    $filename='car_'.$car_id.'_'.date('Ymd_His').'_'.bin2hex(random_bytes(6)).'.'.$ext;
    $destAbs=$baseDir.$filename;
    if(!move_uploaded_file($tmp[$i],$destAbs)) continue;

    $relPath='uploads/cars/originals/'.$filename;
    $isPrimary=($hasPrimary?0:1);

    $stmt=db()->prepare("INSERT INTO car_images (car_id,image_path,is_primary,is_deleted) VALUES (?,?,?,0)");
    $stmt->bind_param("isi",$car_id,$relPath,$isPrimary);
    $stmt->execute();

    if($isPrimary===1) $hasPrimary=1;
    $saved++;
  }
  return $saved;
}

if($_SERVER['REQUEST_METHOD']==='POST'){
  $action=$_POST['action'] ?? '';
  if($action==='upload'){
    $n=upload_more($car_id);
    flash_set('ok','Images uploaded: '.$n);
    redirect_to('admin/cars/images.php?id='.$car_id);
  }
  if($action==='primary'){
    $img_id=(int)($_POST['img_id'] ?? 0);
    if($img_id>0) set_primary($car_id,$img_id);
    flash_set('ok','Primary image updated.');
    redirect_to('admin/cars/images.php?id='.$car_id);
  }
  if($action==='delete'){
    $img_id=(int)($_POST['img_id'] ?? 0);
    if($img_id>0) soft_delete_image($car_id,$img_id);
    flash_set('ok','Image deleted.');
    redirect_to('admin/cars/images.php?id='.$car_id);
  }
}

$imgs=array();
$r=db()->query("SELECT * FROM car_images WHERE car_id=$car_id AND is_deleted=0 ORDER BY is_primary DESC, id ASC");
if($r){ while($row=$r->fetch_assoc()) $imgs[]=$row; }
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div><h3 class="fw-bold mb-1">Manage Images</h3><p class="text-muted mb-0"><?php echo esc($car['title']); ?></p></div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/index.php">Back</a>
  </div>

  <?php if($m=flash_get('ok')): ?><div class="alert alert-success mt-3"><?php echo esc($m); ?></div><?php endif; ?>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" class="mb-3">
        <input type="hidden" name="action" value="upload">
        <label class="form-label">Upload images</label>
        <input class="form-control" type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp">
        <button class="btn btn-dark mt-2">Upload</button>
      </form>

      <hr>

      <div class="row g-3">
        <?php foreach($imgs as $im): ?>
          <div class="col-md-4 col-lg-3">
            <div class="card card-soft h-100">
              <div class="card-body">
                <div class="ratio ratio-4x3 bg-light rounded overflow-hidden">
                  <img src="<?php echo BASE_URL . esc($im['image_path']); ?>" style="object-fit:cover;width:100%;height:100%;" alt="">
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                  <?php if((int)$im['is_primary']===1): ?>
                    <span class="badge text-bg-success">Primary</span>
                  <?php else: ?>
                    <span class="badge text-bg-secondary">Secondary</span>
                  <?php endif; ?>
                </div>

                <div class="d-flex gap-2 mt-2">
                  <form method="post" class="w-100">
                    <input type="hidden" name="action" value="primary">
                    <input type="hidden" name="img_id" value="<?php echo (int)$im['id']; ?>">
                    <button class="btn btn-outline-success btn-sm w-100" <?php echo ((int)$im['is_primary']===1)?'disabled':''; ?>>Set Primary</button>
                  </form>

                  <form method="post" class="w-100" onsubmit="return confirm('Delete this image?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="img_id" value="<?php echo (int)$im['id']; ?>">
                    <button class="btn btn-outline-danger btn-sm w-100">Delete</button>
                  </form>
                </div>

              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <?php if(count($imgs)===0): ?>
          <div class="col-12"><div class="alert alert-warning">No images yet. Upload at least 1 image.</div></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
