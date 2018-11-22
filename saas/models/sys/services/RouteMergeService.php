<?php declare(strict_types=1);
/**
 * RouteMergeService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class RouteMergeService {
	
	protected $RouteMergeMapper;
	
	public function __construct() {
		$this->RouteMergeMapper = new RouteMergeMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(RouteMergeObject $RouteMergeObject) {
		$data['id_route']   = $RouteMergeObject->get_data()['id_route'];
		$data['code']       = $RouteMergeObject->get_data()['code'];
		$data['controller'] = $RouteMergeObject->get_data()['controller'];
		$data['action']     = $RouteMergeObject->get_data()['action'];
		$data['methods']    = $RouteMergeObject->get_data()['methods'];
		$data['id_lang']    = $RouteMergeObject->get_data()['id_lang'];
		$data['pattern']    = $RouteMergeObject->get_data()['pattern'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_route' => null,
			'id_lang'  => null,
			'code'     => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->RouteMergeMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
