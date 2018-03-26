<?php
class Action_Weixinactivecode {
    
    public static function getItemDetail($data){
        $data['stime'] = $data['stime'] ? strtotime($data['stime']) : strtotime("-14 days");
        $data['etime'] = $data['etime'] ? strtotime($data['etime']."23:59:59") : NOW;
        $diff          = (int)ceil(($data['stime'] - $data['etime'])/86400)-1;
        
        $total[0] = count(Loader_Redis::common()->hGetAll('wxcard'));
        for ($i=0;$i>$diff;$i--){
            $time = date("Y-m-d",strtotime("$i days",$data['etime']));
            $total[1][$time] = Loader_Redis::common()->get('weixinActiveCode|'.$time, false, false);
        }
        
        ksort($total[1]);
        
        $total[2] = Loader_Redis::common()->get('weixinActiveCodeSwitch', false, false);
        
        return $total;
    }
    
}