<?php
abstract class MainFuncClass{
	
	//protected $pdo		= NULL;
	
	/** 
     * 
     * Smartyオブジェクト
     * @var Smarty Object 
     */
	protected $smarty	= NULL;
	
	// 継承先で以下を定義
	// protected $templateName;
	
	/** 
     * 
     * ページ処理メイン処理
     */
	abstract public function execute();
	
	/** 
     * 
     * コンストラクタ
     */
	public function __construct(){
		if ( method_exists( $this, 'afterExecute' ) ){
			register_shutdown_function( array( $this, 'afterExecute' ) );
		}
	}
	
	// public function setPdo( $pdo ){
		// $this->pdo = $pdo;
	// }
	
	/** 
     * 
     * Smartyオブジェクト登録 
     * @param Smarty Object :Smartyオブジェクト
     */
	public function setSmarty( $smarty ){
		$this->smarty = $smarty;
	}
	
	/** 
     * 
     * HTTP ヘッダ送信
	 * @return bool :ヘッダーを送ることができたか
     */
	protected function sendHeader(){
		if ( headers_sent() ){
			return FALSE;
		} else {
			header( 'Content-Type: text/html; charset:utf-8' );
		}
		
		return TRUE;
	}
	
	 /** 
     * 
     * ページに対応するSmartyテンプレート名を取得 
	 * @return string :テンプレート名
     */
	public function getTemplateName(){
		return $this->templateName;
	}
	
	 /** 
     * 
     * ページに対応するSmartyテンプレート名を登録
     * @param string $tn :テンプレート名
     */
	public function setTemplateName( $tn ){
		$this->templateName = $tn;
	}
	
}

// end of MainFuncClass.abstract.class.php