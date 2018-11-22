<?php declare(strict_types=1);
/**
 * EmailObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class EmailObject extends AbstractObject {
	
	protected $data = array(
		'id_email' => null,
		'code'     => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_email'] = ($data['id_email'] !== null) ? (int)$data['id_email'] : null;
		$this->data['code']     = ($data['code']     !== null) ? (int)$data['code']     : null;
	}
	
}
