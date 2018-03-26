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
//话费金币抽奖
$useMoney = $_REQUEST['useMoney'];

$canLa = 1;
//当天已经领取的次数
$hadLa = Loader_Redis::common()->get("hadLa".$mid.$keyday);
if(!$hadLa){
	$hadLa = 0;
}

//得出当前可拉次数
$canLa = $canLa-$hadLa;


//第一次的时候弹出提示告知玩家接下来都是消耗金币 询问玩家是否继续
if($hadLa>0 && !$useMoney ){
	$ret['money']  = $useMoney;
	echo json_encode($ret);
	exit;
}

$oldmoney=$_REQUEST['oldmoney'];

$huishoujinbi = Loader_Redis::common()->get("huishoujinbi".$keyday);
if(!$huishoujinbi){
	$huishoujinbi = 0;
}
$fafangjinbi = Loader_Redis::common()->get("fafangjinbi".$keyday);
if(!$fafangjinbi){
	$fafangjinbi = 0;
}

$fafanglaba = Loader_Redis::common()->get("fafanglaba".$keyday);
if(!$fafanglaba)
{	
	$fafanglaba = 0;
}
//$hadLa>0 表示为金币拉取 则要扣除玩家金币
if($hadLa>0){
	if(1000<=$oldmoney){
		$huishoujinbi=$huishoujinbi+1000;
		Loader_Redis::common()->set("huishoujinbi".$keyday,$huishoujinbi);
		Logs::factory()->addWin($gameid,$mid,19,$sid, $cid, $pid,$ctype,1,1000,$desc='yaoyaole');
	}else{
		$ret['messid'] = 1;
		echo json_encode($ret);
		exit;
	}

}

//此刻头所在的位置  根据这个位置算出需要多少步回归到1这个位置。
$lhead = $_REQUEST['lhead'];
$backToFirst = 17-$lhead;

//奖励物品 0 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15

//实际奖励物品为;2,3,4,6,7,10,11,14
$jArray = array(2=>600,3=>3000,4=>1000,6=>100,7=>300,10=>102,11=>8000,14=>103);
$jArray = array(0=>388,2=>688,3=>3000,4=>1000,6=>188,7=>266,10=>102,11=>8000,12=>104,13=>466,14=>103);
$randNum = 6;

//hadLa = 0 的时候 一定是当天免费抽取 hadLa>=1就是在用金币拉取 (免费抽取只能抽到100 和 300 金币)
if($hadLa == 0)
{
	$temp2 = rand(1,100);
	if($temp2>=1 && $temp2<=25)
	{
		$randNum = 6;
	}else if($temp2>=26 && $temp2<=50){
		$randNum = 7;
	}else if($temp2>=51 && $temp2<=75){
		$randNum = 0;
	}else if($temp2>=76 && $temp2<=100){
		$randNum = 13;
	}
}else if($hadLa==1){//1.如果是用金币第一次抽 直接奖励就是3000金币
	$randNum = 3;
}else if($hadLa>1){ 
	
	$nowxingyunvalue = $hadLa%10;
	if($nowxingyunvalue==0){
		$nowxingyunvalue = 10;
	}
		
	//if($nowxingyunvalue <10){//2.如果是2到9次 则在 600 1000 2个小喇叭 3000金币 中随机  3000的概率为1%  1000的概率为 20%  2个小喇叭的概率为 40%  600的概率为29%
		
	$temp2 = rand(1,100);
	if($temp2>=1 && $temp2<=59){
		$randNum = 2;//688
	}else if($temp2==60){
		$randNum = 10;//小喇叭*2
	}else if($temp2>=61 && $temp2<=82){
		$randNum = 13;//466
	}else if($temp2>=83 && $temp2<=99){
		$randNum = 4;//1000
	}else if($temp==100){
		$randNum = 3;//3000
	}
	
	if($nowxingyunvalue == 10){//3.如果是第10次满幸运抽 则在 3个喇叭  3000金币 8000金币中随机  8000 1%  3000 44%  3个小喇叭 55%
		
		//查看当前系统发放金币 和 收取的金币数值 如果收取的金币数值-当前系统发放金币数值>30万 则 返回结果在3个喇叭 3000金币中选择 50% 50%
		if($huishoujinbi-$fafangjinbi>=(30*10000)){
			
			$temp2 = rand(1,100);
			if($temp2>=1 && $temp2<=25){
				$randNum = 14;//小喇叭*3
			}else if($temp2==26){
				$randNum = 12;//vip1天
			}else if($temp2>=27 && $temp2<=89){
				$randNum = 4;//1000
			}else if($temp2>=90 && $temp2<=99){
				$randNum = 3;//3000
			}else if($temp2==100){
				$randNum = 11;//8000
			}			
		}else{
			$temp2 = rand(1,100);
			if($temp2>=1 && $temp2<=60){
				$randNum = 4;//1000
			}else if($temp2>=61 && $temp2<=80){
				$randNum = 3;//3000
			}else if($temp2>=81 && $temp2<=99){
				$randNum = 14;//小喇叭*3
			}else if($temp2==100){
				$randNum = 12;//vip1天
			}
			
		}
	}
	
}
	


