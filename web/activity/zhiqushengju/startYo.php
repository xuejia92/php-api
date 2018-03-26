<?php 
!defined('IN WEB') AND exit('Access Denied!');

$time  = time();
$today = date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);

$ret = array();
$ret['flag']   = 0;  	//返回值
$ret['messid'] = 0;		//错误号
$ret['money']  = 0;	//领取金额

//时间控制 超过这个时间不在进行处理
//if($time< strtotime("2014-3-11") || $time > strtotime("2014-12-30")){
//	echo json_encode($ret);
//	exit;
//}

//与自身活动相关的参数及一些载入逻辑
$gameid=$_REQUEST['gameid'];
$mid=$_REQUEST['mid'];
$sid=$_REQUEST['sid'];
$cid=$_REQUEST['cid'];
$pid=$_REQUEST['pid'];
$ctype=$_REQUEST['ctype'];
$oldmoney=$_REQUEST['oldmoney'];

//$ret['flag']   = 1;
//echo json_encode($ret);
//exit;

$target1 	= 2;
$target2 	= 2;
$target3 	= 2;

//获得
$doTarget1 = Loader_Redis::game()->hGet("landrocket",$mid);
$doTarget2 = Loader_Redis::game()->hGet("landair",$mid);
$doTarget3 = Loader_Redis::game()->hGet("landline",$mid);
$doTarget1 = $doTarget1?$doTarget1:0;
$doTarget2 = $doTarget2?$doTarget2:0;
$doTarget3 = $doTarget3?$doTarget3:0;

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
}else{
	echo json_encode($ret);
	exit;
}

//如果是可以领取状态 那么检测宝箱是否已经领取过
if($status == 2)
{
	//当天已经领取的次数
	$hadLa = Loader_Redis::common()->get("zhiqunshengju_".$mid.$keyday);
	$hadLa = $hadLa?$hadLa:0;
	
	if($hadLa == 1)
	{
		//$status = 3;
		echo json_encode($ret);
		exit;
	}
}

//发货
//标记领取
//$hadLa++;
Loader_Redis::common()->set("zhiqunshengju_".$mid.$keyday,1);


$userinfo = Member::factory()->getUserInfo($mid,false);
$mnick    = $userinfo['mnick'];
$mnick    = trim($mnick);
$mnick = $mnick ? $mnick : "GT-I9100";


$prize = 0;
$temp2 = rand(1,100);
if($temp2>=1 && $temp2<=20)
{
	$prize = 3888;
}else if($temp2>=21 && $temp2<=40){
	$prize = 4666;
}else if($temp2>=41 && $temp2<=60){
	$prize = 5888;
}else if($temp2>=61 && $temp2<=80){
	$prize = 6666;
}else if($temp2>=81 && $temp2<=100){
	$prize = 7888;

}
if($prize == 7888){
	$msg      = "系统消息:恭喜***开启宝箱获得".$prize."金币！";//".$mnick."
	Logs::factory()->debug($msg,'zhiqunshengju_horn');
	Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
}else{
	$tempL = rand(0,12);
	$arrayL = array(
		'0'=>"10元话费",
		'1'=>"30元话费",
		'2'=>"50元话费",
		'3'=>"10乐券",
		'4'=>"50乐券",
		'5'=>"100乐券",
		'6'=>"1000乐券",
		'7'=>"vip1天",
		'8'=>"vip15天",
		'9'=>"vip30天",
		'10'=>"100000金币",
		'11'=>"666666金币",
		'12'=>"888888金币",
		
	);
	$tempR = rand(0,3);
	$arrayR = array(
		'0'=>"江湖传闻",
		'1'=>"百晓生密报",
		'2'=>"天机泄露",
		'3'=>"美美剧透",
	);
	
	$msg      = "系统消息:".$arrayR[$tempR]."***开启活动宝箱获得".$arrayL[$tempL];
	$tempF = rand(1,100);
	if($tempF>20 && $tempF<=25){
		Logs::factory()->debug($msg,'zhiqunshengju_horn');
		Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
	}
}
//$fafangjinbi=$fafangjinbi+$prize;
//Loader_Redis::common()->set("fafangjinbi".$keyday,$fafangjinbi);
Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0, $prize,$desc='zhiqunshengju');



//返回结果
//$nowmoney = $oldmoney+$prize;

$ret['flag'] = 1;
$ret['money']  = $prize;
//上报数据
//Loader_Udp::stat()->sendData(65,$mid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);
