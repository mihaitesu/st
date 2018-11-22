<?php declare(strict_types=1);
/**
 * MsgLangService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class MsgLangService {
	
	protected $MsgLangMapper;
	
	public function __construct() {
		$this->MsgLangMapper = new MsgLangMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(MsgLangObject $MsgLangObject) {
		$data['id_msg']  = $MsgLangObject->get_data()['id_msg'];
		$data['id_lang'] = $MsgLangObject->get_data()['id_lang'];
		$data['title']   = $MsgLangObject->get_data()['title'];
		$data['message'] = $MsgLangObject->get_data()['message'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_msg'  => null,
			'id_lang' => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->MsgLangMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
