<?php !defined('IN WEB') AND exit('Access Denied!');

/**
 * 
 * 语言文件
 * 1 简体中文
 * 2 繁体中文
 * 3 英文
 * 4 越语
 * 
 * */

class Config_Lang
{
	
	public static function getLang($lang){
		$idc  = Config_Inc::$idc;
		$lang = 1;
		if($idc != 1 ){
			$lang = $lang ? $lang : 2;
		}
		return $lang;
	}
	
	public static $roll = array(
									1=>'有效期至:',
									2=>'有效期至:',
									3=>'Valid until:',
									4=>'thời hạn đến'
								);
	public static $activityDesc = array(
									1=>'暂无活动',
									2=>'暫無活動',
									3=>'No activity',
									4=>'Không có sự kiện'
								);
 
	public static $fastPay = array(
                                    'fastPayConfig' => array(
                                        1 => array(1=>'60000金币',2=>'60000金币',3=>'200000金币',4=>'200000金币'),
                                        2 => array(1=>'60000金幣',2=>'60000金幣',3=>'200000金幣',4=>'200000金幣'),
                                        3 => array(1=>'60000 gold coins',2=>'60000 gold coins',3=>'200000 gold coins',4=>'200000 gold coins'),
                                        4 => array(1=>'60000 Gold',2=>'60000 Gold',3=>'200000 Gold',4=>'200000 Gold')
                                    ),
                                    'firstpaygood' => array(
                                        1 => '60000金币',
                                        2 => '60000金幣',
                                        3 => '60000 gold coins',
                                        4 => '60000 Gold',
                                    ),
                                    'bankruptpay' => array(
                                        1 => array('gname'=>'200000金币','gold'=>200000),
                                        2 => array('gname'=>'200000金幣','gold'=>200000),
                                        3 => array('gname'=>'200000 gold coins','gold'=>200000),
                                        4 => array('gname'=>'200000 Gold','gold'=>200000),
                                    ),
                                    'flashsalegood' => array(
                                        1 => array('200000金币',150),
                                        2 => array('200000金幣',150),
                                        3 => array('200000 gold coins',150),
                                        4 => array('200000 Gold',150),
                                    ),
                                );
	
}