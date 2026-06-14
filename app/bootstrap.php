<?php
spl_autoload_register(function($c){$rel=str_replace('\\','/',$c); if(str_starts_with($rel,'App/')) $rel='app/'.substr($rel,4); $p=__DIR__.'/../'.$rel.'.php'; if(file_exists($p)) require $p;});
final class DB{static $pdo; static function pdo(){if(!self::$pdo){$c=require __DIR__.'/../config/config.php';self::$pdo=new PDO("mysql:host={$c['db']['host']};dbname={$c['db']['name']};charset={$c['db']['charset']}",$c['db']['user'],$c['db']['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}return self::$pdo;}}
function setting($k,$d=null){$s=DB::pdo()->prepare('SELECT value FROM settings WHERE `key`=?');$s->execute([$k]);$r=$s->fetchColumn();return $r===false?$d:$r;}
function set_setting($k,$v){$s=DB::pdo()->prepare('REPLACE INTO settings(`key`,`value`) VALUES(?,?)');$s->execute([$k,$v]);}
function csrf(){$_SESSION['csrf']=$_SESSION['csrf']??bin2hex(random_bytes(16));return $_SESSION['csrf'];}
function check_csrf(){if(($_POST['csrf']??'')!==($_SESSION['csrf']??'')) throw new Exception('CSRF failed');}
function auth(){if(empty($_SESSION['uid'])){header('Location:?page=login');exit;}}
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function log_event($ch,$lvl,$msg,$ctx=[]){$s=DB::pdo()->prepare('INSERT INTO logs(channel,level,message,context) VALUES(?,?,?,?)');$s->execute([$ch,$lvl,$msg,json_encode($ctx)]);} 
function audit($a,$d=''){if(isset($_SESSION['uid'])){DB::pdo()->prepare('INSERT INTO audit_logs(user_id,action,details,ip) VALUES(?,?,?,?)')->execute([$_SESSION['uid'],$a,$d,$_SERVER['REMOTE_ADDR']??'cli']);}}
