<?php
class resend_survey extends MainFuncClass {
	protected $templateName = '04/safety_survey/resend_survey/resend_survey.tpl';
	
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
		
		// データベースから安否確認の一覧を取得
		$surveyDao = new SafetySurveyDao();
		// データベースから安否確認の一覧を取得
		$surveys = $surveyDao->select_ByAdministrator_id($_SESSION['administrator_id']);
		$title_list = '';
		foreach ( $surveys as $row ) {
			$title_list .= '<option value="'.$row['survey_id'].'">'.$row['title'].'</option>';
			$title_list .= "\n";
		}
		$this->smarty->assign('title_list', $title_list);
		
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
		$survey_id = (isset($_POST['title'])) ? $_POST['title'] : '';
		$target = (isset($_POST['target'])) ? $_POST['target'] : '';
		$destination = (isset($_POST['destination'])) ? implode(", ", (array)$_POST['destination']) : '';
		$comment = (isset($_POST['comment'])) ? $_POST['comment'] : '';
        $group_password = (isset($_POST['group_password'])) ? $_POST['group_password'] : '';
		
		//HTMLタグをエスケープ
		$survey_id = htmlspecialchars($survey_id);
		$target = htmlspecialchars($target);
		$destination = htmlspecialchars($destination);
		$comment = htmlspecialchars($comment);
        $group_password = htmlspecialchars($group_password);
		
		//survey_idからタイトルを取得
        $title = '';
		if (!empty($survey_id) && $survey_id != -1) {
		    $survey_array = $surveyDao->select($survey_id);
            $title = $survey_array[0]['title'];
		}
		
