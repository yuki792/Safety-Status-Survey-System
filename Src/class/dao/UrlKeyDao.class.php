<?php
	//require_once('dao.php');

	class UrlKeyDao extends Dao{
		private $table = 'url_key';
		
		function select($url_key_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE url_key_id = :url_key_id');
			$stmt->execute(array(':url_key_id'=>$url_key_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_byKey($key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE `key` = :key');
			$stmt->execute(array(':key'=>$key));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_bySurveyAndMemberAndType($survey_id,$member_id,$type){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE survey_id = :survey_id AND member_id = :member_id AND type = :type');
			$stmt->execute(array(':survey_id'=>$survey_id,':member_id'=>$member_id,':type'=>$type));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY url_key_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function update($member_id,$survey_id,$u_isable){
			// キーのフラグを変更する
            $sql = 'UPDATE '.$this->table.' SET isable=\''.$u_isable.'\' WHERE member_id = \''.$member_id.'\' 
            	AND survey_id = \''.$survey_id.'\'';
            return self::$pdo->query($sql);
		}
		
		function update_type($member_id,$survey_id,$type,$u_isable){
			// キーのフラグを変更にする
            $sql = 'UPDATE '.$this->table.' SET isable=\''.$u_isable.'\' WHERE member_id = \''.$member_id.'\' 
            	AND survey_id = \''.$survey_id.'\' AND type = '.$type;
            return self::$pdo->query($sql);
		}
		
		function insert($key,$member_id,$survey_id,$type){
			$sql = 'INSERT INTO '.$this->table.'(`key`, `member_id`, `survey_id`, `type`) 
				VALUES (\''.$key.'\',\''.$member_id.'\',\''.$survey_id.'\','.$type.')';
			$result = self::$pdo->query($sql);
			
			return $result;
		}
		
		function delete($url_key_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE url_key_id = \''.$url_key_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>