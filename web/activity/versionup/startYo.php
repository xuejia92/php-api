<?php 
!defined('IN WEB') AND exit('Access Denied!');

$time  = time();
$today = date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);

$ret = array();
$ret['flag']   = 0;  	//返回值
$ret['messid'] = 0;		//错误号
$ret['money']  = 0;	    //领取金额


//时间控制 超过这个时间不在进行处理
/*
if($time< strtotime("2014-3-11") || $time > strtotime("2014-12-30")){
	echo json_encode($ret);
	exit;
}
*/
$names = array(
	"SCH-I959",
	"HUAWEI C8813Q",
	"HUAWEI C8813",
	"SCH-I739",
	"HUAWEI C8812",
	"HUAWEI C8815",
	"SCH-I869",
	"GT-I9300",
	"SM-N9009",
	"GT-I9100",
	"GT-N7100",
	"HUAWEI A199",
	"GT-I9500",
	"HUAWEI C8812E",
	"HUAWEI C8813D",
	"SCH-I619",
	"SCH-I829",
	"SCH-I879",
	"HTC T328d",
	"SCH-I779",
	"C8650",
	"HS-E820",
	"SCH-W999",
	"SCH-i929",
	"SCH-N719",
	"SCH-W2013",
	"SCH-i509",
	"ZTE N880E",
	"SCH-W899",
	"SCH-I939",
	"HUAWEI G610-C00",
	"GT-N7000",
	"GT-I8552",
	"SCH-I699",
	"ZTE N909",
	"HUAWEI Y300C",
	"GT-I9152",
	"GT-S7562",
	"GT-S7572",
	"SCH-i909",
	"GT-S7568",
	"ONDA MID",
	"GT-S7562i",
	"HUAWEI C8650+",
	"SCH-I759",
	"ZTE V889D",
	"GT-S5830i",
	"GT-I8262D",
	"GT-I9220",
	"GT-I9308",
	"abc'siphone" ,
	"Amity的iPhone" ,
	"bai的IPHONE" ,
	"bdbd117" ,
	"BMWiPhone" ,
	"iphone5" ,
	"iphone" ,
	"LEiPhone" ,
	"Lenovo" ,
	"lenvov" ,
	"leo" ,
	"LEO" ,
	"LeoChen" ,
	"Leon" ,
	"lgalove" ,
	"LGCiPhone" ,
	"MyiPhone" ,
	"李大海的iPhone" ,
	"33c8888",
	"610",
	"6.22",
	"701",
	"acer",
	"Acer",
	"admin",
	"Administrator",
	"AiPhone",
	"Alan",
	"apple",
	"Apple",
	"AppleiPhone",
	"applestore",
	"asus",
	"ausu",
	"Avenger’siPodTouch4",
	"BR",
	"CC",
	"dashuai",
	"dell",
	"Dell",
	"dj",
	"Drew",
	"drivepro",
	"e",
	"E909C",
	"E909C’siPhone",
	"E909C的iPhon...(4.3.3",
	"EROS",
	"Hs",
	"HT",
	"iPad",
	"iphone",
	"iPhone",
	"iPhone3",
	"iPhone3GS",
	"iphone4",
	"iPhone4",
	"iPhone4s",
	"iPhone4S",
	"iphone5.01",
	"iPhone君",
	"iPhone沈洋",
	"iPod",
	"iPodtouch",
	"Jiiniphone",
	"iphone",
	"iphone4",
	"iphone5.01",
	"Jiiniphone",
	"Vishal'siphone",
	"AiPhone",
	"AppleiPhone",
	"Avenger’siPodTouch4",
	"E909C’siPhone",
	"E909C的iPhon...(4.3.3",
	"iPad",
	"iPhone",
	"iPhone3",
	"iPhone3GS",
	"iPhone4",
	"iPhone4s",
	"iPhone4S",
	"iPhone君",
	"iPhone沈洋",
	"iPod",
	"iPodtouch",
	"刘烽的iPhone",
	"斯iPhone4s",
	"apple",
	"applestore",
	"sttxapple",
	"Apple",
	"AppleiPhone",
	"jj",
	"Jkdch",
	"JOE",
	"john",
	"jx",
	"K",
	"KJtx",
	"Kyle",
	"lenovo",
	"lightning",
	"liwq2",
	"LJY",
	"lzm",
	"mac",
	"Mac",
	"Marvin",
	"Mike",
	"ml",
	"my",
	"NokLok",
	"Peter",
	"Q421975019",
	"Rinky",
	"rock",
	"StevenZhang",
	"sttxapple",
	"sunlinsong",
	"Think",
	"Tom",
	"tyll",
	"T樣",
	"user",
	"User",
	"iPhone君",
	"iPhone沈洋",
	"iPod",
	"iPodtouch",
	"Jiiniphone",
	"iphone",
	"iphone4",
	"iphone5.01",
	"Jiiniphone",
	"Vishal'siphone",
	"AiPhone",
	"AppleiPhone",
	"Avenger’siPodTouch4",
	"E909C’siPhone",
	"E909C的iPhon...(4.3.3",
	"iPad",
	"iPhone",
	"iPhone3",
	"iPhone3GS",
	"iPhone4",
	"iPhone4s",
	"iPhone4S",
	"iPhone君",
	"iPhone沈洋",
	"刘伟",
	"刘烽的iPhone",
	"北斗星的爱",
	"卓越通讯",
	"叶",
	"々司仪-子鱼々",
	"咸龙",
	"哥的神器",
	"壮",
	"大润发",
	"奢求，爱你",
	"婷",
	"孙韩",
	"小丹",
	"小康",
	"小景",
	"小晶晶",
	"小马",
	"张华",
	"张明军",
	"徐立立",
	"振鹏",
	"救救小熊吧",
	"斯iPhone4s",
	"易永胜",
	"晓东的iPhone",
	"曹铭",
	"曾斌其",
	"月少很忙",
	"李立",
	"杜先生",
	"来友通讯",
	"欧阳",
	"江建峰",
	"沉鱼儿小姐",
	"泽冗iPhone",
	"混沌之风",
	"温永玺",
	"潘帅",
	"爱3G永中iPhone",
	"王亮",
	"王现琨",
	"王虎",
	"王鹏的iPhone",
	"珈玥的iphone4s",
	"iPhone君",
	"iPhone沈洋",
	"iPod",
	"iPodtouch",
	"Jiiniphone",
	"iphone",
	"iphone4",
	"iphone5.01",
	"Jiiniphone",
	"Vishal'siphone",
	"AiPhone",
	"AppleiPhone",
	"Avenger’siPodTouch4",
	"E909C’siPhone",
	"E909C的iPhon...(4.3.3",
	"iPad",
	"iPhone",
	"iPhone3",
	"iPhone3GS",
	"iPhone4",
	"iPhone4s",
	"iPhone4S",
	"iPhone君",
	"iPhone沈洋",
	"随便",
	"难得赢",
	"韩成锁",
	"顺利",
	"飞",
);

