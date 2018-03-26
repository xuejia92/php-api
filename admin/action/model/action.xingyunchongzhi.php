<?php
class Action_Xingyunchongzhi{
    
    public static function getItemDetail($data){
    $types = array('0'=>'1.1', '1'=>'1.2', '2'=>'1.3', '3'=>'1.4', '4'=>'2','5'=>'3');

        for ($i=-15;$i<=0;$i++){
            $time = date("Y-m-d",strtotime("$i days"));
            foreach (Config_Game::$game as $gameid=>$gamename){
                foreach (Config_Game::$clientTyle as $ctype=>$cliname){
                    $dianjis    = Loader_Redis::common()->hGetAll("xingyunrenshu|$time|$gameid|$ctype");//获取点击人数
                    $dianjil    = Loader_Redis::common()->get("xingyundianji|$time|$gameid|$ctype",false,false);//获取点击次数
                    $canyus     = Loader_Redis::common()->hGetAll("xingyunchongzhi|$time|$gameid|$ctype");//获取参与人数
                    $canyul     = Loader_Redis::common()->get("xingyuntimes|$time|$gameid|$ctype",false,false);//获取参与次数
                    $shouru     = Loader_Redis::common()->get("xingyunincome|$time|$gameid|$ctype",false,false);//获取收入
                    $fafang     = Loader_Redis::common()->get("xingyunpay|$time|$gameid|$ctype",false,false);//获取发放金币数
                    $chengg     = Loader_Redis::common()->get("xingyunpayman|$time|$gameid|$ctype",false,false);//成功充值人数统计
                    $mount      = Loader_Redis::common()->get("xingyunpayamount|$time|$gameid|$ctype", false, false);//成功充值总金额
                    
                    foreach ($types as $type){
                        $beishu = Loader_Redis::common()->get("xingyun$type|$time|$gameid|$ctype",false,false);//获取抽奖倍率
                        $total[$time][$type]    = $total[$time][$type] + $beishu;
                        $total_game[$time][$gameid][$type]  = $total_game[$time][$gameid][$type] + $beishu;
                        $total_ctype[$time][$gameid][$ctype][$type] = $total_ctype[$time][$gameid][$ctype][$type] + $beishu;
                    }
                    
                    $total[$time]['dianjis']    = $total[$time]['dianjis'] + count($dianjis);
                    $total[$time]['dianjil']    = $total[$time]['dianjil'] + $dianjil;
                    $total[$time]['canyus']     = $total[$time]['canyus'] + count($canyus);
                    $total[$time]['canyul']     = $total[$time]['canyul'] + $canyul;
                    $total[$time]['shouru']     = $total[$time]['shouru'] + $shouru;
                    $total[$time]['fafang']     = $total[$time]['fafang'] + $fafang;
                    $total[$time]['chengg']     = $total[$time]['chengg'] + $chengg;
                    $total[$time]['mount']      = $total[$time]['mount'] + $mount;
                    
                    $total_game[$time][$gameid]['dianjis']    = $total_game[$time][$gameid]['dianjis'] + count($dianjis);
                    $total_game[$time][$gameid]['dianjil']    = $total_game[$time][$gameid]['dianjil'] + $dianjil;
                    $total_game[$time][$gameid]['canyus']     = $total_game[$time][$gameid]['canyus'] + count($canyus);
                    $total_game[$time][$gameid]['canyul']     = $total_game[$time][$gameid]['canyul'] + $canyul;
                    $total_game[$time][$gameid]['shouru']     = $total_game[$time][$gameid]['shouru'] + $shouru;
                    $total_game[$time][$gameid]['fafang']     = $total_game[$time][$gameid]['fafang'] + $fafang;
                    $total_game[$time][$gameid]['chengg']     = $total_game[$time][$gameid]['chengg'] + $chengg;
                    $total_game[$time][$gameid]['mount']      = $total_game[$time][$gameid]['mount'] + $mount;
                    
                    $total_ctype[$time][$gameid][$ctype]['dianjis']    = $total_ctype[$time][$gameid][$ctype]['dianjis'] + count($dianjis);
                    $total_ctype[$time][$gameid][$ctype]['dianjil']    = $total_ctype[$time][$gameid][$ctype]['dianjil'] + $dianjil;
                    $total_ctype[$time][$gameid][$ctype]['canyus']     = $total_ctype[$time][$gameid][$ctype]['canyus'] + count($canyus);
                    $total_ctype[$time][$gameid][$ctype]['canyul']     = $total_ctype[$time][$gameid][$ctype]['canyul'] + $canyul;
                    $total_ctype[$time][$gameid][$ctype]['shouru']     = $total_ctype[$time][$gameid][$ctype]['shouru'] + $shouru;
                    $total_ctype[$time][$gameid][$ctype]['fafang']     = $total_ctype[$time][$gameid][$ctype]['fafang'] + $fafang;
                    $total_ctype[$time][$gameid][$ctype]['chengg']     = $total_ctype[$time][$gameid][$ctype]['chengg'] + $chengg;
                    $total_ctype[$time][$gameid][$ctype]['mount']      = $total_ctype[$time][$gameid][$ctype]['mount'] + $mount;
                }
            }
        }
        ksort($total);
        ksort($total_game);
        ksort($total_ctype);
        
        if ($data['gameid']){
            if($data['ctype']){
                return $total_ctype;
            }else {
                return $total_game;
            }
        }else {
            return $total;
        }
    }
}