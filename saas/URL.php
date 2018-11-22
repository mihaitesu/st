<?php declare(strict_types=1);
/**
 * URL.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class URL {
	
	private $base_url = null; //--- url curent sau transmis ca parametru
	
	public function __construct(string $base_url = null) {
		if($base_url !== null) {
			$this->base_url = $this->valid_format_url($base_url) ? $base_url : null;
			return;
		}
		$this->base_url = $this->get_current_url();
	}
	
	
	//--- returneaza URL curent
	public function get_current_url() {
		$s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
		$s1 = strtolower($_SERVER['SERVER_PROTOCOL']);
		$s2 = '/';
		$protocol = substr($s1, 0, strpos($s1, $s2)).$s;
		$port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (':'.$_SERVER['SERVER_PORT']);
		return $protocol.'://'.$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}
	
	
	//------------------------------------------------------------------------
	//--- valideaza format url
	private function valid_format_url(string $url) {
		if(strlen($url) && preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
			return true;
		}
		return false;
	}
	
	
	//--- returneaza query string URL
	private function get_query_url(string $url) {
		$parts = parse_url($url);
		return isset($parts['query']) ? $parts['query'] : null;
	}
	
	
	//--- construieste URL din parse_url()
	private function build_url(array $url_data) {
		$url = '';
		if(isset($url_data['host'])) {
			$url .= $url_data['scheme'] . '://';
			if(isset($url_data['user'])) {
				$url .= $url_data['user'];
				if(isset($url_data['pass'])) {
					$url .= ':' . $url_data['pass'];
				}
				$url .= '@';
			}
			$url .= $url_data['host'];
			if(isset($url_data['port'])) {
				$url .= ':' . $url_data['port'];
			}
		}
		$url .= isset($url_data['path']) ? $url_data['path'] : '/';//default path
		if(isset($url_data['query']) && $url_data['query']) {
			$url .= '?' . $url_data['query'];
		}
		if(isset($url_data['fragment']) && $url_data['fragment']) {
			$url .= '#' . $url_data['fragment'];
		}
		return $url;
	}
	
	
	//--- adauga/actualizeaza un parametru in URL
	private function set_url_param(string $url, $param_name, $param_value) {
		$url_data = parse_url($url);
		if(!isset($url_data['query'])) {
			$url_data['query'] = '';
		}
		$params = array();
		parse_str($url_data['query'], $params);
		$params[$param_name] = $param_value;
		$url_data['query'] = http_build_query($params);
		return $this->build_url($url_data);
	}
	
	
	//--- adauga parametrii in URL
	private function set_url_params(string $url, array $params) {
		foreach($params as $pname => $pvalue) {
			$url = $this->set_url_param($url, $pname, $pvalue);
		}
		return $url;
	}
	
	
	//--- sterge un parametru din url
	private function rem_url_param(string $url, $param_name) {
		$url_data = parse_url($url);
		if(!isset($url_data['query'])) {
			$url_data['query'] = '';
		}
		$params = array();
		parse_str($url_data['query'], $params);
		unset($params[$param_name]);
		$url_data['query'] = http_build_query($params);
		return $this->build_url($url_data);
	}
	
	
	//--- returneaza valoarea unui parametru din URL
	private function get_url_param_val(string $url, $param_name) {
		$parts = parse_url($url);
		if(!isset($parts['query'])) { $parts['query'] = ''; }
		parse_str($parts['query'], $query);
		return isset($query[$param_name]) ? $query[$param_name] : null;
	}
	
	
	//--- verifica daca este setat un parametru in URL
	private function isset_url_param_name(string $url, $param_name) {
		$parts = parse_url($url);
		if(!isset($parts['query'])) { return false; } //niciun parametru
		parse_str($parts['query'], $query);
		return isset($query[$param_name]) ? true : false;
	}
	//------------------------------------------------------------------------
	
	
	//--- sterge parametrul "route"
	public function get_remove_route_param() {
		if($this->isset_url_param_name($this->base_url, 'route')) {
			return $this->rem_url_param($this->base_url, 'route');
		}
		return $this->base_url;
	}
	
	
	//--- returneaza host (domeniu, subdomenii) din URL
	public function get_url_host() {
		$parts = parse_url($this->base_url);
		return isset($parts['host']) ? $parts['host'] : null;
	}
	
	
	//--- verifica daca url apartine domeniului serviciului
	public function is_service_host() {
		$parts = parse_url($this->base_url);
		if(isset($parts['host'])) {
			$hlen = strlen($parts['host']);
			$dlen = strlen(SYS['domain']);
			if ($dlen > $hlen) return false;
			return (substr_compare($parts['host'], SYS['domain'], $hlen-$dlen, $dlen) === 0) ? true : false;
		}
		return false;
	}
	
	
	/* returneaza subdomeniul din URL (cel mai apropiat de domeniu) daca exista sau null
		http://www.sub.domain.ext => sub
		http://sub.domain.ext => sub
	-----------------------------------------------------*/
	public function get_service_url_subdomain() {
		if(!$this->is_service_host()) { return null; } //doar subdomenii ale domeniului serviciului
		
		$parts = parse_url($this->base_url);
		$host = array();
		if(isset($parts['host'])) {
			$host = explode('.', $parts['host']);
		}
		$domain = explode('.', SYS['domain'])[0];
		foreach($host as $key => $val) {
			if(($val === $domain) && ($key>0) && (set_lowercase_string($host[$key-1])!=='www')) {
				return $host[$key-1];
			}
		}
		return null;
	}
	
	
	/* seteaza subdomeniul in URL
	   (cel mai apropiat de domeniu)
	-----------------------------------------------------*/
	public function set_service_url_subdomain($subdomain) {
		if(!$this->is_service_host()) { return $this->base_url; } //doar pt domeniul serviciului
		
		$parts = parse_url($this->base_url);
		$host = array();
		if(isset($parts['host'])) {
			$host = explode('.', $parts['host']);
		}
		$domain = explode('.', SYS['domain'])[0];
		foreach($host as $key => $val) {
			if($val === $domain) {
				if($this->get_service_url_subdomain()) {
					if($subdomain) { $host[$key-1] = $subdomain; } else { unset($host[$key-1]); }
				} else {
					if($subdomain) { $host[$key] = $subdomain.'.'.$val; }
				}
				break;
			}
		}
		$parts['host'] = implode('.', $host);
		
		return $this->build_url($parts);
	}
	
	
	//--- seteaza/schimba domeniul din URL
	public function set_url_domain($domain) {
		$parts = parse_url($this->base_url);
		if($domain) $parts['host'] = $domain;
		return $this->build_url($parts);
	}
	
	
	//--- returneaza ruta cu parametrii setati
	private function get_route($route_code, $route_params) {
		if(empty($route_params)) { //--- iau limba din base_url
			$parts = parse_url($this->base_url);
			$rp    = $parts['path'] ? explode("/", trim($parts['path'], '/')) : array();
			$route_params[0] = isset($rp[0]) ? $rp[0] : null;
		}
		
		$LangService = new LangService();
		$lang_data   = $LangService->get_data(array('iso_code'=>$route_params[0], 'status'=>1));
		if(!$lang_data['id_lang']) { return null; }
		
		$RouteMergeService = new RouteMergeService();
		$route_list   = $RouteMergeService->get_data_list(array('id_lang'=>$lang_data['id_lang'], 'code'=>$route_code));
		$pattern      = (count($route_list)===1) ? $route_list[0]['pattern'] : null;
		if(!$pattern) { return null; }
		
		$pattern = '{P0}'.$pattern; //--- adaug {P0} pentru limba
		$ar_vars = array();
		$ar_data = array();
		foreach($route_params as $key=>$val) {
			$ar_vars[] = '{P'.$key.'}';
			$ar_data[] = $val;
		}
		return trim(str_replace($ar_vars, $ar_data, $pattern), '/');
	}
	
	
	//--- returneaza URL
	public function get_url(int $route_code, array $route_params=array(), array $qs_params=array(), bool $absolute=false) {
		//-- ruta
		$route = $this->get_route($route_code, $route_params);
		$route = $route ? '/'.$route.'/' : '/';
		$url = SYS['protocol'].'://'.$this->get_url_host().$route;
		
		//-- qs param
		if(!empty($qs_params)) {
			$url = $this->set_url_params($url, $qs_params);
		}
		
		//-- relativ/absolut
		$url_data = parse_url($url);
		if(!$absolute) unset($url_data['host']);
		return $this->build_url($url_data);
	}
	
	
}
