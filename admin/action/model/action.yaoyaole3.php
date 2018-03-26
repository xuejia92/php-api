<?php
class Action_Yaoyaole3 {
    
    public static function getItemDetail($data){
        for ($i=-15;$i<=0;$i++){
            $time = date("Y-m-d",strtotime("$i days"));
            
            foreach (Config_Game::$game as $gameid=>$gamename){
                $dianArray      = Loader_Redis::common()->hgetAll('YYL_Usernum_'.$gameid.'_'.$time);
                $userArray      = Loader_Redis::common()->hgetAll('YYL_'.$gameid.'_'.$time);
                $HS             = Loader_Redis::common()->get('YYL_HS_'.$gameid.'_'.$time);
                $FF             = Loader_Redis::common()->get('YYL_FF_'.$gameid.'_'.$time);
                $BZUserArray    = Loader_Redis::common()->hgetAll('YYL_BZ_'.$gameid.'_'.$time);
                
                $dianNum = 0;
                $dianCi  = 0;
                if($dianArray){
                    $dianNum = count($dianArray);
                    foreach($dianArray as $key=>$value){
                        $dianCi = $dianCi+$value;
                    }
                }
                
                $userNum = 0;
		        $userCi  = 0;
                if($userArray){
                    $userNum = count($userArray);
                    foreach($userArray as $key=>$value){
                        $userCi = $userCi+$value;
                    }
                }
                
                $BZuserNum 			= 0;
                $BZuserCi			= 0;
                if($BZUserArray){
                    $BZuserNum = count($BZUserArray);
                    foreach($BZUserArray as $key=>$value){
                        $BZuserCi = $BZuserCi + $value;
                    }
                }
                
                $total[$time]['dianNum']    += $dianNum;
                $total[$time]['dianCi']     += $dianCi;
                $total[$time]['userNum']    += $userNum;
                $total[$time]['userCi']     += $userCi;
                $total[$time]['huishou']    += Helper::uint($HS);
                $total[$time]['fafang']     += Helper::uint($FF);
                $total[$time]['bzNum']      += $BZuserNum;
                $total[$time]['bzCi']       += $BZuserCi;
                
                $total_game[$time][$gameid]['dianNum']    += $dianNum;
                $total_game[$time][$gameid]['dianCi']     += $dianCi;
                $total_game[$time][$gameid]['userNum']    += $userNum;
                $total_game[$time][$gameid]['userCi']     += $userCi;
                $total_game[$time][$gameid]['huishou']    += Helper::uint($HS);
                $total_game[$time][$gameid]['fafang']     += Helper::uint($FF);
                $total_game[$time][$gameid]['bzNum']      += $BZuserNum;
                $total_game[$time][$gameid]['bzCi']       += $BZuserCi;
            }
        }
        
        ksort($total);
        ksort($total_game);
        
        if ($data['gameid']){
            return $total_game;
        }else {
            return $total;
        }
    }
}