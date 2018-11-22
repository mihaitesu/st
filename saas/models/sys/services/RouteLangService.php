<?php declare(strict_types=1);
/**
 * RouteLangService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class RouteLangService {
	
	protected $RouteLangMapper;
	
	public function __construct() {
		$this->RouteLangMapper = new RouteLangMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(RouteLangObject $RouteLangObject) {
		$data['id_route'] = $RouteLangObject->get_data()['id_route'];
		$data['id_lang']  = $RouteLangObject->get_data()['id_lang'];
		$data['pattern']  = $RouteLangObject->get_data()['pattern'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_route' => null,
			'id_lang'  => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->RouteLangMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
