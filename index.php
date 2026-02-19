<?php require_once __DIR__ . '/includes/head.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<?php
$kpi=array('cars'=>0,'customers'=>0,'orders'=>0,'inquiries'=>0);
$r=db()->query("SELECT COUNT(*) c FROM cars"); if($r) $kpi['cars']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM users WHERE role='Customer'"); if($r) $kpi['customers']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM orders"); if($r) $kpi['orders']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM inquiries"); if($r) $kpi['inquiries']=(int)$r->fetch_assoc()['c'];

$cars=array();
$r=db()->query("SELECT c.id,c.title,c.price,c.location,c.car_year,cat.name category
               FROM cars c LEFT JOIN categories cat ON cat.id=c.category_id
               WHERE c.status='Published' ORDER BY c.id DESC LIMIT 6");
if($r){ while($row=$r->fetch_assoc()) $cars[]=$row; }
?>

<div class="container my-4">
  <div class="hero p-4 p-md-5">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <h1 class="display-5 fw-bold">A Real-World Car Showroom System</h1>
        <p class="lead text-white-50 mb-4">
          Browse verified cars, send inquiries, place orders, or submit a sell request.
          Admin manages inventory, customers, orders, and inquiries with a professional dashboard.
        </p>
        <div class="d-flex gap-2 flex-wrap">
          <a class="btn btn-primary btn-lg" href="<?php echo BASE_URL; ?>user/buy_cars.php">Explore Cars</a>
          <a class="btn btn-outline-light btn-lg" href="<?php echo BASE_URL; ?>user/sell_car.php">Sell Your Car</a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="row g-3">
          <div class="col-6"><div class="card card-soft p-3"><div class="small text-muted">Cars</div><div class="h3 mb-0"><?php echo $kpi['cars']; ?></div></div></div>
          <div class="col-6"><div class="card card-soft p-3"><div class="small text-muted">Customers</div><div class="h3 mb-0"><?php echo $kpi['customers']; ?></div></div></div>
          <div class="col-6"><div class="card card-soft p-3"><div class="small text-muted">Orders</div><div class="h3 mb-0"><?php echo $kpi['orders']; ?></div></div></div>
          <div class="col-6"><div class="card card-soft p-3"><div class="small text-muted">Inquiries</div><div class="h3 mb-0"><?php echo $kpi['inquiries']; ?></div></div></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row align-items-end mt-4">
    <div class="col-lg-8">
      <h3 class="fw-bold">Featured Cars</h3>
      <p class="text-muted mb-0">Latest inventory from our database.</p>
    </div>
    <div class="col-lg-4 text-lg-end">
      <a class="btn btn-outline-primary" href="<?php echo BASE_URL; ?>user/buy_cars.php">View All</a>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <?php foreach($cars as $c): ?>
      <div class="col-md-6 col-lg-4">
        <div class="card card-soft h-100">
          <div class="card-body">
            <span class="badge badge-soft"><?php echo esc($c['category'] ?: 'Car'); ?></span>
            <h5 class="mt-2 mb-1"><?php echo esc($c['title']); ?></h5>
            <div class="text-muted small"><?php echo (int)$c['car_year']; ?> • <?php echo esc($c['location']); ?></div>
            <div class="h5 mt-3">₹<?php echo number_format((float)$c['price']); ?></div>
            <a class="btn btn-primary w-100 mt-3" href="<?php echo BASE_URL; ?>user/car_details.php?id=<?php echo (int)$c['id']; ?>">View Details</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row g-4 mt-5">
    <div class="col-md-4"><div class="card card-soft p-4 h-100"><h5 class="fw-bold">Inspection & Trust</h5><p class="text-muted mb-0">Every listing is reviewed for details and pricing transparency.</p></div></div>
    <div class="col-md-4"><div class="card card-soft p-4 h-100"><h5 class="fw-bold">Fast Responses</h5><p class="text-muted mb-0">Inquiries are tracked so customers get quick updates.</p></div></div>
    <div class="col-md-4"><div class="card card-soft p-4 h-100"><h5 class="fw-bold">Admin Control</h5><p class="text-muted mb-0">Manage cars, categories, users, orders & sell requests.</p></div></div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
