<?php
$dirName = dirname(dirname(__FILE__));

die();

include $dirName.'/common.php';
error_reporting(E_ALL ^ E_NOTICE);
set_time_limit(0);

$timehi = date('Hi');
$aGame  = Config_Game::$game;

$anteayer  = date("Y-m-d",strtotime('-2 day'));
$yesterday = date("Y-m-d",strtotime('-1 day'));
$day = date("Y-m-d",strtotime("-2 day"));
$stime     = strtotime($yesterday);
$etime     = strtotime("$yesterday 23:59:59 ");
	
$aCid = Base::factory()->getChannel();
$aPid = Base::factory()->getPack();
$aCtype = Config_Game::$clientTyle;



foreach ($aGame as $gameid=>$gameName) {
		foreach ($aCtype as $ctype=>$cliname){
			$fishNum     = Stat::factory()->statFishMember($stime,$etime,array("ctype=$ctype"),$gameid);//打鱼人数
			$itemid      = $gameid == 5 ? 322 : 261;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$fishNum));
			$shellNum    = Stat::factory()->statFishSum($stime, $etime, array("ctype=$ctype"), 'shotnum', $gameid);//炮弹总数
			$aveShell    = ceil($shellNum / $fishNum);//人均炮弹数
			$lostMoney   = Stat::factory()->statFishSum($stime, $etime, array("ctype=$ctype"), 'losemoney', $gameid);//消耗金币数
			$aveOneShell = ceil($lostMoney/$shellNum);//单炮弹消耗金币数
			$itemid      = $gameid == 5 ? 323 : 327;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$shellNum));
			$itemid      = $gameid == 5 ? 324 : 328;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aveShell));
			$itemid      = $gameid == 5 ? 325 : 329;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aveOneShell));

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
			$itemid      = $gameid == 5 ? 323 : 327;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$shellNum));
			$itemid      = $gameid == 5 ? 324 : 328;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aveShell));
			$itemid      = $gameid == 5 ? 325 : 329;
			Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aveOneShell));

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
		$itemid      = $gameid == 5 ? 323 : 327;
		Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$shellNum));
		$itemid      = $gameid == 5 ? 324 : 328;
		Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aveShell));
		$itemid      = $gameid == 5 ? 325 : 329;
		Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aveOneShell));

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


die();

/*
foreach ($aGame as $gameid=>$gname) {
	echo $sql  = "SELECT mid FROM log_register$gameid";
	$rows = Loader_Mysql::DBLogchip()->getAll($sql);
	
	foreach ($rows as $row) {
		$mid = $row['mid'];
		$date           = date("Y-m-d",NOW);
		Loader_Redis::account()->rPush(Config_Keys::dayreg($gameid,$date), $mid, false, false);
	}
}


*/
die();

