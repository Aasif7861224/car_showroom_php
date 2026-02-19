<?php
define('APP_NAME', 'Car Showroom');
define('BASE_URL', '/car_showroom_php/'); // change if folder name differs

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '4306'); // if your phpMyAdmin shows 4306 then set 4306
define('DB_NAME', 'car_showroom');
define('DB_USER', 'root');
define('DB_PASS', '');

date_default_timezone_set('Asia/Kolkata');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
