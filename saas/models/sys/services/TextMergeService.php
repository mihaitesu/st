<?php declare(strict_types=1);
/**
 * TextMergeService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class TextMergeService {
	
	protected $TextMergeMapper;
	
	public function __construct() {
		$this->TextMergeMapper = new TextMergeMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(TextMergeObject $TextMergeObject) {
		$data['id_text'] = $TextMergeObject->get_data()['id_text'];
		$data['code']    = $TextMergeObject->get_data()['code'];
		$data['id_lang'] = $TextMergeObject->get_data()['id_lang'];
		$data['text']    = $TextMergeObject->get_data()['text'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_text' => null,
			'codes'   => array(),
			'id_lang' => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->TextMergeMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
}
