<?php 
!defined('IN WEB') AND exit('Access Denied!');


$time  	= time();
$today 	= date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);


//点击进入活动统计 根据不同游戏来统计
$tojikey = "total_CSJJ";
$time  = time();
$today = date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);
if($_REQUEST['gameid'] == 1){
		$tojikey = $tojikey."_1";
}else if($_REQUEST['gameid'] == 3){
		$tojikey = $tojikey."_3";
}else if($_REQUEST['gameid'] == 4){
		$tojikey = $tojikey."_4";
}else if($_REQUEST['gameid'] == 6){
		$tojikey = $tojikey."_6";
}

$tojikey = $tojikey."_".$keyday;

$tojiNum = Loader_Redis::common()->hGet($tojikey,$_REQUEST['mid']);
//hset hash_key field_name field_value
if(!$tojiNum){
	$tojiNum = 0;
}

$tojiNum = $tojiNum+1;

Loader_Redis::common()->hSet($tojikey,$_REQUEST['mid'],$tojiNum);

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

//Loader_Udp::stat()->sendData(42,$mid,$gameid,$ctype,$cid,$sid,$pid,'');

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
//echo "lianyin ".$lianyin."<br/>";
//echo "maxlianyin ".$maxlianyin."<br/>";

//三项按钮进展状态 1.领取 2.已经领取
$status1 	= 1;
$status2 	= 1;
$status3 	= 1;

//查看是否已经领取
//从redis 获取信息。
$handLingqu1 = Loader_Redis::common()->get("CSJJ_1_".$mid."_".$gameid."_".$keyday);
if($handLingqu1){
	$status1 = 2;
}

$handLingqu2 = Loader_Redis::common()->get("CSJJ_2_".$mid."_".$gameid."_".$keyday);
if($handLingqu2){
	$status2 = 2;
}

$handLingqu3 = Loader_Redis::common()->get("CSJJ_3_".$mid."_".$gameid."_".$keyday);
if($handLingqu3){
	$status3 = 2;
}

$tempString = Loader_Redis::common()->get("CSJJH_".$mid."_".$gameid);
if(!$tempString){
		$tempString = "";
}

$historyS = "";
if($tempString!=""){
	$arr = explode(",",$tempString);
	
	$tmpNum = 0;
	foreach($arr as $u){
		if($temp<7){
			if($u!=""){
				$strarr = explode("@",$u);
				$s = "<div style='width:98%;padding:10px;color:#ffbd80;font-size:32px;margin:0 auto;margin-top:10px;text-align:left;border-bottom:solid #5d4437;'><span style='float:left;margin-left:100px;width:320px;'>".$strarr[0]."</span><span style='float:left;margin-left:100px;width:300px;'>领取连胜".$strarr[1]."场奖励</span><img src='http://user.dianler.com/web/activity/changshengjiangjun/image/sh_xiaojinbi.png' style='float:left;margin-left:100px;width:44px;height:45px;'/><span style='float:left;margin-left:5px;width:150px;text-align:left;'>".$strarr[2]."</span><div style='clear:both;'></div></div>";
				$historyS = $s.$historyS;
			}
			$tempNum++;
		}
	}
}
//require_once('view/index.html');
require_once('view/index_'.$gameid.'.html');