<?php declare(strict_types=1);
/**
 * TextMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class TextMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id($obj->get_data()['id_text']);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new TextObject();
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
			'id_text' => null,
			'code'    => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$qc = "";
		if($filter['id_text'] !== null) { $qc .= "texts.id_text=:id_text AND "; }
		if($filter['code']    !== null) { $qc .= "texts.code=:code AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate
		}
		//---
		$query = "SELECT texts.id_text, texts.code 
					FROM texts 
					WHERE ".$qc;
		$stmt = DB::call()->prepare($query);
		if($filter['id_text'] !== null) { $stmt->bindValue(':id_text', $filter['id_text'], PDO::PARAM_INT); }
		if($filter['code']    !== null) { $stmt->bindValue(':code',  $filter['code'],  PDO::PARAM_INT); }
		$stmt->execute();
		$obj_list = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_text'] = (int)$row['id_text'];
			$data['code']    = (int)$row['code'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
