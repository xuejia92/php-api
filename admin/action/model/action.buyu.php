<?php

class Action_Buyu {

    public static function getItemDetail($data){
        $data['stime'] = $data['stime'] ? strtotime($data['stime']) : strtotime("-14 days");
        $data['etime'] = $data['etime'] ? strtotime($data['etime']."23:59:59") : NOW;
        $diff          = (int)ceil(($data['stime'] - $data['etime'])/86400)-1;


        for ($i=0;$i>$diff;$i--){
            $time = date("Y-m-d",strtotime("$i days",$data['etime']));
            foreach (Config_Game::$game as $gameid=>$gamename){
                foreach (Config_Game::$clientTyle as $ctype=>$clientname){
                    $userNum    = Loader_Redis::common()->hGetAll("buyuUsernum|$gameid|$ctype|$time");
                    $userCi     = Loader_Redis::common()->get("buyuUserci|$gameid|$ctype|$time", false, false);
                    $canyuNum   = Loader_Redis::common()->hGetAll("buyuCanyunum|$gameid|$ctype|$time");
                    $canyuCi    = Loader_Redis::common()->get("buyuCanyuci|$gameid|$ctype|$time", false, false);
                    
                    $FF         = Loader_Redis::common()->get("buyuFamoney|$gameid|$ctype|$time", false, false);
                    
                    $tapNum1    = Loader_Redis::common()->hGetAll("buyuTapNum1|$gameid|$ctype|$time");
                    $tapCi1     = Loader_Redis::common()->get("buyuTapCi1|$gameid|$ctype|$time", false, false);
                    $tapNum2    = Loader_Redis::common()->hGetAll("buyuTapNum2|$gameid|$ctype|$time");
                    $tapCi2     = Loader_Redis::common()->get("buyuTapCi2|$gameid|$ctype|$time", false, false);
                    $tapNum3    = Loader_Redis::common()->hGetAll("buyuTapNum3|$gameid|$ctype|$time");
                    $tapCi3     = Loader_Redis::common()->get("buyuTapCi3|$gameid|$ctype|$time", false, false);

                    $total[$time]['userNum']    += count($userNum);
                    $total[$time]['userCi']     += (int)$userCi;
                    $total[$time]['canyuNum']   += count($canyuNum);
                    $total[$time]['canyuCi']    += (int)$canyuCi;
                    $total[$time]['fafang']     += (int)$FF;
                    $total[$time]['tapNum1']    += count($tapNum1);
                    $total[$time]['tapCi1']     += (int)$tapCi1;
                    $total[$time]['tapNum2']    += count($tapNum2);
                    $total[$time]['tapCi2']     += (int)$tapCi2;
                    $total[$time]['tapNum3']    += count($tapNum3);
                    $total[$time]['tapCi3']     += (int)$tapCi3;

                    $total_game[$time][$gameid]['userNum']      += count($userNum);
                    $total_game[$time][$gameid]['userCi']       += (int)$userCi;
                    $total_game[$time][$gameid]['canyuNum']     += count($canyuNum);
                    $total_game[$time][$gameid]['canyuCi']      += (int)$canyuCi;
                    $total_game[$time][$gameid]['fafang']       += (int)$FF;
                    $total_game[$time][$gameid]['tapNum1']      += count($tapNum1);
                    $total_game[$time][$gameid]['tapCi1']       += (int)$tapCi1;
                    $total_game[$time][$gameid]['tapNum2']      += count($tapNum2);
                    $total_game[$time][$gameid]['tapCi2']       += (int)$tapCi2;
                    $total_game[$time][$gameid]['tapNum3']      += count($tapNum3);
                    $total_game[$time][$gameid]['tapCi3']       += (int)$tapCi3;

                    $total_ctype[$time][$gameid][$ctype]['userNum']     += count($userNum);
                    $total_ctype[$time][$gameid][$ctype]['userCi']      += (int)$userCi;
                    $total_ctype[$time][$gameid][$ctype]['canyuNum']    += count($canyuNum);
                    $total_ctype[$time][$gameid][$ctype]['canyuCi']     += (int)$canyuCi;
                    $total_ctype[$time][$gameid][$ctype]['fafang']      += (int)$FF;
                    $total_ctype[$time][$gameid][$ctype]['tapNum1']     += count($tapNum1);
                    $total_ctype[$time][$gameid][$ctype]['tapCi1']      += (int)$tapCi1;
                    $total_ctype[$time][$gameid][$ctype]['tapNum2']     += count($tapNum2);
                    $total_ctype[$time][$gameid][$ctype]['tapCi2']      += (int)$tapCi2;
                    $total_ctype[$time][$gameid][$ctype]['tapNum3']     += count($tapNum3);
                    $total_ctype[$time][$gameid][$ctype]['tapCi3']      += (int)$tapCi3;
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