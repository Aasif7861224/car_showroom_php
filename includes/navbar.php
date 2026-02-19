<?php $u=current_user(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>">Car Showroom</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>user/buy_cars.php">Buy Cars</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>user/sell_car.php">Sell Car</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
        <?php if ($u && $u['role']==='Admin'): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/dashboard.php">Admin</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if ($u): ?>
          <li class="nav-item"><span class="navbar-text text-white-50 me-2">Hi, <?php echo esc($u['full_name']); ?></span></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>user/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>user/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>user/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-3">
  <?php if ($m=flash_get('err')): ?><div class="alert alert-danger"><?php echo esc($m); ?></div><?php endif; ?>
  <?php if ($m=flash_get('ok')): ?><div class="alert alert-success"><?php echo esc($m); ?></div><?php endif; ?>
</div>
