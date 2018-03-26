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
$mtkey      = $_REQUEST['mtkey']?$_REQUEST['mtkey']:'';
//获取用户金币和乐券
//$userGameInfo = Member::factory()->getGameInfo($mid,false);
//$userInfo     = Member::factory()->getOneById($mid,false);
$paramString = "&gameid=".$gameid."&mid=".$mid."&sid=".$sid."&cid=".$cid."&pid=".$pid."&ctype=".$ctype."&versions=".$versions;

$jiangliNum = 0;
if($gameid == 1){
	$jiangliNum = 10000;
}else if($gameid == 3){
	$jiangliNum = 10000;
}else if($gameid == 4){
	$jiangliNum = 10000;
}
Loader_Udp::stat()->sendData(42,$mid,$gameid,$ctype,$cid,$sid,$pid,'');
//Logs::factory()->debug($mid,"wuxinghuodong".$gameid.$keyday);
$target1 	= 10;
$target2 	= 10;
$target3 	= 10;

$doTarget1 	= 0;
$doTarget2 	= 0;
$doTarget3 	= 0;

//三项任务进展状态 1.正在做此项 2.已经做完此项
$status1 	= 1;
$status2 	= 1;
$status3 	= 1;

if($target1<=$doTarget1)
{
	$status1 = 2;
}

if($target2<=$doTarget2)
{
	$status2 = 2;
}

if($target3<=$doTarget3)
{
	$status3 = 2;
}

//宝箱是否可以开启 1.宝箱不可点击。 2.宝箱可点击。 3.宝箱已点击
$status 	= 1;

if($status1==2&&$status2==2&&$status3==2)
{
	$status = 2;
}

//如果是可以领取状态 那么检测宝箱是否已经领取过
if($status == 2)
{
	//当天已经领取的次数
	$hadLa = Loader_Redis::common()->get("zhiqunshengju_".$mid.$keyday);
	$hadLa = $hadLa?$hadLa:0;
	
	if($hadLa == 1)
	{
		$status = 3;
	}
}

$pinglunKeys = "wuxingpingjia_".$gameid."_".$mid;
$pinglun = Loader_Redis::common()->get($pinglunKeys);
if(!$pinglun){
	$pinglun = 0;
}

//机器码
$deviceno = Member::factory()->getDevicenoBymid($mid);
$pinglunDevicenoKeys = "wuxingpingjia_".$gameid."_".$deviceno;
$pinglunDeviceno     = Loader_Redis::common()->get($pinglunDevicenoKeys);
if(!$pinglunDeviceno){
	$pinglunDeviceno = 0;
}

if($mid == 14481){
	$pinglunDeviceno = 0;
}
if($pinglunDeviceno==1){
	$pinglun = 2;
}
if($mid == 4057){
	echo $pinglunDevicenoKeys."  pinglun".$pinglun;
}
require_once('view/index_'.$gameid.'.html');