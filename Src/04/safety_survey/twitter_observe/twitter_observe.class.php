<?php
class twitter_observe extends MainFuncClass {
	protected $templateName = '04/safety_survey/twitter_observe/twitter_observe.tpl';
	
	public function execute(){
		session_start();
	
		// データベース接続
		$ssurveyDao = new SafetySurveyDao();
		$mDao = new MemberDao();
		$lDao = new LaboDao();
		$aDao = new AdministratorDao();
		$sstatusDao = new SafetyStatusDao();
		$toDao = new TwitterObserveDao();
		
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
		
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
		$survey_id = (isset($_POST['title'])) ? $_POST['title'] : '';
		$group_password = ( isset( $_POST['group_password'] ) ) ? $_POST['group_password'] : '';
		
		//HTMLタグをエスケープ
		$survey_id = htmlspecialchars($survey_id);
		
		//survey_idからタイトルを取得
		$survey = $ssurveyDao->select($survey_id);
		if ( !empty( $survey ) ){
			$survey = $survey[0];
			$title = isset($survey['title'])?$survey['title']:'';
		}
		
		// データベースから安否確認の一覧を取得
		$surveys = $ssurveyDao->select_ByAdministrator_id($_SESSION['administrator_id']);
		$title_list = '';
		foreach ( $surveys as $row ) {
			$title_list .= '<option value="'.$row['survey_id'].'">'.$row['title'].'</option>';
			$title_list .= "\n";
		}
		$this->smarty->assign('title_list', $title_list);

		$administrator = $aDao->select( $_SESSION['administrator_id'] );
		$administrator = $administrator[0];
		$labo = $lDao->select($administrator['labo_id']);
		$labo = $labo[0];
		
		// 確認ボタンが押された
		if($confirm_button != ''){
			
			if($survey_id == '-1'){   // タイトルが未選択
				$error_message .= 'タイトルが未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			if( hash( 'sha512', $group_password ) !== $labo['labo_password'] ) {
				$error_message .= 'グループパスワードが間違っています。<br />';
				$_SESSION['group_password'] = '';
				$confirm_button = '';
				$send_button = '';
				
			} else {
				$_SESSION['group_password'] = $group_password;
			}
			
			if ( $confirm_button == '' ){
				$this->smarty->assign('error_message', $error_message);
				$this->setTemplateName( '04/safety_survey/twitter_observe/twitter_observe.tpl' );
				return;
			}
			
			// 検索件数取得＆予想時間計算
			// データベースのsafety_surveyテーブルから検索対象メンバーを取得
			$temp = $ssurveyDao->select($survey_id);
			if( count( $temp ) == 1 ){
				$tf = $temp[0]['target_faculty'];
				$tc = $temp[0]['target_course'];
				$tm = $temp[0]['target_member'];
			}
			
			if($tm != NULL){
				$members = $mDao->select_key($tm, $_SESSION['group_password']);
			}elseif($tc != NULL){
				$members = $mDao->select_byCourse_key($tc, $_SESSION['group_password']);
			}elseif($tf != NULL){
				$members = $mDao->select_byFaculty_key($tf, $_SESSION['group_password']);
			}else{
				$members = $mDao->select_byLabo_key($labo['labo_id'], $_SESSION['group_password']);
			}
			
			$cnt = 0;
			foreach ( $members as $row ) {
				$result = $sstatusDao->select_bySurveyAndMember($survey_id, $row['member_id']);
				if(count( $result ) >= 1){
					continue;
				}else{
					if(isset($row['twitter_id']) AND !empty( $row['twitter_id'] )){
						$cnt++;
					}
				}
			}
			$quantity = $cnt;
			$time = $cnt * 1;
		}
		
		// 確認画面で登録ボタンが押されたときの処理
		if($send_button != ''){
			
			//survey_idから安否確認開始日を取得
			$temp = $ssurveyDao->select($survey_id);
			$register_date = $temp[0]['register_date'];
			$tf = $temp[0]['target_faculty'];
			$tc = $temp[0]['target_course'];
			$tm = $temp[0]['target_member'];
			
			// データベースのsafety_surveyテーブルから検索対象メンバーを取得
			if($tm != NULL){
				$members = $mDao->select_key($tm, $_SESSION['group_password']);
			}elseif($tc != NULL){
				$members = $mDao->select_byCourse_key($tc, $_SESSION['group_password']);
			}elseif($tf != NULL){
				$members = $mDao->select_byFaculty_key($tf, $_SESSION['group_password']);
			}else{
				$members = $mDao->select_byLabo_key($labo['labo_id'], $_SESSION['group_password']);
			}
			
			$mem_id = array();
			$twitter_id = array();
			foreach ( $members as $row ) {
				$result = $sstatusDao->select_bySurveyAndMember($survey_id, $row['member_id']);
				if(count( $result ) >= 1){
					continue;
				}else{
					if(isset($row['twitter_id']) AND !empty( $row['twitter_id'] )){
						$mem_id[] = $row['member_id'];
						$twitter_id[] = $row['twitter_id'];
					}
				}
			}
			
			$ta = new TwitterAnpi();
			
			// 災害用伝言板を1件ずつ検索し、ヒットしたらデータベースに登録する
			$cnt = 0;
			foreach($twitter_id as $key1 => $val1){
				if( $ta->CheckTwitterAnpi( $val1, $register_date ) ){
					try{
							$result = $toDao->insert($mem_id[$key1], $survey_id);
						if($result){   // 登録成功
							$cnt++;
						}
					} catch (Exception $e ) {
					}
				}
				// 1件検索するごとに3秒停止させる（負荷軽減のため）
				sleep(1);
				// phpの設定でタイムアウトしてしまうのを防ぐためにタイマーをリセット
				set_time_limit(30);
			}
			
			$_SESSION['group_password'] = '';
			$this->smarty->assign('message','伝言板検索が完了しました。<br />新たに'.$cnt.
			'件の安否情報を発見しました。<br />管理者ページの「安否確認状況閲覧」から参照できます。');
			$this->setTemplateName( '04/safety_survey/twitter_observe/twitter_observe_complete.tpl' );
		}
		
		// 画面に表示する情報をセット
		$this->smarty->assign('error_message', $error_message);
		if(isset($title)){
			$this->smarty->assign('title', $title);
		}
		$this->smarty->assign('survey_id', $survey_id);
		if(isset($quantity)){
			$this->smarty->assign('quantity', $quantity);
		}
		if(isset($time)){
			$this->smarty->assign('time', $time);
		}
		
		// ボタンを押されたときの処理
		if($send_button != ''){
		}elseif($confirm_button != ''){
			$this->setTemplateName( '04/safety_survey/twitter_observe/twitter_observe_confirm.tpl' );
		}else{
			$this->setTemplateName( '04/safety_survey/twitter_observe/twitter_observe.tpl' );
		}
	}
}
