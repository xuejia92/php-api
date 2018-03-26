<?php !defined('IN WEB') AND exit('Access Denied!');

class Action_Configuration{

    public static $action = array(
        '1' => array(
            'subject'   => '更新活动',
            'class'     => 'Action_ConfigurationVersionup',
            'name'      => 'configuration_versionup',
            'kname'     => 'activity_versionup_config'
        ),
        '2' => array(
            'subject'   => '五星评价',
            'class'     => 'Action_ConfigurationWuxingpingjia',
            'name'      => 'configuration_wuxingpingjia',
            'kname'     => 'activity_wuxingpingjia_config'
        ),
        /*'3' => array(
            'subject'   => '网页支付',
            'class'     => 'Action_ConfigurationWangyezhifu',
            'name'      => 'configuration_wangyezhifu',
            'kname'     => 'wangyezhifu_config'
        ),
        '4' => array(
            'subject'   => '更多游戏,cardopen',
            'class'     => 'Action_ConfigurationLianyun',
            'name'      => 'configuration_lianyun',
            'kname'     => 'lianyun_config'
        ),
        '5' => array(
            'subject'   => '娱乐场控制',
            'class'     => 'Action_ConfigurationYule',
            'name'      => 'configuration_yule',
            'kname'     => 'yule_config'
        ),
        '6' => array(
            'subject'   => 'ios审核控制',
            'class'     => 'Action_ConfigurationAllswitch',
            'name'      => 'configuration_allswitch',
            'kname'     => 'allswitch_config'
        ),
        '7' => array(
            'subject'   => '首充显示开关',
            'class'     => 'Action_ConfigurationFirstpaylimit',
            'name'      => 'configuration_firstpaylimit',
            'kname'     => 'firstpaylimit_config'
        ),
        '8' => array(
            'subject'   => '破产充值开关',
            'class'     => 'Action_ConfigurationBankruptpay',
            'name'      => 'configuration_bankruptpay',
            'kname'     => 'bankruptpay_config'
        ),*/
    );
}