<?php
class Activity_Jiaochatuiguang {
    
    private static $_instance;
    
    public static function factory(){
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_Jiaochatuiguang();
        }
        return self::$_instance;
    }
    
    //获取用户游戏信息
    public function getFlag($gameid, $mid, $times){
        $gameInfo        = Member::factory()->getGameInfo($mid);    //用户金币数
        $rtn['money']    = $gameInfo['money'];
        
        $rtn['flag1'] = (int)Loader_Redis::common()->hGet("promotion|1|$times", $mid);
        $rtn['flag3'] = (int)Loader_Redis::common()->hGet("promotion|3|$times", $mid);
        $rtn['flag4'] = (int)Loader_Redis::common()->hGet("promotion|4|$times", $mid);
        
        //获取用户的牌局信息
        $paijuinfo1 = Loader_Redis::common()->hGet("sh_winstreak", $mid);
        $paijuinfo3 = Loader_Redis::rank(3)->hGet("lc_winstreak", $mid);
        $paijuinfo4 = Loader_Redis::rank(4)->hGet("bf_winstreak", $mid);
        
        $rtn['paijuFrequency1'] = strlen($paijuinfo1);      //用户今日牌局数
        $rtn['paijuFrequency3'] = strlen($paijuinfo3);
        $rtn['paijuFrequency4'] = strlen($paijuinfo4);
        
        return $rtn;
    }
    
    //领取奖励
    public function setFlag($gameid, $mid, $sid, $cid, $pid, $ctype, $times, $which){
        $re = $this->getFlag($gameid, $mid, $times);
        if ($re["flag$which"] == 0){
            if ($re["paijuFrequency$which"] >= 10){
                $value = 1;
                $se = Loader_Redis::common()->hSet("promotion|$which|$times", $mid, $value, false, false, 10*24*3600);
                if ($se){
                    $ff = (int)Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, 1288, $desc='promotion');     //金币奖励
                
                    Loader_Redis::common()->hSet("promotion-data|$times", $mid, 1, false, false,10*24*3600);                          //统计数据
                    $de = Loader_Redis::common()->hGet("promotion-data|0|$times", $mid);                                            
                    $ee = Loader_Redis::common()->hGet("promotion-data|1|$times", $mid);
                    if (!$de){
                        Loader_Redis::common()->hSet("promotion-data|0|$times", $mid, 1, false, false, 10*24*3600);
                    }else if ($de && !$ee){
                        Loader_Redis::common()->hSet("promotion-data|1|$times", $mid, 1, false, false, 10*24*3600);
                    }else if($ee){
                        Loader_Redis::common()->hSet("promotion-data|2|$times", $mid, 1, false, false, 10*24*3600);
                    }
                }
            }else {
                $ff = -1;
            }
        }
        $rtn['paijuFrequency1'] = $re['paijuFrequency1'];
        $rtn['paijuFrequency3'] = $re['paijuFrequency3'];
        $rtn['paijuFrequency4'] = $re['paijuFrequency4'];
        $rtn['flag1'] = $re['flag1'];
        $rtn['flag3'] = $re['flag3'];
        $rtn['flag4'] = $re['flag4'];
        if ($value){
            $rtn["flag$which"] = $value;
        }
        $rtn['status'] = $ff;
        
        return $rtn;
    }
}