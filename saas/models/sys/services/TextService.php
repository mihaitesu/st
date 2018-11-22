<?php declare(strict_types=1);
/**
 * TextService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class TextService {
	
	protected $TextMapper;
	
	public function __construct() {
		$this->TextMapper = new TextMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(TextObject $TextObject) {
		$data['id_text'] = $TextObject->get_data()['id_text'];
		$data['code']    = $TextObject->get_data()['code'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_text' => null,
			'code'    => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->TextMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
