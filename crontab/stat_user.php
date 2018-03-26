<?php 

$dirName = dirname(dirname(__FILE__));

include $dirName.'/common.php';
set_time_limit(0);

$timehi = date('Hi');
$day = date("Y-m-d",strtotime("-1 day"));
$aGame  = Config_Game::$game;
$aCtype = Config_Game::$clientTyle;
$aCid   = Base::factory()->getChannel();
$aPid   = Base::factory()->getPack();

if( $timehi == '0030'|| $_GET['test']) { 
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
				
				$new_register_num       = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'cid', $cid);//新增人数
				$new_account_num        = Stat::factory()->getAmountFromDB($gameid, 9, $yesterday, 'cid', $cid);//新增账号数
				$num                    = round($new_account_num/$new_register_num,2);
				Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>331,'amount'=>$num));//人均账号数
			}
			
			foreach ($aPid as $pid=>$pname) {
				$count = (int)$countPid[$itemid][$gameid.'gameid'][$pid];
				$count && Stat::factory()->redisData2db($gameid,array('itemid'=>$itemid,'pid'=>$pid,'amount'=>$count,'date'=>$day));
				Stat::factory()->cal_rate($gameid,$day, $count, $itemid, 3, 'pid', $pid);//计算回头率
				Stat::factory()->stat_register_sum($gameid,$itemid,$day, $count, 'pid', $pid);//总注册
				Stat::factory()->stat_active_rate($gameid,$itemid,$day, $count, 'pid', $pid);//日活跃
				
				$new_register_num       = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'pid', $cid);//新增人数
				$new_account_num        = Stat::factory()->getAmountFromDB($gameid, 9, $yesterday, 'pid', $cid);//新增账号数
				$num                    = round($new_account_num/$new_register_num,2);
				Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>$yesterday,'itemid'=>331,'amount'=>$num));//人均账号数
			}
		}

		Loader_Redis::common()->setTimeout(Config_Keys::stat($day, $itemid), 31*3600*24);
	}
}

