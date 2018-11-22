<?php declare(strict_types=1);
/**
 * MsgMergeObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class MsgMergeObject extends AbstractObject {
	
	protected $data = array(
		'id_msg'  => null,
		'code'    => null,
		'type'    => null,
		'id_lang' => null,
		'title'   => null,
		'message' => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_msg']  = ($data['id_msg']  !== null) ?    (int)$data['id_msg']  : null;
		$this->data['code']    = ($data['code']    !== null) ?    (int)$data['code']    : null;
		$this->data['type']    = ($data['type']    !== null) ?    (int)$data['type']    : null;
		$this->data['id_lang'] = ($data['id_lang'] !== null) ?    (int)$data['id_lang'] : null;
		$this->data['title']   = ($data['title']   !== null) ? (string)$data['title']   : null;
		$this->data['message'] = ($data['message'] !== null) ? (string)$data['message'] : null;
	}
	
}
