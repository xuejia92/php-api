<?php
class Action_Jiaochatuiguang {
    
    public static  function getItemDetail($data){
        $gg = $data['gameid'] ? $data['gameid'] : '-1';
        $cc = $data['ctype'] ? $data['ctype'] : '-1';
        
        $arr   = array('0'=>'1','1'=>'3','2'=>'4'); 
        $types = array('0'=>'once','1'=>'twice','2'=>'thrice');
        $keys  = array('0'=>'NOW','1'=>'-1 days','2'=>'-2 days','3'=>'-3 days','4'=>'-4 days','5'=>'-5 days','6'=>'-6 days','7'=>'-7 days','8'=>'-8 days','9'=>'-9 days');
        
        $total_all = array();
        
        foreach ($keys as $v) {
            $times = date('Y-m-d',strtotime($v));
            $total = Loader_Redis::common()->hGetAll("promotion-data|$times");
            if ($total){
                $total_all[$times]['all'] = count($total);
            }
            foreach ($types as $ty=>$type){
                $records = Loader_Redis::common()->hGetAll("promotion-data|$ty|$times");
                if($records){
                    foreach ($records as $record) {
                        $total_all[$times]['one'][$type]++;
                    }
                }
            }
            foreach ($arr as $ar){
                $resu = Loader_Redis::common()->hGetAll("promotion|$ar|$times");
                if ($resu){
                    $total_all[$times]['which'][$ar] = count($resu);
                }
            }
        }
        ksort($total_all);
        
        return $total_all;
    }
}