if($timehi == '0000'){
	$yesterday = date("Y-m-d",strtotime("-1 day"));
	$stime  = strtotime($yesterday);
	$etime  = strtotime("$yesterday 23:59:59");
	$amount = Stat::factory()->statNewUserByMid($stime,$etime);//新增用户统计
	$amount_activeuser = Stat::factory()->statActiveUserByMid($stime, $etime);//活跃用户统计
	
	foreach ($amount as $itemid=>$array) {
		foreach ($aGame as $gameid=>$gameName) {
			
			if($itemid == 56){//玩牌人数 
				$playerNum = $array[$gameid]['gameid'] + $amount_activeuser['23'][$gameid]['gameid'];//玩牌人数 = 新增用户1局以上+活跃用户1局以上
				Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>23,'amount'=>$playerNum));
				Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>197,'amount'=>$array[$gameid]['gameid']));
			}
			
			if($itemid == 71){//破产人数
				$new_register_bankrupt = $array[$gameid]['gameid'];
				$array[$gameid]['gameid'] = $array[$gameid]['gameid'] + $amount_activeuser['71'][$gameid]['gameid'];//破产人数 = 新增用户破产人数+活跃用户破产人数
				Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>201,'amount'=>$new_register_bankrupt));//新增用户中的破产人数
			}
			
			$array[$gameid]['gameid'] && Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>$itemid,'amount'=>$array[$gameid]['gameid']));
						
			if($itemid == 60){
				if($amount['59'][$gameid]['gameid']){
					//$pay_register_rate = sprintf("%.2f", $array[$gameid]['gameid'] / $amount['59'][$gameid]['gameid'] );//注册arpu值
					Stat::factory()->setStatSum($gameid,array('itemid'=>61,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$pay_register_rate));
				}
			}
			
			foreach ($aCid as $cid=>$cname) {
				
				if($itemid == 56){//玩牌人数
					$playerNum = $array[$gameid]['cid'][$cid] + $amount_activeuser['23'][$gameid]['cid'][$cid];//玩牌人数 = 新增用户1局以上+活跃用户1局以上
					Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>23,'amount'=>$playerNum));
					Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>197,'amount'=>$array[$gameid]['cid'][$cid]));
				}
				
				if($itemid == 71){//破产人数
					$new_register_bankrupt = $array[$gameid]['cid'][$cid];
					$array[$gameid]['cid'][$cid] = $array[$gameid]['cid'][$cid] + $amount_activeuser['71'][$gameid]['cid'][$cid];//破产人数 = 新增用户破产人数+活跃用户破产人数
					Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>201,'amount'=>$new_register_bankrupt));//新增用户中的破产人数
				}
				
				$array[$gameid]['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>$itemid,'amount'=>$array[$gameid]['cid'][$cid]));
				if($itemid == 60){
					if($amount['59'][$gameid]['cid'][$cid]){
						//$pay_register_rate = sprintf("%.2f", $array[$gameid]['cid'][$cid] / $amount['59'][$gameid]['cid'][$cid]);//注册arpu值
						Stat::factory()->setStatSum($gameid,array('itemid'=>61,'cid'=>$cid,'date'=>$yesterday,'amount'=>$pay_register_rate));
					}
				}
			}
				
			foreach (Config_Game::$clientTyle as $ctype=>$clientname) {
				
				if($itemid == 56){//玩牌人数
					$playerNum = $array[$gameid]['ctype'][$ctype] + $amount_activeuser['23'][$gameid]['ctype'][$ctype];//玩牌人数 = 新增用户1局以上+活跃用户1局以上
					Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>23,'amount'=>$playerNum));
					Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>197,'amount'=>$array[$gameid]['ctype'][$ctype]));
				}
				
				if($itemid == 71){//破产人数
					$new_register_bankrupt = $array[$gameid]['ctype'][$ctype];
					$array[$gameid]['ctype'][$ctype] = $array[$gameid]['ctype'][$ctype] + $amount_activeuser['71'][$gameid]['ctype'][$ctype];//破产人数 = 新增用户破产人数+活跃用户破产人数
					Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>201,'amount'=>$new_register_bankrupt));//新增用户中的破产人数
				}
				
				$array[$gameid]['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>$itemid,'amount'=>$array[$gameid]['ctype'][$ctype]));
				if($itemid == 60){
					if($amount['59'][$gameid]['ctype'][$ctype]){
						//$pay_register_rate = sprintf("%.2f", $array[$gameid]['ctype'][$ctype] / $amount['59'][$gameid]['ctype'][$ctype]);//注册arpu值
						Stat::factory()->setStatSum($gameid,array('itemid'=>61,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$pay_register_rate));
					}
				}
			}
				
			foreach ($aPid as $pid=>$pname) {
				
				if($itemid == 56){
					$playerNum = $array[$gameid]['pid'][$pid] + $amount_activeuser['23'][$gameid]['pid'][$pid];//玩牌人数 = 新增用户1局以上+活跃用户1局以上
					Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>$yesterday,'itemid'=>23,'amount'=>$playerNum));
					Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>$yesterday,'itemid'=>197,'amount'=>$array[$gameid]['pid'][$pid]));
				}
				
				if($itemid == 71){//破产人数
					$new_register_bankrupt = $array[$gameid]['pid'][$pid];
					$array[$gameid]['pid'][$pid] = $array[$gameid]['pid'][$pid] + $amount_activeuser['71'][$gameid]['pid'][$pid];//破产人数 = 新增用户破产人数+活跃用户破产人数
					Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>$yesterday,'itemid'=>201,'amount'=>$new_register_bankrupt));//新增用户中的破产人数
				}
				
				$array[$gameid]['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>$yesterday,'itemid'=>$itemid,'amount'=>$array[$gameid]['pid'][$pid]));
				if($itemid == 60){
					if($amount['59'][$gameid]['pid'][$pid]){
						//$pay_register_rate = sprintf("%.2f", $array[$gameid]['pid'][$pid] / $amount['59'][$gameid]['pid'][$pid]);//注册arpu值
						Stat::factory()->setStatSum($gameid,array('itemid'=>61,'pid'=>$pid,'date'=>$yesterday,'amount'=>$pay_register_rate));
					}
				}
			}
		}
	}
}

