<?php
	//require_once('dao.php');

	class CourseDao extends Dao{
		private $table = 'course';
		
		function select($course_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE course_id = :course_id');
			$stmt->execute(array(':course_id'=>$course_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_byfaculty($faculty_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE faculty_id = :faculty_id ORDER BY course_id ASC');
			$stmt->execute(array(':faculty_id'=>$faculty_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY course_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function insert($faculty_id,$course_name){
			// データベースに登録
			$sql = 'INSERT INTO '.$this->table.'(faculty_id, course_name) 
				VALUES (:faculty_id, :course_name)';
			$stmt = self::$pdo->prepare($sql);
			
			$result = $stmt->execute(array(':faculty_id'=>$faculty_id, ':course_name'=>$course_name));
			
			return $result;
		}
		
		function delete($course_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE course_id = \''.$course_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>