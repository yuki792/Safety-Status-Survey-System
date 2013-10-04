<?php	
	require_once('../../include/4s.ini.php');
	require_once('../../include/4s.func.php');
    require_once('../../class/dao/Dao.class.php');
    require_once('../../class/dao/CourseDao.class.php');
	
	session_start();
	
	// ログイン済みかどうかチェック
	if(!isset($_SESSION['administrator_id'])){
		
		// 未ログインならばログインページにジャンプ
		header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
		exit;
	}
	
	
	// データベース接続
	$pdo = db_connect(_DSN, _USER, _PASSWORD);
	
	// モードを取得
	if(isset($_GET['mode'])){
		$mode = htmlspecialchars($_GET['mode']);
	}
	
	// モード1ならば学部IDを、モード2なら学科IDを取得
	if($mode == 1){
		$faculty_id = htmlspecialchars($_GET['faculty_id']);
	}else{
		
	}
	
	// モード1ならばデータベースから対象学部の学科一覧を取得
	if($mode == 1){
	    $cDao = new CourseDao();
        $course_data = $cDao->select_byfaculty($faculty_id);
		
		$course_list = '学科：';
		$course_list .= '<select id="course" name="course_id">'."\n";
		$course_list .= '<option value="-1">選択してください</option>'."\n";
		
		foreach ($course_data as $data) {
			$course_list .= '<option value="'.$data['course_id'].'">'.$data['course_name'].'</option>'."\n";
		}
		print($course_list);
	}else{
		
	}
	
?>