<?php declare(strict_types=1);
/**
 * AbstractMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



abstract class AbstractMapper {
	
	public function create(array $data = null) {
		$obj = $this->_create();
		if($data) {
			$obj = $this->populate($obj, $data);
		}
		return $obj;
	}
	
	public function save(AbstractObject $obj) {
		if(is_null($obj->get_id())) {
			return $this->_insert($obj);
		} else {
			return $this->_update($obj);
		}
	}
	
	public function delete(AbstractObject $obj) {
		return $this->_delete($obj);
	}
	
	abstract public function populate(AbstractObject $obj, array $data);
	
	abstract protected function _create();
	
	//--- persistent storage (db)
	abstract protected function _insert(AbstractObject $obj);
	abstract protected function _update(AbstractObject $obj);
	abstract protected function _delete(AbstractObject $obj);
	
}
