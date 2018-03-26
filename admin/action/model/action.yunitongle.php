<?php

class Action_Yunitongle {
    
    public static function getItemDetail($data){
        $data['stime'] = $data['stime'] ? strtotime($data['stime']) : strtotime("-14 days");
        $data['etime'] = $data['etime'] ? strtotime($data['etime']."23:59:59") : NOW;
        $diff          = (int)ceil(($data['stime'] - $data['etime'])/86400)-1;
        
        
        for ($i=0;$i>$diff;$i--){
            $time = date("Y-m-d",strtotime("$i days",$data['etime']));
            foreach (Config_Game::$game as $gameid=>$gamename){
                foreach (Config_Game::$clientTyle as $ctype=>$clientname){
                    $userNum    = Loader_Redis::common()->hGetAll("yunitongleUsernum|$gameid|$ctype|$time");
                    $userCi     = Loader_Redis::common()->get("yunitongleUserci|$gameid|$ctype|$time", false, false);
                    $canyuNum   = Loader_Redis::common()->hGetAll("yunitongleLingquNum|$gameid|$ctype|$time");
                    $canyuCi    = Loader_Redis::common()->get("yunitongleLingquCi|$gameid|$ctype|$time", false, false);
                    $FF         = Loader_Redis::common()->get("yunitongleFafang|$gameid|$ctype|$time", false, false);
                    
                    $ipNum      = Loader_Redis::common()->hGetAll("yunitongle1|$gameid|$ctype|$time");
                    $ipCi       = Loader_Redis::common()->get("yunitongleCi1|$gameid|$ctype|$time", false, false);
                    $idNum      = Loader_Redis::common()->hGetAll("yunitongle2|$gameid|$ctype|$time");
                    $idCi       = Loader_Redis::common()->get("yunitongleCi2|$gameid|$ctype|$time", false, false);
                    $piNum      = Loader_Redis::common()->hGetAll("yunitongle3|$gameid|$ctype|$time");
                    $piCi       = Loader_Redis::common()->get("yunitongleCi3|$gameid|$ctype|$time", false, false);
                    $moNum      = Loader_Redis::common()->hGetAll("yunitongle4|$gameid|$ctype|$time");
                    $moCi       = Loader_Redis::common()->get("yunitongleCi4|$gameid|$ctype|$time", false, false);
                    
                    $total[$time]['userNum']    += count($userNum);
                    $total[$time]['userCi']     += $userCi;
                    $total[$time]['canyuNum']   += count($canyuNum);
                    $total[$time]['canyuCi']    += $canyuCi;
                    $total[$time]['fafang']     += $FF;
                    $total[$time]['iPhoneNum']  += count($ipNum);
                    $total[$time]['iPhoneCi']   += $ipCi;
                    $total[$time]['iPadNum']    += count($idNum);
                    $total[$time]['iPadCi']     += $idCi;
                    $total[$time]['paiNum']     += count($piNum);
                    $total[$time]['paiCi']      += $piCi;
                    $total[$time]['moneyNum']   += count($moNum);
                    $total[$time]['moneyCi']    += $moCi;
                    
                    $total_game[$time][$gameid]['userNum']      += count($userNum);
                    $total_game[$time][$gameid]['userCi']       += $userCi;
                    $total_game[$time][$gameid]['canyuNum']     += count($canyuNum);
                    $total_game[$time][$gameid]['canyuCi']      += $canyuCi;
                    $total_game[$time][$gameid]['fafang']       += $FF;
                    $total_game[$time][$gameid]['iPhoneNum']    += count($ipNum);
                    $total_game[$time][$gameid]['iPhoneCi']     += $ipCi;
                    $total_game[$time][$gameid]['iPadNum']      += count($idNum);
                    $total_game[$time][$gameid]['iPadCi']       += $idCi;
                    $total_game[$time][$gameid]['paiNum']       += count($piNum);
                    $total_game[$time][$gameid]['paiCi']        += $piCi;
                    $total_game[$time][$gameid]['moneyNum']     += count($moNum);
                    $total_game[$time][$gameid]['moneyCi']      += $moCi;
                    
                    $total_ctype[$time][$gameid][$ctype]['userNum']      += count($userNum);
                    $total_ctype[$time][$gameid][$ctype]['userCi']       += $userCi;
                    $total_ctype[$time][$gameid][$ctype]['canyuNum']     += count($canyuNum);
                    $total_ctype[$time][$gameid][$ctype]['canyuCi']      += $canyuCi;
                    $total_ctype[$time][$gameid][$ctype]['fafang']       += $FF;
                    $total_ctype[$time][$gameid][$ctype]['iPhoneNum']    += count($ipNum);
                    $total_ctype[$time][$gameid][$ctype]['iPhoneCi']     += $ipCi;
                    $total_ctype[$time][$gameid][$ctype]['iPadNum']      += count($idNum);
                    $total_ctype[$time][$gameid][$ctype]['iPadCi']       += $idCi;
                    $total_ctype[$time][$gameid][$ctype]['paiNum']       += count($piNum);
                    $total_ctype[$time][$gameid][$ctype]['paiCi']        += $piCi;
                    $total_ctype[$time][$gameid][$ctype]['moneyNum']     += count($moNum);
                    $total_ctype[$time][$gameid][$ctype]['moneyCi']      += $moCi;
                }
            }
        }
        
        ksort($total);
        ksort($total_game);
        ksort($total_ctype);
        
        if ($data['gameid']){
            if ($data['ctype']){
                return $total_ctype;
            }else {
                return $total_game;
            }
        }else {
            return $total;
        }
    }
    
}