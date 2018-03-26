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

if($timehi != '0330'){
	return false;
}

foreach ($aGame as $gameid=>$gameName) {
	if($gameid != 3){
		continue;
	}

	//比赛奖励发放
	$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","level>=5","level<=12","money>0"));
	//牌局发放
	$mathLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype<20","ctype!=30","level>=5","level<=12","money>0"));
	
	//玩家报名消耗
	$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","ctype!=30","level>=5","level<=12"));
	$matchBaoMing = abs($matchBaoMing);
	//台费消耗
	$mathTax = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype!=30","level>=5","level<=12"),"tax");
	$mathTax = abs($mathTax);
	
	//牌局消耗
	$mathWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("tax!=0","ctype!=30","level>=5","level<=12","money<0"));
	$mathWinMoney = abs($mathWinMoney);
	$mathWinMoney = $mathWinMoney-$mathTax;
	
	//比赛金币平衡
	$mathBalance  =$mathTax+$matchBaoMing+$mathWinMoney - $mathLostMoney-$matchLostJiangli;
			
	//比赛场
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>170,'amount'=>$matchBaoMing));//报名收入
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>160,'amount'=>$mathWinMoney));//牌局收入金币
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>174,'amount'=>$mathTax));//台费收入
			
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>161,'amount'=>$mathLostMoney));//牌局发放金币
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>171,'amount'=>$matchLostJiangli));//比赛奖励发放 $matchLostJiangli
	
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>172,'amount'=>$mathTax+$matchBaoMing+$mathWinMoney));//比赛收入金币
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>173,'amount'=>$mathLostMoney+$matchLostJiangli));//比赛发放金币
	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>162,'amount'=>$mathBalance));//比赛金币平衡数
	
	//某游戏下某场次
	foreach ($aRoomLevels as $roomId=>$roomName) {
	
		//比赛奖励发放
		$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","ctype!=30","level=$roomId","money>0"));
		//牌局发放
		$mathLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype<20","ctype!=30","level=$roomId","money>0"));
	
		//玩家报名消耗
		$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","ctype!=30","level=$roomId"));
		$matchBaoMing = abs($matchBaoMing);
		 //台费消耗
		$mathTax = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("ctype!=30","level=$roomId"),"tax");
		$mathTax = abs($mathTax);
	
		 //牌局消耗
		$mathWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("tax!=0","ctype!=30","level=$roomId","money<0"));
		$mathWinMoney = abs($mathWinMoney);
		$mathWinMoney = $mathWinMoney-$mathTax;
	
		//比赛金币平衡
		$mathBalance  =$mathTax+$matchBaoMing+$mathWinMoney - $mathLostMoney-$matchLostJiangli;
	
		//比赛场
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>170,'amount'=>$matchBaoMing));			//报名收入
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>160,'amount'=>$mathWinMoney));			//牌局收入金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>174,'amount'=>$mathTax));			//台费收入
			
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>161,'amount'=>$mathLostMoney));		//牌局发放金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>171,'amount'=>$matchLostJiangli));		//比赛奖励发放 $matchLostJiangli
	
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>172,'amount'=>$mathTax+$matchBaoMing+$mathWinMoney));     //比赛收入金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>173,'amount'=>$mathLostMoney+$matchLostJiangli));  //比赛发放金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'roomid'=>$roomId,'itemid'=>162,'amount'=>$mathBalance));			         //比赛金币平衡数
		}
	}

	foreach ($aGame as $gameid=>$gameName) {
		if($gameid != 3){
			continue;
		}

		//某游戏下某客户端类型
		foreach ($aCtype as $ctype=>$clientname) {

			//比赛奖励发放
			$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","mid>1000","level>=5","level<=12","money>0","ctype=$ctype"));
			//牌局发放
			$mathLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype<20","mid>1000","level>=5","level<=12","money>0","ctype=$ctype"));


			//玩家报名消耗
			$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","mid>1000","level>=5","level<=12","ctype=$ctype"));
			$matchBaoMing = abs($matchBaoMing);
	 		//台费消耗
	 		$mathTax = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid>1000","level>=5","level<=12","ctype=$ctype"),"tax");
	 		$mathTax = abs($mathTax);

	 		//牌局消耗
			$mathWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("tax!=0","mid>1000","level>=5","level<=12","money<0","ctype=$ctype"));
			$mathWinMoney = abs($mathWinMoney);
			$mathWinMoney = $mathWinMoney-$mathTax;

			//比赛金币平衡
			$mathBalance  =$mathTax+$matchBaoMing+$mathWinMoney - $mathLostMoney-$matchLostJiangli;


			//比赛场
			Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'itemid'=>170,'amount'=>$matchBaoMing));//报名收入
			Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'itemid'=>160,'amount'=>$mathWinMoney));//牌局收入金币
			Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'itemid'=>174,'amount'=>$mathTax));//台费收入
		
			Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'itemid'=>161,'amount'=>$mathLostMoney));//牌局发放金币
	    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'itemid'=>171,'amount'=>$matchLostJiangli));//比赛奖励发放 $matchLostJiangli

	    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'itemid'=>172,'amount'=>$mathTax+$matchBaoMing+$mathWinMoney));//比赛收入金币
	    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'itemid'=>173,'amount'=>$mathLostMoney+$matchLostJiangli));//比赛发放金币
	    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'itemid'=>162,'amount'=>$mathBalance));//比赛金币平衡数
		}

		//某游戏下某渠道
		foreach ($aCid as $cid=>$cname) {

			//比赛奖励发放
			$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","mid>1000","level>=5","level<=12","money>0","cid=$cid"));
			//牌局发放
			$mathLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype<20","mid>1000","level>=5","level<=12","money>0","cid=$cid"));


			//玩家报名消耗
			$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","mid>1000","level>=5","level<=12","cid=$cid"));
			$matchBaoMing = abs($matchBaoMing);
	 		//台费消耗
	 		$mathTax = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid>1000","level>=5","level<=12","cid=$cid"),"tax");
	 		$mathTax = abs($mathTax);

	 		//牌局消耗
			$mathWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("tax!=0","mid>1000","level>=5","level<=12","money<0","cid=$cid"));
			$mathWinMoney = abs($mathWinMoney);
			$mathWinMoney = $mathWinMoney-$mathTax;

			//比赛金币平衡
			$mathBalance  =$mathTax+$matchBaoMing+$mathWinMoney - $mathLostMoney-$matchLostJiangli;

			//比赛场
			Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'itemid'=>170,'amount'=>$matchBaoMing));//报名收入
			Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'itemid'=>160,'amount'=>$mathWinMoney));//牌局收入金币
			Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'itemid'=>174,'amount'=>$mathTax));//台费收入
		
			Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'itemid'=>161,'amount'=>$mathLostMoney));//牌局发放金币
	    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'itemid'=>171,'amount'=>$matchLostJiangli));//比赛奖励发放 $matchLostJiangli

	    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'itemid'=>172,'amount'=>$mathTax+$matchBaoMing+$mathWinMoney));//比赛收入金币
	    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'itemid'=>173,'amount'=>$mathLostMoney+$matchLostJiangli));//比赛发放金币
	    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'itemid'=>162,'amount'=>$mathBalance));//比赛金币平衡数
		}

		
		//某游戏下某客户端类型下某场次
		foreach ($aCtype as $ctype=>$clientname) {
		
			foreach ($aRoomLevels as $roomId=>$roomName) {		
				//比赛奖励发放
				$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","mid>1000","money>0","ctype=$ctype","level=$roomId"));
				//牌局发放
				$mathLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype<20","mid>1000","money>0","ctype=$ctype","level=$roomId"));


				//玩家报名消耗
				$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","mid>1000","ctype=$ctype","level=$roomId"));
				$matchBaoMing = abs($matchBaoMing);
		 		//台费消耗
		 		$mathTax = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid>1000","ctype=$ctype","level=$roomId"),"tax");
		 		$mathTax = abs($mathTax);

		 		//牌局消耗
				$mathWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("tax!=0","mid>1000","money<0","ctype=$ctype","level=$roomId"));
				$mathWinMoney = abs($mathWinMoney);
				$mathWinMoney = $mathWinMoney-$mathTax;

				//比赛金币平衡
				$mathBalance  =$mathTax+$matchBaoMing+$mathWinMoney - $mathLostMoney-$matchLostJiangli;


				//比赛场
				Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'roomid'=>$roomId,'itemid'=>170,'amount'=>$matchBaoMing));			//报名收入
				Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'roomid'=>$roomId,'itemid'=>160,'amount'=>$mathWinMoney));			//牌局收入金币
				Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'roomid'=>$roomId,'itemid'=>174,'amount'=>$mathTax));			//台费收入
			
				Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'roomid'=>$roomId,'itemid'=>161,'amount'=>$mathLostMoney));		//牌局发放金币
		    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'roomid'=>$roomId,'itemid'=>171,'amount'=>$matchLostJiangli));		//比赛奖励发放 $matchLostJiangli

		    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'roomid'=>$roomId,'itemid'=>172,'amount'=>$mathTax+$matchBaoMing+$mathWinMoney));     //比赛收入金币
		    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'roomid'=>$roomId,'itemid'=>173,'amount'=>$mathLostMoney+$matchLostJiangli));  //比赛发放金币
		    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'ctype'=>$ctype,'roomid'=>$roomId,'itemid'=>162,'amount'=>$mathBalance));			         //比赛金币平衡数
			}
		}
		
		//某游戏下某渠道 某场次
		foreach ($aCid as $cid=>$cname) {
			foreach ($aRoomLevels as $roomId=>$roomName) {
			
				//比赛奖励发放
				$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","mid>1000","money>0","cid=$cid","level=$roomId"));
				//牌局发放
				$mathLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype<20","mid>1000","money>0","cid=$cid","level=$roomId"));


				//玩家报名消耗
				$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","mid>1000","cid=$cid","level=$roomId"));
				$matchBaoMing = abs($matchBaoMing);
		 		//台费消耗
		 		$mathTax = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid>1000","cid=$cid","level=$roomId"),"tax");
		 		$mathTax = abs($mathTax);

		 		//牌局消耗
				$mathWinMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("tax!=0","mid>1000","money<0","cid=$cid","level=$roomId"));
				$mathWinMoney = abs($mathWinMoney);
				$mathWinMoney = $mathWinMoney-$mathTax;

				//比赛金币平衡
				$mathBalance  =$mathTax+$matchBaoMing+$mathWinMoney - $mathLostMoney-$matchLostJiangli;

				//比赛场
				Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'roomid'=>$roomId,'itemid'=>170,'amount'=>$matchBaoMing));			//报名收入
				Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'roomid'=>$roomId,'itemid'=>160,'amount'=>$mathWinMoney));			//牌局收入金币
				Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'roomid'=>$roomId,'itemid'=>174,'amount'=>$mathTax));			//台费收入
			
				Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'roomid'=>$roomId,'itemid'=>161,'amount'=>$mathLostMoney));		//牌局发放金币
		    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'roomid'=>$roomId,'itemid'=>171,'amount'=>$matchLostJiangli));		//比赛奖励发放 $matchLostJiangli

		    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'roomid'=>$roomId,'itemid'=>172,'amount'=>$mathTax+$matchBaoMing+$mathWinMoney));     //比赛收入金币
		    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'roomid'=>$roomId,'itemid'=>173,'amount'=>$mathLostMoney+$matchLostJiangli));  //比赛发放金币
		    	Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'cid'=>$cid,'roomid'=>$roomId,'itemid'=>162,'amount'=>$mathBalance));			         //比赛金币平衡数
			}
		}
	}
