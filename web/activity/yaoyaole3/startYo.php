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


//参与活动的人次统计
//统计不同游戏
$tojikey = "YYL_".$gameid;
$tojikey = $tojikey."_".$keyday;
$tojiNum = Loader_Redis::common()->hGet($tojikey,$mid);
//hset hash_key field_name field_value
if(!$tojiNum){
	$tojiNum = 0;
}
$tojiNum = $tojiNum+1;
Loader_Redis::common()->hSet($tojikey,$mid,$tojiNum);


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





//玩家已经玩了多少次  次数限制
if($tojiNum>100)
{
	echo json_encode($ret);
	exit;
}

//先扣金币
if($useMoney<=$oldmoney){
	$userGameInfo 	= Member::factory()->getGameInfo($mid,false);
	if($useMoney<$userGameInfo['money'])
	{
		$t = Logs::factory()->addWin($gameid,$mid,19,$sid, $cid, $pid,$ctype,1,$useMoney,$desc='yaoyaole3');
	
		if(!$t){
			$ret['messid'] = 7;
			echo json_encode($ret);
			exit;
		}
		
		$huishoujinbi=$huishoujinbi+$useMoney;
		Loader_Redis::common()->set("YYL_HS_".$gameid."_".$keyday,$huishoujinbi);
	}else{
		//金币不足返回
		$ret['messid'] = 1;
		echo json_encode($ret);
		exit;
	}
	
}else{
	//金币不足返回
	$ret['messid'] = 1;
	echo json_encode($ret);
	exit;
}

//玩家开出豹子的次数
$baoziHad 		= 0;


$startNum 		= 0;
$endNum   		= 0;

$baoziStartNum 	= 0;
$baoziEndNum	= 0;
//根据是否开过3次豹子 进行概率的配置
if($baoziHad>=3)
{
	if($moneyIndex==1)
	{
		$startNum = 1;
		$endNum   = 33;
		
		$baoziStartNum 	= 1;
		$baoziEndNum	= 23;
	}
	else if($moneyIndex==2)
	{
		$startNum = 1;
		$endNum   = 34;
		
		$baoziStartNum 	= 1;
		$baoziEndNum	= 36;
	}
	else if($moneyIndex==3)
	{
		$startNum = 1;
		$endNum   = 35;
		
		$baoziStartNum 	= 1;
		$baoziEndNum	= 43;
	}
	else if($moneyIndex==4)
	{
		$startNum = 1;
		$endNum   = 36;
		
		$baoziStartNum 	= 1;
		$baoziEndNum	= 51;
	}
}
else
{
	if($moneyIndex==1)
	{
		$startNum = 1;
		$endNum   = 40;
		
		$baoziStartNum 	= 1;
		$baoziEndNum	= 30;
	}
	else if($moneyIndex==2)
	{
		$startNum = 1;
		$endNum   = 36;
		
		$baoziStartNum 	= 1;
		$baoziEndNum	= 36;
	}
	else if($moneyIndex==3)
	{
		$startNum = 1;
		$endNum   = 35;
		
		$baoziStartNum 	= 1;
		$baoziEndNum	= 43;
	}
	else if($moneyIndex==4)
	{
		$startNum = 1;
		$endNum   = 34;
		
		$baoziStartNum 	= 1;
		$baoziEndNum	= 51;
	}
}




$temp2 = rand(1,100);
$prize = 0;
//中奖
if($startNum!=0 && $endNum!=0 && $temp2>=$startNum && $temp2 <=$endNum)
{
	$temp3 = rand(1,100);
	//可能出豹子
	if($temp3 == 1)
	{
		$temp4 = rand(1,100);
		if($baoziStartNum!=0 && $baoziEndNum!=0 && $temp4>=$baoziStartNum && $temp2 <=$baoziEndNum)
		{
			//豹子中
			if(tojiBZNum<=3){
				$prize = 5;
				$tojiBZNum=$tojiBZNum+1;
				Loader_Redis::common()->hSet($tojiBZkey,$mid,$tojiBZNum);
			}else{
				$prize = 4;
			}
		}
		else
		{	//豹子不中
			$prize = 4;
		}
	}
	else
	{   //普通中
		$prize = 2;
	}
}
else
{
	//普通不中
	$prize = 3;
}
//$prize=5;
//豹子次数限制
//豹子中
if($prize==5){
	if($tojiBZNum<3){
		$tojiBZNum=$tojiBZNum+1;
		Loader_Redis::common()->hSet($tojiBZkey,$mid,$tojiBZNum);
	}else{
		$prize = 4;
	}
}

if($prize==2||$prize==5){
	//金币防刷限制
	$jy=$fafangjinbi+($prize-1)*$useMoney-$huishoujinbi;
	if($jy>2000000)
	{
		$prize = 3;
	}
}

//$prize=$jArray[$randNum];
//标记领取
//$hadLa++;
//Loader_Redis::common()->set("hadLa".$mid.$keyday,$hadLa);


