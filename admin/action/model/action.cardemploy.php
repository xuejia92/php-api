<?php

class Action_CardEmploy {

    public static function getItemDetail($data){
        $data['stime'] = $data['stime'] ? strtotime($data['stime']) : strtotime("-14 days");
        $data['etime'] = $data['etime'] ? strtotime($data['etime']."23:59:59") : NOW;
        $diff          = (int)ceil(($data['stime'] - $data['etime'])/86400)-1;


        for ($i=0;$i>$diff;$i--){
            $time = date("Y-m-d",strtotime("$i days",$data['etime']));
            foreach (Config_Game::$game as $gameid=>$gamename){
                foreach (Config_Game::$clientTyle as $ctype=>$clientname){
                    $userNum    = Loader_Redis::common()->hGetAll("cardEmployUsernum|$gameid|$ctype|$time");
                    $userCi     = Loader_Redis::common()->get("cardEmployUserci|$gameid|$ctype|$time", false, false);
                    $canyuNum   = Loader_Redis::common()->hGetAll("cardEmployCanyunum|$gameid|$ctype|$time");
                    $canyuCi    = Loader_Redis::common()->get("cardEmployCanyuci|$gameid|$ctype|$time", false, false);

                    $total[$time]['userNum']    += count($userNum);
                    $total[$time]['userCi']     += (int)$userCi;
                    $total[$time]['canyuNum']   += count($canyuNum);
                    $total[$time]['canyuCi']    += (int)$canyuCi;

                    $total_game[$time][$gameid]['userNum']      += count($userNum);
                    $total_game[$time][$gameid]['userCi']       += (int)$userCi;
                    $total_game[$time][$gameid]['canyuNum']     += count($canyuNum);
                    $total_game[$time][$gameid]['canyuCi']      += (int)$canyuCi;

                    $total_ctype[$time][$gameid][$ctype]['userNum']     += count($userNum);
                    $total_ctype[$time][$gameid][$ctype]['userCi']      += (int)$userCi;
                    $total_ctype[$time][$gameid][$ctype]['canyuNum']    += count($canyuNum);
                    $total_ctype[$time][$gameid][$ctype]['canyuCi']     += (int)$canyuCi;
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