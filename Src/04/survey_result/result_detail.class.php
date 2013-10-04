<?php
class result_detail extends MainFuncClass {
	protected $templateName = '04/survey_result/result_detail.tpl';
	
	public function execute(){
		session_start();
		
		// データベース接続
		$pdo = db_connect(_DSN, _USER, _PASSWORD);
		
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}	
		
		// URLからデータを取得
		if(isset($_GET['member_id'])){
			$member_id = htmlspecialchars($_GET['member_id']);
		}
		if(isset($_GET['survey_id'])){
			$survey_id = htmlspecialchars($_GET['survey_id']);
		}
		if(isset($_GET['cf'])){
			$cf = htmlspecialchars($_GET['cf']);
		}
		if(isset($_GET['twitter'])){
			$twitter = htmlspecialchars($_GET['twitter']);
		}
		
		
		// データベースから情報を取得して表示
		$sql = 'SELECT `last_name`, `first_name` FROM `member` WHERE `member_id` = \''.$member_id.'\'';
		$stmt = $pdo->query($sql);
		
		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			if((!isset($cf) OR $cf==0 ) && !isset($twitter)){
				$sql2 = 'SELECT `safety_status`, `location`, `attend_school`, `comment`, `register_date` FROM `safety_status` 
				WHERE `survey_id` = \''.$survey_id.'\' AND `member_id` = \''.$member_id.'\'';
				$stmt2 = $pdo->query($sql2);
				if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
					switch($row2['safety_status']){
					case 1:
						$status = '無事';
						break;
					case 2:
						$status = '軽傷';
						break;
					case 3:
						$status = '重傷';
						break;
					default:
						$status = '不明';
					}
					
					switch($row2['location']){
					case 1:
						$location = '自宅';
						break;
					case 2:
						$location = '友人・親類宅';
						break;
					case 3:
						$location = '避難所';
						break;
					case 4:
						$location = 'その他';
						break;
					default:
						$location = '不明';
					}
					
					switch($row2['attend_school']){
					case 1:
						$school = '可能';
						break;
					case 2:
						$school = '不可能';
						break;
					default:
						$school = '不明';
					}
					
					$this->smarty->assign('comment', $row2['comment']);
				}
			}elseif(isset($cf)){
				$sql2 = 'SELECT `register_date` FROM `cross_finder` WHERE `survey_id` = \''
				.$survey_id.'\' AND `member_id` = \''.$member_id.'\'';
				$stmt2 = $pdo->query($sql2);
				if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
					$status = '無事(Cross Finder)';
					$location = '';
					$school = '';
				}
			} else if (isset($twitter)) {
				$sql2 = 'SELECT `register_date` FROM `twitter_observe` WHERE `survey_id` = \''.$survey_id.'\' AND `member_id` = \''.$member_id.'\'';
				$stmt2 = $pdo->query($sql2);
				if ( $row2 = $stmt2->fetch(PDO::FETCH_ASSOC) ) {
					$status = '無事(Twitterつぶやき監視)';
					$location = '';
					$school = '';
				}
			} else {
			}
			//$back_url = 'survey_result.php?title='.$survey_id.'&send_button=安否確認状況表示';
			$back_url = 'index.php?func=survey_result&title='.$survey_id.'&send_button=安否確認状況表示';
			
			$this->smarty->assign('member_id', $member_id);
			$this->smarty->assign('last_name', $row['last_name']);
			$this->smarty->assign('first_name', $row['first_name']);
			$this->smarty->assign('status', $status);
			$this->smarty->assign('location', $location);
			$this->smarty->assign('school', $school);
			$this->smarty->assign('register_date', $row2['register_date']);
			$this->smarty->assign('back_url', $back_url);
		}

	}
}
