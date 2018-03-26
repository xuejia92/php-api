<?php
$dirName = dirname(dirname(__FILE__));

include $dirName.'/common.php';
set_time_limit(0);

$timehi = date('Hi');
$aGame  = Config_Game::$game;

$anteayer  = date("Y-m-d",strtotime('-2 day'));
$yesterday = date("Y-m-d",strtotime('-1 day'));

$stime     = strtotime($yesterday);
$etime     = strtotime("$yesterday 23:59:59 ");
	
$aCid = Base::factory()->getChannel();
$aPid = Base::factory()->getPack();
$aCtype = Config_Game::$clientTyle;

if($timehi != '0350'){
	return false;
}

$aRoomLevels = array(
						'5'=>'match5',
						'6'=>'match6',
						'7'=>'match7',
						'8'=>'match8',
						'9'=>'match9',
						'10'=>'match10',
						'11'=>'match11',
						'12'=>'match12',
	              );

foreach ($aGame as $gameid=>$gameName) {
	if($gameid != 3){
		continue;
	}

	//比赛的台费
	$matchTax         = 0;//Stat::factory()->statLogRoll($gameid,$stime, $etime, array("level>=5","level<=12"),'tax');
		
	//比赛发放
	$mathLostRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount>0"));//比赛金币
	$mathLostRoll = $mathLostRoll+$matchTax;
	//比赛消耗
	$mathWinRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount<0"));//比赛金币
	$mathWinRoll = abs($mathWinRoll+$matchTax);
 
	//比赛金币平衡
	$mathBalance  = $mathWinRoll - $mathLostRoll;
		
	//比赛场
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>163,'amount'=>$mathWinRoll));			//比赛收入金币
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>164,'amount'=>$mathLostRoll));			//比赛发放金币
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>165,'amount'=>$mathBalance));			//比赛金币平衡数

	//某游戏下某场次
	foreach ($aRoomLevels as $roomId=>$roomName) {
		//比赛的台费
		$matchTax            = 0;//Stat::factory()->statLogRoll($gameid,$stime, $etime, array("level>=5","level<=12","amount>0","level=$roomId"),'tax');

		//比赛发放
		$mathLostRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount>0","level=$roomId"));//比赛金币
		$mathLostRoll = $mathLostRoll+$matchTax;
		//比赛消耗
		$mathWinRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount<0","level=$roomId"));//比赛金币
		$mathWinRoll = abs($mathWinRoll+$matchTax);
		//比赛金币平衡
		$mathBalance  = $mathWinRoll - $mathLostRoll;
		
		//比赛场
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>163,'amount'=>$mathWinRoll));			//比赛收入金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>164,'amount'=>$mathLostRoll));			//比赛发放金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>165,'amount'=>$mathBalance));			//比赛金币平衡数
	}
}

