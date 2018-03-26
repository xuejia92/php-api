<?php !defined('IN WEB') AND exit('Access Denied!');
class Config_Game{

	public static $clientTyle = array(
									 '1'=>'android',
									 '2'=>'IOS',
									 //'3'=>'ipad',
									 //'4'=>'web',
	                                  );
	                                  
	public static $game = array(
									 '1'=>'五张',
									 //'2'=>'百家乐',
									 '3'=>'斗地主',
									 '4'=>'斗牛',
									 '5'=>'捕鱼',
									 '6'=>'德州扑克', 
									 '7'=>'炸金花',
	                                  );                                  
		
	
	public static $message = array(
									'url'    =>'http://192.168.1.199/msg/HttpSendSM',
									'account'=>'szdlyzm',
									'pswd'   =>'Szdlyzm123',
									'product'=>'1500640',
								  );  

    public static $specialAccount = array(4040798,4179248,4039929,4436684,4436707,4436725,4436743,4413006,4412265,714567,4508537,4531782,4531822);//sitemid=>mid
    
    //public static $make2special   = array('4192'=>6666,'4195'=>7777,'4196'=>8888,'4193'=>9999,'66'=>1149721);
    public static $make2special   = array('4192'=>11888,'4195'=>11666,'4196'=>22888,'4193'=>22666);
    
    public static $accountDisconnector = 1;//账号隔离开关 比如在苹果注册的梭哈账号不能在斗地主用
    
    public static $disconnectorPid     =  array(1429=>3,1430=>3,1570=>6,1616=>6,1621=>1,1622=>1,1606=>7);//账号隔离的pid=>gameid  某pid只能在gameid里登陆  '1239'=>3,'1240'=>3,'1241'=>3,
    
    public static $platformAccount  = array(10,15,52,55,59,65,17,88,86,91);//第三方渠道账号登陆的渠道ID ，此类ID的统计项如激活，新增用户直接上报，不依赖机器码
        
    public static function getLevel($score){
    	
    	$level2exp = array ( 0 => 49, 1 => 98, 2 => 148, 3 => 205, 4 => 281, 5 => 394, 6 => 568, 7 => 833, 8 => 1225, 9 => 1786, 10 => 2564, 11 => 3613, 12 => 4993, 13 => 6770, 14 => 9016, 15 => 11809, 16 => 15233, 17 => 19378, 18 => 24340, 19 => 30221, 20 => 37129, 21 => 45178, 22 => 54488, 23 => 65185, 24 => 77401, 25 => 91274, 26 => 106948, 27 => 124573, 28 => 144305, 29 => 166306, 30 => 190744, 31 => 217793, 32 => 247633, 33 => 280450, 34 => 316436, 35 => 355789, 36 => 398713, 37 => 445418, 38 => 496120, 39 => 551041, 40 => 610409, 41 => 674458, 42 => 743428, 43 => 817565, 44 => 897121, 45 => 982354, 46 => 1073528, 47 => 1170913, 48 => 1274785, 49 => 1385426, 50 => 1503124, 51 => 1628173, 52 => 1760873, 53 => 1901530, 54 => 2050456, 55 => 2207969, 56 => 2374393, 57 => 2550058, 58 => 2735300, 59 => 2930461, 60 => 3135889, 61 => 3351938, 62 => 3578968, 63 => 3817345, 64 => 4067441, 65 => 4329634, 66 => 4604308, 67 => 4891853, 68 => 5192665, 69 => 5507146, 70 => 5835704, 71 => 6178753, 72 => 6536713, 73 => 6910010, 74 => 7299076, 75 => 7704349, 76 => 8126273, 77 => 8565298, 78 => 9021880, 79 => 9496481, 80 => 9989569, 81 => 10501618, 82 => 11033108, 83 => 11584525, 84 => 12156361, 85 => 12749114, 86 => 13363288, 87 => 13999393, 88 => 14657945, 89 => 15339466, 90 => 16044484, 91 => 16773533, 92 => 17527153, 93 => 18305890, 94 => 19110296, 95 => 19940929, 96 => 20798353, 97 => 21683138, 98 => 22595860, 99 => 23537101, 100 => 24507449, 101 => 25507498, 102 => 26537848, 103 => 27599105, 104 => 28691881, 105 => 29816794, 106 => 30974468, 107 => 32165533, 108 => 33390625, 109 => 34650386, 110 => 35945464, 111 => 37276513, 112 => 38644193, 113 => 40049170, 114 => 41492116, 115 => 42973709, 116 => 44494633, 117 => 46055578, 118 => 47657240, 119 => 49300321, 120 => 50985529, 121 => 52713578, 122 => 54485188, 123 => 56301085, 124 => 58162001, 125 => 60068674, 126 => 62021848, 127 => 64022273);
    	
	    foreach ($level2exp as $level=>$exp) {
	    	if($score<=$exp){
	    		return $level;
	    	}
	    }
    }
    
