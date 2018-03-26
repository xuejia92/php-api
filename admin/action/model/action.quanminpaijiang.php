<?php

class Action_Quanminpaijiang {

    public static function getItemDetail($data){
        $data['stime'] = $data['stime'] ? strtotime($data['stime']) : strtotime("-14 days");
        $data['etime'] = $data['etime'] ? strtotime($data['etime']."23:59:59") : NOW;
        $diff          = (int)ceil(($data['stime'] - $data['etime'])/86400)-1;


        for ($i=0;$i>$diff;$i--){
            $time = date("Y-m-d",strtotime("$i days",$data['etime']));
            foreach (Config_Game::$game as $gameid=>$gamename){
                foreach (Config_Game::$clientTyle as $ctype=>$clientname){
                    $userNum    = Loader_Redis::common()->hGetAll("quanminpaijiangUsernum|$gameid|$ctype|$time");
                    $userCi     = Loader_Redis::common()->get("quanminpaijiangUserci|$gameid|$ctype|$time", false, false);
                    $canyuNum   = Loader_Redis::common()->hGetAll("quanminpaijiangCanyuNum|$gameid|$ctype|$time");
                    $canyuCi    = Loader_Redis::common()->get("quanminpaijiangCanyuCi|$gameid|$ctype|$time", false, false);
                    $FF         = Loader_Redis::common()->get("quanminpaijiangFafang|$gameid|$ctype|$time", false, false);

                    $bronzeNum  = Loader_Redis::common()->hGetAll("quanminpaijiangTapNum1|$gameid|$ctype|$time");
                    $bronzeCi   = Loader_Redis::common()->get("quanminpaijiangTapCi1|$gameid|$ctype|$time", false, false);
                    $silverNum  = Loader_Redis::common()->hGetAll("quanminpaijiangTapNum2|$gameid|$ctype|$time");
                    $silverCi   = Loader_Redis::common()->get("quanminpaijiangTapCi2|$gameid|$ctype|$time", false, false);
                    $goldNum    = Loader_Redis::common()->hGetAll("quanminpaijiangTapNum3|$gameid|$ctype|$time");
                    $goldCi     = Loader_Redis::common()->get("quanminpaijiangTapCi3|$gameid|$ctype|$time", false, false);

                    $total[$time]['userNum']    += count($userNum);
                    $total[$time]['userCi']     += (int)$userCi;
                    $total[$time]['canyuNum']   += count($canyuNum);
                    $total[$time]['canyuCi']    += (int)$canyuCi;
                    $total[$time]['fafang']     += (int)$FF;
                    $total[$time]['bronzeNum']  += count($bronzeNum);
                    $total[$time]['bronzeCi']   += (int)$bronzeCi;
                    $total[$time]['silverNum']  += count($silverNum);
                    $total[$time]['silverCi']   += (int)$silverCi;
                    $total[$time]['goldNum']    += count($goldNum);
                    $total[$time]['goldCi']     += (int)$goldCi;

                    $total_game[$time][$gameid]['userNum']      += count($userNum);
                    $total_game[$time][$gameid]['userCi']       += (int)$userCi;
                    $total_game[$time][$gameid]['canyuNum']     += count($canyuNum);
                    $total_game[$time][$gameid]['canyuCi']      += (int)$canyuCi;
                    $total_game[$time][$gameid]['fafang']       += (int)$FF;
                    $total_game[$time][$gameid]['bronzeNum']    += count($bronzeNum);
                    $total_game[$time][$gameid]['bronzeCi']     += (int)$bronzeCi;
                    $total_game[$time][$gameid]['silverNum']    += count($silverNum);
                    $total_game[$time][$gameid]['silverCi']     += (int)$silverCi;
                    $total_game[$time][$gameid]['goldNum']      += count($goldNum);
                    $total_game[$time][$gameid]['goldCi']       += (int)$goldCi;

                    $total_ctype[$time][$gameid][$ctype]['userNum']     += count($userNum);
                    $total_ctype[$time][$gameid][$ctype]['userCi']      += (int)$userCi;
                    $total_ctype[$time][$gameid][$ctype]['canyuNum']    += count($canyuNum);
                    $total_ctype[$time][$gameid][$ctype]['canyuCi']     += (int)$canyuCi;
                    $total_ctype[$time][$gameid][$ctype]['fafang']      += (int)$FF;
                    $total_ctype[$time][$gameid][$ctype]['bronzeNum']   += count($bronzeNum);
                    $total_ctype[$time][$gameid][$ctype]['bronzeCi']    += (int)$bronzeCi;
                    $total_ctype[$time][$gameid][$ctype]['silverNum']   += count($silverNum);
                    $total_ctype[$time][$gameid][$ctype]['silverCi']    += (int)$silverCi;
                    $total_ctype[$time][$gameid][$ctype]['goldNum']     += count($goldNum);
                    $total_ctype[$time][$gameid][$ctype]['goldCi']      += (int)$goldCi;
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