//保险作用 对特殊物品过滤 小米note 三星s4 50乐券 100乐券 10元话费 30话费
if($randNum==1 ||$randNum==5 || $randNum==8 || $randNum==9  || $randNum==15){
	$randNum = 6;
}

$oneUservipKey = "vip".$mid.$keyday;
/*
if($mid == 131405){
	$randNum = 12;
}
*/
if($randNum == 12){
	$hadVip = Loader_Redis::common()->get($oneUservipKey);
	if(!$hadVip){
		$hadVip = 0;
	}
	if($hadVip!=0){
		$randNum = 4;
	}
}

$prize=$jArray[$randNum];
$ret['steplength'] = $randNum+$backToFirst;
$ret['money']  = $randNum;
$ret['prize']  = $prize;

//标记领取
$hadLa++;
Loader_Redis::common()->set("hadLa".$mid.$keyday,$hadLa);




//发放奖励品
//如果奖励喇叭
if($prize == 102)
{
	$fafanglaba = Loader_Redis::common()->set("fafanglaba".$keyday,$fafanglaba);
	Loader_Redis::account()->hIncrBy(Config_Keys::other($mid), 'horn',2);
}
if($prize == 103)
{
	$fafanglaba = Loader_Redis::common()->set("fafanglaba".$keyday,$fafanglaba);
	Loader_Redis::account()->hIncrBy(Config_Keys::other($mid), 'horn',3);
}


//如果奖励是vip
if($prize == 104)
{
	
	$vipsKey          = "fafang".$keyday;
	//当天已经获得VIP次数
	
	$vipFafang = Loader_Redis::common()->get($vipsKey);
	
	if(!$vipFafang){
		$vipFafang = 0;
	}
	
	

	//if($hadVip==0){
		$hadVip = $hadVip+1;
		Loader_Redis::common()->set($oneUservipKey,$hadVip);
		
		$vipFafang = $vipFafang+1;
		Loader_Redis::common()->set($vipsKey,$vipFafang);
	
		$exptime = 1;
		$vipexptime = Loader_Redis::account()->ttl(Config_Keys::vip($mid));//如果之前购买了VIP，则天数累加
		$vipexptime = Helper::uint($vipexptime) ?  ceil(Helper::uint($vipexptime)/86400) : 0;
		$exptime = $exptime + $vipexptime;

    	Loader_Redis::account()->set(Config_Keys::vip($mid), 1,false,false,24*3600*$exptime);
	//}else{
	//	$prize = 4;
	//}
	//$fafanglaba = Loader_Redis::common()->set("fafanglaba".$keyday,$fafanglaba);
	//Loader_Redis::account()->hIncrBy(Config_Keys::other($mid), 'horn',3);
}

//如果奖励是金币
if($prize != 102 && $prize !=103 && $prize !=104){
	$fafangjinbi=$fafangjinbi+$prize;
	Loader_Redis::common()->set("fafangjinbi".$keyday,$fafangjinbi);
	Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0, $prize,$desc='yaoyaole');
}



//返回结果
$nowmoney = $oldmoney+$prize;


//如果hadLa>0 则表示是金币抽取
if($hadLa>0)
{
	if($prize == 102 || $prize ==103 || $prize ==104){
		$nowmoney = $oldmoney-1000;
	}else{
		$nowmoney = $oldmoney+$prize-1000;
	}
	
}

$ret['nowmoney'] = $nowmoney;

if($hadLa>0)
{
	$ret['canLa'] = 0;
}

if($hadLa == 0){
	$xingyunvalue = 0;
}else{
	$xingyunvalue = $hadLa%10;
	if($xingyunvalue==0){
		$xingyunvalue = 10;
	}
}

$ret['xingyunvalue'] = $xingyunvalue;
$ret['flag'] = 1;


//上报数据
//Loader_Udp::stat()->sendData(65,$mid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);
