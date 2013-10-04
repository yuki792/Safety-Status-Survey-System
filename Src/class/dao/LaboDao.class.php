<?php
	//require_once('dao.php');

	class LaboDao extends Dao{
		private $table = 'laboratory';
		
		function select($labo_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE labo_id = :labo_id');
			$stmt->execute(array(':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから研究室の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY labo_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function insert($labo_name,$labo_password){
			// データベースに登録
			$sql = 'INSERT INTO '.$this->table.'(labo_name, labo_password) 
				VALUES (:labo_name, :labo_password)';
			$stmt = self::$pdo->prepare($sql);
			
			$result = $stmt->execute(array(':labo_name'=>$labo_name, ':labo_password'=>$labo_password));
			
			return $result;
		}
		
		function delete($labo_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE labo_id = \''.$labo_id.'\'';
			return self::$pdo->query($sql);
		}
		
		function update( $labo_id, $labo_name, $labo_password ){
			$stmt = self::$pdo->prepare('UPDATE '.$this->table.' SET labo_name = :labo_name, labo_password = :labo_password WHERE labo_id = :labo_id');
			$result = $stmt->execute(array(':labo_name'=>$labo_name,'labo_password'=>$labo_password,':labo_id'=>$labo_id));
			
			return $result;
		}
	}
?>