<?php
class Activity_Shengdan {
    
    private static $_instance;
    
    public static function factory(){

        if(!is_object(self::$_instance)){
            self::$_instance = new Activity_Shengdan();
        }
        return self::$_instance;
    }
    
    //获取可兑换信息
    public function getCurrenInfo ($gameid, $mid, $times){
        $gameInfo        = Member::factory()->getGameInfo($mid); //用户金币数
        $rtn['money']    = $gameInfo['money'];
        //获取用户的牌局信息
        if($gameid==1){
            $rtn['paijuinfo'] = $paijuinfo = Loader_Redis::common()->hGet("sh_winstreak",$mid);
        }else if($gameid==3){
            $rtn['paijuinfo'] = $paijuinfo = Loader_Redis::rank(3)->hGet("lc_winstreak",$mid);
        }else if($gameid==4){
            $rtn['paijuinfo'] = $paijuinfo = Loader_Redis::rank(4)->hGet("bf_winstreak",$mid);
        }
        if(!$paijuinfo){
            $paijuinfo="y";
        }
        if ($paijuinfo && $paijuinfo!="y"){
            $paijuFrequency  = strlen($paijuinfo);          //用户今日牌局数
        }
        
        $hat = $clo = $boo = $toHat = $toClo = $toBoo = 0;
        
        //不同牌局数下可获得的物品数量
        if ($paijuFrequency>=5 && $paijuFrequency<40){
            if ($paijuFrequency>=5){
                $hat = 1;
                if ($paijuFrequency>=15){
                    $clo = 1;
                    if ($paijuFrequency>=35){
                        $boo = 1;
                    }
                }
            }
        }else if($paijuFrequency>=40 && $paijuFrequency<=175){
            $hat = floor(($paijuFrequency-5)/35)+1;
            $clo = floor(($paijuFrequency-15)/35)+1;
            $boo = floor(($paijuFrequency-35)/35)+1;
        }else if ($paijuFrequency>175){
            $hat = 5;
            $clo = 5;
            $boo = 5;
        }
        
        //不同牌局数下各物品剩余局数及当前任务进度
        $array = array(0=>1,5=>2,15=>3,35=>1,40=>2,50=>3,70=>1,75=>2,85=>3,105=>1,110=>2,120=>3,140=>1,145=>2,155=>3);
        
        foreach ($array as $k=>$v){
            if($paijuFrequency>$k){
                if ($v=='1'){
                    if ($paijuFrequency<35){
                        $toHat = $paijuFrequency;
                    }else {
                        $toHat = Helper::uint($paijuFrequency-$k);
                        $toClo = 0;
                        $toBoo = 0;
                    }
                }else if($v=='2'){
                    $toClo = Helper::uint($paijuFrequency-$k);
                }else if($v=='3'){
                    $toBoo = Helper::uint($paijuFrequency-$k);
                }
            }
            if ($paijuFrequency>=$k){
                $flag = $v;
            }
        }
        $arr = Activity_Shengdan::getExchangeFlag($mid, $times);
        $rtn['have_hat']     = Helper::uint($hat - $arr['hat']);        //可兑换帽子数量
        $rtn['have_clothes'] = Helper::uint($clo - $arr['clothes']);    //可兑换衣服数量
        $rtn['have_boots']   = Helper::uint($boo - $arr['boots']);      //可兑换靴子数量
        $rtn['have_socks']   = Helper::uint(3 - $arr['socks']);         //可兑换袜子数量
        $rtn['to_hat']       = $toHat;                                  //获得帽子剩余局数
        $rtn['to_clo']       = $toClo;                                  //获得衣服剩余局数
        $rtn['to_boots']     = $toBoo;                                  //获得靴子剩余局数
        $rtn['flag']         = $flag;                                   //当前任务进度
    
        return $rtn;
    }
    
    
    //获取已兑换状态
    public function getExchangeFlag ($mid, $times){
        $rtn['hat']     = (int)Loader_Redis::common()->hGet("shengdan|hat|exchangeflag|$times", $mid);
        $rtn['boots']   = (int)Loader_Redis::common()->hGet("shengdan|boots|exchangeflag|$times", $mid);
        $rtn['clothes'] = (int)Loader_Redis::common()->hGet("shengdan|clothes|exchangeflag|$times", $mid);
        $rtn['socks']   = (int)Loader_Redis::common()->hGet("shengdan|socks|exchangeflag|$times", $mid);
        
        return $rtn;
    }
    
