<?php
    class account_setting extends MainFuncClass {
        protected $templateName = "03/account_setting/account_setting.tpl";
        
        public function execute() {
            session_start();
            
            // ログイン済みかどうかチェック
            if(!isset($_SESSION['member_id'])){
                
                // 未ログインならばログインページにジャンプ
                header('Location: '._HTTP_HOST.'?func=member_login');
                exit;
            }
            
            // エラーメッセージ
            $error_message = '';
            
            // データベースから変更前のアカウント情報を取得
            $mDao = new MemberDao();
            $member_data = $mDao->select_key($_SESSION['member_id'], $_SESSION['group_password']);
            $member_id = '';
            if (!empty($member_data)) {
                $member_id = $member_data[0]['member_id'];
                $last_name = $member_data[0]['last_name'];
                $first_name = $member_data[0]['first_name'];
                $phone_mail = $member_data[0]['phone_mail'];
                $phone_mail_confirm = $member_data[0]['phone_mail'];
                $phone_number = $member_data[0]['phone_number'];
                $other_mail1 = $member_data[0]['other_mail1'];
                $other_mail2 = $member_data[0]['other_mail2'];
                $other_mail3 = $member_data[0]['other_mail3'];
                $flink_mail1 = $member_data[0]['flink_mail1'];
                $flink_mail2 = $member_data[0]['flink_mail2'];
                $flink_mail3 = $member_data[0]['flink_mail3'];
                $flink_mail4 = $member_data[0]['flink_mail4'];
                $flink_mail5 = $member_data[0]['flink_mail5'];
                $twitter_id = $member_data[0]['twitter_id'];
            }else{
                // 取得失敗
                $error_message .= 'アカウント情報の取得に失敗しました。<br />';
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
            $last_name = (isset($_POST['last_name'])) ? $_POST['last_name'] : $last_name;
            $first_name = (isset($_POST['first_name'])) ? $_POST['first_name'] : $first_name;
            $password = (isset($_POST['password'])) ? $_POST['password'] : '';
            $password_confirm = (isset($_POST['password_confirm'])) ? $_POST['password_confirm'] : '';
            $phone_mail = (isset($_POST['phone_mail'])) ? $_POST['phone_mail'] : $phone_mail;
            $phone_mail_confirm = (isset($_POST['phone_mail_confirm'])) ? $_POST['phone_mail_confirm'] : $phone_mail_confirm;
            $phone_number = (isset($_POST['phone_number'])) ? $_POST['phone_number'] : $phone_number;
            $other_mail1 = (isset($_POST['other_mail1'])) ? $_POST['other_mail1'] : $other_mail1;
            $other_mail2 = (isset($_POST['other_mail2'])) ? $_POST['other_mail2'] : $other_mail2;
            $other_mail3 = (isset($_POST['other_mail3'])) ? $_POST['other_mail3'] : $other_mail3;
            $flink_mail1 = (isset($_POST['flink_mail1'])) ? $_POST['flink_mail1'] : $flink_mail1;
            $flink_mail2 = (isset($_POST['flink_mail2'])) ? $_POST['flink_mail2'] : $flink_mail2;
            $flink_mail3 = (isset($_POST['flink_mail3'])) ? $_POST['flink_mail3'] : $flink_mail3;
            $flink_mail4 = (isset($_POST['flink_mail4'])) ? $_POST['flink_mail4'] : $flink_mail4;
            $flink_mail5 = (isset($_POST['flink_mail5'])) ? $_POST['flink_mail5'] : $flink_mail5;
            $twitter_id = (isset($_POST['twitter_id'])) ? $_POST['twitter_id'] : $twitter_id;
            
            //HTMLタグをエスケープ
            $last_name = htmlspecialchars($last_name);
            $first_name = htmlspecialchars($first_name);
            $password = htmlspecialchars($password);
            $password_confirm = htmlspecialchars($password_confirm);
            $phone_mail = htmlspecialchars($phone_mail);
            $phone_mail_confirm = htmlspecialchars($phone_mail_confirm);
            $phone_number = htmlspecialchars($phone_number);
            $other_mail1 = htmlspecialchars($other_mail1);
            $other_mail2 = htmlspecialchars($other_mail2);
            $other_mail3 = htmlspecialchars($other_mail3);
            $flink_mail1 = htmlspecialchars($flink_mail1);
            $flink_mail2 = htmlspecialchars($flink_mail2);
            $flink_mail3 = htmlspecialchars($flink_mail3);
            $flink_mail4 = htmlspecialchars($flink_mail4);
            $flink_mail5 = htmlspecialchars($flink_mail5);
            $twitter_id = htmlspecialchars($twitter_id);
            
            // 確認ボタンが押されたとき
            if($confirm_button != ''){
                
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
                
                if(strcmp($password, $password_confirm) != 0){   // パスワードと（確認）が不一致
                    $error_message .= 'パスワードとパスワード（確認）が一致しません。<br />';
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
                
                if($other_mail1 != '' && !is_mail($other_mail1)){   // その他メールアドレスが入力されたが値が不正
                    $error_message .= 'その他メールアドレス１が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if($other_mail2 != '' && !is_mail($other_mail2)){   // その他メールアドレスが入力されたが値が不正
                    $error_message .= 'その他メールアドレス２が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if($other_mail3 != '' && !is_mail($other_mail3)){   // その他メールアドレスが入力されたが値が不正
                    $error_message .= 'その他メールアドレス３が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if($flink_mail1 != '' && !is_mail($flink_mail1)){   // fリンクメールアドレスが入力されたが値が不正
                    $error_message .= 'fリンクメールアドレス１が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if($flink_mail2 != '' && !is_mail($flink_mail2)){   // fリンクメールアドレスが入力されたが値が不正
                    $error_message .= 'fリンクメールアドレス２が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if($flink_mail3 != '' && !is_mail($flink_mail3)){   // fリンクメールアドレスが入力されたが値が不正
                    $error_message .= 'fリンクメールアドレス３が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if($flink_mail4 != '' && !is_mail($flink_mail4)){   // fリンクメールアドレスが入力されたが値が不正
                    $error_message .= 'fリンクメールアドレス４が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
                if($flink_mail5 != '' && !is_mail($flink_mail5)){   // fリンクメールアドレスが入力されたが値が不正
                    $error_message .= 'fリンクメールアドレス５が不正です。<br />';
                    $confirm_button = '';
                    $send_button = '';
                }
                
            }
            
            // 確認画面で登録ボタンが押されたときの処理
            if($send_button != ''){
                
                // パスワードをSHA-512関数でハッシュ化
                if($password != NULL){
                    $hashed_password = hash('sha512', $password);
                }
                $result = $mDao->update_key($member_id, $password, $last_name, $first_name, $phone_number, $phone_mail, $other_mail1, $other_mail2, $other_mail3, $flink_mail1, $flink_mail2, $flink_mail3, $flink_mail4, $flink_mail5, $twitter_id, $_SESSION['group_password']);
                
                unset($_SESSION['group_password']);
                if($result){   // 登録成功
                    $this->smarty->assign('message','登録情報変更が完了しました。');
                }else{   // 登録失敗
                    $this->smarty->assign('message','登録情報変更に失敗しました。');
                }
                
                $this->setTemplateName("03/account_setting/account_setting_complete.tpl");
            }
            
            // 画面に表示する情報をセット
            $this->smarty->assign('error_message', $error_message);
            $this->smarty->assign('last_name', $last_name);
            $this->smarty->assign('first_name', $first_name);
            $this->smarty->assign('password', $password);
            $this->smarty->assign('password_confirm', $password_confirm);
            $this->smarty->assign('phone_mail', $phone_mail);
            $this->smarty->assign('phone_mail_confirm', $phone_mail_confirm);
            $this->smarty->assign('phone_number', $phone_number);
            $this->smarty->assign('other_mail1', $other_mail1);
            $this->smarty->assign('other_mail2', $other_mail2);
            $this->smarty->assign('other_mail3', $other_mail3);
            $this->smarty->assign('flink_mail1', $flink_mail1);
            $this->smarty->assign('flink_mail2', $flink_mail2);
            $this->smarty->assign('flink_mail3', $flink_mail3);
            $this->smarty->assign('flink_mail4', $flink_mail4);
            $this->smarty->assign('flink_mail5', $flink_mail5);
            $this->smarty->assign('twitter_id', $twitter_id);
            
            // ボタンを押されたときの処理
            if($send_button != ''){
            }elseif($confirm_button != ''){
                $this->setTemplateName("03/account_setting/account_setting_confirm.tpl");
            }else{
                $this->setTemplateName("03/account_setting/account_setting.tpl");
            }
        }
    }
?>
