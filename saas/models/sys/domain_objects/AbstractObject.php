<?php declare(strict_types=1);
/**
 * AbstractObject.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



abstract class AbstractObject {
	
	protected $id_obj = null; //--- id obiect
	protected $data   = array();
	
	public function __construct(array $data = array()) {
		if(!empty($data)) $this->set_data($data);
	}
	
	abstract public function set_data(array $data);
	
	//--- returneaza id obiect
	public function get_id() {
		return $this->id_obj;
	}
	
	//--- seteaza id_obiect
	public function set_id($id_obj) {
		if (!is_null($this->id_obj)) { //--- id nu trebuie modificat
			return null;
		}
		return $this->id_obj = $id_obj;
	}
	
	public function set_data_null() {
		foreach($this->data as $key => $val) {
			$this->data[$key] = is_array($val) ? array() : null;
		}
	}
	
	public function get_data() {
		return $this->data;
	}
	
}
