<?php
require_once 'ApnsPHP/Autoload.php';

class IOSPush {
	private $_push;
	
	public function __construct($pemPath, $isProduction = false) {
		$this->_push = new ApnsPHP_Push(
							$isProduction ? ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION : ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
							$pemPath . ($isProduction ? 'apns-pro.pem' : 'apns-dev.pem' )
						);
	}
	
	public function connect() {
		$this->_push->connect();
	}
	
	public function sendMsg($devices, $ruleId=0, $msg = '') {

		$cacheKey = 'FBFTCDD'.date("Ymd");
		$cachePush = ocache::cache()->get( $cacheKey );

		$pushId = $cachePush ? $cachePush:1;
		foreach($devices as $k => $d) {
			$message = new ApnsPHP_Message($d['device']);

			$message->setCustomIdentifier(sprintf("Message-Badge-%03d", $k));
			$message->setBadge(1);
			$message->setText($msg);
			$message->setPushId($pushId);
			$pushId =$pushId+1;
		    ocache::cache()->set($cacheKey, $pushId);
			$message->setRuleId( $ruleId );
			$this->_push->add($message);
		}
		
		$this->_push->send();
	}
	public function disconnect() {
		$this->_push->disconnect();
	}
}