$temp2 	= rand(0,265);
$mnick  = $names[$temp2];

//与自身活动相关的参数及一些载入逻辑
$gameid=$_REQUEST['gameid'];
$mid=$_REQUEST['mid'];
$sid=$_REQUEST['sid'];
$cid=$_REQUEST['cid'];
$pid=$_REQUEST['pid'];
$ctype=$_REQUEST['ctype'];

$buttonctype = $_REQUEST['buttonctype'];
$tihao       = $_REQUEST['tihao'];
$oldmoney=$_REQUEST['oldmoney'];




$games = array(
			"1"=>"showhand",
			"3"=>"landcard",
			"4"=>"bullfight",
		 );
		 
$cidnames["1"] = 
		array(
			 "1"=>array("name"=>"appstore简体","version"=>"",),
			 "2"=>array("name"=>"安智市场","version"=>"",),
			 "3"=>array("name"=>"公司官网","version"=>"",),
			 "4"=>array("name"=>"应用汇","version"=>"",),
			 "5"=>array("name"=>"百度","version"=>"",),
			 "6"=>array("name"=>"豌豆荚","version"=>"",),
			 "7"=>array("name"=>"360助手","version"=>"",),
			 "8"=>array("name"=>"91","version"=>"",),
			 "9"=>array("name"=>"appstore繁体","version"=>"",),
			"10"=>array("name"=>"乐游","version"=>"",),
			"11"=>array("name"=>"移动MM","version"=>"",),
			"12"=>array("name"=>"腾讯应用宝","version"=>"",),
			"14"=>array("name"=>"安卓B","version"=>"",),
			"15"=>array("name"=>"oppo平台","version"=>"",),
			"16"=>array("name"=>"悠悠村(非联运)","version"=>"",),
			"17"=>array("name"=>"小米","version"=>"",),
			"18"=>array("name"=>"移动基地","version"=>"",),
			"19"=>array("name"=>"木瓜","version"=>"",),
			"20"=>array("name"=>"木瓜2","version"=>"",),
			"21"=>array("name"=>"小四大赢家","version"=>"",),
			"22"=>array("name"=>"宝软联盟","version"=>"",),
			"23"=>array("name"=>"斗地主-ipad","version"=>"",),
			"24"=>array("name"=>"斗牛-ipad","version"=>"",),
			"25"=>array("name"=>"梭哈-ipad","version"=>"",),
			"27"=>array("name"=>"木瓜4","version"=>"",),
			"28"=>array("name"=>"点乐沙蟹","version"=>"",),
			"29"=>array("name"=>"wl001","version"=>""),
			"30"=>array("name"=>"木蚂蚁","version"=>""),
			"31"=>array("name"=>"联想","version"=>"",),
			"32"=>array("name"=>"电信爱游戏","version"=>"",),
			"33"=>array("name"=>"木瓜5","version"=>"",),
			"35"=>array("name"=>"搜狐应用平台","version"=>"",),
			"36"=>array("name"=>"安软市场","version"=>"",),
			"38"=>array("name"=>"联系乐商店","version"=>"",),
			"39"=>array("name"=>"易用汇","version"=>"",),
			"40"=>array("name"=>"酷市场","version"=>"",),
			"42"=>array("name"=>"机锋市场","version"=>"",),
			"43"=>array("name"=>"搜狗市场","version"=>"",),
			"44"=>array("name"=>"海南","version"=>"",),
			"45"=>array("name"=>"mg001","version"=>"",),
			"46"=>array("name"=>"海南1(qhzx)","version"=>"",),
			"47"=>array("name"=>"海南2(fxsyou)","version"=>"",),
			"48"=>array("name"=>"联通沃商店","version"=>"",),
			"49"=>array("name"=>"海南3(wnzx002)","version"=>"",),
			"50"=>array("name"=>"微博平台","version"=>"",),
			"51"=>array("name"=>"GooglePlay","version"=>"",),
			"52"=>array("name"=>"悠悠村(联运版)","version"=>"",),
			"53"=>array("name"=>"安粉网","version"=>"",),
			"54"=>array("name"=>"苏宁应用商店","version"=>"",),
			"55"=>array("name"=>"sougo泰语","version"=>"",),
			"56"=>array("name"=>"移动MM外放","version"=>"",),
			"57"=>array("name"=>"酷传市场","version"=>"",),
			"58"=>array("name"=>"ss001","version"=>"",),
			"59"=>array("name"=>"东南亚市场888","version"=>"",),
			"60"=>array("name"=>"wb001","version"=>"",),
			"61"=>array("name"=>"snhd","version"=>"",),
		 );


		 

