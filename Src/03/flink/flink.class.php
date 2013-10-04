<?php
    class flink extends MainFuncClass {
        protected $templateName = "03/flink/flink.tpl";
        
        public function execute() {
            session_start();
            
            // データベース接続
            $flink_dao = new FlinkDao();
            $url_dao = new UrlKeyDao();
			$survey_dao = new SafetySurveyDao();
			$status_dao = new SafetyStatusDao();
			$member_dao = new MemberDao();
            
            // URLのキーを調べる
            if(isset($_GET['key'])){   // URL中のキーをデータベースと照合する
            	$uel_array = $url_dao->select_byKey($_GET['key']);
                if(count($uel_array) == 1){
                    if($uel_array[0]['isable'] == 1 && $uel_array[0]['type'] == 2){
                        $_SESSION['flink_id'] = $uel_array[0]['member_id'];
                    }else{
                        $error_message = '対象者の安否は既に確認されました。ご協力ありがとうございました。';
                        $this->smarty->assign('error_message', $error_message);
                        $this->setTemplateName("03/flink/flink_error.tpl");
                        die();
                    }
                }
            }
            
            // 正規アクセスかどうかチェック
            if(!isset($_SESSION['flink_id'])){
                // 不正なアクセスならばエラーを表示して終了
                $error_message = 'エラーが発生しました。不正なアクセスです。';
                $this->smarty->assign('error_message', $error_message);
                $this->setTemplateName("03/flink/flink_error.tpl");
                return;
            }
            
            // エラーメッセージ
            $error_message = '';
            
            // すでに回答済み、または回答対象外の安否確認であればエラーを表示して終了
            
            // まず、データベースから自分が回答対象者かつ未回答である安否確認の一覧を取得
            $title_list = array();
			
			$survey_array = $survey_dao->select_all();
			
			$status_array = $status_dao->select_byMember($_SESSION['flink_id']);
			
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
				
                if(in_array($_SESSION['flink_id'], $tmp2) === TRUE){
                    if(in_array($data['survey_id'], $tmp) === FALSE){
                        $title_list[] = $data['survey_id'];
                    }
                }
			}
			
            // 次に、現在選択されている安否確認が上のリストに含まれているか調べる
            if(isset($_GET['key'])){
            	$url_array = $url_dao->select_byKey($_GET['key']);
                if(count($url_array)==1){
                    if(in_array($url_array[0]['survey_id'], $title_list) === FALSE){
                    $error_message = '対象者の安否は既に確認されました。ご協力ありがとうございました。';
                    $this->smarty->assign('error_message', $error_message);
                    $this->setTemplateName("03/flink/flink_error.tpl");
                    die();
                    }
                }
            }elseif(isset($_POST['survey_id'])){
                if(in_array($_POST['survey_id'], $title_list) === FALSE){
                    $error_message = '対象者の安否は既に確認されました。ご協力ありがとうございました。';
                    $this->smarty->assign('error_message', $error_message);
                    $this->setTemplateName("03/flink/flink_error.tpl");
                    die();
                }
            }else{
                $error_message = 'エラーが発生しました。';
                $this->smarty->assign('error_message', $error_message);
                $this->setTemplateName("03/flink/flink_error.tpl");
                die();
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
            $mail_addr = (isset($_POST['mail_addr'])) ? $_POST['mail_addr'] : '';
            $mail_addr_confirm = (isset($_POST['mail_addr_confirm'])) ? $_POST['mail_addr_confirm'] : '';
            $fmail = (isset($_POST['fmail'])) ? $_POST['fmail'] : $_GET['fmail'];
            if(isset($_GET['key'])){
            	$url_array = $url_dao->select_byKey($_GET['key']);
                if(count($url_array)==1){
                    $survey_id = $url_array[0]['survey_id'];
                }
            }elseif(isset($_POST['survey_id'])){
                $survey_id = $_POST['survey_id'];
            }else{
                $survey_id = '';
            }
            
            //HTMLタグをエスケープ
            $mail_addr = htmlspecialchars($mail_addr);
            $fmail = htmlspecialchars($fmail);
            $survey_id = htmlspecialchars($survey_id);
            
            // 確認ボタンが押された
            if($confirm_button != ''){
                
                if($mail_addr == ''){   // メールアドレスが未入力
                    $error_message .= 'メールアドレスを入力してください。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }elseif(!is_mail($mail_addr)){   // メールアドレスが不正
                    $error_message .= 'メールアドレスが不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if($mail_addr_confirm == ''){   // メールアドレス（確認）が未入力
                    $error_message .= 'メールアドレス（確認）を入力してください。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }elseif(!is_mail($mail_addr_confirm)){   // メールアドレス（確認）が不正
                    $error_message .= 'メールアドレス（確認）が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if(strcmp($mail_addr, $mail_addr_confirm) != 0){   // メールアドレスと（確認）が不一致
                    $error_message .= 'メールアドレスとメールアドレス（確認）が一致しません。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
            }
            
            // 確認画面で登録ボタンが押されたときの処理
            if($send_button != ''){
                
                //$pdo->query('START TRANSACTION');
                
                // メールアドレスをデータベースに登録  
                $result = $flink_dao->insert($_SESSION['flink_id'],$mail_addr,$survey_id,$fmail);
                
                // メールアドレスへ安否確認メール送信
                $subject = '4S 安否確認システム 安否確認メール';
                
                // メール認証
                $smtp = auth_mail();
                
				$member_array = $member_dao->select($_SESSION['flink_id']);
                if(count($member_array)==1){
                    $body = 
$member_array[0]['last_name'].$member_array[0]['first_name']."さん

このメールは4S 安否確認システムのfリンク機能を利用して送信しています。
もしあなたが".$member_array[0]['last_name'].$member_array[0]['first_name']."さんで無い場合は速やかにこのメールを破棄してください。
ご迷惑をおかけして申し訳ありません。

回答が行われていない安否確認があります。早急に回答してください。

PCから4S 安否確認システムにアクセスして利用者ページから安否状況を回答してください。
"._HTTP_HOST;
                    
                    send_mail($smtp, $mail_addr, $subject, $body);
                }
                // キーのフラグを0にする
                $result3 = $url_dao->update_type($_SESSION['flink_id'],$survey_id,2,0);
                
                if($result && count($member_array) && $result3){   // 登録成功
                    //$pdo->query('COMMIT');
                    // セッション'flink_id'をアンセットする。
                    unset($_SESSION['flink_id']);
                    $this->smarty->assign('message','入力されたメールアドレス宛に安否確認メールを送信しました。ご協力ありがとうございました。');
                }else{   // 登録失敗
                    //$pdo->query('ROLLBACK');
                    $this->smarty->assign('message','登録に失敗しました。');
                }
                $this->setTemplateName("03/flink/flink_complete.tpl");
            }
            
            // 画面に表示する情報をセット
            $this->smarty->assign('error_message', $error_message);
            $this->smarty->assign('survey_id', $survey_id);
            $this->smarty->assign('mail_addr', $mail_addr);
            $this->smarty->assign('fmail', $fmail);
            
            // ボタンを押されたときの処理
            if($send_button != ''){
            }elseif($confirm_button != ''){
                $this->setTemplateName("03/flink/flink_confirm.tpl");
            }else{
                $this->setTemplateName("03/flink/flink.tpl");
            }
        }
    }	
?>
