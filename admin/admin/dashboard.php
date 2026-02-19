<?php
require_once __DIR__ . '/../app/init.php';
require_admin();

$kpi=array('cars'=>0,'categories'=>0,'customers'=>0,'sell'=>0,'orders'=>0,'inquiries'=>0);
$r=db()->query("SELECT COUNT(*) c FROM cars WHERE is_deleted=0"); if($r) $kpi['cars']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM categories"); if($r) $kpi['categories']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM users WHERE role='Customer'"); if($r) $kpi['customers']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM sell_requests"); if($r) $kpi['sell']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM orders"); if($r) $kpi['orders']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM inquiries"); if($r) $kpi['inquiries']=(int)$r->fetch_assoc()['c'];

$recent=array();
$r=db()->query("SELECT id,car_title,status,expected_price,created_at FROM sell_requests ORDER BY id DESC LIMIT 5");
if($r){ while($row=$r->fetch_assoc()) $recent[]=$row; }

function badgeSell($s){
  if($s==='Approved') return 'success';
  if($s==='Rejected') return 'danger';
  return 'warning';
}
?>

<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../includes/admin_sidebar.php'; ?>

<div class="container-fluid my-4 px-3 px-md-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h2 class="fw-bold mb-1">Admin Dashboard</h2>
      <p class="text-muted mb-0">Overview of showroom operations and recent activity.</p>
    </div>
  </div>

  <div class="row g-3 mt-2">
    <div class="col-6 col-md-4 col-xl-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Cars</div><div class="h4 mb-0"><?php echo $kpi['cars']; ?></div></div></div>
    <div class="col-6 col-md-4 col-xl-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Categories</div><div class="h4 mb-0"><?php echo $kpi['categories']; ?></div></div></div>
    <div class="col-6 col-md-4 col-xl-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Customers</div><div class="h4 mb-0"><?php echo $kpi['customers']; ?></div></div></div>
    <div class="col-6 col-md-4 col-xl-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Sell Requests</div><div class="h4 mb-0"><?php echo $kpi['sell']; ?></div></div></div>
    <div class="col-6 col-md-4 col-xl-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Orders</div><div class="h4 mb-0"><?php echo $kpi['orders']; ?></div></div></div>
    <div class="col-6 col-md-4 col-xl-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Inquiries</div><div class="h4 mb-0"><?php echo $kpi['inquiries']; ?></div></div></div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-xl-8">
      <div class="card card-soft h-100">
        <div class="card-body">
          <h5 class="fw-bold mb-2">Quick Actions</h5>
          <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-dark" href="<?php echo BASE_URL; ?>admin/cars/create.php">+ Add Car</a>
            <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/cars/index.php">Cars</a>
            <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/categories/index.php">Categories</a>
            <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/users/index.php">Users</a>
            <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/sell_requests/index.php">Sell Requests</a>
            <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/orders/index.php">Orders</a>
          </div>
          <div class="text-muted small mt-3">Tip: Keep cars in Draft until images & details are added. Publish only after verification.</div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card card-soft h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Recent Sell Requests</h5>
            <a class="small text-decoration-none" href="<?php echo BASE_URL; ?>admin/sell_requests/index.php">View all</a>
          </div>
          <div class="table-responsive mt-3">
            <table class="table table-sm align-middle">
              <thead><tr><th>Car</th><th class="text-end">Status</th></tr></thead>
              <tbody>
                <?php foreach($recent as $r): ?>
                  <tr>
                    <td>
                      <div class="fw-bold small"><?php echo esc($r['car_title']); ?></div>
                      <div class="text-muted small">₹<?php echo number_format((float)$r['expected_price']); ?></div>
                    </td>
                    <td class="text-end"><span class="badge text-bg-<?php echo badgeSell($r['status']); ?>"><?php echo esc($r['status']); ?></span></td>
                  </tr>
                <?php endforeach; ?>
                <?php if(count($recent)===0): ?><tr><td colspan="2" class="text-muted">No data.</td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
