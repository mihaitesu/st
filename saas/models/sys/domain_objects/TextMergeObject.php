<?php declare(strict_types=1);
/**
 * TextMergeObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class TextMergeObject extends AbstractObject {
	
	protected $data = array(
		'id_text' => null,
		'code'    => null,
		'id_lang' => null,
		'text'    => null
	);
	
	public function set_data(array $data) {
		$data = array_merge($this->data, $data);
		
		$this->data['id_text'] = ($data['id_text'] !== null) ?    (int)$data['id_text'] : null;
		$this->data['code']    = ($data['code']    !== null) ?    (int)$data['code']    : null;
		$this->data['id_lang'] = ($data['id_lang'] !== null) ?    (int)$data['id_lang'] : null;
		$this->data['text']    = ($data['text']    !== null) ? (string)$data['text']    : null;
	}
	
}
