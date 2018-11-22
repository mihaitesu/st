<?php declare(strict_types=1);
/**
 * Session.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class Session {
	
	private $session_nam;
	private $session_val;
	
	public function __construct($name, $value=null) {
		$this->session_nam = $name;
		$this->session_val = $value;
	}
	
	public function get_value() {
		return isset($_SESSION[$this->session_nam]) ? $_SESSION[$this->session_nam] : null;
	}
	
	public function save() {
		if( !isset($_SESSION[$this->session_nam]) || ($_SESSION[$this->session_nam]!==$this->session_val) ) {
			session_regenerate_id(true);
			$_SESSION[$this->session_nam] = $this->session_val;
			session_write_close();
		}
	}
	
	public function delete() {
		unset($_SESSION[$this->session_nam]);
		session_regenerate_id(true);
		session_write_close();
	}
	
}
