<?php !defined('IN WEB') AND exit('Access Denied!');
class Wallet{

	public function ask($param){
		$mid    = Helper::uint($param['mid']);
		$giver  = Helper::uint($param['param']['giver']);
		$gameid = Helper::uint($param['gameid']);
		
		$rtn['result'] = $rtn['askers'] = $rtn['lefttimes'] = 0;
		
		if(!$mid || !$giver ||!$gameid){
			$rtn['result'] = -1;
			return $rtn;
		}
		
		if($mid == $giver){
			$rtn['result'] = -2;
			return $rtn;
		}
		
		$playInfo="";
		//获取用户的牌局信息
		if($gameid==1){
			$playInfo = Loader_Redis::common()->hGet("sh_winstreak",$mid);
		}else if($gameid==3){
			$playInfo = Loader_Redis::rank(3)->hGet("lc_winstreak",$mid);
		}else if($gameid==4){
			$playInfo = Loader_Redis::rank(4)->hGet("bf_winstreak",$mid);
		}
		
		$playCount           = strlen($playInfo);
		$can_askwallet_count = floor($playCount / 3);
		$used_count          = Loader_Redis::common()->get(Config_Keys::askWalletlimit($mid), false,false);

		if(($can_askwallet_count <= $used_count) || ($playCount == 0) ){
			$rtn['lefttimes']  = ($can_askwallet_count + 1) * 3 - $playCount;
			$rtn['result']     = -3;
			return $rtn; 
		}
		
		$result = Base::factory()->askWallet($mid, $giver, $gameid);
		
		if($result === false){
			return $rtn;
		}
		
		Loader_Redis::common()->incr(Config_Keys::askWalletlimit($mid), 1,Helper::time2morning());//用了多少次机会
				
		$rtn['result'] = 1;
		$rtn['askers'] = $result;
		$rtn['giver']  = $giver;
		
		return $rtn;
	}
	
	public function give($param){
		$mid    = Helper::uint($param['mid']);
		$sid    = Helper::uint($param['sid']);
		$cid    = Helper::uint($param['cid']);
		$pid    = Helper::uint($param['pid']);
		$ctype  = Helper::uint($param['ctype']);
		$gameid = Helper::uint($param['gameid']);
		$money  = Helper::uint($param['param']['money']);
		
		$rtn['result'] = $rtn['money'] = 0;
		if(!$mid || !$sid || !$cid || !$pid ||!$ctype || !$gameid || !$money){
			return $rtn;
		}
		
		if($money < Config_Game::$wallet['user_min_wallet']){
			$rtn['result'] = -1;
			return $rtn;
		}
		
		if($money > Config_Game::$wallet['user_max_wallet']){
			$rtn['result'] = -2;
			return $rtn;
		}
		
		$userInfo = Member::factory()->getOneById($mid);
		
		if($money > $userInfo['money']){
			$rtn['result'] = -3;
			return $rtn;
		}
		
		$info['giver']  = $mid;
		$info['money']  = $money;
		$info['gameid'] = $gameid;
		$info['sid']    = $sid;
		$info['cid']    = $cid;
		$info['pid']    = $pid;
		$info['ctype']  = $ctype;
		
		Loader_Redis::common()->lPush(Config_Keys::walletList(), $info);//存放到消息队列
		
		
		$msg1 = "发钱啦！土豪".$userInfo['mnick']."大手一挥".$money."金币装进红包，各路英雄准备10秒后开抢！（屏幕出现红包、立即点击）";
		$msg2 = "抢钱啦！大善人".$userInfo['mnick']."慷慨解囊".$money."金币装进红包，各路英雄准备10秒后开抢！（屏幕出现红包、立即点击）";
		$msg = $money > 100000 ? $msg2 : $msg1;
		$msg      = str_replace("\n", "", $msg);
		Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,11);//type  100 表示发到全部游戏
		
		$rtn['money']  = $userInfo['money'] - $money;
		$rtn['result'] = 1;
		
		return $rtn;
	}
	
	public function get($param){
		$_stime = microtime(true);
		$mid    = Helper::uint($param['mid']);
		$sid    = Helper::uint($param['sid']);
		$cid    = Helper::uint($param['cid']);
		$pid    = Helper::uint($param['pid']);
		$ctype  = Helper::uint($param['ctype']);
		$gameid = Helper::uint($param['gameid']);
		$wtype = Helper::uint($param['param']['wtype']);
		
		$rtn['result'] = $rtn['wallet'] = $rtn['money'] = 0;
		if(!$mid || !$sid || !$cid || !$pid ||!$ctype || !$gameid || !$wtype ){
			return $rtn;
		}
				
		$return = Base::factory()->getWallet($mid, $gameid, $sid, $cid, $pid, $ctype,$wtype);
		if($return == false){
			return $rtn;
		}
		$_etime = microtime(true);
		
		$time1 = $_etime - $_stime;
		
		$_stime = microtime(true);
		$rtn['wallet'] = $return;
		$gameInfo      = Member::factory()->getGameInfo($mid);
		$rtn['money']  = $gameInfo['money'];
		$rtn['result'] = 1;
		$_etime = microtime(true);
		$time2  = $_etime - $_stime;
		
		Logs::factory()->debug($time1.'|'.$time2,'getwallat');
		
		//$userInfo = Member::factory()->getUserInfo($mid);
		
		//$flag = Loader_Redis::common()->get(Config_Keys::tosend($mid),false,false);
		
		/*
		if($flag){
			$msg      = "恭喜玩家".$userInfo['mnick']."抢到了".$return."红包,玩牌越多,红包抢得越多!";
			$msg      = str_replace("\n", "", $msg);
			Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
		}
		*/
		
		
		return $rtn;
	}
}