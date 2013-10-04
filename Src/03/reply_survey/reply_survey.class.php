<?php
    class reply_survey extends MainFuncClass {
        protected $templateName = "03/reply_survey/reply_survey.tpl";
        
        public function execute() {
            session_start();
			
            // データベース接続
            $url_dao = new UrlKeyDao();
			$survey_dao = new SafetySurveyDao();
			$status_dao = new SafetyStatusDao();
			$member_dao = new MemberDao();
            
            // ログイン済みかどうか、またはURLのキーを調べる
            if(isset($_GET['key'])){   // URL中のキーをデータベースと照合する
                $key = htmlspecialchars($_GET['key']);
				$url_array = $url_dao->select_byKey($key);
                if(count($url_array)==1){
                    if($url_array[0]['isable'] == 1 && $url_array[0]['type'] == 1){
                        $_SESSION['reply_id'] = $url_array[0]['member_id'];
                    }else{
                        $error_message = '安否回答者対象外あるいはすでに回答済みのため、安否回答はできません。';
                        $this->smarty->assign('error_message', $error_message);
                        $this->setTemplateName("03/reply_survey/reply_survey_error.tpl");
                        return;
                    }
                }
            }elseif(isset($_SESSION['reply_id'])){
                
            }elseif(isset($_SESSION['member_id'])){   // ログイン済み
                $_SESSION['reply_id'] = $_SESSION['member_id'];
            }else{   // 未ログインかつURLのキーも有効でない
            }
            
            // 正規アクセスかどうかチェック
            if(!isset($_SESSION['reply_id'])){
                // 不正なアクセスならばログインページにジャンプ
                // header('Location: '.$http_host.'03/member_login.php');
                header('Location: '._HTTP_HOST.'index.php?func=member_login');
                exit;
            }
            
            // エラーメッセージ
            $error_message = '';
            
            // すでに回答済み、または回答対象外の安否確認であればエラーを表示して終了
            
            // まず、データベースから自分が回答対象者かつ未回答である安否確認の一覧を取得
            $title_list = array();
			$survey_array = $survey_dao->select_all();
			$status_array = $status_dao->select_byMember($_SESSION['reply_id']);
			
            $tmp = array();
			foreach($status_array as $data){
				$tmp[] = $data['survey_id'];
			}
			
			foreach($survey_array as $data){
				if($data['target_member'] != NULL){
					$member_array = $member_dao->select($data['target_member']);
                }elseif($data['target_course'] != NULL){
                	$member_array = $member_dao->select_byCourse($data['target_course']);
                }elseif($data['target_faculty'] != NULL){
                	$member_array = $member_dao->select_byFaculty($data['target_faculty']);
                }else{
                	$member_array = $member_dao->select_all();
                }
                $tmp2 = array();
                foreach($member_array as $m_data){
                	$tmp2[] = $m_data['member_id'];
                }
				
                if(in_array($_SESSION['reply_id'], $tmp2) === TRUE){
                    if(in_array($data['survey_id'], $tmp) === FALSE){
                        $title_list[] = $data['survey_id'];
                    }
                }
			}
            // 次に、現在選択されている安否確認が上のリストに含まれているか調べる
            if(isset($_GET['survey_id'])){
                if(in_array($_GET['survey_id'], $title_list) === FALSE){
                    $error_message = '安否回答者対象外あるいはすでに回答済みのため、安否回答はできません。';
                    $this->smarty->assign('error_message', $error_message);
                    $this->setTemplateName("03/reply_survey/reply_survey_error");
                    return;
                }
            }elseif(isset($_POST['survey_id'])){
                if(in_array($_POST['survey_id'], $title_list) === FALSE){
                    $error_message = '安否回答者対象外あるいはすでに回答済みのため、安否回答はできません。';
                    $this->smarty->assign('error_message', $error_message);
                    $this->setTemplateName("03/reply_survey/reply_survey_error.tpl");
                    return;
                }
            }elseif(isset($_GET['key'])){
                $key = htmlspecialchars($_GET['key']);
				$url_array = $url_dao->select_byKey($_GET['key']);
                if(count($url_array)==1){
                    if(in_array($url_array[0]['survey_id'], $title_list) === FALSE){
                    $error_message = '安否回答者対象外あるいはすでに回答済みのため、安否回答はできません。';
                    $this->smarty->assign('error_message', $error_message);
                    $this->setTemplateName("03/reply_survey/reply_survey_error.tpl");
                    return;
                    }
                }
            }else{
                $error_message = 'エラーが発生しました。';
                $this->smarty->assign('error_message', $error_message);
                $this->setTemplateName("03/reply_survey/reply_survey_error.tpl");
                return;
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
            $safety_status = (isset($_POST['safety_status'])) ? $_POST['safety_status'] : '';
            $location = (isset($_POST['location'])) ? $_POST['location'] : '';
            $attend_school = (isset($_POST['attend_school'])) ? $_POST['attend_school'] : '';
            $comment = (isset($_POST['comment'])) ? $_POST['comment'] : '';
            if(isset($_GET['survey_id'])){
                $survey_id = $_GET['survey_id'];
            }elseif(isset($_POST['survey_id'])){
                $survey_id = $_POST['survey_id'];
            }elseif(isset($_GET['key'])){
                $key = htmlspecialchars($_GET['key']);
				$url_array = $url_dao->select_byKey($_GET['key']);
                if(count($url_array)==1){
                    $survey_id = $url_array[0]['survey_id'];
                }
            }else{
                $survey_id = '';
            }
            
            //HTMLタグをエスケープ
            $survey_id = htmlspecialchars($survey_id);
            $safety_status = htmlspecialchars($safety_status);
            $location = htmlspecialchars($location);
            $attend_school = htmlspecialchars($attend_school);
            $comment = htmlspecialchars($comment);
            
            // 確認ボタンが押された
            if($confirm_button != ''){
                
                switch($safety_status){
                case 1:
                    $status_name = '無事';
                    break;
                case 2:
                    $status_name = '軽傷';
                    break;
                case 3:
                    $status_name = '重傷';
                    break;
                default:
                    $status_name = '';
                    $error_message .= '現在の状態が未選択です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                switch($location){
                case 1:
                    $location_name = '自宅';
                    break;
                case 2:
                    $location_name = '友人・親類宅';
                    break;
                case 3:
                    $location_name = '避難所';
                    break;
                case 4:
                    $location_name = 'その他';
                    break;
                default:
                    $location_name = '';
                    $error_message .= '現在の居場所が未選択です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                switch($attend_school){
                case 1:
                    $school_name = '可能';
                    break;
                case 2:
                    $school_name = '不可能';
                    break;
                default:
                    $school_name = '';
                    $error_message .= '通学可否が未選択です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                $this->smarty->assign('status_name', $status_name);
                $this->smarty->assign('location_name', $location_name);
                $this->smarty->assign('school_name', $school_name);
            }
            
            // 確認画面で登録ボタンが押されたときの処理
            if($send_button != ''){
                
                // 安否状態をデータベースに登録
                //$pdo->query('START TRANSACTION');
                $result = $status_dao->insert($survey_id,$_SESSION['reply_id'],$safety_status,$location,$attend_school,$comment);
                
                // キーのフラグを0にする
                $result2 = $url_dao->update($_SESSION['reply_id'],$survey_id,0);
                
                if($result && $result2){   // 登録成功
                    //$pdo->query('COMMIT');
                    // セッション'reply_id'をアンセットする。
                    unset($_SESSION['reply_id']);
                    $this->smarty->assign('message','安否情報の登録が完了しました。');
                }else{   // 登録失敗
                    //$pdo->query('ROLLBACK');
                    $this->smarty->assign('message','安否情報の登録に失敗しました。');
                }
                $this->setTemplateName("03/reply_survey/reply_survey_complete.tpl");
            }
            
            
            // 画面に表示する情報をセット
            $this->smarty->assign('error_message', $error_message);
            $this->smarty->assign('survey_id', $survey_id);
            $this->smarty->assign('safety_status', $safety_status);
            $this->smarty->assign('location', $location);
            $this->smarty->assign('attend_school', $attend_school);
            $this->smarty->assign('comment', $comment);
            
            // ボタンを押されたときの処理
            if($send_button != ''){
            }elseif($confirm_button != ''){
                $this->setTemplateName("03/reply_survey/reply_survey_confirm.tpl");
            }else{
                $this->setTemplateName("03/reply_survey/reply_survey.tpl");
            }

        }
    }	
?>
