<?php 

require_once ROOT_PATH.'system/api/push/ios/ApnsPHP/Autoload.php';

class Ios_Api
{
	protected static $_instance = array();
	
	private $_push;
	
    /**
	 * 创建一个实例
	 *
	 * @return  Ios_Api
	 */
    public static function factory($gameid,$ctype,$isProduction=true)
	{
	    if(!is_object(self::$_instance[$gameid][$ctype]))
		{
		    self::$_instance[$gameid][$ctype] = new Ios_Api($gameid,$ctype,$isProduction);
		}
	
		return self::$_instance[$gameid][$ctype];
	}
	
	public  function __construct($gameid, $ctype,$isProduction){
		
		$pemPath = ROOT_PATH."system/api/push/ios/dev/$gameid/";
		
		$this->_push = new ApnsPHP_Push(
							$isProduction ? ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION : ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
							$pemPath . ($isProduction ? 'apns-pro-'.$ctype.'.pem' : 'apns-dev-'.$ctype.'.pem' )
						  );
						  
		$pemPath . ($isProduction ? 'apns-pro-'.$ctype.'.pem' : 'apns-dev-'.$ctype.'.pem' );				  
	}
	
	public function connect() {
		$this->_push->connect();
	}
	
	public function disconnect() {
		$this->_push->disconnect();
	}
	
	/**
	 * 
	 * 推送消息
	 */	
	public function sendMsg($devices,$msg,$type=''){
		
		foreach($devices as $k => $d) {	 		
			
			if($type){
				$flag = $this->_extraMutiPush( $d,$type,$msg);
				if($flag == false){
					continue;
				}
			}
			
			$message = new ApnsPHP_Message($d['device']);
			$message->setCustomIdentifier(sprintf("Message-Badge-%03d", $k));
			$message->setBadge(1);
			$message->setSound( 'Bounce2.caf' );
			$message->setText($msg);
			
			$this->_push->add($message);
		}
		
		$message && $this->_push->send();
		unset($message);
	}
	
	/**
	 * 批量推送时的一些处理
	 */
	private function _extraMutiPush( $d ,$type,&$msg){
		
		$type2itemid = array(1=>100,2=>177);
		
		Loader_Udp::stat()->sendData($type2itemid[$type], $d['mid'],$d['gameid'], $d['ctype'], $d['cid'], $d['sid'], $d['pid'], 0);//n天没登陆推统计
		
		/*
		if($type == 1){
			
			$rewardMoney = Loader_Redis::push()->hGet(Config_Keys::pushReward(), $d['device']);
			if(!$rewardMoney){
				return false;
			}
			$msg = str_replace("{money}", $rewardMoney, $msg);
			
			$itemid = 100;
			Loader_Udp::stat()->sendData($itemid, $d['mid'],$d['gameid'], $d['ctype'], $d['cid'], $d['sid'], $d['pid'], 0);//n天没登陆推统计
		}
		*/
		return true;
	}
}