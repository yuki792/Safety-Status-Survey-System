<?php
require_once('twitteroauth/twitteroauth.php');

class TwitterAnpi{
	/** 
     * 
     * Twitter OAuth Object 
     * @var unknown_type 
     */
	private $TwitterOAuth = NULL;
	
	/** 
     * 
     * コンストラクタ  
     */
	public function __construct(){
		$this->TwitterOAuth = new TwitterOAuth('e5BiW3kEvOESmjaibMdYg','jNTN7IvAfRum4H99SWqGv9HjcdlyieCvoWetSEMnTs','1899582188-YzgZ8m77dpQ8b77jkTvl8FAlxRiVc17tCGngIqB','ceyqwyWQdPFGiwseKVjQCCpvEpQb9DcmlIjXYE8UI');
	}
	
	/** 
     * 
     * Twitterから安否情報を確認する
     * @param string $user :Twitterのアカウント名
	 * @param string $data :検索の基準の日付
     */
	public function CheckTwitterAnpi($user,$date){
		$tof = false;
		
		$options = array('count'=>'1','screen_name'=>$user);
		
		$json = $this->TwitterOAuth->OAuthRequest('https://api.twitter.com/1.1/statuses/user_timeline.json','GET',$options);
		
		$jset = json_decode($json,true);
		
		foreach($jset as $result){
			$time = new DateTime($date); //比較する日時
			$ttime = new DateTime($result['created_at']); //ツイートした日時
			$ttime->setTimezone( $time->getTimezone() );
			$ttime->modify('+7 hour');
			
			//$ut = $ttime->getTimestamp()-3600*2;
			//$dt = new DateTime();
			//$dt->setTimestamp( $ut );
			
			//echo '<br>'.$time->format('Y-m-d H:i:s');
			//echo '<br>'.$ttime->format('Y-m-d H:i:s');
			//echo '<br>'.$dt->format('Y-m-d H:i:s');
			
			//var_dump($time<$ttime);
			//var_dump($time<$dt);
			if($time<$ttime){
				$tof = true;
				break;
			}
		}
		
		return $tof;
	}
}
?>