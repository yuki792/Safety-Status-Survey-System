<?php
	//require_once('dao.php');
	//require_once('labo_dao.php');
	require_once('cryptfunc.php');

	class AdministratorDao extends Dao{
		private $table = 'administrator';
		
		function select($administrator_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE administrator_id = :administrator_id');
			$stmt->execute(array(':administrator_id'=>$administrator_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_key($administrator_id,$labo_key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE administrator_id = :administrator_id');
			$stmt->execute(array(':administrator_id'=>$administrator_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			
			//一つ見つかれば複合化
			if(count($array_data) == 1)
			{
				$labo_dao = new LaboDao();
				$labo_data = $labo_dao->select($array_data[0]['labo_id']);
				
				if(count($labo_data) == 1 && ($labo_data[0]['labo_password'] == hash('sha512', $labo_key))){
					$array_data[0]['mail'] = decrypt($array_data[0]['mail'],$labo_key);
				}else{
					$array_data = array();
				}
			}
			
			return $array_data;
		}
		
		function select_acount($administrator_id,$password){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE administrator_id = :administrator_id AND password = :password');
			// パスワードをSHA-512関数でハッシュ化
			$hashed_password = hash('sha512', $password);
			$stmt->execute(array(':administrator_id'=>$administrator_id, ':password'=>$password));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_all(){
			// データベースから管理者の一覧を取得
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY administrator_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function update($administrator_id,$password,$mail,$last_name,$first_name){
			$sql = 'UPDATE '.$this->table.' SET ';
			if($password != NULL){
				$sql .= 'password=:password,';
			}
			$sql .= 'mail=:mail,last_name=:last_name, first_name=:first_name WHERE administrator_id=:administrator_id';
			$stmt = self::$pdo->prepare($sql);
			
			if($password != NULL){
				// パスワードをSHA-512関数でハッシュ化
				$hashed_password = hash('sha512', $password);
				$result = $stmt->execute(array(':password'=>$hashed_password, ':mail'=>$mail, ':last_name'=>$last_name, 
				':first_name'=>$first_name, ':administrator_id'=>$administrator_id));
			}else{
				$result = $stmt->execute(array(':mail'=>$mail, ':last_name'=>$last_name, ':first_name'=>$first_name, 
				':administrator_id'=>$administrator_id));
			}
			
			return $result;
		}
		
		function update_key($administrator_id,$password,$mail,$last_name,$first_name,$labo_key){
			$array_data = select($administrator_id);
			if(count($array_data)==1){
				$labo_dao = new LaboDao();
				$labo_data = $labo_dao->select($array_data[0]['labo_id']);
				
				if(count($labo_data) == 1 && ($labo_data[0]['labo_password'] == hash('sha512', $labo_key))){
					$en_mail = encrypt($array_data[0]['mail'],$labo_key);
				}else{
					return 0;
				}
			}
			
			$sql = 'UPDATE '.$this->table.' SET ';
			if($password != NULL){
				$sql .= 'password=:password,';
			}
			$sql .= 'mail=:mail,last_name=:last_name, first_name=:first_name WHERE administrator_id=:administrator_id';
			$stmt = self::$pdo->prepare($sql);
			
			if($password != NULL){
				// パスワードをSHA-512関数でハッシュ化
				$hashed_password = hash('sha512', $password);
				$result = $stmt->execute(array(':password'=>$hashed_password, ':mail'=>$en_mail, ':last_name'=>$last_name, 
				':first_name'=>$first_name, ':administrator_id'=>$administrator_id));
			}else{
				$result = $stmt->execute(array(':mail'=>$en_mail, ':last_name'=>$last_name, ':first_name'=>$first_name, 
				':administrator_id'=>$administrator_id));
			}
			
			return $result;
		}
		
		function insert($administrator_id,$password,$authority_id,$mail,$last_name,$first_name){
			// データベースに登録
			$sql = 'INSERT INTO '.$this->table.'(administrator_id, password, authority_id, mail, last_name, first_name) 
				VALUES (:administrator_id, :password, :authority_id, :mail, :last_name, :first_name)';
			$stmt = self::$pdo->prepare($sql);
			
			$result = $stmt->execute(array(':administrator_id'=>$administrator_id, ':password'=>$hashed_password, ':authority_id'=>$authority_id, 
				':mail'=>$mail, ':last_name'=>$last_name, ':first_name'=>$first_name));
			
			return $result;
		}
		
		function insert_key($administrator_id,$password,$authority_id,$mail,$last_name,$first_name,$labo_key){
			
			$array_data = select($administrator_id);
			if(count($array_data)==1){
				$labo_dao = new LaboDao();
				$labo_data = $labo_dao->select($array_data[0]['labo_id']);
				
				if(count($labo_data) == 1 && ($labo_data[0]['labo_password'] == hash('sha512', $labo_key))){
					$en_mail = encrypt($array_data[0]['mail'],$labo_key);
				}else{
					return 0;
				}
			}
			
			// データベースに登録
			$sql = 'INSERT INTO '.$this->table.'(administrator_id, password, authority_id, mail, last_name, first_name) 
				VALUES (:administrator_id, :password, :authority_id, :mail, :last_name, :first_name)';
			$stmt = self::$pdo->prepare($sql);
			
			$result = $stmt->execute(array(':administrator_id'=>$administrator_id, ':password'=>$hashed_password, ':authority_id'=>$authority_id, 
				':mail'=>$en_mail, ':last_name'=>$last_name, ':first_name'=>$first_name));
			
			return $result;
		}
		
		function delete($administrator_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE administrator_id = \''.$administrator_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>