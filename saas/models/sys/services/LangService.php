<?php declare(strict_types=1);
/**
 * LangService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class LangService {
	
	protected $LangMapper;
	
	public function __construct() {
		$this->LangMapper = new LangMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(LangObject $LangObject) {
		$data['id_lang']  = $LangObject->get_data()['id_lang'];
		$data['status']   = $LangObject->get_data()['status'];
		$data['iso_code'] = $LangObject->get_data()['iso_code'];
		$data['name']     = $LangObject->get_data()['name'];
		$data['implicit'] = $LangObject->get_data()['implicit'];
		return $data;
	}
	
	
	//--- returneaza date limbi
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_lang'  => null,
			'status'   => null,
			'iso_code' => null,
			'implicit' => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->LangMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
	public function get_data(array $filter) {
		$data = $this->get_data_list($filter);
		return (count($data)===1) ? $data[0] : array('id_lang'  => null,
							     'status'   => null,
							     'iso_code' => null,
							     'name'     => null,
							     'implicit' => null);
	}
	
	
	//--- returneaza lang default
	public function get_default() {
		return $this->get_data(array('status'=>1, 'implicit'=>1));
	}
	
	
}