$cidnames["3"] = 
		array(
			"3"=>array("name"=>"公司官网","showname"=>"点乐斗地主","version"=>"1.1.3"),
			//普通
			 "2"=>array("name"=>"安智市场","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-anzhi-v1.1.3-release.apk",),
			 "4"=>array("name"=>"应用汇","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-yingyonghui-v1.1.3-release.apk",),
			 "6"=>array("name"=>"豌豆荚","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-wandoujia-v1.1.3-release.apk",),
			"12"=>array("name"=>"腾讯应用宝","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-tencent-v1.1.3-release.apk",),
			"35"=>array("name"=>"搜狐应用平台","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-souhu-v1.1.3-release.apk",),
			"38"=>array("name"=>"联想乐商店","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-lenovo-v1.1.3-release.apk",),
			"30"=>array("name"=>"木蚂蚁","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-mumayi-v1.1.3-release.apk",),
			"57"=>array("name"=>"酷传市场","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-kuchuang-v1.1.3-release.apk",),
			"58"=>array("name"=>"ss001","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-ss001-v1.1.3-release.apk",),
			"60"=>array("name"=>"wb001","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-wb001-v1.1.3-release.apk",),
			"61"=>array("name"=>"snhd","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-snhd-v1.1.3-release.apk",),
			
			//木瓜
			"20"=>array("name"=>"木瓜2","showname"=>"全民斗地主","version"=>"1.1.3","apkname"=>"LandCard-mugua2-v1.1.3-release.apk",),
			
			//更新到官网
			"19"=>array("name"=>"木瓜->更新到官网","showname"=>"全民斗地主","version"=>"1.1.3","apkname"=>"LandCard-guanwang-v1.1.3-release.apk",),
			"33"=>array("name"=>"木瓜5->更新到官网","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-guanwang-v1.1.3-release.apk",),
			"29"=>array("name"=>"wl001->更新到官网","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-guanwang-v1.1.3-release.apk",),
			"22"=>array("name"=>"宝软联盟原木瓜3->更新到官网","showname"=>"点乐斗地主","version"=>"1.1.3","apkname"=>"LandCard-guanwang-v1.1.3-release.apk",),


			//海南
			"44"=>array("name"=>"海南","showname"=>"海南斗地主","version"=>"1.1.3","apkname"=>"LandCard-hainan-v1.1.3-release.apk",),
			"45"=>array("name"=>"mg001","showname"=>"海南mg001斗地主","version"=>"1.1.3","apkname"=>"LandCard-mg001-v1.1.3-release.apk",),
			"46"=>array("name"=>"海南1(qhzx)","showname"=>"海南1斗地主","version"=>"1.1.3","apkname"=>"LandCard-hainan1-v1.1.3-release.apk",),
			"47"=>array("name"=>"海南2(fxsyou)","showname"=>"海南2斗地主","version"=>"1.1.3","apkname"=>"LandCard-hainan2-v1.1.3-release.apk",),
			"49"=>array("name"=>"海南3(wnzx002)","showname"=>"海南3斗地主","version"=>"1.1.3","apkname"=>"LandCard-hainan3-v1.1.3-release.apk",),
			"44_1191"=>array("name"=>"海南应用宝","showname"=>"海南应用宝斗地主","version"=>"1.1.3","apkname"=>"LandCard-yingyongbao-v1.1.3-release.apk",),
			
		 );		 
		 //特殊的 海南cid=44 pid=1162首发包。 海南cid=44 pid=1191应用宝包

