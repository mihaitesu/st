<?php declare(strict_types=1);
/**
 * SendEmailMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class SendEmailMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id(null);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new SendEmailObject();
	}
	
	
	//--- insert db (persistent storage)
	protected function _insert(AbstractObject $obj) {
		//---
		
	}
	
	
	//--- update db
	protected function _update(AbstractObject $obj) {
		//---
		
	}
	
	
	//--- delete db
	protected function _delete(AbstractObject $obj) {
		//---
		
	}
	
	
	//--- returneaza obiect Email
	public function get_obj($from, $to, $replay, $subject, $body) {
		$data = array();
		if( is_array($from) && 
			array_key_exists('email', $from) && 
			array_key_exists('name', $from) ) {
			//---
			$data['from']['email'] = $from['email'];
			$data['from']['name']  = $from['name'];
		}
		if( is_array($to) && 
			array_key_exists('email', $to) && 
			array_key_exists('name', $to) ) {
			//---
			$data['to']['email'] = $to['email'];
			$data['to']['name']  = $to['name'];
		}
		if( is_array($replay) && 
			array_key_exists('email', $replay) && 
			array_key_exists('name', $replay) ) {
			//---
			$data['replay']['email'] = $replay['email'];
			$data['replay']['name']  = $replay['name'];
		}
		$data['subject'] = $subject;
		if( is_array($body) && 
			array_key_exists('text', $body) && 
			array_key_exists('html', $body) ) {
			//---
			$data['body']['text'] = $body['text'];
			$data['body']['html'] = $body['html'];
		}
		return $this->create($data);
	}
	
	
	
}
