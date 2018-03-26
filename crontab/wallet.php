<?php
$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';
set_time_limit(0);

$timehi = date('Hi');

$msg = "系统消息：尊敬的玩家，为了给您更好的游戏体验，系统将于3：30-4：30进行停服维护，请退出游戏，否则会造成不必要的金币损失！";
	Loader_Tcp::callServer2Horn()->setHorn($msg,0,100);

if($timehi % 30 == 0){
	$msg = "系统消息：关注微信公众号'点乐游戏'，可领取金币大礼包！";
	Loader_Tcp::callServer2Horn()->setHorn($msg,0,100);
	
	sleep(10);
	
	$msg = "系统消息:购买50元以上金币，随机赠送小喇叭,多买多送,先到先得！";
	Loader_Tcp::callServer2Horn()->setHorn($msg,0,100);
	
	sleep(10);
	
	$msg = "系统消息:禁止打合张，一经发现，永久封号，举报有奖！";
	Loader_Tcp::callServer2Horn()->setHorn($msg,0,3);
}

if($timehi == '1230' || $timehi == '2030' || $timehi == '2130' ){
	
	$money  = 1000000;
	$gameid = 3;

	//$msg = "发钱啦！系统将1000000金币装进红包，各路英雄准备10秒后开抢！（屏幕出现红包、立即点击,仅支持最新版本）";
	//Loader_Tcp::callServer2Horn()->setHorn($msg,3,10);
	
	sleep(15);
	
	$result = Base::factory()->giveWalletBySystem($gameid, $money);
	
	sleep(10);
	
	$num      = 200 + mt_rand(100, 500);
	$maxmoney = $money*0.08 + mt_rand(10000, 100000) ;
	$msg      = "本轮抢红包活动结束,共".$num."人抢得红包，最高获得".$maxmoney."金币。敬请期待下期活动。！";
	$msg      = str_replace("\n", "", $msg);
	//Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);

}else{
	if($timehi == '1200' || $timehi == '2000' || $timehi == '2100'){//提前半个小时发
		//$msg = "亲爱的玩家：抢红包活动将于30分钟后开启，请做好准备哦，需在游戏中点击红包领取！（屏幕出现红包、立即点击，仅支持最新版本）";
		//Loader_Tcp::callServer2Horn()->setHorn($msg,3,10);
	}
	
	if($timehi % 15 == 0){
		//$msg = "普天同庆，系统将在12:30、20:30、21:30分别发放1000000金币红包，请大家在游戏中抢红包，（屏幕出现红包、立即点击，仅支持最新版本）";
		//Loader_Tcp::callServer2Horn()->setHorn($msg,3,10);
	}
	
	
	$hi = date("H");
	//if($hi >= 8 && $hi < 18){
	/*
		if($timehi % 5 == 0){
			$msg = "系统消息:移动短信无法充值？别急，用支付宝或银联充值即送高达30%返利金币！";
			Loader_Tcp::callServer2Horn()->setHorn($msg,3,10);
			Loader_Tcp::callServer2Horn()->setHorn($msg,4,10);
		}
	*/
	//}

}

$w = date("w");
if($w == 2 && $timehi == '0000'){
	Loader_Redis::rank(3)->delete(Config_Keys::charity());
}


