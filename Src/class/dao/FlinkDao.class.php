<?php
	//require_once('dao.php');

	class FlinkDao extends Dao{
		private $table = 'flink';
		
		function select($flink_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE flink_id = :flink_id');
			$stmt->execute(array(':flink_id'=>$flink_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY flink_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function insert($member_id,$mail,$survey_id,$fmail){
			// メールアドレスをデータベースに登録    
            $sql = 'INSERT INTO '.$this->table.'(member_id, mail, survey_id, flink_mail) 
            	VALUES (\''.$member_id.'\',\''.$mail.'\',\''.$survey_id.'\',\''.$fmail.'\')';
            $result = self::$pdo->query($sql);
			
			return $result;
		}
		
		function delete($flink_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE flink_id = \''.$flink_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>