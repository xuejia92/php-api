<?php 
class Action_ConfigurationAllswitch {
    
    public static function modify (){

        $closepid   = $_REQUEST['closepid'];
        
        $array  = array(
            'closepid'  => $closepid ? $closepid : '',
        );
        
        $status = Loader_Redis::common()->set('allswitch_config', $array , false, true);
        
        if ($status) {
            return true;
        }else {
            return false;
        }
        
    }
    
    public static function getChannel() {
        
        $result = Loader_Redis::common()->get('allswitch_config', false, true);
        
        $rows['cname'] = $result['closecid'];
        
        $i = 0;
        $channel = Base::factory()->getChannel();
        
        foreach ($channel as $cid=>$cname) {
            $rows['cid'][$i]['cid']   = $cid;
            $rows['cid'][$i]['cname'] = $cname;
            $i++;
        }
        
        return $rows;
        
    }
}