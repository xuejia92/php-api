<?php !defined('IN WEB') AND exit('Access Denied!');
class Config_Game7{

	//评价游戏列表
	public static $evaluateUrl = array('1'=>"www.baidu.com",
									   '141'=>"www.sina.com"
								  );
    //public static $evaluateUrl = array('1'=>"https://itunes.apple.com/cn/app/huo-pin-dou-niu/id879994259?mt=8",
    //                                 '9'=>"https://itunes.apple.com/us/app/dian-le-suo-ha/id806953900?ls=1&mt=8"
    //                            );
    								  
    public static function getServerInfo($cid,$mtype,$versions,$pmode,$mid){
    	$serverInfo['hallAddr'] = Config_Inc::$hallAddr;
    	//根据运营商卡类型    	
    	if($mtype){
    		$spGood = '63000金币';
    	}else{//没传
    		$spGood = '108000金币';
    	}

    	$serverInfo['vip'] = Config_Game::$loginVipConfig;//登陆弹出框会员配置
    	
    	switch ($cid) {
    		case 1:
    		case 9:
    		case 24:
    		case 88://爱思助手（IOS）	
    		case 112://火拼德州	
    		case 92://海马助手	
    		case 132://专业进阶	
    			$fastPayConfig = array(1=>'60000金币',2=>'200000金币',3=>'580000金币',4=>'580000金币',5=>'580000金币',6=>'580000金币');
		    	$serverInfo['firstpaygood'] = '60000金币'; //首充商品配置  商品名称 送金币倍数  喇叭数 会员天数
		    	$serverInfo['flashsalegood'] = array('200000金币',150); //限时抢购商品 商品名  倍数
				$serverInfo['bankruptpay']  = array('gname'=>'200000金币','gold'=>200000); //破产支付配置;
    		break;
    		default:
                $fastPayConfig = array(0=>'6万金币',1=>'12万金币',2=>'18万金币',3=>'25万金币',4=>'30万金币');
                $serverInfo['firstpaygood'] = '6万金币'; //首充商品配置  商品名称 
                $serverInfo['flashsalegood'] = array('6万金币',150); //限时抢购商品 商品名  倍数
                $spGood_int = (int)$spGood;
                $serverInfo['bankruptpay']  = array('gname'=>'6万金币','gold'=>108000); //破产支付配置
    		break;
    	}
		
    	$i = 0;
    	for($id=1;$id<=5;$id++){
    		$config = Loader_Redis::server()->hGetAll(Config_Keys::flowerRoomConfig($id));	
			$serverInfo['roomconfig'][$i]['roomid']      = $id;
    		$serverInfo['roomconfig'][$i]['minmoney']    = (int)$config['minmoney'];
    		$serverInfo['roomconfig'][$i]['maxmoney']    = (int)$config['maxmoney'];
    		$serverInfo['roomconfig'][$i]['ante']        = (int)$config['ante'];
            $serverInfo['roomconfig'][$i]['tax']         = (int)$config['tax'];
    		$serverInfo['roomconfig'][$i]['maxlimit']    = (int)$config['maxlimit'];
            $serverInfo['roomconfig'][$i]['maxallin']    = (int)$config['maxallin'];
            $serverInfo['roomconfig'][$i]['rase1']       = (int)$config['rase1'];
            $serverInfo['roomconfig'][$i]['rase2']       = (int)$config['rase2'];
            $serverInfo['roomconfig'][$i]['rase3']       = (int)$config['rase3'];
            $serverInfo['roomconfig'][$i]['rase4']       = (int)$config['rase4'];
    		$serverInfo['roomconfig'][$i]['magiccoin']    = (int)$config['magiccoin'];
    		$serverInfo['roomconfig'][$i]['fastpayname']  = $fastPayConfig[$i];
    		$i++; 
    	}
    	
    	return $serverInfo;
    }
    
    public static $promotion = array(
        array(
            'gameid'=>7,
            'imgurl'=>"www.aaa.com/statics/7.jpg",
            'durl'=>array(),
            'pack'=>array(),
            //'imgurl'=>'http://user.dianler.com/statics/promotion/7.jpg',
            //'durl'=>array('1'=>'http://statics.dianler.com/apk/Goldenfraud-guanwang.apk','2'=>'https://itunes.apple.com/cn/app/huo-pin-zha-jin-hua-jing-dian/id1008041119?mt=8'),
            //'pack'=>array('1'=>'com.gangswz.goldenfraud','2'=>'com.gangswz.goldenfraud://gangswzapp'),
            'desc'=>'同账号玩火拼炸金花最高奖励100万金币，超级好赚！'
        )
	);
    
	public static function getVar($arvg){
    	return self::$$arvg;
    }
   
}
