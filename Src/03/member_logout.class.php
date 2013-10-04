<?php
    class member_logout extends MainFuncClass {
        protected $templateName = "03/member_logout.tpl";
        
        public function execute() {
            session_start();
                
            // ログイン済みかどうかチェック
            if(!isset($_SESSION['member_id'])){
                
                // 未ログインならばログインページにジャンプ
                header('Location: '._HTTP_HOST.'?func=member_login');
                exit;
            }
            
            // セッション変数のクリア
            $_SESSION = array();
            
            // クッキーの削除
            if(isset($_COOKIE['PHPSESSID'])){
                setcookie('PHPSESSID', '', time() - 42000, '/');
            }
            
            // セッションのクリア
            session_destroy();            
        }
    }
?>


