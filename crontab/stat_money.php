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

$_stime = microtime(true);
if($timehi == '0000' || $_GET['test']){
	foreach ($aGame as $gameid=>$gameName) {
		
		$progressive = $change = $nmt = $double = $buyu = $fruitWinMoney = $fruitLostMoney = $fflMoney = $magicMoney = $taskMoney = $helpMoney = $fruitChatMoney = $baiRenMoney = $bairen_tax = $matchBaoMing = $matchLostJiangli = 0;
				
		$robotLostMoney = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid<1000","money<0"));//机器人输金币总数（发放）
		$robotLostMoney = abs($robotLostMoney);
		$robotLostTax   = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid<1000","money<0"),'tax');//机器人输的台费
		$robotWinTax    = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid<1000","money>=0"),'tax');//机器人赢的台费
		$aWinLogGrantMoney   = Stat::factory()->statLogWin($gameid,$stime, $etime, array("money>0"));//各项金币发放总数（发放）
		$aWinLogRecycleMoney = Stat::factory()->statLogWin($gameid,$stime, $etime, array("money<0"));//各项金币回收总数（消耗）
		$robotWinMoney       = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("mid<1000","money>0"));//机器人赢金币总数（消耗）
		if($gameid != 5){
			$tax             = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000"),'tax');//台费（消耗）
		}
		
		$fruitMachine = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=1"));//水果机金币
		$racing       = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=2"));//小马快跑金币
		$toradora     = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=3"));//龙虎斗金币
		$toradora_tax = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=3"),'tax');//龙虎斗台费
		$buyu         = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level>=4","level<=11"));//捕鱼金币
		$buyuNew      = Stat::factory()->statFishSum($stime, $etime, array(), "endmoney-stmoney", $gameid);//捕鱼新表
		$buyu         = $buyu + $buyuNew;
		
		$total_inventory_money = Stat::factory()->statGameInfo($gameid);//玩家身上库存金币
		
		if($gameid == 1){//统计娱乐场总体概况
			$fm    = Stat::factory()->statLogPlay(5,$stime,$etime,array("level=1"));//水果机金币
			$rac   = Stat::factory()->statLogPlay(5,$stime,$etime,array("level=2"));//小马快跑金币
			$ta    = Stat::factory()->statLogPlay(5,$stime,$etime,array("level=3"));//龙虎斗金币
			$by    = Stat::factory()->statLogPlay(5,$stime,$etime,array("level>=4","level<=11"));//捕鱼金币
			$buyuNew    = Stat::factory()->statFishSum($stime, $etime, array("gid!=5"), "endmoney-stmoney");//捕鱼新表
			$by         = $by + $buyuNew;
			
			Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>75,'amount'=>$fm*-1));//水果机金币消耗
			Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>156,'amount'=>$rac*-1));//小马快跑
			Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>184,'amount'=>$ta*-1));//龙虎斗消耗
			Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>260,'amount'=>$by*-1));//捕鱼
		}
		
		if($gameid == 7){
			$double = Stat::factory()->statLogPlay(7,$stime,$etime,array(),'`double`');//翻倍回收
			$nmt    = Stat::factory()->statLogPlay(7,$stime,$etime,array(),'`nmt`');//禁比回收
			$change = Stat::factory()->statLogPlay(7,$stime,$etime,array(),'`change`');//换牌回收
		}
		
		$fruitLostMoney = $fruitMachine > 0 ? $fruitMachine : 0;//水果机金币发放
		$fruitWinMoney  = $fruitMachine < 0 ? abs($fruitMachine) : 0;//水果机金币消耗
		
		$racingLostMoney = $racing > 0 ? $racing : 0;//小马快跑
		$racingWinMoney  = $racing < 0 ? abs($racing) : 0;//小马快跑
		$racingMoney     = $racing * -1;//小马快跑金币消耗
		
		$buyuLostMoney   = $buyu > 0 ? $buyu : 0;//捕鱼金币发放
		$buyuWinMoney    = $buyu < 0 ? abs($buyu) : 0;//捕鱼金币消耗
		$buyuMoney       = $buyu * -1;//捕鱼金币消耗
		
		$toradoraLostMoney = $toradora > 0 ? $toradora : 0;//龙虎斗发放
		$toradoraWinMoney  = $toradora < 0 ? abs($toradora) : 0;//龙虎斗消耗
		$toradoraMoney     = $toradora * -1;//龙虎斗金币消耗

		if($gameid != 5){
			$magicMoney = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000"),'magiccoin');//魔法表情消耗
			$taskMoney  = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000"),'taskcoin');//局数任务发放
		}
		$fruitChatMoney = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level NOT IN (4,5,6,7,8,9,10,11)"),'chatcost');//水果机聊天消耗金币
		
		if($gameid == 4){
			$fflMoney       = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("level = 5"));//翻翻乐金币消耗
			$fflLostMoney   = $fflMoney > 0 ? $fflMoney : 0;//翻翻乐发放
			$fflWinMoney    = $fflMoney < 0 ? abs($fflMoney) : 0;//翻翻乐消耗
			$fflMoney       = $fflMoney * -1;//翻翻乐消耗
			$helpMoney      = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000"),'helpcoin');//提示消耗金币(斗牛)
			$baiRenMoney    = Stat::factory()->getBaiRenMoney($stime,$etime);//斗牛百人场金币消耗
			$bairen_tax     = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","money >= 0","level = 8"),'tax');//百人场台费（消耗）
		}
		
		if(in_array($gameid, array(6,7))){
			$level          = $gameid == 6 ? 26 : 6;
			$robotMoney     = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid<1000","level = $level"));//机器人输赢
			$robotTax       = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid<1000","level = $level"),'tax');//机器人税收
			$progressive    = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>1000","level = $level"),'taskcoin');//玩家在奖池获得的金币
			$baiRenMoney    = $robotMoney + $robotTax - $progressive;
		}
		
		if($gameid == 3){
			//玩家报名消耗
			$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","ctype!=30","level>=5","level<=12"));
			$matchBaoMing = abs($matchBaoMing);
			//比赛奖励发放
			$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","level>=5","level<=12","money>0"));
		}
	
		$robotLostMoney = $robotLostMoney - $robotLostTax;
		$robotWinMoney  = $robotWinMoney  + $robotWinTax;
		$robotBalance   = $robotWinMoney  - $robotLostMoney;//机器人金币平衡数

		$total_grant       = $robotLostMoney + $aWinLogGrantMoney['all'][0]   + $fruitLostMoney + $taskMoney + $racingLostMoney +$matchLostJiangli + $toradoraLostMoney + $fflLostMoney + $buyuLostMoney;//发放总额 = 各项金币发放+机器人输金币+水果机金币发放+局数任务+小马快跑+比赛奖励+龙虎斗+翻翻乐+捕鱼
		$total_recycle     = $robotWinMoney  + $aWinLogRecycleMoney['all'][0] + $tax + $fruitWinMoney + $magicMoney + $helpMoney + $fruitChatMoney + (int)$bairen_tax + $baiRenMoney + $racingWinMoney + $matchBaoMing + $toradoraWinMoney + $fflWinMoney + $buyuWinMoney + $double + $nmt + $change;//消耗总额 = 各项金币消耗+机器人赢金币+台费+水果机金币消耗+百人场台费+百人场机器人赢金币+小马快跑+比赛报名费用+龙虎斗+翻翻乐消耗+捕鱼+金花翻倍+禁比+换牌
		$total_diff        = $total_grant    - $total_recycle;//发放消耗差额
		$anteayerInventoryMoney = (int)Stat::factory()->getStatSum($gameid,array("date='$anteayer'","itemid=35"));//前天实际库存
		$anteayerInventoryMoney = $anteayerInventoryMoney == 0 ? $total_inventory_money : $anteayerInventoryMoney;
		//金币平衡数 = 昨天实际库存 - （前天实际库存+理论库存）
		$balanceMoney = $total_inventory_money - ($anteayerInventoryMoney + $total_diff);
		$reality_consume = $aWinLogRecycleMoney['all'][0] + $tax + (int)$helpMoney + (int)$fruitWinMoney + (int)$magicMoney + (int)$fruitChatMoney+ (int)$bairen_tax + $baiRenMoney + $racingWinMoney + $matchBaoMing + $toradoraWinMoney+ $fflWinMoney + $buyuWinMoney + $double + $nmt + $change; //实际消耗 = 真实玩家各项金币消耗+真实玩家台费+提示消耗金币(斗牛)+龙虎斗
		$reality_grant   = $aWinLogGrantMoney['all'][0] + (int)$taskMoney + $fruitLostMoney + $racingLostMoney + $matchLostJiangli + $toradoraLostMoney + $fflLostMoney + $buyuLostMoney;//实际发放 = 真实玩家各项金币发放
		$reality_diff    = $reality_grant - $reality_consume;//金币实际差额 = 实际发放 - 实际消耗;
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>32,'amount'=>$total_grant));//发放总额
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>33,'amount'=>$total_recycle));//消耗总额
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>34,'amount'=>$total_diff));//发放消耗差额
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>35,'amount'=>$total_inventory_money));//昨天库库金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>36,'amount'=>$robotBalance));//机器人金币平衡数
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>37,'amount'=>$balanceMoney));//金币平衡数
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>43,'amount'=>$robotLostMoney));//机器人输金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>44,'amount'=>$robotWinMoney));//机器人赢金币
		Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>67,'amount'=>$tax));//台费（消耗）
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>68,'amount'=>$reality_grant));//实际发放
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>69,'amount'=>$reality_consume));//实际消耗
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>70,'amount'=>$reality_diff));//实际消耗
	    
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>75,'amount'=>$fruitWinMoney));//水果机金币消耗
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>74,'amount'=>$fruitLostMoney));//水果机金币发放
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>78,'amount'=>$magicMoney));//魔法表情消耗
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>79,'amount'=>$helpMoney));//斗牛点提示消耗
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>80,'amount'=>$fruitChatMoney));//水果机聊天消耗
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>81,'amount'=>$taskMoney));//局数任务奖励
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>130,'amount'=>$baiRenMoney));//斗牛百人场金币消耗
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>140,'amount'=>$bairen_tax));//斗牛百人场台费消耗
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>156,'amount'=>$racingMoney));//小马快跑
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>175,'amount'=>$matchLostJiangli));//比赛奖励
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>176,'amount'=>$matchBaoMing));//比赛报名费用
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>184,'amount'=>$toradoraMoney));//龙虎斗消耗
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>226,'amount'=>$toradora_tax));//龙虎斗台费
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>260,'amount'=>$buyuMoney));//捕鱼
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>253,'amount'=>$fflMoney));//新版翻翻乐
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>295,'amount'=>$double));//金花翻倍
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>296,'amount'=>$nmt));//金花禁比
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>297,'amount'=>$change));//金花换牌
	    Stat::factory()->setStatSum($gameid,array('date'=>$yesterday,'itemid'=>312,'amount'=>$progressive));//德州彩池发放
	    
	    $all_anteayerInventoryMoney = $anteayerInventoryMoney + (int)$all_anteayerInventoryMoney;
	    $all_total_grant            = $total_grant            + (int)$all_total_grant;
	    $all_total_recycle          = $total_recycle          + (int)$all_total_recycle;
	    $all_total_inventory_money  = $total_inventory_money  + (int)$all_total_inventory_money;
	    $all_robotBalance           = $robotBalance           + (int)$all_robotBalance;
	    $all_robotLostMoney         = $robotLostMoney         + (int)$all_robotLostMoney;
	    $all_robotWinMoney          = $robotWinMoney          + (int)$all_robotWinMoney;
	    $all_tax                    = $tax                    + (int)$all_tax;
	    $all_reality_grant          = $reality_grant          + (int)$all_reality_grant;
	    $all_reality_consume        = $reality_consume        + (int)$all_reality_consume;
	    $all_reality_diff           = $reality_diff           + (int)$all_reality_diff;
	    $all_fruitWinMoney          = $fruitWinMoney          + (int)$all_fruitWinMoney;
	    $all_fruitLostMoney         = $fruitLostMoney         + (int)$all_fruitLostMoney;
	}
	
	$all_total_inventory_money = Stat::factory()->statGameInfo();//玩家身上库存金币
	Loader_Redis::common()->set('inventory|'.$yesterday,$all_total_inventory_money,false,false,7*24*3600);//用redis暂存每天全局的库存金币
	$last_total_inventory_money = Loader_Redis::common()->get('inventory|'.$anteayer,false,false);
	$all_anteayerInventoryMoney = $last_total_inventory_money ? $last_total_inventory_money : $all_anteayerInventoryMoney;
	
	$all_robotBalance   = $all_robotWinMoney  - $all_robotLostMoney;//机器人金币平衡数
	
	$all_total_diff     = $all_total_grant - $all_total_recycle;
	$all_balanceMoney   = $all_total_inventory_money - ($all_anteayerInventoryMoney + $all_total_diff);
	$all_reality_diff   = $all_reality_grant - $all_reality_consume;//金币实际差额 = 实际发放 - 实际消耗;
	
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>32,'amount'=>$all_total_grant));//发放总额
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>33,'amount'=>$all_total_recycle));//消耗总额
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>34,'amount'=>$all_total_diff));//发放消耗差额
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>35,'amount'=>$all_total_inventory_money));//昨天库库金币
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>36,'amount'=>$all_robotBalance));//机器人金币平衡数
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>37,'amount'=>$all_balanceMoney));//金币平衡数
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>43,'amount'=>$all_robotLostMoney));//机器人输金币
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>44,'amount'=>$all_robotWinMoney));//机器人赢金币
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>67,'amount'=>$all_tax));//台费（消耗）
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>68,'amount'=>$all_reality_grant));//实际发放
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>69,'amount'=>$all_reality_consume));//实际消耗
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>70,'amount'=>$all_reality_diff));//实际消耗
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>75,'amount'=>$all_fruitWinMoney));//水果机金币消耗
	Stat::factory()->setStatSum(0,array('date'=>$yesterday,'itemid'=>74,'amount'=>$all_fruitLostMoney));//水果机金币发放
		
	$_etime    = microtime(true);
	$crosstime = $_etime - $_stime;
	Logs::factory()->debug(array('stat_money_crosstime'=>$crosstime),'stat_money_time');
}


