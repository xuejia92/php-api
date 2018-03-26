<?php

class Activity_Yangniansongfu {
    
    private static $_instance;
    
    public static function factory(){
        
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_Yangniansongfu();
        }
        return self::$_instance;
    }
    
    //获取用户活动数据
    public function getPlayInfo($mid, $gameid, $ctype, $gameConfig, $time) {
        $paijuFrequency = $this->getPaijuInfo($mid, $gameid);               //获取用户牌局数
        $guaFrequency = (int)Loader_Redis::common()->hGet("yangniansongfu|$time|$gameid", $mid);
        
        $return['need'] = $gameConfig[$gameid][$guaFrequency+1];
        
        for ($i=1;$i<=$guaFrequency;$i++){
            $use += $gameConfig[$gameid][$i];
        };
        
        $return['surplus'] = $paijuFrequency - $use;
        
        return $return;
    }
    
    //记录用户抽奖相关数据
    public function setPlayInfo($mid, $gameid, $sid, $cid, $pid, $ctype, $gameConfig, $prize_arr, $time) {
        $paijuFrequency = $this->getPaijuInfo($mid, $gameid);
        $guaFrequency = (int)Loader_Redis::common()->hGet("yangniansongfu|$time|$gameid", $mid);
        $need = $gameConfig[$gameid][$guaFrequency+1];
        
        for ($i=1;$i<=$guaFrequency;$i++){              //已用的羊气值
            $use += $gameConfig[$gameid][$i];
        };
        
        $surplus = $paijuFrequency - $use;
        $return['lottery'] = $lottery = $this->lottery($prize_arr, $gameid, $time);              //开奖结果
        if ($surplus >= $need && $lottery){                 //判断羊气值是否足够抽奖
            if ($guaFrequency<5){
                $guaFrequency++;
                $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $lottery);//奖励金币
                
                if ($add){
                    Loader_Redis::common()->incr("yangniansongfuFafang|$time|$gameid", $lottery, 15*24*3600);                    //发放金币统计
                    Loader_Redis::common()->hSet("yangniansongfu|$time|$gameid", $mid, $guaFrequency, false, false, 15*24*3600); //记录刮奖次数
                    Loader_Redis::common()->hSet("yangniansongfuTotal|$time|$gameid", $mid, 1, false, false, 15*24*3600);        //记录奖品发放人数
                    Loader_Redis::common()->incr("yangniansongfuCount|$time|$gameid", 1,  15*24*3600);                           //记录参与人次
                    Loader_Redis::common()->incr("yangniansongfu$lottery|$time|$gameid", 1, 15*24*3600);                         //记录奖品发放次数
                    
                    $return['status'] = 1;          //增加金币成功
                }else {
                    $return['status'] = 0;          //增加金币失败
                }
            }else {
                $return['status'] = -1;             //抽奖次数达到上限
            }
        }else {
            $return['status'] = -2;                 //抽象需要条件不足
        }
        $record = $this->getPlayInfo($mid, $gameid, $ctype, $gameConfig, $time);
        $return['need']     = $record['need'];          //返回所需羊气值
        $return['surplus']  = $record['surplus'];       //返回剩余羊气值
        return $return;
    }
    
    //获取用户的牌局信息
    public function getPaijuInfo($mid,$gameid) {
        if($gameid==1){
            $paijuinfo = Loader_Redis::common()->hGet("sh_winstreak",$mid);
        }else if($gameid==3){
            $paijuinfo = Loader_Redis::rank(3)->hGet("lc_winstreak",$mid);
        }else if($gameid==4){
            $paijuinfo = Loader_Redis::rank(4)->hGet("bf_winstreak",$mid);
        }
        if ($paijuinfo){
            $paijuFrequency = strlen($paijuinfo);          //用户今日牌局数
        }else {
            $paijuFrequency = 0;
        }
        return $paijuFrequency;
    }
    
    //开奖
    public function lottery($prize_arr, $gameid, $time) {
        $bigger = Loader_Redis::common()->get("yangniansongfu8888|$time|$gameid", false, false);
        if ($bigger >= 100){//当8888话费派完
            $prize_arr[2]['v'] = 0;
            $prize_arr[3]['v'] = 100;
        }
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->getRand($arr);                //根据概率获取奖项id
        $res = $prize_arr[$rid-1];                  //中奖项
        
        $prize = $res['prize'];
        return $prize;
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
    
}