		// 確認ボタンが押された
		if($confirm_button != ''){
			
			if($survey_id == '-1'){   // タイトルが未選択
				$error_message .= 'タイトルが未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($target == '-1'){   // 送信対象メンバーが未選択
				$error_message .= '送信対象メンバーが未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($destination == ''){   // メール送信先が未選択
				$error_message .= 'メール送信先が未選択です。<br />';
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
			
			// データベースのsafety_surveyテーブルから送信対象メンバーを取得
			$sData = $surveyDao->select($survey_id);
            $tf = $sData[0]['target_faculty'];
            $tc = $sData[0]['target_course'];
            $tm = $sData[0]['target_member'];
			
			/* 
			*  メール送信先をintで扱うための値を計算する。
			*  携帯メール = 1, その他メール = 2, fリンクメール = 4とし、その和を登録する。
			*/ 
			$dst_array = explode(", ", $destination);
			$dst = 0;
			if(array_search('携帯メールアドレス', $dst_array) !== FALSE){
				$dst += 1;
			}
			if(array_search('その他メールアドレス', $dst_array) !== FALSE){
				$dst += 2;
			}
			if(array_search('fリンクメールアドレス', $dst_array) !== FALSE){
				$dst += 4;
			}
			
            // 管理者の研究室IDを取得
            $aDao = new AdministratorDao();
            $admin_data = $aDao->select($_SESSION['administrator_id']);
            $labo_id = $admin_data[0]['labo_id'];
            
			// メール送信
			$mDao = new MemberDao();
            $member_data = '';
			if($tm != NULL){
			    $member_data = $mDao->select_byMemberIdAndLabo_key($tm, $labo_id, $group_password);
			}elseif($tc != NULL){
			    $member_data = $mDao->select_byCourseAndLabo_key($tc, $labo_id, $group_password);
			}elseif($tf != NULL){
			    $member_data = $mDao->select_byFacultyAndLabo_key($tf, $labo_id, $group_password);
			}else{
			    $member_data = $mDao->select_byLabo_key($labo_id, $group_password);
			}
			
			// 送信するメールの情報
			$subject = '（再送信）4S 安否確認システム 安否確認メール';
			$flink_subject = '（お願い）4S 安否確認システム';
			
			// メール認証
			$smtp = auth_mail();
			
            $uDao = new UrlKeyDao();
            
			// URL付加　＆　対象者にメール送信
			foreach ($member_data as $data) {
                // 送信対象が「安否確認未回答者のみ」で、今メールを送ろうとしている利用者が安否回答済みならばコンティニュー
                if($target == '1'){
                    $statusDao = new SafetyStatusDao();
                    $status_data = $statusDao->select_bySurveyAndMember($survey_id, $data['member_id']);
                    if(!empty($status_data)){
                        continue;
                    }
                }

                $url_data = $uDao->select_bySurveyAndMemberAndType($survey_id, $data['member_id'], 1);
                if (!empty($url_data)) {
                    $body = 
$data['last_name'].$data['first_name']."さん

このメールは4S 安否確認システムから送信しています。

回答が行われていない安否確認があります。早急に回答してください。

コメント：".$comment."

4S 安否確認システムにアクセスして利用者ページから回答するか、
PCまたは携帯電話から以下のURLにアクセスして安否状況を回答してください。

PCからアクセスする場合はこちら
"._HTTP_HOST."index.php?func=reply_survey_k&key=".$url_data[0]['key'].

"

携帯電話からアクセスする場合はこちら
"._HTTP_HOST."index.php?func=reply_survey_k&key=".$url_data[0]['key'];
    
                    // 携帯メールに送信
                    if($dst == 1 || $dst == 3 || $dst == 5 || $dst == 7){
                        send_mail($smtp, $data['phone_mail'], $subject, $body);
                    }
                    
                    // その他メールに送信
                    if($dst == 2 || $dst == 3 || $dst == 6 || $dst == 7){
                        if($data['other_mail1']){
                            send_mail($smtp, $data['other_mail1'], $subject, $body);  
                        }
                        if($data['other_mail2']){
                            send_mail($smtp, $data['other_mail2'], $subject, $body);
                        }
                        if($data['other_mail3']){
                            send_mail($smtp, $data['other_mail3'], $subject, $body);
                        }
                    }
                }

                // URLに付加するキーを生成してデータベースに登録
                // すでにキーが発行済みならば再利用
                $u_data = $uDao->select_bySurveyAndMemberAndType($survey_id, $data['member_id'], 2);
                if (!empty($u_data)) {
                    $key = $u_data[0]['key'];
                } else {
                    $key = create_password(16);
                    $result = $uDao->insert($key, $data['member_id'], $survey_id, 2);
                    if (!$result) {
                        $this->smarty->assign('message','安否確認メール送信中にエラーが発生しました。処理を中断します。');
                        $this->setTemplateName( '04/safety_survey/resend_survey/resend_survey_complete.tpl' );
                        die();
                    }
                }
                
                $flink_body = "
突然のメールで失礼いたします。
このメールは4S 安否確認システムから".$data['last_name'].$data['first_name']."さんの友人・家族に向けて送信しています。

現在安否確認を行っていますが、未だに".$data['last_name'].$data['first_name']."さんとの連絡が取れておりません。

もし連絡可能なメールアドレスをご存じでしたら、以下のURLにアクセスしてメールアドレスの登録を行ってください。

疑問点などがございましたら、問い合わせフォームからお問い合わせください。

お手数をおかけしますが、ご協力の程よろしくお願いいたします。
";
                $flink_body .= _HTTP_HOST.'03/flink/flink.php?key='.$key.'&fmail=';
                
                // fリンクメールに送信（安否確認ではなく、利用者の連絡可能メールアドレスを教えてくれるように依頼するメール）
                if($dst == 4 || $dst == 5 || $dst == 6 || $dst == 7){
                    if($data['flink_mail1']){
                        send_mail($smtp, $data['flink_mail1'], $flink_subject, $flink_body.$data['flink_mail1']);
                        
                    }
                    if($data['flink_mail2']){
                        send_mail($smtp, $data['flink_mail2'], $flink_subject, $flink_body.$data['flink_mail2']);
                        
                    }
                    if($data['flink_mail3']){
                        send_mail($smtp, $data['flink_mail3'], $flink_subject, $flink_body.$data['flink_mail3']);
                        
                    }
                    if($data['flink_mail4']){
                        send_mail($smtp, $data['flink_mail4'], $flink_subject, $flink_body.$data['flink_mail4']);
                        
                    }
                    if($data['flink_mail5']){
                        send_mail($smtp, $data['flink_mail5'], $flink_subject, $flink_body.$data['flink_mail5']);
                        
                    }
                }
                
                // phpの設定でタイムアウトしてしまうのを防ぐためにタイマーをリセット
                set_time_limit(30);
			}

			$this->smarty->assign('message','安否確認メール送信が完了しました。');
			//$this->smarty->display('resend_survey_complete.tpl');
			$this->setTemplateName( '04/safety_survey/resend_survey/resend_survey_complete.tpl' );
		}
		
		// 画面に表示する情報をセット
		$this->smarty->assign('error_message', $error_message);
		if(isset($title)){
			$this->smarty->assign('title', $title);
		}
		if($target == 0){
			$target_name = '安否確認対象者全員';
		}elseif($target == 1){
			$target_name = '安否確認未回答者';
		}else{
			$target_name = '';
		}
		$this->smarty->assign('survey_id', $survey_id);
		$this->smarty->assign('target', $target);
		$this->smarty->assign('target_name', $target_name);
		$this->smarty->assign('destination', $destination);
		$this->smarty->assign('comment', $comment);
        $this->smarty->assign('group_password', $group_password);
		
		// ボタンを押されたときの処理
		if($send_button != ''){
		}elseif($confirm_button != ''){
			$this->setTemplateName( '04/safety_survey/resend_survey/resend_survey_confirm.tpl' );
		}else{
			$this->setTemplateName( '04/safety_survey/resend_survey/resend_survey.tpl' );
		}
	}
}
