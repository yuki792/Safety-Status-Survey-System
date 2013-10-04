<?php
	//require_once('dao.php');

	class SafetySurveyDao extends Dao{
		private $table = 'safety_survey';
		
		function select($survey_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE survey_id = :survey_id');
			$stmt->execute(array(':survey_id'=>$survey_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY survey_id ASC');

			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_ByAdministrator_id( $administrator_id ){
			$sql = 'SELECT `survey_id`, `title` FROM `safety_survey` WHERE `administrator_id`=\''.$administrator_id.'\' ORDER BY `survey_id` ASC';
			$stmt = self::$pdo->query($sql);
			
			$array_data = array();
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function insert($title,$admin_id,$tf,$tc,$tm){
			// データベース（safety_survey）に登録
			$sql = 'INSERT INTO '.$this->table.'(`title`, `administrator_id`, `target_faculty`, `target_course`, 
			`target_member`) VALUES (:title, :administrator_id, :target_faculty, :target_course, 
			:target_member)';
			$stmt = self::$pdo->prepare($sql);
			
			$result = $stmt->execute(array(':title'=>$title, ':administrator_id'=>$admin_id, 
				':target_faculty'=>$tf, ':target_course'=>$tc, ':target_member'=>$tm));
			
			return $result;
		}
		
		function delete($safety_survey_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE administrator_id = \''.$administrator_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>