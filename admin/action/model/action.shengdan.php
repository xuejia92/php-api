<?php

class Action_Shengdan {
    
    public static function getItemDetail($data){
        $gg = $data['gameid'] ? $data['gameid'] : '-1';
        $cc = $data['ctype'] ? $data['ctype'] : '-1';
        
        $types  = array('0'=>'hat','1'=>'clothes','2'=>'boots','3'=>'socks');
        $keys = array('0'=>'NOW','1'=>'-1 days','2'=>'-2 days','3'=>'-3 days','4'=>'-4 days','5'=>'-5 days');
        
        $total_game = $total_ctype = $total_all = array();
        
        foreach ($keys as $k => $v) {
            $time = date('Y-m-d',strtotime($v));
            foreach (Config_Game::$game as $gameid=>$gamename) {
                foreach (Config_Game::$clientTyle as $ctype=>$cliname) {
                    foreach ($types as $type){
                        $records = Loader_Redis::common()->hGetAll("shengdan|$time|$gameid|$ctype|$type");
                        if($records){
                            foreach ($records as $record) {
                                $total_ctype['mid'][$time][$gameid][$ctype][$type] ++ ;//某个游戏某个客户端的人数
                                $total_ctype['times'][$time][$gameid][$ctype][$type] = $total_ctype['times'][$time][$gameid][$ctype][$type] + $record;//某个游戏个客户端次数
                                
                                $total_game['mid'][$time][$gameid][$type] ++ ;//某个游戏的人数
                                $total_game['times'][$time][$gameid][$type] = $total_game['times'][$time][$gameid][$type] + $record;//某个游戏的人数次数

                                $total_all['mid'][$time][$type] ++;//总人数
                                $total_all['times'][$time][$type] = $total_all['times'][$time][$type] + $record;//总次数
                                
                                ksort($total_ctype['mid']);
                                ksort($total_ctype['times']);
                                ksort($total_game['mid']);
                                ksort($total_game['times']);
                                ksort($total_all['mid']);
                                ksort($total_all['times']);
                            }
                        }
                    }
                }
            }
        }
        if ($gg == '-1'){
            return $total_all;
        }else if($cc == '-1'){
            return $total_game;
        }else {
            return $total_ctype;
        }
    }
    
    public function getJoin ($data){
        $gg = $data['gameid'] ? $data['gameid'] : '-1';
        $cc = $data['ctype'] ? $data['ctype'] : '-1';
        
        $keys = array('0'=>'NOW','1'=>'-1 days','2'=>'-2 days','3'=>'-3 days','4'=>'-4 days','5'=>'-5 days');
        
        $total_game = $total_ctype = $total_all = array();
        
        foreach ($keys as $k => $v) {
            $time = date('Y-m-d',strtotime($v));
            foreach (Config_Game::$game as $gameid=>$gamename) {
                foreach (Config_Game::$clientTyle as $ctype=>$cliname) {
                        $records = Loader_Redis::common()->hGetAll("shengdan-join|$time|$gameid|$ctype");
                        if ($records){
                            foreach ($records as $record){
                                $total_ctype[$time][$gameid][$ctype]++;
                                $total_game[$time][$gameid]++;
                                $total_all[$time]++;
                            }
                        }
                }
            }
        }
        if ($gg == '-1'){
            return $total_all;
        }else if($cc == '-1'){
            return $total_game;
        }else {
            return $total_ctype;
        }
    }
}