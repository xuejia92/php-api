<?php !defined('IN WEB') AND exit('Access Denied!');

class Action_Config{

    public static $action = array(
        '0' => array(
            'subject'   => '版本更新',
            'class'     => 'Action_Versionup',
            'name'      => 'versionup'
        ),
        '1' => array(
            'subject'   => '五星好评',
            'class'     => 'Action_Wuxingpingjia',
            'name'      => 'wuxingpingjia'
        ),
        '2' => array(
            'subject'   => '乐宝猜猜',
            'class'     => 'Action_Lebao',
            'name'      => 'lebaocaicai'
        ),
        '3' => array(
            'subject'   => '圣诞活动',
            'class'     => 'Action_Shengdan',
            'name'      => 'shengdan'
        ),
        '4' => array(
            'subject'   => '推广活动',
            'class'     => 'Action_Jiaochatuiguang',
            'name'      => 'jiaochatuiguang'
        ),
        '5' => array(
            'subject'   => '幸运充值',
            'class'     => 'Action_Xingyunchongzhi',
            'name'      => 'xingyunchongzhi'
        ),
        '6' => array(
            'subject'   => '羊年送福',
            'class'     => 'Action_Yangniansongfu',
            'name'      => 'yangniansongfu'
        ),
        '7' => array(
            'subject'   => '羊年红包',
            'class'     => 'Action_Yangnianhongbao',
            'name'      => 'yangnianhongbao'
        ),
        '8' => array(
            'subject'   => '摇摇乐3',
            'class'     => 'Action_Yaoyaole3',
            'name'      => 'yaoyaole3'
        ),
        '9' => array(
            'subject'   => '幸运拉霸',
            'class'     => 'Action_Xingyunlaba',
            'name'      => 'xingyunlaba'
        ),
        '10' => array(
            'subject'   => '全民派奖',
            'class'     => 'Action_Quanminpaijiang',
            'name'      => 'quanminpaijiang'
        ),
        '11' => array(
            'subject'   => '愚你同乐',
            'class'     => 'Action_Yunitongle',
            'name'      => 'yunitongle'
        ),
        '12' => array(
            'subject'   => '微信激活码',
            'class'     => 'Action_Weixinactivecode',
            'name'      => 'weixinactivecode'
        ),
        '13' => array(
            'subject'   => '端午活动',
            'class'     => 'Action_Duanwu',
            'name'      => 'duanwu'
        ),
        '14' => array(
            'subject'   => '捕鱼活动',
            'class'     => 'Action_Buyu',
            'name'      => 'buyu'
        ),
        '15' => array(
            'subject'   => '兑换活动',
            'class'     => 'Action_CardEmploy',
            'name'      => 'cardemploy'
        ),
    );
}