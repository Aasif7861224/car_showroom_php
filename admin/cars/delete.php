<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id = (int)(isset($_GET['id']) ? $_GET['id'] : 0);
if ($id<=0) { flash_set('err','Invalid car.'); redirect_to('admin/cars/index.php'); }

// soft delete car
db()->query("UPDATE cars SET is_deleted=1 WHERE id=$id LIMIT 1");
// soft delete images
db()->query("UPDATE car_images SET is_deleted=1, is_primary=0 WHERE car_id=$id");

flash_set('ok','Car deleted (soft).');
redirect_to('admin/cars/index.php');