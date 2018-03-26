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

if($timehi != '0340'){
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

	//比赛发放
	$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("ctype=30","level>=5","level<=12","money>0"),'tax');
	$mathRobotWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","level>=5","level<=12","money>0"));
	$mathRobotWinMoney = $mathRobotWinMoney+$matchTax;

	//比赛消耗
	$matchTax            = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("ctype=30","level>=5","level<=12","money<0"),'tax');
	$mathRobotLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","level>=5","level<=12","money<0"));
	$mathRobotLostMoney = abs($mathRobotLostMoney+$matchTax);
 
	//比赛金币平衡
	$mathBalance  = $mathRobotWinMoney - $mathRobotLostMoney ;
		
	//比赛场
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>167,'amount'=>$mathRobotWinMoney));			//比赛收入金币
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>168,'amount'=>$mathRobotLostMoney));		//比赛发放金币
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>169,'amount'=>$mathBalance));			//比赛金币平衡数

	//某游戏下某场次
	foreach ($aRoomLevels as $roomId=>$roomName) {
		//比赛发放
		$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("ctype=30","money>0","level=$roomId"),'tax');
		$mathRobotWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","money>0","level=$roomId"));//比赛金币
		$mathRobotWinMoney = $mathRobotWinMoney+$matchTax;

		//比赛消耗
		$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("ctype=30","money<0","level=$roomId"),'tax');
		$mathRobotLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","money<0","level=$roomId"));//比赛金币
		$mathRobotLostMoney = abs($mathRobotLostMoney+$matchTax);
		//比赛金币平衡
		$mathBalance  = $mathRobotWinMoney-$mathRobotLostMoney  ;
		
		//比赛场
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>167,'amount'=>$mathRobotLostMoney));//比赛收入金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>168,'amount'=>$mathRobotWinMoney));//比赛发放金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>169,'amount'=>$mathBalance));	//比赛金币平衡数
	}
}


foreach ($aGame as $gameid=>$gameName) {
	if($gameid != 3){
		continue;
	}

	//某游戏下某客户端类型
	foreach ($aCtype as $ctype=>$clientname) {
		//比赛发放
		$matchTax          = 0 ;// Stat::factory()->statLogPlay($gameid,$stime, $etime, array("level>=5","level<=12","money>0","ctype=$ctype"),'tax');
		$mathRobotWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid<=1000","level>=5","level<=12","money>0","ctype=$ctype"));//比赛金币
		$mathRobotWinMoney = $mathRobotWinMoney+$matchTax;

		//比赛消耗
		$matchTax           = 0 ;//Stat::factory()->statLogPlay($gameid,$stime, $etime, array("level>=5","level<=12","money<0","ctype=$ctype"),'tax');
		$mathRobotLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid<=1000","level>=5","level<=12","money<0","ctype=$ctype"));//比赛金币
		$mathRobotLostMoney = abs($mathRobotLostMoney+$matchTax);
		//比赛金币平衡
		$mathBalance  = $mathRobotWinMoney-$mathRobotLostMoney ;		
		//比赛场
		Stat::factory()->setStatSum($gameid,array('itemid'=>167,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$mathRobotLostMoney));			//比赛收入金币
		Stat::factory()->setStatSum($gameid,array('itemid'=>168,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$mathRobotWinMoney));			//比赛发放金币
		Stat::factory()->setStatSum($gameid,array('itemid'=>169,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$mathBalance));			//比赛金币平衡数
	}
	//某游戏下某渠道
	foreach ($aCid as $cid=>$cname) {
		//比赛发放
		$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("ctype=30","level>=5","level<=12","money>0","cid=$cid"),'tax');
		$mathRobotWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","level>=5","level<=12","money>0","cid=$cid"));//比赛金币
		$mathRobotWinMoney = $mathRobotWinMoney+$matchTax;
			
		//比赛消耗
		$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("ctype=30","level>=5","level<=12","money<0","cid=$cid"),'tax');
		$mathRobotLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","level>=5","level<=12","money<0","cid=$cid"));//比赛金币
		$mathRobotLostMoney = abs($mathRobotLostMoney+$matchTax);
		//比赛金币平衡
		$mathBalance  = $mathRobotWinMoney-$mathRobotLostMoney ;
			
		//比赛场
		Stat::factory()->setStatSum($gameid,array('itemid'=>167,'cid'=>$cid,'date'=>$yesterday,'amount'=>$mathRobotLostMoney));			//比赛收入金币
		Stat::factory()->setStatSum($gameid,array('itemid'=>168,'cid'=>$cid,'date'=>$yesterday,'amount'=>$mathRobotWinMoney));			//比赛发放金币
		Stat::factory()->setStatSum($gameid,array('itemid'=>169,'cid'=>$cid,'date'=>$yesterday,'amount'=>$mathBalance));			//比赛金币平衡数
	}
	
		
	//某游戏下某客户端类型下某场次
	foreach ($aCtype as $ctype=>$clientname) {
		
		foreach ($aRoomLevels as $roomId=>$roomName) {
			//比赛发放
			$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid<=1000","money>0","ctype=$ctype","level=$roomId"),'tax');
			$mathRobotWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid<=1000","money>0","ctype=$ctype","level=$roomId"));//比赛金币
			$mathRobotWinMoney = $mathRobotWinMoney+$matchTax;
				
			//比赛消耗
			$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid<=1000","money<0","ctype=$ctype","level=$roomId"),'tax');
			$mathRobotLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","money<0","ctype=$ctype","level=$roomId"));//比赛金币
			$mathRobotLostMoney = abs($mathRobotLostMoney+$matchTax);
			//比赛金币平衡
			$mathBalance  = $mathRobotWinMoney-$mathRobotLostMoney ;			
			//比赛场
			Stat::factory()->setStatSum($gameid,array('itemid'=>167,'ctype'=>$ctype,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathRobotLostMoney));//比赛收入金币
			Stat::factory()->setStatSum($gameid,array('itemid'=>168,'ctype'=>$ctype,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathRobotWinMoney));//比赛发放金币
			Stat::factory()->setStatSum($gameid,array('itemid'=>169,'ctype'=>$ctype,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathBalance));//比赛金币平衡数
		}
	}
		
	//某游戏下某渠道 某场次
	foreach ($aCid as $cid=>$cname) {
		foreach ($aRoomLevels as $roomId=>$roomName) {
			//比赛发放
			$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("ctype=30","money>0","cid=$cid","level=$roomId"),'tax');
			$mathRobotWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","money>0","cid=$cid","level=$roomId"));//比赛金币
			$mathRobotWinMoney = $mathRobotWinMoney+$matchTax;
				
			//比赛消耗
			$matchTax           = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("ctype=30","money<0","cid=$cid","level=$roomId"),'tax');
			$mathRobotLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype=30","money<0","cid=$cid","level=$roomId"));//比赛金币
			$mathRobotLostMoney = abs($mathRobotLostMoney+$matchTax);
				
			//比赛金币平衡
			$mathBalance  = $mathRobotWinMoney-$mathRobotLostMoney;
			
			//比赛场
			Stat::factory()->setStatSum($gameid,array('itemid'=>167,'cid'=>$cid,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathRobotLostMoney));//比赛收入金币
			Stat::factory()->setStatSum($gameid,array('itemid'=>168,'cid'=>$cid,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathRobotWinMoney));//比赛发放金币
			Stat::factory()->setStatSum($gameid,array('itemid'=>169,'cid'=>$cid,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$mathBalance));//比赛金币平衡数
		}
	}
		
}
