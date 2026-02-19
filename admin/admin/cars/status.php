<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id=(int)($_GET['id']??0);
$status=trim($_GET['status']??'');
$allowed=['Draft','Published','Sold'];
if($id<=0 || !in_array($status,$allowed)){
  flash_set('err','Invalid.');
  redirect_to('admin/cars/index.php');
}

$stmt=db()->prepare("UPDATE cars SET status=? WHERE id=? LIMIT 1");
$stmt->bind_param("si",$status,$id);
$stmt->execute();

flash_set('ok','Status updated to '.$status);
redirect_to('admin/cars/index.php');
