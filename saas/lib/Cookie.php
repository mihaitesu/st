<?php declare(strict_types=1);
/**
 * Cookie.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class Cookie {
	
	const DEFAULT_PATH     = "/";
	const DEFAULT_DOMAIN   = '';
	
	private $cookie_name;
	private $cookie_value;
	private $cookie_duration;
	private $cookie_path;
	private $cookie_domain;
	
	public function __construct(string $name, string $value='', int $duration=0, string $path='', string $domain='') {
		$this->cookie_name     = $name;
		$this->cookie_value    = $value;
		$this->cookie_duration = $duration ? $duration : time()+(60*60*24*90);
		$this->cookie_path     = $path     ? $path     : self::DEFAULT_PATH;
		$this->cookie_domain   = $domain   ? $domain   : self::DEFAULT_DOMAIN;
	}
	
	public function get_value() {
		return isset($_COOKIE[$this->cookie_name]) ? $_COOKIE[$this->cookie_name] : null;
	}
	
	public function save() {
		setcookie($this->cookie_name, $this->cookie_value, $this->cookie_duration, $this->cookie_path, $this->cookie_domain);
		
	}
	
	public function delete() {
		setcookie($this->cookie_name, $this->cookie_value, time()-1, $this->cookie_path, $this->cookie_domain);
	}
	
}
