<?php declare(strict_types=1);
/**
 * UserSubDomainMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class UserSubDomainMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id($obj->get_data()['id_sub_domain']);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new UserSubDomainObject();
	}
	
	
	//--- insert db (persistent storage)
	protected function _insert(AbstractObject $obj) {
		$query = "INSERT INTO users_sub_domains (users_sub_domains.id_user,
							 users_sub_domains.domain,
							 users_sub_domains.subdomain,
							 users_sub_domains.date_created,
							 users_sub_domains.date_modified)
				VALUES (:id_user, :domain, :subdomain, :date_created, :date_modified)";
		$stmt = DB::call()->prepare($query);
		$stmt->bindParam(':id_user',       $obj->get_data()['id_user'],       PDO::PARAM_INT);
		$stmt->bindParam(':domain',        $obj->get_data()['domain'],        PDO::PARAM_STR);
		$stmt->bindParam(':subdomain',     $obj->get_data()['subdomain'],     PDO::PARAM_STR);
		$stmt->bindParam(':date_created',  $obj->get_data()['date_created'],  PDO::PARAM_STR);
		$stmt->bindParam(':date_modified', $obj->get_data()['date_modified'], PDO::PARAM_STR);
		$stmt->execute();
		$id_sub_domain = (int)DB::call()->lastInsertId();
		if(!$id_sub_domain || !$stmt->rowCount()) {
			$id_sub_domain = null;
		}
		$stmt = null;
		return $id_sub_domain;
	}
	
	
	//--- update db
	protected function _update(AbstractObject $obj) {
		if(!$obj->get_data()['id_sub_domain']) return null;
		$query = "UPDATE users_sub_domains SET  users_sub_domains.id_user=:id_user, 
							users_sub_domains.domain=:domain, 
							users_sub_domains.subdomain=:subdomain,
							users_sub_domains.date_modified=:date_modified
				WHERE users_sub_domains.id_sub_domain=:id_sub_domain";
		$stmt = DB::call()->prepare($query);
		$stmt->bindParam(':id_user',       $obj->get_data()['id_user'],       PDO::PARAM_INT);
		$stmt->bindParam(':domain',        $obj->get_data()['domain'],        PDO::PARAM_STR);
		$stmt->bindParam(':subdomain',     $obj->get_data()['subdomain'],     PDO::PARAM_STR);
		$stmt->bindParam(':date_modified', $obj->get_data()['date_modified'], PDO::PARAM_STR);
		$stmt->bindParam(':id_sub_domain', $obj->get_data()['id_sub_domain'], PDO::PARAM_INT);
		$stmt->execute();
		$id_sub_domain = $obj->get_data()['id_sub_domain'];
		if(!$id_sub_domain || !$stmt->rowCount()) {
			$id_sub_domain = null;
		}
		$stmt = null;
		return $id_sub_domain;
	}
	
	
	//--- delete db
	protected function _delete(AbstractObject $obj) {
		if(!$obj->get_data()['id_sub_domain']) return null;
		$query = "DELETE FROM users_sub_domains WHERE users_sub_domains.id_sub_domain=:id_sub_domain";
		$stmt = DB::call()->prepare($query);
		$stmt->bindParam(':id_sub_domain', $obj->get_data()['id_sub_domain'], PDO::PARAM_INT);
		$stmt->execute();
		$id_sub_domain = $obj->get_data()['id_sub_domain'];
		if(!$stmt->rowCount()) {
			$id_sub_domain = null;
		}
		$stmt = null;
		return $id_sub_domain;
	}
	
	
	//--- returneaza array obiecte
	public function get_obj_list(array $filter) {
		$default_filter = array(
			'id_sub_domain' => null,
			'id_user'       => null,
			'domain'        => null,
			'subdomain'     => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$qc  = "";
		if($filter['id_sub_domain'] !== null) { $qc .= "users_sub_domains.id_sub_domain=:id_sub_domain AND "; }
		if($filter['id_user']       !== null) { $qc .= "users_sub_domains.id_user=:id_user AND "; }
		if($filter['domain']        !== null) { $qc .= "users_sub_domains.domain=:domain AND "; }
		if($filter['subdomain']     !== null) { $qc .= "users_sub_domains.subdomain=:subdomain AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate
		}
		//---
		$query = "SELECT users_sub_domains.id_sub_domain, users_sub_domains.id_user, 
					users_sub_domains.domain, users_sub_domains.subdomain,
					users_sub_domains.date_created, users_sub_domains.date_modified
				FROM users_sub_domains 
				WHERE ".$qc;
		$stmt = DB::call()->prepare($query);
		if($filter['id_sub_domain'] !== null) { $stmt->bindValue(':id_sub_domain', $filter['id_sub_domain'], PDO::PARAM_INT); }
		if($filter['id_user']       !== null) { $stmt->bindValue(':id_user', $filter['id_user'], PDO::PARAM_INT); }
		if($filter['domain']        !== null) { $stmt->bindValue(':domain', $filter['domain'], PDO::PARAM_STR); }
		if($filter['subdomain']     !== null) { $stmt->bindValue(':subdomain', $filter['subdomain'], PDO::PARAM_STR); }
		$stmt->execute();
		$obj_list = array();
		if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_sub_domain'] = (int)$row['id_sub_domain'];
			$data['id_user']       = (int)$row['id_user'];
			$data['domain']        = $row['domain'];
			$data['subdomain']     = $row['subdomain'];
			$data['date_created']  = $row['date_created'];
			$data['date_modified'] = $row['date_modified'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
