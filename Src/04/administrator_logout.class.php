<?php
class administrator_logout extends MainFuncClass {
	protected $templateName = '04/administrator_logout.tpl';
	
	public function execute(){
		session_start();
	
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
		
		// セッション変数のクリア
		$_SESSION = array();
		
		// クッキーの削除
		if(isset($_COOKIE['PHPSESSID'])){
			setcookie('PHPSESSID', '', time() - 42000, '/');
		}
		
		// セッションのクリア
		session_destroy();
		
	}
}
