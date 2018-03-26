<?php !defined('IN WEB') AND exit('Access Denied!');
class Exchange{

	public function money2vip($param){
		$mid     = Helper::uint($param['mid']);
		$cid     = Helper::uint($param['cid']);
		$ctype   = Helper::uint($param['ctype']);
		$pid     = Helper::uint($param['pid']);
		$sid     = Helper::uint($param['sid']);
		$gameid  = Helper::uint($param['gameid']) ? $param['gameid'] : 1;
		$vipType = $param['param']['viptype'] ? Helper::uint($param['param']['viptype']) : 1;
		
		$return['result'] = $return['viptime'] = $return['money'] = 0;
		if(!$gameid || !$mid || !$cid || !$ctype || !$pid){
			return $return;
		}

		if(!in_array($vipType, array('1','2','10','100'))){
			return $return;
		}
		
		$gameInfo   = Member::factory()->getGameInfo($mid,false);
		$money2vip  = Config_Money::$vipConfig[$vipType]['money'];
		$exptime = (int)Config_Money::$vipConfig[$vipType]['vipexptime'];
		
		if($gameInfo['money'] < $money2vip){
			return array('result'=>-1);
		}
		
		$serverStatus = Loader_Redis::userServer($mid)->hGet(Config_Keys::userServer($mid), 'svid');
		if($serverStatus !=0 ){
			return $return;
		}
		
		$flag = Logs::factory()->addWin($gameid ,$mid, 6, $sid, $cid, $pid, $ctype, 1, $money2vip);
		if(!$flag){
			return array('result'=>-1);
		}
		
		$vip = (int)Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);
		
		if($gameid == 3){
			$vipType = $vip;
		}
		
		if($vip == 1 && $vipType == 2){
			$vipType = 1;
		}

    	Member::factory()->setVip($mid, $gameid, $exptime);
    	
		$return['viptime']  = (int)$exptime;
		$return['money']    = $gameInfo['money']  - $money2vip;
		$return['viptype']  = $vipType;
		$return['result']   = 1;
		return $return;
	}
	
	
	public function getVipList($param){
		$mid     = Helper::uint($param['mid']);
		$cid     = Helper::uint($param['cid']);
		$ctype   = Helper::uint($param['ctype']);
		$pid     = Helper::uint($param['pid']);
		$sid     = Helper::uint($param['sid']);
		$gameid  = Helper::uint($param['gameid']) ? $param['gameid'] : 1;
		
		$return['result'] = 0;
		$return['info'] = '';
		
		$return['info'] = array(
								array('viptype'=>2,'needMoney'=>Config_Money::$vipConfig[2]['money'],'name'=>'周会员（7天）'),
								array('viptype'=>1,'needMoney'=>Config_Money::$vipConfig[1]['money'],'name'=>'月会员（30天）'),
								array('viptype'=>10,'needMoney'=>Config_Money::$vipConfig[10]['money'],'name'=>'季会员（90天）'),
								array('viptype'=>100,'needMoney'=>Config_Money::$vipConfig[100]['money'],'name'=>'年会员（365天）'),
							
							);
		$return['result'] = 1;
		
		return $return;			
	}
	
	
	public function getWmReward($param){
		$mid    = Helper::uint($param['mid']);
		$cid    = Helper::uint($param['cid']);
		$ctype  = Helper::uint($param['ctype']);
		$pid    = Helper::uint($param['pid']);
		$sid    = Helper::uint($param['sid']);
		$gameid = Helper::uint($param['gameid']);
		$filed  = Helper::uint($param['param']['filed']);
		$gid    = trim($param['param']['gid']);
		
		$return['result'] = $return['money'] = $return['rewardMoney'] = 0;
		
		$wmconfig      = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), $gid);
		$wmconfig      = json_decode($wmconfig,true);

		if($wmconfig){
			$dates = array_keys($wmconfig);
			$date  = $dates[$filed];
			$currentDayCfg = $wmconfig[$date];
		}
		
		if($date > date("Ymd")){//还没到时间领取
			$return['result'] = -1;
			return $return;
		}
		
		if(!$wmconfig || !$currentDayCfg){//参数有误 gid date
			$return['result'] = -2;
			return $return;
		}
		
		$arrTmp = explode(':', $currentDayCfg);
		$rewardMoney = Helper::uint($arrTmp[0]);
		$rewardFlag  = Helper::uint($arrTmp[1]);
		
		if($rewardFlag == 1){//之前已经领取
			$return['result'] = -3;
			return $return;
		}
		
		$flag = Logs::factory()->addWin($gameid,$mid, 16, $sid, $cid, $pid, $ctype, 0, $rewardMoney,$rewardDate);
		
		if($flag){
			$wmconfig[$date] = $rewardMoney.':1';
			
			$check_has_fetched = 1;//查看是否已经全部领取完，如果领取完，则删除该条记录，便重复购买，否则，更改本次领取状态
			foreach ($wmconfig as $cfg) {
				$arrTmp = explode(':', $cfg);
				if($arrTmp[1] == 0 ){
					$check_has_fetched = 0;
				}
			}
			
			if($check_has_fetched == 1){
				Loader_Redis::ote($mid)->hDel(Config_Keys::other($mid), $gid);
			}else{
				$wmconfig = json_encode($wmconfig);
				Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), $gid, $wmconfig);
			}
			
			$return['result']      = 1;
			$gameInfo              = Member::factory()->getGameInfo($mid);
			$return['money']       = $gameInfo['money'];
			$return['rewardMoney'] = $rewardMoney;
			$return['gid']         = $gid;
			$return['filed']       = $filed;
		}
		
		return $return;
		
	}
}