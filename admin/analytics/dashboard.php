<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$stats=array('revenue'=>0,'paid'=>0,'pending'=>0,'delivered'=>0,'cancelled'=>0);
$r=db()->query("SELECT COALESCE(SUM(amount),0) rev FROM orders WHERE status IN ('Paid','Delivered')"); if($r) $stats['revenue']=(float)$r->fetch_assoc()['rev'];
$r=db()->query("SELECT COUNT(*) c FROM orders WHERE status='Paid'"); if($r) $stats['paid']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM orders WHERE status='Pending'"); if($r) $stats['pending']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM orders WHERE status='Delivered'"); if($r) $stats['delivered']=(int)$r->fetch_assoc()['c'];
$r=db()->query("SELECT COUNT(*) c FROM orders WHERE status='Cancelled'"); if($r) $stats['cancelled']=(int)$r->fetch_assoc()['c'];

$top=null;
$r=db()->query("SELECT id,title,view_count FROM cars WHERE is_deleted=0 ORDER BY view_count DESC, id DESC LIMIT 1"); if($r) $top=$r->fetch_assoc();
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container-fluid my-4 px-3 px-md-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div><h3 class="fw-bold mb-1">Analytics</h3><p class="text-muted mb-0">Business overview (demo).</p></div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/dashboard.php">Back</a>
  </div>

  <div class="row g-3 mt-2">
    <div class="col-12 col-md-4"><div class="card card-soft p-3 h-100"><div class="small text-muted">Revenue (Paid/Delivered)</div><div class="h4 mb-0">₹<?php echo number_format($stats['revenue']); ?></div></div></div>
    <div class="col-6 col-md-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Paid</div><div class="h4 mb-0"><?php echo $stats['paid']; ?></div></div></div>
    <div class="col-6 col-md-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Pending</div><div class="h4 mb-0"><?php echo $stats['pending']; ?></div></div></div>
    <div class="col-6 col-md-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Delivered</div><div class="h4 mb-0"><?php echo $stats['delivered']; ?></div></div></div>
    <div class="col-6 col-md-2"><div class="card card-soft p-3 h-100"><div class="small text-muted">Cancelled</div><div class="h4 mb-0"><?php echo $stats['cancelled']; ?></div></div></div>
  </div>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <h5 class="fw-bold">Most Viewed Car</h5>
      <?php if($top): ?>
        <div class="fw-bold"><?php echo esc($top['title']); ?></div>
        <div class="text-muted small">Views: <?php echo (int)$top['view_count']; ?></div>
      <?php else: ?>
        <div class="text-muted">No cars found.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
