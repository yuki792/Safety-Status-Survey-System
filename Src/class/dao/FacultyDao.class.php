<?php
	//require_once('dao.php');

	class FacultyDao extends Dao{
		private $table = 'faculty';
		
		function select($faculty_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE faculty_id = :faculty_id');
			$stmt->execute(array(':faculty_id'=>$faculty_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY faculty_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function insert($faculty_name){
			// データベースに登録
			$sql = 'INSERT INTO '.$this->table.'(faculty_name) 
				VALUES (:faculty_name)';
			$stmt = self::$pdo->prepare($sql);
			
			$result = $stmt->execute(array(':faculty_name'=>$faculty_name));
			
			return $result;
		}
		
		function delete($faculty_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE faculty_id = \''.$faculty_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>