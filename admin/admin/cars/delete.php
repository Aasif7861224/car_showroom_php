<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id=(int)($_GET['id']??0);
if($id<=0){ flash_set('err','Invalid.'); redirect_to('admin/cars/index.php'); }

$stmt=db()->prepare("UPDATE cars SET is_deleted=1 WHERE id=? LIMIT 1");
$stmt->bind_param("i",$id);
$stmt->execute();

flash_set('ok','Car deleted (soft delete).');
redirect_to('admin/cars/index.php');
