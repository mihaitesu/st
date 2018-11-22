<?php declare(strict_types=1);
/**
 * TextObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class TextObject extends AbstractObject {
	
	protected $data = array(
		'id_text' => null,
		'code'    => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_text'] = ($data['id_text'] !== null) ? (int)$data['id_text'] : null;
		$this->data['code']    = ($data['code']    !== null) ? (int)$data['code']    : null;
	}
	
}
