<?php 
!defined('IN WEB') AND exit('Access Denied!');

$time  = time();
$today = date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);

$ret = array();
$ret['flag']   = 0;  	//返回值
$ret['messid'] = 0;		//错误号
$ret['money']  = 15;	//领取金额

//时间控制 超过这个时间不在进行处理
if($time< strtotime("2014-3-11") || $time > strtotime("2014-06-30")){
	//echo json_encode($ret);
	//exit;
}

//与自身活动相关的参数及一些载入逻辑
$gameid	=	$_REQUEST['gameid'];
$mid	=	$_REQUEST['mid'];
$sid	=	$_REQUEST['sid'];
$cid	=	$_REQUEST['cid'];
$pid	=	$_REQUEST['pid'];
$ctype	=	$_REQUEST['ctype'];



//此人豹子次数
$tojiBZkey = "YYL_BZ_".$gameid;
$tojiBZkey = $tojiBZkey."_".$keyday;
$tojiBZNum = Loader_Redis::common()->hGet($tojiBZkey,$mid);
//hset hash_key field_name field_value
if(!$tojiBZNum){
	$tojiBZNum = 0;
}

//第几个金币项
$moneyIndex = $_REQUEST['moneyIndex'];
//花费的金币
$useMoney = $_REQUEST['useMoney'];
//选择的大 还是 小
$bigOrSmall = $_REQUEST['bigOrSmall'];  //1,小 2,大
//玩家原始金币
$oldmoney	=	$_REQUEST['oldmoney'];

$huishoujinbi = Loader_Redis::common()->get("YYL_HS_".$gameid."_".$keyday);

if(!$huishoujinbi){
	$huishoujinbi = 0;
}

$fafangjinbi = Loader_Redis::common()->get("YYL_FF_".$gameid."_".$keyday);

if(!$fafangjinbi){
	$fafangjinbi = 0;
}

if($tojiBZNum<3){
	$ret['messid'] = 4;		//未达到领取次数
	echo json_encode($ret);
	exit;
}
if($tojiBZNum>=4){
	$ret['messid'] = 5;		//已经领取过
	echo json_encode($ret);
	exit;
}

if($tojiBZNum==3)
{
	$tojiBZNum=$tojiBZNum+1;
	Loader_Redis::common()->hSet($tojiBZkey,$mid,$tojiBZNum);
	
	$fafangjinbi=$fafangjinbi+10000;
	Loader_Redis::common()->set("YYL_FF_".$gameid."_".$keyday,$fafangjinbi);
	Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0, 10000,$desc='yaoyaole3_b');

	$ret['prize']  			= 0;     //是否中奖。5.豹子 2.普通中奖 3.未中奖
	$ret['nowmoney'] 		= 1;  //玩家最新金币
	$ret['xingyunvalue'] 	= 1;          //第一个骰子值
	$ret['steplength'] 		= 0;          //第二个骰子值
	$ret['saizi3']          = 0;          //第三个骰子值
	$ret['flag'] 			= 1;
	$ret['tojiBZNum']      = $tojiBZNum;
	echo json_encode($ret);
	exit;
}

exit;

