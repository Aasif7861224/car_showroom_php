<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id=(int)($_GET['id'] ?? 0);
if($id<=0){ flash_set('err','Invalid user.'); redirect_to('admin/users/index.php'); }

$r=db()->query("SELECT id,role,is_active FROM users WHERE id=$id LIMIT 1");
$u=$r?$r->fetch_assoc():null;
if(!$u){ flash_set('err','User not found.'); redirect_to('admin/users/index.php'); }
if($u['role']==='Admin'){ flash_set('err','Cannot block admin.'); redirect_to('admin/users/index.php'); }

$new=((int)$u['is_active']===1)?0:1;
$stmt=db()->prepare("UPDATE users SET is_active=? WHERE id=? LIMIT 1");
$stmt->bind_param("ii",$new,$id);
$stmt->execute();

flash_set('ok','User status updated.');
redirect_to('admin/users/index.php');
