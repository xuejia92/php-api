<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Match {
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Match();
		}
		
		return self::$_instances;
	}

	public function setMatchConfig(){
		$matchConfig['id']   = Helper::uint($data['id']);//场次ID
		$matchConfig['type'] = Helper::uint($data['type']);//1 是人满即开赛 2 为定时赛
		$matchConfig['money1']= Helper::uint($data['money1']);//打立出局需要的金币数额才可以玩
		$matchConfig['money2']= Helper::uint($data['money2']);//瑞士移位需要的金币数额才可以玩
		$matchConfig['max']= Helper::uint($data['max']);//人满即开赛的最大人数
		$matchConfig['promot'] = Helper::uint($data['promot']);//晋级人数
		$matchConfig['score'] =  Helper::uint($data['score']);//初始积分
		$matchConfig['endruishi'] = Helper::uint($data['endruishi']);//总共几轮决赛
		$matchConfig['ruishi1'] = Helper::uint($data['ruishi1']);//第一轮决赛晋级人数
		$matchConfig['ruishi2'] = Helper::uint($data['ruishi2']);//第二轮决赛晋级人数
		$matchConfig['ruishi3'] = Helper::uint($data['ruishi3']);//第三轮决赛晋级人数
		$matchConfig['ruishi4'] = Helper::uint($data['ruishi4']);//第三轮决赛晋级人数
		$matchConfig['gamebase'] = Helper::uint($data['gamebase']);//游戏基数用于积分结算
		$matchConfig['joybase'] = Helper::uint($data['joybase']);//欢乐基数用于金币结算
		$matchConfig['gamebaseruishi'] = Helper::uint($data['gamebaseruishi']);//决赛游戏基数
		$matchConfig['joybaseruishi'] = Helper::uint($data['joybaseruishi']);//决赛欢乐基数
		$matchConfig['basedali']  = Helper::uint($data['basedali']);//打立出局的低注
		$matchConfig['baseruishi'] = Helper::uint($data['baseruishi']);//瑞士移位的低注
		$matchConfig['taxdali'] = Helper::uint($data['taxdali']);//打立出局的台费
		$matchConfig['taxruishi'] = Helper::uint($data['taxruishi']);//瑞士移位的台费
		$matchConfig['diedround'] = Helper::uint($data['diedround']);//玩的局数最多的人最多玩多少局
		$matchConfig['limittime'] = Helper::uint($data['limittime']);//从玩家报名到开始游戏的时间限制
		$matchConfig['ratiodali'] =  Helper::uint($data['ratiodali']);//从打立出局到瑞士移位积分换算比率
		$matchConfig['ratioruishi'] = Helper::uint($data['ratioruishi']);//从打立出局到瑞士移位积分换算比率
		$matchConfig['needtype'] = Helper::uint($data['needtype']);//报名需要报名费 1 为金币 2 为乐劵
		$matchConfig['neednum'] = Helper::uint($data['neednum']);//需要报名费的多少
		$matchConfig['starttimetype'] = Helper::uint($data['starttimetype']);//开始时间的控制 1 为全天 2 为特定时间开启
		$matchConfig['specialstarttime'] = Helper::uint($data['specialstarttime']);//定时赛开赛时间
		$matchConfig['typelimit'] = Helper::uint($data['typelimit']);//玩比赛的限制 1为金币 2为乐劵
		$matchConfig['minlimit'] = Helper::uint($data['minlimit']);//进入最小限制的金币数量 
		$matchConfig['maxlimit'] = Helper::uint($data['maxlimit']);//进入最大限制的金币数量 
		$matchConfig['swtich'] = Helper::uint($data['swtich']);//开关
		$matchConfig['endnum'] = Helper::uint($data['endnum']);//截止到多少人开始确定晋级名单
		$matchConfig['timeslot'] = Helper::uint($data['timeslot']);//时间段  每天开始游戏的时间段  最多为3段
		$matchConfig['starttime1'] = Helper::uint($data['starttime1']);//时间段  每天开始游戏的时间段  最多为3段
		$matchConfig['endtime1'] = Helper::uint($data['endtime1']);//时间段  每天结束游戏的时间段  最多为3段
		$matchConfig['starttime2'] =  Helper::uint($data['starttime2']);//时间段  每天结束游戏的时间段  最多为3段
		$matchConfig['endtime2'] = Helper::uint($data['endtime2']);//时间段  每天结束游戏的时间段  最多为3段
		$matchConfig['starttime3'] = Helper::uint($data['starttime3']);//时间段  每天结束游戏的时间段  最多为3段
		$matchConfig['endtime3'] = Helper::uint($data['endtime3']);//时间段  每天结束游戏的时间段  最多为3段
		$matchConfig['detail'] = $data['detail'];//比赛详细描述

		if(!$matchConfig['id']){
			return false;
		}
		
		Loader_Redis::server()->hMset("LandLord_CompetConfig:".$matchConfig['id'], $matchConfig);
		
		return true;
	}
	
	public function setRewardConfig(){
		$rewardConfig['awardcont']  = Helper::uint($data['awardcont']);//奖励名次
		$rewardConfig['type']       = Helper::uint($data['type']);//1 抢金币   2 抢实物   3抢乐券
		$rewardConfig['reward']     = $data['reward'];//奖励描述
		
		$rewardConfig['rank1count'] = Helper::uint($data['rank1count']);//第一名奖励的总类型数量
		$rewardConfig['rank1type1'] = Helper::uint($data['rank1type1']);//第一名的第一项奖励类型
		$rewardConfig['rank1num1']  = Helper::uint($data['rank1num1']);//第一名第一种奖励的数量
		$rewardConfig['rank1type2'] = Helper::uint($data['rank1type2']);//第二名第二种奖励类型
		$rewardConfig['rank1num2']  = Helper::uint($data['rank1num2']);//第一名第二种类型奖励的数量
		
		$rewardConfig['rank2count'] = Helper::uint($data['rank2count']);//第二名奖励的总类型数量
		$rewardConfig['rank2type1'] = Helper::uint($data['rank2type1']);//第二名第一种奖励的类型
		$rewardConfig['rank2num1']  = Helper::uint($data['rank2num1']);//第二名第一种奖励数量
		$rewardConfig['rank2type2'] = Helper::uint($data['rank2type2']);//第二名的第二项奖励类型
		$rewardConfig['rank2num2']  = $data['rank2num2'];//第二名第二项奖励的数量
		
		$rewardConfig['rank3count'] = Helper::uint($data['rank3count']);//第三名奖励的类型总数
		$rewardConfig['rank3type1'] = Helper::uint($data['rank3type1']);//第三名的第一项奖励
		$rewardConfig['rank3num1']  = Helper::uint($data['rank3num1']);//第三名第一种奖励的数量
		$rewardConfig['rank3type2'] = Helper::uint($data['rank3type2']);//第三名第二项奖励类型
		$rewardConfig['rank3num2']  = Helper::uint($data['rank3num2']);//第三名第二项奖励数量
		
		$rewardConfig['id']         = Helper::uint($data['id']);
		
		if(!$rewardConfig['id']){
			return false;
		}
		
		Loader_Redis::server()->hMset("LandLord_AwardConfig:".$matchConfig['id'], $rewardConfig);
		
		return true;
	}
	
	public function get($data){
		$rewardType = $data['rewardtype'];//奖励类型
		$matchType  = $data['matchType'];//比赛类型

		$aResult = array();
		for($i=5;$i<=53;$i++){
			$key = Config_Keys::roomConfig($i);
			$aInfo = Loader_Redis::server()->hGetAll($key);
			$aResult[$i] = $aInfo;
		}
	
		return $aResult;		
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);	
		$key = Config_Keys::roomConfig($id);
		return Loader_Redis::server()->hGetAll($key);
	}
}