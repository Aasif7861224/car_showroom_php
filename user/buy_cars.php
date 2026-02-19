<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<?php
$qry=trim(isset($_GET['q'])?$_GET['q']:'');
$cat=(int)(isset($_GET['cat'])?$_GET['cat']:0);

$cats=array();
$r=db()->query("SELECT id,name FROM categories WHERE is_active=1 ORDER BY name");
if($r){ while($row=$r->fetch_assoc()) $cats[]=$row; }

$where="WHERE c.status='Published'";
if($qry!==''){
  $s=q($qry);
  $where.=" AND (c.title LIKE '%$s%' OR c.brand LIKE '%$s%' OR c.model LIKE '%$s%' OR c.location LIKE '%$s%')";
}
if($cat>0){
  $where.=" AND c.category_id=".$cat;
}

$cars=array();
$sql="SELECT c.id,c.title,c.price,c.location,c.car_year,c.fuel,c.transmission,cat.name category
      FROM cars c LEFT JOIN categories cat ON cat.id=c.category_id
      $where ORDER BY c.id DESC";
$r=db()->query($sql);
if($r){ while($row=$r->fetch_assoc()) $cars[]=$row; }
?>

<div class="container my-4">
  <div class="d-flex flex-wrap gap-2 align-items-end justify-content-between">
    <div>
      <h3 class="fw-bold mb-1">Buy Cars</h3>
      <p class="text-muted mb-0">Search & filter cars from our inventory.</p>
    </div>
    <form class="row g-2" method="get">
      <div class="col-auto">
        <input class="form-control" name="q" placeholder="Search: brand, model, city..." value="<?php echo esc($qry); ?>">
      </div>
      <div class="col-auto">
        <select class="form-select" name="cat">
          <option value="0">All Categories</option>
          <?php foreach($cats as $c): ?>
            <option value="<?php echo (int)$c['id']; ?>" <?php echo ($cat==(int)$c['id'])?'selected':''; ?>>
              <?php echo esc($c['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto">
        <button class="btn btn-primary">Filter</button>
      </div>
    </form>
  </div>

  <div class="row g-3 mt-2">
    <?php foreach($cars as $c): ?>
      <div class="col-md-6 col-lg-4">
        <div class="card card-soft h-100">
          <div class="card-body">
            <span class="badge badge-soft"><?php echo esc($c['category'] ?: 'Car'); ?></span>
            <h5 class="mt-2 mb-1"><?php echo esc($c['title']); ?></h5>
            <div class="text-muted small"><?php echo (int)$c['car_year']; ?> • <?php echo esc($c['location']); ?></div>
            <div class="text-muted small"><?php echo esc($c['fuel']); ?> • <?php echo esc($c['transmission']); ?></div>
            <div class="h5 mt-3">₹<?php echo number_format((float)$c['price']); ?></div>
            <a class="btn btn-primary w-100 mt-3" href="<?php echo BASE_URL; ?>user/car_details.php?id=<?php echo (int)$c['id']; ?>">View Details</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if(count($cars)===0): ?>
      <div class="col-12">
        <div class="alert alert-warning">No cars found for your filter.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