if(1){
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






die();
if(1){
    $yesterday = date("Y-m-d",strtotime("-1 day"));
    $counting = Stat::factory()->payUserByMid();

    foreach ($counting as $itemid=>$array) {
        foreach ($aGame as $gameid=>$gameName) {
            if ($itemid==244){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>244,'amount'=>$counting['244'][$gameid]['gameid']));
            }
            
            if ($itemid==245){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>245,'amount'=>$counting['245'][$gameid]['gameid']));
                if ($counting['246'][$gameid]['gameid']){
                    $pay_keep_rate = sprintf("%.2f", $counting['245'][$gameid]['gameid'] / $counting['246'][$gameid]['gameid'] * 100).'%';
                    Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>246,'amount'=>$pay_keep_rate));
                }
            }
            
            if ($itemid==247){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>247,'amount'=>$counting['247'][$gameid]['gameid']));
                if ($counting['248'][$gameid]['gameid']){
                    $pay_keep_rate = sprintf("%.2f", $counting['247'][$gameid]['gameid'] / $counting['248'][$gameid]['gameid'] * 100).'%';
                    Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>248,'amount'=>$pay_keep_rate));
                }
            }
            
            if ($itemid==249){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>249,'amount'=>$counting['249'][$gameid]['gameid']));
                if ($counting['250'][$gameid]['gameid']){
                    $pay_keep_rate = sprintf("%.2f", $counting['249'][$gameid]['gameid'] / $counting['250'][$gameid]['gameid'] * 100).'%';
                    Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>250,'amount'=>$pay_keep_rate));
                }
            }
            
            if ($itemid==251){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>251,'amount'=>$counting['251'][$gameid]['gameid']));
                if ($counting['252'][$gameid]['gameid']){
                    $pay_keep_rate = sprintf("%.2f", $counting['251'][$gameid]['gameid'] / $counting['252'][$gameid]['gameid'] * 100).'%';
                    Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>252,'amount'=>$pay_keep_rate));
                }
            }
            
            /*
            foreach ($aCid as $cid=>$cname){
                if ($itemid==244){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>244,'amount'=>$counting['244'][$gameid]['cid'][$cid]));
                }
                
                if ($itemid==245){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>245,'amount'=>$counting['245'][$gameid]['cid'][$cid]));
                    if ($counting['246'][$gameid]['cid'][$cid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['245'][$gameid]['cid'][$cid] / $counting['246'][$gameid]['cid'][$cid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>246,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==247){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>247,'amount'=>$counting['247'][$gameid]['cid'][$cid]));
                    if ($counting['248'][$gameid]['cid'][$cid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['247'][$gameid]['cid'][$cid] / $counting['248'][$gameid]['cid'][$cid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>248,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==249){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>249,'amount'=>$counting['249'][$gameid]['cid'][$cid]));
                    if ($counting['250'][$gameid]['cid'][$cid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['249'][$gameid]['cid'][$cid] / $counting['250'][$gameid]['cid'][$cid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>250,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==251){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>251,'amount'=>$counting['251'][$gameid]['cid'][$cid]));
                    if ($counting['252'][$gameid]['cid'][$cid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['251'][$gameid]['cid'][$cid] / $counting['252'][$gameid]['cid'][$cid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>252,'amount'=>$pay_keep_rate));
                    }
                }
            }
            */
            
            foreach (Config_Game::$clientTyle as $ctype=>$clientname) {
                if ($itemid==244){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>244,'amount'=>$counting['244'][$gameid]['ctype'][$ctype]));
                }
                
                if ($itemid==245){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>245,'amount'=>$counting['245'][$gameid]['ctype'][$ctype]));
                    if ($counting['246'][$gameid]['ctype'][$ctype]){
                        $pay_keep_rate = sprintf("%.2f", $counting['245'][$gameid]['ctype'][$ctype] / $counting['246'][$gameid]['ctype'][$ctype] * 100).'%';
                        var_dump($pay_keep_rate);
                        Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>246,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==247){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>247,'amount'=>$counting['247'][$gameid]['ctype'][$ctype]));
                    if ($counting['248'][$gameid]['ctype'][$ctype]){
                        $pay_keep_rate = sprintf("%.2f", $counting['247'][$gameid]['ctype'][$ctype] / $counting['248'][$gameid]['ctype'][$ctype] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>248,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==249){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>249,'amount'=>$counting['249'][$gameid]['ctype'][$ctype]));
                    if ($counting['250'][$gameid]['ctype'][$ctype]){
                        $pay_keep_rate = sprintf("%.2f", $counting['249'][$gameid]['ctype'][$ctype] / $counting['250'][$gameid]['ctype'][$ctype] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>250,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==251){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>251,'amount'=>$counting['251'][$gameid]['ctype'][$ctype]));
                    if ($counting['252'][$gameid]['ctype'][$ctype]){
                        $pay_keep_rate = sprintf("%.2f", $counting['251'][$gameid]['ctype'][$ctype] / $counting['252'][$gameid]['ctype'][$ctype] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>252,'amount'=>$pay_keep_rate));
                    }
                }
            }
            
            /*
            foreach ($aPid as $pid=>$pname) {
                if ($itemid==244){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>$yesterday,'itemid'=>244,'amount'=>$counting['244'][$gameid]['pid'][$pid]));
                }
                
                if ($itemid==245){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>245,'amount'=>$counting['245'][$gameid]['pid'][$pid]));
                    if ($counting['246'][$gameid]['pid'][$pid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['245'][$gameid]['pid'][$pid] / $counting['246'][$gameid]['pid'][$pid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>246,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==247){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>247,'amount'=>$counting['247'][$gameid]['pid'][$pid]));
                    if ($counting['248'][$gameid]['pid'][$pid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['247'][$gameid]['pid'][$pid] / $counting['248'][$gameid]['pid'][$pid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>248,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==249){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>249,'amount'=>$counting['249'][$gameid]['pid'][$pid]));
                    if ($counting['250'][$gameid]['pid'][$pid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['249'][$gameid]['pid'][$pid] / $counting['250'][$gameid]['pid'][$pid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>250,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==251){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>251,'amount'=>$counting['251'][$gameid]['pid'][$pid]));
                    if ($counting['252'][$gameid]['pid'][$pid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['251'][$gameid]['pid'][$pid] / $counting['252'][$gameid]['pid'][$pid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>252,'amount'=>$pay_keep_rate));
                    }
                }
            }
            */
        }
        
    }
}
die();
    $yesterday = date("Y-m-d",strtotime("-1 day"));
    $counting = Stat::factory()->payUserByMid();

    foreach ($counting as $itemid=>$array) {
        foreach ($aGame as $gameid=>$gameName) {
            if ($itemid==244){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>244,'amount'=>$counting['244'][$gameid]['gameid']));
            }
            
            if ($itemid==245){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>245,'amount'=>$counting['245'][$gameid]['gameid']));
                if ($counting['246'][$gameid]['gameid']){
                    $pay_keep_rate = sprintf("%.2f", $counting['245'][$gameid]['gameid'] / $counting['246'][$gameid]['gameid'] * 100).'%';
                    Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>246,'amount'=>$pay_keep_rate));
                }
            }
            
            if ($itemid==247){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>247,'amount'=>$counting['247'][$gameid]['gameid']));
                if ($counting['248'][$gameid]['gameid']){
                    $pay_keep_rate = sprintf("%.2f", $counting['247'][$gameid]['gameid'] / $counting['248'][$gameid]['gameid'] * 100).'%';
                    Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>248,'amount'=>$pay_keep_rate));
                }
            }
            
            if ($itemid==249){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>249,'amount'=>$counting['249'][$gameid]['gameid']));
                if ($counting['250'][$gameid]['gameid']){
                    $pay_keep_rate = sprintf("%.2f", $counting['249'][$gameid]['gameid'] / $counting['250'][$gameid]['gameid'] * 100).'%';
                    Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>250,'amount'=>$pay_keep_rate));
                }
            }
            
            if ($itemid==251){
                Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>251,'amount'=>$counting['251'][$gameid]['gameid']));
                if ($counting['252'][$gameid]['gameid']){
                    $pay_keep_rate = sprintf("%.2f", $counting['251'][$gameid]['gameid'] / $counting['252'][$gameid]['gameid'] * 100).'%';
                    Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>252,'amount'=>$pay_keep_rate));
                }
            }
            
            foreach ($aCid as $cid=>$cname){
                if ($itemid==244){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>244,'amount'=>$counting['244'][$gameid]['cid'][$cid]));
                }
                
                if ($itemid==245){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>245,'amount'=>$counting['245'][$gameid]['cid'][$cid]));
                    if ($counting['246'][$gameid]['cid'][$cid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['245'][$gameid]['cid'][$cid] / $counting['246'][$gameid]['cid'][$cid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>246,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==247){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>247,'amount'=>$counting['247'][$gameid]['cid'][$cid]));
                    if ($counting['248'][$gameid]['cid'][$cid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['247'][$gameid]['cid'][$cid] / $counting['248'][$gameid]['cid'][$cid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>248,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==249){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>249,'amount'=>$counting['249'][$gameid]['cid'][$cid]));
                    if ($counting['250'][$gameid]['cid'][$cid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['249'][$gameid]['cid'][$cid] / $counting['250'][$gameid]['cid'][$cid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>250,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==251){
                    Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>251,'amount'=>$counting['251'][$gameid]['cid'][$cid]));
                    if ($counting['252'][$gameid]['cid'][$cid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['251'][$gameid]['cid'][$cid] / $counting['252'][$gameid]['cid'][$cid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>252,'amount'=>$pay_keep_rate));
                    }
                }
            }
            
            foreach (Config_Game::$clientTyle as $ctype=>$clientname) {
                if ($itemid==244){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>244,'amount'=>$counting['244'][$gameid]['ctype'][$ctype]));
                }
                
                if ($itemid==245){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>245,'amount'=>$counting['245'][$gameid]['ctype'][$ctype]));
                    if ($counting['246'][$gameid]['ctype'][$ctype]){
                        $pay_keep_rate = sprintf("%.2f", $counting['245'][$gameid]['ctype'][$ctype] / $counting['246'][$gameid]['ctype'][$ctype] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>246,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==247){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>247,'amount'=>$counting['247'][$gameid]['ctype'][$ctype]));
                    if ($counting['248'][$gameid]['ctype'][$ctype]){
                        $pay_keep_rate = sprintf("%.2f", $counting['247'][$gameid]['ctype'][$ctype] / $counting['248'][$gameid]['ctype'][$ctype] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>248,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==249){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>249,'amount'=>$counting['249'][$gameid]['ctype'][$ctype]));
                    if ($counting['250'][$gameid]['ctype'][$ctype]){
                        $pay_keep_rate = sprintf("%.2f", $counting['249'][$gameid]['ctype'][$ctype] / $counting['250'][$gameid]['ctype'][$ctype] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>250,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==251){
                    Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>251,'amount'=>$counting['251'][$gameid]['ctype'][$ctype]));
                    if ($counting['252'][$gameid]['ctype'][$ctype]){
                        $pay_keep_rate = sprintf("%.2f", $counting['251'][$gameid]['ctype'][$ctype] / $counting['252'][$gameid]['ctype'][$ctype] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>252,'amount'=>$pay_keep_rate));
                    }
                }
            }
            
            foreach ($aPid as $pid=>$pname) {
                if ($itemid==244){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>$yesterday,'itemid'=>244,'amount'=>$counting['244'][$gameid]['pid'][$pid]));
                }
                
                if ($itemid==245){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>245,'amount'=>$counting['245'][$gameid]['pid'][$pid]));
                    if ($counting['246'][$gameid]['pid'][$pid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['245'][$gameid]['pid'][$pid] / $counting['246'][$gameid]['pid'][$pid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -3 day")),'itemid'=>246,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==247){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>247,'amount'=>$counting['247'][$gameid]['pid'][$pid]));
                    if ($counting['248'][$gameid]['pid'][$pid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['247'][$gameid]['pid'][$pid] / $counting['248'][$gameid]['pid'][$pid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -7 day")),'itemid'=>248,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==249){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>249,'amount'=>$counting['249'][$gameid]['pid'][$pid]));
                    if ($counting['250'][$gameid]['pid'][$pid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['249'][$gameid]['pid'][$pid] / $counting['250'][$gameid]['pid'][$pid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -15 day")),'itemid'=>250,'amount'=>$pay_keep_rate));
                    }
                }
                
                if ($itemid==251){
                    Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>251,'amount'=>$counting['251'][$gameid]['pid'][$pid]));
                    if ($counting['252'][$gameid]['pid'][$pid]){
                        $pay_keep_rate = sprintf("%.2f", $counting['251'][$gameid]['pid'][$pid] / $counting['252'][$gameid]['pid'][$pid] * 100).'%';
                        Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>date("Y-m-d",strtotime("$yesterday -30 day")),'itemid'=>252,'amount'=>$pay_keep_rate));
                    }
                }
            }
        }
    }





