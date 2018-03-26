<?php
class Activity_Quanminpaijiang {
    
    private static $_instance;
    
    public static function factory () {
        
        if (!is_object(self::$_instance)) {
            self::$_instance = new Activity_Quanminpaijiang();
        }
        return self::$_instance;
    }
    
    public function getPlayInfo ($gameid, $mid, $ctype, $time) {
        
        switch ($gameid) {
            case 1:
                $paijuinfo1 = strlen(Loader_Redis::common()->hGet("sh_winstreak1",$mid));
                $paijuinfo2 = strlen(Loader_Redis::common()->hGet("sh_winstreak2",$mid));
                $paijuinfo3 = strlen(Loader_Redis::common()->hGet("sh_winstreak3",$mid));
                $paijuinfo  = $paijuinfo1 + $paijuinfo2*2 + $paijuinfo3*3;
                break;
                
            case 3:
                $paijuinfo1 = strlen(Loader_Redis::rank(3)->hGet("lc_winstreak1",$mid));
                $paijuinfo2 = strlen(Loader_Redis::rank(3)->hGet("lc_winstreak2",$mid));
                $paijuinfo3 = strlen(Loader_Redis::rank(3)->hGet("lc_winstreak3",$mid));
                $paijuinfo4 = strlen(Loader_Redis::rank(3)->hGet("lc_winstreak4",$mid));
                $paijuinfo  = $paijuinfo1 + $paijuinfo2*2 + $paijuinfo3*3 + $paijuinfo4*4;
                break;
                
            case 4:
                $paijuinfo1 = strlen(Loader_Redis::rank(4)->hGet("bf_winstreak1",$mid));
                $paijuinfo2 = strlen(Loader_Redis::rank(4)->hGet("bf_winstreak2",$mid));
                $paijuinfo3 = strlen(Loader_Redis::rank(4)->hGet("bf_winstreak3",$mid));
                $paijuinfo  = $paijuinfo1 + $paijuinfo2*2 + $paijuinfo3*3;
                break;
        }
        
        $result['use']      = $use = (int)Loader_Redis::common()->hGet("quanminpaijiangCanyuNum|$gameid|$ctype|$time", $mid);
        $result['sheng']    = $paijuinfo - $use;
        $result['bronze']   = (int)Loader_Redis::common()->hGet("quanminpaijiangTapNum1|$gameid|$ctype|$time", $mid);
        $result['silver']   = (int)Loader_Redis::common()->hGet("quanminpaijiangTapNum2|$gameid|$ctype|$time", $mid);
        $result['gold']     = (int)Loader_Redis::common()->hGet("quanminpaijiangTapNum3|$gameid|$ctype|$time", $mid);
        
        return $result;
    }
    
    public function setPlayInfo ($gameid, $mid, $ctype, $sid, $cid, $pid, $time, $need, $bonus, $taptype) {
        
        $record = $this->getPlayInfo($gameid, $mid, $ctype, $time);
        $use    = $record['use'];
        $sheng  = $record['sheng'];
        $bronze = $record['bronze'];
        $silver = $record['silver'];
        $gold   = $record['gold'];
        
        $needs = $need[$gameid][$taptype];
        
        switch ($taptype) {
            case 1:
                $had = $bronze;
                break;
            case 2:
                $had = $silver;
                break;
            case 3:
                $had = $gold;
                break;
        }
        
        $result['sheng'] = $sheng; 
        
        if ($had<1) {
            if ($sheng>=$needs) {
                $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $bonus[$taptype], '全民派奖');
                if ($add) {
                    
                    Loader_Redis::common()->incr("quanminpaijiangFafang|$gameid|$ctype|$time", $bonus[$taptype], 90*24*3600);
                    Loader_Redis::common()->hSet("quanminpaijiangCanyuNum|$gameid|$ctype|$time", $mid, $use+$needs, false, false, 90*24*3600);
                    Loader_Redis::common()->incr("quanminpaijiangCanyuCi|$gameid|$ctype|$time", 1, 90*24*3600);
                    Loader_Redis::common()->hSet("quanminpaijiangTapNum$taptype|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
                    Loader_Redis::common()->incr("quanminpaijiangTapCi$taptype|$gameid|$ctype|$time", 1, 90*24*3600);
                    
                    $result['sheng'] = $sheng - $needs;
                    $result['money'] = $bonus[$taptype];
                    $result['status'] = 1;
                }else {
                    $result['status'] = 0;
                }
            }else {
                $result['status'] = -1;
            }
        }else {
            $result['status'] = -2;
        }
        
        return $result;
    }
    
}