if($timehi == '0500'){//按客户端和渠道统计相关金币
	foreach ($aGame as $gameid=>$gameName) {
		foreach ($aCid as $cid=>$cname) {
			$progressive = $change = $nmt = $double = $buyuMoney = $fruitWinMoney = $fruitLostMoney = $fflMoney = $magicMoney = $taskMoney = $helpMoney = $fruitChatMoney = $baiRenMoney = $bairen_tax = $matchBaoMing = $matchLostJiangli = $fruitMachine = 0;
			$fruitMachine = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=1","cid=$cid"));//水果机金币
			$racing       = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=2","cid=$cid"));//小马快跑金币
			$toradora     = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=3","cid=$cid"));//龙虎斗金币
			$buyu         = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level>=4","level<=7","cid=$cid"));//捕鱼金币
			$buyuNew      = Stat::factory()->statFishSum($stime, $etime, array("cid=$cid"), "endmoney-stmoney", $gameid);//捕鱼新表
			$buyu         = $buyu + $buyuNew;
			
			$fruitLostMoney = $fruitMachine > 0 ? $fruitMachine : 0;//水果机金币发放
			$fruitWinMoney  = $fruitMachine < 0 ? abs($fruitMachine) : 0;//水果机金币消耗
			
			$racingMoney       = $racing * -1;//小马快跑金币消耗
			$toradoraMoney     = $toradora * -1;//龙虎斗金币消耗
			$buyuMoney         = $buyu * -1;//捕鱼金币消耗
			$tax               = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","cid=$cid"),'tax');//台费（消耗）
			
			if($gameid != 5){
				$magicMoney = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","cid=$cid"),'magiccoin');//魔法表情消耗
				$taskMoney  = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","cid=$cid"),'taskcoin');//局数任务发放
			}
			$fruitChatMoney = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","cid=$cid","level NOT IN (4,5,6,7,8,9,10,11)"),'chatcost');//水果机聊天消耗金币
			
			if($gameid == 4){
				$fflMoney       = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("level = 5","cid=$cid"));//翻翻乐金币消耗
				$fflLostMoney = $fflMoney > 0 ? $fflMoney : 0;//翻翻乐发放
				$fflWinMoney  = $fflMoney < 0 ? abs($fflMoney) : 0;//翻翻乐消耗
				$fflMoney     = $fflMoney * -1;//翻翻乐消耗
				$helpMoney  = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","cid=$cid"),'helpcoin');//提示消耗金币(斗牛)
				$bairen_tax = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","money >= 0","level = 8","cid=$cid"),'tax');//百人场台费（消耗）
			}
			
			if(in_array($gameid, array(6,7))){
				$baiRenMoney    = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid<1000","cid=$cid","level = 26"));//百人场金币消耗
				$progressive    = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>1000","cid=$cid","level = 26"),'taskcoin');//奖池发放
			}
			
			if($gameid == 3){
				//玩家报名消耗
				$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","ctype!=30","level>=5","level<=12","cid=$cid"));
				$matchBaoMing = abs($matchBaoMing);
				
				//比赛奖励发放
				$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","level>=5","level<=12","money>0","cid=$cid"));
			}
			
			if($gameid == 7){
				$double = Stat::factory()->statLogPlay(7,$stime,$etime,array("cid=$cid"),'`double`');//翻倍回收
				$nmt    = Stat::factory()->statLogPlay(7,$stime,$etime,array("cid=$cid"),'`nmt`');//禁比回收
				$change = Stat::factory()->statLogPlay(7,$stime,$etime,array("cid=$cid"),'`change`');//换牌回收
			}

			$fruitWinMoney  && Stat::factory()->setStatSum($gameid,array('itemid'=>75,'cid'=>$cid,'date'=>$yesterday,'amount'=>$fruitWinMoney));
			$fruitLostMoney && Stat::factory()->setStatSum($gameid,array('itemid'=>74,'cid'=>$cid,'date'=>$yesterday,'amount'=>$fruitLostMoney));
			$magicMoney     && Stat::factory()->setStatSum($gameid,array('itemid'=>78,'cid'=>$cid,'date'=>$yesterday,'amount'=>$magicMoney));
			$taskMoney      && Stat::factory()->setStatSum($gameid,array('itemid'=>81,'cid'=>$cid,'date'=>$yesterday,'amount'=>$taskMoney));
			$helpMoney      && Stat::factory()->setStatSum($gameid,array('itemid'=>79,'cid'=>$cid,'date'=>$yesterday,'amount'=>$helpMoney));
			$fruitChatMoney && Stat::factory()->setStatSum($gameid,array('itemid'=>80,'cid'=>$cid,'date'=>$yesterday,'amount'=>$fruitChatMoney));
			$baiRenMoney    && Stat::factory()->setStatSum($gameid,array('itemid'=>130,'cid'=>$cid,'date'=>$yesterday,'amount'=>$baiRenMoney));//百人场金币消耗
			$bairen_tax     && Stat::factory()->setStatSum($gameid,array('itemid'=>140,'cid'=>$cid,'date'=>$yesterday,'amount'=>$bairen_tax));//百人场台费消耗
			$tax            && Stat::factory()->setStatSum($gameid,array('itemid'=>64,'cid'=>$cid,'date'=>$yesterday,'amount'=>$tax));//台费
			$racingMoney    && Stat::factory()->setStatSum($gameid,array('itemid'=>156,'cid'=>$cid,'date'=>$yesterday,'amount'=>$racingMoney));//小马快跑
			$toradoraMoney  && Stat::factory()->setStatSum($gameid,array('itemid'=>184,'cid'=>$cid,'date'=>$yesterday,'amount'=>$toradoraMoney));//龙虎斗
			$matchLostJiangli && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>175,'amount'=>$matchLostJiangli));//比赛奖励
	    	$matchBaoMing     && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>176,'amount'=>$matchBaoMing));//比赛报名费用
	    	$buyuMoney        && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>260,'amount'=>$buyuMoney));//捕鱼金币回收
	    	$fflMoney       &&	 Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>253,'amount'=>$fflMoney));//翻翻乐
	    	$double        && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>295,'amount'=>$double));//金花翻倍
		    $nmt           && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>296,'amount'=>$nmt));//金花禁比
		    $change        && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>297,'amount'=>$change));//金花换牌
		    $progressive   && Stat::factory()->setStatSum($gameid,array('cid'=>$cid,'date'=>$yesterday,'itemid'=>312,'amount'=>$progressive));//德州彩池发放
		}
		
		foreach ($aCtype as $ctype=>$clientname) {
			$fruitMachine   = 0;
			$fruitMachine   = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=1","ctype=$ctype"));//水果机金币
			$racing         = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=2","ctype=$ctype"));//小马快跑金币
			$toradora       = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level=3","ctype=$ctype"));//龙虎斗金币
			$buyu           = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","level>=4","level<=7","ctype=$ctype"));//捕鱼金币
			$buyuNew        = Stat::factory()->statFishSum($stime, $etime, array("ctype=$ctype"), "endmoney-stmoney", $gameid);//捕鱼新表
			$buyu           = $buyu + $buyuNew;
			$fruitLostMoney = $fruitMachine > 0 ? $fruitMachine : 0;//水果机金币发放
			$fruitWinMoney  = $fruitMachine < 0 ? abs($fruitMachine) : 0;//水果机金币消耗
			

			$racingMoney     = $racing * -1;//小马快跑金币消耗
			$toradoraMoney   = $toradora * -1;//龙虎斗金币消耗
			$buyuMoney       = $buyu * -1;//捕鱼金币消耗
			
			$tax             = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","ctype=$ctype"),'tax');//台费（消耗）

			$progressive = $change = $nmt = $double = $buyuMoney = $fruitWinMoney = $fruitLostMoney = $fflMoney = $magicMoney = $taskMoney = $helpMoney = $fruitChatMoney = $baiRenMoney = $bairen_tax = $matchBaoMing = $matchLostJiangli = $fruitMachine = 0;
						
			if($gameid != 5){
				$magicMoney = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","ctype=$ctype"),'magiccoin');//魔法表情消耗
				$taskMoney  = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","ctype=$ctype"),'taskcoin');//局数任务发放
			}
			$fruitChatMoney = Stat::factory()->statLogPlay(5,$stime,$etime,array("gid=$gameid","ctype=$ctype","level NOT IN (4,5,6,7,8,9,10,11)"),'chatcost');//水果机聊天消耗金币
			
			if($gameid == 4){
				$fflMoney       = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("level = 5","ctype=$ctype",));//翻翻乐金币消耗
				$fflLostMoney = $fflMoney > 0 ? $fflMoney : 0;//翻翻乐发放
				$fflWinMoney  = $fflMoney < 0 ? abs($fflMoney) : 0;//翻翻乐消耗
				$fflMoney     = $fflMoney * -1;//翻翻乐消耗
				$helpMoney  = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","ctype=$ctype"),'helpcoin');//提示消耗金币(斗牛)
				$bairen_tax = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>=1000","money >= 0","level = 8","ctype=$ctype"),'tax');//百人场台费（消耗）
			}
			
			if(in_array($gameid, array(6,7))){
				$baiRenMoney    = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid<1000","ctype=$ctype","level = 26"));//百人场金币消耗
				$progressive    = Stat::factory()->statLogPlay($gameid,$stime, $etime, array("mid>1000","ctype=$ctype","level = 26"),'taskcoin');//奖池发放
			}
			
			if($gameid == 3){
				//玩家报名消耗
				$matchBaoMing = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype in (20,21)","ctype!=30","level>=5","level<=12","ctype=$ctype"));
				$matchBaoMing = abs($matchBaoMing);
				
				//比赛奖励发放
				$matchLostJiangli = Stat::factory()->statLogPlay($gameid,$stime,$etime,array("endtype=22","level>=5","level<=12","money>0","ctype=$ctype"));
			}
			
			if($gameid == 7){
				$double = Stat::factory()->statLogPlay(7,$stime,$etime,array("ctype=$ctype"),'`double`');//翻倍回收
				$nmt    = Stat::factory()->statLogPlay(7,$stime,$etime,array("ctype=$ctype"),'`nmt`');//禁比回收
				$change = Stat::factory()->statLogPlay(7,$stime,$etime,array("ctype=$ctype"),'`change`');//换牌回收
			}

			$fruitWinMoney  && Stat::factory()->setStatSum($gameid,array('itemid'=>75,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$fruitWinMoney));
			$fruitLostMoney && Stat::factory()->setStatSum($gameid,array('itemid'=>74,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$fruitLostMoney));
			$magicMoney     && Stat::factory()->setStatSum($gameid,array('itemid'=>78,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$magicMoney));
			$taskMoney      && Stat::factory()->setStatSum($gameid,array('itemid'=>81,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$taskMoney));
			$helpMoney      && Stat::factory()->setStatSum($gameid,array('itemid'=>79,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$helpMoney));
			$fruitChatMoney && Stat::factory()->setStatSum($gameid,array('itemid'=>80,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$fruitChatMoney));
			$baiRenMoney    && Stat::factory()->setStatSum($gameid,array('itemid'=>130,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$baiRenMoney));//百人场金币消耗
			$bairen_tax     && Stat::factory()->setStatSum($gameid,array('itemid'=>140,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$bairen_tax));//百人场台费消耗
			$tax            && Stat::factory()->setStatSum($gameid,array('itemid'=>64,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$tax));//台费
			$racingMoney    && Stat::factory()->setStatSum($gameid,array('itemid'=>156,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$racingMoney));//小马快跑
			$toradoraMoney  && Stat::factory()->setStatSum($gameid,array('itemid'=>184,'ctype'=>$ctype,'date'=>$yesterday,'amount'=>$toradoraMoney));//龙虎斗
			$matchLostJiangli && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>175,'amount'=>$matchLostJiangli));//比赛奖励
	    	$matchBaoMing     && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>176,'amount'=>$matchBaoMing));//比赛报名费用
	    	$fflMoney         && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>253,'amount'=>$fflMoney));//翻翻乐
	    	$buyuMoney        && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>260,'amount'=>$buyuMoney));//捕鱼金币回收
	    	$double        && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>295,'amount'=>$double));//金花翻倍
		    $nmt           && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>296,'amount'=>$nmt));//金花禁比
		    $change        && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>297,'amount'=>$change));//金花换牌
		    $progressive   && Stat::factory()->setStatSum($gameid,array('ctype'=>$ctype,'date'=>$yesterday,'itemid'=>312,'amount'=>$progressive));//德州彩池发放
		}
	}
}

if($timehi == '0050' || $_GET['test']){	
	foreach ($aGame as $gameid=>$gameName) {
		//金币流水统计
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
		}
		
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

if($timehi == '0250' ){	//统计昨天收入省份分布
	Stat::factory()->provincePayStat();
}
