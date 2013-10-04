<?php
class safety_survey extends MainFuncClass {
	protected $templateName = '04/safety_survey/safety_survey.tpl';
	
	public function execute(){
		session_start();
	
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
	}
}
