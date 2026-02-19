<?php
require_once __DIR__ . '/../../app/init.php';
require_admin();

$id = (int)(isset($_GET['id']) ? $_GET['id'] : 0);
if ($id<=0) { flash_set('err','Invalid request.'); redirect_to('admin/sell_requests/index.php'); }

$stmt = db()->prepare("SELECT * FROM sell_requests WHERE id=? LIMIT 1");
$stmt->bind_param("i",$id);
$stmt->execute();
$req = $stmt->get_result()->fetch_assoc();

if (!$req) { flash_set('err','Request not found.'); redirect_to('admin/sell_requests/index.php'); }
if ($req['status'] !== 'Pending') { flash_set('err','Only Pending requests can be approved.'); redirect_to('admin/sell_requests/index.php'); }

$category_id = 0;
$title = $req['car_title'];
$brand = 'Unknown';
$model = 'Unknown';
$car_year = (int)$req['car_year'];
$fuel = $req['fuel'];
$transmission = $req['transmission'];
$price = (float)$req['expected_price'];
$mileage_km = (int)(isset($req['mileage_km']) ? $req['mileage_km'] : 0);
$location = $req['location'];
$description = 'Added from Sell Request (#'.$req['id'].'). Admin: update brand/model, add images, then publish.';
$status = 'Draft';

$stmt2 = db()->prepare("INSERT INTO cars (category_id,title,brand,model,car_year,fuel,transmission,price,mileage_km,location,description,status,is_deleted,is_featured,discount_percent,view_count)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,0,0,0,0)");
$stmt2->bind_param("isssissdiiss",
  $category_id,$title,$brand,$model,$car_year,$fuel,$transmission,$price,$mileage_km,$location,$description,$status
);
$stmt2->execute();
$car_id = db()->insert_id;

$stmt3 = db()->prepare("UPDATE sell_requests SET status='Approved', reject_reason=NULL, approved_car_id=? WHERE id=? LIMIT 1");
$stmt3->bind_param("ii",$car_id,$id);
$stmt3->execute();

flash_set('ok','Approved! Car added to inventory as Draft. Please edit and publish it.');
redirect_to('admin/sell_requests/index.php');
