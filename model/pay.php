<?php !defined('IN WEB') AND exit('Access Denied!');

class Pay extends Config_Table{
	
	private  static $_instance ;

    /**
     * @return Pay 
     */
    public static function factory(){
        if(!is_object(self::$_instance)){
            self::$_instance = new Pay;
        }

        return self::$_instance;        
    }
    
    /**
     * 支付中心通知发货
     */
    public function payOrder($param){
    	$gameid       = Helper::uint($param['gameid']);
    	$mid          = Helper::uint($param['mid']);
    	$cid          = Helper::uint($param['cid']);
    	$pid          = Helper::uint($param['pid']);
    	$ip           = $param['ip'];
    	$sid          = Helper::uint($param['sid']);
    	$ctype        = Helper::uint($param['ctype']);
    	$pmode        = Helper::uint($param['pmode']);
    	$pamount      = floatval($param['pamount']);
    	$pexchangenum = Helper::uint($param['pexchangenum']);
    	$ptype        = Helper::uint($param['ptype']);
    	$viptime      = Helper::uint($param['viptime']);
    	$pdealno      = $param['pdealno'];
    	$wmconfig     = $param['wmconfig'];
    	$oldsource    = $source = Helper::uint($param['source']);
    	$versions     = $param['versions'] ? $param['versions'] : '';
    	$source       = $source >= 100 ? (int)($source/100) : $source;
    	
    	
    	if( !$gameid || !$mid || !$cid || !$sid || !$ctype || !$pmode ||!$pamount ){
    		return -2;
    	}

    	if($ptype==3 && !$viptime){
    		return -3;
    	}
    	
   		$gameInfo = Member::factory()->getGameInfo($mid);  	
   		
   		if(!$gameInfo){
   			//return -4;
   		}
   		
    	$payInfo = $this->getPayByDealNo($pdealno,2);
   		if($payInfo){
   			return -5;
   		}
   		
   		$wmode    = $pmode == 11 ? 14 : 2;
   		$wmcfg = '';
   		$pmode == 11 && $ptype == 5 && $ptype = 1;//处理点卡中的无密码卡（金币）
   		$pmode == 11 && $ptype == 6 && $ptype = 3;//处理点卡中的无密码卡(会员+金币)
   		$pmode != 11 && Loader_Redis::ote($mid)->hIncrBy(Config_Keys::other($mid), 'pamt', $pamount);//充值用户，写入ote
   		
   		switch ($ptype) {
    		case 1:// 金币
    			if($pmode != 11){//不是点卡充值
	    			$viptype = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);
	    			if($source !=6 && $viptype == 100){//年会员 加赠
	    				$pexchangenum = ceil($pexchangenum * 0.03 + $pexchangenum);
	    			}
    			}
   				
				$time  	= time();
				$today 	= date('Y-m-d H:i:s',$time);
				$keyday = date('Y-m-d',$time);
				
				//用户当日累计充值金额
				$hadKey = "chongzhi_".$mid."_".$keyday;
				Loader_Redis::common()->incr($hadKey,$pamount,30*24*3600);
				
				if(0.1 == $pamount){//移动MM0.1元只能购买一次
					Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'mmp', 1);
				}
				
				if($pamount >= 50){//购买50元以上金币数1-3个小喇叭
					$num = $pamount > 50 ? mt_rand(1, 3) : 1;
					Loader_Redis::ote($mid)->hIncrBy(Config_Keys::other($mid), 'horn', $num);
					Logs::factory()->debug($mid.'|'.$pamount.'|'.$num,'give_horn');
				}
				
   				if($source == 1){//快速充值
   					
   					if($versions >= '3.0.0' || in_array($gameid, array(5,7))){
						Logs::factory()->setRoll($gameid, $mid, $sid, $cid, $pid, $ctype, Config_Money::$fastPayRewardRoll,0,8);
   					}else{
						Member::factory()->setVip($mid, $gameid, 1);
   					}
   					//Loader_Redis::ote($mid)->hIncrBy(Config_Keys::other($mid), 'horn', 1);
   					Loader_Udp::stat()->sendData(126, $mid, $gameid, $ctype, $cid, $sid, $pid, 0);//上报统计中心
    			 }
				
