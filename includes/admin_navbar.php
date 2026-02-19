<?php require_once __DIR__ . '/../app/init.php'; $u=current_user(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container-fluid px-3">
    <button class="btn btn-outline-light me-2 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
      ☰
    </button>

    <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>admin/dashboard.php">Admin Panel</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminTopNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminTopNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/cars/index.php">Cars</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/categories.php">Categories</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/sell_requests.php">Sell Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/orders.php">Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/inquiries.php">Inquiries</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/users.php">Users</a></li>
      </ul>

      <div class="d-flex align-items-center gap-2">
        <span class="text-white-50 small d-none d-md-inline">
          <?php echo $u ? esc($u['full_name']) : 'Admin'; ?>
        </span>
        <a class="btn btn-outline-light btn-sm" href="<?php echo BASE_URL; ?>user/logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>