<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<?php
$id=(int)(isset($_GET['id'])?$_GET['id']:0);
if($id<=0){ flash_set('err','Invalid car.'); redirect_to('user/buy_cars.php'); }

$car=null;
$r=db()->query("SELECT c.*,cat.name category FROM cars c LEFT JOIN categories cat ON cat.id=c.category_id WHERE c.id=$id LIMIT 1");
if($r) $car=$r->fetch_assoc();
if(!$car){ flash_set('err','Car not found.'); redirect_to('user/buy_cars.php'); }

$images=array();
$r=db()->query("SELECT image_path,is_primary FROM car_images WHERE car_id=$id ORDER BY is_primary DESC, id ASC");
if($r){ while($row=$r->fetch_assoc()) $images[]=$row; }

if(is_post()){
  $name=trim(isset($_POST['name'])?$_POST['name']:'');
  $email=trim(isset($_POST['email'])?$_POST['email']:'');
  $phone=trim(isset($_POST['phone'])?$_POST['phone']:'');
  $message=trim(isset($_POST['message'])?$_POST['message']:'');
  if($name==='' || $email==='' || $message===''){
    flash_set('err','Name, Email and Message are required.');
    redirect_to('user/car_details.php?id='.$id);
  }
  $uid=current_user()? (int)current_user()['id'] : null;

  $stmt=db()->prepare("INSERT INTO inquiries (user_id,car_id,name,email,phone,message,status) VALUES (?,?,?,?,?,?, 'New')");
  // user_id can be null: use bind_param with i might fail. We'll insert as NULL when not logged in.
  if($uid){
    $stmt->bind_param("iissss", $uid,$id,$name,$email,$phone,$message);
  } else {
    // insert NULL for user_id using query
    db()->query("INSERT INTO inquiries (user_id,car_id,name,email,phone,message,status) VALUES (NULL,$id,'".q($name)."','".q($email)."','".q($phone)."','".q($message)."','New')");
    flash_set('ok','Inquiry submitted. We will contact you soon.');
    redirect_to('user/car_details.php?id='.$id);
  }
  $stmt->execute();
  flash_set('ok','Inquiry submitted. We will contact you soon.');
  redirect_to('user/car_details.php?id='.$id);
}
?>

<div class="container my-4">
  <div class="row g-4">
    <div class="col-lg-7">
      <div class="card card-soft">
        <div class="card-body p-4">
          <span class="badge badge-soft"><?php echo esc($car['category'] ?: 'Car'); ?></span>
          <h2 class="fw-bold mt-2 mb-1"><?php echo esc($car['title']); ?></h2>
          <div class="text-muted"><?php echo (int)$car['car_year']; ?> • <?php echo esc($car['location']); ?></div>

          <div class="h3 mt-3">₹<?php echo number_format((float)$car['price']); ?></div>

          <div class="row g-3 mt-2">
            <div class="col-md-4"><div class="card card-soft p-3"><div class="small text-muted">Fuel</div><div class="fw-bold"><?php echo esc($car['fuel']); ?></div></div></div>
            <div class="col-md-4"><div class="card card-soft p-3"><div class="small text-muted">Transmission</div><div class="fw-bold"><?php echo esc($car['transmission']); ?></div></div></div>
            <div class="col-md-4"><div class="card card-soft p-3"><div class="small text-muted">Mileage</div><div class="fw-bold"><?php echo (int)$car['mileage_km']; ?> km</div></div></div>
          </div>

          <h5 class="fw-bold mt-4">Description</h5>
          <p class="text-muted mb-0"><?php echo esc($car['description']); ?></p>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card card-soft">
        <div class="card-body p-4">
          <h5 class="fw-bold">Send Inquiry</h5>
          <p class="text-muted small">Ask about test drive, price, service history, etc.</p>
          <form method="post">
            <div class="mb-2"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
            <div class="mb-2"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
            <div class="mb-2"><label class="form-label">Phone</label><input class="form-control" name="phone"></div>
            <div class="mb-2"><label class="form-label">Message</label><textarea class="form-control" name="message" rows="3" required>Interested in <?php echo esc($car['title']); ?>.</textarea></div>
            <button class="btn btn-primary w-100">Submit Inquiry</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
