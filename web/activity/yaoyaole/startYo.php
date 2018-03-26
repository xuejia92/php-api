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
if($time< strtotime("2014-3-11") || $time > strtotime("2014-06-01")){
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

//获取连续登陆天数
$userInfo     = Member::factory()->getOneById($mid,false);
$accountInfo = Loader_Redis::account()->hMget(Config_Keys::other($mid), array('continuousLoginDay','bankPWD','binding','horn'));
$continuousLoginDay       = $accountInfo['continuousLoginDay'];
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
if(!$hadLa){
	$hadLa = 0;
}

//得出当前可拉次数
$canLa = $canLa-$hadLa;

if($canLa<=0){
  echo json_encode($ret);
  exit;
}

$oldmoney=$_REQUEST['oldmoney'];




//此刻头所在的位置  根据这个位置算出需要多少步回归到1这个位置。
$lhead = $_REQUEST['lhead'];
$backToFirst = 17-$lhead;

//奖励物品 0 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15

//实际奖励物品为0,2,3,4,5,6,7,9,11,12,13,14;
$jArray = array(0=>50,2=>100,3=>100000,4=>500,5=>1000,6=>2000,7=>50,9=>100,11=>500,12=>50,13=>100,14=>500);
$randNum = rand(0,15);

//对特殊物品过滤
if($randNum==1||$randNum==3||$randNum==8||$randNum==10||$randNum==15){
	$randNum = $randNum+1;
	if($randNum>=15){
		$randNum = 0;
	}
}
$prize=$jArray[$randNum];

$ret['steplength'] = $randNum+$backToFirst;
$ret['money']  = $prize+$backToFirst;

$ret['prize']  = $prize;

//标记领取
$hadLa++;
Loader_Redis::common()->set("hadLa".$mid.$keyday,$hadLa);

//加金币
//Member::factory()->setRoll(288,10);
Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0, $prize,$desc='yaoyaole');
//返回结果
$nowmoney = $oldmoney+$prize;
$ret['nowmoney'] = $nowmoney;

$ret['canLa'] = $canLa-1;
$ret['flag'] = 1;


//上报数据
Loader_Udp::stat()->sendData(65,$mid,$gameid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);
