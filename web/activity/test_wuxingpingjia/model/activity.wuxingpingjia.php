<?php

class Activity_Wuxingpingjia {
    
    private static $_instance;
    
    public static function factory (){
        
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_Wuxingpingjia();
        }
        return self::$_instance;
    }
    
    public function getPlayInfo ($mid, $gameid, $ctype, $cid){
        $deviceno = Member::factory()->getDevicenoBymid($mid);
        $tarversion = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gameid, false, true)['version'];
        $activity_anotherGameCid = Loader_Redis::common()->hGet('another_game_cid', $gameid, false, true);
        
        if (in_array($cid, $activity_anotherGameCid)){
            $state = (int)Loader_Redis::common()->hGet("wuxingpingjia$tarversion:$cid", $deviceno);
            $flag = (int)Loader_Redis::common()->get("wuxingpingjia_commentflag$cid:$deviceno");
        }else {
            $state = (int)Loader_Redis::common()->hGet("wuxingpingjia$tarversion:$gameid", $deviceno);
            $flag = (int)Loader_Redis::common()->get("wuxingpingjia_commentflag$gameid:$deviceno");
        }
        
        if ($state==1 && $flag==0){
            $status = 1;
        }else if ($state==2){
            $status = 0;
        }else {
            $status = -1;
        }
        
        return $status;
    }
    
    public function setPlayInfo ($gameid, $mid, $sid, $cid, $pid, $ctype, $time){
        $status = $this->getPlayInfo($mid, $gameid, $ctype, $cid);
        
        if ($status == 1 && $ctype==2){

            $deviceno = Member::factory()->getDevicenoBymid($mid);
            $tarversion = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gameid, false, true)['version'];
            $config = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gameid, false, true);
            $money = $config['bonus'];
            $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $money, 'wuxingpingjia');
            
            if ($add){
                Loader_Redis::common()->incr("wuxingpingjiaFafang|$gameid|$ctype|$time", $money,90*24*3600);
                Loader_Redis::common()->hSet("wuxingpingjiaCanyunum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
                Loader_Redis::common()->incr("wuxingpingjiaCanyuci|$gameid|$ctype|$time", 1, 90*24*3600);
                
                if (in_array($cid, Config_Switch::$another_game_cid[$gameid])){
                    Loader_Redis::common()->hSet("wuxingpingjia$tarversion:$cid", $deviceno, 2, false, false, 90*24*3600);
                }else {
                    Loader_Redis::common()->hSet("wuxingpingjia$tarversion:$gameid", $deviceno, 2, false, false, 90*24*3600);
                }
                
                $state = 1;
            }else {
                $state = 0;
            }
        }else {
            $state = -1;
        }
        return $state;
    }
    
    public function goComments ($gameid, $mid, $sid, $cid, $pid, $ctype){
        $status = $this->getPlayInfo($mid, $gameid, $ctype);
        if ($status == -1 && $ctype==2){
            $deviceno = Member::factory()->getDevicenoBymid($mid);
            $tarversion = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gameid, false, true)['version'];
            $config = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gameid, false, true);
            
            if (in_array($cid, Config_Switch::$another_game_cid[$gameid])){
                Loader_Redis::common()->hSet("wuxingpingjia$tarversion:$cid", $deviceno, 1, false, false, 100*24*3600);
                Loader_Redis::common()->set("wuxingpingjia_commentflag$cid:$deviceno", 1, false, true, 40);
            }else {
                Loader_Redis::common()->hSet("wuxingpingjia$tarversion:$gameid", $deviceno, 1, false, false, 100*24*3600);
                Loader_Redis::common()->set("wuxingpingjia_commentflag$gameid:$deviceno", 1, false, true, 40);
            }
        }
    }
}