<?php
class Action_ConfigurationVersionup {
    
    public static function modify (){
        $gameid   = $_REQUEST['gameid'];
        $version1 = $_REQUEST['version1'];
        $version2 = $_REQUEST['version2'];
        $bonus    = $_REQUEST['bonus'];
        
        $array = array(
            '1' => $version1,
            '2' => $version2,
            'bonus' => (int)$bonus
        );
        
        $status = Loader_Redis::common()->hSet('activity_versionup_config', $gameid, $array, false, true);
        
        if ($status) {
            return true;
        }else {
            return false;
        }
        
    }
}