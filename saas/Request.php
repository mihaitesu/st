<?php declare(strict_types=1);
/**
 * Request.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class Request {
	
	//--- date request transmise in controller
	private $route_code    = null;
	private $lang_data     = array();
	private $route_params  = array();
	private $query_params  = array();
	private $method        = null; //--- din request
	
	//--- pattern url
	private $route_pattern = null;
	
	//--- date ruta din baza
	private $controller    = null;
	private $action        = null;
	private $methods       = null; //--- acceptate de ruta
	
	
	public function __construct() {
		$this->check_protocol();
		$this->check_route_query_string();
		$this->route_pattern = isset($_GET['route']) ? trim($_GET['route'], '/') : '';
		foreach($_GET as $param => $val) { if($param!=='route') $this->query_params[$param] = $val; }
		$this->route_params  = $this->route_pattern ? explode("/", $this->route_pattern) : array();
		$this->match_route();
		$this->method = $this->get_method();
	}
	
	
	//--- returneaza true daca protocolul este https
	private function protocol_https() {
		return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}
	
	
	//--- backup htaccess - redirectare protocol
	private function check_protocol() {
		if( ( (SYS['protocol']==="https") && (!$this->protocol_https()) ) || 
			( (SYS['protocol']==="http")  && ($this->protocol_https())  ) ) {
			$redirect = SYS['protocol']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			header("Location: ".$redirect);
			exit;
		}
	}
	
	
	//--- nu trebuie sa existe parametri "route"
	private function check_route_query_string() {
		$URL          = new URL();
		$url_redirect = $URL->get_remove_route_param();
		$current_url  = $URL->get_current_url();
		if($url_redirect!==$current_url) { //--- $current_url contine parametru "route"
			header("Location: ".$url_redirect);
			exit;
		}
	}
	
	
	//--- cauta si seteaza parametrii pentru ruta
	private function match_route() {
		$LangService   = new LangService();
		$redirect = false;
		if(isset($this->route_params[0])) {
			$this->lang_data = $LangService->get_data(array('iso_code'=>$this->route_params[0], 'status'=>1));
		} else {
			$redirect = true;
			//--- verifica cookie
			$Cookie = new Cookie('lang');
			$this->lang_data = $LangService->get_data(array('iso_code'=>$Cookie->get_value(), 'status'=>1));
			if(!$this->lang_data['id_lang']) {
				//--- verifica browser
				if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
					$http_langs = array();
					preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
					if(count($lang_parse[1])) {
						$http_langs = array_combine($lang_parse[1], $lang_parse[4]);
						foreach($http_langs as $http_lang => $val) {
							if($val === '') $http_langs[$http_lang] = 1;
						}
						arsort($http_langs, SORT_NUMERIC); //--- dupa prioritatea setata in browser
					}
					$lang_list = $LangService->get_data_list(array('status'=>1)); //--- lista limbi active
					foreach ($http_langs as $http_lang => $val) {
						foreach($lang_list as $lid => $larr) {
							if(strpos($http_lang, $larr['iso_code']) === 0) {
								$this->lang_data = $LangService->get_data(array('iso_code'=>$larr['iso_code'], 'status'=>1));
								break 2;
							}
						}
					}
				}
			}
		}
		if(empty($this->lang_data) || !$this->lang_data['id_lang']) {
			$this->lang_data = $LangService->get_default();
			$redirect = true;
		}
		//--- redirectare pentru setare limba in URL
		if($redirect) {
			$URL = new URL();
			header("Location: ".$URL->get_url(100, array($this->lang_data['iso_code'])));
			exit;
		}
		//----------------
		$RouteMergeService = new RouteMergeService();
		$route_list        = $RouteMergeService->get_data_list(array('id_lang'=>$this->lang_data['id_lang']));
		
		$ar_vars = array();
		$ar_data = array();
		foreach($this->route_params as $key=>$val) {
			$ar_vars[] = '{P'.$key.'}';
			$ar_data[] = $val;
		}
		$match = false;
		foreach($route_list as $i => $data) {
			$pattern = trim(str_replace($ar_vars, $ar_data, $data['pattern']), '/');
			if($this->route_pattern === trim($this->lang_data['iso_code'].'/'.$pattern, '/')) { //---adaug limba in pattern
				$this->route_code = $data['code'];
				$this->controller = $data['controller'];
				$this->action     = $data['action'];
				$this->methods    = $data['methods'];
				$match = true;
				break; //--- prima ruta gasita, totusi nu ar trebui sa existe mai multe potriviri
			}
		}
		
		if(!$match) {
			$request_route_data = array(
				'route_code'   => $this->route_code,
				'lang_data'    => $this->lang_data,
				'route_params' => $this->route_params,
				'query_params' => $this->query_params,
				'method'       => $this->method
			);
			$Controller = new BaseController($request_route_data);
			$Controller->error_404();
			exit;
		}
	}
	
	
	//--- metoda request
	private function get_method() {
		// array('PUT', 'POST', 'GET', 'HEAD', 'DELETE', 'OPTIONS')
		$methods = explode("/", trim($this->methods, "/"));
		$method  = strtoupper($_SERVER['REQUEST_METHOD']);
		if(in_array($method, $methods)) {
			return $method;
		}
		//---
		http_response_code(405); //Method Not Allowed
		exit;
	}
	
	
	//--- executa metoda controller corespunzatoare
	public function handle() {
		// controller->action validate (existente in db)
		if($this->controller && class_exists($this->controller)) {
			$request_route_data = array(
				'route_code'   => $this->route_code,
				'lang_data'    => $this->lang_data,
				'route_params' => $this->route_params,
				'query_params' => $this->query_params,
				'method'       => $this->method
			);
			$controller = new $this->controller($request_route_data);
			if($this->action && method_exists($controller, $this->action)) {
				$Cookie = new Cookie('lang', $request_route_data['lang_data']['iso_code']);
				$Cookie->save();
				$controller->{$this->action}();
			} else {
				// metoda neimplementata in clasa controller
				http_response_code(500); //Internal Server Error
				exit;
			}
		} else {
			// controller neimplementat - clasa inexistenta
			http_response_code(500); //Internal Server Error
			exit;
		}
	}
	
}
