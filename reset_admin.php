<?php
require_once __DIR__ . '/app/init.php';

$email = 'admin@demo.com';
$newPass = 'Admin@123';

$hash = password_make($newPass);

$stmt = db()->prepare("UPDATE users SET password_hash=? WHERE email=? AND role='Admin' LIMIT 1");
$stmt->bind_param("ss", $hash, $email);
$stmt->execute();

echo "Admin password reset done for $email. Now login with Admin@123";