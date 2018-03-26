<?php !defined('IN WEB') AND exit('Access Denied!');
class Activity_Config{
	
	//public static $testData = "click me";
	
	public static $activity  = array(
	    'weekendTask' =>array(
	        'id' => 1,
			'subject' => '玩牌领大奖',
			'time'    => '2014.4.04-2014.4.19',
			'desc'    => '每日累计玩牌3局/15局/30局/60局/90局均可获得金币奖励',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/playcard.png', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=playCard',
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-04-6 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
	        'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),

		'yaoyaole' =>array(
	        'id' => 2,
			'subject' => '摇摇乐',
			'time'    => '2014.4.04-2014.4.30',
			'desc'    => '玩家连续登陆1天，获得1次抽奖机会。玩家连续登陆3天，获得2次抽奖机会。玩家连续登陆5天及以上，每天均可获得3次抽奖机会。',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/yaoyaole.png', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=yaoyaole',
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-06-30 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
		    'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
		
		'yaoyaole2' =>array(
	        'id' => 3,
			'subject' => '摇不停',
			'time'    => '2014.7.11-2014.12.30',
			'desc'    => '每天登录获得一次免费抽奖机会,之后每抽一次需使用1000金币并获得幸运值,幸运值越高,抽中大奖几率越大!',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/yaobuting.png', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=yaoyaole2',
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-07-30 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>',1,4',
		    'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
		
		
		

		
		'wuxingpingjia' =>array(
	        'id' => 6,
			'subject' => '五星评价赢好礼',
			'time'    => '永久',
			'desc'    => '凡在苹果商店对《火拼斗牛》给予5星好评，并留下美好寄语，即可获得10000金币奖励。快去评论吧！',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/wuxingpingjia.png?1=1', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=wuxingpingjia',
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-10-30 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>',1,3',
		    'new'             =>'',
			'buttonName'      =>'去评价',
	    ),
		
		
		'wuxingpingjiaSH' =>array(
	        'id' => 10,
			'subject' => '五星评价赢好礼',
			'time'    => '永久',
			'desc'    => '凡在苹果商店对《点乐梭哈》给予5星好评，并留下美好寄语，即可获得10000金币奖励。快去评论吧！',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/wuxingpingjia.png?1=1', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=wuxingpingjia',
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-10-30 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>',3,4',
		    'new'             =>'',
			'buttonName'      =>'去评价',
	    ),
		
		'wuxingpingjiaDDZ' =>array(
	        'id' => 11,
			'subject' => '五星评价赢好礼',
			'time'    => '永久',
			'desc'    => '凡在苹果商店对《点乐斗地主》给予5星好评，并留下美好寄语，即可获得10000金币奖励。快去评论吧！',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/wuxingpingjia.png?1=1', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=wuxingpingjia',
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-10-30 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>',1,4',
		    'new'             =>'',
			'buttonName'      =>'去评价',
	    ),
		
		'zhongqiudati' =>array(
	        'id' => 7,
			'subject' => '月圆中秋，灯谜相聚',
			'time'    => '2014.9.4-2014.9.10',
			'desc'    => '中秋佳节，灯谜相聚。"免费金币"，快乐赢取！',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/zhongqiudati.png?1=1', // 大图			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=zhongqiudati',
			'open'			  =>1,
			'start_time'      =>'2014-09-04 00:00:00',
			'end_time'        =>'2014-09-10 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
		    'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
		
		
		
		'zajindan' =>array(
	        'id' => 8,
			'subject' => '天天砸金蛋',
			'time'    => '2014.9.15-2014.9.30',
			'desc'    => '砸金蛋，金花四溅，豪礼根本停不下来。',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/zajindan.png?1=1', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=zajindan',
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-09-30 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
		    'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
				
		'versionup' =>array(
	        'id' => 12,
			'subject' => '更新大赠送',
			'time'    => '2014.9.23-2014.10.30',
			'desc'    => '更新版本大赠送啦！新版畅玩，更多赠送，更多乐趣！！',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/versionup.png?2=3', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=versionup',
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-07-30 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
		    'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
		
		'guoqing' =>array(
	        'id' => 13,
			'subject' => '国庆七天乐',
			'time'    => '2014.10.01-2014.10.07',
			'desc'    => '欢乐国庆欢乐颂！天天登录领宝箱！7天领取高达价值20W金币豪奖！',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/guoqing.png?2=2', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=guoqing',
			'open'			  =>1,
			'start_time'      =>'2014-10-01 00:00:00',
			'end_time'        =>'2014-10-07 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
		    'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
		
		'changshengjiangjun' =>array(
	        'id' => 5,
			'subject' => '常胜将军',
			'time'    => '2015.01.01-2015.02.08',
			'desc'    => '轻轻松松把金拿！',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/changshengjiangjun.png?1=2', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=changshengjiangjun',
			'open'			  =>1,
			'start_time'      =>'2014-10-21 00:00:00',
			'end_time'        =>'2014-12-31 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>',3',
		    'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
		
		'zhiqushengju' =>array(
	        'id' => 4,
			'subject' => '智取胜局 勇夺宝箱',
			'time'    => '2014.11.29-2014.12.31',
			'desc'    => '每天智取三项任务,即可获得豪华大宝箱,各种大奖等你拿!',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/zhiqushengju.png?1=1', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=zhiqushengju',
			'open'			  =>1,
			'start_time'      =>'2014-10-21 00:00:00',
			'end_time'        =>'2014-12-31 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>',1,4',
		    'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
		
	    'test_wuxingpingjia' =>array(
	        'id' => 1,
	        'subject' => '五星评价测试',
	        'time'    => '2014.4.04-2014.4.19',
	        'desc'    => '每日累计玩牌3局/15局/30局/60局/90局均可获得金币奖励',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/wuxingpingjia.png', // 大图
	        
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=test_wuxingpingjia',
	        'open'			  =>1,
	        'start_time'      =>'2014-03-11 00:00:00',
	        'end_time'        =>'2014-04-6 23:59:59',
	        'closeCid'		  =>array(),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'0',
	        'new'             =>'',
	        'buttonName'      =>'点击领奖',
	    ),
	    
	    'quanminpaijiang' =>array(
	        'id' => 26,
	        'subject' => '全民派奖',
	        'time' => '2015.3.26-2015.4.3',
	        'desc'    => '',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/quanminpaijiang.png', // 大图
	         
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=quanminpaijiang',
	        'open'			  =>1,
	        'start_time'      =>'2015-03-26 00:00:00',
	        'end_time'        =>'2014-04-03 23:59:59',
	        'closeCid'		  =>array(),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'',
	        'new'             =>'1',
	        'buttonName'      =>'立即参与',
	    ),
	    
		'yunitongle' =>array(
	        'id' => 25,
	        'subject' => '愚你同乐,礼惠全国',
	        'time' => '2015.3.26-2015.4.3',
	        'desc'    => '愚人节豪礼来袭',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/yunitongle.png', // 大图
	    
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=yunitongle',
	        'open'			  =>1,
	        'start_time'      =>'2015-03-26 00:00:00',
	        'end_time'        =>'2015-04-03 23:59:59',
	        'closeCid'		  =>array(),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'',
	        'new'             =>'1',
	        'buttonName'      =>'立即参与',
	    ),
		
		'xingyunlaba' =>array(
	        'id' => 24,
	        'subject' => '幸运拉霸，财运拉回家',
	        'time' => '2015.4.7-2015.4.13',
	        'desc'    => '拉霸在手，财运我有',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/xingyunlaba.png', // 大图
	    
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=xingyunlaba',
	        'open'			  =>1,
	        'start_time'      =>'2015-04-07 00:00:00',
	        'end_time'        =>'2015-04-13 23:59:59',
	        'closeCid'		  =>array(),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'',
	        'new'             =>'1',
	        'buttonName'      =>'立即参与',
	    ),
		
		'yangnianhongbao' =>array(
	        'id' => 23,
	        'subject' => '羊年红包至,尽享新年乐',
	        'time' => '2015.2.19-2015.2.26',
	        'desc'    => '新年发红包啦！',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/yangnianhongbao.png', // 大图
	    
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=yangnianhongbao',
	        'open'			  =>1,
	        'start_time'      =>'2015-02-19 00:00:00',
	        'end_time'        =>'2015-02-26 23:59:59',
	        'closeCid'		  =>array(),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'',
	        'new'             =>'',
	        'buttonName'      =>'立即参与',
	    ),
		
		'yaoyaole3' =>array(
	        'id' => 21,
			'subject' => '羊年摇摇乐,财运摇回家',
			'time'    => '2015.2.18-2015.2.28',
			'desc'    => '过新年，财神送财来',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/yaoyaole3.png?v=2', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=yaoyaole3',
			'open'			  =>1,
			'start_time'      =>'2015-02-18 00:00:00',
			'end_time'        =>'2015-02-28 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
		    'new'             =>'',
			'buttonName'      =>'立即参与',
	    ),
		
	    'yangniansongfu' =>array(
	        'id' => 16,
	        'subject' => '吉羊送福来，洋气过新年',
	        'time' => '2015.2.12-2015.2.22',
	        'desc'    => '新的一年，就要羊（洋）气的过！',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/yangniansongfu.png', // 大图
	    
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=yangniansongfu',
	        'open'			  =>1,
	        'start_time'      =>'2015-02-12 00:00:00',
	        'end_time'        =>'2015-02-22 23:59:59',
	        'closeCid'		  =>array(),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'',
	        'new'             =>'',
	        'buttonName'      =>'立即参与',
	    ),
	    
	    'xingyunchongzhi' =>array(
	        'id' => 17,
	        'subject' => '幸运充值，金喜不断',
	        'time' => '2015.2.14-2015.2.21',
	        'desc'    => '今天运气如何，进来就知道啦！',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/xingyunchongzhi.png', // 大图
	         
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=xingyunchongzhi',
	        'open'			  =>1,
	        'start_time'      =>'2015-02-14 00:00:00',
	        'end_time'        =>'2015-02-21 23:59:59',
	        'closeCid'		  =>array('7','17','15','52','65','91'),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'',
	        'new'             =>'',
	        'buttonName'      =>'立即参与',
	    ),
	    
	    'lebaocaicai' =>array(
	        'id' => 18,
	        'subject' => '乐宝猜猜猜',
	        'time' => '2014.11.20-2015.2.1',
	        'desc'    => '压1万，中5万！乐宝天天送您金~',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/lebaocaicai.png', // 大图
	        
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=lebaocaicai',
	        'open'			  =>1,
	        'start_time'      =>'2014-11-11 00:00:00',
	        'end_time'        =>'2015-02-01 23:59:59',
			'closeCid'		  =>array(),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'0',
	        'new'             =>'',
	        'buttonName'      =>'立即押宝',
	    ),
		
		'shengdan' =>array(
	        'id' => 19,
	        'subject' => '圣诞嘉年华',
	        'time' => '2014.12.20-2012.12.29',
	        'desc'    => '玩游戏！领取圣诞礼品袜！',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/christ.png', // 大图
	        
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=shengdan',
	        'open'			  =>1,
	        'start_time'      =>'2014-12-20 00:00:00',
	        'end_time'        =>'2014-12-29 23:59:59',
			'closeCid'		  =>array(),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'0',
		    'new'             =>'',
	        'buttonName'      =>'立即参与',
	    ),
	    
	    'jiaochatuiguang' =>array(
	        'id' => 20,
	        'subject' => '玩游戏,金币免费送!',
	        'time' => '2014.12.28-2015.2.1',
	        'desc'    => '通过“更多游戏”畅玩10局即获免费金币!',
	        'content' => 'content',
	        'image'   =>'http://user.dianler.com/web/activity/image/jiaochatuiguang.png', // 大图
	         
	        'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=jiaochatuiguang',
	        'open'			  =>1,
	        'start_time'      =>'2014-12-28 00:00:00',
	        'end_time'        =>'2015-02-01 23:59:59',
			'closeCid'		  =>array('52'),
	        'closeVersion'    =>'',
	        'closeGameid'     =>'0',
	        'new'             =>'',
	        'buttonName'      =>'立即参与',
	    ),
	    
	    'chongzhifanli' =>array(
	        'id' => 14,
			'subject' => '充值返利',
			'time'    => '2014.10.13-2014.10.20',
			'desc'    => '为感谢所有玩家对充值活动的支持，现充值返利加倍，小伙伴快来领取吧！',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/chongzhifanli.png?2=3', // 大图
			
			'url'=>'http://user.dianler.com/index.php?m=activity&p=dispath&fpath=chongzhifanli',
			'open'			  =>1,
			'start_time'      =>'2014-10-13 00:00:00',
			'end_time'        =>'2014-10-20 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
	        'new'             =>'',
			'buttonName'      =>'点击领奖',
	    ),
	    
	    'wangyezhifu' =>array(
	        'id' => 9,
			'subject' => '充值中心（支持支付宝、银行卡、信用卡）',
			'time'    => '永久有效',
			'desc'    => '通过活动充值中心充值，金币赠送20%以上。',
			'content' => 'content',
			'image'   =>'http://user.dianler.com/web/activity/image/wangyezhifu.png?1=2', // 大图
			
			'url'=>'http://www.dianlergame.com/paycenter/index.php?m=pay&p=index&act=list',//http://www.dianlergame.com/paycenter/index.php?m=pay&p=index http://42.62.52.54/pycenter/mpay/?appid=101&uid=10004&gid=12
			'open'			  =>1,
			'start_time'      =>'2014-03-11 00:00:00',
			'end_time'        =>'2014-08-01 23:59:59',
			'closeCid'		  =>array(),
			'closeVersion'    =>'',
			'closeGameid'     =>'0',
	        'new'             =>'',
			'buttonName'      =>'马上充值',
	    ),
	    
	);

}