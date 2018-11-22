<?php declare(strict_types=1);
/**
 * MsgMergeService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class MsgMergeService {
	
	protected $MsgMergeMapper;
	
	public function __construct() {
		$this->MsgMergeMapper = new MsgMergeMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(MsgMergeObject $MsgMergeObject) {
		$data['id_msg']  = $MsgMergeObject->get_data()['id_msg'];
		$data['code']    = $MsgMergeObject->get_data()['code'];
		$data['type']    = $MsgMergeObject->get_data()['type'];
		$data['id_lang'] = $MsgMergeObject->get_data()['id_lang'];
		$data['title']   = $MsgMergeObject->get_data()['title'];
		$data['message'] = $MsgMergeObject->get_data()['message'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_msg'  => null,
			'code'    => null,
			'id_lang' => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->MsgMergeMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
