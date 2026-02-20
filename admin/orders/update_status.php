<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

if($_SERVER['REQUEST_METHOD']!=='POST'){ flash_set('err','Invalid request.'); redirect_to('admin/orders/index.php'); }

$id=(int)($_POST['id'] ?? 0);
$status=trim($_POST['status'] ?? '');
$allowed=array('Pending','Confirmed','Paid','Delivered','Cancelled');

if($id<=0 || !in_array($status,$allowed)){ flash_set('err','Invalid data.'); redirect_to('admin/orders/index.php'); }

$stmt=db()->prepare("UPDATE orders SET status=? WHERE id=? LIMIT 1");
$stmt->bind_param("si",$status,$id);
$stmt->execute();

flash_set('ok','Order status updated.');
redirect_to('admin/orders/index.php');
