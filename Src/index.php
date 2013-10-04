<?php

// インクルードファイル
require_once './include/4s.func.php';
require_once './include/4s.ini.php';

// クラスローダ設定
require_once './class/ClassLoader.class.php';
$classLoader = new ClassLoader();
$classLoader->registerDir( './class/' );
$classLoader->registerDir( './class/twitter/' );
$classLoader->registerDir( './class/dao/' );
$classLoader->registerDir( './00/' );
$classLoader->registerDir( './01/' );
$classLoader->registerDir( './02/' );
$classLoader->registerDir( './03/' );
$classLoader->registerDir( './03/account_setting/' );
$classLoader->registerDir( './03/flink/' );
$classLoader->registerDir( './03/reply_survey/' );
$classLoader->registerDir( './03/reply_survey_k/' );
$classLoader->registerDir( './04/' );
$classLoader->registerDir( './04/administrator_account_setting/' );
$classLoader->registerDir( './04/administrator_delete/' );
$classLoader->registerDir( './04/labo_password_register/' );
$classLoader->registerDir( './04/administrator_signup/' );
$classLoader->registerDir( './04/member_delete/' );
$classLoader->registerDir( './04/member_signup/' );
$classLoader->registerDir( './04/safety_survey/' );
$classLoader->registerDir( './04/safety_survey/cross_finder/' );
$classLoader->registerDir( './04/safety_survey/resend_survey/' );
$classLoader->registerDir( './04/safety_survey/send_survey/' );
$classLoader->registerDir( './04/safety_survey/twitter_observe/' );
$classLoader->registerDir( './04/survey_result/' );
$classLoader->registerDir( './05/' );

// Smarty設定
require_once('Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = './smarty/templates/';
$smarty->compile_dir  = './smarty/templates_c/';
$smarty->config_dir   = './smarty/configs/';
$smarty->cache_dir    = './smarty/cache/';

// 処理選択
$func = @$_GET['func'];

// NotFound処理を追加する必要あり
if ( is_null( $func ) ){ 
	//echo 'えらー 処理名が宣言されてない';
	//exit;
	$func = 'top';
}

// メイン処理 
$mainClass = NULL; 

try {
	$mainClass = new $func();	
} catch ( Exception $e ) {
	$mainClass = new top();
}

//$mainClass->setPdo( $pdo ); 
$mainClass->setSmarty( $smarty ); 
$mainClass->execute(); 

// HTML出力 
$html = $smarty->fetch( $mainClass->getTemplateName() ); 
echo $html;

// end of index.php