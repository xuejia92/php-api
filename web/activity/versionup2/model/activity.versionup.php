<?php

class Activity_Versionup {
    
    private static $_instance;
    
    public static function factory (){
        
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_Versionup();
        }
        return self::$_instance;
    }
    
    public function getPlayInfo ($mid, $gameid, $ctype, $versions){
        $otherCtype = 3-$ctype;
        
        $version = Loader_Redis::ote($mid)->hGet("OTE|$mid", "versionup$ctype:$gameid");
        $otherVersion = Loader_Redis::ote($mid)->hGet("OTE|$mid", "versionup$otherCtype:$gameid");
        
        $tarVersion = Loader_Redis::common()->hGet('activity_versionup_config', $gameid, false, true);
        
        if ($version<$tarVersion[$ctype] && $otherVersion<$tarVersion[$otherCtype] && $versions>=$tarVersion[$ctype]){
            $status = 1;
        }else if ($version<$tarVersion[$ctype] && $otherVersion<$tarVersion[$otherCtype] && $versions<$tarVersion[$ctype]){
            $status = -1;
        }else {
            $status = 0;
        }
        
        return $status;
    }
    
    public function setPlayInfo ($gameid, $mid, $sid, $cid, $pid, $ctype, $versions, $time){
        $status = $this->getPlayInfo($mid, $gameid, $ctype, $versions);

        if ($status == 1){
            
            $config = Loader_Redis::common()->hGet('activity_versionup_config', $gameid, false, true);
            $version = $config[$ctype];
            $money = $config['bonus'];
            $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $money, 'versionup');
            
            if ($add){
                Loader_Redis::ote($mid)->hSet("OTE|$mid", "versionup$ctype:$gameid", $versions);
                
                Loader_Redis::common()->incr("versionupFafang|$gameid|$ctype|$time", $money, 90*24*3600);
                Loader_Redis::common()->hSet("versionupCanyunum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);//参与人数统计
                Loader_Redis::common()->incr("versionupCanyuci|$gameid|$ctype|$time", 1, 90*24*3600);
                
                $state = 1;
            }else {
                $state = 0;
            }
        }else {
            $state = -1;
        }
        return $state;
    }
}