die();
if(1){
	$item1 = Stat::factory()->getItemid(1);
	$item8 = Stat::factory()->getItemid(8);
	$item9 = Stat::factory()->getItemid(9);
	$item11 = Stat::factory()->getItemid(11);
	$item12 = Stat::factory()->getItemid(12);
	
	//实时更新数据统计项
	$_item = array( 
				    array('itemid'=>113,'itemname'=>'首充人数'),
				    array('itemid'=>114,'itemname'=>'破产充值人次'),
				    array('itemid'=>126,'itemname'=>'快速充值人次'),
				    array('itemid'=>133,'itemname'=>'进入翻翻乐人数'),
				    array('itemid'=>155,'itemname'=>'限时抢购人数'),
	);
	
	//$_item = array(array('itemid'=>71,'itemname'=>'破产人数'));
	$items = array_merge($item1,$item8,$item9,$item11,$item12,$_item);

	foreach ($items as $itemid => $item) {
		$itemid = $item['itemid'];

		$aData = Loader_Redis::common()->hGetAll(Config_Keys::stat($day, $itemid));

		if(!$aData && $itemid !=9 ){
			continue;
		}
		var_dump($aData);
		echo "\n";
		
		foreach ($aData as $key=>$count) {
			$aKey   = explode('-', $key);
			$gameid = $aKey[0].'gameid';
			$ctype  = $aKey[1].'ctype';
			$cid    = $aKey[2].'cid';
			$pid    = $aKey[3];
			
			$countGame[$itemid][$gameid]          = $count + (int)$countGame[$itemid][$gameid];
			$countCtype[$itemid][$gameid][$ctype] = $count + (int)$countCtype[$itemid][$gameid][$ctype];
			$countCid[$itemid][$gameid][$cid]     = $count + (int)$countCid[$itemid][$gameid][$cid];
			$countPid[$itemid][$gameid][$pid]     = $count;
		}
		
		foreach ($aGame as $gameid=>$gameName) {
			
			$count = (int)$countGame[$itemid][$gameid.'gameid'];
			$count && Stat::factory()->redisData2db($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'amount'=>$count,'date'=>$day));										
			Stat::factory()->cal_rate($gameid,$day, $count, $itemid, 1, 'gameid', $gameid);//计算回头率
			Stat::factory()->stat_register_sum($gameid,$itemid,$day, $count, 'gameid', $gameid);//总注册
			Stat::factory()->stat_active_rate($gameid,$itemid,$day, $count, 'gameid', $gameid);//日活跃
			
			foreach ($aCtype as $ctype=>$cliname){
				$count = (int)$countCtype[$itemid][$gameid.'gameid'][$ctype.'ctype'];
				$count && Stat::factory()->redisData2db($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'amount'=>$count,'date'=>$day));										
				Stat::factory()->cal_rate($gameid,$day, $count, $itemid, 1, 'ctype', $ctype);//计算回头率
				Stat::factory()->stat_register_sum($gameid,$itemid,$day, $count, 'ctype', $ctype);//总注册
				Stat::factory()->stat_active_rate($gameid,$itemid,$day, $count, 'ctype', $ctype);//日活跃
			}
			
			foreach ($aCid as $cid=>$cname) {
				$count = (int)$countCid[$itemid][$gameid.'gameid'][$cid.'cid'];
				$count && Stat::factory()->redisData2db($gameid,array('itemid'=>$itemid,'cid'=>$cid,'amount'=>$count,'date'=>$day));
				Stat::factory()->cal_rate($gameid,$day, $count, $itemid, 2, 'cid', $cid);//计算回头率
				Stat::factory()->stat_register_sum($gameid,$itemid,$day, $count, 'cid', $cid);//总注册
				Stat::factory()->stat_active_rate($gameid,$itemid,$day, $count, 'cid', $cid);//日活跃
			}
			
			foreach ($aPid as $pid=>$pname) {
				$count = (int)$countPid[$itemid][$gameid.'gameid'][$pid];
				$count && Stat::factory()->redisData2db($gameid,array('itemid'=>$itemid,'pid'=>$pid,'amount'=>$count,'date'=>$day));
				Stat::factory()->cal_rate($gameid,$day, $count, $itemid, 3, 'pid', $pid);//计算回头率
				Stat::factory()->stat_register_sum($gameid,$itemid,$day, $count, 'pid', $pid);//总注册
				Stat::factory()->stat_active_rate($gameid,$itemid,$day, $count, 'pid', $pid);//日活跃
			}
		}

		Loader_Redis::common()->setTimeout(Config_Keys::stat($day, $itemid), 31*3600*24);
	}
}
	

