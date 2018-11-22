<?php declare(strict_types=1);
/**
 * BaseController.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class BaseController {
	
	
	protected $request_route_data;
	protected $URL;
	
	protected $UserAccountService;
	protected $auth_user_data       = array();
	protected $sub_domain_user_data = array();
	
	private   $View;
	protected $view_params = array();
	
	
	public function __construct(array $request_route_data) {
		$this->request_route_data = $request_route_data;
		
		$this->URL                = new URL();
		
		$this->UserAccountService   = new UserAccountService();
		$this->auth_user_data       = $this->UserAccountService->get_auth_user_data();
		$user_domain    = null;
		$user_subdomain = null;
		if($this->URL->is_service_host()) { //--- verific subdomeniu utilizator
			$user_subdomain = $this->URL->get_service_url_subdomain();
		} else { //--- verific domeniu utilizator
			$user_domain = $this->URL->get_url_host(); //--- poate fi si sub.domeniu.tld
		}
		$this->sub_domain_user_data = $this->UserAccountService->get_sub_domain_user_data($user_domain, $user_subdomain);
		
		$this->view_params = array(
			'request_route_data'   => $this->request_route_data,
			'URL'                  => $this->URL,
			'UserAccountService'   => $this->UserAccountService,
			'auth_user_data'       => $this->auth_user_data,
			'sub_domain_user_data' => $this->sub_domain_user_data
		);
		$this->View = new BaseView($this->view_params);
		
		
		//--- redirectare spre domeniu daca exista
		if($user_subdomain && !empty($this->sub_domain_user_data)) {
			$UserSubDomainService = new UserSubDomainService();
			$sd_data = $UserSubDomainService->get_data_list(array('id_user'=>$this->sub_domain_user_data['id_user'], 'subdomain'=>$user_subdomain));
			if(!empty($sd_data) && $sd_data[0]['domain']) {
				header("Location: ".$this->URL->set_url_domain($sd_data[0]['domain']), true, 301); // Moved Permanently
				exit;
			}
		}
		
		//--- hostul este strain (neinregistrat in baza) si ruta este alta decat cea de validare a domeniilor noi
		//--- hosturile straine au acces doar la pagina 404 si paginile de test/validare dns
		if(($user_subdomain || $user_domain) && empty($this->sub_domain_user_data) && 
			$this->request_route_data['route_code']!==102 && $this->request_route_data['route_code']!==103) {
				$this->View->error_page_404();
				exit;
		}
		
	}
	
	
	
	
	//--- pentru rute rezervate exclusiv hosturilor utilizatorilor
	protected function set_user_sub_domain_area() {
		if(empty($this->sub_domain_user_data)) {
			$this->View->error_page_404();
			exit;
		}
	}
	
	
	//--- pentru rute rezervate exclusiv domeniului principal
	protected function set_service_domain_area() {
		if(!empty($this->sub_domain_user_data)) {
			$this->View->error_page_404();
			exit;
		}
	}
	
	
	//--- seteaza zona utilizator autentificat
	protected function set_authenticated_area($redirect=false) {
		if(!isset($this->auth_user_data['id_user']) || !$this->auth_user_data['id_user']) {
			if($redirect) {
				header("Location: ".$this->URL->get_url(201));
				exit;
			}
			http_response_code(403); //forbidden
			exit;
		}
	}
	
	
	//--- seteaza zona utilizator neautentificat
	protected function set_unauthenticated_area($redirect=false) {
		if(isset($this->auth_user_data['id_user']) && $this->auth_user_data['id_user']) {
			if($redirect) {
				header("Location: ".$this->URL->get_url(200));
				exit;
			}
			http_response_code(403); //forbidden
			exit;
		}
	}
	
	
	//--- seteaza zona request ajax
	protected function set_ajax_call() {
		if(!is_ajax_call()) {
			http_response_code(403); //forbidden
			exit;
		}
	}
	
	
	
	
	
	
	//--- /...
	public function error_404() {
		$this->View->error_page_404();
	}
	
	
	//--- /
	public function index() {
		if(isset($_GET['q'])) {
			$this->set_service_domain_area();
			$CatalogView = new CatalogView($this->view_params);
			$CatalogView->search();
			return;
		}
		
		if(empty($this->sub_domain_user_data)) {
			$this->set_service_domain_area();
			$this->View->service_domain_index();
		} else {
			$this->set_user_sub_domain_area();
			$this->View->sub_domain_index();
		}
	}
	
	
	//--- /captcha/
	public function captcha() {
		$this->View->captcha();
	}
	
	
	//--- /verify-host/
	public function verify_host() {
		$this->View->verify_host();
	}
	
	
	//--- /dns-test-page/
	public function dns_test() {
		$this->set_user_sub_domain_area();
		$this->View->dns_test();
	}
	
	
	
	
	
	//--- /about/
	public function about_page() {
		$this->set_service_domain_area();
		$this->View->about_page();
	}
	
	
	//--- /help/
	public function help_page() {
		$this->set_service_domain_area();
		$this->View->help_page();
	}
	
	
	//--- /terms-conditions/
	public function terms_page() {
		$this->set_service_domain_area();
		$this->View->terms_page();
	}
	
	
	//--- /confidentiality/
	public function conf_page() {
		$this->set_service_domain_area();
		$this->View->conf_page();
	}
	
	
	//--- /contact/
	public function contact_page() {
		$this->set_service_domain_area();
		$this->View->contact_page();
	}
	
	
}
