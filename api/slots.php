<?php !defined('IN WEB') AND exit('Access Denied!');
class Slots{

	public function bet($param){
		
		$mid       = Helper::uint($param['mid']);
		$sid       = Helper::uint($param['sid']);
		$cid       = Helper::uint($param['cid']);
		$pid       = Helper::uint($param['pid']);
		$ctype     = Helper::uint($param['ctype']);
		$gameid    = Helper::uint($param['gameid']);
		$betMoney  = Helper::uint($param['param']['betMoney']);
		
		$rtn['result'] = $rtn['cardType']  = $rtn['status'] = $rtn['money'] =0;
		$rtn['cardInfo'] = array();
		if(!$mid || !$sid || !$cid || !$pid ||!$ctype || !$gameid || !$betMoney){
			return $rtn;
		}
		
		$gameInfo = Member::factory()->getGameInfo($mid);
		
		if($gameInfo['money'] < 10000 || $gameInfo['money'] < $betMoney )
		{
			$rtn['result'] = -1;
			return $rtn;
		}
		
		if(($betMoney < 10000) || ($betMoney % 10 !=0)   )
		{
			$rtn['result'] = -2;
			return $rtn;
		}
		
		$info = M_Slots::factory()->bet($mid, $betMoney, $gameid, $sid, $cid, $pid, $ctype);

		if(!$info)
		{
			$status        = Loader_Redis::common()->hGet(Config_Keys::ffl($mid), 'status');
			$rtn['status'] = $status;
			return $rtn;
		}
		
		$info['cardInfo'] = explode(',',$info['cardInfo']);
		
		$i = 1;
		foreach ($info['cardInfo'] as $v) 
		{
			$rtn['cardInfo']['c'.$i] = $v;
			$i ++ ;
		}
		
		$gameInfo = Loader_Tcp::callServer($mid)->get($mid);//最新金币数
		
		$rtn['rewardMoney'] = $info['rewardMoney'];
		$rtn['cardType']    = $info['cardType'];
		$rtn['status']      = $info['status'];
		$rtn['cardType']    = $info['cardType'];
		$rtn['money']       = $gameInfo['money'];
		$rtn['result']      = 1;

		return $rtn;
	}
	
	public function changeCard($param)
	{
		$mid       = Helper::uint($param['mid']);
		$sid       = Helper::uint($param['sid']);
		$cid       = Helper::uint($param['cid']);
		$pid       = Helper::uint($param['pid']);
		$ctype     = Helper::uint($param['ctype']);
		$gameid    = Helper::uint($param['gameid']);
		$unsetCard = Helper::uint($param['param']['unsetCard']);
		
		$rtn['result']   = $rtn['cardType'] = $rtn['status'] = $rtn['money'] = $rtn['newCard'] = 0;

		if(!$mid || !$sid || !$cid || !$pid ||!$ctype || !$gameid || !$unsetCard)
		{
			return $rtn;
		}
		
		$info = Loader_Redis::common()->hMget(Config_Keys::ffl($mid),array('status','betMoney'));
		$status = $info['status'] + 1 ;

		$deductMoney = Config_Slots::$changeCardCosts[$status] * $info['betMoney'];

		$gameInfo = Member::factory()->getGameInfo($mid);
		
		if($gameInfo['money'] < $deductMoney)
		{
			$rtn['result'] = -1;//钱不够扣
			return $rtn;
		}
				
		$info = M_Slots::factory()->changeCard($mid, $deductMoney,$unsetCard, $gameid, $sid, $cid, $pid, $ctype);
		
		if(!$info)
		{
			$status        = Loader_Redis::common()->hGet(Config_Keys::ffl($mid), 'status');
			$rtn['status'] = $status;
			return $rtn;
		}
		
		$gameInfo = Loader_Tcp::callServer($mid)->get($mid);//最新金币数
		
		
		$rtn['rewardMoney'] = $info['rewardMoney'];
		$rtn['newCard']     = $info['newCard'];
		$rtn['status']      = $info['status'];
		$rtn['cardType']    = $info['cardType'];
		$rtn['money']       = $gameInfo['money'];
		$rtn['result']      = 1;
		
		return $rtn;
	}
	
