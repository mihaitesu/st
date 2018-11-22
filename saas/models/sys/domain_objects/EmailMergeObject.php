<?php declare(strict_types=1);
/**
 * EmailMergeObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class EmailMergeObject extends AbstractObject {
	
	protected $data = array(
		'id_email'  => null,
		'code'      => null,
		'id_lang'   => null,
		'subject'   => null,
		'body_text' => null,
		'body_html' => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_email']  = ($data['id_email']  !== null) ?    (int)$data['id_email']  : null;
		$this->data['code']      = ($data['code']      !== null) ?    (int)$data['code']      : null;
		$this->data['id_lang']   = ($data['id_lang']   !== null) ?    (int)$data['id_lang']   : null;
		$this->data['subject']   = ($data['subject']   !== null) ? (string)$data['subject']   : null;
		$this->data['body_text'] = ($data['body_text'] !== null) ? (string)$data['body_text'] : null;
		$this->data['body_html'] = ($data['body_html'] !== null) ? (string)$data['body_html'] : null;
	}
	
}
