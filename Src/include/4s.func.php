<?php
	
	/** 
     * 
     * データベースに接続する 
     * @param string $dsn :データベース接続情報
	 * @param string $user :データベース接続ユーザ名
	 * @param string $password :データベース接続パスワード
	 * @return PDO Object :データベース接続オブジェクト
     */
	function db_connect($dsn, $user, $password)
	{
		try{
			$pdo = new PDO($dsn, $user, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`",PDO::ATTR_PERSISTENT => TRUE));
		}catch(PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		
		return $pdo;
	}
	
	
	
	
	
	/** 
     * 
     * メールアドレスが正しいかを簡易チェックする
     * @param string $text :確認するメールアドレス
	 * @return bool :メールアドレスが正しかったか
     */
	function is_mail($text){
		if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $text)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	
	
	
	
	/** 
     * 
     * メールサーバへ認証を試みる
	 * @return unknown type :メールサーバ接続情報
     */
	function auth_mail()
	{
		//一時的にPHP5のエラー出力からE_STRICTを外す
		$E = error_reporting();
		if(($E & E_STRICT) == E_STRICT) error_reporting($E ^ E_STRICT);
		
		require_once('Mail.php');
		require_once('Mail/mime.php');
		
		mb_language('japanese');
		mb_internal_encoding('UTF-8');
		
		$params = array(
			'host'     => '',	// ホスト名
			'port'     => ,	// ポート番号
			'auth'     => true,	// 認証
			'debug'    => false,	// デバッグ
			'username' => '',	// ユーザ名
			'password' => ''	// パスワード
		);
		
		$smtp = Mail::factory('smtp', $params);
		
		// error_reportingを元に戻す
		error_reporting($E);
		
		return $smtp;
	}
	
	
	
	
	
	/** 
     * 
     * メールを送信する
     * @param unknown type :メールサーバ接続情報
	 * @param string $to :メール送り先アドレス
	 * @param string $subject :メール題名
	 * @param string $body :メール本文
     */
	function send_mail($smtp, $to, $subject, $body)
	{
		//一時的にPHP5のエラー出力からE_STRICTを外す
		$E = error_reporting();
		if(($E & E_STRICT) == E_STRICT) error_reporting($E ^ E_STRICT);
		
		$headers = array(
			'To'       => $to,				// 送信先
			'From'     => '',				// 送信元
			'Cc'       => '',				// CC
			'Bcc'      => '',				// BCC
			'Subject'  => mb_encode_mimeheader($subject)	// 件名
		);
		
		$body = mb_convert_encoding($body, 'ISO-2022-JP', 'auto');
		
		$return = $smtp->send($to, $headers, $body);	// メール送信
		
		if(PEAR::isError($return)){
			echo('メールが送信できませんでした　エラー：' .$return->getMessage());
		}
		
		// error_reportingを元に戻す
		error_reporting($E);
	}
	
	
	
	
	
	/** 
     * 
     * ランダムパスワードを作成する
     * @param int $len :生成するランダムパスワードの文字数
	 * @return string :生成したランダムパスワード
     */
	function create_password($len)
	{
		$chars = array(
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
			'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
			'u', 'v', 'w', 'x', 'y', 'z',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
			'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
			'U', 'V', 'W', 'X', 'Y', 'Z',
		);
		
		$pw = array();
		
		for ($i = 0; $i < $len; $i++)
		{
			shuffle($chars);
			$pw[] = $chars[rand(0, 35)];
		}
		
		shuffle($pw);
		$pw = implode('', $pw);
		
		return $pw;
	}
	
	
	
	
	
	// 災害用伝言板存在確認（存在していればTRUE、存在していなければFALSE）
	/** 
     * 
     * 災害用伝言板存在確認
	 * @return bool :災害用伝言板が存在するか（存在していればTRUE、存在していなければFALSE）
     */
	function is_dengonban()
	{
		//URLを指定
		$url = 'http://dengon.softbank.ne.jp/pc-2.jsp';
		
		//指定したURLのHTMLソースコードを取得
		$url_contents = @file_get_contents("$url");
		
		if($url_contents === FALSE){
			return FALSE;
		}
		
		// HTML除去 & 文字コード変換
		$striped_contents = strip_tags($url_contents);
		$utf8_contents = mb_convert_encoding($striped_contents, 'UTF-8', 'Shift-JIS');
		
		// 伝言板サービスが提供されていないときに表示されるメッセージ
		$pattern = '/現在、災害用伝言板はサービス提供しておりません/';
		
		if(preg_match($pattern, $utf8_contents)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	
	
	
	
	/** 
     * 
     * 災害用伝言板検索機能 Cross Finder
	 * @param string $phone_number :確認する電話番号
	 * @return bool :災害用伝言板に登録されているか(見つかったらTRUE、見つからないor失敗ならFALSE)
     */
	function cross_finder($phone_number)
	{
		//URLを指定
		$url = 'http://dengon.softbank.ne.jp/pc-2.jsp?m='.$phone_number;
		
		//指定したURLのHTMLソースコードを取得
		$url_contents = @file_get_contents("$url");
		
		if($url_contents === FALSE){
			return FALSE;
		}
		
		// HTML除去 & 文字コード変換
		$striped_contents = strip_tags($url_contents);
		$utf8_contents = mb_convert_encoding($striped_contents, 'UTF-8', 'Shift-JIS');
		
		/*
		11桁の半角数字が入力されたが、ヒットするデータが無かったとき
		この携帯電話番号での各社災害用伝言板へのメッセージ登録はありませんでした
		
		11桁以下、11桁以上、数字以外を入力したとき
		電話番号は半角数字11桁で入力してください
		
		未入力のとき
		電話番号を入力してください
		
		SoftBank以外でヒットしたとき
		入力された携帯電話番号は、以下の災害用伝言板にメッセージ登録がありました
		
		SoftBankでヒットしたとき
		安否情報の確認ができます
		*/
		
		$true_pattern1 = '/入力された携帯電話番号は、以下の災害用伝言板にメッセージ登録がありました/';
		$true_pattern2 = '/安否情報の確認ができます/';
		
		$false_pattern1 = '/この携帯電話番号での各社災害用伝言板へのメッセージ登録はありませんでした/';
		$false_pattern2 = '/電話番号は半角数字11桁で入力してください/';
		$false_pattern3 = '/電話番号を入力してください/';
		
		if(preg_match($true_pattern1, $utf8_contents)){
			return TRUE;
		}elseif(preg_match($true_pattern2, $utf8_contents)){
			return TRUE;
		}elseif(preg_match($false_pattern1, $utf8_contents)){
			return FALSE;
		}elseif(preg_match($false_pattern2, $utf8_contents)){
			return FALSE;
		}elseif(preg_match($false_pattern3, $utf8_contents)){
			return FALSE;
		}else{
			return FALSE;
		}
	}

?>