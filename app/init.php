<?php
require_once __DIR__ . '/config.php';

function db(){
  static $m = null;
  if ($m) return $m;

  $m = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, (int)DB_PORT);
  if ($m->connect_errno) {
    http_response_code(500);
    die("DB Connection failed: " . $m->connect_error);
  }
  $m->set_charset('utf8mb4');
  return $m;
}

function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function redirect_to($path){
  if (strpos($path,'http')===0) { header("Location: ".$path); exit; }
  if ($path==='' || $path===null) { header("Location: ".BASE_URL); exit; }
  if (strlen($path) && $path[0]==='/') { header("Location: ".$path); exit; }
  header("Location: ".BASE_URL.ltrim($path,'/')); exit;
}

function is_post(){ return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']==='POST'; }

function flash_set($k,$m){
  if (!isset($_SESSION['flash'])) $_SESSION['flash']=array();
  $_SESSION['flash'][$k]=$m;
}
function flash_get($k){
  if (isset($_SESSION['flash']) && isset($_SESSION['flash'][$k])) {
    $m=$_SESSION['flash'][$k];
    unset($_SESSION['flash'][$k]);
    return $m;
  }
  return '';
}

function current_user(){ return isset($_SESSION['user']) ? $_SESSION['user'] : null; }
function login_user($row){
  $_SESSION['user']=array(
    'id'=>(int)$row['id'],
    'full_name'=>$row['full_name'],
    'email'=>$row['email'],
    'role'=>$row['role']
  );
}
function logout_user(){ unset($_SESSION['user']); }

function require_login(){
  if (!current_user()) { flash_set('err','Please login first.'); redirect_to('user/login.php'); }
}
function require_admin(){
  $u=current_user();
  if (!$u || !isset($u['role']) || $u['role']!=='Admin') { flash_set('err','Admin login required.'); redirect_to('admin/login.php'); }
}

function password_make($plain){
  if (function_exists('password_hash')) return password_hash($plain, PASSWORD_BCRYPT);
  return md5($plain); // fallback
}
function password_check($plain,$hash){
  if (function_exists('password_verify')) return password_verify($plain,$hash);
  return md5($plain)===$hash;
}

function q($s){ return db()->real_escape_string($s); }
