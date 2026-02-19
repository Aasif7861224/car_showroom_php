<?php
require_once __DIR__ . '/../app/init.php';
require_admin();

$id = (int)(isset($_GET['id']) ? $_GET['id'] : 0);
if ($id<=0) { flash_set('err','Invalid request.'); redirect_to('admin/sell_requests.php'); }

$stmt = db()->prepare("SELECT id,car_title,status FROM sell_requests WHERE id=? LIMIT 1");
$stmt->bind_param("i",$id);
$stmt->execute();
$req = $stmt->get_result()->fetch_assoc();

if (!$req) { flash_set('err','Request not found.'); redirect_to('admin/sell_requests.php'); }
if ($req['status'] !== 'Pending') { flash_set('err','Only Pending requests can be rejected.'); redirect_to('admin/sell_requests.php'); }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $reason = trim(isset($_POST['reason']) ? $_POST['reason'] : '');
  if ($reason==='') {
    flash_set('err','Reject reason is required.');
    redirect_to('admin/sell_requests_reject.php?id='.$id);
  }

  $stmt2 = db()->prepare("UPDATE sell_requests SET status='Rejected', reject_reason=?, approved_car_id=NULL WHERE id=? LIMIT 1");
  $stmt2->bind_param("si",$reason,$id);
  $stmt2->execute();

  flash_set('ok','Request rejected.');
  redirect_to('admin/sell_requests.php');
}
?>

<?php require_once __DIR__ . '/../includes/head.php'; ?>
<?php require_once __DIR__ . '/../includes/admin_navbar.php'; ?>
<?php require_once __DIR__ . '/../includes/admin_sidebar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Reject Sell Request</h3>
      <p class="text-muted mb-0"><?php echo esc($req['car_title']); ?></p>
    </div>
    <a class="btn btn-outline-dark" href="<?php echo BASE_URL; ?>admin/sell_requests.php">Back</a>
  </div>

  <div class="card card-soft mt-3">
    <div class="card-body">
      <form method="post">
        <label class="form-label">Reason *</label>
        <textarea class="form-control" name="reason" rows="4" required placeholder="Example: documents not available / price unrealistic / car condition mismatch..."></textarea>
        <button class="btn btn-danger mt-3">Reject</button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>