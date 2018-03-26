<?php !defined('IN WEB') AND exit('Access Denied!');

class User_Behavior extends Config_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new User_Behavior();
		}
		
		return self::$_instances;
	}
	
	public function get($data){
		$mid = Helper::uint($data['mid']);
		if(!$mid){
			return false;
		}
		$day = $data['day'] ? date("Ymd",strtotime($data['day'])) : date("Ymd");
	   	
		$gameid = $data['gameid'] ? $data['gameid'] : 1;
	   	
		$file = PRODUCTION_SERVER == false ? "/data0/wwwroot/data/behavior/1/$day/$mid/behavior.log" : "http://192.168.1.188/behavior/$gameid/$day/$mid.log";
		//$time = strtotime("2015-5-25 10:36:00");
		
		$idc = Config_Inc::$idc;
		if($idc == 2){
			$file =  "/data/dis/log/behavior/$gameid/$day/$mid.log";
		}
	
		//$file = "http://192.168.1.148/data/behavior/$gameid/$day/$mid/behavior.log";
		$items = array();		
		$handle = fopen($file, 'r');
		//var_dump($handle);
		if($handle){
			while(!feof($handle)){
				$row = fgets($handle);
				$row && $items[] = json_decode($row,true);
			}
		}		
		
		fclose($handle);		
		return $items;
	}

}