<?php declare(strict_types=1);
/**
 * MsgLangMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class MsgLangMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id(null);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new MsgLangObject();
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
			'id_msg'  => null,
			'id_lang' => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$qc = "";
		if($filter['id_msg']  !== null) { $qc .= "msgs_lang.id_msg=:id_msg AND "; }
		if($filter['id_lang'] !== null) { $qc .= "msgs_lang.id_lang=:id_lang AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate
		}
		//---
		$query = "SELECT msgs_lang.id_msg, msgs_lang.id_lang, msgs_lang.title, msgs_lang.message 
					FROM msgs_lang 
					WHERE ".$qc;
		$stmt = DB::call()->prepare($query);
		if($filter['id_msg']  !== null) { $stmt->bindValue(':id_msg', $filter['id_msg'], PDO::PARAM_INT); }
		if($filter['id_lang'] !== null) { $stmt->bindValue(':id_lang',  $filter['id_lang'],  PDO::PARAM_INT); }
		$stmt->execute();
		$obj_list = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_msg']  = (int)$row['id_msg'];
			$data['id_lang'] = (int)$row['id_lang'];
			$data['title']   = $row['title'];
			$data['message'] = $row['message'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
