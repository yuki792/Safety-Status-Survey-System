<?php
class send_survey extends MainFuncClass {
	protected $templateName = '04/safety_survey/send_survey/send_survey.tpl';
	
	public function execute(){
		session_start();
		
		// DAO 作成
		$fDao = new FacultyDao();
		$cDao = new CourseDao();
		$mDao = new MemberDao();
		$ssurveyDao = new SafetySurveyDao();
		$aDao = new AdministratorDao();
		$ukDao = new urlKeyDao();
		$lDao = new LaboDao();
		
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
		
		// エラーメッセージ
		$error_message = '';
		
		// データベースから学部の一覧を取得
		$facultys = $fDao->select_all();
		$faculty_list = '';
		foreach ( $facultys as $faculty ) {
			$faculty_list .= '<option value="'.$faculty['faculty_id'].'">'.$faculty['faculty_name'].'</option>';
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
		$title = (isset($_POST['title'])) ? $_POST['title'] : '';
		$target_faculty = (isset($_POST['target_faculty'])) ? $_POST['target_faculty'] : '';
		$target_course = (isset($_POST['target_course'])) ? $_POST['target_course'] : '';
		$target_member = (isset($_POST['target_member'])) ? $_POST['target_member'] : '';
		$destination = (isset($_POST['destination'])) ? implode(", ", (array)$_POST['destination']) : '';
		$comment = (isset($_POST['comment'])) ? $_POST['comment'] : '';
		$group_password = ( isset($_POST['group_password']) ) ? $_POST['group_password'] : '';
		
		$administrator = $aDao->select( $_SESSION['administrator_id'] );
		if( $administrator ){
        	$administrator = $administrator[0];
        } else {
        	$error_message .= '管理者情報が壊れています。<br />';
			$this->smarty->assign('error_message', $error_message);
			return;
		}
		
		$labo = $lDao->select( $administrator['labo_id'] );
		$labo = $labo[0];
		
		
		if( $confirm_button == '' AND $send_button == '' ) { $_SESSION['group_password'] = ''; }
		
		
		
		//HTMLタグをエスケープ
		$title = htmlspecialchars($title);
		$target_faculty = htmlspecialchars($target_faculty);
		$target_course = htmlspecialchars($target_course);
		$target_member = htmlspecialchars($target_member);
		$destination = htmlspecialchars($destination);
		$comment = htmlspecialchars($comment);
		
		// 学部IDから学部名を取得
		if($target_faculty == '' || $target_faculty == -1){
			$faculty_name = '';
		}elseif($target_faculty == 0){
			$faculty_name = '全て';
		}else{
			$faculty = $fDao->select( $target_faculty );
			$faculty_name = $faculty[0]['faculty_name'];
		}
		// 学科IDから学科名を取得
		if($target_course == '' || $target_course == -1){
			$course_name = '';
		}elseif($target_course == 0){
			$course_name = '全て';
		}else{
			$course = $cDao->select( $target_course );
			$course_name = $course[0]['course_name'];
		}
		// 利用者IDから学生名を取得
		if($target_member == '' || $target_member == -1){
			$member_name = '';
		}elseif($target_member == 0){
			$member_name = '全て';
		}else{
			$member = $mDao->select( $target_member );
			$member_name = $target_member.' '.$member['last_name'].' '.$member['first_name'];
		}
		
		// 確認ボタンが押された
		if($confirm_button != ''){
			
			if($title == ''){   // タイトルが未入力
				$error_message .= 'タイトルを入力してください。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($target_faculty == '-1'){   // 送信対象メンバーが未選択
				$error_message .= '送信対象メンバー（学部）が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			if($target_course == '-1'){   // 送信対象メンバーが未選択
				$error_message .= '送信対象メンバー（学科）が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			if($target_member == '-1'){   // 送信対象メンバーが未選択
				$error_message .= '送信対象メンバー（学生）が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if($destination == ''){   // メール送信先が未選択
				$error_message .= 'メール送信先が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			if( hash( 'sha512', $group_password ) !== $labo['labo_password'] ) {
				$error_message .= 'グループパスワードが間違っています。<br />';
				//$this->smarty->assign('error_message', $error_message);
				$_SESSION['group_password'] = '';
				//return;
				$confirm_button = '';
				$send_button = '';
			} else {
				$_SESSION['group_password'] = $group_password;
			}
		}
		
		// 確認画面で登録ボタンが押されたときの処理
		if($send_button != ''){
			$admin_id = $_SESSION['administrator_id'];
			if($target_faculty == '0'){
				$tf = NULL;
			}else{
				$tf = $target_faculty;
			}
			if($target_course == '' || $target_course == '0'){
				$tc = NULL;
			}else{
				$tc = $target_course;
			}
			if($target_member == '' || $target_member == '0'){
				$tm = NULL;
			}else{
				$tm = $target_member;
			}
			
			/* 
			*  メール送信先をintで扱うための値を計算する。
			*  携帯メール = 1, その他メール = 2とし、その和を登録する。
			*/ 
			$dst_array = explode(", ", $destination);
			$dst = 0;
			if(array_search('携帯メールアドレス', $dst_array) !== FALSE){
				$dst += 1;
			}
			if(array_search('その他メールアドレス', $dst_array) !== FALSE){
				$dst += 2;
			}
			
			$result = $ssurveyDao->insert($title, $admin_id, $tf, $tc, $tm);
			
			if($result == 1){   // 登録成功
				// 挿入したsafety_surveyのsurvey_idを取得する
				$survey_id = $ssurveyDao->lastInsertId();
			}else{   // 登録失敗
				$this->smarty->assign('message','安否確認メール送信に失敗しました。');
				$this->setTemplateName( '04/safety_survey/send_survey/send_survey_complete.tpl' );
				die();
			}
			
            // 管理者の研究室IDを取得
            // 上で管理者情報を取得してる
            
			$labo_id = $administrator['labo_id'];
			
            
			// メール送信
			if($tm != NULL){
				$members = $mDao->select_byMemberIdAndLabo_key($tm, $labo_id,$_SESSION['group_password']);
			}elseif($tc != NULL){
				$members = $mDao->select_byCourseAndLabo_key($tc, $labo_id,$_SESSION['group_password']);
			}elseif($tf != NULL){
				$members = $mDao->select_byFacultyAndLabo_key($tf, $labo_id,$_SESSION['group_password']);
			}else{
				$members = $mDao->select_byLabo_key($labo_id,$_SESSION['group_password']);
			}
			
			// 送信するメールの情報
			$subject = '4S 安否確認システム 安否確認メール';
			
			// メール認証
			$smtp = auth_mail();
			
			// URL付加　＆　対象者にメール送信
			foreach ( $members as $member ) {
				// URLに付加するキーを生成してデータベースに登録
				$key = create_password(16);
				$result = $ukDao->insert($key, $member['member_id'], $survey_id, 1);
				if($result){
					
					$body = 
$member['last_name'].$member['first_name']."さん

このメールは4S 安否確認システムから送信しています。

現在、以下の安否確認が行われています。

タイトル：$title
送信対象：".$faculty_name.$course_name.$member_name."
コメント：
$comment

PCまたは携帯電話から以下のURLにアクセスして安否状況を回答してください。

PCからアクセスする場合はこちら
"._HTTP_HOST."index.php?func=reply_survey&key=".$key.

"

携帯電話からアクセスする場合はこちら
"._HTTP_HOST."index.php?func=reply_survey_k&key=".$key;
					
					// 携帯メールに送信
					if($dst == 1 || $dst == 3){
						send_mail($smtp, $member['phone_mail'], $subject, $body);
					}
					
					// その他メールに送信
					if($dst == 2 || $dst == 3){
						if($member['other_mail1']){
							send_mail($smtp, $member['other_mail1'], $subject, $body);
							
						}
						if($member['other_mail2']){
							send_mail($smtp, $member['other_mail2'], $subject, $body);
							
						}
						if($member['other_mail3']){
							send_mail($smtp, $member['other_mail3'], $subject, $body);
							
						}
					}
					
					// phpの設定でタイムアウトしてしまうのを防ぐためにタイマーをリセット
					set_time_limit(30);
				}else{
					$this->smarty->assign('message','安否確認メール送信中に異常が発生しました。強制終了します。');
					$this->setTemplateName( '04/safety_survey/send_survey/send_survey_complete.tpl' );
					die();
				}
			}
			
			$_SESSION['group_password'] = '';
			$this->smarty->assign('message','安否確認メール送信が完了しました。');
			$this->setTemplateName( '04/safety_survey/send_survey/send_survey_complete.tpl' );
			
		}
		
		// 画面に表示する情報をセット
		$this->smarty->assign('error_message', $error_message);
		$this->smarty->assign('title', $title);
		$this->smarty->assign('target_faculty', $target_faculty);
		$this->smarty->assign('target_course', $target_course);
		$this->smarty->assign('target_member', $target_member);
		$this->smarty->assign('faculty_name', $faculty_name);
		$this->smarty->assign('course_name', $course_name);
		$this->smarty->assign('member_name', $member_name);
		$this->smarty->assign('destination', $destination);
		$this->smarty->assign('comment', $comment);
		
		// ボタンを押されたときの処理
		if($send_button != ''){
		}elseif($confirm_button != ''){
			$this->setTemplateName( '04/safety_survey/send_survey/send_survey_confirm.tpl' );
		}else{
			$this->setTemplateName( '04/safety_survey/send_survey/send_survey.tpl' );
		}
	}
}
