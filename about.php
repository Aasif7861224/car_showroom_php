<?php require_once __DIR__ . '/includes/head.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<div class="container my-4">
  <div class="card card-soft">
    <div class="card-body p-4 p-md-5">
      <h2 class="fw-bold mb-2">About Our Showroom</h2>
      <p class="text-muted">
        We are a customer-first car showroom delivering reliable cars and a smooth buying experience.
        Our focus is simple: honest listings, transparent pricing, and quick customer support.
      </p>
      <div class="row g-4 mt-2">
        <div class="col-md-6">
          <h5 class="fw-bold">Our Services</h5>
          <ul class="text-muted">
            <li>Verified pre-owned cars with clear history</li>
            <li>Test drive assistance</li>
            <li>Sell your car with quick evaluation</li>
            <li>Documentation & ownership transfer guidance</li>
          </ul>
        </div>
        <div class="col-md-6">
          <h5 class="fw-bold">Why Choose Us</h5>
          <ul class="text-muted">
            <li>Trusted inventory management</li>
            <li>Professional after-sale support</li>
            <li>Fast response on inquiries</li>
            <li>Customer satisfaction first</li>
          </ul>
        </div>
      </div>
      <div class="mt-4">
        <a class="btn btn-primary" href="<?php echo BASE_URL; ?>user/buy_cars.php">Explore Cars</a>
        <a class="btn btn-outline-primary ms-2" href="<?php echo BASE_URL; ?>contact.php">Contact Us</a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
