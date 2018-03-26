<?php !defined('IN WEB') AND exit('Access Denied!');

class Config_Switch{
	
	public static function optSwitch(&$aUser){
		
		$aUser['otherpay']      = 1;//网页支付
		$aUser['othergame']     = 1;//更多游戏
		$aUser['hasActivity']   = 1;//活动
		$aUser['cardOpen']      = 1;//点卡开关
		$aUser['bankruptpay']   = 1;//破产充
		$aUser['flashsaletime'] = 0;//限时充
		$aUser['firstpay']      = 0;//首充  默认开
		$aUser['yuleInfo']['yuleopen'] =  1;
				
		$config = array(
						1=>'otherpay',
						2=>'othergame',
						3=>'yuleInfo',
						4=>'firstpay',
						5=>'bankruptpay',
						8=>'hasActivity',
						9=>'cardOpen',
						10=>'flashsaletime'
					);
		
		
		$pid        = $aUser['pid'];
		$switch_bit = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$pid);
		
		
		$is_china_ip     = 0;
		$ip              = Helper::getip();
		$aUser['ipArr']  = $ip_arr = Lib_Ip::find($ip);
		
		if( in_array($ip_arr[0], array('中国','局域网 '))){
			$is_china_ip = 1;
		}
		
		if($ip_arr[0] == '美国'){
			$is_usa_ip = 1;
		}
		
		if($is_usa_ip){//美国IP不能显示更多支付
			$aUser['otherpay'] = 0;
		}
		
		if(!$is_china_ip){//非中国IP的一些限制
			$aUser['othergame'] = $aUser['cardOpen'] = 0;//IOS网页充值
		}
		
		if($switch_bit == 0){
			return '';
		}
		
		$switch_arr = array();
		for($i=0;$i<20;$i++){
			$flag = $switch_bit >> $i & 1;
			if($flag){
				$switch_arr[] = $i+1;
			}
		}
		
		foreach ($config as $key=>$val) {
			if( in_array($key, $switch_arr) ){
				if($key == 4){
					$aUser['firstpay'] = 1;
				}elseif($key == 3){
					$aUser['yuleInfo']['yuleconfig'][0]['open'] = 0; 
					$aUser['yuleInfo']['yuleconfig'][1]['open'] = 0;
					$aUser['yuleInfo']['yuleconfig'][2]['open'] = 0;
					$aUser['yuleInfo']['yuleconfig'][3]['open'] = 0;
				}elseif($key == 10){
					$aUser['flashsaletime'] = -1;
				}
				else{
					$aUser[$val] = 0;
				}
			}
		}
	}
	
	public static function rewardSwitch(&$aUser){
		
		if($aUser['gameid'] == 3){//斗地主临时增加记牌器
			$stime = strtotime("2015-09-01 00:00:00");
			$etime = strtotime("2015-09-04 23:59:59");
			$flag = Loader_Redis::common()->get("optpros|".$aUser['mid'],false,false);
			if(NOW > $stime && NOW <= $etime){
				Member::factory()->optProps(3, $aUser['mid'], 1);
				Loader_Redis::common()->set("optpros|".$aUser['mid'], 1, false,false,7*3600*24);
			}
		}
	}
	
}