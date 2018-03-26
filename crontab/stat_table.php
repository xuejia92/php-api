<?php
$dirName = dirname(dirname(__FILE__));

include $dirName.'/common.php';
set_time_limit(0);

$timehi = date('Hi');

$anteayer  = date("Y-m-d",strtotime('-2 day'));
$yesterday = date("Y-m-d",strtotime('-1 day'));
	
$stime     = strtotime($yesterday);
$etime     = strtotime("$yesterday 23:59:59 ");
	
$aCid   = Base::factory()->getChannel();
$aPid   = Base::factory()->getPack();
$aCtype = Config_Game::$clientTyle;
$aGame  = Config_Game::$game;

$_stime = microtime(true);
if($timehi == '0100' || $_GET['test']){
	foreach ($aGame as $gameid => $gameName) {
		$aPlayCountBoardidAll = Stat::factory()->getLogPlayCount($gameid,$stime, $etime, array("mid>1000"),"boardid");
		$aPlayFruitAll        = Stat::factory()->getLogPlayCount(5,$stime,$etime,array("gid=$gameid","level=1"),'mid',$gameid);//玩水果机的人数
		$aPlayToradoraAll     = Stat::factory()->getLogPlayCount(5,$stime,$etime,array("gid=$gameid","level=3"),'mid',$gameid);//玩龙虎斗的人数
		$gameid == 4 && $aPlayBaiRen = Stat::factory()->getLogPlayCount($gameid,$stime,$etime,array("level=8","mid>1000"),'mid');//玩斗牛百人场人数
		$gameid == 6 && $aPlayBaiRen = Stat::factory()->getLogPlayCount($gameid,$stime,$etime,array("level=26","mid>1000"),'mid');//玩德州百人场人数
		$gameid == 7 && $aPlayBaiRen = Stat::factory()->getLogPlayCount($gameid,$stime,$etime,array("level=6","mid>1000"),'mid');//玩炸金花百人场人数
		$aPlayHorse           = Stat::factory()->getLogPlayCount(5,$stime,$etime,array("gid=$gameid","level=2"),'mid',$gameid);//玩小马的人数
		//$gameid != 5 && $aPlayBuyu        = Stat::factory()->getLogPlayCount(5,$stime,$etime,array("gid=$gameid","level>=4","level<=11"),'mid',$gameid);//玩捕鱼的人数
		$gameid == 4 && $aPlaySlots       = Stat::factory()->getPlaySlotsCount($gameid, $stime, $etime, array("wmode=31"));//玩翻翻乐人数
		//$gameid == 4 && $aPlaySlotsChange = Stat::factory()->getPlaySlotsCount($gameid, $stime, $etime, array("wmode=32"));//玩翻翻乐换牌人数
		$gameid == 4 && $fflMembers       = Stat::factory()->getLogPlayCount($gameid,$stime,$etime,array("level=5"),'mid');//玩新翻翻乐人数
		
		$gameid == 7 && $aPlayDouble      = Stat::factory()->getLogPlayCount($gameid,$stime, $etime, array("`double`!=0"),"mid");//金花翻倍人数
		$gameid == 7 && $aPlaynmt         = Stat::factory()->getLogPlayCount($gameid,$stime, $etime, array("`nmt`!=0"),"mid");//金花禁比人数
		$gameid == 7 && $aPlayChange      = Stat::factory()->getLogPlayCount($gameid,$stime, $etime, array("`change`!=0"),"mid");//金花换牌人数

		for($id=1;$id<=5;$id++){//计算各场次的牌局信息
			
			if($gameid == 6){
				if($row['level'] <= 6){
					$level = 1;
				}elseif($row['level'] > 6 && $row['level'] <= 12){
					$level = 2;
				}elseif($row['level'] > 12 && $row['level'] <= 21){
					$level = 3;
				}elseif($row['level'] > 22 && $row['level'] <= 24){
					$level = 4;
				}elseif($row['level'] == 25){
					$level = 5;
				}
			}
			
			if($gameid == 5){
				$aPlayCountBoardid = Stat::factory()->getLogPlayCount($gameid,$stime, $etime, array("level=$id","mid>1000","gid=5"),"boardid");
	    		$aPlayCountMid     = Stat::factory()->getLogPlayCount($gameid,$stime, $etime, array("level=$id","mid>1000","gid=5"),"mid",$gameid);
			}else{
				$aPlayCountBoardid = Stat::factory()->getLogPlayCount($gameid,$stime, $etime, array("level=$id","mid>1000"),"boardid");
	    		$aPlayCountMid     = Stat::factory()->getLogPlayCount($gameid,$stime, $etime, array("level=$id","mid>1000"),"mid",$gameid);
			}
			
			foreach ($aCid as $cid=>$cname) {
				$aPlayCountBoardid['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>22,'cid'=>$cid,'roomid'=>$id,'date'=>$yesterday,'amount'=>$aPlayCountBoardid['cid'][$cid]));
				$aPlayCountMid['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>23,'cid'=>$cid,'roomid'=>$id,'date'=>$yesterday,'amount'=>$aPlayCountMid['cid'][$cid]));
				$id==1 && $aPlayCountBoardidAll['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>22,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlayCountBoardidAll['cid'][$cid]));			
				$id==1 && $aPlayFruitAll['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>82,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlayFruitAll['cid'][$cid]));
				$id==1 && $aPlayHorse['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>157,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlayHorse['cid'][$cid]));
				//$id==1 && $aPlayBuyu['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>261,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlayBuyu['cid'][$cid]));
				
				if($gameid == 4 && $id==1){
					$aPlaySlots['cid'][$cid] = $fflMembers['cid'][$cid] + $aPlaySlots['cid'][$cid];
					$aPlaySlots['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>127,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlaySlots['cid'][$cid]));
				}
								
				in_array($gameid, array(4,6,7)) && $id==1 && $aPlayBaiRen['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>129,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlayBaiRen['cid'][$cid]));
				$id==1 && $aPlayToradoraAll['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>185,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlayToradoraAll['cid'][$cid]));
				
				$gameid == 7 && $id==1 && $aPlayDouble['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>298,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlayDouble['cid'][$cid]));
				$gameid == 7 && $id==1 && $aPlaynmt['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>299,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlaynmt['cid'][$cid]));
				$gameid == 7 && $id==1 && $aPlayChange['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>300,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPlayChange['cid'][$cid]));
			}
			foreach ($aCtype as $ctype=>$clientname) {
				$aPlayCountBoardid['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>22,'ctype'=>$ctype,'roomid'=>$id,'date'=>$yesterday,'amount'=>$aPlayCountBoardid['ctype'][$ctype]));
				$aPlayCountMid['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>23,'ctype'=>$ctype,'roomid'=>$id,'date'=>$yesterday,'amount'=>$aPlayCountMid['ctype'][$ctype]));
				$id==1 && $aPlayCountBoardidAll['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>22,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlayCountBoardidAll['ctype'][$ctype]));	
				$id==1 && $aPlayFruitAll['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>82,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlayFruitAll['ctype'][$ctype]));	
				$id==1 && $aPlayHorse['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>157,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlayHorse['ctype'][$ctype]));
				if($gameid == 4 && $id==1){
					$aPlaySlots['ctype'][$ctype] = $fflMembers['ctype'][$ctype] + $aPlaySlots['ctype'][$ctype];
					$aPlaySlots['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>127,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlaySlots['ctype'][$ctype]));
					
				}
				
				//$id==1 && $aPlayBuyu['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>261,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlayBuyu['ctype'][$ctype]));	
				$gameid == 4 && $id==1 && $aPlaySlots['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>127,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlaySlots['ctype'][$ctype]));
				in_array($gameid, array(4,6,7)) && $id==1 && $aPlayBaiRen['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>129,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlayBaiRen['ctype'][$ctype]));
				$id==1 && $aPlayToradoraAll['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>185,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlayToradoraAll['ctype'][$ctype]));
				
				$gameid == 7 && $id==1 && $aPlayDouble['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>298,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlayDouble['ctype'][$ctype]));
				$gameid == 7 && $id==1 && $aPlaynmt['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>299,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlaynmt['ctype'][$ctype]));
				$gameid == 7 && $id==1 && $aPlayChange['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>300,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPlayChange['ctype'][$ctype]));
			}

			$aPlayCountMid['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>23,'roomid'=>$id,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayCountMid['gameid'][$gameid]));
			$aPlayCountBoardid['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>22,'roomid'=>$id,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayCountBoardid['gameid'][$gameid]));
			Logs::factory()->debug(array('id'=>$id,'gameid'=>$gameid,'$aPlayCountMid'=>$aPlayCountMid),'stat_table');
	    }
	    
	    $aPlayDouble['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>298,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayDouble['gameid'][$gameid]));
		$aPlaynmt['gameid'][$gameid]    && Stat::factory()->setStatSum($gameid,array('itemid'=>299,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlaynmt['gameid'][$gameid]));
		$aPlayChange['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>300,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayChange['gameid'][$gameid]));
	    $aPlaySlots['gameid'][$gameid] = $aPlaySlots['gameid'][$gameid] + $fflMembers['gameid'][$gameid];
	    $aPlaySlots['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>127,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlaySlots['gameid'][$gameid]));
	    $aPlaySlotsChange['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>128,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlaySlotsChange['gameid'][$gameid]));
	    $aPlayBaiRen['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>129,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayBaiRen['gameid'][$gameid]));
	    $aPlayCountBoardidAll['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>22,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayCountBoardidAll['gameid'][$gameid]));
	    $aPlayFruitAll['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>82,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayFruitAll['gameid'][$gameid]));
	    $aPlayHorse['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>157,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayHorse['gameid'][$gameid]));
	    $aPlayToradoraAll['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>185,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayToradoraAll['gameid'][$gameid]));
	   	//$aPlayBuyu['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>261,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPlayBuyu['gameid'][$gameid]));
	}
}

$_etime = microtime(true);
Logs::factory()->debug(array('crosstime'=>$_etime-$_stime),'stat_table_time');

if($timehi == '0110' || $_GET['test']){
	
	foreach ($aGame as $gameid=>$gameName) {
		foreach ($aCtype as $ctype=>$cliname){
			if(in_array($ctype,array(1,2,3))){
				$amount = Stat::factory()->getLogPlayAverage($gameid,$stime,$etime,array("ctype=$ctype"));
				$amount[0] && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>15,'amount'=>$amount[0]));
				$amount[1] && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>21,'amount'=>$amount[1]));
				$onlinesum[$gameid] = $amount[0] + (int)$onlinesum[$gameid];
				$playsum[$gameid]   = $amount[1] + (int)$playsum[$gameid];
			}
		}
			
		foreach ($aCid as $cid=>$cname) {
			$amount = Stat::factory()->getLogPlayAverage($gameid,$stime,$etime,array("cid=$cid"));
			$amount[0] && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>15,'amount'=>$amount[0]));
			$amount[1] && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>21,'amount'=>$amount[1]));
		}
		
		$onlinesum[$gameid] && Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>15,'amount'=>$onlinesum[$gameid]));
		$playsum[$gameid]   && Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>21,'amount'=>$playsum[$gameid]));
	}
}


if($timehi == '0530'){
	foreach ($aGame as $gameid=>$gameName) {
		foreach ($aCtype as $ctype=>$cliname){
			if(in_array($ctype,array(1,2,3))){
				$new_register_num  = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'ctype', $ctype);//新增
				$active_num        = Stat::factory()->getAmountFromDB($gameid, 11, $yesterday, 'ctype', $ctype);//活跃
				$dau               = $new_register_num + $active_num;//DAU
				
				$register_pay_num        = Stat::factory()->getAmountFromDB($gameid, 59, $yesterday, 'ctype', $ctype);//日注付费人数
				$register_pay_amount     = Stat::factory()->getAmountFromDB($gameid, 60, $yesterday, 'ctype', $ctype);//日注付费金额
				$register_pay_num && $arppu = sprintf("%.2f", $register_pay_amount/$register_pay_num );
				$arppu && $flag = Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>225,'amount'=>$arppu));//新增ARPPU
				Logs::factory()->debug(array('$register_pay_amount'=>$register_pay_amount,'$register_pay_num'=>$register_pay_num,'flag'=>$flag),'stat_arppu');
				
				$play_count        = Stat::factory()->getAmountFromDB($gameid, 22, $yesterday, 'ctype', $ctype);//新增牌局
				$rate              = sprintf("%.2f", $play_count/$dau );
				$play_count && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>186,'amount'=>$rate));//人均玩牌
				
				$bankruptcy_count  = Stat::factory()->getAmountFromDB($gameid, 71, $yesterday, 'ctype', $ctype);//破产人数
				$rate              = sprintf("%.2f", $bankruptcy_count/$dau *100 ).'%';
				$bankruptcy_count && $flag = Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>187,'amount'=>$rate));//破产率
				Logs::factory()->debug(array('bankruptcy_count'=>$bankruptcy_count,'dau'=>$dau,'rate'=>$rate,'flag'=>$flag),'stat_bankruptcy');
				
				$player_count      = Stat::factory()->getAmountFromDB($gameid, 23, $yesterday, 'ctype', $ctype);//玩牌人数
				$rate              = sprintf("%.2f", $player_count/$dau *100 ).'%';
				$player_count && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>188,'amount'=>$rate));//玩牌率
				
			}
		}
			
		foreach ($aCid as $cid=>$cname) {
			$new_register_num  = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'cid', $cid);//新增
			$active_num        = Stat::factory()->getAmountFromDB($gameid, 11, $yesterday, 'cid', $cid);//活跃
			$dau               = $new_register_num + $active_num;//DAU
			
			$register_pay_num        = Stat::factory()->getAmountFromDB($gameid, 59, $yesterday, 'cid', $cid);//日注付费人数
			$register_pay_amount     = Stat::factory()->getAmountFromDB($gameid, 60, $yesterday, 'cid', $cid);//日注付费金额
			$register_pay_num && $arppu = sprintf("%.2f", $register_pay_amount/$register_pay_num );
			$arppu && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>225,'amount'=>$arppu));//新增ARPPU
							
			$play_count        = Stat::factory()->getAmountFromDB($gameid, 22, $yesterday, 'cid', $cid);//新增牌局
			$rate              = sprintf("%.2f", $play_count/$dau );
			$play_count && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>186,'amount'=>$rate));//人均玩牌率
				
			$bankruptcy_count  = Stat::factory()->getAmountFromDB($gameid, 71, $yesterday, 'cid', $cid);//破产人数
			$rate              = sprintf("%.2f", $bankruptcy_count/$dau *100 ).'%';
			$bankruptcy_count && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>187,'amount'=>$rate));//破产率
				
			$player_count      = Stat::factory()->getAmountFromDB($gameid, 23, $yesterday, 'cid', $cid);//玩牌人数
			$rate              = sprintf("%.2f", $player_count/$dau *100 ).'%';;
			$player_count && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>188,'amount'=>$rate));//玩牌率
		}
		
		$new_register_num  = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'gameid', $gameid);//新增
		$active_num        = Stat::factory()->getAmountFromDB($gameid, 11, $yesterday, 'gameid', $gameid);//活跃
		$dau               = $new_register_num + $active_num;//DAU
		
		$register_pay_num        = Stat::factory()->getAmountFromDB($gameid, 59, $yesterday, 'gameid', $gameid);//日注付费人数
		$register_pay_amount     = Stat::factory()->getAmountFromDB($gameid, 60, $yesterday, 'gameid', $gameid);//日注付费金额
		$register_pay_num && $arppu = sprintf("%.2f", $register_pay_amount/$register_pay_num );
		$arppu && Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>225,'amount'=>$arppu));//新增ARPPU
				
		$play_count        = Stat::factory()->getAmountFromDB($gameid, 22, $yesterday, 'gameid', $gameid);//新增牌局
		$rate              = sprintf("%.2f", $play_count/$dau );
		$play_count && Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>186,'amount'=>$rate));//人均玩牌率
				
		$bankruptcy_count  = Stat::factory()->getAmountFromDB($gameid, 71, $yesterday, 'gameid', $gameid);//破产人数
		$rate              = sprintf("%.2f", $bankruptcy_count/$dau *100 ).'%';;
		$bankruptcy_count && Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>187,'amount'=>$rate));//破产率
				
		$player_count     = Stat::factory()->getAmountFromDB($gameid, 23, $yesterday, 'gameid', $gameid);//玩牌人数
		$rate             = sprintf("%.2f", $player_count/$dau *100 ).'%';;
		$player_count && Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>188,'amount'=>$rate));//玩牌率
	}

}

