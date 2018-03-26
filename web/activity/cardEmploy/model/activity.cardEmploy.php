<?php
class Activity_CardEmploy{
    
    private static $_instance;
    
    public static function factory(){
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_CardEmploy();
        }
        return self::$_instance;
    }
    
    public function getInfo ($mid){
        $status = Loader_Redis::common()->hGet('cardEmploy', $mid);
        $status = (int)$status;
        
        return $status;
    }
    
    public function employ ($data,$time){
        
        $mid    = $data['mid'];
        $gameid = $data['gameid'];
        $ctype  = $data['ctype'];
        $mtkey  = $data['mtkey'];

        $ss = $this->getInfo($mid);
        
        $userInfoKey = Config_Keys::getUserInfo($mid);
        $server_mtkey = Loader_Redis::minfo($mid)->hGet($userInfoKey, 'mtkey');
        
        $status = array();
        $status['result'] = 0;
        if ($mtkey == $server_mtkey && $ss==0){
            $result = Helper::curl("192.168.1.147/paycenter/cbapi/cardEmploy.php", $data,'get');
            $status = json_decode($result, true);
            
            if ($status['result'] == 1){
                Loader_Redis::common()->hSet('cardEmploy', $mid, 1, false, false, 7*24*3600);
                
                Loader_Redis::common()->hSet("cardEmployCanyunum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);//参与人数统计
                Loader_Redis::common()->incr("cardEmployCanyuci|$gameid|$ctype|$time", 1, 90*24*3600);
            }
        }
        return $status;
    }
}