die();
if(1){	
	foreach ($aGame as $gameid=>$gameName) {
		
		
		
		
		
		
		
		//金币流水统计
		/*
		$wmode2itemid = array('1'=>'27','2'=>'28','3'=>'29','4'=>'30','5'=>'38',6=>'45',7=>'48',8=>'49',9=>'50',10=>'51',11=>'52',12=>'53',13=>'54',14=>'66',15=>'64',16=>'72',17=>'73',18=>'77',19=>'76',20=>'97',21=>'103',22=>'102',24=>120,27=>118,28=>119,29=>115,30=>117,31=>123,34=>151,35=>152,37=>224);
		$wmodes = Base::factory()->getMoneyDescByWmode();
		foreach ($wmodes as $wmodeid=>$name) {
			if($wmodeid == 24){
				$aWinlog  = Stat::factory()->statLogWin($gameid, $stime, $etime, array("wmode IN (24,25)"));
			}elseif($wmodeid == 31 && $gameid == 4){
				$aWinlog     = Stat::factory()->statLogWin($gameid, $stime, $etime, array("wmode IN (31,32,33)"),0);
				Logs::factory()->debug($aWinlog,'ffl_coin_log');
			}else{
				$aWinlog  = Stat::factory()->statLogWin($gameid, $stime, $etime, array("wmode=$wmodeid"));
			}

			$wmodeid == 31 && $aWinlog['all'][0] = $aWinlog['all'][0] * -1;
			$itemid = $wmode2itemid[$wmodeid];
			$aWinlog['all'][0] && Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aWinlog['all'][0]));
			foreach ($aCid as $cid=>$cname) {
				$itemid = $wmode2itemid[$wmodeid];
				$wmodeid == 31 && $aWinlog['cid'][$cid] = $aWinlog['cid'][$cid] * -1;
				$aWinlog['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aWinlog['cid'][$cid]));
			}
			foreach ($aCtype as $ctype=>$clientname) {
				$itemid = $wmode2itemid[$wmodeid];
				$wmodeid == 31 && $aWinlog['ctype'][$ctype] = $aWinlog['ctype'][$ctype] * -1;
				$aWinlog['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aWinlog['ctype'][$ctype]));
			}
			foreach ($aPid as $pid=>$pname) {
				$itemid = $wmode2itemid[$wmodeid];
				$wmodeid == 31 && $aWinlog['pid'][$pid] = $aWinlog['pid'][$pid] * -1;
				$aWinlog['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'pid'=>$pid,'date'=>$yesterday,'amount'=>$aWinlog['pid'][$pid]));
			}
			echo $wmodeid;
			echo "\n";
		}
		*/
		
		//统计支付信息
		$aPayAmount     = Stat::factory()->statPayment($gameid,$stime, $etime, array("pmode!=11","gameid=$gameid"),'pamount');//充值金额（支付渠道）
		$aCardPayAmount = Stat::factory()->statPayment($gameid,$stime, $etime, array("pmode=11","gameid=$gameid"),'pamount');//充值金额（点卡）
		$aPayMid        = Stat::factory()->statPayment($gameid,$stime, $etime, array("pmode!=11","gameid=$gameid"),'mid');//充值人数
		$aPayOrder      = Stat::factory()->statPayment($gameid,$stime, $etime, array("pmode!=11","gameid=$gameid"),'order');//订单总数
		$aFastPayMid        = Stat::factory()->statPayment($gameid,$stime, $etime, array("pmode!=11","gameid=$gameid","source=1"),'mid');//快速充值人数
		$aBankruptPayMid    = Stat::factory()->statPayment($gameid,$stime, $etime, array("pmode!=11","gameid=$gameid","source=3"),'mid');//破产充值人数
		
		$aFastPayMid['gameid'][$gameid]     && Stat::factory()->setStatSum($gameid,array('itemid'=>132,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aFastPayMid['gameid'][$gameid]));
		$aBankruptPayMid['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>131,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aBankruptPayMid['gameid'][$gameid]));
		$aPayAmount['gameid'][$gameid]     && Stat::factory()->setStatSum($gameid,array('itemid'=>24,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPayAmount['gameid'][$gameid]));
		$aCardPayAmount['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>63,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aCardPayAmount['gameid'][$gameid]));
		$aPayMid['gameid'][$gameid]        && Stat::factory()->setStatSum($gameid,array('itemid'=>25,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPayMid['gameid'][$gameid]));
		$aPayOrder['gameid'][$gameid]      && Stat::factory()->setStatSum($gameid,array('itemid'=>31,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$aPayOrder['gameid'][$gameid]));
				
		if($aPayAmount['gameid'][$gameid] && $aPayMid['gameid'][$gameid]){
			$arpu = sprintf("%.2f", $aPayAmount['gameid'][$gameid] / $aPayMid['gameid'][$gameid] );//arpu值
			Stat::factory()->setStatSum($gameid,array('itemid'=>46,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$arpu));
			$active   = Stat::factory()->getStatSum($gameid,array("date='$yesterday'","itemid=11","gameid='$gameid'"));//活跃数
			$register = Stat::factory()->getStatSum($gameid,array("date='$yesterday'","itemid=55","gameid='$gameid'"));//新增用户
			if($active){
				$active_user = $active + $register;
				$pay_penetration_rate = sprintf("%.2f", $aPayMid['gameid'][$gameid] / $active_user *100 ).'%';//付费渗透率
				Stat::factory()->setStatSum($gameid,array('itemid'=>47,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$pay_penetration_rate));
			}
			
			if($register){
				$pay_register_rate = sprintf("%.2f", $aPayAmount['gameid'][$gameid] / $register );//注册arpu值
				Stat::factory()->setStatSum($gameid,array('itemid'=>61,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$pay_register_rate));
			}
			echo 'payment:'.$gameid;
			echo "\n";
		}
		
		foreach ($aCid as $cid=>$cname) {
			
			$aFastPayMid['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>132,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aFastPayMid['cid'][$cid]));
			$aBankruptPayMid['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>131,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aBankruptPayMid['cid'][$cid]));
			
			$aPayAmount['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>24,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPayAmount['cid'][$cid]));
			$aCardPayAmount['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>63,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aCardPayAmount['cid'][$cid]));
			$aPayMid['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>25,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPayMid['cid'][$cid]));
			$aPayOrder['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>31,'cid'=>$cid,'date'=>$yesterday,'amount'=>$aPayOrder['cid'][$cid]));
			if($aPayAmount['cid'][$cid] && $aPayMid['cid'][$cid]){
				$arpu = sprintf("%.2f", $aPayAmount['cid'][$cid] / $aPayMid['cid'][$cid] );//arpu值
				Stat::factory()->setStatSum($gameid,array('itemid'=>46,'cid'=>$cid,'date'=>$yesterday,'amount'=>$arpu));
				$active   = Stat::factory()->getStatSum($gameid,array("date='$yesterday'","itemid=11","cid='$cid'"));//活跃数
				$register = Stat::factory()->getStatSum($gameid,array("date='$yesterday'","itemid=55","cid='$cid'"));//新增用户
				if($active){
					$active_user = $active + $register;
					$pay_penetration_rate = sprintf("%.2f", $aPayMid['cid'][$cid] / $active_user *100 ).'%';//付费渗透率
					Stat::factory()->setStatSum($gameid,array('itemid'=>47,'cid'=>$cid,'date'=>$yesterday,'amount'=>$pay_penetration_rate));
				}
				
				if($register){
					$pay_register_rate = sprintf("%.2f", $aPayAmount['cid'][$cid] / $register );//注册arpu值
					Stat::factory()->setStatSum($gameid,array('itemid'=>61,'cid'=>$cid,'date'=>$yesterday,'amount'=>$pay_register_rate));
				}
			}
		}
		
		foreach ($aCtype as $ctype=>$clientname) {
			
			$aFastPayMid['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>132,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aFastPayMid['ctype'][$ctype]));
			$aBankruptPayMid['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>131,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aBankruptPayMid['ctype'][$ctype]));

			$aPayAmount['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>24,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPayAmount['ctype'][$ctype]));
			$aCardPayAmount['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>63,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aCardPayAmount['ctype'][$ctype]));
			$aPayMid['ctype'][$ctype]   && Stat::factory()->setStatSum($gameid,array('itemid'=>25,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPayMid['ctype'][$ctype]));
			$aPayOrder['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>31,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$aPayOrder['ctype'][$ctype]));
			if($aPayAmount['ctype'][$ctype] && $aPayMid['ctype'][$ctype]){
				$arpu = sprintf("%.2f", $aPayAmount['ctype'][$ctype] / $aPayMid['ctype'][$ctype] );//arpu值
				Stat::factory()->setStatSum($gameid,array('itemid'=>46,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$arpu));
				$active = Stat::factory()->getStatSum($gameid,array("date='$yesterday'","itemid=11","ctype='$ctype'"));
				$register = Stat::factory()->getStatSum($gameid,array("date='$yesterday'","itemid=55","ctype='$ctype'"));//新增用户
				if($active){
					$active_user = $active + $register;
					$pay_penetration_rate = sprintf("%.2f", $aPayMid['ctype'][$ctype] / $active_user *100 ).'%';//付费渗透率
					Stat::factory()->setStatSum($gameid,array('itemid'=>47,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$pay_penetration_rate));
				}
				
				if($register){
					$pay_register_rate = sprintf("%.2f", $aPayAmount['ctype'][$ctype] / $register );//注册arpu值
					Stat::factory()->setStatSum($gameid,array('itemid'=>61,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$pay_register_rate));
				}
			}
		}
		
		
		foreach ($aPid as $pid=>$pname) {
			
			$aFastPayMid['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>132,'pid'=>$pid,'date'=>$yesterday,'amount'=>$aFastPayMid['pid'][$pid]));
			$aBankruptPayMid['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>131,'pid'=>$pid,'date'=>$yesterday,'amount'=>$aBankruptPayMid['pid'][$pid]));
			$aPayAmount['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>24,'pid'=>$pid,'date'=>$yesterday,'amount'=>$aPayAmount['pid'][$pid]));
			$aCardPayAmount['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>63,'pid'=>$pid,'date'=>$yesterday,'amount'=>$aCardPayAmount['pid'][$pid]));
			$aPayMid['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>25,'pid'=>$pid,'date'=>$yesterday,'amount'=>$aPayMid['pid'][$pid]));
			$aPayOrder['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>31,'pid'=>$pid,'date'=>$yesterday,'amount'=>$aPayOrder['pid'][$pid]));
			if($aPayAmount['pid'][$pid] && $aPayMid['pid'][$pid]){
				$arpu = sprintf("%.2f", $aPayAmount['pid'][$pid] / $aPayMid['pid'][$pid] );//arpu值
				Stat::factory()->setStatSum($gameid,array('itemid'=>46,'pid'=>$pid,'date'=>$yesterday,'amount'=>$arpu));
				$active = Stat::factory()->getStatSum($gameid,array("date='$yesterday'","itemid=11","pid='$pid'"));
				$register = Stat::factory()->getStatSum($gameid,array("date='$yesterday'","itemid=55","pid='$pid'"));//新增用户
				if($active){
					$active_user = $active + $register;
					$pay_penetration_rate = sprintf("%.2f", $aPayMid['pid'][$pid] / $active_user *100 ).'%';//付费渗透率
					Stat::factory()->setStatSum($gameid,array('itemid'=>47,'pid'=>$pid,'date'=>$yesterday,'amount'=>$pay_penetration_rate));
				}
				
				if($register){
					$pay_register_rate = sprintf("%.2f", $aPayAmount['pid'][$pid] / $register );//注册arpu值
					Stat::factory()->setStatSum($gameid,array('itemid'=>61,'pid'=>$pid,'date'=>$yesterday,'amount'=>$pay_register_rate));
				}
			}
		}
		
	}
}