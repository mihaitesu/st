<?php declare(strict_types=1);
/**
 * UserService.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class UserService {
	
	protected $UserMapper;
	
	public function __construct() {
		$this->UserMapper = new UserMapper();
	}
	
	
	//--- obj to array
	private function get_data_obj(UserObject $UserObject) {
		$data['id_user']        = $UserObject->get_data()['id_user'];
		$data['email']          = $UserObject->get_data()['email'];
		$data['password']       = $UserObject->get_data()['password'];
		$data['status']         = $UserObject->get_data()['status'];
		$data['token']          = $UserObject->get_data()['token'];
		$data['id_lang']        = $UserObject->get_data()['id_lang'];
		$data['timezone']       = $UserObject->get_data()['timezone'];
		$data['date_created']   = $UserObject->get_data()['date_created'];
		$data['date_modified']  = $UserObject->get_data()['date_modified'];
		$data['token_recovery'] = $UserObject->get_data()['token_recovery'];
		$data['date_recovery']  = $UserObject->get_data()['date_recovery'];
		$data['newemail']       = $UserObject->get_data()['newemail'];
		$data['token_newemail'] = $UserObject->get_data()['token_newemail'];
		$data['date_newemail']  = $UserObject->get_data()['date_newemail'];
		$data['token_disable']  = $UserObject->get_data()['token_disable'];
		$data['date_disable']   = $UserObject->get_data()['date_disable'];
		$data['ban_start']      = $UserObject->get_data()['ban_start'];
		$data['ban_end']        = $UserObject->get_data()['ban_end'];
		return $data;
	}
	
	
	//--- returneaza date
	public function get_data_list(array $filter) {
		$default_filter = array(
			'id_user' => null,
			'email'   => null,
			'status'  => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list  = $this->UserMapper->get_obj_list($filter);
		$data_list = array();
		foreach($obj_list as $key => $obj) {
			$data_list[] = $this->get_data_obj($obj);
		}
		return $data_list;
	}
	
	
}
