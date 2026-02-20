<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id=(int)($_GET['id'] ?? 0);
if($id<=0){ flash_set('err','Invalid category.'); redirect_to('admin/categories/index.php'); }
db()->query("DELETE FROM categories WHERE id=$id LIMIT 1");
flash_set('ok','Category deleted.');
redirect_to('admin/categories/index.php');
