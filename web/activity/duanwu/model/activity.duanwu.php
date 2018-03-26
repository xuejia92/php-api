<?php

class Activity_Duanwu {
    
    private static $_instance;
    
    public static function factory(){
        
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_Duanwu();
        }
        
        return self::$_instance;
    }
    


    //根据随机结果返回相关结果
    public function getPrize($prize_arr){
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['chance'];
        }
        $rid = $this->getRand($arr); //根据概率获取奖项id
        $res = $prize_arr[$rid]; //中奖项
    
        $result = array();
        if ($rid<=3){
            $min = $res['min'];
            $max = $res['max'];
            $ptype = 'money';
            $pamount = mt_rand($min,$max);
        }
        
        if ($rid==4){
            $ptype = 'ticket';
            $pamount = 1;
        }
    
        if ($rid==5){
            $ptype = 'ticket';
            $pamount = 3;
        }
        
        return array($ptype, $pamount);
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
    
    //获取用户的牌局信息
    public function getPaijuInfo($mid,$gameid) {
        if($gameid==1){
            $paijuinfo = Loader_Redis::common()->hGet("sh_winstreak",$mid);
        }else if($gameid==3){
            $paijuinfo = Loader_Redis::rank(3)->hGet("lc_winstreak",$mid);
        }else if($gameid==4){
            $paijuinfo = Loader_Redis::rank(4)->hGet("bf_winstreak",$mid);
        }else if($gameid==6){
            $paijuinfo = Loader_Redis::rank(6)->hGet("tx_winstreak",$mid);
        }
        if ($paijuinfo){
            $paijuFrequency = strlen($paijuinfo);          //用户今日牌局数
        }else {
            $paijuFrequency = 0;
        }
        
        return $paijuFrequency;
    }
    
    
    public function getPlayInfo($mid, $gameid, $time){
        $tili   = $this->getPaijuInfo($mid, $gameid);//获取体力数据
        $cost   = (int)Loader_Redis::common()->hGet("duanwucost|$gameid|$time", $mid);//获取体力使用数据
        $tili   = $tili-$cost;
        $ticket = (int)Loader_Redis::common()->hGet("duanwuticket|$gameid", $mid);
        $one    = (int)Loader_Redis::common()->hGet("duanwuzongzi1|$gameid", $mid);
        $two    = (int)Loader_Redis::common()->hGet("duanwuzongzi2|$gameid", $mid);
        $three  = (int)Loader_Redis::common()->hGet("duanwuzongzi3|$gameid", $mid);
        $four   = (int)Loader_Redis::common()->hGet("duanwuzongzi4|$gameid", $mid);
        $five   = (int)Loader_Redis::common()->hGet("duanwuzongzi5|$gameid", $mid);
        $SX     = (int)Loader_Redis::common()->hGet("duanwuSX|$gameid|$time", $mid);
        
        return array($tili,$ticket,$cost,$one,$two,$three,$four,$five,$SX);
    }
    
    public function setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $time, $prize_array, $tapType){
        $playInfo   = $this->getPlayInfo($mid, $gameid, $time);
        $tili       = $playInfo[0];
        $ticket     = $playInfo[1];
        $prize_arr  = $prize_array[$tapType];
        
        switch ($tapType){
            case 1:
                $need = 5;
                break;
            case 2:
                $need = 14;
                break;
            case 3:
                $need = 16;
                break;
            case 4:
                $need = 30;
                break;
            case 5:
                $need = 50;
                break;
        }
        
        $ticket1    = (int)Loader_Redis::common()->hGet("duanwuticket1|$gameid", $mid);
        $ticket3    = (int)Loader_Redis::common()->hGet("duanwuticket3|$gameid", $mid);
        
        if ($ticket>=16 || $ticket1>=1 || $ticket3>=1){//限制兑换券发放数量
            if ($tapType==1){
                $prize_arr[1]['chance'] += $prize_arr[4]['chance'] + $prize_arr[5]['chance'];
            }
            if ($tapType==2){
                $prize_arr[2]['chance'] += $prize_arr[4]['chance'] + $prize_arr[5]['chance'];
            }
            if ($tapType>=3){
                $prize_arr[3]['chance'] += $prize_arr[4]['chance'] + $prize_arr[5]['chance'];
            }
            $prize_arr[4]['chance'] = $prize_arr[5]['chance'] = 0;
            Logs::factory()->debug($prize_array,'duanwu');
        }
        
        $clicknumber = (int)Loader_Redis::common()->hGet("duanwuzongzi$tapType|$gameid", $mid);
        if ($clicknumber!=0){//是否领取过此粽子
            $return['status'] = -2;
            return $return;
        }
        
        if ($tili<$need){//体力是否满足抽奖
            $return['status'] = -1;
            return $return;
        }
        
        $result = $this->getPrize($prize_arr);//抽奖
        $prize_type = $result[0];
        $prize_amount = $result[1];
        
        if ($prize_type=='money'){
            $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $prize_amount, '端午活动');//发放金币
        }
        if ($prize_type=='ticket'){
            $add = Loader_Redis::common()->hIncrBy("duanwuticket|$gameid", $mid, $prize_amount);//发放兑换券
            Loader_Redis::common()->setTimeout("duanwuticket|$gameid", 10*24*3600);
        }
        
        if ($add && $result){
            if ($prize_type=='money'){
                Loader_Redis::common()->incr("duanwuFa$prize_type|$gameid|$ctype|$time", $prize_amount,90*24*3600);//发放金币统计
            }else {
                Loader_Redis::common()->hSet("duanwuFanum$prize_type"."$prize_amount|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);//发放兑换券统计
                Loader_Redis::common()->incr("duanwuFa$prize_type"."$prize_amount|$gameid|$ctype|$time", $prize_amount,90*24*3600);
                Loader_Redis::common()->hSet("duanwu$prize_type"."$prize_amount|$gameid", $mid, 1, false, false, Helper::time2morning());//每日兑换券发放限制
            }
            
            Loader_Redis::common()->hIncrBy("duanwucost|$gameid|$time", $mid, $need);//体力使用记录
            Loader_Redis::common()->setTimeout("duanwucost|$gameid|$time", Helper::time2morning());
            
            Loader_Redis::common()->hIncrBy("duanwuSX|$gameid|$time", $mid, 1);
            Loader_Redis::common()->setTimeout("duanwuSX|$gameid|$time", Helper::time2morning());
            
            Loader_Redis::common()->hSet("duanwuzongzi$tapType|$gameid", $mid, 1, false, false, Helper::time2morning());//粽子领取限制
            
            Loader_Redis::common()->hSet("duanwuTapNum$tapType|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);//粽子点击次数统计
            Loader_Redis::common()->incr("duanwuTapCi$tapType|$gameid|$ctype|$time", 1,90*24*3600);
            
            Loader_Redis::common()->hSet("duanwuCanyunum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);//参与人数统计
            Loader_Redis::common()->incr("duanwuCanyuci|$gameid|$ctype|$time", 1, 90*24*3600);
            
            $return = $this->getPlayInfo($mid, $gameid, $time);
            $return['type']     = $prize_type;
            $return['amount']   = $prize_amount;
            $return['status']   = 1;
        }else {
            $return['status'] = 0;
        }
        
        return $return;
    }
}