<?php
class member_delete extends MainFuncClass {
	protected $templateName = '04/member_delete/member_delete.tpl';
	
	public function execute(){
		session_start();
		
		// ログイン済みかどうかチェック
		if(!isset($_SESSION['administrator_id'])){
			
			// 未ログインならばログインページにジャンプ
			header('Location: '._HTTP_HOST.'index.php?func=administrator_login');
			exit;
		}
		
		// データベース接続
		$member_dao = new MemberDao();
		$faculty_dao = new FacultyDao();
		$course_dao = new CourseDao();
		
		// エラーメッセージ
		$error_message = '';
		
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
		$target_faculty = (isset($_POST['target_faculty'])) ? $_POST['target_faculty'] : '';
		$target_course = (isset($_POST['target_course'])) ? $_POST['target_course'] : '';
		$target_member = (isset($_POST['target_member'])) ? $_POST['target_member'] : '';
		
		//HTMLタグをエスケープ
		$target_faculty = htmlspecialchars($target_faculty);
		$target_course = htmlspecialchars($target_course);
		$target_member = htmlspecialchars($target_member);
		
		// 確認ボタンも登録ボタンも押されていないとき
		if($confirm_button == '' && $send_button == ''){
			
			// データベースから学部の一覧を取得
			$faculty_array = $faculty_dao->select_all();
			$faculty_list = '';
			foreach($faculty_array as $data){
				$faculty_list .= '<option value="'.$data['faculty_id'].'">'.$data['faculty_name'].'</option>';
				$faculty_list .= "\n";
			}
			$this->smarty->assign('faculty_list', $faculty_list);
		}
		
		// 確認ボタンが押されたとき
		if($confirm_button != ''){
			
			if($target_faculty == '-1'){   // 削除するアカウントが未選択
				$error_message .= '削除するアカウント（学部）が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			if($target_course == '-1' || $target_course == ''){   // 削除するアカウントが未選択
				$error_message .= '削除するアカウントが未選択（学科）が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			if($target_member == '-1' || $target_member == ''){   // 削除するアカウントが未選択
				$error_message .= '削除するアカウントが未選択（学生）が未選択です。<br />';
				$confirm_button = '';
				$send_button = '';
			}
			
			// 学部IDから学部名を取得
			if($target_faculty == '' || $target_faculty == -1){
				$faculty_name = '';
			}else{
				$faculty_array = $faculty_dao->select($target_faculty);
				$faculty_name = $faculty_array[0]['faculty_name'];
				$this->smarty->assign('faculty_name', $faculty_name);
			}
			// 学科IDから学科名を取得
			if($target_course == '' || $target_course == -1){
				$course_name = '';
			}else{
				$course_array = $course_dao->select($target_course);
				$course_name = $course_array[0]['course_name'];
				$this->smarty->assign('course_name', $course_name);
			}
			// 利用者IDから学生名を取得
			if($target_member == '' || $target_member == -1){
				$member_name = '';
			}else{
				$member_array = $member_dao->select($target_member);
				$member_name = $target_member.' '.$member_array[0]['last_name'].' '.$member_array[0]['first_name'];
				$this->smarty->assign('member_name', $member_name);
			}
		}
		
		// 確認画面で登録ボタンが押されたときの処理
		if($send_button != ''){
			$result = $member_dao->delete($target_member);
			
			if($result){
				$this->smarty->assign('message','利用者アカウントの削除が完了しました。');
			}else{
				$this->smarty->assign('message','利用者アカウントの削除に失敗しました。');
			}
			
			//$this->smarty->display('member_delete_complete.tpl');
			$this->setTemplateName( '04/member_delete/member_delete_complete.tpl' );
		}
		
		// 画面に表示する情報をセット
		$this->smarty->assign('error_message', $error_message);
		$this->smarty->assign('target_faculty', $target_faculty);
		$this->smarty->assign('target_course', $target_course);
		$this->smarty->assign('target_member', $target_member);
		
		// ボタンを押されたときの処理
		if($send_button != ''){
		}elseif($confirm_button != ''){
			//$this->smarty->display('member_delete_confirm.tpl');
			$this->setTemplateName( '04/member_delete/member_delete_confirm.tpl' );
		}else{
			//$this->smarty->display('member_delete.tpl');
			$this->setTemplateName( '04/member_delete/member_delete.tpl' );
		}
	}
}
