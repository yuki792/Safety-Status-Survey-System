<?php
    class reply_survey_select extends MainFuncClass {
        protected $templateName = "03/reply_survey/reply_survey_select.tpl";
        
        public function execute() {
            session_start();
            
            // データベース接続
            $survey_dao = new SafetySurveyDao();
			$status_dao = new SafetyStatusDao();
			$member_dao = new MemberDao();
            
            // ログイン済みかどうかチェック
            if(!isset($_SESSION['member_id'])){
                
                // 未ログインならばログインページにジャンプ
                header('Location: '._HTTP_HOST.'?func=member_login');
                exit;
            }
            
            // エラーメッセージ
            $error_message = '';
            
            // データベースから自分が回答対象者かつ未回答である安否確認の一覧を取得
            $title_list = '';
			$survey_array = $survey_dao->select_all();
			$status_array = $status_dao->select_byMember($_SESSION['member_id']);
			
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
                if(in_array($_SESSION['member_id'], $tmp2) === TRUE){
                    if(in_array($data['survey_id'], $tmp) === FALSE){
                        $title_list .= '<option value="'.$data['survey_id'].'">'.$data['title'].'</option>';
                        $title_list .= "\n";
                    }
                }
			}
            
            // ボタン押下を取得
            $next_button = (isset($_POST['next_button'])) ? $_POST['next_button'] : '';
            
            // 入力値を取得
            $survey_id = (isset($_POST['title'])) ? $_POST['title'] : '';
            
            // 次へボタンが押された
            if($next_button != ''){
                if($safety_status == '-1'){   // タイトルが未選択
                    $error_message .= 'タイトルが未選択です。<br />';
                    $next_button = '';
                }else{
                    // タイトルが選択されていれば、GETで情報を送信しつつ、回答画面にジャンプ
                    //header('Location: '.$http_host.'03/reply_survey/reply_survey.php?survey_id='.$survey_id);
					header('Location: '._HTTP_HOST.'?func=reply_survey&survey_id='.$survey_id);
                    exit;
                }
            }
            
            $this->smarty->assign('title_list', $title_list);        
        }
    }	
?>
