<?php declare(strict_types=1);
/**
 * RouteLangObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class RouteLangObject extends AbstractObject {
	
	protected $data = array(
		'id_route'   => null,
		'id_lang'    => null,
		'pattern'    => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_route'] = ($data['id_route'] !== null) ?    (int)$data['id_route'] : null;
		$this->data['id_lang']  = ($data['id_lang']  !== null) ?    (int)$data['id_lang']  : null;
		$this->data['pattern']  = ($data['pattern']  !== null) ? (string)$data['pattern']  : null;
	}
	
}