   				if($source == 2 ){//首冲 
   					$wmode = 29;
   					$rewardMoney = Base::factory()->firstPay($mid, $gameid, $pamount,$sid, $cid, $pid, $ctype,$versions);
   					Loader_Udp::stat()->sendData(113, $mid, $gameid, $ctype, $cid, $sid, $pid, 0);//上报统计中心
    			 }
    			     			 	
    			 if($source == 3){//破产开宝箱充值
    			 	$wmode = 30;
    			 	$rewardMoney = Base::factory()->bankruPtpay($mid, $gameid, $sid, $cid, $pid, $ctype, $pamount);
    			 	Loader_Udp::stat()->sendData(114, $mid, $gameid, $ctype, $cid, $sid, $pid, 0);//上报统计中心
    			 }
    			 
    			 //短信充值不了，支付宝和银联充值优惠
		   		 //$hi = date("H");
		    	// if($hi >= 8 && $hi < 18){
			    	 //if( in_array($pmode,array(1,3)) && in_array($pamount, array(2,5,6,10)) ){
	    			 	//$addMoney = $pamount * 10000 * 0.03;
	    			 //}
		    	 //}

				$pexchangenum = $pexchangenum + (int)$rewardMoney + (int)$addMoney;
				
   		 		if($source == 4){//限时抢购
    			 	$flag = Loader_Redis::common()->get(Config_Keys::limitqiang($gameid, $mid),false,false);
    			 	if($flag){
    			 		Loader_Udp::stat()->sendData(155, $mid, $gameid, $ctype, $cid, $sid, $pid, 0);//上报统计中心
    			 		$pexchangenum = $pamount * 10000 * 1.5;
    			 		Loader_Redis::common()->delete(Config_Keys::limitqiang($gameid, $mid));
    			 	}
    			}
    			
    			if($source == 5){//活动网页充值
    				$time = date("Y-m-d");
    				Loader_Redis::common()->hIncrBy("chognzhiRecord|$gameid|$ctype|$time", $mid, $pamount);
    				Loader_Redis::common()->setTimeout("chognzhiRecord|$gameid|$ctype|$time", 30*24*3600);
    				
    				$rate = Loader_Redis::common()->hGet("chongzhibeishu|$time", $mid);//抽奖概率
    				$paycount = Loader_Redis::common()->incr("xingyunpaycount|$mid",1,Helper::time2morning());
    				if($rate && $paycount <=5 ){
    					Logs::factory()->debug(array('rate'=>$rate,'pexchangenum'=>$pexchangenum,'mid'=>$mid),'pay_source_rate');
    					$pexchangenum = $pexchangenum * $rate;
    					Loader_Redis::common()->incr("xingyunpay|$time|$gameid|$ctype", $pexchangenum, 15*24*3600);//网页充值活动充值统计
    					Loader_Redis::common()->incr("xingyunpayman|$time|$gameid|$ctype", 1, 15*24*3600);//成功充值人数统计
    					Loader_Redis::common()->incr("xingyunpayamount|$time|$gameid|$ctype", $pamount, 15*24*3600);//成功充值总金额
    				}
    			}
    			
   				if($source == 6){//活动网页充值狂欢
    				$pexchangenum = $this->doActionPay($mid, $pamount, $pexchangenum);
    			}

   				$flag = Logs::factory()->addWin($gameid,$mid, $wmode, $sid, $cid, $pid, $ctype, 0, $pexchangenum);
   				
   				if(!$flag){//加钱失败
   					return 0;
   				}
   				
    		break;
    		case 2:	
                if($cid==8)//如果是天涯渠道
                {//乐券
    		       $flag = Logs::factory()->setRoll($gameid,$mid,$sid, $cid, $pid,$ctype,$pexchangenum,0,6);
                   $flag = Logs::factory()->addWin($gameid,$mid, $wmode, $sid, $cid, $pid, $ctype, 0, 8888);
                   Member::factory()->setVip($mid, $gameid, 3);
                }
    		break;
    		case 3:	//	会员卡+金币
    			$flag = Logs::factory()->addWin($gameid,$mid, $wmode, $sid, $cid, $pid, $ctype, 0, $pexchangenum);
    			Member::factory()->setVip($mid, $gameid, $viptime);
    			
