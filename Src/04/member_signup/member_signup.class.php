<?php
class member_signup extends MainFuncClass {
	protected $templateName = '04/member_signup/member_signup.tpl';
	
	public function execute(){
		session_start();
		
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
		
		// エラーメッセージ
		$error_message = '';
		
		// データベースから学部の一覧を取得
		$fDao = new FacultyDao();
        $array_data = $fDao->select_all();
		$faculty_list = '';
		foreach($array_data as $data){
			$faculty_list .= '<option value="'.$data['faculty_id'].'">'.$data['faculty_name'].'</option>';
			$faculty_list .= "\n";
		}
		$this->smarty->assign('faculty_list', $faculty_list);
		
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
		$member_id = (isset($_POST['member_id'])) ? $_POST['member_id'] : '';
		$last_name = (isset($_POST['last_name'])) ? $_POST['last_name'] : '';
		$first_name = (isset($_POST['first_name'])) ? $_POST['first_name'] : '';
		$faculty_id = (isset($_POST['faculty_id'])) ? $_POST['faculty_id'] : '';
		$faculty_name = (isset($_POST['faculty_name'])) ? $_POST['faculty_name'] : '';
		$course_id = (isset($_POST['course_id'])) ? $_POST['course_id'] : '';
		$course_name = (isset($_POST['course_name'])) ? $_POST['course_name'] : '';
		$phone_mail = (isset($_POST['phone_mail'])) ? $_POST['phone_mail'] : '';
		$phone_mail_confirm = (isset($_POST['phone_mail_confirm'])) ? $_POST['phone_mail_confirm'] : '';
		$group_password = (isset($_POST['group_password'])) ? $_POST['group_password'] : '';
        
		//HTMLタグをエスケープ
		$member_id = htmlspecialchars($member_id);
		$last_name = htmlspecialchars($last_name);
		$first_name = htmlspecialchars($first_name);
		$faculty_id = htmlspecialchars($faculty_id);
		$faculty_name = htmlspecialchars($faculty_name);
		$course_id = htmlspecialchars($course_id);
		$course_name = htmlspecialchars($course_name);
		$phone_mail = htmlspecialchars($phone_mail);
		$phone_mail_confirm = htmlspecialchars($phone_mail_confirm);
        $group_password = htmlspecialchars($group_password);
		
		// 学部IDから学部名を取得　＆　学科IDから学科名を取得
        if (!empty($faculty_id) && $faculty_id != -1) {
		    $faculty_data = $fDao->select($faculty_id);
		    $faculty_name = $faculty_data[0]['faculty_name'];
		}
		
        if (!empty($course_id) && $course_id != -1) {
            $cDao = new CourseDao();
            $course_data = $cDao->select($course_id);
            $course_name = $course_data[0]['course_name'];
        }
		
		// 確認ボタンが押されたとき
		if($confirm_button != ''){
			if($member_id == ''){   // 利用者ID（学籍番号）が未入力
				$error_message .= '利用者ID（学籍番号）を入力してください。<br />';
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
			
			if($faculty_id == '-1'){
				$error_message .= '学部が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($course_id == '-1' || $course_id == ''){
				$error_message .= '学科が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($phone_mail == ''){   // 携帯メールアドレスが未入力
				$error_message .= '携帯メールアドレスを入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}elseif(!is_mail($phone_mail)){   // 携帯メールアドレスが不正
				$error_message .= '携帯メールアドレスが不正です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($phone_mail_confirm == ''){   // 携帯メールアドレス（確認）が未入力
				$error_message .= '携帯メールアドレス（確認）を入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}elseif(!is_mail($phone_mail_confirm)){   // 携帯メールアドレス（確認）が不正
				$error_message .= '携帯メールアドレス（確認）が不正です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if(strcmp($phone_mail, $phone_mail_confirm) != 0){   // 携帯メールアドレスと（確認）が不一致
				$error_message .= '携帯メールアドレスと携帯メールアドレス（確認）が一致しません。<br />';
				$confirm_button = '';
				$send_button = '';
			}
            
            if($group_password == ''){   // グループパスワードが未入力
                $error_message .= 'グループパスワードを入力してください。<br />';
                $confirm_button = '';
                $send_button = '';
            }
            
            // 管理者の研究室IDを取得
            $aDao = new AdministratorDao();
            $admin_data = $aDao->select($_SESSION['administrator_id']);
            $labo_id = $admin_data[0]['labo_id'];
            // データベースからこの研究室のグループパスワード（ハッシュ値）を取得
            $lDao = new LaboDao();
            $labo_data = $lDao->select($labo_id);
            $labo_password = $labo_data[0]['labo_password'];
            // 入力されたグループパスワードをSHA-512関数でハッシュ化
            $hashed_password = hash('sha512', $group_password);
            if(strcmp($labo_password, $hashed_password) != 0) { // 入力されたグループパスワードが正しくない
                $error_message .= 'グループパスワードが不正です。<br />';
                $confirm_button = '';
                $send_button = '';
            }
		}
		
		// 確認画面で登録ボタンが押されたときの処理
		if($send_button != ''){
			
			// 8桁の仮パスワードを自動生成
			$tmp_password = create_password(8);
			
            // 管理者の研究室IDを取得
            $aDao = new AdministratorDao();
            $admin_data = $aDao->select($_SESSION['administrator_id']);
            $labo_id = $admin_data[0]['labo_id'];
            
			// データベース登録
			$mDao = new MemberDao();
            $result = $mDao->insert_key($member_id, $tmp_password, $last_name, $first_name, $phone_mail, $faculty_id, $course_id, $labo_id, $group_password);
			if($result == 1){   // 登録成功
				$this->smarty->assign('message','新規管理者登録が完了しました。');
			}else{   // 登録失敗
				$this->smarty->assign('message','新規管理者登録が失敗しました。');
				$this->setTemplateName( '04/member_signup/member_signup_complete.tpl' );
				return;
			}
			
			// メール認証
			$smtp = auth_mail();
			
			// メール送信 
			$subject = '4S 安否確認システム 新規利用者登録完了通知';
			$body = $last_name.$first_name."さん
システム管理者による安否確認システムへの利用者登録が完了しました。

ログインに必要な利用者IDと仮パスワードは以下の通りです。

利用者ID: $member_id
仮パスワード: $tmp_password

PCから以下のURLにアクセスしてパスワードの変更及びその他情報の登録を行ってください。
"._HTTP_HOST;
			send_mail($smtp, $phone_mail, $subject, $body);
			
			$this->setTemplateName( '04/member_signup/member_signup_complete.tpl' );
		}
		
		// 画面に表示する情報をセット
		$this->smarty->assign('error_message', $error_message);
		$this->smarty->assign('member_id', $member_id);
		$this->smarty->assign('last_name', $last_name);
		$this->smarty->assign('first_name', $first_name);
		$this->smarty->assign('faculty_id', $faculty_id);
		$this->smarty->assign('faculty_name', $faculty_name);
		$this->smarty->assign('course_id', $course_id);
		$this->smarty->assign('course_name', $course_name);
		$this->smarty->assign('phone_mail', $phone_mail);
		$this->smarty->assign('phone_mail_confirm', $phone_mail_confirm);
		$this->smarty->assign('group_password', $group_password);
        
		// ボタンを押されたときの処理
		if($send_button != ''){
		}elseif($confirm_button != ''){
			$this->setTemplateName( '04/member_signup/member_signup_confirm.tpl' );
		}else{
			$this->setTemplateName( '04/member_signup/member_signup.tpl' );
		}
	}
}
