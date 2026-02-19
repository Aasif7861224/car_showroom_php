<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<?php
require_login();
$u=current_user();

if(is_post()){
  $title=trim(isset($_POST['car_title'])?$_POST['car_title']:'');
  $year=(int)(isset($_POST['car_year'])?$_POST['car_year']:0);
  $fuel=trim(isset($_POST['fuel'])?$_POST['fuel']:'');
  $trans=trim(isset($_POST['transmission'])?$_POST['transmission']:'');
  $price=(float)(isset($_POST['expected_price'])?$_POST['expected_price']:0);
  $mileage=(int)(isset($_POST['mileage_km'])?$_POST['mileage_km']:0);
  $loc=trim(isset($_POST['location'])?$_POST['location']:'');
  $notes=trim(isset($_POST['notes'])?$_POST['notes']:'');

  if($title==='' || $year<=0 || $fuel==='' || $trans==='' || $price<=0 || $loc===''){
    flash_set('err','Please fill all required fields.');
    redirect_to('user/sell_car.php');
  }

  $stmt=db()->prepare("INSERT INTO sell_requests (user_id,car_title,car_year,fuel,transmission,expected_price,mileage_km,location,notes,status) VALUES (?,?,?,?,?,?,?,?,?,'Pending')");
  $uid=(int)$u['id'];
  $stmt->bind_param("isissdisss",$uid,$title,$year,$fuel,$trans,$price,$mileage,$loc,$notes);
  $stmt->execute();

  flash_set('ok','Sell request submitted. Admin will review it soon.');
  redirect_to('user/sell_car.php');
}

$req=array();
$uid=(int)$u['id'];
$r=db()->query("SELECT * FROM sell_requests WHERE user_id=$uid ORDER BY id DESC LIMIT 10");
if($r){ while($row=$r->fetch_assoc()) $req[]=$row; }
?>

<div class="container my-4">
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card card-soft">
        <div class="card-body p-4 p-md-5">
          <h3 class="fw-bold">Sell Your Car</h3>
          <p class="text-muted">Submit details for evaluation. Admin will approve/reject.</p>

          <form method="post">
            <div class="mb-2"><label class="form-label">Car Title*</label><input class="form-control" name="car_title" required></div>
            <div class="row g-2">
              <div class="col-md-6 mb-2"><label class="form-label">Year*</label><input class="form-control" name="car_year" type="number" required></div>
              <div class="col-md-6 mb-2"><label class="form-label">Expected Price*</label><input class="form-control" name="expected_price" type="number" required></div>
            </div>
            <div class="row g-2">
              <div class="col-md-6 mb-2">
                <label class="form-label">Fuel*</label>
                <select class="form-select" name="fuel" required>
                  <option value="">Select</option>
                  <option>Petrol</option><option>Diesel</option><option>CNG</option><option>Electric</option><option>Hybrid</option>
                </select>
              </div>
              <div class="col-md-6 mb-2">
                <label class="form-label">Transmission*</label>
                <select class="form-select" name="transmission" required>
                  <option value="">Select</option>
                  <option>Manual</option><option>Automatic</option>
                </select>
              </div>
            </div>
            <div class="row g-2">
              <div class="col-md-6 mb-2"><label class="form-label">Mileage (km)</label><input class="form-control" name="mileage_km" type="number"></div>
              <div class="col-md-6 mb-2"><label class="form-label">Location*</label><input class="form-control" name="location" required></div>
            </div>
            <div class="mb-2"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="3"></textarea></div>
            <button class="btn btn-primary w-100 mt-2">Submit Request</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card card-soft h-100">
        <div class="card-body p-4 p-md-5">
          <h5 class="fw-bold">My Recent Sell Requests</h5>
          <div class="table-responsive mt-3">
            <table class="table align-middle">
              <thead><tr><th>Car</th><th>Status</th><th>Price</th></tr></thead>
              <tbody>
                <?php foreach($req as $r): ?>
                  <tr>
                    <td>
                      <div class="fw-bold"><?php echo esc($r['car_title']); ?></div>
                      <div class="text-muted small"><?php echo (int)$r['car_year']; ?> • <?php echo esc($r['location']); ?></div>
                    </td>
                    <td><span class="badge text-bg-<?php echo ($r['status']==='Approved')?'success':(($r['status']==='Rejected')?'danger':'warning'); ?>">
                      <?php echo esc($r['status']); ?></span></td>
                    <td>₹<?php echo number_format((float)$r['expected_price']); ?></td>
                  </tr>
                <?php endforeach; ?>
                <?php if(count($req)===0): ?>
                  <tr><td colspan="3" class="text-muted">No requests yet.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="text-muted small">Tip: Approved requests can be added to inventory by admin.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
