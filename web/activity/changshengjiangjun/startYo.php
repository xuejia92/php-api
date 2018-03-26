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
$mtkey      = $_REQUEST['mtkey']?$_REQUEST['mtkey']:'';
$buttonctype = $_REQUEST['buttonctype'];
$oldmoney=$_REQUEST['oldmoney'];


$target1 	= 999;
$target2 	= 999;
$target3 	= 999;

$doTarget1 	= 0;
$doTarget2 	= 0;
$doTarget3 	= 0;

if($gameid==1){
	$target1 	= 2;
	$target2 	= 5;
	$target3 	= 10;
	
	$doTarget1 	= 2888;
	$doTarget2 	= 6888;
	$doTarget3 	= 28888;
}else if($gameid == 3){
	$target1 	= 2;
	$target2 	= 5;
	$target3 	= 10;
	
	$doTarget1 	= 2888;
	$doTarget2 	= 12888;
	$doTarget3 	= 88888;

}else if($gameid == 4){
	$target1 	= 2;
	$target2 	= 5;
	$target3 	= 10;
	
	$doTarget1 	= 2888;
	$doTarget2 	= 6888;
	$doTarget3 	= 28888;
	
}else if($gameid == 6){
    
	$target1 	= 2;
	$target2 	= 5;
	$target3 	= 10;
	
	$doTarget1 	= 2888;
	$doTarget2 	= 6888;
	$doTarget3 	= 28888;
}

//用户牌局信息
$paijuinfo="y";
//获取用户的牌局信息
if($gameid==1){
	$paijuinfo = Loader_Redis::common()->hGet("sh_winstreak",$mid);
	//$paijuinfo="1010101111110110011111111011111111111111111111111111111111111111";
}else if($gameid==3){
	$paijuinfo = Loader_Redis::rank(3)->hGet("lc_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111111";
}else if($gameid==4){
	$paijuinfo = Loader_Redis::rank(4)->hGet("bf_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111";
}else if($gameid==6){
	$paijuinfo = Loader_Redis::rank(6)->hGet("tx_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111";
}
if($mid==4057){
	$paijuinfo="10101011111101100111111110111111111111111111111111111111111";
}
if(!$paijuinfo){
$paijuinfo="y";
}

//分析牌局信息得到当前连赢局数 和 最高连赢次数
$lianyin = 0;
$maxlianyin = 0;

if($paijuinfo && $paijuinfo!="y")
{
	
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
		//echo "<br/>";
		//echo $tmp ;
	}
	
	if($lianyin>$maxlianyin){
		$maxlianyin = $lianyin;
	}
}

//三项任务进展状态 0.未达到领取条件 1.可以领取. 2.已经领取
$canLingqu = 0;
$jianglimoney = 0;
if($buttonctype==1){
	if($maxlianyin>=$target1){
		$canLingqu = 1;
		$jianglimoney = $doTarget1;
	}
}else if($buttonctype==2){
	if($maxlianyin>=$target2){
		$canLingqu = 1;
		$jianglimoney = $doTarget2;
	}
}else if($buttonctype==3){
	if($maxlianyin>=$target3){
		$canLingqu = 1;
		$jianglimoney = $doTarget3;
	}
}


//查看是否已经领取
//从redis 获取信息。
$handLingqu = Loader_Redis::common()->get("CSJJ_".$buttonctype."_".$mid."_".$gameid."_".$keyday);
if(!$handLingqu){
	$handLingqu = 1;
}

$userinfo = Member::factory()->getUserInfo($mid,false);
$mnick    = $userinfo['mnick'];
$mnick    = trim($mnick);
$mnick = $mnick ? $mnick : "GT-I9100";

if($canLingqu == 1 && $handLingqu == 1){
	//发货
	//标记领取
	Loader_Redis::common()->set("CSJJ_".$buttonctype."_".$mid."_".$gameid."_".$keyday,2);
	Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$buttonctype,0,$jianglimoney,$desc='lianliansheng');
	$ret['money']  = $jianglimoney;
	
	$tempString = Loader_Redis::common()->get("CSJJH_".$mid."_".$gameid);
	if(!$tempString){
		$tempString = "";
	}
	
	$num = 0;
	if($buttonctype==1){
		$num = $target1;
	}else if($buttonctype==2){
		$num = $target2;
	}else if($buttonctype==3){
		$num = $target3;
	}
	
	$tempString = $tempString.",".$today."@".$num."@".$jianglimoney;
	Loader_Redis::common()->set("CSJJH_".$mid."_".$gameid,$tempString);
	/*
	if($maxlianyin>=$target3 && $gameid==4){
		$tempString = $tempString.",".$today."@".$num."@".$jianglimoney;
		Loader_Redis::common()->set("CSJJH_".$mid."_".$gameid,$tempString);
		$msg      = "系统消息:恭喜".$mnick."轻轻松松连胜".$maxlianyin."场"."收获".$jianglimoney."金币！";//".$mnick."
		Logs::factory()->debug($msg,'zhiqunshengju_horn');
		Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
	}else{
	*/
	//if($gameid != 4){
	//	$tempString = $tempString.",".$today."@".$num."@".$jianglimoney;
	//	Loader_Redis::common()->set("CSJJH_".$mid."_".$gameid,$tempString);
	//	$msg      = "系统消息:恭喜".$mnick."轻轻松松连胜".$maxlianyin."场"."收获".$jianglimoney."金币！";//".$mnick."
	//	Logs::factory()->debug($msg,'zhiqunshengju_horn');
	//	Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
	//}else{
		if($buttonctype == 3){
			
			$msg      = "系统消息:恭喜".$mnick."轻轻松松连胜".$maxlianyin."场"."收获".$jianglimoney."金币！";//".$mnick."
			Logs::factory()->debug($msg,'zhiqunshengju_horn');
			Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
		}
	
	//}
}else{
	echo json_encode($ret);
	exit;
}


//返回结果
$ret['flag'] = 1;

//上报数据
//Loader_Udp::stat()->sendData(65,$mid,$ctype,$cid,$sid,$pid,'');
echo json_encode($ret);