if($timehi == '0340'){
	$yesterday = date("Y-m-d",strtotime("-1 day"));
	$stime  = strtotime($yesterday);
	$etime  = strtotime("$yesterday 23:59:59");
	foreach ($aGame as $gameid=>$gameName) {
		foreach ($aCtype as $ctype=>$cliname){
			if(in_array($ctype,array(1,2,3))){
				$new_register_num       = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'ctype', $ctype);//新增
				$new_register_player    = Stat::factory()->getAmountFromDB($gameid, 197, $yesterday, 'ctype', $ctype);//新增用户中的玩牌人数
				$new_register_bankrupt  = Stat::factory()->getAmountFromDB($gameid, 201, $yesterday, 'ctype', $ctype);//新增用户中的破产人数
				$new_register_payamount = Stat::factory()->getAmountFromDB($gameid, 60, $yesterday, 'ctype', $ctype);//新增用户中的付费金额
				$new_register_paynum    = Stat::factory()->getAmountFromDB($gameid, 59, $yesterday, 'ctype', $ctype);//新增用户中的付费人数
				$new_register_paiju     = Stat::factory()->getAmountFromDB($gameid, 198, $yesterday, 'ctype', $ctype);//新增用户中的新增牌局
				
				$new_pay_rate  = sprintf("%.2f",$new_register_paynum / $new_register_num*100 ).'%'; //新增付费转化率：新增用户付费人数/新增人数
				$new_play_rate = sprintf("%.2f",$new_register_player / $new_register_num*100 ).'%'; //新增玩牌转化率:新增用户玩牌人数/新增人数
				
				$new_pre_play_rate = sprintf("%.2f",$new_register_paiju / $new_register_num); //注册人均玩牌局数：新增牌局/新增用户数
				$new_bankrupt_rate = sprintf("%.2f",$new_register_bankrupt / $new_register_num*100 ).'%'; //注册破产率：新增用户破产人数/新增用户数
				
				$new_register_num       = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'ctype', $ctype);//新增人数
				$new_account_num        = Stat::factory()->getAmountFromDB($gameid, 9, $yesterday, 'ctype', $ctype);//新增账号数
				$num                    = round($new_account_num/$new_register_num,2);
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>331,'amount'=>$num));//人均账号数
	
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>191,'amount'=>$new_register_num));//新增
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>192,'amount'=>$new_pay_rate));//新增付费转化率
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>193,'amount'=>$new_play_rate));//新增玩牌转化率
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>194,'amount'=>$new_register_paynum));//当日付费用户
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>196,'amount'=>$new_register_payamount));//付费额度
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>197,'amount'=>$new_register_player));//注册玩牌人数
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>199,'amount'=>$new_pre_play_rate));//注册人均玩牌局数
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>201,'amount'=>$new_register_bankrupt));//新增用户中的破产人数
				Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>200,'amount'=>$new_bankrupt_rate));//注册破产率
			}
		}
			
		foreach ($aCid as $cid=>$cname) {
			$new_register_num       = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'cid', $cid);//新增
			$new_register_player    = Stat::factory()->getAmountFromDB($gameid, 197, $yesterday, 'cid', $cid);//新增用户中的玩牌人数
			$new_register_bankrupt  = Stat::factory()->getAmountFromDB($gameid, 201, $yesterday, 'cid', $cid);//新增用户中的破产人数
			$new_register_payamount = Stat::factory()->getAmountFromDB($gameid, 60, $yesterday, 'cid', $cid);//新增用户中的付费金额
			$new_register_paynum    = Stat::factory()->getAmountFromDB($gameid, 59, $yesterday, 'cid', $cid);//新增用户中的付费人数
			$new_register_paiju     = Stat::factory()->getAmountFromDB($gameid, 198, $yesterday, 'cid', $cid);//新增用户中的新增牌局
				
			$new_pay_rate  = sprintf("%.2f",$new_register_paynum / $new_register_num*100 ).'%'; //新增付费转化率：新增用户付费人数/新增人数
			$new_play_rate = sprintf("%.2f",$new_register_player / $new_register_num*100 ).'%'; //新增玩牌转化率:新增用户玩牌人数/新增人数
				
			$new_pre_play_rate = sprintf("%.2f",$new_register_paiju / $new_register_num); //注册人均玩牌局数：新增牌局/新增用户数
			$new_bankrupt_rate = sprintf("%.2f",$new_register_bankrupt / $new_register_num*100 ).'%'; //注册破产率：新增用户破产人数/新增用户数
			
			$new_register_num       = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'cid', $cid);//新增人数
			$new_account_num        = Stat::factory()->getAmountFromDB($gameid, 9, $yesterday, 'cid', $cid);//新增账号数
			$num                    = round($new_account_num/$new_register_num,2);
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>331,'amount'=>$num));//人均账号数
	
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>191,'amount'=>$new_register_num));//新增
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>192,'amount'=>$new_pay_rate));//新增付费转化率
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>193,'amount'=>$new_play_rate));//新增玩牌转化率
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>194,'amount'=>$new_register_paynum));//当日付费用户
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>196,'amount'=>$new_register_payamount));//付费额度
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>197,'amount'=>$new_register_player));//注册玩牌人数
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>199,'amount'=>$new_pre_play_rate));//注册人均玩牌局数
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>201,'amount'=>$new_register_bankrupt));//新增用户中的破产人数
			Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>200,'amount'=>$new_bankrupt_rate));//注册破产率
		}
		
		$new_register_num       = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'gameid', $gameid);//新增
		$new_register_player    = Stat::factory()->getAmountFromDB($gameid, 197, $yesterday, 'gameid', $gameid);//新增用户中的玩牌人数
		$new_register_bankrupt  = Stat::factory()->getAmountFromDB($gameid, 201, $yesterday, 'gameid', $gameid);//新增用户中的破产人数
		$new_register_payamount = Stat::factory()->getAmountFromDB($gameid, 60, $yesterday, 'gameid', $gameid);//新增用户中的付费金额
		$new_register_paynum    = Stat::factory()->getAmountFromDB($gameid, 59, $yesterday, 'gameid', $gameid);//新增用户中的付费人数
		$new_register_paiju     = Stat::factory()->getAmountFromDB($gameid, 198, $yesterday, 'gameid', $gameid);//新增用户中的新增牌局
				
		$new_pay_rate  = sprintf("%.2f",$new_register_paynum / $new_register_num*100 ).'%'; //新增付费转化率：新增用户付费人数/新增人数
		$new_play_rate = sprintf("%.2f",$new_register_player / $new_register_num*100 ).'%'; //新增玩牌转化率:新增用户玩牌人数/新增人数
				
		$new_pre_play_rate = sprintf("%.2f",$new_register_paiju / $new_register_num); //注册人均玩牌局数：新增牌局/新增用户数
		$new_bankrupt_rate = sprintf("%.2f",$new_register_bankrupt / $new_register_num*100 ).'%'; //注册破产率：新增用户破产人数/新增用户数
		
		$new_register_num       = Stat::factory()->getAmountFromDB($gameid, 55, $yesterday, 'gameid', $gameid);//新增人数
		$new_account_num        = Stat::factory()->getAmountFromDB($gameid, 9, $yesterday, 'gameid', $gameid);//新增账号数
		$num                    = round($new_account_num/$new_register_num,2);
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>331,'amount'=>$num));//人均账号数
	
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>191,'amount'=>$new_register_num));//新增
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>192,'amount'=>$new_pay_rate));//新增付费转化率
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>193,'amount'=>$new_play_rate));//新增玩牌转化率
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>194,'amount'=>$new_register_paynum));//当日付费用户
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>196,'amount'=>$new_register_payamount));//付费额度
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>197,'amount'=>$new_register_player));//注册玩牌人数
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>199,'amount'=>$new_pre_play_rate));//注册人均玩牌局数
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>201,'amount'=>$new_register_bankrupt));//新增用户中的破产人数
		Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$yesterday,'itemid'=>200,'amount'=>$new_bankrupt_rate));//注册破产率
	}
}

