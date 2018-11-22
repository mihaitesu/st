<?php declare(strict_types=1);
/**
 * SendEmailObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class SendEmailObject extends AbstractObject {
	
	//--- default from/reply
	const FROM_EMAIL    = "contact@tesu.ro";
	const FROM_NAME     = "Stack Travel";
	const REPLYTO_EMAIL = "contact@tesu.ro";
	const REPLYTO_NAME  = "Stack Travel";
	
	protected $data = array(
		'from' => array(
			'email' => null,
			'name'  => null
		),
		'to' => array(
			'email' => null,
			'name'  => null
		),
		'reply' => array(
			'email' => null,
			'name'  => null
		),
		'subject' => null,
		'body' => array(
			'text' => null,
			'html' => null
		)
	);
	
	public function set_data(array $data) {
		//--- from
		if( array_key_exists('from', $data) && 
			is_array($data['from']) && 
			array_key_exists('email', $data['from']) && 
			array_key_exists('name', $data['from']) ) {
			//---
			$this->data['from']['email'] = $data['from']['email'];
			$this->data['from']['name']  = $data['from']['name'];
		} else {
			$this->data['from'] = array(
							'email' => self::FROM_EMAIL,
							'name'  => self::FROM_NAME
						);
		}
		//--- to
		if( array_key_exists('to', $data) && 
			is_array($data['to']) && 
			array_key_exists('email', $data['to']) && 
			array_key_exists('name', $data['to']) ) {
			//---
			$this->data['to']['email'] = $data['to']['email'];
			$this->data['to']['name']  = $data['to']['name'];
		} else {
			$this->data['to'] = array(
							'email' => null,
							'name'  => null
						);
		}
		//--- reply to
		if( array_key_exists('reply', $data) && 
			is_array($data['reply']) && 
			array_key_exists('email', $data['reply']) && 
			array_key_exists('name', $data['reply']) ) {
			//---
			$this->data['reply']['email'] = $data['reply']['email'];
			$this->data['reply']['name']  = $data['reply']['name'];
		} else {
			$this->data['reply'] = array(
							'email' => self::REPLYTO_EMAIL,
							'name'  => self::REPLYTO_NAME
						);
		}
		//--- subject
		$this->data['subject'] = array_key_exists('subject', $data) ? $data['subject'] : null;
		//--- body
		if( array_key_exists('body', $data) && 
			is_array($data['body']) && 
			array_key_exists('text', $data['body']) && 
			array_key_exists('html', $data['body']) ) {
			//---
			$this->data['body']['text'] = $data['body']['text'];
			$this->data['body']['html'] = $data['body']['html'];
		} else {
			$this->data['body'] = array(
							'text' => null,
							'html'  => null
						);
		}
		//---
	}
	
	
}