//猜中了发放奖励
if($prize != 1 && $prize != 3 && $prize != 4){
	$fafangjinbi=$fafangjinbi+$prize*$useMoney;
	Loader_Redis::common()->set("YYL_FF_".$gameid."_".$keyday,$fafangjinbi);
	Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0, $useMoney*$prize,$desc='yaoyaole3');
}



//返回结果
//玩家最新金币
if($prize != 1 && $prize != 3 && $prize != 4){	
	$nowmoney = $oldmoney+$useMoney*$prize-$useMoney;
}else{
	$nowmoney = $oldmoney-$useMoney;
}

if($prize != 1 && $prize != 3 && $prize != 4){
	$ret['money']  			= $useMoney*$prize;   //奖励金额
}else{
	$ret['money']  			= 0;   //奖励金额
}





//普通中
if($prize ==2)
{
	if($bigOrSmall==1){//要小出小
		//各个骰子的点数
		$tmpDian1 = rand(1,6);
		//必出小
		$dianEnd  = (10-$tmpDian1)/2;
		$tmpDian2 = rand(1,$dianEnd);
		$tmpDian3 = rand(1,$dianEnd);
		//保护措施摇出的3个点数不为豹子
		if($tmpDian1==$tmpDian2&&$tmpDian1==$tmpDian3)
		{
			//3,6,9 加1 也必定是小
			$tmpDian3=$tmpDian3+1;
		}
	}else if($bigOrSmall==2){//要大出大
		//各个骰子的点数
		$tmpDian1 = rand(1,6);
		//必出大
		$dianEnd  = (12-$tmpDian1)/2;
		$tmpDian2 = rand($dianEnd,6);
		$tmpDian3 = rand($dianEnd,6);
		//保护措施摇出的3个点数不为豹子
		if($tmpDian1==$tmpDian2&&$tmpDian1==$tmpDian3)
		{
			//12,15,18 减1 也必定是大
			$tmpDian3=$tmpDian3-1;
		}
	}
}
	
	
	
	
if($prize ==3)//普通不中  不中的情况下 是可以出豹子的 这个时候在出豹子 就直接表示了 豹子未压中
{
	if($bigOrSmall==1){//要小就出大
		//各个骰子的点数
		$tmpDian1 = rand(1,6);
		//必出大
		$dianEnd  = (12-$tmpDian1)/2;
		$tmpDian2 = rand($dianEnd,6);
		$tmpDian3 = rand($dianEnd,6);
		//保护措施摇出的3个点数不为豹子
		if($tmpDian1==$tmpDian2&&$tmpDian1==$tmpDian3)
		{
			//12,15,18 减1 也必定是大
			//$tmpDian3=$tmpDian3-1;
			$prize ==4;
		}
	}else if($bigOrSmall==2){//要大就出小
		//各个骰子的点数
		$tmpDian1 = rand(1,6);
		//必出小
		$dianEnd  = (10-$tmpDian1)/2;
		$tmpDian2 = rand(1,$dianEnd);
		$tmpDian3 = rand(1,$dianEnd);
		//保护措施摇出的3个点数不为豹子
		if($tmpDian1==$tmpDian2&&$tmpDian1==$tmpDian3)
		{
				//3,6,9 加1 也必定是小
				//$tmpDian3=$tmpDian3+1;
				$prize ==4;
		}
	}
}

//需要出现豹子点数
if($prize ==4){//豹子不中
	if($bigOrSmall==1){//要小就出大
		//出大豹子
		$tmpDian1 = rand(4,6);
		$tmpDian2 =$tmpDian1;
		$tmpDian3 =$tmpDian1;
	}else if($bigOrSmall==2){//要大就出小
		//出小豹子
		$tmpDian1 = rand(1,3);
		$tmpDian2 =$tmpDian1;
		$tmpDian3 =$tmpDian1;
	}
}
	
if($prize ==5){//豹子压中
	if($bigOrSmall==1){//要大就出大
		//出小豹子
		$tmpDian1 = rand(1,3);
		$tmpDian2 =$tmpDian1;
		$tmpDian3 =$tmpDian1;
	}else if($bigOrSmall==2){//要小就出小
		//出大豹子
		$tmpDian1 = rand(4,6);
		$tmpDian2 =$tmpDian1;
		$tmpDian3 =$tmpDian1;
	}
}


$ret['prize']  			= $prize;     //是否中奖。5.豹子 2.普通中奖 3.未中奖
$ret['nowmoney'] 		= $nowmoney;  //玩家最新金币
$ret['xingyunvalue'] 	= $tmpDian1;          //第一个骰子值
$ret['steplength'] 		= $tmpDian2;          //第二个骰子值
$ret['saizi3']          = $tmpDian3;          //第三个骰子值
$ret['flag'] 			= 1;
$ret['tojiBZNum']      = $tojiBZNum;

//上报数据
//Loader_Udp::stat()->sendData(65,$mid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);
