<?php
class survey_result extends MainFuncClass {
	protected $templateName = '04/survey_result/result_display.tpl';
	
	public function execute(){
		session_start();
		
		// データベース接続
		$pdo = db_connect(_DSN, _USER, _PASSWORD);
		//$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		// データベース接続
		$ssurveyDao = new SafetySurveyDao();
		$mDao = new MemberDao();
		$lDao = new LaboDao();
		$aDao = new AdministratorDao();
		$sstatusDao = new SafetyStatusDao();
		$toDao = new TwitterObserveDao();
		$csDao = new CrossFinderDao();
		
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
		
		// エラーメッセージ
		$error_message = '';
		
		// データベースから安否確認の一覧を取得
		$surveys = $ssurveyDao->select_ByAdministrator_id($_SESSION['administrator_id']);
		$title_list = '';
		foreach ( $surveys as $row ) {
			$title_list .= '<option value="'.$row['survey_id'].'">'.$row['title'].'</option>';
			$title_list .= "\n";
		}
		$this->smarty->assign('title_list', $title_list);
		
		// ボタン押下を取得
		//$send_button = (isset($_POST['send_button'])) ? $_POST['send_button'] : '';
		if ( isset( $_POST['send_button'] ) ){
			$send_button = $_POST['send_button'];
		} else if ( isset( $_GET['send_button'] ) ){
			$send_button = $_GET['send_button'] ;
		} else {
			$send_button = '';
		}
		
		// 入力値を取得
		//$survey_id = (isset($_POST['title'])) ? $_POST['title'] : '';
		if ( isset( $_POST['title'] ) ) {
			$survey_id = $_POST['title'];
		} else if ( isset( $_GET['title'] ) ) {
			$survey_id = $_GET['title'];
		} else {
			$survey_id = '';
		}
		
		//HTMLタグをエスケープ
		$survey_id = htmlspecialchars($survey_id);
		
		// 送信ボタンが押されたときの処理
		if($send_button != ''){
			
			if($survey_id == '-1'){   // タイトルが未選択
				$error_message .= 'タイトルが未選択です。<br />';
				$send_button = '';
				$this->smarty->assign('error_message', $error_message);
				//$this->smarty->display('result_select.tpl');
				$this->setTemplateName( '04/survey_result/result_select.tpl' );
				return;
			}
			
			// データベースから情報を取得して表示
			// データベースのsafety_surveyテーブルから検索対象メンバーを取得
			$tmp = '';
			$sql = 'SELECT `title`, `target_faculty`, `target_course`, `target_member` FROM `safety_survey` WHERE `survey_id` = \''.$survey_id.'\'';
			$stmt = $pdo->query($sql);
			if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$tf = $row['target_faculty'];
				$tc = $row['target_course'];
				$tm = $row['target_member'];
				$this->smarty->assign('title', $row['title']);
			}else{
				$this->smarty->assign('error_message', 'エラーが発生しました。');
				//$this->smarty->display('result_display.tpl');
				$this->setTemplateName( '04/survey_result/result_display.tpl' );
				return;
			}
			
			if($tm != NULL){
				$sql = 'SELECT `member_id`, `last_name`, `first_name` FROM `member` WHERE `member_id` = \''.$tm.'\'';
			}elseif($tc != NULL){
				$sql = 'SELECT `member_id`, `last_name`, `first_name` FROM `member` WHERE `course_id` = \''.$tc.'\'';
			}elseif($tf != NULL){
				$sql = 'SELECT `member_id`, `last_name`, `first_name` FROM `member` WHERE `faculty_id` = \''.$tf.'\'';
			}else{
				$sql = 'SELECT `member_id`, `last_name`, `first_name` FROM `member` WHERE 1';
			}
			$stmt = $pdo->query($sql);
			
			$anpi_cnt = array(0,0,0,0,0,0);
			$regi_cnt = array(0,0,0,0,0,0,0,0);
			$ref_date = strtotime(date("Y-m-d")." -6 day");
			$now_date = time() + 28800;   // 8時間分足す（なぜ8時間かは不明）
			
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$sql = 'SELECT `safety_status`, `register_date` FROM `safety_status` WHERE `survey_id` = \''.
				$survey_id.'\' AND `member_id` = \''.$row['member_id'].'\'';
				$stmt2 = $pdo->query($sql);
				if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
					$regi_date = strtotime($row2['register_date']);
					if($ref_date < $regi_date && $regi_date < $now_date){
						$regi_cnt[(int)(($regi_date - $ref_date) / 86400)]++;
					}
					switch($row2['safety_status']){
					case 1:
						$anpi = '無事';
						$anpi_cnt[1]++;
						break;
					case 2:
						$anpi = '軽傷';
						$anpi_cnt[2]++;
						break;
					case 3:
						$anpi = '重傷';
						$anpi_cnt[3]++;
						break;
					default:
						$anpi = '';
					}
					$detail_url = '<a href="index.php?func=result_detail&member_id='.$row['member_id'].'&survey_id='.$survey_id.'&cf=0">クリック</a>';
				}else{
					// Cross Finderに登録されていないか調べる
					$sql3 = 'SELECT* FROM `cross_finder` WHERE `member_id` = \''.$row['member_id'].'\' AND `survey_id` = \''.$survey_id.'\'';
					$stmt3 = $pdo->query($sql3);
					if($stmt3->fetch(PDO::FETCH_ASSOC)){
						$anpi = '無事(Cross Finder)';
						$anpi_cnt[4]++;
						$detail_url = '<a href="index.php?func=result_detail&member_id='.$row['member_id'].'&survey_id='.$survey_id.'&cf=1">クリック</a>';
					}else{
						// Twitterつぶやき監視に登録されていないか調べる
						$sql4 = 'SELECT * FROM `twitter_observe` WHERE `member_id` = \''.$row['member_id'].'\' AND `survey_id` = \''.$survey_id.'\'';
						$stmt4 = $pdo->query($sql4);
						if ( $stmt4->fetch(PDO::FETCH_ASSOC) ) {
							$anpi = '無事(Twitterつぶやき監視)';
							$anpi_cnt[5]++;
							$detail_url = '<a href="index.php?func=result_detail&member_id='.$row['member_id'].'&survey_id='.$survey_id.'&twitter=1">クリック</a>';
						} else {
							$anpi = '未回答';
							$anpi_cnt[0]++;
							$detail_url = '';
						}
					}
				}
				$tmp .= '<tr><td>'.$row['member_id'].'</td><td>'.$row['last_name'].'&nbsp'.$row['first_name'].
				'</td><td>'.$anpi.'</td><td>'.$detail_url.'</td></tr>';
			}
			
			
			$pie_chart = '<img src="http://chart.apis.google.com/chart?chs=620x200&chd=t:'.
			$anpi_cnt[0].','.$anpi_cnt[1].','.$anpi_cnt[2].','.$anpi_cnt[3].','.$anpi_cnt[4].','.$anpi_cnt[5].'&cht=p3&
			chl=未回答('.$anpi_cnt[0].')|無事('.$anpi_cnt[1].')|軽傷('.$anpi_cnt[2].')|重傷('.$anpi_cnt[3].')|無事(Cross Finder)('.$anpi_cnt[4].')|無事(Twitterつぶやき監視)('.$anpi_cnt[5].')&
			chtt=安否状態の割合&chco=0077ff">';
			
			$regi_max = ceil(max($regi_cnt)*1.1);
			
			$bar_chart = '<img src="http://chart.apis.google.com/chart?chs=400x300&chd=t:'.
			$regi_cnt[1].','.$regi_cnt[2].','.$regi_cnt[3].','.$regi_cnt[4].','.$regi_cnt[5].','.$regi_cnt[6].','.$regi_cnt[7].
			'&chxt=x,y&chxl=0:|6日前|5日前|4日前|3日前|2日前|1日前|今日|1:|0人|'.$regi_max.'人&cht=bvs
			&chtt=過去7日間の安否登録者数の推移&chco=0077ff&chbh=30,20,15&chm=N,000000,0,,10
			&chg=0,10,1,5&chds=0,'.$regi_max.'">';
			
			
			$this->smarty->assign('pie_chart', $pie_chart);
			$this->smarty->assign('bar_chart', $bar_chart);
			$this->smarty->assign('result', $tmp);
		}
		
		// 画面に表示する情報をセット
		$this->smarty->assign('error_message', $error_message);
		if(isset($title)){
			$this->smarty->assign('title', $title);
		}
		$this->smarty->assign('survey_id', $survey_id);
		
		// ボタンを押されたときの処理
		if($send_button != ''){
			//$this->smarty->display('result_display.tpl');
			$this->setTemplateName( '04/survey_result/result_display.tpl' );
		}else{
			//$this->smarty->display('result_select.tpl');
			$this->setTemplateName( '04/survey_result/result_select.tpl' );
		}
	}
}
