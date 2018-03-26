<?php   !defined('IN WEB') AND exit('Access Denied!');

class Robot_win {
    private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Robot_win();
		}
		
		return self::$_instances;
	}
	
	public function bankerwsin(){
	    $dragon = Loader_Redis::game()->hGetAll('RobotWinDragon');
	    $dragon['upperlimit'] = Loader_Redis::game()->hGet('Dragon_RoomConfig', 'upperlimit');
	    $many = Loader_Redis::game()->hGetAll('RobotWinMany');
	    $many['upperlimit'] = Loader_Redis::game()->hGet('BullFightMany', 'upperlimit');
	    $fishinfo = Loader_Redis::game()->hGet("FishInfo",'poolcoin');
	    $texas = Loader_Redis::game()->hGetAll('RobotWinManyTexas');
	    $texas['upperlimit'] = Loader_Redis::game()->hGet('TexasMany', 'upperlimit');
	    $glod = Loader_Redis::game()->hGetAll('RobotWinManyFlower');
	    $glod['upperlimit'] = Loader_Redis::game()->hGet('FlowerMany', 'upperlimit');
	    
	    $array = array($dragon,$many,$fishinfo,$texas,$glod);
	    
	    return $array;
	}
}
