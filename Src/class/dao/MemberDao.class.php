<?php
	//require_once('dao.php');
	//require_once('labo_dao.php');
	require_once('cryptfunc.php');

	class MemberDao extends Dao{
		private $table = 'member';
		
		function decryptdata($array_data,$labo_key){
		    
			// 復号
			if(count($array_data) >= 1)
			{
				$labo_dao = new LaboDao();
				$labo_data = $labo_dao->select($array_data[0]['labo_id']);
				
				if(count($labo_data) == 1 && ($labo_data[0]['labo_password'] == hash('sha512', $labo_key))){
					foreach($array_data as &$data){
						$data['phone_mail'] = decrypt($data['phone_mail'],$labo_key);
						$data['phone_number'] = decrypt($data['phone_number'],$labo_key);
						$data['other_mail1'] = decrypt($data['other_mail1'],$labo_key);
						$data['other_mail2'] = decrypt($data['other_mail2'],$labo_key);
						$data['other_mail3'] = decrypt($data['other_mail3'],$labo_key);
						$data['flink_mail1'] = decrypt($data['flink_mail1'],$labo_key);
						$data['flink_mail2'] = decrypt($data['flink_mail2'],$labo_key);
						$data['flink_mail3'] = decrypt($data['flink_mail3'],$labo_key);
						$data['flink_mail4'] = decrypt($data['flink_mail4'],$labo_key);
						$data['flink_mail5'] = decrypt($data['flink_mail5'],$labo_key);
						$data['twitter_id'] = decrypt($data['twitter_id'],$labo_key);
					} unset( $data );
				}else{
					$array_data = array();
				}
			}

			return $array_data;
		}
		
		function select($member_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE member_id = :member_id');
			$stmt->execute(array(':member_id'=>$member_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_key($member_id,$labo_key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE member_id = :member_id');
			$stmt->execute(array(':member_id'=>$member_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			
			$array_data = $this->decryptdata($array_data,$labo_key);
			
			return $array_data;
		}
		
		function select_acount($member_id,$password){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE member_id = :member_id AND password = :password');
			// パスワードをSHA-512関数でハッシュ�
			$hashed_password = hash('sha512', $password);
			$stmt->execute(array(':member_id'=>$member_id, ':password'=>$hashed_password));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_byCourse($course_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE course_id = :course_id');
			$stmt->execute(array(':course_id'=>$course_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_byCourse_key($course_id,$labo_key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE course_id = :course_id');
			$stmt->execute(array(':course_id'=>$course_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			
			$array_data = $this->decryptdata($array_data,$labo_key);
			
			return $array_data;
		}
		
		function select_byFaculty($faculty_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE faculty_id = :faculty_id');
			$stmt->execute(array(':faculty_id'=>$faculty_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function select_byFaculty_key($faculty_id,$labo_key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE faculty_id = :faculty_id');
			$stmt->execute(array(':faculty_id'=>$faculty_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			
			$array_data = $this->decryptdata($array_data,$labo_key);
			
			return $array_data;
		}
		
		function select_byCourseAndLabo($course_id,$labo_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE course_id = :course_id AND labo_id = :labo_id');
			$stmt->execute(array(':course_id'=>$course_id,':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}

		function select_byCourseAndLabo_key($course_id,$labo_id,$labo_key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE course_id = :course_id AND labo_id = :labo_id');
			$stmt->execute(array(':course_id'=>$course_id,':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			
			$array_data = $this->decryptdata($array_data,$labo_key);
			
			return $array_data;
		}
		
		function select_byFacultyAndLabo($faculty_id,$labo_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE faculty_id = :faculty_id AND labo_id = :labo_id');
			$stmt->execute(array(':faculty_id'=>$faculty_id,':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}

		function select_byFacultyAndLabo_key($faculty_id,$labo_id,$labo_key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE faculty_id = :faculty_id AND labo_id = :labo_id');
			$stmt->execute(array(':faculty_id'=>$faculty_id,':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			
			$array_data = $this->decryptdata($array_data,$labo_key);
			
			return $array_data;
		}
		
		function select_byLabo($labo_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE labo_id = :labo_id');
			$stmt->execute(array(':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}

		function select_byLabo_key($labo_id,$labo_key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE labo_id = :labo_id');
			$stmt->execute(array(':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			
			$array_data = $this->decryptdata($array_data,$labo_key);
			
			return $array_data;
		}
		
		function select_byMemberIdAndLabo($member_id,$labo_id){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE member_id = :member_id AND labo_id = :labo_id');
			$stmt->execute(array(':member_id'=>$member_id,':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}

		function select_byMemberIdAndLabo_key($member_id,$labo_id,$labo_key){
			$stmt = self::$pdo->prepare('SELECT * FROM '.$this->table.' WHERE member_id = :member_id AND labo_id = :labo_id');
			$stmt->execute(array(':member_id'=>$member_id,':labo_id'=>$labo_id));
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			
			$array_data = $this->decryptdata($array_data,$labo_key);
			
			return $array_data;
		}
		
		
		
		function select_all(){
			// �タベ�スから利用�一覧を取�
			$stmt = self::$pdo->query('SELECT * FROM '.$this->table.' ORDER BY course_id ASC');
			
			$array_data = array();
			
			while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
				array_push($array_data,$data);
			}
			return $array_data;
		}
		
		function update($member_id,$password,$last_name,$first_name, $phone_number, $phone_mail,$other_mail1,$other_mail2,$other_mail3,
			$flink_mail1,$flink_mail2,$flink_mail3,$flink_mail4,$flink_mail5,$twitter){
			// �タベ�スに登録
            $sql = 'UPDATE '.$this->table.' SET ';
            if($password != ''){
            	// パスワードをSHA-512関数でハッシュ�
				$hashed_password = hash('sha512', $password);
                $sql .= 'password=\''.$hashed_password.'\',';
            }
            
            if($phone_number !== ''){
                $sql .= 'phone_number=\''.$phone_number.'\',';
            }else{
                $sql .= 'phone_number=NULL,';
            }
                        
            if($other_mail1 !== ''){
                $sql .= 'other_mail1=\''.$other_mail1.'\',';
            }else{
                $sql .= 'other_mail1=NULL,';
            }
            
            if($other_mail2 !== ''){
                $sql .= 'other_mail2=\''.$other_mail2.'\',';
            }else{
                $sql .= 'other_mail2=NULL,';
            }
            
            if($other_mail3 !== ''){
                $sql .= 'other_mail3=\''.$other_mail3.'\',';
            }else{
                $sql .= 'other_mail3=NULL,';
            }
            
            if($flink_mail1 !== ''){
                $sql .= 'flink_mail1=\''.$flink_mail1.'\',';
            }else{
                $sql .= 'flink_mail1=NULL,';
            }
            
            if($flink_mail2 !== ''){
                $sql .= 'flink_mail2=\''.$flink_mail2.'\',';
            }else{
                $sql .= 'flink_mail2=NULL,';
            }
            
            if($flink_mail3 !== ''){
                $sql .= 'flink_mail3=\''.$flink_mail3.'\',';
            }else{
                $sql .= 'flink_mail3=NULL,';
            }
            
            if($flink_mail4 !== ''){
                $sql .= 'flink_mail4=\''.$flink_mail4.'\',';
            }else{
                $sql .= 'flink_mail4=NULL,';
            }
            
            if($flink_mail5 !== ''){
                $sql .= 'flink_mail5=\''.$flink_mail5.'\',';
            }else{
                $sql .= 'flink_mail5=NULL,';
            }
            
            if($twitter !== ''){
                $sql .= 'twitter_id=\''.$twitter.'\',';
            }else{
                $sql .= 'twitter_id=NULL,';
            }
            $sql .= 'last_name=\''.$last_name.'\',';
            $sql .= 'first_name=\''.$first_name.'\',';
            $sql .= 'phone_mail=\''.$phone_mail.'\' ';
            $sql .= 'WHERE member_id=\''.$member_id.'\'';
            
            return self::$pdo->query($sql);
		}

		function update_key($member_id,$password,$last_name,$first_name,$phone_number,$phone_mail,$other_mail1,$other_mail2,$other_mail3,
			$flink_mail1,$flink_mail2,$flink_mail3,$flink_mail4,$flink_mail5,$twitter,$labo_key){
			
			$array_data = $this->select($member_id);
			if(count($array_data)==1){
				$labo_dao = new LaboDao();
				$labo_data = $labo_dao->select($array_data[0]['labo_id']);
				
				if(count($labo_data) != 1 || ($labo_data[0]['labo_password'] != hash('sha512', $labo_key))){
					return 0;
				}
			}
				
			// �タベ�スに登録
            $sql = 'UPDATE '.$this->table.' SET ';
            if($password != ''){
            	// パスワードをSHA-512関数でハッシュ�
				$hashed_password = hash('sha512', $password);
                $sql .= 'password=\''.$hashed_password.'\',';
            }
            
            if($phone_number !== ''){
                $sql .= 'phone_number=\''.encrypt($phone_number, $labo_key).'\',';
            }else{
                $sql .= 'phone_number=NULL,';
            }
            
            if($other_mail1 !== ''){
                $sql .= 'other_mail1=\''.encrypt($other_mail1,$labo_key).'\',';
            }else{
                $sql .= 'other_mail1=NULL,';
            }
            
            if($other_mail2 !== ''){
                $sql .= 'other_mail2=\''.encrypt($other_mail2,$labo_key).'\',';
            }else{
                $sql .= 'other_mail2=NULL,';
            }
            
            if($other_mail3 !== ''){
                $sql .= 'other_mail3=\''.encrypt($other_mail3,$labo_key).'\',';
            }else{
                $sql .= 'other_mail3=NULL,';
            }
            
            if($flink_mail1 !== ''){
                $sql .= 'flink_mail1=\''.encrypt($flink_mail1,$labo_key).'\',';
            }else{
                $sql .= 'flink_mail1=NULL,';
            }
            
            if($flink_mail2 !== ''){
                $sql .= 'flink_mail2=\''.encrypt($flink_mail2,$labo_key).'\',';
            }else{
                $sql .= 'flink_mail2=NULL,';
            }
            
            if($flink_mail3 !== ''){
                $sql .= 'flink_mail3=\''.encrypt($flink_mail3,$labo_key).'\',';
            }else{
                $sql .= 'flink_mail3=NULL,';
            }
            
            if($flink_mail4 !== ''){
                $sql .= 'flink_mail4=\''.encrypt($flink_mail4,$labo_key).'\',';
            }else{
                $sql .= 'flink_mail4=NULL,';
            }
            
            if($flink_mail5 !== ''){
                $sql .= 'flink_mail5=\''.encrypt($flink_mail5,$labo_key).'\',';
            }else{
                $sql .= 'flink_mail5=NULL,';
            }
            
            if($twitter !== ''){
                $sql .= 'twitter_id=\''.encrypt($twitter,$labo_key).'\',';
            }else{
                $sql .= 'twitter_id=NULL,';
            }
            $sql .= 'last_name=\''.$last_name.'\',';
            $sql .= 'first_name=\''.$first_name.'\',';
            $sql .= 'phone_mail=\''.encrypt($phone_mail,$labo_key).'\' ';
            $sql .= 'WHERE member_id=\''.$member_id.'\'';
            
            return self::$pdo->query($sql);
		}

		function insert($member_id,$password,$last_name,$first_name,$phone_mail,$faculty_id,$course_id,$labo_id){
            // パスワードをSHA-512関数でハッシュ�
            $hashed_password = hash('sha512', $password);
            
			// �タベ�ス登録
			$sql = 'INSERT INTO '.$this->table.'(member_id, password, last_name, first_name, phone_mail, faculty_id, course_id, labo_id) 
				VALUES (:member_id,:password,:last_name,:first_name,:phone_mail,:faculty_id,:course_id,:labo_id)';
			$stmt = self::$pdo->prepare($sql);
			$result = $stmt->execute(array(':member_id'=>$member_id, ':password'=>$hashed_password, ':last_name'=>$last_name, 
				':first_name'=>$first_name, ':phone_mail'=>$phone_mail, ':faculty_id'=>$faculty_id, ':course_id'=>$course_id, ':labo_id'=>$labo_id));
			
			return $result;
		}
		
		function insert_key($member_id,$password,$last_name,$first_name,$phone_mail,$faculty_id,$course_id,$labo_id,$labo_key){
            // パスワードをSHA-512関数でハッシュ�
            $hashed_password = hash('sha512', $password);
            
			$array_data = $this->select($member_id);
			if(count($array_data)==1){
				$labo_dao = new LaboDao();
				$labo_data = $labo_dao->select($array_data[0]['labo_id']);
				
				if(count($labo_data) != 1 || ($labo_data[0]['labo_password'] != hash('sha512', $labo_key))){
					return 0;
				}
			}
			
			// �タベ�ス登録
			$sql = 'INSERT INTO '.$this->table.'(member_id, password, last_name, first_name, phone_mail, faculty_id, course_id, labo_id) 
				VALUES (:member_id,:password,:last_name,:first_name,:phone_mail,:faculty_id,:course_id,:labo_id)';
			$stmt = self::$pdo->prepare($sql);
			$result = $stmt->execute(array(':member_id'=>$member_id, ':password'=>$hashed_password, ':last_name'=>$last_name, 
				':first_name'=>$first_name, ':phone_mail'=>encrypt($phone_mail,$labo_key), ':faculty_id'=>$faculty_id, ':course_id'=>$course_id, ':labo_id'=>$labo_id));
			
			return $result;
		}
		
		function delete($member_id){
			$sql = 'DELETE FROM '.$this->table.' WHERE member_id = \''.$member_id.'\'';
			return self::$pdo->query($sql);
		}
	}
?>