<?php
	//require_once('dao.php');

	class SafetyStatusDao extends Dao{
		private $table = 'safety_status';
		
		function select($safety_status_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE safety_status_id = :safety_status_id');
			$stmt->execute(array(':safety_status_id'=>$safety_status_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_byFlink($flink_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE flink_id = :flink_id');
			$stmt->execute(array(':flink_id'=>$flink_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_byMember($member_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE member_id = :member_id');
			$stmt->execute(array(':member_id'=>$member_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_bySurveyAndMember($survey_id,$member_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE survey_id = :survey_id AND member_id = :member_id');
			$stmt->execute(array(':survey_id'=>$survey_id,':member_id'=>$member_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY safety_status_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function insert($survey_id,$member_id,$safety_status,$location,$attend_school,$comment){
			if($comment == ''){
                $sql = 'INSERT INTO `safety_status`(`survey_id`, `member_id`, `safety_status`, `location`, `attend_school`) 
                VALUES (\''.$survey_id.'\',\''.$_SESSION['reply_id'].'\',\''.$safety_status.'\',\''.$location.'\',\''.$attend_school.'\')';
            }else{
                $sql = 'INSERT INTO `safety_status`(`survey_id`, `member_id`, `safety_status`, `location`, `attend_school`, `comment`) 
                VALUES (\''.$survey_id.'\',\''.$_SESSION['reply_id'].'\',\''.$safety_status.'\',\''.$location.'\',\''.$attend_school.'\',\''.$comment.'\')';
            }
            $result = self::$pdo->query($sql);
			
			return $result;
		}
		
		function delete($safety_status_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE safety_status_id = \''.$safety_status_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>