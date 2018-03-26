<?php 
!defined('IN WEB') AND exit('Access Denied!');


$time  	= time();
$today 	= date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);

//与自身活动相关的参数及一些载入逻辑
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$versions = $_REQUEST['versions'];

//获取用户金币和乐券
//$userGameInfo = Member::factory()->getGameInfo($mid,false);
//$userInfo     = Member::factory()->getOneById($mid,false);
$paramString = "&gameid=".$gameid."&mid=".$mid."&sid=".$sid."&cid=".$cid."&pid=".$pid."&ctype=".$ctype."&versions=".$versions;

//Loader_Udp::stat()->sendData(42,$mid,$gameid,$ctype,$cid,$sid,$pid,'');

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

		 
$apkUrl = "http://www.dianler.com/static/apk/";//LandCard-guanwang.apk;		 
$tarVersion ="0.0.0";

if($gameid == 3 && $cid == 44 && $pid == 1191 ){		 
	$apkUrl = $apkUrl.$cidnames[$gameid]["44_1191"]["apkname"];
	$tarVersion = $cidnames[$gameid]["44_1191"]["version"];
}else{
	$apkUrl = $apkUrl.$cidnames[$gameid][$cid]["apkname"];
	$tarVersion = $cidnames[$gameid][$cid]["version"];
}

if($apkUrl == "http://dianler.com/static/apk/"){

}

$apkUrl = "http://www.dianler.com/";

if($gameid == 1){
	//梭哈更新到2.0.0
	if($ctype == 1){
		$tarVersion = "3.0.0";
	}else if($ctype == 2){
		$tarVersion = "3.0.1";
	}
}

if($gameid == 3){
	//斗地主更新到3.0.0
	if($ctype == 1){
		$tarVersion = "3.0.0";
	}else if($ctype == 2){
		$tarVersion = "3.0.0";
	}
}else if($gameid ==  4){
	//斗牛更新到2.0.0
	if($ctype == 1){
		$tarVersion = "3.0.0";
	}else if($ctype == 2){
		$tarVersion = "3.0.0";
	}
}
$jianglimoney = 1888;

$status = 0;

//版本是否是最新版本
if($versions == $tarVersion){
	$status = 1;
}

//已经领取过
$hadgetKey = "Versionup_".$gameid."_".$mid;
if($gameid==1){
	$hadgetKey = "Versionup_".$gameid."_".$ctype."_".$mid;
	$otherCtype = 3-$ctype;
	$otherhadgetKey  = "Versionup_".$gameid."_".$otherCtype."_".$mid;
}
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
$hadget = Loader_Redis::common()->get($hadgetKey);
$otherHadGet = Loader_Redis::common()->get($otherhadgetKey);
if($hadget){
	$status = 2;
}
if($otherHadGet){
	$status = 2;
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
	//$status = 2;
}
//echo $versions."-----".$gameid."-----".$cid."-----".$tarVersion."---".$status;
//exit;
//
//echo $apkUrl;
require_once("view/index_".$gameid.".html");