foreach ($aGame as $gameid=>$gameName) {
	if($gameid != 3){
		continue;
	}

	//某游戏下某客户端类型
	foreach ($aCtype as $ctype=>$clientname) {
		//比赛的台费
		$matchTax            = 0;//Stat::factory()->statLogRoll($gameid,$stime, $etime, array("level>=5","level<=12","amount>0","ctype=$ctype"),'tax');

		//比赛发放
		$mathLostRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount>0","ctype=$ctype"));//比赛金币
		$mathLostRoll = $mathLostRoll+$matchTax;
		//比赛消耗
		$mathWinRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount<0","ctype=$ctype"));//比赛金币
		$mathWinRoll = abs($mathWinRoll+$matchTax);
		//比赛金币平衡
		$mathBalance  = $mathWinRoll - $mathLostRoll;			
		//比赛场
		Stat::factory()->setStatSum($gameid,array('itemid'=>163,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$mathWinRoll));			//比赛收入金币
		Stat::factory()->setStatSum($gameid,array('itemid'=>164,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$mathLostRoll));			//比赛发放金币
		Stat::factory()->setStatSum($gameid,array('itemid'=>165,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$mathBalance));			//比赛金币平衡数
	}
	//某游戏下某渠道
	foreach ($aCid as $cid=>$cname) {
		//比赛的台费
		$matchTax            = 0;//Stat::factory()->statLogRoll($gameid,$stime, $etime, array("level>=5","level<=12","amount>0","cid=$cid"),'tax');

		//比赛发放
		$mathLostRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount>0","cid=$cid"));//比赛金币
		$mathLostRoll = $mathLostRoll+$matchTax;
		//比赛消耗
		$mathWinRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount<0","cid=$cid"));//比赛金币
		$mathWinRoll = abs($mathWinRoll+$matchTax);
		//比赛金币平衡
		$mathBalance  = $mathWinRoll - $mathLostRoll;
			
		//比赛场
		Stat::factory()->setStatSum($gameid,array('itemid'=>163,'cid'=>$cid,'date'=>$yesterday,'amount'=>$mathWinRoll));			//比赛收入金币
		Stat::factory()->setStatSum($gameid,array('itemid'=>164,'cid'=>$cid,'date'=>$yesterday,'amount'=>$mathLostRoll));			//比赛发放金币
		Stat::factory()->setStatSum($gameid,array('itemid'=>165,'cid'=>$cid,'date'=>$yesterday,'amount'=>$mathBalance));			//比赛金币平衡数
	}

	//某游戏下某客户端类型下某场次
	foreach ($aCtype as $ctype=>$clientname) {
		foreach ($aRoomLevels as $roomId=>$roomName) {
			//比赛的台费
			$matchTax            = 0;//Stat::factory()->statLogRoll($gameid,$stime, $etime, array("level>=5","level<=12","amount>0","ctype=$ctype","level=$roomId"),'tax');
			//比赛发放
			$mathLostRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount>0","ctype=$ctype","level=$roomId"));//比赛金币
			$mathLostRoll = $mathLostRoll+$matchTax;
			//比赛消耗
			$mathWinRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount<0","ctype=$ctype","level=$roomId"));//比赛金币
			$mathWinRoll = abs($mathWinRoll+$matchTax);
			//比赛金币平衡
			$mathBalance  = $mathWinRoll - $mathLostRoll;			
			//比赛场
			Stat::factory()->setStatSum($gameid,array('itemid'=>163,'ctype'=>$ctype,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathWinRoll));			//比赛收入金币
			Stat::factory()->setStatSum($gameid,array('itemid'=>164,'ctype'=>$ctype,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathLostRoll));			//比赛发放金币
			Stat::factory()->setStatSum($gameid,array('itemid'=>165,'ctype'=>$ctype,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathBalance));			//比赛金币平衡数
		}
	}
		
	//某游戏下某渠道 某场次
	foreach ($aCid as $cid=>$cname) {
		foreach ($aRoomLevels as $roomId=>$roomName) {
			//比赛的台费
			$matchTax            = 0;//Stat::factory()->statLogRoll($gameid,$stime, $etime, array("level>=5","level<=12","amount>0","cid=$cid","level=$roomId"),'tax');

			//比赛发放
			$mathLostRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount>0","cid=$cid","level=$roomId"));//比赛金币
			$mathLostRoll = $mathLostRoll+$matchTax;
			//比赛消耗
			$mathWinRoll = Stat::factory()->statLogRoll($gameid,$stime,$etime,array("level>=5","level<=12","amount<0","cid=$cid","level=$roomId"));//比赛金币
			$mathWinRoll = abs($mathWinRoll+$matchTax);
			//比赛金币平衡
			$mathBalance  = $mathWinRoll - $mathLostRoll;
			
			//比赛场
			Stat::factory()->setStatSum($gameid,array('itemid'=>163,'cid'=>$cid,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathWinRoll));			//比赛收入金币
			Stat::factory()->setStatSum($gameid,array('itemid'=>164,'cid'=>$cid,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathLostRoll));			//比赛发放金币
			Stat::factory()->setStatSum($gameid,array('itemid'=>165,'cid'=>$cid,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathBalance));			//比赛金币平衡数
		}
	}

}
