<?php require_once __DIR__ . '/../app/init.php'; ?>
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="adminSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Admin Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="list-group list-group-flush">
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/dashboard.php">Dashboard</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/cars/index.php">Cars</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/categories.php">Categories</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/sell_requests.php">Sell Requests</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/orders.php">Orders</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/inquiries.php">Inquiries</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/users.php">Users</a>
    </div>
  </div>
</div>