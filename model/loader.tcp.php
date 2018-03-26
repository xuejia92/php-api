<?php !defined('IN WEB') AND exit('Access Denied!');

class Loader_Tcp{
	
	private static $_instances = array();
	
	/**
	 * @return Lib_Tcp
	 */
	public static function callServer($mid){
		$addr = Config_Inc::$moneyServer;
		$mod  = $mid % 10;
		$port = $addr['port'];
		//$port = $addr['port'][0] + $mod;

		if(!is_object(self::$_instances['server'][$mid])){
			self::$_instances['server'][$mid] = new Lib_Tcp($addr['ip'], $port);
		}
		
		return self::$_instances['server'][$mid];
	}
	
	/**
	 * @return Lib_Tcp
	 */
	public static function callServer2PayNotice($mid){
		$addr = Config_Inc::$userServer;
		$mod  = $mid % 10;
		$port = $addr['port'];
		//$port = $addr['port'][0] + $mod;
		if(!is_object(self::$_instances['callServerPayNotice'])){
			self::$_instances['callServerPayNotice'] = new Lib_Tcp($addr['ip'],$port);
		}
		
		return self::$_instances['callServerPayNotice'];
	}
	
	/**
	 * @return Lib_Tcp
	 */
	public static function callServer2Horn(){
		if(!is_object(self::$_instances['serverHorn'])){
			self::$_instances['serverHorn'] = new Lib_Tcp(Config_Inc::$serverHorn['ip'], Config_Inc::$serverHorn['port']);
		}
		
		return self::$_instances['serverHorn'];
	}
	
	/**
	 * @return Lib_Tcp
	 */
	public static function sendMsg2Client($mid){
		$addr = Config_Inc::$userServer;
		$port = $addr['port'];
		//$mod  = $mid % 10;
		//$port = $addr['port'][0] + $mod;
		if(!is_object(self::$_instances['sendMsg2Client'][$mid])){
			self::$_instances['sendMsg2Client'][$mid] = new Lib_Tcp($addr['ip'], $port);
		}
		
		return self::$_instances['sendMsg2Client'][$mid];
	}
	
	/**
	 * @return Lib_Tcp
	 */
	public static function callServer2CheckName(){
		
		if(!is_object(self::$_instances['checkName'])){
			self::$_instances['checkName'] = new Lib_Tcp(Config_Inc::$serverCheckUserName['ip'], Config_Inc::$serverCheckUserName['port']);
		}
		
		return self::$_instances['checkName'];
	}

}