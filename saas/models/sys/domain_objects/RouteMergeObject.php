<?php declare(strict_types=1);
/**
 * RouteMergeObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class RouteMergeObject extends AbstractObject {
	
	protected $data = array(
		'id_route'   => null,
		'code'       => null,
		'controller' => null,
		'action'     => null,
		'methods'    => null,
		'id_lang'    => null,
		'pattern'    => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_route']   = ($data['id_route']   !== null) ?    (int)$data['id_route']   : null;
		$this->data['code']       = ($data['code']       !== null) ?    (int)$data['code']       : null;
		$this->data['controller'] = ($data['controller'] !== null) ? (string)$data['controller'] : null;
		$this->data['action']     = ($data['action']     !== null) ? (string)$data['action']     : null;
		$this->data['methods']    = ($data['methods']    !== null) ? (string)$data['methods']    : null;
		$this->data['id_lang']    = ($data['id_lang']    !== null) ?    (int)$data['id_lang']    : null;
		$this->data['pattern']    = ($data['pattern']    !== null) ? (string)$data['pattern']    : null;
	}
	
}
