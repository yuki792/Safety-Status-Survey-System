<?php
class inquiry extends MainFuncClass {
	//const TEMPLATE_NAME = '05/inquiry.tpl';
	protected $templateName = '05/inquiry.tpl';
	
	public function execute(){
		// エラーメッセージ
		$error_message = '';
		
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
		$name_kanji = (isset($_POST['name_kanji'])) ? $_POST['name_kanji'] : '';
		$name_kana = (isset($_POST['name_kana'])) ? $_POST['name_kana'] : '';
		$mail_addr = (isset($_POST['mail_addr'])) ? $_POST['mail_addr'] : '';
		$question = (isset($_POST['question'])) ? $_POST['question'] : '';
		
		//HTMLタグをエスケープ
		$name_kanji = htmlspecialchars($name_kanji);
		$name_kana = htmlspecialchars($name_kana);
		$mail_addr = htmlspecialchars($mail_addr);
		$question = htmlspecialchars($question);
		
		// 入力値をチェック
		if($confirm_button != '' || $send_button != ''){
			
			if($mail_addr == ''){   // メールアドレスが未入力
				$error_message .= 'メールアドレスを入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}elseif(!is_mail($mail_addr)){   // メールアドレスが不正
				$error_message .= 'メールアドレスが不正です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($question == ''){   // お問い合わせ内容が未入力
				$error_message .= 'お問い合わせ内容を入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}
		}
		
		// 確認画面で送信ボタンが押されたときの処理
		if($send_button != ''){
			
			// メール認証
			$smtp = auth_mail();
			
			// ここでメール送信処理を行う
			$subject = '4S 安否確認システム 問い合わせメール';
			$body = "
名前（漢字）：$name_kanji

なまえ（ふりがな）：$name_kana

メールアドレス：$mail_addr

問い合わせ内容：$question

";
			send_mail($smtp, '09t4041a@hcs.ibaraki.ac.jp', $subject, $body);
			
			//$this->smarty->display('05/complete.tpl');
			$this->setTemplateName('05/complete.tpl');
		}
		
		// 画面に表示する情報をセット
		$this->smarty->assign('error_message', $error_message);
		$this->smarty->assign('name_kanji', $name_kanji);
		$this->smarty->assign('name_kana', $name_kana);
		$this->smarty->assign('mail_addr', $mail_addr);
		$this->smarty->assign('question', $question);
		
		// ボタンを押されたときの処理
		if($send_button != ''){
		}elseif($confirm_button != ''){
			//$this->smarty->display('05/confirm.tpl');
			$this->setTemplateName('05/confirm.tpl');
		}else{
			//$this->smarty->display('05/inquiry.tpl');
			$this->setTemplateName('05/inquiry.tpl');
		}
	}
}
