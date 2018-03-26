<?php 
!defined('IN WEB') AND exit('Access Denied!');

$time  = time();
$today = date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);

//与自身活动相关的参数及一些载入逻辑
$gameid=$_REQUEST['gameid'];
$mid=$_REQUEST['mid'];
$sid=$_REQUEST['sid'];
$cid=$_REQUEST['cid'];
$pid=$_REQUEST['pid'];
$ctype=$_REQUEST['ctype'];
$versions = $_REQUEST['versions'];

//获取用户金币和乐券
$userGameInfo = Member::factory()->getGameInfo($mid,false);
$userInfo     = Member::factory()->getOneById($mid,false);
$paramString = "&gameid=".$gameid."&mid=".$mid."&sid=".$sid."&cid=".$cid."&pid=".$pid."&ctype=".$ctype."&versions=".$versions;

Loader_Udp::stat()->sendData(42,$mid,$gameid,$ctype,$cid,$sid,$pid,'');

//获取连续登陆天数
$accountInfo = Loader_Redis::account()->hMget(Config_Keys::other($mid), array('continuousLoginDay','bankPWD','binding','horn'));
$continuousLoginDay   =  $continuousLoginDay1  = $accountInfo['continuousLoginDay'];
$continuousLoginDay       = date("Ymd",(int)$userInfo['mactivetime']) == date("Ymd",strtotime("-1 days")) ? $continuousLoginDay + 1 : $continuousLoginDay;

//按照登陆天数 可抽奖次数
$canLa = 0;
if($continuousLoginDay>=1 && $continuousLoginDay <3){
	$canLa = 1;
}else if($continuousLoginDay>=3 && $continuousLoginDay <5){
	$canLa = 2;
}else if($continuousLoginDay>=5){
	$canLa = 3;
}

//当天已经领取的次数
$hadLa = Loader_Redis::common()->get("hadLa".$mid.$keyday);
$hadLa = $hadLa?$hadLa:0;

//得出当前可拉次数
$canLa = $canLa-$hadLa;


require_once('view/index_1.html');