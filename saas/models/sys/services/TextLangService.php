<?php declare(strict_types=1);
/**
 * TextLangService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class TextLangService {
	
	protected $TextLangMapper;
	
	public function __construct() {
		$this->TextLangMapper = new TextLangMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(TextLangObject $TextLangObject) {
		$data['id_text'] = $TextLangObject->get_data()['id_text'];
		$data['id_lang'] = $TextLangObject->get_data()['id_lang'];
		$data['text']    = $TextLangObject->get_data()['text'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_text' => null,
			'id_lang' => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->TextLangMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
