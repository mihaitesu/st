<?php declare(strict_types=1);
/**
 * UserSubDomainObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class UserSubDomainObject extends AbstractObject {
	
	protected $data = array(
		'id_sub_domain' => null,
		'id_user'       => null,
		'domain'        => null,
		'subdomain'     => null,
		'date_created'  => null,
		'date_modified' => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_sub_domain'] = ($data['id_sub_domain'] !== null) ?    (int)$data['id_sub_domain'] : null;
		$this->data['id_user']       = ($data['id_user']       !== null) ?    (int)$data['id_user']       : null;
		$this->data['domain']        = ($data['domain']        !== null) ? (string)$data['domain']        : null;
		$this->data['subdomain']     = ($data['subdomain']     !== null) ? (string)$data['subdomain']     : null;
		$this->data['date_created']  = ($data['date_created']  !== null) ? (string)$data['date_created']  : null;
		$this->data['date_modified'] = ($data['date_modified'] !== null) ? (string)$data['date_modified'] : null;
	}
	
}
