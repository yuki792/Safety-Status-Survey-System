<?php
	//require_once('dao.php');

	class CrossFinderDao extends Dao{
		private $table = 'cross_finder';
		
		function select($cross_finder_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE cross_finder_id = :cross_finder_id');
			$stmt->execute(array(':cross_finder_id'=>$cross_finder_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY cross_finder_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function insert($member_id,$survey_id){
			$sql = 'INSERT INTO '.$this->table.'(member_id, survey_id) VALUES (\''.$member_id.'\',\''.$survey_id.'\')';
			$result = self::$pdo->query($sql);
			
			return $result;
		}
		
		function delete($cross_finder_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE cross_finder_id = \''.$cross_finder_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>