    			if ($source == 5){//皇家礼包活动
    			    $time = date("Y-m-d");
    			    Loader_Redis::common()->set("huangjialibao_$mid", $time, false, false, Helper::time2someday(30));//金币领取凭证
    			    
    			    Loader_Redis::common()->hIncrBy("huangjialibao_Bought_$time", $mid, 1);//购买人数统计
    			    Loader_Redis::common()->setTimeout("huangjialibao_Bought_$time", 30*24*3600);
    			    
    			    Loader_Redis::common()->hIncrBy("huangjialibao_Canyuci_$time", $mid, 1);//领取人数统计
    			    Loader_Redis::common()->setTimeout("huangjialibao_Canyuci_$time", 30*24*3600);
    			    
    			    Loader_Redis::common()->set("huangjialibao_Record_$mid", 1, false, false, Helper::time2morning());//领取记录统计
    			}
    			
    		break;
    		case 4:	//小喇叭
    			if($pexchangenum){//个数
    				Loader_Redis::ote($mid)->hIncrBy(Config_Keys::other($mid), 'horn', $pexchangenum);
    			}
    		
    		break;
    		case 5:	//理财产品
    			if(!$wmconfig){
    				return -2;
    			}
    			$wmcfg = $this->setWmConfig($wmconfig, $mid, $gameid, $mid, $wmode, $sid, $cid, $pid, $ctype);
    			if(!$wmcfg){
    				return -5;
    			}
    		break;
    		
    		case 6:	//记牌器
    			if($viptime){//天数
    				$exptime = (int)$viptime;
					$vipexptime = Loader_Redis::account()->ttl(Config_Keys::props($gameid,$mid));//如果之前购买了道具，则天数累加
					$vipexptime = Helper::uint($vipexptime) ?  ceil(Helper::uint($vipexptime)/86400) : 0;
					$exptime = $exptime + $vipexptime;
					Loader_Redis::account()->set(Config_Keys::props($gameid,$mid), 1,false,false,24*3600*$exptime);
					Loader_Tcp::callServer($mid)->setMoney($mid,0);//通知server 处理在房间玩牌的情况
    			}
    			
    		break;
    		
    		case 7:	//捕鱼黄金之角
    		    if($gameid==5){
    		    	$flag = Logs::factory()->addWin($gameid,$mid, $wmode, $sid, $cid, $pid, $ctype, 0, $pexchangenum);
    			    Member::factory()->setGunStyle($mid, $gameid, 2, $viptime);
    		    }
		    break;
		    
