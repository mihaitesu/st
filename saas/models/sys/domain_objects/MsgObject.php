<?php declare(strict_types=1);
/**
 * MsgObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class MsgObject extends AbstractObject {
	
	protected $data = array(
		'id_msg' => null,
		'code'   => null,
		'type'   => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_msg'] = ($data['id_msg'] !== null) ? (int)$data['id_msg'] : null;
		$this->data['code']   = ($data['code']   !== null) ? (int)$data['code']   : null;
		$this->data['type']   = ($data['type']   !== null) ? (int)$data['type']   : null;
	}
	
}
