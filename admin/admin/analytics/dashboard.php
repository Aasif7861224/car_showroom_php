<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

// Simple placeholders (upgrade later with charts)
$k=[];
$r=db()->query("SELECT COUNT(*) c FROM orders"); $k['orders']=$r?(int)$r->fetch_assoc()['c']:0;
$r=db()->query("SELECT COALESCE(SUM(amount),0) s FROM orders WHERE status IN ('Paid','Delivered')"); $k['revenue']=$r?(float)$r->fetch_assoc()['s']:0;
?>
<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>
<div class="container-fluid my-4 px-3 px-md-4">
  <h3 class="fw-bold mb-1">Analytics</h3>
  <p class="text-muted">Basic KPIs (charts can be added next).</p>
  <div class="row g-3">
    <div class="col-12 col-md-6 col-xl-3"><div class="card card-soft p-3 h-100"><div class="small text-muted">Total Orders</div><div class="h4 mb-0"><?php echo (int)$k['orders']; ?></div></div></div>
    <div class="col-12 col-md-6 col-xl-3"><div class="card card-soft p-3 h-100"><div class="small text-muted">Revenue (Paid/Delivered)</div><div class="h4 mb-0">₹<?php echo number_format((float)$k['revenue']); ?></div></div></div>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
