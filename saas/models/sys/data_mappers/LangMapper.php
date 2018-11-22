<?php declare(strict_types=1);
/**
 * LangMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class LangMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id($obj->get_data()['id_lang']);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new LangObject();
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
			'id_lang'  => null,
			'status'   => null,
			'iso_code' => null,
			'implicit' => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$qc = "";
		if($filter['id_lang']  !== null) { $qc .= "lang.id_lang=:id_lang AND "; }
		if($filter['status']   !== null) { $qc .= "lang.status=:status AND "; }
		if($filter['iso_code'] !== null) { $qc .= "BINARY lang.iso_code=:iso_code AND "; }
								/*comparatia iso_code trebuie sa fie case sensitive*/
		if($filter['implicit'] !== null) { $qc .= "lang.implicit=:implicit AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate limbile
		}
		//---
		$query = "SELECT lang.id_lang, lang.status, lang.iso_code, lang.name, lang.implicit 
					FROM lang 
					WHERE ".$qc." 
					ORDER BY lang.name";
		$stmt = DB::call()->prepare($query);
		if($filter['id_lang']  !== null) { $stmt->bindValue(':id_lang',  $filter['id_lang'],  PDO::PARAM_INT); }
		if($filter['status']   !== null) { $stmt->bindValue(':status',   $filter['status'],   PDO::PARAM_INT); }
		if($filter['iso_code'] !== null) { $stmt->bindValue(':iso_code', $filter['iso_code'], PDO::PARAM_STR); }
		if($filter['implicit'] !== null) { $stmt->bindValue(':implicit', $filter['implicit'], PDO::PARAM_INT); }
		$stmt->execute();
		$obj_list = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_lang']  = (int)$row['id_lang'];
			$data['status']   = (int)$row['status'];
			$data['iso_code'] = $row['iso_code'];
			$data['name']     = $row['name'];
			$data['implicit'] = (int)$row['implicit'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
