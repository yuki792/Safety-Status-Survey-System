<?php
    class account_setting_start extends MainFuncClass {
        protected $templateName = "03/account_setting/account_setting_start.tpl";
        
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
            
            if(isset($_POST['next'])){     // 次へボタンが押された
                // 自分の研究室IDを取得
                $mDao = new MemberDao();
                $member_data = $mDao->select($_SESSION['member_id']);
                $labo_id = $member_data[0]['labo_id'];
                // データベースからこの研究室のグループパスワード（ハッシュ値）を取得
                $lDao = new LaboDao();
                $labo_data = $lDao->select($labo_id);
                $labo_password = $labo_data[0]['labo_password'];
                // 入力されたグループパスワードをSHA-512関数でハッシュ化
                $hashed_password = hash('sha512', $_POST['group_password']);
                if(strcmp($labo_password, $hashed_password) == 0) { // 入力されたグループパスワードが正しくない
                    // グループパスワードをセッションに一時保存
                    $_SESSION['group_password'] = $_POST['group_password'];
                    
                    // 登録内容変更画面へリダイレクト
                    $success_url = _HTTP_HOST.'?func=account_setting';
                    header('Location: '.$success_url);
                    exit;
                }
                
                $this->smarty->assign('error_message', '<br />グループパスワードが間違っています。<br /><br />');
            }
        }
    }
?>
