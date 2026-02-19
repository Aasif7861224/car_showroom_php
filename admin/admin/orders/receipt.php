<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id = (int)(isset($_GET['id']) ? $_GET['id'] : 0);
if ($id<=0) { exit('Invalid'); }

$sql="SELECT o.*, u.full_name,u.email,u.mobile,
            c.title car_title,c.brand,c.model,c.car_year,c.fuel,c.transmission,c.location
     FROM orders o
     LEFT JOIN users u ON u.id=o.user_id
     LEFT JOIN cars c ON c.id=o.car_id
     WHERE o.id=$id
     LIMIT 1";
$r = db()->query($sql);
$o = $r ? $r->fetch_assoc() : null;
if (!$o) { exit('Not found'); }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Receipt #<?php echo (int)$o['id']; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family: Arial, sans-serif; margin:20px;}
    .box{max-width:900px;margin:auto;border:1px solid #ddd;padding:18px;border-radius:10px;}
    .row{display:flex;gap:20px;flex-wrap:wrap;}
    .col{flex:1;min-width:260px;}
    h2{margin:0 0 8px 0;}
    .muted{color:#666;font-size:13px;}
    table{width:100%;border-collapse:collapse;margin-top:12px;}
    th,td{border:1px solid #ddd;padding:10px;text-align:left;}
    th{background:#f5f5f5;}
    .total{font-size:18px;font-weight:bold;text-align:right;}
    @media print{button{display:none;}}
  </style>
</head>
<body>
  <div class="box">
    <div class="row">
      <div class="col">
        <h2>Car Showroom</h2>
        <div class="muted">Receipt / Invoice (Print)</div>
        <div class="muted">Receipt #: <?php echo (int)$o['id']; ?></div>
        <div class="muted">Date: <?php echo htmlspecialchars($o['created_at']); ?></div>
      </div>
      <div class="col">
        <div style="text-align:right"><button onclick="window.print()">Print</button></div>
      </div>
    </div>

    <hr>

    <div class="row">
      <div class="col">
        <h3>Customer</h3>
        <div><b><?php echo htmlspecialchars($o['full_name']); ?></b></div>
        <div class="muted"><?php echo htmlspecialchars($o['email']); ?></div>
        <div class="muted"><?php echo htmlspecialchars($o['mobile']); ?></div>
      </div>
      <div class="col">
        <h3>Order</h3>
        <div class="muted">Status: <b><?php echo htmlspecialchars($o['status']); ?></b></div>
        <div class="muted">Amount: <b>₹<?php echo number_format((float)$o['amount']); ?></b></div>
      </div>
    </div>

    <table>
      <thead><tr><th>Car</th><th>Details</th><th>Location</th><th>Amount</th></tr></thead>
      <tbody>
        <tr>
          <td><b><?php echo htmlspecialchars($o['car_title']); ?></b></td>
          <td class="muted"><?php echo htmlspecialchars($o['brand'].' '.$o['model']); ?> • <?php echo (int)$o['car_year']; ?> • <?php echo htmlspecialchars($o['fuel']); ?> • <?php echo htmlspecialchars($o['transmission']); ?></td>
          <td><?php echo htmlspecialchars($o['location']); ?></td>
          <td>₹<?php echo number_format((float)$o['amount']); ?></td>
        </tr>
      </tbody>
    </table>

    <div class="total" style="margin-top:10px;">Total: ₹<?php echo number_format((float)$o['amount']); ?></div>
    <div class="muted" style="margin-top:12px;">This is a demo receipt for the Car Showroom project.</div>
  </div>
</body>
</html>
