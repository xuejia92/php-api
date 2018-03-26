<?php

class Activity_Xingyunlaba{
    
    private static $_instance;
    
    public static function factory(){
        
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_Xingyunlaba();
        }
        return self::$_instance;
    }
    
    public function setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $money, $time, $prize_arr){
        
        $gameInfo   = Member::factory()->getGameInfo($mid);
        $userMoney  = $gameInfo['money']; 
        
        $lottery    = $this->getArry($prize_arr);
        $famoney    = $money*$lottery['prize'];
        
        $res['bouns']   = 0;
        if ($userMoney>=$money){
            $add = Logs::factory()->addWin($gameid, $mid, 19, $sid, $cid, $pid, $ctype, 1, $money, '幸运拉霸');
            if ($add) {
                
                Loader_Redis::common()->incr("xingyunlabaIncome|$gameid|$ctype|$time", $money, 90*24*3600);
                Loader_Redis::common()->incr("xingyunlabaFafang|$gameid|$ctype|$time", $famoney,90*24*3600);
                
                Loader_Redis::common()->hSet("xingyunlabaCanyunum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
                Loader_Redis::common()->incr("xingyunlabaCanyuci|$gameid|$ctype|$time", 1, 90*24*3600);
                
                $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $famoney, '幸运拉霸');
                
                $res['status']  = 1;
                $res['bouns']   = $famoney;
            }else {
                $res['status'] = 0;
            }
        }else {
            $res['status'] = -1;
        }
        
        
        $res['famoney'] = $famoney;
        $res['number']  = $lottery['number'];
        
        return $res;
    }
    
    //根据随机结果返回相关结果
    public function getArry($prize_arr){
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->getRand($arr); //根据概率获取奖项id
        $res = $prize_arr[$rid]; //中奖项
    
        $result = array();
        switch ($res['prize']){
            case 0:
                for ($i=0;$i<4;$i++){
                    for ($j=0;$j<100;$j++){
                        $value = mt_rand(1,4);
                        if (!in_array($value, $result)){
                            $result[] = $value;
                            break;
                        }
                    }
                }
                break;
                
            case 2:
                $value = mt_rand(1,2);
                $result[] = $value;
                $result[] = $value;
                for ($i=0;$i<2;$i++){
                    for ($j=0;$j<100;$j++){
                        $value = mt_rand(1,4);
                        if (!in_array($value, $result)){
                            $result[] = $value;
                            break;
                        }
                    }
                }
                shuffle($result);
                break;
                
            case 4:
                $value = mt_rand(1,4);
                $result[] = $value;
                $result[] = $value;
                $result[] = $value;
                for ($j=0;$j<100;$j++){
                    $value = mt_rand(1,4);
                    if (!in_array($value, $result)){
                        $result[] = $value;
                        break;
                    }
                }
                shuffle($result);
                break;
                
            case 20:
                $value = mt_rand(1,4);
                $result[] = $value;
                $result[] = $value;
                $result[] = $value;
                $result[] = $value;
                break;
        }
        
        $res['number'] = (int)implode('', $result);
        return $res;
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