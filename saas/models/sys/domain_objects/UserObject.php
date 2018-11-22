<?php declare(strict_types=1);
/**
 * UserObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class UserObject extends AbstractObject {
	
	protected $data = array(
		'id_user'         => null,
		'email'           => null,
		'password'        => null,
		'status'          => null,
		'token'           => null,
		'id_lang'         => null,
		'timezone'        => null,
		'date_created'    => null,
		'date_modified'   => null,
		'token_recovery'  => null,
		'date_recovery'   => null,
		'newemail'        => null,
		'token_newemail'  => null,
		'date_newemail'   => null,
		'token_disable'   => null,
		'date_disable'    => null,
		'ban_start'       => null,
		'ban_end'         => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_user']         = ($data['id_user']         !== null) ?    (int)$data['id_user']         : null;
		$this->data['email']           = ($data['email']           !== null) ? (string)$data['email']           : null;
		$this->data['password']        = ($data['password']        !== null) ? (string)$data['password']        : null;
		$this->data['status']          = ($data['status']          !== null) ?    (int)$data['status']          : null;
		$this->data['token']           = ($data['token']           !== null) ? (string)$data['token']           : null;
		$this->data['id_lang']         = ($data['id_lang']         !== null) ?    (int)$data['id_lang']         : null;
		$this->data['timezone']        = ($data['timezone']        !== null) ? (string)$data['timezone']        : null;
		$this->data['date_created']    = ($data['date_created']    !== null) ? (string)$data['date_created']    : null;
		$this->data['date_modified']   = ($data['date_modified']   !== null) ? (string)$data['date_modified']   : null;
		$this->data['token_recovery']  = ($data['token_recovery']  !== null) ? (string)$data['token_recovery']  : null;
		$this->data['date_recovery']   = ($data['date_recovery']   !== null) ? (string)$data['date_recovery']   : null;
		$this->data['newemail']        = ($data['newemail']        !== null) ? (string)$data['newemail']        : null;
		$this->data['token_newemail']  = ($data['token_newemail']  !== null) ? (string)$data['token_newemail']  : null;
		$this->data['date_newemail']   = ($data['date_newemail']   !== null) ? (string)$data['date_newemail']   : null;
		$this->data['token_disable']   = ($data['token_disable']   !== null) ? (string)$data['token_disable']   : null;
		$this->data['date_disable']    = ($data['date_disable']    !== null) ? (string)$data['date_disable']    : null;
		$this->data['ban_start']       = ($data['ban_start']       !== null) ? (string)$data['ban_start']       : null;
		$this->data['ban_end']         = ($data['ban_end']         !== null) ? (string)$data['ban_end']         : null;
	}
	
	
}
