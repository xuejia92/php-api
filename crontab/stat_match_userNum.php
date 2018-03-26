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

if($timehi != '0400'){
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

	//参与人数
	$matchUserNum = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000"),'mid');
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>159,'amount'=>$matchUserNum));
	//参与人次
	$matchUserNum2 = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000"),'mid',false);
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>166,'amount'=>$matchUserNum2));
	

	//某游戏下某场次
	foreach ($aRoomLevels as $roomId=>$roomName) {
		//参与人数
		$matchUserNum = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","level=$roomId"),'mid');
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>159,'amount'=>$matchUserNum));

		//参与人次
		$matchUserNum2 = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","level=$roomId"),'mid',false);
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>166,'amount'=>$matchUserNum2));
		
		$inning = Loader_Redis::push()->hGet("competnum", "level".$roomId);//比赛场的举行场次
		$inning_count = (int)$inning_count + $inning;
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>158,'amount'=>$inning));
	}
	
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>158,'amount'=>$inning_count));//比赛场的举行场次
}

foreach ($aGame as $gameid=>$gameName) {
	if($gameid != 3){
		continue;
	}

	//某游戏下某客户端类型
	foreach ($aCtype as $ctype=>$clientname) {
		//参与人数
		$matchUserNum = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","ctype=$ctype"),'mid');
		Stat::factory()->setStatSum($gameid,array('itemid'=>159,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$matchUserNum ));

		//参与人次
		$matchUserNum2 = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","ctype=$ctype"),'mid',false);
		Stat::factory()->setStatSum($gameid,array('itemid'=>166,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$matchUserNum2));
	}
	//某游戏下某渠道
	foreach ($aCid as $cid=>$cname) {
		//参与人数
		$matchUserNum = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","cid=$cid"),'mid');
		Stat::factory()->setStatSum($gameid,array('itemid'=>159,'cid'=>$cid,'date'=>$yesterday,'amount'=>$matchUserNum));

		//参与人次
		$matchUserNum2 = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","cid=$cid"),'mid',false);
		Stat::factory()->setStatSum($gameid,array('itemid'=>166,'cid'=>$cid,'date'=>$yesterday,'amount'=>$matchUserNum2));
	}
			
	//某游戏下某客户端类型下某场次
	foreach ($aCtype as $ctype=>$clientname) {
		foreach ($aRoomLevels as $roomId=>$roomName) {
			//参与人数
			$matchUserNum = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","ctype=$ctype","level=$roomId"),'mid');
			Stat::factory()->setStatSum($gameid,array('itemid'=>159,'ctype'=>$ctype,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$matchUserNum));

			//参与人次
			$matchUserNum2 = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","ctype=$ctype","level=$roomId"),'mid',false);
			Stat::factory()->setStatSum($gameid,array('itemid'=>166,'ctype'=>$ctype,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$matchUserNum2));
		}
	}
		
	//某游戏下某渠道 某场次
	foreach ($aCid as $cid=>$cname) {
		foreach ($aRoomLevels as $roomId=>$roomName) {
			//参与人数
			$matchUserNum = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","cid=$cid","level=$roomId"),'mid');
			Stat::factory()->setStatSum($gameid,array('itemid'=>159,'cid'=>$cid,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$matchUserNum));

			//参与人次
			$matchUserNum2 = Stat::factory()->statCount($gameid,$stime, $etime, array("level>=5","level<=12","mid>1000","cid=$cid","level=$roomId"),'mid',false);
			Stat::factory()->setStatSum($gameid,array('itemid'=>166,'cid'=>$cid,'roomid'=>$roomId,'date'=>$yesterday,'amount'=>$matchUserNum2));
		}
	}
}

