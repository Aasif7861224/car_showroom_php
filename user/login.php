<?php
require_once __DIR__ . '/../app/init.php';

if (is_post()) {
  $email = trim(isset($_POST['email'])?$_POST['email']:'');
  $pass  = isset($_POST['password'])?$_POST['password']:'';

  if ($email==='' || $pass==='') {
    flash_set('err','Email and password are required.');
    redirect_to('user/login.php');
  }

  $stmt = db()->prepare("SELECT * FROM users WHERE email=? AND is_active=1 LIMIT 1");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $u = $res ? $res->fetch_assoc() : null;

  if (!$u || !password_check($pass, $u['password_hash'])) {
    flash_set('err','Invalid login details.');
    redirect_to('user/login.php');
  }

  login_user($u);
  if ($u['role'] === 'Admin') redirect_to('admin/dashboard.php');
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
          <p class="text-muted">Access your account.</p>

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
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
