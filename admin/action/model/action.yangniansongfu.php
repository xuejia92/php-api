<?php
class Action_Yangniansongfu{

    public static function getItemDetail($data){
        $types = array('0'=>'888', '1'=>'8888', '2'=>'100', '3'=>'6');

        for ($i=-15;$i<=0;$i++){
            $time = date("Y-m-d",strtotime("$i days"));
            foreach (Config_Game::$game as $gameid=>$gamename){
                    $fafang = Loader_Redis::common()->get("yangniansongfuFafang|$time|$gameid", false, false);
                    $renshu = Loader_Redis::common()->hGetAll("yangniansongfuRenshu|$time|$gameid");
                    $renci  = Loader_Redis::common()->get("yangniansongfuRenci|$time|$gameid", false, false);
                    $canyur = Loader_Redis::common()->hGetAll("yangniansongfuTotal|$time|$gameid");
                    $canyuc = Loader_Redis::common()->get("yangniansongfuCount|$time|$gameid", false,  false);
                    
                    foreach ($types as $type){
                        $jiangx = Loader_Redis::common()->get("yangniansongfu$type|$time|$gameid", false, false);
                        
                        $total[$time][$type] += $jiangx;
                        $total_game[$time][$gameid][$type] += $jiangx;
                    }
                    
                    $total[$time]['fafang'] += $fafang;
                    $total[$time]['renshu'] += count($renshu);
                    $total[$time]['renci']  += $renci;
                    $total[$time]['canyur'] += count($canyur);
                    $total[$time]['canyuc'] += $canyuc;
                    
                    $total_game[$time][$gameid]['fafang']   += $fafang;
                    $total_game[$time][$gameid]['renshu']   += count($renshu);
                    $total_game[$time][$gameid]['renci']    += $renci;
                    $total_game[$time][$gameid]['canyur']   += count($canyur);
                    $total_game[$time][$gameid]['canyuc']   += $canyuc;
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