if($timehi == '0500'){
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
}

if($timehi == '0230'){
    $payment = Stat::factory()->newRegisterPayment();

    foreach ($payment as $itemid=>$array) {
        
        switch ($itemid){
            case 254:
                $day=4;
                break;
            case 255:
                $day=8;
                break;
            case 256:
                $day=16;
                break;
            case 257:
                $day=31;
                break;
        }
        $dataTime = date("Y-m-d",strtotime("-".$day." day"));
        
        foreach ($array as $gameid=>$gameinfo) {

            Stat::factory()->setStatSum($gameid,array('gameid'=>$gameid,'date'=>$dataTime,'itemid'=>$itemid,'amount'=>$gameinfo['gameid']));

            foreach ($gameinfo['cid'] as $cid=>$cinfo){
                Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$dataTime,'itemid'=>$itemid,'amount'=>$cinfo));
            }

            foreach ($gameinfo['ctype'] as $ctype=>$clientinfo) {
                Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$dataTime,'itemid'=>$itemid,'amount'=>$clientinfo));
            }

            foreach ($gameinfo['pid'] as $pid=>$pinfo) {
                Stat::factory()->setStatSum($gameid,array('pid'=>$pid,'date'=>$dataTime,'itemid'=>$itemid,'amount'=>$pinfo));
            }
        }
    }
}
