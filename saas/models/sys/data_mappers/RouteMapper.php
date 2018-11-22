<?php declare(strict_types=1);
/**
 * RouteMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class RouteMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id($obj->get_data()['id_route']);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new RouteObject();
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
	
	
	//--- returneaza obiect Route
	public function get_obj_list(array $filter) {
		$default_filter = array(
			'id_route' => null,
			'code'     => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$qc = "";
		if($filter['id_route'] !== null) { $qc .= "routes.id_route=:id_route AND "; }
		if($filter['code']     !== null) { $qc .= "routes.code=:code AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate rutele
		}
		//---
		$query = "SELECT routes.id_route, routes.code, routes.controller, routes.action, routes.methods 
					FROM routes 
					WHERE ".$qc." 
					ORDER BY routes.code";
		$stmt = DB::call()->prepare($query);
		if($filter['id_route'] !== null) { $stmt->bindValue(':id_route',  $filter['id_route'],  PDO::PARAM_INT); }
		if($filter['code']     !== null) { $stmt->bindValue(':code',  $filter['code'],  PDO::PARAM_INT); }
		$stmt->execute();
		$obj_list = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_route']   = (int)$row['id_route'];
			$data['code']       = (int)$row['code'];
			$data['controller'] = $row['controller'];
			$data['action']     = $row['action'];
			$data['methods']    = $row['methods'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
