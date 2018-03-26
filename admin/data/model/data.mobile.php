<?php
class Data_Mobile {
    
    private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Data_Mobile();
		}
		return self::$_instances;
	}
	
	public function getItemDetail($data){
	    $data['stime'] = $data['stime'] ? strtotime($data['stime']) : strtotime("-14 days");
        $data['etime'] = $data['etime'] ? strtotime($data['etime']."23:59:59") : NOW;
        $diff          = (int)ceil(($data['stime'] - $data['etime'])/86400)-1;
	    
        $itemtype = array(99,100,101,102,103,110,111,112,113,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192);
        
        for ($i=0;$i>$diff;$i--){
            $time = date("Y-m-d",strtotime("$i days",$data['etime']));
            foreach ($itemtype as $itype){
                foreach (Config_Game::$clientTyle as $ctype=>$cliname){
                    if ($data['filter']==1){
                        $record = (int)Loader_Redis::common()->get("mobile_nofilter|$time|$itype|$ctype",false,false);
                         
                        $detail[$time][$itype]['0']     = $detail[$time][$itype]['0']+$record;
                        $detail[$time][$itype][$ctype]  = $detail[$time][$itype][$ctype]+$record;
                    }else {
                        $record = Loader_Redis::common()->hGetAll("mobile|$time|$itype|$ctype");
                        
                        $number = count($record);
                        $detail[$time][$itype]['0']     = $detail[$time][$itype]['0']+$number;
                        $detail[$time][$itype][$ctype]  = $detail[$time][$itype][$ctype]+$number;
                    }
                }
            }
        }
        ksort($detail);
        return $detail;
	}
}