		  case 8:	//捕鱼水晶风暴
    		    if($gameid==5){
    		    	$flag = Logs::factory()->addWin($gameid,$mid, $wmode, $sid, $cid, $pid, $ctype, 0, $pexchangenum);
    			    Member::factory()->setGunStyle($mid, $gameid, 3, $viptime);
    		    }
		    break;  
    	}
    	
    	$moneyNow = $gameInfo['money'] ? $gameInfo['money'] : '';
    	
    	if(in_array($mid, Config_Pay::$specialAccount)){
    		return 1;
    	}

    	$sql = "INSERT INTO $this->payment VALUES ('',$mid,$gameid,'$versions','$moneyNow',$sid,$cid,$pid,'$ip',$ctype,$pmode,$ptype,$pamount,1,$pexchangenum,'$oldsource','$wmcfg','$viptime','$pdealno','',".NOW.",2 )"; 
   		Loader_Mysql::DBMaster()->query($sql);
    	  		
   		return (int)Loader_Mysql::DBMaster()->affectedRows();   		
    }  
    
    public function doActionPay($mid,$pamount,$pexchangenum){
    	
    	$config           = Config_Pay::$actReward;
    	$maxReward        = $config[$pamount];
    	$minReward        = ceil($config[$pamount] / 2);
    	$rank             = mt_rand($minReward, $maxReward);
    	$rankReward       = ceil($rank / 1000) * 1000;
    	$org_pexchangenum = $pexchangenum;
    	$pexchangenum     = $pexchangenum + $rankReward;
      	
    	if(in_array($mid, Config_Pay::$specialAccount)){
    		
    		$amount_config = array_keys(Config_Pay::$big2money);
    		if(!in_array($pamount, $amount_config)){
    			return $pexchangenum;
    		}
    		
    		foreach (Config_Pay::$sp_user as $groupID=>$aMid) {
    			if(in_array($mid, $aMid)){
    				$_groupID = $groupID;
    				break;
    			}
    		}
    		
    		if(!$_groupID){
    			Logs::factory()->debug(array($mid,$pamount,$pexchangenum),'doActionPay_nogroupid');
    			return $pexchangenum;
    		}
    		
    		$arr_rank         = Config_Pay::$sprank[$pamount];
    		$rank             = mt_rand($arr_rank[0], $arr_rank[1]);
    		$rankReward       = ceil($rank / 1000) * 1000;
    		$pexchangenum     = $org_pexchangenum + $rankReward;
    		
    		Loader_Redis::common()->hIncrBy(Config_Keys::sau($_groupID), 'amount', $pamount);
    		Loader_Redis::common()->hIncrBy(Config_Keys::sau($_groupID), 'exchangenum', $pexchangenum);
    		$rows = Loader_Redis::common()->hGetAll(Config_Keys::sau($_groupID));
    		$_amount      = $rows['amount'];
    		$_exchangenum = $rows['exchangenum'];

    		if(Config_Pay::$group_money[$_groupID]['amount'] == 0){//无限制
    			$ot = Config_Pay::$big2money[$pamount] - $pexchangenum;
    			Loader_Redis::common()->hIncrBy(Config_Keys::sau($_groupID), 'exchangenum', $ot);
    			$pexchangenum = Config_Pay::$big2money[$pamount] ? Config_Pay::$big2money[$pamount] : $pexchangenum;
    		}else{//有限制
	    		if ($_amount >= Config_Pay::$group_money[$_groupID]['amount']) {
	    			
	    			$lt = Config_Pay::$group_money[$_groupID]['chip'] - $_exchangenum;

	    			if($_amount > Config_Pay::$group_money[$_groupID]['amount']){
	    				$rd = ($_amount - Config_Pay::$group_money[$_groupID]['amount']) * 10000 * 1.3;
	    			}
	    			
	    			$ot = Helper::uint($lt) + Helper::uint($rd);
	    			Loader_Redis::common()->hIncrBy(Config_Keys::sau($_groupID), 'exchangenum', $ot);
	    			$pexchangenum = $pexchangenum + $ot;
	    		}
    		}
    	}

    	Loader_Redis::common()->setTimeout(Config_Keys::sau($_groupID), Helper::time2morning());
    	Logs::factory()->debug(array($_groupID,$mid,$pamount,$pexchangenum),'doActionPay');

    	return $pexchangenum;
    }
    
    private function setWmConfig($wmconfig,$mid,$gameid,$mid, $wmode, $sid, $cid, $pid, $ctype){
    	$cfg    = json_decode($wmconfig,true);
    	$gid    = key($cfg);
    	$values = array_values($cfg);
    	$values = $values[0];
    	
    	Logs::factory()->debug(array($wmconfig,$gid,$values),'setWmConfig');
    	
    	$isPurchased  = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), $gid);
    	if($isPurchased){
    		return false;
    	}
    	
    	$day1  = date("Ymd",NOW);
    	$day2  = date("Ymd",strtotime(" + 1 days"));
    	$day3  = date("Ymd",strtotime(" + 2 days"));
    	$day4  = date("Ymd",strtotime(" + 3 days"));
    	$day5  = date("Ymd",strtotime(" + 4 days"));
    	$day6  = date("Ymd",strtotime(" + 5 days"));
    	$day8  = date("Ymd",strtotime(" + 7 days"));
    	$day10 = date("Ymd",strtotime(" + 9 days"));
    	
    	$rewards = array($day1=>$values[0].':0',$day2=>$values[1].':0',$day3=>$values[2].':0',$day4=>$values[3].':0',$day5=>$values[4].':0',$day6=>$values[5].':0',$day8=>$values[6].':0',$day10=>$values[7].':0');
    	Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), $gid, json_encode($rewards));
    	
    	return $gid;
    }
    
    public function getPayByDealNo($pdealno,$status=2){
    	$pid = Loader_Mysql::DBMaster()->escape($pdealno);
    	$status = Helper::uint($status);
    	
    	if(!$pdealno){
    		return false;
    	}
    	
    	$sql = "SELECT * FROM $this->payment  WHERE pdealno='{$pdealno}' AND pstatus='{$status}'";
    	return Loader_Mysql::DBMaster()->getOne($sql);
    }
    
	/**
	 * 处理退款的退物品的所有操作
	 *
	 * @param int $pid 订单号
	 * @return int or string
	 */
	public function payback( $pdealno ) {

		if ( !$pdealno = Helper::uint($pdealno) ) {
			return 0;
		}

		$return = -1; //返回值

		$sql = "SELECT * FROM $this->payment WHERE pdealno='$pdealno' LIMIT 1";
		$aBack = Loader_Mysql::DBMaster()->getOne( $sql );
		
		if( ! $aBack['pdealno'] ) { //没有支付成功 -1,则返回
			return $return;
		}
		if ( $aBack['pstatus'] == 3 ) { //已退货
			return 1;
		}
		if ( $aBack['pstatus'] != 2 ) {
			return -2;
		}

		$query = "UPDATE $this->payment SET pstatus=3 WHERE pdealno='$pdealno' AND pstatus=2 LIMIT 1";
		Loader_Mysql::DBMaster()->query( $query );
		$return = Loader_Mysql::DBMaster()->affectedRows();
		
		if ( $return ) {
			if( $aBack['ptype']==1 ) {
				$flag = Logs::factory()->addWin($aBack['fmid'], 101, 13, 1, $aBack['pmoney'], "",$bid,"",true);//金币 pmode=13 是退单操作
			}else if( $aBack['ptype'] == 2 ) {
				Boyacoins::factory()->updateBoyaCoins( $aBack['fmid'],101, $aBack['pbycoins'], 1,$bid ,10, $aBack['pid'] );	//博雅币 pmode=10是退单操作
			}
		}

		return $return;
	}
	
	/**
	 * 
	 * 根据条件查找订单信息
	 * @param array $aParam   查找条件
	 * @param bool $isRank    是否排行
	 * @param bool $returnSql 是否返回sql语句
	 */
	public function getPaymentByCondition($aParam,$isRank=false,$returnSql=true){
		foreach( (array)$aParam as $key => $val ) {
			if($key == 'pdealno'){
				$aParam[$key]==Loader_Mysql::DBMaster()->escape($val);
			}else{
				$aParam[$key] = Helper::uint( $val );
			}
		}
		
		$bReturnSql = $returnSql === true ? true : false;

		$aWhere = array();
		$aParam['mid'] && $aWhere[] = " fmid = '{$aParam['mid']}' ";
		$aParam['pcard'] && $aWhere[] = " pcard = '{$aParam['pcard']}' ";
		$aParam['pstatus'] && $aWhere[] = " pstatus = '{$aParam['pstatus']}' ";
		$aParam['pdealno'] && $aWhere[] = " pdealno = '{$aParam['pdealno']}' ";
		$aParam['starttime'] && $aWhere[] = " ptime >= '{$aParam['starttime']}' ";
		$aParam['endtime'] && $aWhere[] = " ptime <= '{$aParam['endtime']}' ";
		$aParam['pmode'] && $aWhere[] = " pmode = '{$aParam['pmode']}' ";
		$aWhere[] = " pstatus = 2 ";
		
		$sWhere =  $aWhere ? implode( ' AND ', $aWhere ) : ' 1 ';
		
		if($isRank == true){
			$sql = "SELECT *,sum(pmoney) num FROM $this->payment WHERE $sWhere GROUP BY fmid DESC ORDER BY num DESC";
		}else{
			$sql = "SELECT * FROM $this->payment WHERE $sWhere ORDER BY pid DESC";
		}		

		return $bReturnSql ? $sql : Loader_Mysql::DBMaster()->getAll( $sql, MYSQL_ASSOC );
	}
	
	/**
	 * 
	 * 根据用户ID获取支付记录
	 * @param int $mid
	 * @param bool $returnSql
	 */
	public function getPayLogByMid($mid,$returnSql=true){
		
		$mid = Helper::uint($mid);
		
		$sql = "SELECT * FROM $this->payment WHERE fmid=$mid AND pstatus = 2 ";
		
		return $returnSql ? $sql : Loader_Mysql::DBMaster()->getAll( $sql, MYSQL_ASSOC );	
	}	

	public function getShopList($ctype,$version){
    	$clientVersion   = $version;  	
    	$return = $datas = array();
    	
    	$cacheKey = Config_Keys::shopList($ctype);
    	$serverVersion = (int)Loader_Redis::common()->get($cacheKey,false,false);
    	if( $clientVersion != 0 && $clientVersion == $serverVersion){
    		$return['version'] = $serverVersion;
    		$return['result']  = 0;
    		$return['list']    = array();
    		return $return;
    	}
    	$sql   = "SELECT * FROM $this->shopcurrency WHERE ctype='$ctype' AND status=1 ORDER BY `order` ASC" ;
    	$items = Loader_Mysql::DBMaster()->getAll($sql);

    	foreach ($items as $k => $item) {
    		$datas[$k]['gid']          = (int)$item['id'];
    		$datas[$k]['type']        = (int)$item['type'];
    		$datas[$k]['name']        = $item['name'] ? $item['name'] : "";
    		$datas[$k]['exchangenum'] = (int)$item['pexchangenum'];
    		$datas[$k]['label']       = (int)$item['label'];
    		$datas[$k]['originmoney'] = $item['originmoney'];
    		if(($item['ctype'] == 2 || $item['ctype']==3)){
    			if($item['identifier']){
    				$datas[$k]['identifier'] = $item['identifier'];
    			}
    		} 
    		$datas[$k]['img']         = $item['img'] ? $item['img'] : "";
    		$datas[$k]['desc']        = $item['desc'] ? (string)$item['desc'] : "";
    		$datas[$k]['price']       = $item['price'];
    		$datas[$k]['exptime']     = (int)$item['exptime'];
    	}
    	return array('result'=>1,'version'=>$serverVersion,'list'=>$datas);
    }
    
    public function getPayChannelList($id){
    	if(!$id = Helper::uint($id)){
    		return false;
    	}

    	$cacheData =  Loader_Redis::common()->get(Config_Keys::paychannel($id));   	
    	if($cacheData != false){
    		//return $cacheData;
    	}

    	$rtn = array();    	  	
    	$sql = "SELECT pmode,price,name,identifier FROM $this->shop_list WHERE id =$id LIMIT 1";
    	$list = Loader_Mysql::DBMaster()->getOne($sql);    	
    	$rtn['payinfo'] = $list;
    	
    	$aPmode = array();
    	if($list['pmode']){
    		$aPmode = explode(',', $list['pmode']);
    	}
    	
    	$sql = "SELECT * FROM $this->setting_pmode ORDER BY pmode ASC";
    	$rows = Loader_Mysql::DBMaster()->getAll($sql);
    	
    	foreach ($rows as $k=>$row) {
    		if(in_array($row['pmode'], $aPmode)){
    			$rtn['paychannel'][$k]['pmode'] = $row['pmode'];
    			$rtn['paychannel'][$k]['pname'] = $row['payname'];
    		}
    	}
  
    	Loader_Redis::common()->set(Config_Keys::paychannel($id), $rtn);    	
    	return $rtn;
    }
    
    public function getPayAmountBymid($mid){
    	$sql = "SELECT sum( `pamount` ) amount FROM `uc_payment` WHERE mid ='$mid'";
    	$row = Loader_Mysql::DBMaster()->getOne($sql);
    	return $row['amount'];
    }

}