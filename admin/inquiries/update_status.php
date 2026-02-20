<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'New';

$allowed = array('New','Replied','Closed');
if(!in_array($status,$allowed)){
  flash_set('err','Invalid status.');
  redirect_to('admin/inquiries/index.php');
}

$stmt = db()->prepare("UPDATE inquiries SET status=? WHERE id=? LIMIT 1");
$stmt->bind_param("si",$status,$id);
$stmt->execute();

flash_set('ok','Inquiry status updated.');
redirect_to('admin/inquiries/index.php');