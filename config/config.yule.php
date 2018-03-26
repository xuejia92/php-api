<?php !defined('IN WEB') AND exit('Access Denied!');
class Config_Yule{

    public static function getYuleInfo($aUser){
		$mid      = $aUser['mid'];
		$gameid   = $aUser['gameid'];
		$ctype    = $aUser['ctype'];
		$cid 	  = $aUser['cid'];
		$pid 	  = $aUser['pid'];
		$versions = $aUser['versions'];
		
		//水果派配置
    	$furit_roomConfig = Loader_Redis::server()->hGetAll("Fruit_RoomConfig");
		$yuleInfo['yuleconfig'][0]['name'] 			= "furit";            					 //娱乐场名称
    	$yuleInfo['yuleconfig'][0]['ip'] 			= "121.201.0.149";	  					 //娱乐场IP
    	$yuleInfo['yuleconfig'][0]['port'] 			= 7015;				  					 //娱乐场端口
    	$yuleInfo['yuleconfig'][0]['reqmoney'] 		= $furit_roomConfig["limitbet"];	     //娱乐场资产需求
		$yuleInfo['yuleconfig'][0]['onebet'] 		= $furit_roomConfig["onebet"];			 //单注价格
    	$yuleInfo['yuleconfig'][0]['speakmoney'] 	= $furit_roomConfig["limitchatcoin"]; 	 //聊天价格
    	$yuleInfo['yuleconfig'][0]['open'] 			= 1;				  					 //水果派开启与否
    	
		//小马快跑配置
    	$horse_roomConfig = Loader_Redis::server()->hGetAll("Horse_RoomConfig");
		$yuleInfo['yuleconfig'][1]['name'] 			= "horse";            					 //娱乐场名称
    	$yuleInfo['yuleconfig'][1]['ip'] 			= "121.201.0.149";	  					 //跑马场IP //"192.168.0.188";"121.201.0.148"
    	$yuleInfo['yuleconfig'][1]['port'] 			= 7015;				  					 //跑马场端口 //7000;
    	$yuleInfo['yuleconfig'][1]['reqmoney'] 		= $horse_roomConfig["limitbet"];		 //跑马资产需求
		$yuleInfo['yuleconfig'][1]['onebet'] 		= $horse_roomConfig["onebet"];			 //跑马单注价格
    	$yuleInfo['yuleconfig'][1]['speakmoney'] 	= $horse_roomConfig["limitchatcoin"]; 	 //跑马聊天价格
    	$yuleInfo['yuleconfig'][1]['open'] 			= 1;									 //跑马是否关闭
		
		
		//龙虎斗
    	$torodora_roomConfig = Loader_Redis::server()->hGetAll("Dragon_RoomConfig");
		$yuleInfo['yuleconfig'][2]['name'] 			= "torodora";            				 //娱乐场名称
    	$yuleInfo['yuleconfig'][2]['ip'] 			= "121.201.0.149";	  					 //龙虎斗IP //"192.168.0.188";"121.201.0.148"
    	$yuleInfo['yuleconfig'][2]['port'] 			= 7015;				  					 //龙虎斗端口 //7000;
    	$yuleInfo['yuleconfig'][2]['reqmoney'] 		= $torodora_roomConfig["limit"];		 //龙虎斗资产需求
		$yuleInfo['yuleconfig'][2]['onebet'] 		= 10000;								 //龙虎斗单注价格
    	$yuleInfo['yuleconfig'][2]['speakmoney'] 	= 1000;									 //龙虎斗聊天价格
    	$yuleInfo['yuleconfig'][2]['open'] 			= 1;									 //龙虎斗否关闭
		
    	
    	//万炮捕鱼
    	$fish_roomConfig = Loader_Redis::server()->hGetAll("Fish_RoomConfig");
    	$yuleInfo['yuleconfig'][3]['name'] 			= "fish";            				     //娱乐场名称
    	$yuleInfo['yuleconfig'][3]['ip'] 			= "121.201.0.149";	  					 //捕鱼IP //"192.168.0.188";"121.201.0.148"
    	$yuleInfo['yuleconfig'][3]['port'] 			= 7019;				  					 //捕鱼端口 //7000;
    	$yuleInfo['yuleconfig'][3]['reqmoney'] 		= $fish_roomConfig["limit"];		                                 //捕鱼资产需求
    	$yuleInfo['yuleconfig'][3]['onebet'] 		= 10000;								 //捕鱼单注价格
    	$yuleInfo['yuleconfig'][3]['speakmoney'] 	= 1000;									 //捕鱼聊天价格
    	$yuleInfo['yuleconfig'][3]['open'] 			= 1;									 //捕鱼否关闭
    	
    	if ($gameid==3 || $gameid==6 || $gameid==7){
    		$ports = array(7020,7022,7023,7024,7025);
    		$rank  = $mid % 5;
            $yuleInfo['yuleconfig'][3]['ip'] 			= "121.201.30.110";
            $yuleInfo['yuleconfig'][3]['port'] 			= $ports[$rank] ? $ports[$rank] : $ports[0];;
    	}
    	
		return $yuleInfo;
    }

    public static function getVar($arvg){
    	return self::$$arvg;
    }
}