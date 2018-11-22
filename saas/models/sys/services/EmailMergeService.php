<?php declare(strict_types=1);
/**
 * EmailMergeService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class EmailMergeService {
	
	protected $EmailMergeMapper;
	
	public function __construct() {
		$this->EmailMergeMapper = new EmailMergeMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(EmailMergeObject $EmailMergeObject) {
		$data['id_email']  = $EmailMergeObject->get_data()['id_email'];
		$data['code']      = $EmailMergeObject->get_data()['code'];
		$data['id_lang']   = $EmailMergeObject->get_data()['id_lang'];
		$data['subject']   = $EmailMergeObject->get_data()['subject'];
		$data['body_text'] = $EmailMergeObject->get_data()['body_text'];
		$data['body_html'] = $EmailMergeObject->get_data()['body_html'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_email' => null,
			'code'     => null,
			'id_lang'  => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->EmailMergeMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
