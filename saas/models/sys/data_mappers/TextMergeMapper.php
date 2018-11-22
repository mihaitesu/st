<?php declare(strict_types=1);
/**
 * TextMergeMapper.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class TextMergeMapper extends AbstractMapper {
	
	
	//--- seteaza valorile
	public function populate(AbstractObject $obj, array $data) {
		$obj->set_data($data);
		$obj->set_id(null);
		return $obj;
	}
	
	
	//--- creeaza obiect
	protected function _create() {
		return new TextMergeObject();
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
			'codes'   => array(),
			'id_lang' => null
		);
		$filter = array_merge($default_filter, $filter);
		
		$obj_list = array();
		
		if($filter['id_lang'] === null) { //--- id_lang este obligatoriu
			return $obj_list;
		}
		
		$qc = "";
		if($filter['id_text'] !== null) { $qc .= "texts.id_text=:id_text AND "; }
		
		if(!empty($filter['codes'])) {
			$qc .= "texts.code IN (";
			$li = '';
			foreach($filter['codes'] as $key => $val) {
				$li .= ":code".$key.",";
			}
			$li = trim($li, ',');
			$qc .= $li.") AND ";
		}
		if($filter['id_lang'] !== null) { $qc .= "texts_lang.id_lang=:id_lang AND "; }
		$qc = preg_replace('/AND$/', '', trim($qc));
		if(!$qc) {
			$qc = "1"; //--- toate
		}
		//--- nu sunt selectate textele fara "text"
		//--- limba poate fi si inactiva
		$query = "SELECT texts.id_text, texts.code, texts_lang.id_lang, texts_lang.text 
					FROM texts 
					RIGHT JOIN texts_lang 
					ON texts.id_text=texts_lang.id_text 
					WHERE ".$qc." 
					ORDER BY texts.code";
		$stmt = DB::call()->prepare($query);
		if($filter['id_text'] !== null) { $stmt->bindValue(':id_text', $filter['id_text'], PDO::PARAM_INT); }
		if(!empty($filter['codes'])) {
			foreach($filter['codes'] as $key => $val) {
				$stmt->bindValue(':code'.$key, $val, PDO::PARAM_INT);
			}
		}
		if($filter['id_lang'] !== null) { $stmt->bindValue(':id_lang', $filter['id_lang'], PDO::PARAM_INT); }
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data['id_text'] = (int)$row['id_text'];
			$data['code']    = (int)$row['code'];
			$data['id_lang'] = (int)$row['id_lang'];
			$data['text']    = $row['text'];
			$obj_list[] = $this->create($data);
		}
		$stmt = null;
		return $obj_list;
	}
	
	
}
