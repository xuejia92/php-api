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
if($time< strtotime("2014-3-11") || $time > strtotime("2014-12-30")){
	echo json_encode($ret);
	exit;
}

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


//目标
$target1 = 10;
$target2 = 150;
$target3 = 550;

//奖励
$do1 = 4;
$do2 = 68;
$do3 = 276;

//用户当日累计充值金额
$hadKey = "chongzhi_".$mid."_".$keyday;
$hadnum = Loader_Redis::common()->get($hadKey);
if(!$hadnum){
	$hadnum = 0;
}
if($mid == 4057){
	$hadnum = 5600000;
}
//按钮状态 0:未达到条件，1：可领取，2：已领取
$buttonStatus1 = 0;
$buttonStatus2 = 0;
$buttonStatus3 = 0;

if($hadnum>=$target1*10000){
	$buttonStatus1 = 1;
}

if($hadnum>=$target2*10000){
	$buttonStatus2 = 1;
}

if($hadnum>=$target3*10000){
	$buttonStatus3 = 1;
}


$hadgetKey1 = "chongzhi1_".$mid."_".$keyday;
$hadgetKey2 = "chongzhi2_".$mid."_".$keyday;
$hadgetKey3 = "chongzhi3_".$mid."_".$keyday;

$had1 = Loader_Redis::common()->get($hadgetKey1);
$had2 = Loader_Redis::common()->get($hadgetKey2);
$had3 = Loader_Redis::common()->get($hadgetKey3);

if($had1){
		$buttonStatus1 = 2;
}

if($had2){
		$buttonStatus2 = 2;
}

if($had3){
		$buttonStatus3 = 2;
}


$faFlag = 0;
$jianglimoney = 0;
if($buttonctype == 1){
	if($buttonStatus1 == 1){
		$faFlag = 1;
		$jianglimoney = $do1*10000;
	}else if($buttonStatus1 == 2){
		$faFlag = 2;
	}
}else if($buttonctype == 2){
	if($buttonStatus2 == 1){
		$faFlag = 1;
		$jianglimoney = $do2*10000;
	}else if($buttonStatus1 == 2){
		$faFlag = 2;
	}
}else if($buttonctype == 3){
	if($buttonStatus3 == 1){
		$faFlag = 1;
		$jianglimoney = $do3*10000;
	}else if($buttonStatus1 == 2){
		$faFlag = 2;
	}
}



if($faFlag == 0){//未达到领取条件
	$ret['messid'] = 4;	
	echo json_encode($ret);
	exit;
}else if($faFlag == 2){//已经领取
	$ret['messid'] = 5;	
	echo json_encode($ret);
	exit;
}else if($faFlag == 1){//发放奖励

	
	//标记今天
	if($buttonctype == 1){
		Loader_Redis::common()->set($hadgetKey1,1);
	}else if($buttonctype == 2){
		Loader_Redis::common()->set($hadgetKey2,1);
	}else if($buttonctype == 3){
		Loader_Redis::common()->set($hadgetKey3,1);
	}
	
	//发放金币
	Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0,$jianglimoney,$desc='chongzhifanli');
	$ret['laba']  = $jianglilaba;
	$ret['money'] = $jianglimoney;
	$ret['flag'] = 1;
	
	
	
	//发小喇叭
	//if($buttonctype >=2 ){
		$userinfo = Member::factory()->getUserInfo($mid,false);
		$mnick    = $userinfo['mnick'];
		$mnick    = trim($mnick);
		$mnick 	  = $mnick ? $mnick : "GT-I9100";
		
		$msg      = "系统消息:恭喜".$mnick."累计充值".$hadnum."金币"."在充值返利活动中成功领取".$jianglimoney."金币的返利！多冲多送，小伙伴们快来哟！";//".$mnick."
		//Logs::factory()->debug($msg,'zhiqunshengju_horn');
		Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
		
	//}
	
	
	
	//点击进入活动统计 根据不同游戏来统计
	$tojikey = "total_chongzhi_".$buttonctype;
	if($_REQUEST['gameid'] == 1){
		$tojikey = $tojikey."_1";
	}else if($_REQUEST['gameid'] == 3){
		$tojikey = $tojikey."_3";
	}else if($_REQUEST['gameid'] == 4){
		$tojikey = $tojikey."_4";
	}
	
	$tojikey = $tojikey."_".$keyday;

	$tojiNum = Loader_Redis::common()->hGet($tojikey,$_REQUEST['mid']);
	//hset hash_key field_name field_value
	if(!$tojiNum){
		$tojiNum = 0;
	}

	$tojiNum = $tojiNum+1;

	Loader_Redis::common()->hSet($tojikey,$_REQUEST['mid'],$tojiNum);
	
	
	echo json_encode($ret);
	
	exit;
}


















/*

//今天是否领取过
$hadDayKey = "GuoqingDay_".$mid."_".$keyday;
$hadDay    = Loader_Redis::common()->get($hadDayKey);
//今天已经领取过
if($hadDay){
	$ret['messid'] = 5;	
	echo json_encode($ret);
	exit;
}

	
//获取已经领取的奖励次数
$hadKey = "Guoqing_".$mid;
$hadnum = Loader_Redis::common()->get($hadKey);	
if(!$hadnum){
	$hadnum = 0;
}


$tar = $buttonctype-1 ;

//这个宝箱已经领取过
if($hadnum>$tar){
	$ret['messid'] = 5;	
	echo json_encode($ret);
	exit;
}

//该宝箱还未开放
if($hadnum<$tar){
//宝箱需要逐个开启，加油哦。
	$ret['messid'] = 4;	
	echo json_encode($ret);
	exit;
}

//根据buttontype发放不同奖励
$jianglimoney = 0;
$jianglilaba = 0;
if($buttonctype == 1){
	$jianglimoney = 588;
}else if($buttonctype == 2){
	$jianglimoney = 666;
}else if($buttonctype == 3){
	$jianglimoney = 888;
	$jianglilaba = 1;
}else if($buttonctype == 4){
	$jianglimoney = 1288;
}else if($buttonctype == 5){
	$jianglimoney = 2888;
	$jianglilaba = 2;

}else if($buttonctype == 6){
	$jianglimoney = 6666;
}else if($buttonctype == 7){
	$jianglimoney = 88888;
	$jianglilaba = 8;
}




//标记总领取
Loader_Redis::common()->set($hadKey,$buttonctype);
//标记今天
Loader_Redis::common()->set($hadDayKey,1);

//发放喇叭
if($jianglilaba>0){
	Loader_Redis::account()->hIncrBy(Config_Keys::other($mid), 'horn',$jianglilaba);
}
//发放金币
Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0,$jianglimoney,$desc='zadan');

$ret['laba']  = $jianglilaba;
$ret['money'] = $jianglimoney;
$ret['flag'] = 1;


//上报数据
//Loader_Udp::stat()->sendData(65,$mid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);
*/