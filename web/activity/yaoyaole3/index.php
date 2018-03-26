<?php 
!defined('IN WEB') AND exit('Access Denied!');

$time  = time();
$today = date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);

//与自身活动相关的参数及一些载入逻辑
$gameid	=	$_REQUEST['gameid'];
$mid	=	$_REQUEST['mid'];
$sid	=	$_REQUEST['sid'];
$cid	=	$_REQUEST['cid'];
$pid	=	$_REQUEST['pid'];
$ctype	=	$_REQUEST['ctype'];
$versions = $_REQUEST['versions'];

//获取用户金币和乐券
$userGameInfo 	= Member::factory()->getGameInfo($mid,false);
$userInfo     	= Member::factory()->getOneById($mid,false);
$paramString 	= "&gameid=".$gameid."&mid=".$mid."&sid=".$sid."&cid=".$cid."&pid=".$pid."&ctype=".$ctype."&versions=".$versions;

//Loader_Udp::stat()->sendData(42,$mid,$gameid,$ctype,$cid,$sid,$pid,'');

//根据用户金币数 生成不同的金额按钮
$buttonMoney1 = 0;
$buttonMoney2 = 0;
$buttonMoney3 = 0;
$buttonMoney4 = 0;
if($userGameInfo['money']<=10*10000)
{
	$buttonMoney1 = 1000;
	$buttonMoney2 = 2000;
	$buttonMoney3 = 5000;
	$buttonMoney4 = 10000;
}
else if($userGameInfo['money']>10*10000 && $userGameInfo['money']<=100*10000)
{
	$buttonMoney1 = 5000;
	$buttonMoney2 = 10000;
	$buttonMoney3 = 50000;
	$buttonMoney4 = 100000;
}
else if($userGameInfo['money']>100*10000 && $userGameInfo['money']<=1000*10000)
{
	$buttonMoney1 = 10000;
	$buttonMoney2 = 50000;
	$buttonMoney3 = 100000;
	$buttonMoney4 = 200000;
}
else if($userGameInfo['money']>1000*10000)
{
	$buttonMoney1 = 50000;
	$buttonMoney2 = 100000;
	$buttonMoney3 = 200000;
	$buttonMoney4 = 500000;
}


//按照登陆天数 可抽奖次数
$canLa = 1;


//当天已经领取的次数
$hadLa = Loader_Redis::common()->get("hadLa".$mid.$keyday);
$hadLa = $hadLa?$hadLa:0;

$xingyunvalue = 0;

//幸运值
if($hadLa == 0){
	$xingyunvalue = 0;
}else{
	$xingyunvalue = $hadLa%10;
	if($xingyunvalue==0){
		$xingyunvalue = 10;
	}
}

//得出当前可拉次数
if($hadLa>0){
	$canLa = 0;
}

$tojiBZkey = "YYL_BZ_".$gameid;
$tojiBZkey = $tojiBZkey."_".$keyday;
$tojiBZNum = Loader_Redis::common()->hGet($tojiBZkey,$mid);
//hset hash_key field_name field_value
if(!$tojiBZNum){
	$tojiBZNum = 0;
}
require_once('view/index_1.html');