<?php
class Action_ConfigurationWangyezhifu {
    
    public static function modify (){
         
        $cid = explode(',', $_REQUEST['cid_cid']);
        $closepid   = $_REQUEST['closepid'];
        
        $array  = array(
            'closecid'  => $cid ? $cid : '',
            'closepid'  => $closepid ? $closepid : '',
        );
        
        $status = Loader_Redis::common()->set('wangyezhifu_config', $array , false, true);
        
        if ($status) {
            return true;
        }else {
            return false;
        }
        
    }
    
    public static function getChannel() {
        
        $result = Loader_Redis::common()->get('wangyezhifu_config', false, true);
        
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