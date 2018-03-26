<?php
class Activity_Yunitongle {
    private static $_instance;
    
    public static function factory () {
        if (!is_object(self::$_instance)) {
            self::$_instance = new Activity_Yunitongle();
        }
        return self::$_instance;
    }
    
    public function getPlayInfo ($gameid, $mid, $ctype, $time) {
        
        $czNum              = (int)Loader_Redis::common()->get("chongzhi_".$mid."_".$time, false, false);           //获取用户本日充值金额
        $result['use']      = $usNum = (int)Loader_Redis::common()->hGet("yunnitongleUseNum|$time", $mid);               //获取使用体力值
        $result['tili']     = $czNum - $usNum;                                                                      //用户剩余体力
        $result['lingquNum'] = (int)Loader_Redis::common()->hGet("yunitongleLingquNum|$gameid|$ctype|$time", $mid);  //用户领取次数
        
        return $result;
    }
    
    public function setPlayInfo ($gameid, $mid, $sid, $cid, $pid, $ctype, $money, $time, $prize_arr, $tapType) {
        
        $record     = $this->getPlayInfo($gameid, $mid, $ctype, $time);
        $use        = $record['use'];
        $tili       = $record['tili'];
        $lingquNum  = $record['lingquNum'];
        
        switch ($tapType) {
            case 1:
                $need = 100;
                break;
            case 2:
                $need = 80;
                break;
            case 3:
                $need = 30;
                break;
            case 4:
                $need = 6;
                break;
        }
        
        $lottery                = $this->getArry($prize_arr, $tapType);
        $result['prize']        = $prize    = $lottery['prize'];
        $result['famoney']      = $famoney  = $lottery['bonus'];
        $result['tili']         = $tili;
        $result['lingquNum']    = $lingquNum;
        
        if ($lingquNum<2){
            if ($tili>=$need){
                $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $famoney, '愚你同乐');
                if ($add) {
                    Loader_Redis::common()->incr("yunitongleFafang|$gameid|$ctype|$time", $famoney, 90*24*3600);    //发放金额统计
                    
                    Loader_Redis::common()->hSet("yunnitongleUseNum|$time", $mid, $use+$need, false, false, 90*24*3600);    //使用体力值统计
                    
                    Loader_Redis::common()->hSet("yunitongleLingquNum|$gameid|$ctype|$time", $mid, $lingquNum+1, false, false, 90*24*3600); //用户已领取次数统计
                    Loader_Redis::common()->incr("yunitongleLingquCi|$gameid|$ctype|$time", 1, 90*24*3600); //用户参与次数统计
                    
                    Loader_Redis::common()->hSet("yunitongle$tapType|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);   //不同彩蛋被砸人数统计
                    Loader_Redis::common()->incr("yunitongleCi$tapType|$gameid|$ctype|$time", 1, 90*24*3600); //不同彩蛋被砸次数统计
                    
                    $result['tili']         = $tili-$need;
                    $result['lingquNum']    = $lingquNum+1;
                    $result['status']       = 1;
                }
            }else {
                $result['status'] = 0;
            }
        }else {
            $result['status'] = -1;
        }
        
        return $result;
    }
    
    //根据随机结果返回相关结果
    public function getArry($prize_arr, $tapType){
        foreach ($prize_arr[$tapType] as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->getRand($arr); //根据概率获取奖项id
        $res = $prize_arr[$tapType][$rid]; //中奖项
    
        $bonus = 0;
        
        if ($res['prize']=='none'){
            switch ($tapType){
                case 1:
                    $bonus = mt_rand(10000, 12000);
                    break;
                case 2:
                    $bonus = mt_rand(8000, 10000);
                    break;
                case 3:
                    $bonus = mt_rand(2000, 3000);
                    break;
                case 4:
                    $bonus = mt_rand(1000, 2000);
                    break;
            }
        }else if ($res['prize']=='get') {
            if ($tapType == 4) {
                $bonus = 10000;
            }
        }
        $result['prize'] = $res['prize'];
        $result['bonus'] = $bonus;
        
        return $result;
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