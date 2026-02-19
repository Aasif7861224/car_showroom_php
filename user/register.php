<?php
require_once __DIR__ . '/../app/init.php';

if (is_post()) {
  $name = trim(isset($_POST['full_name'])?$_POST['full_name']:'');
  $email = trim(isset($_POST['email'])?$_POST['email']:'');
  $mobile = trim(isset($_POST['mobile'])?$_POST['mobile']:'');
  $pass = isset($_POST['password'])?$_POST['password']:'';
  $cpass = isset($_POST['confirm_password'])?$_POST['confirm_password']:'';

  if ($name==='' || $email==='' || $pass==='' || $cpass==='') {
    flash_set('err','All required fields must be filled.');
    redirect_to('user/register.php');
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash_set('err','Invalid email address.');
    redirect_to('user/register.php');
  }
  if ($pass !== $cpass) {
    flash_set('err','Passwords do not match.');
    redirect_to('user/register.php');
  }
  if (strlen($pass) < 6) {
    flash_set('err','Password must be at least 6 characters.');
    redirect_to('user/register.php');
  }

  $stmt = db()->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($res && $res->fetch_assoc()) {
    flash_set('err','Email already registered. Please login.');
    redirect_to('user/login.php');
  }

  $hash = password_make($pass);
  $role = 'Customer';
  $mobileVal = ($mobile==='') ? null : $mobile;

  $stmt = db()->prepare("INSERT INTO users (full_name,email,password_hash,role,mobile,is_active) VALUES (?,?,?,?,?,1)");
  $stmt->bind_param("sssss", $name, $email, $hash, $role, $mobileVal);
  $stmt->execute();

  flash_set('ok','Registration successful! Please login.');
  redirect_to('user/login.php');
}
?>

<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card card-soft">
        <div class="card-body p-4 p-md-5">
          <h3 class="fw-bold mb-1">Register</h3>
          <p class="text-muted">Create your customer account.</p>

          <form method="post" class="mt-3" autocomplete="off">
            <div class="mb-3">
              <label class="form-label">Full Name *</label>
              <input class="form-control" name="full_name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email *</label>
              <input class="form-control" type="email" name="email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mobile (optional)</label>
              <input class="form-control" name="mobile">
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Password *</label>
                <input class="form-control" type="password" name="password" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Confirm Password *</label>
                <input class="form-control" type="password" name="confirm_password" required>
              </div>
            </div>
            <button class="btn btn-primary w-100 mt-3">Create Account</button>
          </form>

          <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none">Already have an account? Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