    public static $loginVipConfig = array('gname'=>'会员(7天)','money'=>10000,'rate'=>1.5);//登陆弹出框会员配置 1, 1.5, 2, 2.5, 3 默认只能用这几个数值

    /**
    *力美配置
	*url		  : 请求地址
	*method		  : 请求方式 (get|post)
	*returnFormat : 返回信息格式 (1-JSON|2-XML)
	*/	
	public static $liMeiUrl = array(
										'url'    		 =>'http://api2.immob.cn/capCallbackApi/1/',
										'appid'			 =>'860402884',
										'method'		 =>'get',
										'returnFormat'   =>1,
									);
    
    public static $paymids = array(8024,85389,30495,60208,4958,67436,43102,46626,78278,8282,34178,6082,444024);//付款超过2K的用户 
    
    public static $probability = array(//大转盘概率 配置
    									'1'=>array(0.12,2     ,2000 ,0  ,105), //乐卷2个
    									'2'=>array(0.27,1000   ,0    ,0  ,106), //金币1000  概率  奖励数量   日总库存  0代表不限  时间限制  统计项ID
    									'3'=>array(0,1     ,1500 ,0  ,112), //小喇叭1个
    									'4'=>array(0.15,2000  ,0    ,0  ,108), //金币2000
    									'5'=>array(0.04,1     ,50   ,144,110), //vip1天 概率  奖励天数   日总库存  0代表不限 144 时间间隔
    									'6'=>array(0.04,100000,10   ,144,109), //金币100000 144 时间间隔
    									'7'=>array(0.12,5     ,600 ,0  ,104), //乐卷5个
									    '8'=>array(0.26,1500   ,0    ,0  ,107), //金币1500
									    '9'=>array(0   ,7     ,10   ,1  ,111), //vip7天
    								);
    								
    public static $probability_client = array( 
    									array(1,2),//2乐卷
    									array(2,1000),//500金币
    									array(3,1),//小喇叭
    									array(4,2000),//金币1000
    									array(5,1),//vip 1天
    									array(6,100000),//金币100000
    									array(7,5),//乐卷5个
    									array(8,1500),//金币1500
    									array(9,7),//vip 7天
    								);	
						
    public static $bonus_probability =  array(
                                        '1' => array('id'=>1,'prize'=>1000,'chance'=>4,'countid'=>227),
                                        '2' => array('id'=>2,'prize'=>1500,'chance'=>4,'countid'=>228),
                                        '3' => array('id'=>3,'prize'=>10000,'chance'=>10,'countid'=>237),
                                        '4' => array('id'=>4,'prize'=>1800,'chance'=>4,'countid'=>229),
                                        '5' => array('id'=>5,'prize'=>2000,'chance'=>10,'countid'=>230),
                                        '6' => array('id'=>6,'prize'=>50000,'chance'=>2,'countid'=>238),
                                        '7' => array('id'=>7,'prize'=>2500,'chance'=>10,'countid'=>231),
                                        '8' => array('id'=>8,'prize'=>2800,'chance'=>10,'countid'=>232),
                                        '9' => array('id'=>9,'prize'=>88888,'chance'=>2,'countid'=>239),
                                        '10' => array('id'=>10,'prize'=>3000,'chance'=>11,'countid'=>233),
                                        '11' => array('id'=>11,'prize'=>3500,'chance'=>11,'countid'=>234),
                                        '12' => array('id'=>12,'prize'=>888888,'chance'=>1,'countid'=>240),
                                        '13' => array('id'=>13,'prize'=>3800,'chance'=>10,'countid'=>235),
                                        '14' => array('id'=>14,'prize'=>4000,'chance'=>10,'countid'=>236),
                                        '15' => array('id'=>15,'prize'=>1000000,'chance'=>1,'countid'=>241)
                                    );
    
    public static $continuousLoginRate  = array(
                                            '1' => 11,
                                            '2' => 12,
                                            '3' => 15,
                                            '4' => 18,
                                            '5' => 20
                                        );

    public static $wallet = array(
    							'min_wallet'=>100,//最小红包金币数
    							'system_give_max_wallet'=>50000,//系统发送红包的最大金币数
    							'system_give_max_num'=>500,//系统发红包最大人数限制
    							'user_max_wallet'=>'10000000',//玩家发红包的最大数目
    							'user_min_wallet'=>10000//玩家发红包的最小数目
    							 );	
	
