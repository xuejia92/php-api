<?php

class Action_item {
    private static $_instances;
    
    public static function factory(){
        if(!self::$_instances){
            self::$_instances = new Action_item();
        }
    
        return self::$_instances;
    }
    
    public function versionup($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'参与人数','2'=>'领取人数','3'=>'发放金币数');
    
        return $rtn;
    }
    
    public function wuxingpingjia($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'参与人数','2'=>'领取人数','3'=>'发放金币数');
        
        return $rtn;
    }
    
    public function lebaocaicai($data){
        $rtn['cats'] = array('1'=>'购买次数','2'=>'购买人数','3'=>'购买排行');
        if ($data['catid'] == 3){
            $rtn['item'] = array('时间','参与人数',1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
        }else {
            $rtn['item'] = array('0'=>'时间','1'=>'购买狗','2'=>'购买鸟','3'=>'购买狐狸','4'=>'购买长颈鹿','5'=>'购买熊猫','6'=>'购买羊');
        }
        
        return $rtn;
    }
    
    public function shengdan($data){
        $rtn['cats'] = array('1'=>'兑换次数','2'=>'兑换人数');
        $rtn['item'] = array('0'=>'时间','1'=>'参与人数','2'=>'兑换帽子','3'=>'兑换衣服','4'=>'兑换靴子','5'=>'兑换袜子');
        
        return $rtn;
    }
    
    public function jiaochatuiguang($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'参与人数','2'=>'五张','3'=>'斗地主','4'=>'斗牛','5'=>'已领一次','6'=>'已领两次','7'=>'已领三次');
        
        return $rtn;
    }
    
    public function xingyunchongzhi($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'消耗金币数','2'=>'发放金币数','3'=>'点击人数','4'=>'参与人数','5'=>'成功充值人次','6'=>'成功充值金额(元)','7'=>'1.1倍','8'=>'1.2倍','9'=>'1.3倍','10'=>'1.4倍','11'=>'2倍','12'=>'3倍');
    
        return $rtn;
    }
    
    public function yangniansongfu($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'活动点击人数','2'=>'活动参与人数','3'=>'发放金币数','4'=>'奖888','5'=>'奖8888','6'=>'奖100','7'=>'奖6');
    
        return $rtn;
    }
    
    public function yangnianhongbao($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'活动点击人数','2'=>'活动参与人数','3'=>'发放金币数','4'=>'6元红包','5'=>'12元红包','6'=>'30元红包');
    
        return $rtn;
    }
    
    public function yaoyaole3($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'活动点击人数','2'=>'活动参与人数','3'=>'发放金币数','4'=>'金币消耗数','5'=>'压中豹子人数');
    
        return $rtn;
    }
    
    public function xingyunlaba($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'活动点击人数','2'=>'活动参与人数','3'=>'发放金币数','4'=>'金币消耗数');
    
        return $rtn;
    }
    
    public function quanminpaijiang($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'活动点击人数','2'=>'活动参与人数','3'=>'发放金币数','4'=>'青铜宝盒人数','5'=>'白银宝盒人数','6'=>'黄金宝盒人数');
    
        return $rtn;
    }
    
    public function yunitongle($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'活动点击人数','2'=>'活动参与人数','3'=>'发放金币数','4'=>'iPhone被砸人数','5'=>'iPad被砸人数','6'=>'拍立得被砸人数','7'=>'金币被砸人数');
    
        return $rtn;
    }
    
    public function weixinactivecode($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'发放数量');
    
        return $rtn;
    }
    
    public function duanwu($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'1号粽子','2'=>'2号粽子','3'=>'3号粽子','4'=>'4号粽子','5'=>'5号粽子','6'=>'发放金币数','7'=>'1话费券发放数','8'=>'3话费券发放数');
    
        return $rtn;
    }
    
    public function buyu($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'活动点击人数','2'=>'活动参与人数','3'=>'1号鱼','4'=>'2号鱼','5'=>'3号鱼','6'=>'发放金币数');
    
        return $rtn;
    }
    
    public function cardEmploy($data){
        $rtn['cats'] = array('1'=>'详情');
        $rtn['item'] = array('0'=>'时间','1'=>'活动点击人数','2'=>'活动参与人数');
    
        return $rtn;
    }
}