<?php
class administrator_page extends MainFuncClass {
	protected $templateName = '04/administrator_page.tpl';
	
	public function execute(){
		session_start();
	
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
		
		// データベース接続
		$pdo = db_connect(_DSN, _USER, _PASSWORD);
		
		// 利用者の名前（姓）を取得
		$sql = 'SELECT `last_name`, `first_name` FROM `administrator` WHERE administrator_id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($_SESSION['administrator_id']));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$last_name = $result['last_name'];
		$first_name = $result['first_name'];
		
		// 「ようこそ○○さん」を表示
		$this->smarty->assign('welcome_message','ようこそ '.$last_name.$first_name.' さん');
		
		// データベース切断
		$pdo = null;

	}
}
