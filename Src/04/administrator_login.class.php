<?php
class administrator_login extends MainFuncClass {
	protected $templateName = '04/administrator_login.tpl';
	
	public function execute(){
		session_start();
		$_SESSION['temp_administrator_id'] = '';
		
		// データベース接続
		$aDao = new AdministratorDao();
		$lDao = new LaboDao();
		
		if(isset($_POST['login'])){     // ログインボタンが押された
			if(ctype_alnum($_POST['administrator_id']) && ctype_alnum($_POST['password'])){     // ユーザIDとパスワードの入力値チェック
				
				// パスワードをSHA-512関数でハッシュ化
				$hashed_password = hash('sha512', $_POST['password']);
				
				$administrator = $aDao->select_acount($_POST['administrator_id'], $hashed_password);
				if( count( $administrator ) == 1 ){     // データベースに2件以上ヒットしないか
					$administrator = $administrator[0];
					$labo = $lDao->select( $administrator['labo_id'] );
					$labo = $labo[0];
					if ( empty( $labo['labo_password'] ) ) {
						$_SESSION['temp_administrator_id'] = $administrator['administrator_id'];
						$uri = _HTTP_HOST . 'index.php?func=labo_password_register';
						header( 'Location:' . $uri );
						exit;
					}
					
					// ログインに成功したことをセッションに保存
					$_SESSION['administrator_id'] = $administrator['administrator_id'];
					
					// ログイン成功画面へリダイレクト
					$success_url = _HTTP_HOST.'index.php?func=administrator_page';
					header('Location: '.$success_url);
					exit;
					
				}else{
					$this->smarty->assign('error_message', '<br />データベースに異常があります。<br />管理者に問い合わせてください。<br /><br />');
				}
			}
			$this->smarty->assign('error_message', '<br />IDまたはパスワードが間違っています。<br /><br />');
		}
		
		// データベース切断
		$pdo = null;
	}
}
