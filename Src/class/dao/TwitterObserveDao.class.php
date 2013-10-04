<?php
	//require_once('dao.php');

	class TwitterObserveDao extends Dao{
		private $table = 'twitter_observe';
		
		function select($twitter_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE twitter_id = :twitter_id');
			$stmt->execute(array(':twitter_id'=>$twitter_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY twitter_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function insert($member_id,$survey_id){
			$sql = 'INSERT INTO `twitter_observe`(`member_id`,`survey_id`) VALUES (\''.$member_id.'\',\''.$survey_id.'\')';
					$result = self::$pdo->query($sql);
			
			return $result;
		}
		
		function delete($twitter_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE twitter_id = \''.$twitter_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>