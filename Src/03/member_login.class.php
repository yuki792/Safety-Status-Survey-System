<?php
    class member_login extends MainFuncClass {
        protected $templateName = "03/member_login.tpl";
        
        public function execute() {
        	session_start();
        	
        	// データベース接続
        	$pdo = db_connect(_DSN, _USER, _PASSWORD);
        	
        	if(isset($_POST['login'])){     // ログインボタンが押された
        		if(ctype_alnum($_POST['member_id']) && ctype_alnum($_POST['password'])){     // ユーザIDとパスワードの入力値チェック
        			
        			// パスワードをSHA-512関数でハッシュ化
        			$hashed_password = hash('sha512', $_POST['password']);
        			
        			// IDとパスワードをデータベースに問い合わせるSQL文を生成
        			$sql = 'SELECT * FROM member WHERE member_id = :member_id AND password = :password';
        			$stmt = $pdo->prepare($sql);
        			$stmt->execute(array(':member_id'=>$_POST['member_id'], ':password'=>$hashed_password));
        			
        			if($row = $stmt->fetch(PDO::FETCH_ASSOC)){     // データベースにヒットするか					
        				if(!$stmt->fetch(PDO::FETCH_ASSOC)){     // データベースに2件以上ヒットしないか
        				
        					// ログインに成功したことをセッションに保存
        					$_SESSION['member_id'] = $row['member_id'];
        					
        					// ログイン成功画面へリダイレクト
        					$success_url = _HTTP_HOST.'?func=member_page';
        					header('Location: '.$success_url);
        					exit;
        					
        				}else{
        					$this->smarty->assign('error_message', '<br />データベースに異常があります。<br />管理者に問い合わせてください。<br /><br />');
        				}
        			}
        		}
        		$this->smarty->assign('error_message', '<br />IDまたはパスワードが間違っています。<br /><br />');
        	}
        	
        	// データベース切断
        	$pdo = null;
        }
    }
?>
