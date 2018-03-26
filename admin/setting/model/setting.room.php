<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Room {
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Room();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$arr['raterent']   = $raterent   = Helper::uint($data['raterent']);
		$arr['minmoney']   = $minmoney   = Helper::uint($data['minmoney']);
		$arr['maxmoney']   = $maxmoney   = Helper::uint($data['maxmoney']);
		$arr['carrycoin']  = $carrycoin   = Helper::uint($data['carrycoin']);
		$arr['retaincoin'] = $retaincoin   = Helper::uint($data['retaincoin']);
		$id         = $data['id'] ? Helper::uint($data['id']) : '';		
		
		if(!$id){
			return false;
		}
		
		$key = Config_Keys::roomConfig($id);
		Loader_Redis::server()->hMset($key, $arr);

		return true;
	}
	
	public function get(){
		$id2roomname = array('1'=>'初级场','2'=>'中级场','3'=>'高级场','4'=>'二人场','5'=>'私人场');
		$aResult = array();
		for($i=1;$i<=5;$i++){
			$key = Config_Keys::roomConfig($i);
			$aInfo = Loader_Redis::server()->hGetAll($key);
			$aInfo['name'] = $id2roomname[$i];
			$aResult[$i] = $aInfo;
		}
	
		return $aResult;		
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);	
		$key = Config_Keys::roomConfig($id);
		return Loader_Redis::server()->hGetAll($key);
	}
}