if($timehi == '0330'){
	foreach ($aGame as $gameid=>$gameName) {
		foreach ($aCtype as $ctype=>$cliname){
			$fishNum     = Stat::factory()->statFishMember($stime,$etime,array("ctype=$ctype"),$gameid);//打鱼人数
			$itemid      = $gameid == 5 ? 322 : 261;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$fishNum));
			$shellNum    = Stat::factory()->statFishSum($stime, $etime, array("ctype=$ctype"), 'shotnum', $gameid);//炮弹总数
			$aveShell    = ceil($shellNum / $fishNum);//人均炮弹数
			$lostMoney   = Stat::factory()->statFishSum($stime, $etime, array("ctype=$ctype"), 'losemoney', $gameid);//消耗金币数
			$aveOneShell = ceil($lostMoney/$shellNum);//单炮弹消耗金币数
			$totalTime   = Stat::factory()->statFishSum($stime, $etime, array("ctype=$ctype"), 'etime-stime', $gameid);//打鱼总时间	
			$aveFishTime = ceil($totalTime/$fishNum);//人均打鱼时间
			$itemid      = $gameid == 5 ? 323 : 327;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$shellNum));
			$itemid      = $gameid == 5 ? 324 : 328;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aveShell));
			$itemid      = $gameid == 5 ? 325 : 329;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aveOneShell));
			$itemid      = $gameid == 5 ? 326 : 330;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aveFishTime));	

			if($gameid == 5){
				for($roomid=8;$roomid<=10;$roomid++){
					$fishNum = Stat::factory()->statFishMember($stime,$etime,array("ctype=$ctype","roomid=$roomid"),5);
					if(!$fishNum){
						continue;
					}
					$fishNum && Stat::factory()->setStatSum($gameid,array('itemid'=>322,'ctype'=>$ctype,'roomid'=>$roomid,'date'=>$yesterday,'amount'=>$fishNum));
					
					$shellNum    = Stat::factory()->statFishSum($stime, $etime, array("ctype=$ctype","roomid=$roomid"), 'shotnum', $gameid);//炮弹总数
					$aveShell    = ceil($shellNum / $fishNum);//人均炮弹数
					$lostMoney   = Stat::factory()->statFishSum($stime, $etime, array("ctype=$ctype","roomid=$roomid"), 'losemoney', $gameid);//消耗金币数
					$aveOneShell = ceil($lostMoney/$shellNum);//单炮弹消耗金币数
					Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>323,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$shellNum));
					Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>324,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aveShell));
					Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>325,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aveOneShell));
				}
			}
		}
			
		foreach ($aCid as $cid=>$cname) {
			$fishNum     = Stat::factory()->statFishMember($stime,$etime,array("cid=$cid"),$gameid);//打鱼人数
			if(!$fishNum){
				continue;
			}
			$itemid      = $gameid == 5 ? 322 : 261;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$fishNum));
			$shellNum    = Stat::factory()->statFishSum($stime, $etime, array("cid=$cid"), 'shotnum', $gameid);//炮弹总数
			$fishNum && $aveShell    = ceil($shellNum / $fishNum);//人均炮弹数
			$lostMoney   = Stat::factory()->statFishSum($stime, $etime, array("cid=$cid"), 'losemoney', $gameid);//消耗金币数
			$aveOneShell = ceil($lostMoney/$shellNum);//单炮弹消耗金币数
			$totalTime   = Stat::factory()->statFishSum($stime, $etime, array("cid=$cid"), 'etime-stime', $gameid);//打鱼总时间	
			$aveFishTime = ceil($totalTime/$fishNum);//人均打鱼时间
			$itemid      = $gameid == 5 ? 323 : 327;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$shellNum));
			$itemid      = $gameid == 5 ? 324 : 328;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aveShell));
			$itemid      = $gameid == 5 ? 325 : 329;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aveOneShell));
			$itemid      = $gameid == 5 ? 326 : 330;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aveFishTime));
			if($gameid == 5){
				for($roomid=8;$roomid<=10;$roomid++){
					$fishNum = Stat::factory()->statFishMember($stime,$etime,array("cid=$cid","roomid=$roomid"),5);
					if(!$fishNum){
						continue;
					}
					$fishNum && Stat::factory()->setStatSum($gameid,array('itemid'=>322,'cid'=>$cid,'roomid'=>$roomid,'date'=>$yesterday,'amount'=>$fishNum));
					
					$shellNum    = Stat::factory()->statFishSum($stime, $etime, array("cid=$cid","roomid=$roomid"), 'shotnum', $gameid);//炮弹总数
					$fishNum && $aveShell    = ceil($shellNum / $fishNum);//人均炮弹数
					$lostMoney   = Stat::factory()->statFishSum($stime, $etime, array("cid=$cid","roomid=$roomid"), 'losemoney', $gameid);//消耗金币数
					$aveOneShell = ceil($lostMoney/$shellNum);//单炮弹消耗金币数
					Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>323,'cid'=>$cid,'date'=>$yesterday,'amount'=>$shellNum));
					Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>324,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aveShell));
					Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>325,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aveOneShell));
				}
			}
		}
				
		$fishNum     = Stat::factory()->statFishMember($stime,$etime,array(),$gameid);//打鱼人数
		$itemid      = $gameid == 5 ? 322 : 261;
		Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$fishNum));
		$shellNum    = Stat::factory()->statFishSum($stime, $etime, array(), 'shotnum', $gameid);//炮弹总数
		$fishNum && $aveShell    = ceil($shellNum / $fishNum);//人均炮弹数
		$lostMoney   = Stat::factory()->statFishSum($stime, $etime, array(), 'losemoney', $gameid);//消耗金币数
		$aveOneShell = ceil($lostMoney/$shellNum);//单炮弹消耗金币数
		$totalTime   = Stat::factory()->statFishSum($stime, $etime, array(), 'etime-stime', $gameid);//打鱼总时间	
		$aveFishTime = ceil($totalTime/$fishNum);//人均打鱼时间
		$itemid      = $gameid == 5 ? 323 : 327;
		Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$shellNum));
		$itemid      = $gameid == 5 ? 324 : 328;
		Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aveShell));
		$itemid      = $gameid == 5 ? 325 : 329;
		Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aveOneShell));
		$itemid      = $gameid == 5 ? 326 : 330;
		Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aveFishTime));

		if($gameid == 5){
			for($roomid=8;$roomid<=10;$roomid++){
				$fishNum = Stat::factory()->statFishMember($stime,$etime,array("roomid=$roomid"),5);
				if(!$fishNum){
					continue;
				}
				$fishNum && Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>322,'ctype'=>$ctype,'roomid'=>$roomid,'date'=>$yesterday,'amount'=>$fishNum));
					
				$shellNum    = Stat::factory()->statFishSum($stime, $etime, array("roomid=$roomid"), 'shotnum', $gameid);//炮弹总数
				$aveShell    = ceil($shellNum / $fishNum);//人均炮弹数
				$lostMoney   = Stat::factory()->statFishSum($stime, $etime, array("roomid=$roomid"), 'losemoney', $gameid);//消耗金币数
				$aveOneShell = ceil($lostMoney/$shellNum);//单炮弹消耗金币数
				Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>323,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$shellNum));
				Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>324,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aveShell));
				Stat::factory()->setStatSum($gameid,array('roomid'=>$roomid,'itemid'=>325,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aveOneShell));
			}
		}
	}

}

//换表
if($timehi == '0630'){
	foreach ($aGame as $gameid=>$gameName) {
		Logs::factory()->changeTableLogMember($gameid);//更换logmember表
		Logs::factory()->changeTableLogwin($gameid);//更换logwin表
		Logs::factory()->changeTableLogRoll($gameid);//更换logroll表
	}
	
}
