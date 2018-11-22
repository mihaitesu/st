<?php declare(strict_types=1);
/**
 * AccountController.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class AccountController extends BaseController {
	
	private $View;
	
	public function __construct(array $view_params) {
		parent::__construct($view_params);
		
		$this->View = new AccountView($this->view_params);
	}
	
	
	public function index() {
		$this->set_authenticated_area(true);
		
		if(empty($this->sub_domain_user_data)) {
			//--- admin main domain
			$this->View->service_domain_admin();
		} else {
			//--- admin user sub-domain
			$this->View->sub_domain_admin();
		}
	}
	
	public function login() {
		$this->set_unauthenticated_area(true);
		
		switch($this->request_route_data['method']) {
			case 'POST':
				$email    = isset($_POST['email'])    ? $_POST['email']    : null;
				$password = isset($_POST['password']) ? $_POST['password'] : null;
				
				if(empty($this->sub_domain_user_data)) {
					$this->set_service_domain_area();
					$this->set_ajax_call(); //--- ajax only
					$this->View->service_domain_act_login($email, $password);
				} else {
					$this->set_user_sub_domain_area();
					$this->set_ajax_call(); //--- ajax only
					$this->View->sub_domain_act_login($email, $password);
				}
			break;
			case 'GET':
				if(empty($this->sub_domain_user_data)) {
					$this->set_service_domain_area();
					$this->View->service_domain_login();
				} else {
					$this->set_user_sub_domain_area();
					$this->View->sub_domain_login();
				}
			break;
			default: http_response_code(405); break; //--- Method Not Allowed
		}
	}
	
	public function register() {
		$this->set_unauthenticated_area(true);
		
		$this->set_service_domain_area();
		$this->View->service_domain_register();
	}
	
	public function recovery() {
		$this->set_unauthenticated_area(true);
		
		$this->set_service_domain_area();
		$this->View->service_domain_recovery();
	}
	
}
