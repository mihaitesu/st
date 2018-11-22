<?php declare(strict_types=1);
/**
 * SendEmailService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class SendEmailService {
	
	
	//--- host settings
	const SMTP_HOST = "mail.localhost.test";
	const SMTP_PORT = 26;
	const SMTP_USER = "contact@localhost.test";
	const SMTP_PASS = "******";
	
	
	protected $SendEmailMapper;
	
	public function __construct() {
		$this->SendEmailMapper = new SendEmailMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(SendEmailObject $SendEmailObject) {
		$data['from']['email']  = $SendEmailObject->get_data()['from']['email'];
		$data['from']['name']   = $SendEmailObject->get_data()['from']['name'];
		$data['to']['email']    = $SendEmailObject->get_data()['to']['email'];
		$data['to']['name']     = $SendEmailObject->get_data()['to']['name'];
		$data['reply']['email'] = $SendEmailObject->get_data()['reply']['email'];
		$data['reply']['name']  = $SendEmailObject->get_data()['reply']['name'];
		
		$data['subject']        = $SendEmailObject->get_data()['subject'];
		
		$data['body']['text']   = $SendEmailObject->get_data()['body']['text'];
		$data['body']['html']   = $SendEmailObject->get_data()['body']['html'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_route' => null,
			'code'     => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->RouteMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
	
	
	//--- trimite email
	public function send_email($from, $to, $replay, $subject, $body) {
		$obj = $this->SendEmailMapper->get_obj($from, $to, $replay, $subject, $body);
		return $obj->send_email();
	}
	
	
	//--- trimite email -----------------------------------
	public function send_email() {
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->Host     = self::SMTP_HOST;
		$mail->Port     = self::SMTP_PORT;
		$mail->SMTPAuth = true;
		$mail->Username = self::SMTP_USER;
		$mail->Password = self::SMTP_PASS;
		$mail->SMTPSecure = 'tls';
		
		$mail->CharSet = 'UTF-8';
		$mail->SetFrom($this->data['from']['email'], $this->data['from']['name']);
		$mail->AddReplyTo($this->data['reply']['email'], $this->data['reply']['name']);
		$mail->AddAddress($this->data['to']['email'], $this->data['to']['name']);
		$mail->Subject = $this->data['subject'];
		$mail->IsHTML(true);
		$mail->Body    = $this->data['body']['html'] ? $this->data['body']['html'] : $this->data['body']['text'];
		$mail->AltBody = $this->data['body']['text'];
		
		return $mail->Send() ? true : false;
	}
	
	
}
