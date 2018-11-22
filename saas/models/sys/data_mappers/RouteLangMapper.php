<?php declare(strict_types=1);
/**
 * RouteLangMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class RouteLangMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id(null);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new RouteLangObject();
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
	
	
	//--- returneaza obiect RouteLang
	public function get_obj_list(array $filter) {
		$default_filter = array(
			'id_route' => null,
			'id_lang'  => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$qc = "";
		if($filter['id_route'] !== null) { $qc .= "routes_lang.id_route=:id_route AND "; }
		if($filter['id_lang']  !== null) { $qc .= "routes_lang.id_lang=:id_lang AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate
		}
		//---
		$query = "SELECT routes_lang.id_route, routes_lang.id_lang, routes_lang.pattern 
					FROM routes_lang 
					WHERE ".$qc." 
					ORDER BY routes_lang.id_route";
		$stmt = DB::call()->prepare($query);
		if($filter['id_route'] !== null) { $stmt->bindValue(':id_route',  $filter['id_route'],  PDO::PARAM_INT); }
		if($filter['id_lang']  !== null) { $stmt->bindValue(':id_lang',  $filter['id_lang'],  PDO::PARAM_INT); }
		$stmt->execute();
		$obj_list = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_route'] = (int)$row['id_route'];
			$data['id_lang']  = (int)$row['id_lang'];
			$data['pattern']  = $row['pattern'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