    //注册黑名单  机器码							 
    public static $register_blacklist = array();		

    public static $update_blacklist = array(
    										'91583',
    										'955642',
    										'926967',
    										'955573',
    										'1216823',
    										'1414421'
    									);

    public static $register_devicename_blacklist = array();		
    										
	public static $firstpayLimitDay = "2016-06-01";	

	public static $channelVertype = array('1' => '主线', '2' => '联运', '3' => '大渠道');
	
	public static $promotion2 = array(
        array(
            'gameid'=>1,
            'imgurl'=>array(
                0 => array(
                    '1'=>'http://user.dianler.com/statics/promotion2/1.png',
                    '2'=>'http://user.dianler.com/statics/promotion2/1_n.png'
                ),
                1 => 'http://user.dianler.com/statics/promotion2/1HD.png',
            ),
            'durl'=>array('1'=>'http://statics.dianler.com/apk/showhand-guanwang.apk','2'=>'https://itunes.apple.com/us/app/dian-le-suo-ha/id788485362?ls=1&mt=8'),
            'pack'=>array('1'=>'com.gangswz.showhand','2'=>'com.dianler.suoha://gangswzapp'),
            'desc'=>'同账号玩点乐梭哈最高奖励10万金币，等着你来玩！',
            'recommend'=>2,//0=>无，1=>新品，2=>推荐
        ),
        
        array(
            'gameid'=>3,
            'imgurl'=>array(
                0 => 'http://user.dianler.com/statics/promotion2/3.png',
                1 => 'http://user.dianler.com/statics/promotion2/3HD.png'
            ),
            'durl'=>array('1'=>'http://statics.dianler.com/apk/LandCard-guanwang.apk','2'=>'https://itunes.apple.com/cn/app/dian-le-dou-de-zhu/id882414740?mt=8'),
            'pack'=>array('1'=>'com.gangswz.LandCard','2'=>'com.gangswz.LandCard://gangswzapp'),
            'desc'=>'同账号玩点乐斗地主最高奖励10万金币，超级好赚！',
            'recommend'=>0,
        ),
        
        array(
            'gameid'=>4,
            'imgurl'=>array(
                0 => 'http://user.dianler.com/statics/promotion2/4.png',
                1 => 'http://user.dianler.com/statics/promotion2/4HD.png'
            ),
            'durl'=>array('1'=>'http://statics.dianler.com/apk/bullfight-guanwang.apk','2'=>'https://itunes.apple.com/cn/app/huo-pin-dou-niu/id879994259?mt=8'),
            'pack'=>array('1'=>'com.gangswz.bullfight','2'=>'com.gangswz.bullfight://gangswzapp'),
            'desc'=>'同账号玩火拼斗牛最高奖励10万金币，等着你来玩！',
            'recommend'=>0,
        ),
        
        array(
            'gameid'=>6,
            'imgurl'=>array(
                0 => 'http://user.dianler.com/statics/promotion2/6.png',
                1 => 'http://user.dianler.com/statics/promotion2/6HD.png'
            ),
            'durl'=>array('1'=>'http://statics.dianler.com/apk/teaxs-guanwang.apk','2'=>'https://itunes.apple.com/cn/app/quan-min-zhou-pu-ke-tian-tian/id988296409?mt=8'),
            'pack'=>array('1'=>'com.gangswz.holdem','2'=>'wx92eee61f8b4e1d26://weixin'),
            'desc'=>'点乐德州震撼上线，同账号玩遍所有游戏，下载最高送10万金币！',
            'recommend'=>1,
        ),
        
        array(
            'gameid'=>7,
            'imgurl'=>array(),
            'durl'=>array(),
            'pack'=>array(),
            'desc'=>'火拼炸金花震撼上线，同账号玩遍所有游戏，下载最高送10万金币！',
            'recommend'=>1,
            // 'gameid'=>7,
            // 'imgurl'=>array(
            //     0 => 'http://user.dd.com/statics/promotion2/7.png',
            //     1 => 'http://user.dd.com/statics/promotion2/7.png'
            // ),
            // 'durl'=>array('1'=>'http://user.dd.com/apk/Goldenfraud-guanwang.apk','2'=>'https://itunes.apple.com/cn/app/huo-pin-zha-jin-hua-ao-men/id1008041119?mt=8'),
            // 'pack'=>array('1'=>'com.gangswz.goldenfraud','2'=>'com.dianlergame.zhajinhua://gangswzapp'),
            // 'desc'=>'火拼炸金花震撼上线，同账号玩遍所有游戏，下载最高送10万金币！',
            // 'recommend'=>1,
        )
	);
    							 
}
