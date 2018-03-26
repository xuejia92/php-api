<?php 

class Activity_Yangnianhongbao {
    
    private static $_instance;
    
    public static function factory (){
        
        if (!is_object(self::$_instance)){
            self::$_instance = new Activity_Yangnianhongbao();
        }
        return self::$_instance;
    }
    
    public function getPlayInfo( $mid, $time){
        
        $result['chongzhi'] = $chongzhijine       = (int)Loader_Redis::common()->get("chongzhi_".$mid."_".$time, false, false);            //获取充值总额
        //$result['lingqu']   = $lingqu = (int)Loader_Redis::common()->hGet("yangnianhongbaoLing|$time", $mid);        //获取已经领取总额
        $result['cishu']    = (int)Loader_Redis::common()->hGet("yangnianhongbaoCishu|$time", $mid);                 //获取已经领取次数
        
        $result['liuyuan']      = (int)Loader_Redis::common()->hGet("yangnianhongbaoliuyuan|$time", $mid);
        $result['shieryuan']    = (int)Loader_Redis::common()->hGet("yangnianhongbaoshieryuan|$time", $mid);
        $result['wushiyuan']    = (int)Loader_Redis::common()->hGet("yangnianhongbaowushiyuan|$time", $mid); 
        
        //$result['shengyu']  = $chongzhijine - $lingqu;
        
        return $result;
    }
    
    public function setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $tapType, $prize_arr, $time){
        $record     = $this->getPlayInfo($mid, $time);
        $chognzhi   = $record['chongzhi'];
        //$lingqu     = $record['lingqu'];
        $cishu      = $record['cishu'];
        //$shengyu    = $record['shengyu'];
        
        $liuyuan    = $record['liuyuan'];
        $shieryuan  = $record['shieryuan'];
        $wushiyuan  = $record['wushiyuan'];
        
        switch ($tapType){
            case 1:
                $ti     = $liuyuan;
                $number = 6;
                $type   = 'liuyuan';
                break;
            case 2:
                $ti     = $shieryuan;
                $number = 12;
                $type   = 'shieryuan';
                break;
            case 3:
                $ti     = $wushiyuan;
                $number = 50;
                $type   = 'wushiyuan';
                break;
        }
        
        $res    = $this->lottery($prize_arr, $tapType);
        
        $prize  = $res['prize'];
        switch ($prize){
            case '三等奖':
                $prizes = 186;
                break;
                
            case '四等奖':
                $prizes = 187;
                break;
        }
        
        $result['money']        = $money = $res['reword'];
        $result['ti']           = $ti;
        $result['liuyuan']      = 0;
        $result['shieryuan']    = 0;
        $result['wushiyuan']    = 0;
        
        $result['chongzhi']     = $chognzhi;
        //$total  = $lingqu + $number;
        //$result['shengyu']  = $shengyu;
        //$result['cishu']    = $cishu;
        
        if ($number <= $chognzhi){
            if ($ti == 0){
                
                $add = Logs::factory()->addWin($gameid, $mid, 15, $sid, $cid, $pid, $ctype, 0, $money, '羊年红包活动');
                
                if ($add){
                    
                    Loader_Redis::common()->hSet("yangnianhongbao$type|$time", $mid, 1, false, false, 15*24*3600);
                    //Loader_Redis::common()->hSet("yangnianhongbaoLing|$time", $mid, $total, false, false, 15*24*3600);              //记录领取总额
                    Loader_Redis::common()->hSet("yangnianhongbaoCishu|$time", $mid, $cishu+1, false, false, 15*24*3600);           //记录领取次数
                    Loader_Redis::common()->incr("yangnianhongbaoLRenci|$time", 1, 15*24*3600);                                     //记录领取人次
                    Loader_Redis::common()->hSet("yangnianhongbaoR".$tapType.$prizes."|$time", $mid, 1, false, false, 15*24*3600);  //记录每个红包发放的人数
                    Loader_Redis::common()->incr("yangnianhongbaoC".$tapType.$prizes."|$time", 1, 15*24*3600);                      //记录每个红包发放人次
                    Loader_Redis::common()->incr("yangnianhongbaoFafang|$time", $money, 15*24*3600);                                //记录发放金额
                    
                    //$result['cishu']    = $cishu + 1; 
                    //$result['shengyu']  = $shengyu - $number;
                    $result['status']   = 1;
                    $result[$type]      = 1;
                }else {
                    $result['status']   = 0;
                }
            }else {
                $result['status']   = -1;
            }
        }else {
            $result['status']   = -2;
        }
        return $result;
    }
    
    //开奖
    public function lottery($prize_arr, $taptype) {
        
        $prize_arry = $prize_arr[$taptype];                         //根据红包种类获取开奖配置
        
        foreach ($prize_arry as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid                = $this->getRand($arr);                //根据概率获取奖项id
        $res                = $prize_arry[$rid-1];                 //中奖项
        $min                = $res['min'];
        $max                = $res['max'];
        $result['prize']    = $res['prize'];
        $result['reword']   = mt_rand($min,$max);                  //随机生成奖金
        
        return $result;
    }
    
    //根据概率随机抽取
    public function getRand($proArr) {
        $result = '';
    
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
    
        return $result;
    }
    
    
}