<?php !defined('IN WEB') AND exit('Access Denied!');

class Action_Lebao {
    
    public static function getItemDetail($data){
        
        $gg = $data['gameid'] ? $data['gameid'] : '-1';
        $cc = $data['ctype'] ? $data['ctype'] : '-1';
        
        $rows = array();
        $types    = array('0'=>'dog','1'=>'bird','2'=>'fox','3'=>'giraffe','4'=>'panda','5'=>'sheep');
        $keys = array('0'=>'NOW','1'=>'-1 days','2'=>'-2 days','3'=>'-3 days','4'=>'-4 days','5'=>'-5 days');
        
        $total_game = $total_ctype = $total_all = array();
        
        foreach ($keys as $k => $v) {
            $time = date('Y-m-d',strtotime($v));
            foreach (Config_Game::$game as $gameid=>$gamename) {
                foreach (Config_Game::$clientTyle as $ctype=>$cliname) {
                    foreach ($types as $type){
                        $records = Loader_Redis::common()->hGetAll("lebao|$time|$gameid|$ctype|$type");
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
    
    public function top (){
        $keys = array('0'=>'NOW','1'=>'-1 days','2'=>'-2 days','3'=>'-3 days','4'=>'-4 days','5'=>'-5 days');
        
        foreach ($keys as $ll){
            $now = date('ymd',strtotime($ll));
            $gg[$now] = Loader_Redis::common()->hGetAll("lebao-top15-$now");
            $o = 0;
            if ($gg[$now]){
                foreach ($gg[$now] as $kkkk=>$vvvv){
                    $be[$now][$o]['id'] = $kkkk;
                    $be[$now][$o]['number'] = $vvvv;
                    $id[$now][] = $kkkk;
                    $xx[$now][] = $vvvv;
                    $o++;
                }
                array_multisort($xx[$now],SORT_DESC,$be[$now]);
                $uu[$now]['num'] = count($be[$now]);
                $uu[$now]['who'] = array_slice($be[$now],0,20);
            }
        }
        ksort($uu);
        return $uu;
    }
    
    public function join (){
        $now = date('ymd',NOW);
        $str = Loader_Redis::common()->hGetAll("lebao-top15-$now");
        return count($str);
    }
    
    public function modify ($data){
        $now = date("Hi",NOW);
        if ($now<2000){
            $time = date("ymd",strtotime("-1 days"));
        }else {
            $time = date("ymd",NOW);
        }
        $rtn = Loader_Redis::common()->set('lebal_results_'.$time,$data,false,true,5*24*3600);
        if ($rtn){
            return true;
        }
    }
    
    public function getResult(){
        $re = Loader_Redis::common()->get('lebal_results_'.date('ymd',strtotime("-1 days")));
        $array = array('dog'=>'狗','bird'=>'鸟','fox'=>'狐狸','giraffe'=>'长颈鹿','panda'=>'熊猫','sheep'=>'羊');
        $his = $array[$re];
        return $his;
    }
}