$cidnames["4"] = 
		array(
			 "1"=>array("name"=>"appstore简体","version"=>"",),
			 "2"=>array("name"=>"安智市场","showname"=>"点乐斗牛","version"=>"1.0.5",),
			 "3"=>array("name"=>"公司官网","showname"=>"点乐斗牛","version"=>"1.0.5",),
			 "4"=>array("name"=>"应用汇","showname"=>"点乐斗牛","version"=>"1.0.5",),
			 "5"=>array("name"=>"百度","version"=>"",),
			 "6"=>array("name"=>"豌豆荚","showname"=>"点乐斗牛","version"=>"1.0.5",),
			 "7"=>array("name"=>"360助手","version"=>"",),
			 "8"=>array("name"=>"91","version"=>"",),
			 "9"=>array("name"=>"appstore繁体","version"=>"",),
			"10"=>array("name"=>"乐游","version"=>"",),
			"11"=>array("name"=>"移动MM","version"=>"",),
			"12"=>array("name"=>"腾讯应用宝","version"=>"",),
			"14"=>array("name"=>"安卓B","version"=>"",),
			"15"=>array("name"=>"oppo平台","showname"=>"点乐斗牛","version"=>"1.0.3",),
			"16"=>array("name"=>"悠悠村(非联运)","version"=>"",),
			"17"=>array("name"=>"小米","version"=>"",),
			"18"=>array("name"=>"移动基地","version"=>"",),
			"19"=>array("name"=>"木瓜","showname"=>"点乐斗牛","version"=>"1.0.5",),
			"20"=>array("name"=>"木瓜2","showname"=>"点乐牛牛","version"=>"1.0.5",),
			"21"=>array("name"=>"小四大赢家","version"=>"",),
			"22"=>array("name"=>"宝软联盟","version"=>"",),
			"23"=>array("name"=>"斗地主-ipad","version"=>"",),
			"24"=>array("name"=>"斗牛-ipad","version"=>"",),
			"25"=>array("name"=>"梭哈-ipad","version"=>"",),
			"27"=>array("name"=>"木瓜4","version"=>"",),
			"28"=>array("name"=>"点乐沙蟹","version"=>"",),
			"29"=>array("name"=>"wl001","showname"=>"点乐斗牛","version"=>"1.0.2"),
			"30"=>array("name"=>"木蚂蚁","version"=>""),
			"31"=>array("name"=>"联想","version"=>"",),
			"32"=>array("name"=>"电信爱游戏","version"=>"",),
			"33"=>array("name"=>"木瓜5","version"=>"",),
			"35"=>array("name"=>"搜狐应用平台","showname"=>"点乐斗牛","version"=>"1.0.5",),
			"36"=>array("name"=>"安软市场","version"=>"",),
			"38"=>array("name"=>"联系乐商店","version"=>"",),
			"39"=>array("name"=>"易用汇","showname"=>"点乐斗牛","version"=>"1.0.5",),
			"40"=>array("name"=>"酷市场","version"=>"",),
			"42"=>array("name"=>"机锋市场","version"=>"",),
			"43"=>array("name"=>"搜狗市场","version"=>"",),
			"44"=>array("name"=>"海南","version"=>"",),
			"45"=>array("name"=>"mg001","version"=>"",),
			"46"=>array("name"=>"海南1(qhzx)","version"=>"",),
			"47"=>array("name"=>"海南2(fxsyou)","version"=>"",),
			"48"=>array("name"=>"联通沃商店","version"=>"",),
			"49"=>array("name"=>"海南3(wnzx002)","version"=>"",),
			"50"=>array("name"=>"微博平台","version"=>"",),
			"51"=>array("name"=>"GooglePlay","version"=>"",),
			"52"=>array("name"=>"悠悠村(联运版)","version"=>"",),
			"53"=>array("name"=>"安粉网","version"=>"",),
			"54"=>array("name"=>"苏宁应用商店","version"=>"",),
			"55"=>array("name"=>"sougo泰语","version"=>"",),
			"56"=>array("name"=>"移动MM外放","version"=>"",),
			"57"=>array("name"=>"酷传市场","version"=>"",),
			"58"=>array("name"=>"ss001","showname"=>"点乐斗牛","version"=>"1.0.5",),
			"59"=>array("name"=>"东南亚市场888","version"=>"",),
			"60"=>array("name"=>"wb001","version"=>"",),
			"61"=>array("name"=>"snhd","version"=>"",),
		 );	





