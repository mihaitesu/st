<?php declare(strict_types=1);
/**
 * UserSubDomainService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class UserSubDomainService {
	
	protected $UserSubDomainMapper;
	
	public function __construct() {
		$this->UserSubDomainMapper = new UserSubDomainMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(UserSubDomainObject $UserSubDomainObject) {
		$data['id_sub_domain'] = $UserSubDomainObject->get_data()['id_sub_domain'];
		$data['id_user']       = $UserSubDomainObject->get_data()['id_user'];
		$data['domain']        = $UserSubDomainObject->get_data()['domain'];
		$data['subdomain']     = $UserSubDomainObject->get_data()['subdomain'];
		$data['date_created']  = $UserSubDomainObject->get_data()['date_created'];
		$data['date_modified'] = $UserSubDomainObject->get_data()['date_modified'];
		return $data;
	}
	
	
	//--- returneaza date
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_sub_domain' => null,
			'id_user'       => null,
			'domain'        => null,
			'subdomain'     => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->UserSubDomainMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
