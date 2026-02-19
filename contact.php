<?php require_once __DIR__ . '/includes/head.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<?php
if (is_post()) {
  $name=trim(isset($_POST['name'])?$_POST['name']:'');
  $email=trim(isset($_POST['email'])?$_POST['email']:'');
  $phone=trim(isset($_POST['phone'])?$_POST['phone']:'');
  $message=trim(isset($_POST['message'])?$_POST['message']:'');

  if ($name==='' || $email==='' || $message==='') {
    flash_set('err','Name, Email and Message are required.');
    redirect_to('contact.php');
  }

  $stmt=db()->prepare("INSERT INTO inquiries (user_id,car_id,name,email,phone,message,status) VALUES (NULL,NULL,?,?,?,?, 'New')");
  $stmt->bind_param("ssss", $name,$email,$phone,$message);
  $stmt->execute();

  flash_set('ok','Thanks! Your message has been received.');
  redirect_to('contact.php');
}
?>

<div class="container my-4">
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card card-soft">
        <div class="card-body p-4 p-md-5">
          <h3 class="fw-bold">Contact Us</h3>
          <p class="text-muted">Send a message and we’ll get back quickly.</p>
          <form method="post" class="mt-3">
            <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
            <div class="mb-3"><label class="form-label">Phone</label><input class="form-control" name="phone"></div>
            <div class="mb-3"><label class="form-label">Message</label><textarea class="form-control" name="message" rows="4" required></textarea></div>
            <button class="btn btn-primary w-100">Send</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card card-soft h-100">
        <div class="card-body p-4 p-md-5">
          <h5 class="fw-bold">Showroom</h5>
          <div class="text-muted">Main Road, City Center</div>
          <div class="text-muted">+91 90000 00000</div>
          <div class="text-muted">support@carshowroom.demo</div>
          <hr>
          <h6 class="fw-bold">Hours</h6>
          <div class="text-muted">Mon–Sat: 10:00 AM – 7:00 PM</div>
          <div class="text-muted">Sunday: Closed</div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