$apkUrl = "http://dianler.com/static/apk/";//LandCard-guanwang.apk;		 
$tarVersion ="0.0.0";


if($gameid == 1){
	//梭哈更新到2.0.0
	//$tarVersion = "2.0.1";
	if($ctype == 1){//android
		$tarVersion = "3.0.0";
	}else if($ctype == 2){
		$tarVersion = "3.0.1";
	}
}else if($gameid == 3){
	if($ctype == 1){
		$tarVersion = "3.0.0";
	}else if($ctype == 2){
		$tarVersion = "3.0.0";
	}
}else if($gameid ==  4){
	
	
	if($ctype == 1){
		$tarVersion = "3.0.0";
	}else if($ctype == 2){
		$tarVersion = "3.0.0";
	}
}

$status = 0;

//版本是否是最新版本
if($versions == $tarVersion){

	$status = 1;
}

//不是升级过来的用户
$userinfo = Member::factory()->getUserInfo($mid,false);
$mnickz    = $userinfo['mnick'];
$mnickz    = trim($mnickz);
$mnickz = $mnickz ? $mnickz : "GT-I9100";

$mentercountJson = $userinfo['mentercount'];//"{\"a\":b,\"c\":d}" ;

$mentercountA = json_decode($mentercountJson, true);

$mentercount  = $mentercountA[$gameid];

//不是升级过来的用户直接退出
if($mentercount<=1){

	//echo json_encode($ret);
	//exit;
}

//已经领取过
$hadgetKey = "Versionup_".$gameid."_".$mid;

if($gameid==1){
	$hadgetKey  = "Versionup_".$gameid."_".$ctype."_".$mid;
	$otherCtype = 3-$ctype;
	$otherhadgetKey  = "Versionup_".$gameid."_".$otherCtype."_".$mid;
}



