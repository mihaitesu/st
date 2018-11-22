<?php declare(strict_types=1);
/**
 * BaseView.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class BaseView {
	
	private   $Template; //--- accesibil doar in base
	
	protected $request_route_data;
	protected $URL;
	
	protected $LangService;
	protected $TextMergeService;
	protected $MsgMergeService;
	
	protected $UserAccountService;
	protected $auth_user_data       = array();
	protected $sub_domain_user_data = array();
	
	public function __construct(array $view_params) {
		
		$this->Template             = new Template(SYS_PATH['tpl'].'base/', SYS['dev_mode'] ? false : SYS_PATH['tpl'].'base/cache/');
		
		$this->request_route_data   = $view_params['request_route_data'];
		$this->URL                  = $view_params['URL'];
		
		$this->LangService          = new LangService();
		$this->TextMergeService     = new TextMergeService();
		$this->MsgMergeService      = new MsgMergeService();
		
		$this->UserAccountService   = $view_params['UserAccountService'];
		$this->auth_user_data       = $view_params['auth_user_data'];
		$this->sub_domain_user_data = $view_params['sub_domain_user_data'];
		
	}
	
	
	//--- returneaza textele dupa coduri
	protected function get_text(array $codes) {
		$text = array();
		$text_list = $this->TextMergeService->get_data_list(array('codes'=>$codes, 'id_lang'=>$this->request_route_data['lang_data']['id_lang']));
		foreach($text_list as $key => $arr) $text[$arr['code']] = $arr;
		return $text;
	}
	
	
	//--- returneaza mesajele dupa coduri
	protected function get_msg(array $codes) {
		$msg = array();
		
		return $msg;
	}
	
	
	//--- seteaza limba
	protected function set_current_url_lang(string $iso_code) {
		$LangService = new LangService();
		$data        = $LangService->get_data(array('iso_code'=>$iso_code, 'status'=>1));
		if(!$data['id_lang']) {
			return $this->URL->get_current_url();
		}
		
		$new_lang_params = $this->request_route_data['route_params'];
		$new_lang_params[0] = $iso_code;
		return $this->URL->get_url($this->request_route_data['route_code'], $new_lang_params, $this->request_route_data['query_params']);
	}
	
	
	//--- array base cu variabilele comune din template-uri
	protected function base_tpl_vars() {
		$text = $this->get_text(array(1004,1005,1006,1007,1008,1009,  2038,2039));
		
		//--- adaug url si current pentru fiecare limba
		$lang_list = $this->LangService->get_data_list(array('status'=>1));
		foreach($lang_list as $key => $data) {
			$lang_list[$key]['url'] = $this->set_current_url_lang($data['iso_code']);
			$lang_list[$key]['current'] = ($data['id_lang'] === $this->request_route_data['lang_data']['id_lang']) ? true : false;
		}
		
		return array(
			'page_lang'         => $this->request_route_data['lang_data']['iso_code'],
			'url_icon'          => SYS_URL['img'],
			'url_css_bootstrap' => SYS_URL['css'].'bootstrap.min.css',
			'url_css_base'      => SYS_URL['css'].'base.css',
			'url_logo'          => $this->URL->get_url(100),
			'text_logo_tooltip' => $text[1004]['text'],
			'text_logo'         => SYS['name'],
			
			'q_form_action'         => $this->URL->get_url(100),
			'input_q_placeholder'   => 'Căutare...',
			'text_submit_q_tooltip' => 'Căutare',
			
			'url_account'       => $this->URL->get_url(200),
			
			'url_about'         => $this->URL->get_url(121),
			'text_about'        => $text[1005]['text'],
			'url_help'          => $this->URL->get_url(120),
			'text_help'         => $text[1006]['text'],
			'url_terms'         => $this->URL->get_url(122),
			'text_terms'        => $text[1007]['text'],
			'url_conf'          => $this->URL->get_url(123),
			'text_conf'         => $text[1008]['text'],
			'url_contact'       => $this->URL->get_url(124),
			'text_contact'      => $text[1009]['text'],
			
			'url_captcha_img'                 => $this->URL->get_url(101),
			'text_captcha_img'                => $text[2038]['text'],
			'input_captcha_placeholder'       => $text[2039]['text'],
			
			'current_lang_name' => $this->request_route_data['lang_data']['name'],
			'lang_data_list'    => $lang_list,
			'text_year'         => date("Y"),
			
			'url_js_jquery'     => SYS_URL['js'].'jquery.min.js',
			'url_js_popper'     => SYS_URL['js'].'popper.min.js',
			'url_js_bootstrap'  => SYS_URL['js'].'bootstrap.min.js',
			'url_js_base'       => SYS_URL['js'].'base.js'
		);
	}
	
	
	//--- valideaza domeniile custom - curl
	public function verify_host() {
		header("Content-Type: application/json; charset=utf-8");
		header("Content-language: ".$this->request_route_data['lang_data']['iso_code']);
		$response = array(
			'host'  => null,
			'token' => null
		);
		if(!$this->URL->is_service_host()) {
			$response['host']  = $this->URL->get_url_host();
			$response['token'] = $this->request_route_data['route_params'][1];
		}
		echo json_encode($response);
	}
	
	
	
	
	
	
	
	
	//--- index
	public function service_domain_index() {
		$text = $this->get_text(array());
		$tpl_vars = array(
			'base'             => $this->base_tpl_vars(),
			'page_title'       => '',
			'page_description' => '',
			'page_content'     => 'service home'
		);
		echo $this->Template->render_file('service_domain_home.html', $tpl_vars);
	}
	
	
	public function sub_domain_index() {
		echo 'User home';
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//--- about
	public function about_page() {
		$text = $this->get_text(array());
		$tpl_vars = array(
			'base'             => $this->base_tpl_vars(),
			'page_title'       => '',
			'page_description' => '',
			'page_content'     => 'about'
		);
		echo $this->Template->render_file('service_domain_about.html', $tpl_vars);
	}
	//--- help
	public function help_page() {
		$text = $this->get_text(array());
		$tpl_vars = array(
			'base'             => $this->base_tpl_vars(),
			'page_title'       => '',
			'page_description' => '',
			'page_content'     => 'help'
		);
		echo $this->Template->render_file('service_domain_help.html', $tpl_vars);
	}
	//--- terms
	public function terms_page() {
		$text = $this->get_text(array());
		$tpl_vars = array(
			'base'             => $this->base_tpl_vars(),
			'page_title'       => '',
			'page_description' => '',
			'page_content'     => 'terms'
		);
		echo $this->Template->render_file('service_domain_terms.html', $tpl_vars);
	}
	//--- conf
	public function conf_page() {
		$text = $this->get_text(array());
		$tpl_vars = array(
			'base'             => $this->base_tpl_vars(),
			'page_title'       => '',
			'page_description' => '',
			'page_content'     => 'conf'
		);
		echo $this->Template->render_file('service_domain_conf.html', $tpl_vars);
	}
	//--- contact
	public function contact_page() {
		$text = $this->get_text(array());
		$tpl_vars = array(
			'base'             => $this->base_tpl_vars(),
			'page_title'       => '',
			'page_description' => '',
			'page_content'     => 'contact'
		);
		echo $this->Template->render_file('service_domain_contact.html', $tpl_vars);
	}
	
	
	//--- pagina de start pentru domeniile custom inainte de validare/salvare in baza
	public function dns_test() {
		$text = $this->get_text(array(1021,1022));
		
		$subtitle = $this->Template->render_string($text[1022]['text'], array(
			'user_domain' => $this->URL->get_url_host(),
			'service_name'  => SYS['name']
		));
		
		$tpl_vars = array(
			'page_lang'         => $this->request_route_data['lang_data']['iso_code'],
			'page_description'  => '',
			'page_title'        => $text[1021]['text'],
			'url_icon'          => SYS_URL['img'],
			'url_css_bootstrap' => SYS_URL['css'].'bootstrap.min.css',
			'url_css_theme'     => SYS_URL['css'].'theme.min.css',
			'url_css_base'      => SYS_URL['css'].'base.css',
			'text_title'        => $text[1021]['text'],
			'text_subtitle'     => $subtitle,
			'url_js_bootstrap'  => SYS_URL['js'].'bootstrap.min.js',
			'url_js_base'       => SYS_URL['js'].'base.js'
		);
		echo $this->Template->render_file('service_domain_dns_test.html', $tpl_vars);
	}
	
	
	//--- 404
	public function error_page_404() {
		$text = $this->get_text(array(1001,1002,1003));
		$tpl_vars = array(
			'page_lang'         => $this->request_route_data['lang_data']['iso_code'],
			'page_description'  => $text[1002]['text'],
			'page_title'        => $text[1001]['text'],
			'url_icon'          => SYS_URL['img'],
			'url_css_bootstrap' => SYS_URL['css'].'bootstrap.min.css',
			'url_css_base'      => SYS_URL['css'].'base.css',
			'text_title'        => $text[1001]['text'],
			'text_subtitle'     => $text[1002]['text'],
			'url_home'          => $this->URL->get_url(100),
			'text_home'         => $text[1003]['text'],
			'url_js_bootstrap'  => SYS_URL['js'].'bootstrap.min.js',
			'url_js_base'       => SYS_URL['js'].'base.js'
		);
		http_response_code(404);
		echo $this->Template->render_file('service_domain_404.html', $tpl_vars);
	}
	
	
	//--- afisare imagine captcha
	public function captcha() {
		header("Content-type: image/png");
		// !!! no cache
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		$Captcha = new Captcha();
		$Captcha->show_png();
	}
	
}
