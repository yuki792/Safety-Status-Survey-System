<?php
class administrator_account_setting extends MainFuncClass {
	protected $templateName = '04/account_setting/administrator_account_setting.tpl';
	
	public function execute(){
		session_start();
	
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
		
		// データベース接続
		$admin_dao = new AdministratorDao();
		
		// エラーメッセージ
		$error_message = '';
		
		// データベースから変更前のアカウント情報を取得
		$admin_array = $admin_dao->select($_SESSION['administrator_id']);
		if(count($admin_array)==1){
			$mail = $admin_array[0]['mail'];
			$mail_confirm = $admin_array[0]['mail'];
			$last_name = $admin_array[0]['last_name'];
			$first_name = $admin_array[0]['first_name'];
		}else{
			// 取得失敗
			$error_message .= 'アカウント情報の取得に失敗しました。<br />';
		}
		
		// ボタン押下を取得
		$confirm_button = (isset($_POST['confirm_button'])) ? $_POST['confirm_button'] : '';
		$send_button = (isset($_POST['send_button'])) ? $_POST['send_button'] : '';
		$back_button = (isset($_PSOT['back_button'])) ? $_POST['back_button'] : '';
		
		// 確認画面で戻るボタンが押された場合の処理
		if($back_button != ''){
			$confirm_button = '';
			$send_button = '';
		}
		
		// 入力値を取得
		$mail = (isset($_POST['mail'])) ? $_POST['mail'] : $mail;
		$mail_confirm = (isset($_POST['mail_confirm'])) ? $_POST['mail_confirm'] : $mail_confirm;
		$password = (isset($_POST['password'])) ? $_POST['password'] : '';
		$password_confirm = (isset($_POST['password_confirm'])) ? $_POST['password_confirm'] : '';
		$last_name = (isset($_POST['last_name'])) ? $_POST['last_name'] : $last_name;
		$first_name = (isset($_POST['first_name'])) ? $_POST['first_name'] : $first_name;
		
		//HTMLタグをエスケープ
		$mail = htmlspecialchars($mail);
		$mail_confirm = htmlspecialchars($mail_confirm);
		$password = htmlspecialchars($password);
		$password_confirm = htmlspecialchars($password_confirm);
		$last_name = htmlspecialchars($last_name);
		$first_name = htmlspecialchars($first_name);
		
		// 確認ボタンが押されたとき
		if($confirm_button != ''){
			
			if($mail == ''){   // メールアドレスが未入力
				$error_message .= 'メールアドレスを入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}elseif(!is_mail($mail)){   // メールアドレスが不正
				$error_message .= 'メールアドレスが不正です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($mail_confirm == ''){   // メールアドレス（確認）が未入力
				$error_message .= 'メールアドレス（確認）を入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}elseif(!is_mail($mail_confirm)){   // メールアドレス（確認）が不正
				$error_message .= 'メールアドレス（確認）が不正です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if(strcmp($mail, $mail_confirm) != 0){   // メールアドレスと（確認）が不一致
				$error_message .= 'メールアドレスとメールアドレス（確認）が一致しません。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if(strcmp($password, $password_confirm) != 0){   // パスワードと（確認）が不一致
				$error_message .= 'パスワードとパスワード（確認）が一致しません。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($last_name == ''){   // 名前（姓）が未入力
				$error_message .= '名前（姓）を入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($first_name == ''){   // 名前（名）が未入力
				$error_message .= '名前（名）を入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}
		}
		
		// 確認画面で登録ボタンが押されたときの処理
		if($send_button != ''){
			
			// パスワードをSHA-512関数でハッシュ化
			if($password != NULL){
				$hashed_password = hash('sha512', $password);
			}
			
			// データベースに登録
			$result = $admin_dao->update($_SESSION['administrator_id'],$password,$mail,$last_name,$first_name);
			
			if($result == 1){   // 登録成功
				$this->smarty->assign('message','登録情報変更が完了しました。');
			}else{   // 登録失敗
				$this->smarty->assign('message','登録情報変更に失敗しました。');
			}
			
			$this->setTemplateName('04/administrator_account_setting/administrator_account_setting_complete.tpl');
		}
		
		// 画面に表示する情報をセット
		$this->smarty->assign('error_message', $error_message);
		$this->smarty->assign('mail', $mail);
		$this->smarty->assign('mail_confirm', $mail_confirm);
		$this->smarty->assign('password', $password);
		$this->smarty->assign('password_confirm', $password_confirm);
		$this->smarty->assign('last_name', $last_name);
		$this->smarty->assign('first_name', $first_name);
		
		// ボタンを押されたときの処理
		if($send_button != ''){
		}elseif($confirm_button != ''){
			$this->setTemplateName('04/administrator_account_setting/administrator_account_setting_confirm.tpl');
		}else{
			$this->setTemplateName('04/administrator_account_setting/administrator_account_setting.tpl');
		}
	}
}
