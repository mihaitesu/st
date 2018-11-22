<?php declare(strict_types=1);
/**
 * UserAccountService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class UserAccountService {
	
	
	private $UserService;
	private $UserSubDomainService;
	
	
	public function __construct() {
		$this->UserService = new UserService();
		$this->UserSubDomainService = new UserSubDomainService();
	}
	
	
	//--- Returneaza date utilizator logat
	public function get_auth_user_data() {
		$Session = new Session('id_login');
		$id_login = $Session->get_value();
		if(!$id_login) return array(); //--- trebuie cel putin un id
		
		$user = $this->UserService->get_data_list(array('id_user'=>$id_login, 'status'=>1));
		if(empty($user)) {
			$Session->delete();
			return array();
		}
		return $user[0];
	}
	
	
	//--- Returneaza date utilizator sub/domeniu
	public function get_sub_domain_user_data($domain, $subdomain) {
		if( !$domain && !$subdomain ) return array(); //--- trebuie cel putin un id
		$sduser = $this->UserSubDomainService->get_data_list(array('domain'=>$domain, 'subdomain'=>$subdomain));
		$user   = empty($sduser) ? array() : $this->UserService->get_data_list(array('id_user'=>$sduser[0]['id_user'], 'status'=>1));
		return empty($user) ? array() : $user[0];
	}
	
	
	//--- verifica doua parole
	private function match_password($match_password, $password) {
		return (crypt($password ,$match_password) === $match_password) ? true : false;
	}
	
	
	//--- Autentifica utilizatori
	public function login($email, $password) {
		//sleep(1);
		$result = array(
			'alert'   => null,
			'email'   => null,
			'password'=> null
		);
		$result['alert'] = 60101;
		
		if(!$email || !$password) {
			return $result;
		}
		
		$login_ok = false;
		
		$data = $this->get_user_data(null, null, $email);
		if( $data['id_user'] && $this->match_password($data['password'], $password) ) {
			switch($data['status']) {
				case  1: //cont activ
					$result['alert'] = 60102;
					$login_ok = true;
				break;
				case -1: //cont dezactivat - anulare dezactivare cont?
					if( $data['date_disable'] && 
					(strtotime($data['date_disable']) >= strtotime('-30 days')) ) {
						//inca nu au trecut 30 de zile, anulare dezactivare cont
						$data['status']        = 1;
						$data['token_disable'] = null;
						$data['date_disable']  = null;
						$obj = $this->UserMapper->create($data);
						if($this->UserMapper->save($obj)) {
							$result['alert'] = 60102;
							$login_ok = true;
						}
					} //daca au trecut 30 de zile contul este considerat sters - autentificare esuata
				break;
				case -2: //cont banat
					$result['alert'] = 60103;
				break;
			}
		}
		
		if($login_ok) {
			$SessionService = new SessionService();
			$SessionService->save_session(array('login_id'=>$data['id_user']));
			
			//--- setare limba utilizator
			$LangService = new LangService();
			$LangService->set_cookie_lang($data['id_lang']);
		}
		
		return $result;
	}
	
	
	//--- Deconecteaza utilizator logat -------------------
	public function logout() {
		$SessionService = new SessionService();
		$login_id = $SessionService->get_session_data()['login_id'];
		if($login_id) {
			$data = array('login_id'=>$login_id);
			$SessionService->delete_session($data);
			return true;
		}
		return false;
	}
	
	
	//--- validare email pentru inregistrare --------------
	private function valid_register_email($email) {
		if(!valid_format_email($email)) {
			return false;
		}
		$data = $this->get_user_data(null, null, $email);
		if( $data['id_user'] && ($data['status']!==0) ) {
			//exista cont asociat activ, banat sau dezactivat
			return false;
		}
		return true;
	}
	//--- validare parola ---------------------------------
	private function valid_register_password($password) {
		return (strlen($password)>=6 && strlen($password)<=30) ? true : false;
	}
	//--- validare nume -----------------------------------
	private function valid_register_name($name) {
		return (strlen($name)>=1 && strlen($name)<=255) ? true : false;
	}
	//--- validare username/subdomeniu --------------------
	private function valid_register_username($username, $email) {
		$username = set_lowercase_string($username);
		//--- format
		if(!preg_match('/^[A-Za-z0-9][A-Za-z0-9_-]{3,30}$/', $username)) {
			return false;
		}
		//--- db
		return $this->UserMapper->uname_available($username, $email);
	}
	
	
	//--- Inregistreaza un cont nou -----------------------
	public function register($id_lang, $name, $subdomain, $email, $password, $password_confirm, $termcond, $captcha) {
		$result = array(
			'alert'            => null,
			'name'             => null,
			'subdomain'        => null,
			'email'            => null,
			'password'         => null,
			'password_confirm' => null,
			'termcond'         => null,
			'captcha'          => null
		);
		
		// Validari ---------------------------
		$LangService = new LangService();
		$lang_data = $LangService->get_lang_data($id_lang);
		if(!$lang_data['id_lang']) {
			$result['alert'] = 60301; //limba neselectata
			return $result;
		}
		if(!$this->valid_register_name($name)) {
			$result['name'] = 60310;
		}
		if(!$this->valid_register_email($email)) {
			$result['email'] = 60302; //email invalid
		}
		if(!$this->valid_register_password($password)) {
			$result['password'] = 60303; //parola incorecta
		}
		if($password !== $password_confirm) {
			$result['password_confirm'] = 60304; //confirmare parola esuata
		}
		$subdomain = set_lowercase_string($subdomain);
		if(!$this->valid_register_username($subdomain, $email)) {
			$result['subdomain'] = 60311;
		}
		if(!$termcond) {
			$result['termcond'] = 60305;
		}
		$Captcha = new Captcha();
		if(!$Captcha->valid_code($captcha)) {
			$result['captcha'] = 60306; //cod invalid
		}
		//---
		$ar = array_filter($result);
		if(!empty($ar)) {
			return $result;
		} else {
			$Captcha->unset_code(); //previne inregistrarile automate
		}
		//-------------------------------------
		
		// DB ---------------------------------
		$data = $this->get_user_data(null, null, $email);
		$data['name']            = $name;
		$data['uname']           = $subdomain;
		$data['email']           = $email;
		$data['password']        = crypt($password);
		$data['status']          = 0;
		$data['token']           = $data['token'] ? $data['token'] : rkeysa(rand(6, 8));
		$data['id_lang']         = $lang_data['id_lang'];
		$data['date_registered'] = gmdate(DATE_FORMAT_DB);
		$obj = $this->UserMapper->create($data);
		$id_user = $this->UserMapper->save($obj);
		
		if(!$id_user) {
			$result['alert'] = 60308; //mesaj eroare neasteptata
			return $result;
		}
		
		
		//--- pachet default ---
		$PackageService = new PackageService();
		$pdd = $PackageService->get_default_package_data($data['id_lang']);
		
		$UserPackageService = new UserPackageService();
		$id_user_package = $UserPackageService->add_package($id_user, $pdd['id_package'], 1); //--- 1 luni
		
		if(!$id_user_package) {
			$result['alert'] = 60308; //mesaj eroare neasteptata
			return $result;
		}
		//-------------------------------------
		
		// email de confirmare ----------------
		$LangEmailService = new LangEmailService();
		$lec = 1; //--- cod lang_email
		$lang_email = $LangEmailService->get_email_by_codes($lang_data['id_lang'], array($lec))[$lec];
		
		$URLService = new URLService();
		$url_confirm = $URLService->get_url(604, array(1=>$data['uname'], 2=>$data['token']), null, true);
		
		//subject
		$twig    = new Twig_Environment(new Twig_Loader_String());
		$subject = $twig->render($lang_email['subject'], array(
			'SITE_NAME' => SITE_NAME
		));
		//body text
		$twig      = new Twig_Environment(new Twig_Loader_String());
		$body_text = $twig->render($lang_email['body_text'], array(
			'SITE_NAME'    => SITE_NAME,
			'CONFIRM_LINK' => $url_confirm,
			'SITE_ADDRESS' => SITE_ADDRESS
		));
		//body html
		$twig    = new Twig_Environment(new Twig_Loader_String());
		$em_body = $twig->render($lang_email['body_html'], array(
			'SITE_NAME'    => SITE_NAME,
			'CONFIRM_LINK' => $url_confirm,
			'SITE_URL'     => URL_ABSOLUTE,
			'SITE_ADDRESS' => SITE_ADDRESS
		));
		//---
		$loader    = new Twig_Loader_Filesystem(SYS_VIEW_HTML_EMAIL);
		$twig      = new Twig_Environment($loader);
		$template  = $twig->loadTemplate('email.page.html');
		$body_html = $template->render(array(
			'PAGE_LANG'        => $lang_data['iso_code'],
			'PAGE_TITLE'       => $subject,
			'URL_ABSOLUTE_CSS' => URL_ABSOLUTE_CSS,
			'EMAIL_BODY'       => $em_body
		));
		
		
		$EmailService = new EmailService();
		$from['email']   = null;
		$from['name']    = null;
		$to['email']     = $email;
		$to['name']      = $name;
		$replay['email'] = null;
		$replay['name']  = null;
		$body['text']    = $body_text;
		$body['html']    = $body_html;
		$result['alert'] = $EmailService->send_email($from, $to, $replay, $subject, $body) ? 60309 : 60308;
		//-------------------------------------
		
		return $result;
	}
	
	
	
	//--- Confirmare cont nou inregistrat -----------------
	public function register_confirm($uname, $token) {
		$data = $this->get_user_data(null, $uname);
		if( $data['id_user'] && $data['token'] && 
			($data['token'] === $token) && ($data['status'] === 0) ) {
			
			$data['status'] = 1;
			$data['token']  = null;
			$obj = $this->UserMapper->create($data);
			$result['alert'] = $this->UserMapper->save($obj) ? 60403 : 60402;
			return $result;
		}
		$result['alert'] = 60401; //err id/token invalid sau status != 0
		return $result;
	}
	
	
	
	//--- Recupereaza parola cont -------------------------
	public function recovery($email, $captcha) {
		$result = array(
			'alert'   => null,
			'email'   => null,
			'captcha' => null
		);
		
		// Validari ---------------------------
		$data = $this->get_user_data(null, null, $email);
		if(!$data['id_user'] || ($data['status']!==1)) {
			$result['email'] = 60501; //email invalid
		}
		$Captcha = new Captcha();
		if(!$Captcha->valid_code($captcha)) {
			$result['captcha'] = 60502; //cod invalid
		}
		//---
		$ar = array_filter($result);
		if(!empty($ar)) {
			return $result;
		}
		//---
		$Captcha->unset_code(); //previne inregistrarile automate
		//-------------------------------------
		
		// DB ---------------------------------
		$data['token_recovery'] = $data['token_recovery'] ? $data['token_recovery'] : rkeysa(rand(6, 8));
		$data['date_recovery']  = gmdate(DATE_FORMAT_DB);
		$obj = $this->UserMapper->create($data);
		$id_user = $this->UserMapper->save($obj);
		
		if(!$id_user) {
			$result['alert'] = 60503; //mesaj eroare neasteptata - eroare db
			return $result;
		}
		//-------------------------------------
		
		// email de confirmare ----------------
		$LangService = new LangService();
		$lang_data   = $LangService->get_lang_data($data['id_lang']);
		
		$LangEmailService = new LangEmailService();
		$lec = 2; //--- cod lang_email
		$lang_email = $LangEmailService->get_email_by_codes($lang_data['id_lang'], array($lec))[$lec];
		
		$URLService = new URLService();
		$url_confirm = $URLService->get_url(606, array(1=>$data['uname'], 2=>$data['token_recovery']), null, true);
		
		//subject
		$twig    = new Twig_Environment(new Twig_Loader_String());
		$subject = $twig->render($lang_email['subject'], array(
			'SITE_NAME' => SITE_NAME
		));
		//body text
		$twig      = new Twig_Environment(new Twig_Loader_String());
		$body_text = $twig->render($lang_email['body_text'], array(
			'SITE_NAME'    => SITE_NAME,
			'CONFIRM_LINK' => $url_confirm,
			'SITE_ADDRESS' => SITE_ADDRESS
		));
		//body html
		$twig    = new Twig_Environment(new Twig_Loader_String());
		$em_body = $twig->render($lang_email['body_html'], array(
			'SITE_NAME'    => SITE_NAME,
			'CONFIRM_LINK' => $url_confirm,
			'SITE_URL'     => URL_ABSOLUTE,
			'SITE_ADDRESS' => SITE_ADDRESS
		));
		//---
		$loader    = new Twig_Loader_Filesystem(SYS_VIEW_HTML_EMAIL);
		$twig      = new Twig_Environment($loader);
		$template  = $twig->loadTemplate('email.page.html');
		$body_html = $template->render(array(
			'PAGE_LANG'        => $lang_data['iso_code'],
			'PAGE_TITLE'       => $subject,
			'URL_ABSOLUTE_CSS' => URL_ABSOLUTE_CSS,
			'EMAIL_BODY'       => $em_body
		));
		
		
		$EmailService = new EmailService();
		$from['email']   = null;
		$from['name']    = null;
		$to['email']     = $data['email'];
		$to['name']      = $data['name'];
		$replay['email'] = null;
		$replay['name']  = null;
		$body['text']    = $body_text;
		$body['html']    = $body_html;
		$result['alert'] = $EmailService->send_email($from, $to, $replay, $subject, $body) ? 60504 : 60503;
		//-------------------------------------
		
		return $result;
	}
	
	
	//--- verifica daca este setata o recuperare ----------
	public function valid_recovery_confirm($uname, $token) {
		$data = $this->get_user_data(null, $uname);
		return ( $data['id_user'] && $data['token_recovery'] && 
					($data['token_recovery'] === $token) && 
					($data['status'] === 1) ) ? true : false;
	}
	
	
	//--- Confirmare cont nou inregistrat -----------------
	public function recovery_confirm_password($uname, $token, $npassword, $npassword_confirm) {
		$result = array(
			'alert'             => null,
			'npassword'         => null,
			'npassword_confirm' => null
		);
		
		if(!$this->valid_recovery_confirm($uname, $token)) {
			$result['alert'] = 60604; //id/token invalid sau status != 1
			return $result;
		}
		if(!$this->valid_register_password($npassword)) {
			$result['npassword'] = 60303; //parola incorecta
		}
		if($npassword !== $npassword_confirm) {
			$result['npassword_confirm'] = 60304; //confirmare parola esuata
		}
		//---
		$ar = array_filter($result);
		if(!empty($ar)) {
			return $result;
		}
		//---
		
		$data = $this->get_user_data(null, $uname);
		$data['password']       = crypt($npassword);
		$data['token_recovery'] = null;
		$data['date_recovery']  = null;
		$obj = $this->UserMapper->create($data);
		$result['alert'] = $this->UserMapper->save($obj) ? 60603 : 60602;
		
		return $result;
	}
	
	
	//=====================================================
	
	
	//--- valideaza domeniile custom ----------------------
	private function valid_host($host) {
		if(!valid_active_host($host)) { //--- host
			return false;
		}
		$data = $this->get_user_data(null, null, null, $host);
		if($data['id_user']) { //--- exista deja un cont cu acest host
			return false;
		}
		
		//--- verific daca hostul este setat (indica spre server - a record, cname, etc...)
		$token = rkeysa(rand(6, 8)); //--- previne utilizatorii spre a reproduce url-ul de validare
		$URLService = new URLService();
		$URLService->set_base_url(SITE_PROTOCOL.'://'.$host);
		
		$opts = array(SITE_PROTOCOL => array(
										'method'  => 'GET',
										'timeout' => 15 
									)
				); //--- timeout(sec.) pentru file_get_contents
		$context = stream_context_create($opts);
		$json = (string)@file_get_contents($URLService->get_url(105, array(1=>$token), null, true), false, $context);
		
		$jres = json_decode($json, true);
		if( is_array($jres) && array_key_exists('host', $jres) && array_key_exists('token', $jres) &&
		($jres['token']===$token) && ($jres['host']===$host) ) {
			return true;
		}
		
		return false;
	}
	
	
	//--- preferinte cont ---------------------------------
	public function change_preferences($id_user, $name, $subdomain, $domain, $id_lang, $timezone, $password) {
		$result = array(
			'alert'     => null,
			'name'      => null,
			'subdomain' => null,
			'domain'    => null,
			'language'  => null,
			'timezone'  => null,
			'password'  => null
		);
		
		//--------------------------
		$data = $this->get_user_data($id_user);
		if(!$data['id_user']) {
			$result['alert'] = 60806;
			return $result;
		}
		
		if(!$this->valid_register_name($name)) {
			$result['name'] = 60810;
		}
		$subdomain = set_lowercase_string($subdomain);
		if( ($subdomain!==$data['uname']) && 
		!$this->valid_register_username($subdomain, $data['email']) ) {
			$result['subdomain'] = 60811;
		}
		$domain = strlen($domain) ? set_lowercase_string($domain) : null;
		if($domain && ($domain !== $data['host']) && !$this->valid_host($domain)) {
			$result['domain'] = 60812;
		}
		$LangService = new LangService();
		$data_lang = $LangService->get_lang_data($id_lang);
		if(!$data_lang['id_lang']) {
			$result['language'] = 60801;
		}
		if(!$timezone) {
			$timezone = null;
		} else {
			$Timezones = new Timezones();
			if(!$Timezones->valid_timezone($timezone)) {
				$result['timezone'] = 60802;
			}
		}
		if(!$this->match_password($data['password'], $password)) {
			$result['password'] = 60803;
		}
		//---
		$ar = array_filter($result);
		if(!empty($ar)) {
			return $result;
		}
		//---
		if( ($name===$data['name']) && 
		($subdomain===$data['uname']) && 
		($domain===$data['host']) && 
		($id_lang===$data['id_lang']) && 
		($timezone===$data['timezone']) ) {
			$result['alert'] = 60805; //nimic de actualizat
			return $result;
		}
		//---------------------------
		
		//--- DB --------------------
		$data['name']     = $name;
		$data['uname']    = $subdomain;
		$data['host']     = $domain;
		$data['id_lang']  = (int)$id_lang;
		$data['timezone'] = $timezone;
		$obj = $this->UserMapper->create($data);
		$result['alert'] = $this->UserMapper->save($obj) ? 60804 : 60806;
		
		return $result;
	}
	
	
	//--- modifica email cont -----------------------------
	public function change_email($id_user, $newemail, $password) {
		$result = array(
			'alert'    => null,
			'email'    => null,
			'password' => null
		);
		
		// Validari ---------------------------
		$data = $this->get_user_data($id_user);
		if(!$data['id_user']) {
			$result['alert'] = 60904;
			return $result;
		}
		
		if($newemail === $data['email']) {
			$result['email'] = 60903; //email identic
		} else {
			if(!$this->valid_register_email($newemail)) {
				$result['email'] = 60901; //email invalid
			}
		}
		if(!$this->match_password($data['password'], $password)) {
			$result['password'] = 60902;
		}
		//---
		$ar = array_filter($result);
		if(!empty($ar)) {
			return $result;
		}
		//-------------------------------------
		
		// DB ---------------------------------
		$data['newemail']       = $newemail;
		$data['token_newemail'] = rkeysa(rand(6, 8));
		$data['date_newemail']  = gmdate(DATE_FORMAT_DB);
		$obj = $this->UserMapper->create($data);
		$id_user = $this->UserMapper->save($obj);
		
		if(!$id_user) {
			$result['alert'] = 60904; //mesaj eroare neasteptata - eroare db
			return $result;
		}
		//-------------------------------------
		
		
		// email de confirmare ----------------
		$LangService = new LangService();
		$lang_data   = $LangService->get_lang_data($data['id_lang']);
		
		$LangEmailService = new LangEmailService();
		$lec = 4; //--- cod lang_email
		$lang_email = $LangEmailService->get_email_by_codes($lang_data['id_lang'], array($lec))[$lec];
		
		$URLService = new URLService();
		$url_confirm = $URLService->get_url(610, array(1=>$data['uname'], 2=>$data['token_newemail']), null, true);
		
		//subject
		$twig    = new Twig_Environment(new Twig_Loader_String());
		$subject = $twig->render($lang_email['subject'], array(
			'SITE_NAME' => SITE_NAME
		));
		//body text
		$twig      = new Twig_Environment(new Twig_Loader_String());
		$body_text = $twig->render($lang_email['body_text'], array(
			'SITE_NAME'    => SITE_NAME,
			'CONFIRM_LINK' => $url_confirm,
			'SITE_ADDRESS' => SITE_ADDRESS
		));
		//body html
		$twig    = new Twig_Environment(new Twig_Loader_String());
		$em_body = $twig->render($lang_email['body_html'], array(
			'SITE_NAME'    => SITE_NAME,
			'CONFIRM_LINK' => $url_confirm,
			'SITE_URL'     => URL_ABSOLUTE,
			'SITE_ADDRESS' => SITE_ADDRESS
		));
		//---
		$loader    = new Twig_Loader_Filesystem(SYS_VIEW_HTML_EMAIL);
		$twig      = new Twig_Environment($loader);
		$template  = $twig->loadTemplate('email.page.html');
		$body_html = $template->render(array(
			'PAGE_LANG'        => $lang_data['iso_code'],
			'PAGE_TITLE'       => $subject,
			'URL_ABSOLUTE_CSS' => URL_ABSOLUTE_CSS,
			'EMAIL_BODY'       => $em_body
		));
		
		
		$EmailService = new EmailService();
		$from['email']   = null;
		$from['name']    = null;
		$to['email']     = $data['newemail'];
		$to['name']      = $data['name'];
		$replay['email'] = null;
		$replay['name']  = null;
		$body['text']    = $body_text;
		$body['html']    = $body_html;
		$result['alert'] = $EmailService->send_email($from, $to, $replay, $subject, $body) ? 60905 : 60904;
		//-------------------------------------
		
		return $result;
	}
	
	
	//--- confirmare modificare adresa email --------------
	public function change_email_confirm($uname, $token) {
		$result = array('alert' => 61001); //implicit err id/token invalid
		
		$data = $this->get_user_data(null, $uname);
		
		if( $data['id_user'] && $data['newemail'] && $data['token_newemail'] && 
		($data['token_newemail'] === $token) && ($data['status'] === 1) ) {
			if($this->valid_register_email($data['newemail'])) {
				//--- sterg eventual cont neconfirmat
				$data_del = $this->get_user_data(null, null, $data['newemail']);
				$obj_del = $this->UserMapper->create($data_del);
				$this->UserMapper->delete($obj_del);
				//---
				
				$data['email']          = $data['newemail'];
				$data['newemail']       = null;
				$data['token_newemail'] = null;
				$data['date_newemail']  = null;
				$obj = $this->UserMapper->create($data);
				$result['alert'] = $this->UserMapper->save($obj) ? 61003 : 61002;
			} else { //exista deja cont - sterg request newemail
				$data['newemail']       = null;
				$data['token_newemail'] = null;
				$data['date_newemail']  = null;
				$obj = $this->UserMapper->create($data);
				$this->UserMapper->save($obj);
				$result['alert'] = 61001; //email invalid - cont deja existent
			}
		}
		
		return $result;
	}
	
	
	//--- modifica parola cont ----------------------------
	public function change_password($id_user, $npassword, $npassword_confirm, $password) {
		$result = array(
			'alert'             => null,
			'npassword'         => null,
			'npassword_confirm' => null,
			'password'          => null
		);
		
		//--------------------------
		$data = $this->get_user_data($id_user);
		
		if(!$this->valid_register_password($npassword)) {
			$result['npassword'] = 61101; //parola noua incorecta
		}
		if($npassword !== $npassword_confirm) {
			$result['npassword_confirm'] = 61102; //confirmare parola noua esuata
		}
		if(!$this->match_password($data['password'], $password)) {
			$result['password'] = 61103;
		}
		//---
		$ar = array_filter($result);
		if(!empty($ar)) {
			return $result;
		}
		//---------------------------
		
		$data['password'] = crypt($npassword);
		$obj = $this->UserMapper->create($data);
		$result['alert'] = $this->UserMapper->save($obj) ? 61104 : 61106;
		
		return $result;
	}
	//-----------------------------------------------------
	
	
	//--- dezactivare cont --------------------------------
	public function disable($id_user, $password) {
		$result = array('alert'=>null, 'password'=>null);
		
		// Validari ---------------------------
		$data = $this->get_user_data($id_user);
		
		if(!$this->match_password($data['password'], $password)) {
			$result['password'] = 61202;
		}
		//---
		$ar = array_filter($result);
		if(!empty($ar)) {
			return $result;
		}
		//-------------------------------------
		
		// DB ---------------------------------
		$data['token_disable'] = rkeysa(rand(6, 8));
		$data['date_disable']  = gmdate(DATE_FORMAT_DB);
		$obj = $this->UserMapper->create($data);
		$id_user = $this->UserMapper->save($obj);
		
		if(!$id_user) {
			$result['alert'] = 61204; //mesaj eroare neasteptata
			return $result;
		}
		//-------------------------------------
		
		
		// email de confirmare ----------------
		$LangService = new LangService();
		$lang_data   = $LangService->get_lang_data($data['id_lang']);
		
		$LangEmailService = new LangEmailService();
		$lec = 3; //--- cod lang_email
		$lang_email = $LangEmailService->get_email_by_codes($lang_data['id_lang'], array($lec))[$lec];
		
		$URLService = new URLService();
		$url_confirm = $URLService->get_url(613, array(1=>$data['uname'], 2=>$data['token_disable']), null, true);
		
		//subject
		$twig    = new Twig_Environment(new Twig_Loader_String());
		$subject = $twig->render($lang_email['subject'], array(
			'SITE_NAME' => SITE_NAME
		));
		//body text
		$twig      = new Twig_Environment(new Twig_Loader_String());
		$body_text = $twig->render($lang_email['body_text'], array(
			'SITE_NAME'    => SITE_NAME,
			'CONFIRM_LINK' => $url_confirm,
			'SITE_ADDRESS' => SITE_ADDRESS
		));
		//body html
		$twig    = new Twig_Environment(new Twig_Loader_String());
		$em_body = $twig->render($lang_email['body_html'], array(
			'SITE_NAME'    => SITE_NAME,
			'CONFIRM_LINK' => $url_confirm,
			'SITE_URL'     => URL_ABSOLUTE,
			'SITE_ADDRESS' => SITE_ADDRESS
		));
		//---
		$loader    = new Twig_Loader_Filesystem(SYS_VIEW_HTML_EMAIL);
		$twig      = new Twig_Environment($loader);
		$template  = $twig->loadTemplate('email.page.html');
		$body_html = $template->render(array(
			'PAGE_LANG'        => $lang_data['iso_code'],
			'PAGE_TITLE'       => $subject,
			'URL_ABSOLUTE_CSS' => URL_ABSOLUTE_CSS,
			'EMAIL_BODY'       => $em_body
		));
		
		
		$EmailService = new EmailService();
		$from['email']   = null;
		$from['name']    = null;
		$to['email']     = $data['email'];
		$to['name']      = $data['name'];
		$replay['email'] = null;
		$replay['name']  = null;
		$body['text']    = $body_text;
		$body['html']    = $body_html;
		$result['alert'] = $EmailService->send_email($from, $to, $replay, $subject, $body) ? 61205 : 61204;
		//-------------------------------------
		
		return $result;
	}
	
	
	//--- Confirmare dezactivare cont ---------------------
	public function disable_confirm($uname, $token_disable) {
		$result = array('alert' => 61301); //implicit err id/token invalid sau status != 1
		$data = $this->get_user_data(null, $uname);
		if( $data['id_user'] && $data['token_disable'] && 
		($data['token_disable'] === $token_disable) && ($data['status'] === 1) ) {
		
			$data['status']        = -1;
			$data['token_disable'] = null;
			$obj = $this->UserMapper->create($data);
			$result['alert'] = $this->UserMapper->save($obj) ? 61303 : 61302; //succes/update esuat
		}
		return $result;
	}
	
	
	
}
