<?php declare(strict_types=1);
/**
 * EmailMergeMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class EmailMergeMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id(null);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new EmailMergeObject();
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
			'id_email' => null,
			'code'     => null,
			'id_lang'  => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list = array();
		
		if($filter['id_lang'] === null) { //--- id_lang este obligatoriu
			return $obj_list;
		}
		
		$qc = "";
		if($filter['id_email'] !== null) { $qc .= "emails.id_email=:id_email AND "; }
		if($filter['code']     !== null) { $qc .= "emails.code=:code AND "; }
		if($filter['id_lang']  !== null) { $qc .= "emails_lang.id_lang=:id_lang AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate
		}
		//--- nu sunt selectate fara "lang"
		//--- limba poate fi si inactiva
		$query = "SELECT emails.id_email, emails.code, emails_lang.id_lang, emails_lang.subject, 
						emails_lang.body_text, emails_lang.body_html 
					FROM emails 
					RIGHT JOIN emails_lang 
					ON emails.id_email=emails_lang.id_email 
					WHERE ".$qc." 
					ORDER BY emails.code";
		$stmt = DB::call()->prepare($query);
		if($filter['id_email'] !== null) { $stmt->bindValue(':id_email', $filter['id_email'], PDO::PARAM_INT); }
		if($filter['code']     !== null) { $stmt->bindValue(':code',  $filter['code'],  PDO::PARAM_INT); }
		if($filter['id_lang']  !== null) { $stmt->bindValue(':id_lang', $filter['id_lang'], PDO::PARAM_INT); }
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_email']  = (int)$row['id_email'];
			$data['code']      = (int)$row['code'];
			$data['id_lang']   = (int)$row['id_lang'];
			$data['subject']   = $row['subject'];
			$data['body_text'] = $row['body_text'];
			$data['body_html'] = $row['body_html'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
