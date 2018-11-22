<?php declare(strict_types=1);
/**
 * MsgService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class MsgService {
	
	protected $MsgMapper;
	
	public function __construct() {
		$this->MsgMapper = new MsgMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(MsgObject $MsgObject) {
		$data['id_msg'] = $MsgObject->get_data()['id_msg'];
		$data['code']   = $MsgObject->get_data()['code'];
		$data['type']   = $MsgObject->get_data()['type'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_msg' => null,
			'code'   => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->MsgMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
