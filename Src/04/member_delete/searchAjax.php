<?php
	require_once('../../include/4s.ini.php');
	require_once('../../include/4s.func.php');
	require_once('../../class/dao/Dao.class.php');
	require_once('../../class/dao/CourseDao.class.php');
	require_once('../../class/dao/AdministratorDao.class.php');
	require_once('../../class/dao/MemberDao.class.php');
	
	session_start();
	
	// ログイン済みかどうかチェック
	if(!isset($_SESSION['administrator_id'])){
		
		// 未ログインならばログインページにジャンプ
		header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
		exit;
	}
	
	// データベース接続
	$course_dao = new CourseDao();
	$admin_dao = new AdministratorDao();
	$member_dao = new MemberDao();
	
	// モードを取得
	if(isset($_GET['mode'])){
		$mode = htmlspecialchars($_GET['mode']);
	}
	
	// モード1ならば学部IDを、モード2なら学科IDを取得
	if($mode == 1){
		$faculty_id = htmlspecialchars($_GET['faculty_id']);
	}elseif($mode == 2){
		$course_id = htmlspecialchars($_GET['course_id']);
	}else{
		
	}
	
	// モード1ならばデータベースから対象学部の学科一覧を取得
	// モード2ならばデータベースから対象学科のメンバー一覧を取得
	if($mode == 1){
		$course_array = $course_dao->select_byfaculty($faculty_id);
		
		$course_list = '学科：';
		$course_list .= '<select id="target_course" name="target_course" onchange="loadText(2)">'."\n";
		$course_list .= '<option value="-1">選択してください</option>'."\n";
		
		foreach($course_array as $data){
			$course_list .= '<option value="'.$data['course_id'].'">'.$data['course_name'].'</option>'."\n";
		}
		print($course_list);
	}elseif($mode == 2){
        // 管理者の研究室IDを取得
        $admin_array = $admin_dao->select($_SESSION['administrator_id']);
        $labo_id = 0;
        if(count($admin_array)==1){
            $labo_id = $admin_array[0]['labo_id'];
        }
        
		$member_array = $member_dao->select_byCourseAndLabo($course_id,$labo_id);
		
		$member_list = '学生：';
		$member_list .= '<select id="target_member" name="target_member">'."\n";
		$member_list .= '<option value="-1">選択してください</option>'."\n";
		
		foreach($member_array as $data){
			$member_list .= '<option value="'.$data['member_id'].'">'.$data['member_id'].'&nbsp&nbsp'.
			$data['last_name'].'&nbsp'.$data['first_name'].'</option>'."\n";
		}
		print($member_list);
	}else{
		
	}
	
?>