//如果是斗地主ios
if($gameid==3){
	$hadgetKey = "Versionup_".$gameid."_".$ctype."_".$mid;
	$otherCtype = 3-$ctype;
	$otherhadgetKey  = "Versionup_".$gameid."_".$otherCtype."_".$mid;
}

if($gameid==4){
	$hadgetKey = "Versionup_".$gameid."_".$ctype."_".$mid;
	$otherCtype = 3-$ctype;
	$otherhadgetKey  = "Versionup_".$gameid."_".$otherCtype."_".$mid;
}

$hadget 	 = Loader_Redis::common()->get($hadgetKey);
$otherHadGet = Loader_Redis::common()->get($otherhadgetKey);

if($hadget){
	$status = 2;
}
if($otherHadGet){
	$status = 2;
}


if($status == 0){
	//返回 //没有更新
	echo json_encode($ret);
	exit;
}else if($status == 2){
	//返回 //已经领取
	echo json_encode($ret);
	exit;
}

//标记已经领取
Loader_Redis::common()->set($hadgetKey,2);
Loader_Redis::common()->set($otherhadgetKey,2);
$jianglimoney = 1888;
//加金币
Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0,$jianglimoney,$desc='Versionup');

//返回结果
$ret['money'] = $jianglimoney;
$ret['flag'] = 1;


//上报数据
//Loader_Udp::stat()->sendData(65,$mid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);

exit;



//用户牌局信息
$paijuinfo="y";

$target1 = 99;
$target2 = 99;

