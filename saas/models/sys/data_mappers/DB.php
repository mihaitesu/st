<?php declare(strict_types=1);
/**
 * DB.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class DB {
	
	//--- db settings
	const MYSQL_HOST = "localhost";
	const MYSQL_DB   = "st";
	const MYSQL_USER = "root";
	const MYSQL_PASS = "****";
	
	private static $link;
	private function __construct() {}
	private function __clone() {}
	
	
	//--- conexiune DB ------------------------------------
	public static function call() {
		if( !self::$link ) {
			self::$link = new PDO('mysql:host='.self::MYSQL_HOST.';dbname='.self::MYSQL_DB, 
						self::MYSQL_USER, self::MYSQL_PASS);
		}
		return self::$link;
	}
	//-----------------------------------------------------
	
	
}
