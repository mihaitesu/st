<?php declare(strict_types=1);
/**
 * UserMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class UserMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id($obj->get_data()['id_user']);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new UserObject();
	}
	
	
	//--- insert db (persistent storage)
	protected function _insert(AbstractObject $obj) {
		$query = "INSERT INTO users (   users.email, 
						users.password, 
						users.status, 
						users.token, 
						users.id_lang, 
						users.timezone, 
						users.date_created,
						users.date_modified,
						users.token_recovery, 
						users.date_recovery, 
						users.newemail, 
						users.token_newemail, 
						users.date_newemail, 
						users.token_disable, 
						users.date_disable, 
						users.ban_start, 
						users.ban_end ) 
				VALUES (:email, :password, :status, :token, 
					:id_lang, :timezone, :date_created, :date_modified, 
					:token_recovery, :date_recovery, 
					:newemail, :token_newemail, :date_newemail, 
					:token_disable, :date_disable, 
					:ban_start, :ban_end)";
		$stmt = DB::call()->prepare($query);
		$stmt->bindParam(':email',           $obj->get_data()['email'],           PDO::PARAM_STR);
		$stmt->bindParam(':password',        $obj->get_data()['password'],        PDO::PARAM_STR);
		$stmt->bindParam(':status',          $obj->get_data()['status'],          PDO::PARAM_INT);
		$stmt->bindParam(':token',           $obj->get_data()['token'],           PDO::PARAM_STR);
		$stmt->bindParam(':id_lang',         $obj->get_data()['id_lang'],         PDO::PARAM_INT);
		$stmt->bindParam(':timezone',        $obj->get_data()['timezone'],        PDO::PARAM_STR);
		$stmt->bindParam(':date_created',    $obj->get_data()['date_created'],    PDO::PARAM_STR);
		$stmt->bindParam(':date_modified',   $obj->get_data()['date_modified'],   PDO::PARAM_STR);
		$stmt->bindParam(':token_recovery',  $obj->get_data()['token_recovery'],  PDO::PARAM_STR);
		$stmt->bindParam(':date_recovery',   $obj->get_data()['date_recovery'],   PDO::PARAM_STR);
		$stmt->bindParam(':newemail',        $obj->get_data()['newemail'],        PDO::PARAM_STR);
		$stmt->bindParam(':token_newemail',  $obj->get_data()['token_newemail'],  PDO::PARAM_STR);
		$stmt->bindParam(':date_newemail',   $obj->get_data()['date_newemail'],   PDO::PARAM_STR);
		$stmt->bindParam(':token_disable',   $obj->get_data()['token_disable'],   PDO::PARAM_STR);
		$stmt->bindParam(':date_disable',    $obj->get_data()['date_disable'],    PDO::PARAM_STR);
		$stmt->bindParam(':ban_start',       $obj->get_data()['ban_start'],       PDO::PARAM_STR);
		$stmt->bindParam(':ban_end',         $obj->get_data()['ban_end'],         PDO::PARAM_STR);
		$stmt->execute();
		$id_user = (int)DB::call()->lastInsertId();
		if(!$id_user || !$stmt->rowCount()) {
			$id_user = null;
		}
		$stmt = null;
		return $id_user;
	}
	
	
	//--- update db
	protected function _update(AbstractObject $obj) {
		if(!$obj->get_data()['id_user']) return null;
		$query = "UPDATE users SET 
					users.email=:email, 
					users.password=:password, 
					users.status=:status, 
					users.token=:token, 
					users.id_lang=:id_lang, 
					users.timezone=:timezone, 
					users.date_created=:date_created, 
					users.date_modified=:date_modified,
					users.token_recovery=:token_recovery, 
					users.date_recovery=:date_recovery, 
					users.newemail=:newemail, 
					users.token_newemail=:token_newemail, 
					users.date_newemail=:date_newemail, 
					users.token_disable=:token_disable, 
					users.date_disable=:date_disable, 
					users.ban_start=:ban_start, 
					users.ban_end=:ban_end 
				WHERE users.id_user=:id_user";
		$stmt = DB::call()->prepare($query);
		$stmt->bindParam(':email',           $obj->get_data()['email'],           PDO::PARAM_STR);
		$stmt->bindParam(':password',        $obj->get_data()['password'],        PDO::PARAM_STR);
		$stmt->bindParam(':status',          $obj->get_data()['status'],          PDO::PARAM_INT);
		$stmt->bindParam(':token',           $obj->get_data()['token'],           PDO::PARAM_STR);
		$stmt->bindParam(':id_lang',         $obj->get_data()['id_lang'],         PDO::PARAM_INT);
		$stmt->bindParam(':timezone',        $obj->get_data()['timezone'],        PDO::PARAM_STR);
		$stmt->bindParam(':date_created',    $obj->get_data()['date_created'],    PDO::PARAM_STR);
		$stmt->bindParam(':date_modified',   $obj->get_data()['date_modified'],   PDO::PARAM_STR);
		$stmt->bindParam(':token_recovery',  $obj->get_data()['token_recovery'],  PDO::PARAM_STR);
		$stmt->bindParam(':date_recovery',   $obj->get_data()['date_recovery'],   PDO::PARAM_STR);
		$stmt->bindParam(':newemail',        $obj->get_data()['newemail'],        PDO::PARAM_STR);
		$stmt->bindParam(':token_newemail',  $obj->get_data()['token_newemail'],  PDO::PARAM_STR);
		$stmt->bindParam(':date_newemail',   $obj->get_data()['date_newemail'],   PDO::PARAM_STR);
		$stmt->bindParam(':token_disable',   $obj->get_data()['token_disable'],   PDO::PARAM_STR);
		$stmt->bindParam(':date_disable',    $obj->get_data()['date_disable'],    PDO::PARAM_STR);
		$stmt->bindParam(':ban_start',       $obj->get_data()['ban_start'],       PDO::PARAM_STR);
		$stmt->bindParam(':ban_end',         $obj->get_data()['ban_end'],         PDO::PARAM_STR);
		$stmt->bindParam(':id_user',         $obj->get_data()['id_user'],         PDO::PARAM_INT);
		$stmt->execute();
		$id_user = $obj->get_data()['id_user'];
		if(!$id_user || !$stmt->rowCount()) {
			$id_user = null;
		}
		$stmt = null;
		return $id_user;
	}
	
	
	//--- delete db
	protected function _delete(AbstractObject $obj) {
		if(!$obj->get_data()['id_user']) return null;
		$query = "DELETE FROM users WHERE users.id_user=:id_user";
		$stmt = DB::call()->prepare($query);
		$stmt->bindParam(':id_user', $obj->get_data()['id_user'], PDO::PARAM_INT);
		$stmt->execute();
		$id_user = $obj->get_data()['id_user'];
		if(!$stmt->rowCount()) {
			$id_user = null;
		}
		$stmt = null;
		return $id_user;
	}
	
	
	//--- returneaza array obiecte
	public function get_obj_list(array $filter) {
		$default_filter = array(
			'id_user' => null,
			'email'   => null,
			'status'  => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$qc  = "";
		if($filter['id_user'] !== null) { $qc .= "users.id_user=:id_user AND "; }
		if($filter['email']   !== null) { $qc .= "users.email=:email AND "; }
		if($filter['status']  !== null) { $qc .= "users.status=:status AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toti userii
		}
		//---
		$query = "SELECT users.id_user, users.email, users.password, users.status, 
				users.token, users.id_lang, users.timezone, 
				users.date_created, users.date_modified, 
				users.token_recovery, users.date_recovery, 
				users.newemail, users.token_newemail, users.date_newemail, 
				users.token_disable, users.date_disable, 
				users.ban_start, users.ban_end 
				FROM users 
				WHERE ".$qc;
		$stmt = DB::call()->prepare($query);
		if($filter['id_user'] !== null) { $stmt->bindValue(':id_user', $filter['id_user'], PDO::PARAM_INT); }
		if($filter['email']   !== null) { $stmt->bindValue(':email', $filter['email'], PDO::PARAM_STR); }
		if($filter['status']  !== null) { $stmt->bindValue(':status', $filter['status'], PDO::PARAM_INT); }
		$stmt->execute();
		$obj_list = array();
		if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_user']         = (int)$row['id_user'];
			$data['email']           = $row['email'];
			$data['password']        = $row['password'];
			$data['status']          = (int)$row['status'];
			$data['token']           = $row['token'];
			$data['id_lang']         = (int)$row['id_lang'];
			$data['timezone']        = $row['timezone'];
			$data['date_created']    = $row['date_created'];
			$data['date_modified']   = $row['date_modified'];
			$data['token_recovery']  = $row['token_recovery'];
			$data['date_recovery']   = $row['date_recovery'];
			$data['newemail']        = $row['newemail'];
			$data['token_newemail']  = $row['token_newemail'];
			$data['date_newemail']   = $row['date_newemail'];
			$data['token_disable']   = $row['token_disable'];
			$data['date_disable']    = $row['date_disable'];
			$data['ban_start']       = $row['ban_start'];
			$data['ban_end']         = $row['ban_end'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
