<?php declare(strict_types=1);
/**
 * EmailService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class EmailService {
	
	protected $EmailMapper;
	
	public function __construct() {
		$this->EmailMapper = new EmailMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(EmailObject $EmailObject) {
		$data['id_email'] = $EmailObject->get_data()['id_email'];
		$data['code']     = $EmailObject->get_data()['code'];
		return $data;
	}
	
	
	//--- returneaza date rute
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_email' => null,
			'code'     => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->EmailMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
