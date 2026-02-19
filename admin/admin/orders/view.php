<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id = (int)(isset($_GET['id']) ? $_GET['id'] : 0);
if ($id<=0) { flash_set('err','Invalid order.'); redirect_to('admin/orders/index.php'); }

$sql="SELECT o.*, u.full_name,u.email,u.mobile,
            c.title car_title,c.brand,c.model,c.car_year,c.fuel,c.transmission,c.location
     FROM orders o
     LEFT JOIN users u ON u.id=o.user_id
     LEFT JOIN cars c ON c.id=o.car_id
     WHERE o.id=$id
     LIMIT 1";
$r = db()->query($sql);
$order = $r ? $r->fetch_assoc() : null;
if (!$order) { flash_set('err','Order not found.'); redirect_to('admin/orders/index.php'); }
?>

<?php require_once __DIR__ . '/../../includes/head.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="container-fluid my-4 px-3 px-md-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Order #<?php echo (int)$order['id']; ?></h3>
      <p class="text-muted mb-0">View order details and print receipt.</p>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/orders/index.php">Back</a>
      <a class="btn btn-dark" target="_blank" href="<?php echo BASE_URL; ?>admin/orders/receipt.php?id=<?php echo (int)$order['id']; ?>">Print Receipt</a>
    </div>
  </div>

  <div class="row g-3 mt-2">
    <div class="col-lg-6">
      <div class="card card-soft h-100"><div class="card-body">
        <h5 class="fw-bold">Customer</h5>
        <div class="mt-2">
          <div class="fw-semibold"><?php echo esc($order['full_name'] ?: '—'); ?></div>
          <div class="text-muted small"><?php echo esc($order['email'] ?: ''); ?></div>
          <div class="text-muted small"><?php echo esc($order['mobile'] ?: ''); ?></div>
        </div>
        <hr>
        <h5 class="fw-bold">Order</h5>
        <div class="d-flex justify-content-between"><div class="text-muted">Amount</div><div class="fw-bold">₹<?php echo number_format((float)$order['amount']); ?></div></div>
        <div class="d-flex justify-content-between mt-1"><div class="text-muted">Status</div><div class="fw-semibold"><?php echo esc($order['status']); ?></div></div>
        <div class="d-flex justify-content-between mt-1"><div class="text-muted">Created</div><div class="text-muted"><?php echo esc($order['created_at']); ?></div></div>
        <hr>
        <form class="d-flex gap-2" method="post" action="<?php echo BASE_URL; ?>admin/orders/update_status.php">
          <input type="hidden" name="id" value="<?php echo (int)$order['id']; ?>">
          <select class="form-select" name="status">
            <?php foreach(array('Pending','Confirmed','Paid','Delivered','Cancelled') as $s): ?>
              <option <?php echo ($order['status']===$s)?'selected':''; ?>><?php echo $s; ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-dark" style="min-width:110px;">Update</button>
        </form>
      </div></div>
    </div>

    <div class="col-lg-6">
      <div class="card card-soft h-100"><div class="card-body">
        <h5 class="fw-bold">Car</h5>
        <div class="mt-2">
          <div class="fw-bold"><?php echo esc($order['car_title'] ?: '—'); ?></div>
          <div class="text-muted small"><?php echo esc($order['brand']); ?> <?php echo esc($order['model']); ?> • <?php echo (int)$order['car_year']; ?></div>
          <div class="text-muted small"><?php echo esc($order['fuel']); ?> • <?php echo esc($order['transmission']); ?> • <?php echo esc($order['location']); ?></div>
        </div>
        <div class="alert alert-info small mt-3 mb-0">Note: You can auto-mark car as Sold when status becomes Delivered/Paid (optional enhancement).</div>
      </div></div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
