<?php

class Activity_Lebaocaicai {
    
    private static $_instance;
	
	public static function factory(){

		if(!is_object(self::$_instance)){
			self::$_instance = new Activity_Lebaocaicai();
		}

		return self::$_instance;
		
	}
	
	/**
	 * 获取上期的购买信息
	 */
	public function getLastInfo($mid,$times){
		
		$rtn = array();
		
		//获取用户金币数量
		$gameInfo        = Member::factory()->getGameInfo($mid);
		$rtn['money']    = $gameInfo['money'];
		
		//上一期购买数量
		$yesterday_bought   = Loader_Redis::common()->get('laobal_'.$times.'_'.$mid,false,true);
		$rtn['dog_yesterday']      = (int)$yesterday_bought['dog'];
		$rtn['bird_yesterday']     = (int)$yesterday_bought['bird'];
		$rtn['fox_yesterday']      = (int)$yesterday_bought['fox'];
		$rtn['giraffe_yesterday']  = (int)$yesterday_bought['giraffe'];
		$rtn['panda_yesterday']    = (int)$yesterday_bought['panda'];
		$rtn['sheep_yesterday']    = (int)$yesterday_bought['sheep'];

		
		//开奖结果
		$rtn['which'] = $lottery = Loader_Redis::common()->get('lebal_results_'.$times);
		$arr_my  = Loader_Redis::common()->get('laobal_'.$times.'_'.$mid,false,true);
		$bonus_number = (int)$arr_my[$lottery];
        //上期中奖的金额
        $rtn['bonus_money'] = $bonus_number*50000;
		
        //开奖历史
        $now = date('Hi',NOW);
        if ($now<2000){
            $tt = strtotime("-1 days",NOW);
        }else {
            $tt = NOW;
        }
        $n = 0;
        for ($i=-1;$i>-6;$i--){
            $key[$n] = strtotime("".$i." days",$tt);
            $rtn['day'][$n] = date("Y/m/d",strtotime("+1 days",$key[$n]));
            $rtn['his'][$n] = Loader_Redis::common()->get('lebal_results_'.date('ymd',$key[$n]));
            $n++;
        }
		return $rtn;
	}
	
	
	/**
	 * 获取本期的购买信息
	 */
	public function getCurrentInfo($mid,$times){
		
		$rtn = array();
		
		//今日购买数量
		$today_bought   = Loader_Redis::common()->get('laobal_'.$times.'_'.$mid,false,true);
		$rtn['dog_today']      = (int)$today_bought['dog'];
		$rtn['bird_today']     = (int)$today_bought['bird'];
		$rtn['fox_today']      = (int)$today_bought['fox'];
		$rtn['giraffe_today']  = (int)$today_bought['giraffe'];
		$rtn['panda_today']    = (int)$today_bought['panda'];
		$rtn['sheep_today']    = (int)$today_bought['sheep'];
		
		return $rtn;
	}
	
	/**
	 * 购买乐宝
	 */
	public function buy($gameid, $mid, $sid, $cid, $pid, $ctype, $money,$times,$buy_number,$which_type){
		//获取用户金币数量
		$gameInfo = Member::factory()->getGameInfo($mid);
		$money    = $gameInfo['money'];
		
		$reqMoney = $buy_number * 10000;
		
		if ($money < $reqMoney ){
            return -1;
        }
        
        $deduct = Logs::factory()->addWin($gameid, $mid, 34, $sid, $cid, $pid, $ctype, 1, $reqMoney, $desc='lebaocaicia');
        
        if ($deduct){
        	$info = $today_bought   = Loader_Redis::common()->get('laobal_'.$times.'_'.$mid,false,true);
        	$info[$which_type] = $totolnum = (int)$info[$which_type] + $buy_number;
        	
        	$totalbuy = Loader_Redis::common()->hGet("lebao-top15-$times", $mid);
        	$totalbuy = (int)$totalbuy + $buy_number;
        	
        	$statConfig = array('dog'=>'144','bird'=>'145','fox'=>'146','giraffe'=>147,'panda'=>'148','sheep'=>'149');
        	Loader_Udp::stat()->sendData($statConfig[$which_type], $mid, $gameid, $ctype, $cid, $sid, $pid, 0,$buy_number);//上报统计中心
        	
        	$key = date('Hi',NOW);
        	if ($key<2000){
        	    $now = date('Y-m-d',NOW);
        	}else {
        	    $now = date('Y-m-d',strtotime("+1 days"));
        	}
        	Loader_Redis::common()->hSet("lebao|$now|$gameid|$ctype|$which_type", $mid, $totolnum, false, false,15*24*3600);//统计数据
        	Loader_Redis::common()->hSet("lebao-top15-$times", $mid, $totalbuy, false, false,5*24*3600);
        	
        	
            $flag = Loader_Redis::common()->set('laobal_'.$times.'_'.$mid, $info, false, true, 5*24*3600);
            
        }
        
        return (int)$flag;
	}
	
	/** 
	 *  获取奖励
	 */
	public function getReward($gameid, $mid, $sid, $cid, $pid, $ctype,$times){
		$lottery = Loader_Redis::common()->get('lebal_results_'.$times);//开奖结果
        $arr_my  = Loader_Redis::common()->get('laobal_'.$times.'_'.$mid,false,true);
        $bonus_number = $arr_my[$lottery];
        if(!$bonus_number){
        	return 0;
        }
        
        $bonus_money = $bonus_number * 50000;

        $rewardFlag = Loader_Redis::common()->get('lb_reward_flag|'.$times.$mid,false,false);
        
        if($rewardFlag){//已经领取过
        	return -1;
        }
        
        $plus = Logs::factory()->addWin($gameid, $mid, 35, $sid, $cid, $pid, $ctype, 0, $bonus_money, $desc='lebaocaicia');//加钱
        Loader_Redis::common()->set('lb_reward_flag|'.$times.$mid,1,false,false,5*24*3600);
        return (int)$plus;
	}
}