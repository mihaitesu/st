<?php declare(strict_types=1);
/**
 * index.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



//--- global config, functii utilitare
require_once '../saas/config.php';
require_once SYS_PATH['root'].'helpers.php';



//--- start timp executie script
if(SYS['dev_mode']) {
	$start_load_time = explode(' ', microtime());
	$start_load_time = $start_load_time[0] + $start_load_time[1];
}



//--- raportare erori
error_reporting(E_ALL);
ini_set('display_errors', SYS['dev_mode'] ? 'On' : 'Off');



//--- setare timp maxim de executie pentru php
ini_set('max_execution_time', (string)30); //--- 30 sec
/*ini_set('max_execution_time', 1800); //30 min - doar pt uploads*/



//--- sesiune valabila x sec (ex. google - 30min)
ini_set('session.gc_maxlifetime', (string)SYS['session_timeout_sec']); // sesiunea trebuie sa existe cel putin ... sec
if (session_id() == "") { session_start(); }
if (isset($_SESSION['LAST_REQUEST_ASD']) && (time() - $_SESSION['LAST_REQUEST_ASD'] > SYS['session_timeout_sec'])) {
	session_unset();
	session_destroy();
	session_regenerate_id(true);
}
$_SESSION['LAST_REQUEST_ASD'] = time();



//--- autoload
function autoload_sys($class_name) {
	$file = SYS_PATH['root'].$class_name.'.php';
	if(is_readable($file)) { require_once $file; }
	
	$file = SYS_PATH['models'].$class_name.'.php';
	if(is_readable($file)) { require_once $file; }
	
	$file = SYS_PATH['domain_objects'].$class_name.'.php';
	if(is_readable($file)) { require_once $file; }
	
	$file = SYS_PATH['data_mappers'].$class_name.'.php';
	if(is_readable($file)) { require_once $file; }
	
	$file = SYS_PATH['services'].$class_name.'.php';
	if(is_readable($file)) { require_once $file; }
	
	$file = SYS_PATH['views'].$class_name.'.php';
	if(is_readable($file)) { require_once $file; }
	
	$file = SYS_PATH['controllers'].$class_name.'.php';
	if(is_readable($file)) { require_once $file; }
	
	$file = SYS_PATH['lib'].$class_name.'.php';
	if(is_readable($file)) { require_once $file; }
}
spl_autoload_register("autoload_sys");



//--- interpreteaza corect caracterele (PHP+MYSQL)
mb_internal_encoding("UTF-8");
DB::call()->query("SET NAMES utf8");



//--- timezone default pentru php
date_default_timezone_set(SYS['date_default_timezone']);



//--- interogari db fara cache (pentru testare)
if(SYS['dev_mode']) {
	DB::call()->query("RESET QUERY CACHE");
}

//--- nr de interogari - inceput
if(SYS['dev_mode']) {
	$query = "SHOW SESSION STATUS LIKE 'Questions'";
	$stmt = DB::call()->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$dbqueries1 = $row['Value'];
	$stmt = null;
}




//--- handle request
$Request = new Request();
$Request->handle();




//--- nr de interogari - sfarsit
if(SYS['dev_mode']) {
	$query = "SHOW SESSION STATUS LIKE 'Questions'";
	$stmt = DB::call()->prepare($query);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$dbqueries2 = $row['Value'];
	$stmt = null;
}



//--- sfarsit timp executie script
if(SYS['dev_mode']) {
	$end_load_time = explode(' ', microtime());
	$total_load_time = round(($end_load_time[0] +  $end_load_time[1] - $start_load_time), 6);
	
	/*
	$URL = new URL();
	$content = "URL: ".$URL->get_current_url().", Server ".$total_load_time." sec, ".$dbqueries1."-".$dbqueries2." interogari/request\r\n";
	file_put_contents('dev.log', $content, FILE_APPEND);
	*/
}
