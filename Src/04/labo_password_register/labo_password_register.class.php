<?php
class labo_password_register extends MainFuncClass {
	protected $templateName = '04/labo_password_register/labo_password_register.tpl';
	
	public function execute(){
		session_start();
		
		// ログインしている
		if (  isset($_SESSION['administrator_id']) AND !empty( $_SESSION['administrator_id'] ) ) {
			$_SESSION['temp_administrator_id'] = '';
			$uri = _HTTP_HOST . 'index.php?func=administrator_page';
			header( 'Location:' . $uri );
			exit;
		}
		
		// 必要な情報がSESSION配列に存在しない
		if ( !isset( $_SESSION['temp_administrator_id'] ) OR empty( $_SESSION['temp_administrator_id'] ) ) {
			$_SESSION['temp_administrator_id'] = '';
			$uri = _HTTP_HOST . 'index.php?func=administrator_login';
			header( 'Location:' . $uri );
			exit;
		}
		
		// 登録ボタンが押された
		if ( isset( $_POST['submit_button'] ) ) {
			$pass1 = @$_POST['group_password1'];
			$pass2 = @$_POST['group_password2'];
			
			// パスワードと確認パースワードどちかが空
			if (  $pass1 === '' OR $pass2 === '' ) {
				$this->smarty->assign('error_message', 'グループパスワードとグループパスワード(確認)両方を入力してください。');
				return;
			}
			
			// パスワードと確認パスワードが一致しない。
			if (  $pass1 !== $pass2 ) {
				$this->smarty->assign('error_message', 'グループパスワードとグループパスワード(確認)は同じ文字列を入力してください。');
				return;
			}
			
			//////////////////////////////////////////////////////////////////////
			// 以下、登録成功処理
			
			$hashed_pass = hash( 'sha512',  $pass1 );
			
			// DAO作成
			$aDao = new AdministratorDao();
			$lDao = new LaboDao();
			
			// 必要な情報を修得
			$administrator = $aDao->select( $_SESSION['temp_administrator_id'] );
			$administrator = $administrator[0];
			$labo = $lDao->select( $administrator['labo_id'] );
			$labo = $labo[0];
			
			// 研究室パスワード登録
			$lDao->update($labo['labo_id'], $labo['labo_name'], $hashed_pass);
			
			// ログインしていることにする
			$_SESSION['administrator_id'] = $administrator['administrator_id'];
			
			$this->setTemplateName( '04/labo_password_register/labo_password_register_complete.tpl' );
			$_SESSION['temp_administrator_id'] = '';
		}
		
	}
}
