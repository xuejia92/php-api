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



if($timehi == '0100' || $_GET['test']){
	$rmode2itemid = array(1=>139,2=>134,3=>136,4=>137,5=>138,200=>135);
	foreach ($aGame as $gameid =>$gname){
		foreach ($rmode2itemid as $rmode=>$itemid) {
			if($rmode == 200 ){
				$rollInfo = Stat::factory()->statRoll($gameid, $stime, $etime, array("rmode=2","amount<0"));//后台扣减
			}else{
				$rollInfo = Stat::factory()->statRoll($gameid, $stime, $etime, array("rmode=$rmode"));
			}
			
			$rollInfo['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$rollInfo['gameid'][$gameid]));
			$totalItem[$itemid] = $rollInfo['gameid'][$gameid] + (int)$totalItem[$itemid];

			foreach ($aCid as $cid=>$cname) {
				$rollInfo['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'cid'=>$cid,'date'=>$yesterday,'amount'=>$rollInfo['cid'][$cid]));
			}
			foreach ($aCtype as $ctype=>$clientname) {
				$rollInfo['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$rollInfo['ctype'][$ctype]));
			}
			foreach ($aPid as $pid=>$pname) {
				$rollInfo['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>$itemid,'pid'=>$pid,'date'=>$yesterday,'amount'=>$rollInfo['pid'][$pid]));
			}
		}
		$total_grant           = Stat::factory()->statRoll($gameid, $stime, $etime, array("amount>0"));//发放总额 
		$total_consume         = Stat::factory()->statRoll($gameid, $stime, $etime, array("amount<0"));//消耗总额
		$total_inventory_roll  = Stat::factory()->statGameInfo($gameid,'roll+roll1');//玩家身上库存乐券

		$total_grant['gameid'][$gameid]   && Stat::factory()->setStatSum($gameid,array('itemid'=>141,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$total_grant['gameid'][$gameid]));
		$total_consume['gameid'][$gameid] && Stat::factory()->setStatSum($gameid,array('itemid'=>142,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$total_consume['gameid'][$gameid]));
		$balance  = $total_grant['gameid'][$gameid] - $total_consume['gameid'][$gameid];
		
		$totalItem[141] = $total_grant['gameid'][$gameid]   + (int)$totalItem[141];
		$totalItem[142] = $total_consume['gameid'][$gameid] + (int)$totalItem[142];
		
		$anteayerInventoryRoll     = (int)Stat::factory()->getStatSum($gameid,array("date='$anteayer'","itemid=189"));//前天实际库存
		$anteayerInventoryRoll     = $anteayerInventoryRoll == 0 ? $total_inventory_roll : $anteayerInventoryRoll;
		$all_anteayerInventoryRoll = $all_anteayerInventoryRoll + $anteayerInventoryRoll;
		//金币平衡数 = 昨天实际库存 - （前天实际库存+理论库存）
		$balanceRoll = $total_inventory_roll - ($anteayerInventoryRoll + $balance);
		
		Stat::factory()->setStatSum($gameid,array('itemid'=>189,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$total_inventory_roll));//乐券库存
		Stat::factory()->setStatSum($gameid,array('itemid'=>190,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$balanceRoll));//乐券平衡数
		Stat::factory()->setStatSum($gameid,array('itemid'=>142,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$total_consume['gameid'][$gameid]));
		Stat::factory()->setStatSum($gameid,array('itemid'=>143,'gameid'=>$gameid,'date'=>$yesterday,'amount'=>$balance));//发放消耗差额
		
		foreach ($aCid as $cid=>$cname) {
			$total_grant['cid'][$cid]   && Stat::factory()->setStatSum($gameid,array('itemid'=>141,'cid'=>$cid,'date'=>$yesterday,'amount'=>$total_grant['cid'][$cid]));
			$total_consume['cid'][$cid] && Stat::factory()->setStatSum($gameid,array('itemid'=>142,'cid'=>$cid,'date'=>$yesterday,'amount'=>$total_consume['cid'][$cid]));
			$balance  = $total_grant['cid'][$cid] - $total_consume['cid'][$cid];
			Stat::factory()->setStatSum($gameid,array('itemid'=>143,'cid'=>$cid,'date'=>$yesterday,'amount'=>$balance));//发放消耗差额
		}
		foreach ($aCtype as $ctype=>$clientname) {
			$total_grant['ctype'][$ctype]   && Stat::factory()->setStatSum($gameid,array('itemid'=>141,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$total_grant['ctype'][$ctype]));
			$total_consume['ctype'][$ctype] && Stat::factory()->setStatSum($gameid,array('itemid'=>142,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$total_consume['ctype'][$ctype]));
			$balance  = $total_grant['ctype'][$ctype] - $total_consume['ctype'][$ctype];
			Stat::factory()->setStatSum($gameid,array('itemid'=>143,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$balance));//发放消耗差额
		}
		/*
		foreach ($aPid as $pid=>$pname) {
			$total_grant['pid'][$pid]   && Stat::factory()->setStatSum($gameid,array('itemid'=>141,'pid'=>$pid,'date'=>$yesterday,'amount'=>$total_grant['pid'][$pid]));
			$total_consume['pid'][$pid] && Stat::factory()->setStatSum($gameid,array('itemid'=>142,'pid'=>$pid,'date'=>$yesterday,'amount'=>$total_consume['pid'][$pid]));
			$balance  = $total_grant['pid'][$pid] - $total_consume['pid'][$pid];
			Stat::factory()->setStatSum($gameid,array('itemid'=>143,'pid'=>$pid,'date'=>$yesterday,'amount'=>$balance));//发放消耗差额
		}
		*/
	}
	
	$totalItem[143] = $totalItem[141] - $totalItem[142];
	
	$all_total_inventory_roll   = Stat::factory()->statGameInfo(0,'roll');//玩家身上库存乐券
	Loader_Redis::common()->set('inventoryroll|'.$yesterday,$all_total_inventory_roll,false,false,7*24*3600);//用redis暂存每天全局的库存金币
	$last_total_inventory_roll  = Loader_Redis::common()->get('inventoryroll|'.$anteayer,false,false);
	$all_anteayerInventoryRoll  = $last_total_inventory_roll ? $last_total_inventory_roll : $all_anteayerInventoryRoll;
	$all_balanceMoney           = $all_total_inventory_roll - ($all_anteayerInventoryRoll + $totalItem[143]);
	
	Stat::factory()->setStatSum(0,array('itemid'=>189,'date'=>$yesterday,'amount'=>$all_total_inventory_roll));//乐券库存
	Stat::factory()->setStatSum(0,array('itemid'=>190,'date'=>$yesterday,'amount'=>$all_balanceMoney));//乐券平衡数
	
	foreach ($totalItem as $itemid =>$val) {
		Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>$itemid,'amount'=>$val));//总表
	}
}

