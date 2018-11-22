<?php declare(strict_types=1);
/**
 * RouteService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class RouteService {
	
	protected $RouteMapper;
	
	public function __construct() {
		$this->RouteMapper = new RouteMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(RouteObject $RouteObject) {
		$data['id_route']   = $RouteObject->get_data()['id_route'];
		$data['code']       = $RouteObject->get_data()['code'];
		$data['controller'] = $RouteObject->get_data()['controller'];
		$data['action']     = $RouteObject->get_data()['action'];
		$data['methods']    = $RouteObject->get_data()['methods'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_route' => null,
			'code'     => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->RouteMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
