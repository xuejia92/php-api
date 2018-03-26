<?php

class Activity_Buyu {
    
    private static $_instance;
    
    public static function factory(){
        
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_Buyu();
        }
        
        return self::$_instance;
    }
    
    //获取用户的牌局信息
    public function getFishingInfo($mid) {
        $fishingInfo    = Loader_Redis::game()->hGetAll("FishPlayer:$mid");
        $fish1          = (int)$fishingInfo['fish:0'];
        $fish2          = (int)$fishingInfo['fish:5'];
        $fish3          = (int)$fishingInfo['fish:11'];
        
        return array($fish1,$fish2,$fish3);
    }
    
    public function getPlayInfo($mid, $gameid) {
        $fishingInfo    = $this->getFishingInfo($mid, $gameid);
        
        $fish1  = Loader_Redis::common()->hGet("buyuPlayInfo|$mid",1);
        $fish2  = Loader_Redis::common()->hGet("buyuPlayInfo|$mid", 2);
        $fish3  = Loader_Redis::common()->hGet("buyuPlayInfo|$mid", 3);
        
        $button1 = $button2 = $button3 = 0;
        
        if ($fishingInfo[0] >= 20 && !$fish1){
            $button1    = 1;
        }else if ($fishingInfo[0] >= 20 && $fish1){
            $button1    = 2;
        }
        if ($fishingInfo[1] >= 10 && !$fish2){
            $button2    = 1;
        }else if ($fishingInfo[1] >= 10 && $fish2){
            $button2    = 2;
        }
        if ($fishingInfo[2] >= 3 && !$fish3){
            $button3    = 1;
        }else if ($fishingInfo[2] >= 3 && $fish3){
            $button3    = 2;
        }
        return array($button1, $button2, $button3);
    }
    
    public function setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $time, $tapType) {
        $fishingInfo    = $this->getFishingInfo($mid);
        
        switch ($tapType){
            case 1:
                $need   = 20;
                $bonus  = 888;
                break;
                
            case 2:
                $need   = 10;
                $bonus  = 1888;
                break;
                
            case 3:
                $need   = 3;
                $bonus  = 2888;
                break;
        }
        
        $flag = (int)Loader_Redis::common()->hGet("buyuPlayInfo|$mid", $tapType);
        
        if ($flag==0){
            if ($fishingInfo[$tapType-1] >= $need){
                $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $bonus, '捕鱼活动');//发放金币
                
                if ($add){
                    Loader_Redis::common()->incr("buyuFamoney|$gameid|$ctype|$time", $bonus,90*24*3600);//发放金币统计
                    
                    Loader_Redis::common()->hSet("buyuPlayInfo|$mid", $tapType, 1, false, false, Helper::time2morning());//领取限制
                    
                    Loader_Redis::common()->hSet("buyuTapNum$tapType|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);//点击次数统计
                    Loader_Redis::common()->incr("buyuTapCi$tapType|$gameid|$ctype|$time", 1,90*24*3600);
                    
                    Loader_Redis::common()->hSet("buyuCanyunum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);//参与人数统计
                    Loader_Redis::common()->incr("buyuCanyuci|$gameid|$ctype|$time", 1, 90*24*3600);
                    
                    $return['bonus']    = $bonus;
                    $return['status']   = 1;
                }else {
                    $return['status'] = 0;
                }
            }else {
                $return['status'] = -1;
            }
        }else {
            $return['status'] = -2;
        }
        
        return $return;
    }
}