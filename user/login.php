<?php
require_once __DIR__ . '/../app/init.php';

// Already logged-in? redirect based on role
$cu = current_user();
if ($cu) {
  if (($cu['role'] ?? '') === 'Admin') redirect_to('admin/dashboard.php');
  redirect_to('');
}

if (is_post()) {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  if ($email === '' || $pass === '') {
    flash_set('err', 'Email and password are required.');
    redirect_to('user/login.php');
  }

  // Find user (active only)
  $stmt = db()->prepare("SELECT id, full_name, email, password_hash, role, is_active FROM users WHERE email=? LIMIT 1");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $u = $stmt->get_result()->fetch_assoc();

  // Security: generic error (don’t reveal if email exists)
  if (!$u || (int)$u['is_active'] !== 1 || !password_check($pass, $u['password_hash'])) {
    flash_set('err', 'Invalid login details.');
    redirect_to('user/login.php');
  }

  // Login success
  login_user($u);

  // Role-based redirect (Admin from same login page ✅)
  if (($u['role'] ?? '') === 'Admin') {
    redirect_to('admin/dashboard.php');
  }

  // user home (or you can set user/dashboard.php)
  redirect_to('');
}
?>

<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-5">
      <div class="card card-soft">
        <div class="card-body p-4 p-md-5">
          <h3 class="fw-bold mb-1">Login</h3>
          <p class="text-muted">Access your account (Admin & User).</p>

          <?php if($m = flash_get('err')): ?>
            <div class="alert alert-danger"><?php echo esc($m); ?></div>
          <?php endif; ?>
          <?php if($m = flash_get('ok')): ?>
            <div class="alert alert-success"><?php echo esc($m); ?></div>
          <?php endif; ?>

          <form method="post" class="mt-3" autocomplete="off">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input class="form-control" type="email" name="email" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input class="form-control" type="password" name="password" required>
            </div>

            <button class="btn btn-primary w-100">Login</button>
          </form>

          <div class="text-center mt-3">
            <a href="register.php" class="text-decoration-none">New here? Create an account</a>
          </div>

          <div class="text-center mt-2">
            <a href="forgot_password.php" class="text-decoration-none small">Forgot Password?</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>