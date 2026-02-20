<?php require_once __DIR__ . '/../app/init.php'; ?>
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="adminSidebarLabel">Admin Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="list-group list-group-flush">
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/dashboard.php">Dashboard</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/cars/index.php">Cars</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/categories/index.php">Categories</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/sell_requests/index.php">Sell Requests</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/orders/index.php">Orders</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/users/index.php">Users</a>
      <a class="list-group-item list-group-item-action bg-dark text-white border-secondary" href="<?php echo BASE_URL; ?>admin/analytics/dashboard.php">Analytics</a>
    </div>
  </div>
</div>