	public function settlement($param)
	{
		$mid       = Helper::uint($param['mid']);
		$sid       = Helper::uint($param['sid']);
		$cid       = Helper::uint($param['cid']);
		$pid       = Helper::uint($param['pid']);
		$ctype     = Helper::uint($param['ctype']);
		$gameid    = Helper::uint($param['gameid']);
		
		$rtn['result']   = $rtn['cardType'] = $rtn['rewardMoney'] = $rtn['betSum'] = $rtn['status'] = $rtn['money'] =0;

		if(!$mid || !$sid || !$cid || !$pid ||!$ctype || !$gameid )
		{
			return $rtn;
		}
				
		$info = M_Slots::factory()->settlement($mid, $gameid, $sid, $cid, $pid, $ctype);
		
		if(!$info)
		{
			$status        = Loader_Redis::common()->hGet(Config_Keys::ffl($mid), 'status');
			$rtn['status'] = $status;
			return $rtn;
		}
		
		$gameInfo = Member::factory()->getOneById($mid);
		$mnick    = $gameInfo['mnick'];
		
		/*
		if($info['cardType'] >= 10)
		{
			$cardTyle =  $info['cardType'] = Config_Slots::$cardType2Name[$info['cardType']];
			$msg = "系统消息:恭喜".$mnick."在娱乐场翻翻乐抽得".$cardTyle."牌型,赢金币".$info['rewardMoney']."!";
			Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);
		}
		*/
		$rtn['rewardMoney'] = $info['rewardMoney'];
		$rtn['status']      = $info['status'];
		$rtn['cardType']    = $info['cardType'];
		$rtn['betSum']      = $info['betSum'];
		$rtn['money']       = $gameInfo['money'];
		$rtn['result']      = 1;
		
		return $rtn;
	}
	
	public function getConfig($param)
	{
		$mid       = Helper::uint($param['mid']);
		$sid       = Helper::uint($param['sid']);
		$cid       = Helper::uint($param['cid']);
		$pid       = Helper::uint($param['pid']);
		$ctype     = Helper::uint($param['ctype']);
		$gameid    = Helper::uint($param['gameid']);
		
		$rtn['result'] = 0;
		$rtn['odds']   = $rtn['costs'] = array();
		if(!$mid || !$sid || !$cid || !$pid ||!$ctype || !$gameid ){
			return $rtn;
		}
		
		if(Logs::factory()->limitCount($mid, 'slots', 1,true,Helper::time2morning()) < 2){
			Loader_Udp::stat()->sendData(133, $mid, $gameid, $ctype, $cid, $sid, $pid, 0);//进入人数
		}
		
		$serverInfo = Loader_Redis::userServer($mid)->hMget(Config_Keys::userServer($mid), array('svid','tid','level'));
		if($serverInfo['svid'] !=0 && $serverInfo['tid'] !=0  ){//在其他场
			return $rtn;
		}
		
		$cardName = Config_Slots::$cardType2Name;
		$rtn['odds'] = "赔率:";
		foreach (Config_Slots::$odds as $cardType => $odd) 
		{
			if(in_array($cardType, array(8,6,5,3,2,1)))
			{
				continue;
			}
			
			if($cardType <= 9 & $cardType >= 8)
			{
				$rtn['odds'] .= "(牛九-牛八)x".$odd.',';
			}
			elseif($cardType <= 7 & $cardType >= 5)
			{
				$rtn['odds'] .= "(牛七-牛五)x".$odd.',';
			}
			elseif($cardType <= 4 & $cardType >= 1)
			{
				$rtn['odds'] .= "(牛四-牛丁)x".$odd.',';
			}
			else 
			{
				$rtn['odds'] .= $cardName[$cardType]."x".$odd.',';
			}
		}
				
		$rtn['odds'] = rtrim($rtn['odds'],',');
		
		$rtn['enterMinMoney'] = Config_Slots::$enterMinMoney;
		$rtn['switch']        = Config_Slots::$switch;
		
		$switch_bit = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$pid);
		
		$flag = $switch_bit >> 2 & 1;
		if( $flag ){
			$rtn['switch'] = 0;
		}
		
		$serverInfo = Loader_Redis::userServer($mid)->hMget(Config_Keys::userServer($mid), array('svid','tid'));
		if($serverInfo['svid'] !=0 && $serverInfo['tid'] !=0  ){
			$rtn['result'] = -1;
			return $rtn;
		}
		
		$costs = Config_Slots::$changeCardCosts;
		$rtn['costs'] = "换牌消耗：第一次".$costs[1]* 100 ."%，第二次".$costs[2]* 100 ."%，第三次".$costs[3]* 100 ."%";

		$rtn['result'] = 1;
		return $rtn;
	}

}