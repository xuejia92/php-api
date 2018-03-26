<?php
class  Activity_Xingyunchongzhi{
    
    private static $_instance;
	
	public static function factory(){
	    
		if(!is_object(self::$_instance)){
			self::$_instance = new Activity_Xingyunchongzhi();
		}
		return self::$_instance;
		
	}
    
	//根据随机结果返回相关角度、中奖倍数、状态
    public function getArry($gameid, $mid, $sid, $cid, $pid, $ctype, $time, $prize_arr){
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->getRand($arr); //根据概率获取奖项id
        $res = $prize_arr[$rid-1]; //中奖项
        $min = $res['min'];
        $max = $res['max'];
        if($res['id']==4){ //1.3倍
            $i = mt_rand(0,1);
            $result['angle'] = mt_rand($min[$i],$max[$i]);
        }else{
            $result['angle'] = mt_rand($min,$max); //随机生成一个角度
        }
        
        $record = $this->lottery($gameid, $mid, $sid, $cid, $pid, $ctype, $time, $res['prize']);
        
        $result['prize']    = $res['prize'];
        $result['moneyNum'] = $record['moneyNum'];
        $result['status']   = $record['status'];
        
        if ($record){
            return $result;
        }
    }
    
    //根据概率随机抽取
    public function getRand($proArr) {
        $result = '';
    
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
    
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
    
        return $result;
    }
    
    //记录相关抽奖数据并返回相关状态
    public function lottery($gameid, $mid, $sid, $cid, $pid, $ctype, $time, $prize){
        
        $gameInfo = Member::factory()->getGameInfo($mid);//获取用户金币数量
        $money    = $gameInfo['money'];
        
        $moneyNum = Loader_Redis::common()->hGet("xingyunchongzhirecord|$time", $mid);//获取已抽奖次数
        if (!$moneyNum){
            $moneyNum = 1;
        }else {
            $moneyNum++;
        }
        $cost = $moneyNum*200;
        
        if ($money>=$cost){
            if ($moneyNum <= 5){
                $kou    = Logs::factory()->addWin($gameid, $mid, 19, $sid, $cid, $pid, $ctype, 1, $cost, 'xingyunchongzhi');//抽奖扣费
                if ($kou){
                    Loader_Redis::common()->hSet("xingyunchongzhirecord|$time", $mid, $moneyNum, false, false, 15*24*3600);//记录抽奖次数
                    Loader_Redis::common()->hSet("xingyunchongzhi|$time|$gameid|$ctype", $mid, $moneyNum, false, false, 15*24*3600);
                    Loader_Redis::common()->hSet("chongzhibeishu|$time", $mid, $prize, false, false, 15*24*3600);//记录抽奖倍率
                    Loader_Redis::common()->incr("xingyunincome|$time|$gameid|$ctype", $cost, 15*24*3600);//每天活动收入统计
                    Loader_Redis::common()->incr("xingyuntimes|$time|$gameid|$ctype", 1, 15*24*3600);//每天总参与次数统计
                    Loader_Redis::common()->incr("xingyun$prize|$time|$gameid|$ctype", 1, 15*24*3600);//每天中奖倍数统计
                    
                    $status = 1;
                }else {
                    $status = 0;
                }
            }else {
                $status = -1;
            }
        }else {
            $status = -2;
        }
        $result['status']   = $status;
        $result['moneyNum'] = $moneyNum;
        
        return $result;
    }
}