$target3 = 99;//铜锤
//获取用户的牌局信息
if($gameid==1){
	$paijuinfo = Loader_Redis::common()->hGet("sh_winstreak",$mid);
	//$paijuinfo="1010101111110110011111111011111111111111111111111111111111111111";
	$target3 = 2;
	$target1 = 10;
	$target2 = 20;
}else if($gameid==3){
	$paijuinfo = Loader_Redis::rank(3)->hGet("lc_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111111";
	$target3 = 2;
	$target1 = 5;
	$target2 = 10;
}else if($gameid==4){
	$paijuinfo = Loader_Redis::rank(4)->hGet("bf_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111";
	$target3 = 2;
	$target1 = 10;
	$target2 = 20;
}
if(!$paijuinfo){
	$paijuinfo="y";
}



//分析牌局信息得到当前连赢局数 和 最高连赢次数
$lianyin = 0;
$maxlianyin = 0;
$paijushu   = 0;
if($paijuinfo && $paijuinfo!="y")
{
	
	$paijushu = strlen($paijuinfo);
	$length = strlen($paijuinfo);
	for($i=0;$i<$length;$i++)
	{
		$tmp = substr($paijuinfo,$i,1);
		
		if($tmp == 0){
			if($lianyin>$maxlianyin){
				$maxlianyin = $lianyin;
			}
			$lianyin = 0;
		}else if($tmp==1){
			$lianyin = $lianyin+1;
		}
	}
	
	if($lianyin>$maxlianyin){
		$maxlianyin = $lianyin;
	}
}

//****************************
//0.未解锁 1.已经解锁 但未使用 2.已经使用
$tCstatus = 0;
$yCstatus = 0;
$jCstatus = 0;

if($paijushu>=$target3){
	$tCstatus = 1;
}

if($paijushu>=$target1){
	$yCstatus = 1;
}

if($paijushu>=$target2){
	$jCstatus = 1;
}



$thadKey = "ZD_C_S_T_".$mid."_".$gameid."_".$keyday;
$yhadKey = "ZD_C_S_Y_".$mid."_".$gameid."_".$keyday;
$jhadKey = "ZD_C_S_J_".$mid."_".$gameid."_".$keyday;

if($tCstatus == 1){
	$thad = Loader_Redis::common()->get($thadKey);
	if($thad && $thad > 0){
		$tCstatus = 2;
	}
}

if($yCstatus == 1){
	$yhad = Loader_Redis::common()->get($yhadKey);
	if($yhad && $yhad > 0){
		$yCstatus = 2;
	}
}

if($jCstatus == 1){
	$jhad = Loader_Redis::common()->get($jhadKey);
	if($jhad && $jhad > 0){
		$jCstatus = 2;
	}
}

//查看是否已经使用了
if($buttonctype == 1){//铜锤
	if($tCstatus!=1){
		echo json_encode($ret);
		exit;
	}
}else if($buttonctype == 2){//银锤
	if($yCstatus!=1){
		echo json_encode($ret);
		exit;
	}
}else if($buttonctype  == 3){//金锤
	if($jCstatus!=1){
		echo json_encode($ret);
		exit;
	}
}
//***************************






//三项任务进展状态 0.未达到领取条件 1.可以领取. 2.已经领取
$canLingqu = 0;
$jianglimoney = 0;

$temp2 = rand(1,100);

if($buttonctype == 1){//铜锤 188金币；288金币；588金币 188和288随机
	if($temp2<=50){
		$jianglimoney = 188;
	}else{
		$jianglimoney = 288;
	}
}else if($buttonctype == 2){//银锤 2小喇叭；3小喇叭；8小喇叭；888金币；1288金币 2个小喇叭（80%）3个小喇叭（10%）888金币（10%）
	if($temp2>=1 && $temp2<=80){
		$jianglimoney = 2;
	}else if($temp2>=81 && $temp2<=90){
		$jianglimoney = 3;
	}else{
		$jianglimoney = 888;
	}
}else if($buttonctype  == 3){//金锤 10元话费（移动，电信，联通）；2288金币；2888金币；3988金币；【2288金币和2888金币随机】
	if($temp2<=50){
		$jianglimoney = 2288;
	}else{
		$jianglimoney = 2888;
	}
}	




$userinfo = Member::factory()->getUserInfo($mid,false);
$mnickz    = $userinfo['mnick'];
$mnickz    = trim($mnickz);
$mnickz = $mnickz ? $mnickz : "GT-I9100";


//发货
//标记领取

if($buttonctype == 1){//铜锤
	Loader_Redis::common()->set($thadKey,1);
}else if($buttonctype == 2){//银锤
	Loader_Redis::common()->set($yhadKey,1);
}else if($buttonctype  == 3){//金锤
	Loader_Redis::common()->set($jhadKey,1);
}

//写历史记录
$tempString = Loader_Redis::common()->get("ZDH_".$mid."_".$gameid);
if(!$tempString){
	$tempString = "";
}
if($jianglimoney == 2 || $jianglimoney == 3){
	$tempString = $tempString.",".$today."@".$num."2@".$jianglimoney;
}else{
	$tempString = $tempString.",".$today."@".$num."1@".$jianglimoney;
}
Loader_Redis::common()->set("ZDH_".$mid."_".$gameid,$tempString);

//发放奖励
if($jianglimoney == 2 || $jianglimoney == 3){
	//$fafanglaba = Loader_Redis::common()->set("fafanglaba".$keyday,$fafanglaba);
	if($jianglimoney == 3){
		$msg      = "系统消息:天降财神,恭喜".$mnickz."在砸金蛋活动中使用银锤砸中".$jianglimoney."小喇叭！";//".$mnick."
		Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);
	}
	Loader_Redis::account()->hIncrBy(Config_Keys::other($mid), 'horn',$jianglimoney);
}else{
	//if($jianglimoney == 888){
		$st = rand(75,100);
		if($st >= 85 && $st<=90){
			$msg      = "系统消息:天降财神,恭喜".$mnick."在砸金蛋活动中使用金锤砸中3988金币";//".$mnick."
			Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
		}else if($st >= 91 && $st<=93){
			$msg      = "系统消息:天降财神,恭喜".$mnick."在砸金蛋活动砸中10元话费！";//".$mnick."
			Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
		}else if($st >= 94 && $st<=95){
			//$msg      = "系统消息:恭喜".$mnick."在砸金蛋活动中使用银锤,很可惜只得到888金币，大家一起围观鄙视他.";//".$mnick."
			//Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
		}
	//}

	Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0,$jianglimoney,$desc='zadan');
	
}
$ret['money']  = $jianglimoney;



//type  100 表示发到全部游戏




//返回结果
$ret['questTittle'] = $timu[$tmpTihao]['question'];
$ret['questA'] = 'A:'.$timu[$tmpTihao]['A'];
$ret['questB'] = 'B:'.$timu[$tmpTihao]['B'];
$ret['questC'] = 'C:'.$timu[$tmpTihao]['C'];
$ret['tihao'] = $tmpTihao;
$ret['flag'] = 1;
$ret['canTiNum'] = $mytimuNum-$handAnswerNum-1;

//上报数据
//Loader_Udp::stat()->sendData(65,$mid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);
