<?php
require_once __DIR__ . '/../app/init.php';
logout_user();
flash_set('ok','Logged out successfully.');
redirect_to('');
