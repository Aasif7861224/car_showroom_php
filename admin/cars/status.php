<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id = (int)(isset($_GET['id']) ? $_GET['id'] : 0);
$status = trim(isset($_GET['status']) ? $_GET['status'] : '');

$allowed = array('Draft','Published','Sold');
if ($id<=0 || !in_array($status, $allowed)) {
  flash_set('err','Invalid request.');
  redirect_to('admin/cars/index.php');
}

$stmt = db()->prepare("UPDATE cars SET status=? WHERE id=? AND is_deleted=0 LIMIT 1");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

flash_set('ok','Status updated to '.$status);
redirect_to('admin/cars/index.php');