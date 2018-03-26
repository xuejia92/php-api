<?php
class Data_Search {
    
    private static $_instance;
    
    public static function factory (){
        
        if (!is_object(self::$_instance)){
                self::$_instance = new Data_Search();
        }
        return self::$_instance;
    }
    
    
    public function getClickNum ($data){
        $stime = $data['stime'] ? strtotime($data['stime']) : strtotime("-14 days");
        $etime = $data['etime'] ? strtotime($data['etime']."23:59:59") : NOW;
        $diff  = (int)ceil(($stime - $etime)/86400)-1;
        
        for ($i=0;$i>$diff;$i--){
            $time = date("Y-m-d",strtotime("$i days",$etime));
            $record = Loader_Redis::common()->hGetAll("tuiGuangWeb_clickIPCount_$time");
            
            foreach ($record as $key=>$value){
                $keyword = explode('|', $key);
                $aitemid = $keyword[0];
                $actype  = $keyword[1];
                $aip     = $keyword[2];
                
                if (in_array($aitemid, array(190,191,192))){
                    $totalnum[$time][100][0][0] += 1;
                    $totalnum[$time][100][$actype][0] += 1;
                    $totalnum[$time][100][0][1] += $value;
                    $totalnum[$time][100][$actype][1] += $value;
                }
                
                if (in_array($aitemid, array(193,194,195))){
                    $totalnum[$time][200][0][0] += 1;
                    $totalnum[$time][200][$actype][0] += 1;
                    $totalnum[$time][200][0][1] += $value;
                    $totalnum[$time][200][$actype][1] += $value;
                }
                
                $totalnum[$time][$aitemid][0][0] += 1;
                $totalnum[$time][$aitemid][$actype][0] += 1;
                $totalnum[$time][$aitemid][0][1] += $value;
                $totalnum[$time][$aitemid][$actype][1] += $value;
            }
        }
        ksort($totalnum);
        
        return $totalnum;
    }
    
    public function getClickIPNum ($data){
        $stime = $data['stime'] ? strtotime($data['stime']) : strtotime("-3 days");
        $etime = $data['etime'] ? strtotime($data['etime']."23:59:59") : NOW;
        $diff  = (int)ceil(($stime - $etime)/86400)-1;
        
        for ($i=0;$i>$diff;$i--){
            $time = date("Y-m-d",strtotime("$i days",$etime));
            $record = Loader_Redis::common()->hGetAll("tuiGuangWeb_clickIPCount_$time");
        
            foreach ($record as $key=>$value){
                $keyword = explode('|', $key);
                $aitemid = $keyword[0];
                $actype  = $keyword[1];
                $aip     = $keyword[2];
                
                if (in_array($aitemid, array(190,191,192))){
                    $totalnum[100][$aip] += $value;
                }
                
                if (in_array($aitemid, array(193,194,195))){
                    $totalnum[200][$aip] += $value;
                }
                
                $totalnum[$aitemid][$aip] += $value;
            }
        }
        
        foreach ($totalnum as $k=>$v){
            foreach ($v as $ip=>$times){
                if ($times>=3){
                    $total[$k][$ip] = $times;
                }
            }
            arsort($total[$k]);
        }
        
        return $total;
    }
    
    
}