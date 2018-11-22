<?php declare(strict_types=1);
/**
 * LangObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class LangObject extends AbstractObject {
	
	protected $data = array(
		'id_lang'  => null,
		'status'   => null,
		'iso_code' => null,
		'name'     => null,
		'implicit' => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_lang']  = ($data['id_lang']  !== null) ?    (int)$data['id_lang']  : null;
		$this->data['status']   = ($data['status']   !== null) ?    (int)$data['status']   : null;
		$this->data['iso_code'] = ($data['iso_code'] !== null) ? (string)$data['iso_code'] : null;
		$this->data['name']     = ($data['name']     !== null) ? (string)$data['name']     : null;
		$this->data['implicit'] = ($data['implicit'] !== null) ?    (int)$data['implicit'] : null;
	}
	
}
