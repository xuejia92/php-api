<?php !defined('IN WEB') AND exit('Access Denied!');
class Bank{

	public function create($param){
		$mid     = Helper::uint($param['mid']);
		$bankPWD = trim($param['param']['bankPWD']);
		
		$return['result'] = 0;
		if(!$mid || !$bankPWD){
			return $return;
		}

		$isVip = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);
		if(!$isVip){
			return array('result'=>-1);
		}
		
		$hasBank = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bankPWD');
		if($hasBank ){//已经有了保险箱
			return $return;
		}
		
		$bankPWD = md5($bankPWD);		
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'bankPWD', $bankPWD);
		$return['result'] = 1;
		return $return;
	}
	
	public function saveMoney($param){
		$mid    = Helper::uint($param['mid']);
		$money  = $param['param']['money'];
		$gameid = Helper::uint($param['gameid']);
		
		$return['result'] = 0;
		if(!$mid || !$money){
			return $return;
		}

		$serverInfo = Loader_Redis::userServer($mid)->hMget(Config_Keys::userServer($mid), array('svid','tid','level','gameid'));

		$can_opt = false;
		if($serverInfo['svid'] !=0 || $serverInfo['tid'] !=0  ){
			
			if($serverInfo['gameid'] == 4 && $serverInfo['level'] == 8){//斗牛百人场
				$can_opt = true;
				$key     = Config_Keys::serverBullTable();
				$key_mid = "ServerBull81bet";
				$count   = 4;
			}
			
			if($serverInfo['gameid'] == 7 && $serverInfo['level'] == 6){//炸金花百人场
				$can_opt = true;
				$key     = Config_Keys::serverFlowerTable();
				$key_mid = "ServerFlower25bet";
				$count   = 4;
			}
			
			if($serverInfo['gameid'] == 6 && $serverInfo['level'] == 26){//德州百人
				$can_opt = true;
				$key     = Config_Keys::serverTexasTable();
				$key_mid = "ServerTexas130bet";
				$count   = 4;
			}
			
			if($serverInfo['gameid'] == 5 && $serverInfo['level'] == 3){//龙虎斗
				$can_opt = true;
				$key     = Config_Keys::serverDragonTable();
				$key_mid = "ServerDragon10bet";
				$count   = 3;
			}
			
			if( $can_opt ){//斗牛百人场 龙虎斗
				$serverInfo = Loader_Redis::server()->hMget($key, array('status','banker'));
				$status_mid = false;
				for($i=1;$i<=$count;$i++){
					if(Loader_Redis::server()->hGet($key_mid.$i, $mid)){
						$status_mid = true;
						break;
					}
				}
				
				if($status_mid){
					return array('result'=>-3);//下注状态，不能存
				}
				
				if($serverInfo['banker'] == $mid  ){
					return array('result'=>-4);//您是荘家不能保险箱操作
				}
				
				$is_ok = true;
			}
			
			if(!$is_ok){
				return $return;
			}
		}
		
		$isVip   = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);	
		$hasBank = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bankPWD');
		
		if(!$isVip && $hasBank ){
			return array('result'=>-1);
		}
		
		if(!$isVip){
			return array('result'=>-2);
		}
		
		$gameInfo    = Member::factory()->getGameInfo($mid);
		
		if($gameInfo['money'] < $money){
			return $return;
		}
				
		$gameInfo = Member::factory()->saveMoney2Bank($mid, $money,$gameid);
		
		if(!$gameInfo){
			return $return;
		}
		$return['result']      = 1;
		$return['freezemoney'] = (int)$gameInfo['freezemoney'];
		$return['money']       = (int)$gameInfo['money'];
		
		return $return;
	}
	
	public function getMoney($param){
		$mid     = Helper::uint($param['mid']);
		$money   = $param['param']['money'];
		$bankPWD = trim($param['param']['bankPWD']);
		$gameid  = Helper::uint($param['gameid']);
		
		$return['result'] = 0;
		if(!$mid || !$money){
			return $return;
		}
		
		$serverInfo = Loader_Redis::userServer($mid)->hMget(Config_Keys::userServer($mid), array('svid','tid','level','gameid'));
		$can_opt    = false;
		if($serverInfo['svid'] !=0 || $serverInfo['tid'] !=0  ){
			
			if($serverInfo['gameid'] == 4 && $serverInfo['level'] == 8){//斗牛百人场
				$can_opt = true;
				$key     = Config_Keys::serverBullTable();
			}
			
			if($serverInfo['gameid'] == 7 && $serverInfo['level'] == 6){//炸金花百人场
				$can_opt = true;
				$key     = Config_Keys::serverFlowerTable();
			}
			
			if($serverInfo['gameid'] == 5 && $serverInfo['level'] == 3){//龙虎斗
				$can_opt = true;
				$key     = Config_Keys::serverDragonTable();
			}
			
			if($serverInfo['gameid'] == 6 && $serverInfo['level'] == 26){//德州百人
				$can_opt = true;
				$key     = Config_Keys::serverTexasTable();
			}
			
			if( $can_opt ){
				$is_ok = false;
				$serverInfo = Loader_Redis::server()->hMget($key, array('status','banker'));
				if($serverInfo['banker'] == $mid  ){
					return array('result'=>-3);//您是荘家不能保险箱操作
				}
				$is_ok = true;
			}
			
			if(!$is_ok){
				return $return;
			}
		}

		$clientPWD = md5($bankPWD);			
		$serverPWD = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bankPWD');
		
		if($clientPWD != $serverPWD){
			return $gameid == 1 ? array('return'=>-1) : array('result'=>-1);
		}
		
		$gameInfo    = Member::factory()->getGameInfo($mid);
		if($gameInfo['freezemoney'] < $money){
			return $return;
		}
		
		$gameInfo = Member::factory()->getMoneyFromBank($mid, $money,$gameid);
		if(!$gameInfo){
			return $return;
		}
		$return['result']      = 1;
		$return['freezemoney'] = (int)$gameInfo['freezemoney'];
		$return['money']       = (int)$gameInfo['money'];
		
		return $return;
	}
	
	public function resetPassword($param){
		$mid     = Helper::uint($param['mid']);
		$oldPWD  = trim($param['param']['oldPWD']);
		$newPWD  = trim($param['param']['newPWD']);
		
		$return['result'] = 0;
		if(!$mid || !$oldPWD ||!$newPWD){
			return $return;
		}
		
		$isVip   = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);	
		$hasBank = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bankPWD');
		
		if(!$isVip && $hasBank ){
			return array('result'=>-1);
		}
		
		if(!$isVip){
			return array('result'=>-2);
		}
		
		$oldPWD = md5($oldPWD);
		$newPWD = md5($newPWD);
		
		$server_oldPWD = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bankPWD');
		if($oldPWD != $server_oldPWD){
			return array('result'=>-3);
		}

		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'bankPWD', $newPWD);
		$return['result'] = 1;
		return $return;
	}
	
	public function transfer($param){
		$mid   = Helper::uint($param['mid']);
		$sid   = Helper::uint($param['sid']);
		$cid   = Helper::uint($param['cid']);
		$ctype = Helper::uint($param['ctype']);
		$pid   = Helper::uint($param['pid']);
		$tomid = $param['param']['tomid'];
		$money = Helper::uint($param['param']['money']);
		$bankPWD = trim($param['param']['bankPWD']);
		$gameid  = Helper::uint($param['gameid']);
		$return['result'] = 0;
		
		if(!$mid || !$tomid ||!$money){
			return $return;
		}
		
		if($mid == $tomid){//不能自己给自己转账
			return $return;
		}
		
		if($money > 9223372036854775807){
			return $return;
		}

		$clientPWD = md5($bankPWD);			
		$serverPWD = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bankPWD');
		$tomidBank = Loader_Redis::ote($mid)->hGet(Config_Keys::other($tomid), 'bankPWD');

		if($clientPWD != $serverPWD){
			return array('result'=>-4);
		}
		
		if(!$tomidBank){
			return array('result'=>-5);
		}
		
		$gameInfo = Member::factory()->getGameInfo($mid);
		if($gameInfo['freezemoney'] < $money){
			return array('result'=>-6);
		}
		
		if( ($gameInfo['freezemoney'] - $money)  < Config_Game2::$bankConfig['low'] ){
			return array('result'=>-7);
		}

		$fee = Config_Game2::$bankConfig['fee'] * $money;
		if($gameInfo['money'] < $fee){
			return array('result'=>-8);
		}
		
		$serverInfo = Loader_Redis::userServer($mid)->hMget(Config_Keys::userServer($mid), array('svid','tid'));
		if($serverInfo['svid'] !=0 && $serverInfo['tid'] !=0  ){
			if( $gameid == 2 ){
				$serverInfo = Loader_Redis::baccarat()->hMget(Config_Keys::baccstatus(), array('status','banker'));
				if($serverInfo['status'] == 2){
					return array('result'=>-2);//下注状态，不能取
				}
				
				if($serverInfo['banker'] == $mid  ){
					return array('result'=>-3);//您是荘家不能保险箱操作
				}
			}
			return $return;
		}
		
		$record = Member::factory()->transfer($mid, $tomid, $money, $gameid, $sid, $cid, $pid, $ctype, $fee,$gameInfo['freezemoney']);
		
		if($record){
			$return['result']       = 1;
			$return['money']        = (int)$record['money'];
			$return['freezemoney']  = (int)$record['freezemoney'];
			return $return;
		}

		return $return;
	}
	
	public function getTransferLog($param){
		$mid   = Helper::uint($param['mid']);
		$return['result'] = 0;
		$return['log']    = array();
		
		$record = Member::factory()->getTransferLog($mid);
		
		if($record){
			$return['result'] = 1;
			foreach ($record as $k=>$row) {
				$record[$k]['ctime'] = date("Y-m-d H:i:s",$row['ctime']);
			}
			$return['log']    = $record;
			return $return;
		}
		
		return $return;
	}

}