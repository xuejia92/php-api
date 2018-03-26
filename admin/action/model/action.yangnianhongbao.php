<?php
class Action_Yangnianhongbao{

    public static function getItemDetail($data){
        $types      = array('0'=>'1', '1'=>'2', '2'=>'3');
        $numbers    = array('0'=>'186', '1'=>'187');
        
        for ($i=-15;$i<=0;$i++){
            $time = date("Y-m-d",strtotime("$i days"));
            
            $fafang = Loader_Redis::common()->get("yangnianhongbaoFafang|$time", false, false);
            $renshu = Loader_Redis::common()->hGetAll("yangnianhongbaoRenshu|$time");
            $renci  = Loader_Redis::common()->get("yangnianhongbaoRenci|$time", false, false);
            $canyur = Loader_Redis::common()->hGetAll("yangnianhongbaoCishu|$time");
            $canyuc = Loader_Redis::common()->get("yangnianhongbaoLRenci|$time", false, false);
            
            foreach ($types as $type){
                foreach ($numbers as $number){
                    $hongbaoR = Loader_Redis::common()->hGetAll("yangnianhongbaoR".$type.$number."|$time");
                    $hongbaoC = Loader_Redis::common()->get("yangnianhongbaoC".$type.$number."|$time", false, false);
                    
                    $total[$time]['hongbaoR'][$type][$number] += count($hongbaoR);
                    $total[$time]['hongbaoC'][$type][$number] += $hongbaoC;
                    
                }
            }
            
            $total[$time]['fafang'] += $fafang;
            $total[$time]['renshu'] += count($renshu);
            $total[$time]['renci']  += $renci;
            $total[$time]['canyur'] += count($canyur);
            $total[$time]['canyuc'] += $canyuc;
        }
        
        ksort($total);
        
        
        return $total;
    }
}