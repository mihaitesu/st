<?php declare(strict_types=1);
/**
 * EmailLangService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class EmailLangService {
	
	protected $EmailLangMapper;
	
	public function __construct() {
		$this->EmailLangMapper = new EmailLangMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(EmailLangObject $EmailLangObject) {
		$data['id_email']  = $EmailLangObject->get_data()['id_email'];
		$data['id_lang']   = $EmailLangObject->get_data()['id_lang'];
		$data['subject']   = $EmailLangObject->get_data()['subject'];
		$data['body_text'] = $EmailLangObject->get_data()['body_text'];
		$data['body_html'] = $EmailLangObject->get_data()['body_html'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_email' => null,
			'id_lang'  => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->EmailLangMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