    //增加兑换数量
    public function increase ($mid, $gameid, $ctype, $type, $times){
       $curr = Activity_Shengdan::getExchangeFlag($mid, $times);
       $curr[$type] = $curr[$type] + 1;
       $result = Loader_Redis::common()->hSet("shengdan|$type|exchangeflag|$times", $mid, $curr[$type], false, false,2*24*3600);

       Loader_Redis::common()->hSet("shengdan|$times|$gameid|$ctype|$type", $mid, $curr[$type], false, false,5*24*3600);//统计数据
       Loader_Redis::common()->hSet("shengdan-join|$times|$gameid|$ctype", $mid, 1, false, false, 5*24*3600);
       
       return $result;
    }
   
    //兑换袜子
    public function exchangeSocks ($gameid, $mid, $sid, $cid, $pid, $ctype, $times){
        $curr = Activity_Shengdan::getCurrenInfo($gameid, $mid, $times);
        if ($curr['have_socks']>0 && $curr['have_socks']<=3){
            $flag  = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, 6888, $desc='shengdan');   //奖励金币
            $gvip  = Member::factory()->setVip($mid, $gameid, 1);                                                       //奖励会员
            $ghorn = Loader_Redis::account()->hIncrBy(Config_Keys::other($mid), 'horn', 2);                             //奖励小喇叭
            
            if ($flag){
                $hat     = Activity_Shengdan::factory()->increase($mid, $gameid, $ctype, 'hat', $times);
                $clothes = Activity_Shengdan::factory()->increase($mid, $gameid, $ctype, 'clothes', $times);
                $boots   = Activity_Shengdan::factory()->increase($mid, $gameid, $ctype, 'boots', $times);
                $socks   = Activity_Shengdan::factory()->increase($mid, $gameid, $ctype, 'socks', $times);
                $result = 1;
            }else {
                $result = 0;
            }
        }else {
            $result = -1;
        }
        return $result;
    }
    
    //兑换金币
    public function getMoney($gameid, $mid, $sid, $cid, $pid, $ctype, $type, $times){
        $arr = Activity_Shengdan::factory()->getCurrenInfo($gameid, $mid, $times);
        switch ($type){
            
            case 'getHat':
                if ($arr['have_hat']>0 && $arr['have_hat']<=5){
                    $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, 1*688, $desc='shengdan');
                    if ($add){
                        Activity_Shengdan::increase($mid, $gameid, $ctype, 'hat', $times);
                        $rtn = 1;
                    }
                }else {
                    return $rtn = -1;
                }
                break;
                
            case 'getClothes':
                if ($arr['have_clothes']>0 && $arr['have_clothes']<=5){
                    $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, 1*1288, $desc='shengdan');
                    if ($add){
                        Activity_Shengdan::increase($mid, $gameid, $ctype, 'clothes', $times);
                        $rtn = 1;
                    }
                }else {
                    return $rtn = -1;
                }
                break;
                
            case 'getBoots':
                if ($arr['have_boots']>0 && $arr['have_boots']<=5){
                    $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, 1*3888, $desc='shengdan');
                    if ($add){
                        Activity_Shengdan::increase($mid, $gameid, $ctype, 'boots', $times);
                        $rtn = 1;
                    }
                }else {
                    return $rtn = -1;
                }
                break;
        }
        return $rtn;
    }
}