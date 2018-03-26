<?php
class Action_ConfigurationWuxingpingjia {
    
    public static function modify (){
        $gameid   = $_REQUEST['gameid'];
        $version = $_REQUEST['version'];
        $bonus    = $_REQUEST['bonus'];
        $address  = $_REQUEST['url'];
        $gid    = $_REQUEST['gid'];
        
        $array = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gameid, false, true);
        
        $array['version'] = $version; 
        $array['bonus'] = $bonus;
        $array['url'][$gid] = $address;
        
        $status = Loader_Redis::common()->hSet('activity_wuxingpingjia_config', $gameid, $array, false, true);
        
        if ($status) {
            return true;
        }else {
            return false;
        }
        
    }
    
    public static function addcid (){
        $gameid = $_REQUEST['gameid'];
        $cid    = $_REQUEST['cid'];
        
        $array = Loader_Redis::common()->hGet('another_game_cid', $gameid, false, true);
        
        if ($array && !in_array($cid, $array)){
            $array[] = (int)$cid;
        }else {
            $array[] = (int)$cid;
        }
        
        $status = Loader_Redis::common()->hSet('another_game_cid', $gameid, $array, false, true);
        
        if ($status) {
            return true;
        }else {
            return false;
        }
    }
    
    public static function deletcid() {
        $gameid = $_REQUEST['gameid'];
        $cid    = $_REQUEST['cid'];
        
        $list = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gameid, false, true);
        
        unset($list['url'][$cid]);
        
        Loader_Redis::common()->hSet('activity_wuxingpingjia_config', $gameid, $list, false, true);
        
        
        $array = Loader_Redis::common()->hGet('another_game_cid', $gameid, false, true);
        
        foreach ($array as $key=>$val){
            if ($val == $cid){
                unset($array[$key]);
            }
        }
        
        $status = Loader_Redis::common()->hSet('another_game_cid', $gameid, $array, false, true);
        
        if ($status) {
            return true;
        }else {
            return false;
        }
    }
}