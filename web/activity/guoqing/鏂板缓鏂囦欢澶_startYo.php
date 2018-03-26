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
$mnick    = $userinfo['mnick'];
$mnick    = trim($mnick);
$mnick = $mnick ? $mnick : "GT-I9100";


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
	Loader_Redis::account()->hIncrBy(Config_Keys::other($mid), 'horn',$jianglimoney);
}else{
	Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0,$jianglimoney,$desc='zadan');
	
}
$ret['money']  = $jianglimoney;

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
