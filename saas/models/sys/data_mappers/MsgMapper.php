<?php declare(strict_types=1);
/**
 * MsgMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class MsgMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id($obj->get_data()['id_msg']);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new MsgObject();
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
	
	
	
	//--- returneaza array obiecte
	public function get_obj_list(array $filter) {
		$default_filter = array(
			'id_msg' => null,
			'code'   => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$qc = "";
		if($filter['id_msg'] !== null) { $qc .= "msgs.id_msg=:id_msg AND "; }
		if($filter['code']    !== null) { $qc .= "msgs.code=:code AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate
		}
		//---
		$query = "SELECT msgs.id_msg, msgs.code, msgs.type 
					FROM msgs 
					WHERE ".$qc;
		$stmt = DB::call()->prepare($query);
		if($filter['id_msg'] !== null) { $stmt->bindValue(':id_msg', $filter['id_msg'], PDO::PARAM_INT); }
		if($filter['code']   !== null) { $stmt->bindValue(':code',  $filter['code'],  PDO::PARAM_INT); }
		$stmt->execute();
		$obj_list = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_msg'] = (int)$row['id_msg'];
			$data['code']   = (int)$row['code'];
			$data['type']   = (int)$row['type'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
