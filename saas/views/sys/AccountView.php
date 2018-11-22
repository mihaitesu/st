<?php declare(strict_types=1);
/**
 * AccountView.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class AccountView extends BaseView {
	
	private $Template;
	
	public function __construct(array $view_params) {
		parent::__construct($view_params);
		
		$this->Template = new Template(SYS_PATH['tpl'].'account/', SYS['dev_mode'] ? false : SYS_PATH['tpl'].'account/cache/');
	}
	
	
	//--- array account cu variabilele comune din template-uri
	private function account_tpl_vars() {
		return array(
			'url_js_account' => SYS_URL['js'].'account.js'
		);
	}
	
	
	
	public function service_domain_admin() {
		
		echo 'admin serviciu';
		
	}
	public function sub_domain_admin() {
		
		echo 'admin sub-domeniu';
		
	}
	
	
	
	public function service_domain_login() {
		$text = $this->get_text(array(2011,2012, 2014,2015,2016,2017,2018,2019));
		$tpl_vars = array(
			'base'    => $this->base_tpl_vars(),
			'account' => $this->account_tpl_vars(),
			'login'   => array(
				'text_description'           => $text[2012]['text'],
				'text_title'                 => $text[2011]['text'],
				'login_form_action'          => $this->URL->get_url(201),
				'input_email_placeholder'    => $text[2015]['text'],
				'input_password_placeholder' => $text[2016]['text'],
				'text_input_submit'          => $text[2017]['text'],
				'url_register'               => $this->URL->get_url(203),
				'text_register'              => $text[2018]['text'],
				'text_or'                    => $text[2014]['text'],
				'url_recover'                => $this->URL->get_url(205),
				'text_recover'               => $text[2019]['text']
			)
		);
		echo $this->Template->render_file('service_domain_login.html', $tpl_vars);
	}
	
	public function sub_domain_login() {
		//---
		
		
	}
	
	public function service_domain_act_login($email, $password) {
		header("Content-Type: application/json; charset=utf-8");
		header("Content-language: ".$this->request_route_data['lang_data']['iso_code']);
		
		/*
		$res      = $this->UserService->login($email, $password);
		$codes[]  = $res['alert'];
		$codes[]  = $res['email'];
		$codes[]  = $res['password'];
		$lang_msg = $this->LangMsgService->get_msg_by_codes($this->lang_set_data['id_lang'], $codes);
		
		$response = array(
			'alert'    => array('type'    => $lang_msg[$res['alert']]['type'],
								'title'   => $lang_msg[$res['alert']]['text_title'],
								'message' => $lang_msg[$res['alert']]['text_message']),
			
			'email'    => array('type'    => $lang_msg[$res['email']]['type'],
								'title'   => $lang_msg[$res['email']]['text_title'],
								'message' => $lang_msg[$res['email']]['text_message']),
			
			'password' => array('type'    => $lang_msg[$res['password']]['type'],
								'title'   => $lang_msg[$res['password']]['text_title'],
								'message' => $lang_msg[$res['password']]['text_message'])
		);
		*/
		
		$response = array();
		
		
		echo json_encode($response);
	}
	
	
	
	
	
	
	public function service_domain_register() {
		$text = $this->get_text(array(2031,2032,2035,2036,2037,20310,20311,20312));
		$tpl_vars = array(
			'base'     => $this->base_tpl_vars(),
			'account'  => $this->account_tpl_vars(),
			'register' => array(
				'text_description'                => $text[2032]['text'],
				'text_title'                      => $text[2031]['text'],
				'register_form_action'            => $this->URL->get_url(203),
				'input_email_placeholder'         => $text[2035]['text'],
				'input_password_placeholder'      => $text[2036]['text'],
				'input_conf_password_placeholder' => $text[2037]['text'],
				'text_input_submit'               => $text[20310]['text'],
				'text_existing_account'           => $text[20311]['text'],
				'url_login'                       => $this->URL->get_url(201),
				'text_login'                      => $text[20312]['text']
			)
		);
		echo $this->Template->render_file('service_domain_register.html', $tpl_vars);
	}
	
	
	
	public function service_domain_recovery() {
		$text = $this->get_text(array(2031,2032,2035,20310,20312));
		$tpl_vars = array(
			'base'                            => $this->base_tpl_vars(),
			'account'  => $this->account_tpl_vars(),
			'recovery' => array(
				'text_description'                => $text[2032]['text'],
				'text_title'                      => $text[2031]['text'],
				'recovery_form_action'            => $this->URL->get_url(203),
				'input_email_placeholder'         => $text[2035]['text'],
				'text_input_submit'               => $text[20310]['text'],
				'url_login'                       => $this->URL->get_url(201),
				'text_login'                      => $text[20312]['text']
			)
		);
		echo $this->Template->render_file('service_domain_recovery.html', $tpl_vars);
	}
	
	
	
	
	
}
