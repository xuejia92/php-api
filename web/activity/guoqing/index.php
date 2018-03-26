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

//获取用户金币和乐券
//$userGameInfo = Member::factory()->getGameInfo($mid,false);
//$userInfo     = Member::factory()->getOneById($mid,false);
$paramString = "&gameid=".$gameid."&mid=".$mid."&sid=".$sid."&cid=".$cid."&pid=".$pid."&ctype=".$ctype."&versions=".$versions;

//Loader_Udp::stat()->sendData(42,$mid,$gameid,$ctype,$cid,$sid,$pid,'');

//领取标记
$day1Status = 0;
$day2Status = 0;
$day3Status = 0;
$day4Status = 0;
$day5Status = 0;
$day6Status = 0;
$day7Status = 0;

//获取已经领取的奖励次数
$hadKey = "Guoqing_".$mid;
$hadnum = Loader_Redis::common()->get($hadKey);

//一天只能领一次。
$hadDayKey = "GuoqingDay_".$mid."_".$keyday;
$hadDay    = Loader_Redis::common()->get($hadDayKey);

if(!$hadnum){
	$hadnum = 0;
}

//根据已经领取的次数，得出宝箱的状态
if($hadnum==0){
	$day1Status = 1;
	if($hadDay){
		$day1Status = 2;
	}
}else if($hadnum==1){
	$day1Status = 2;
	$day2Status = 1;
	if($hadDay){
		$day2Status = 0;
	}
}else if($hadnum==2){
	$day1Status = 2;
	$day2Status = 2;
	$day3Status = 1;
	if($hadDay){
		$day3Status = 0;
	}
}else if($hadnum==3){
	$day1Status = 2;
	$day2Status = 2;
	$day3Status = 2;
	$day4Status = 1;
	if($hadDay){
		$day4Status = 0;
	}
}else if($hadnum==4){
	$day1Status = 2;
	$day2Status = 2;
	$day3Status = 2;
	$day4Status = 2;
	$day5Status = 1;
	if($hadDay){
		$day5Status = 0;
	}
}else if($hadnum==5){
	$day1Status = 2;
	$day2Status = 2;
	$day3Status = 2;
	$day4Status = 2;
	$day5Status = 2;
	$day6Status = 1;
	if($hadDay){
		$day6Status = 0;
	}
}else if($hadnum==6){
	$day1Status = 2;
	$day2Status = 2;
	$day3Status = 2;
	$day4Status = 2;
	$day5Status = 2;
	$day6Status = 2;
	$day7Status = 1;
	if($hadDay){
		$day7Status = 0;
	}
}else if($hadnum==7){
	$day1Status = 2;
	$day2Status = 2;
	$day3Status = 2;
	$day4Status = 2;
	$day5Status = 2;
	$day6Status = 2;
	$day7Status = 2;
}


require_once("view/index_".$gameid.".html");
exit;







//用户牌局信息
$paijuinfo="y";

$target1 = 99;
$target2 = 99;

$target3 = 99;//铜锤
//获取用户的牌局信息
if($gameid==1){
	$paijuinfo = Loader_Redis::common()->hGet("sh_winstreak",$mid);
	//$paijuinfo="1010101111110110011111111011111111111111111111111111111111111111";
	$target3 = 2;
	$target1 = 10;
	$target2 = 20;
}else if($gameid==3){
	$paijuinfo = Loader_Redis::rank(3)->hGet("lc_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111111";
	$target3 = 2;
	$target1 = 5;
	$target2 = 10;
}else if($gameid==4){
	$paijuinfo = Loader_Redis::rank(4)->hGet("bf_winstreak",$mid);
	//$paijuinfo="10101011111101100111111110111111111111111111111111111111111";
	$target3 = 2;
	$target1 = 10;
	$target2 = 20;
}
if(!$paijuinfo){
	$paijuinfo="y";
}


//分析牌局信息得到当前连赢局数 和 最高连赢次数
$lianyin = 0;
$maxlianyin = 0;
$paijushu   = 0;
if($paijuinfo && $paijuinfo!="y")
{
	$paijushu = strlen($paijuinfo);
	
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
	}
	
	if($lianyin>$maxlianyin){
		$maxlianyin = $lianyin;
	}
}

//0.未解锁 1.已经解锁 但未使用 2.已经使用
$tCstatus = 0;
$yCstatus = 0;
$jCstatus = 0;

if($paijushu>=$target3){
	$tCstatus = 1;
}

if($paijushu>=$target1){
	$yCstatus = 1;
}

if($paijushu>=$target2){
	$jCstatus = 1;
}

//查看是否已经使用了
$thadKey = "ZD_C_S_T_".$mid."_".$gameid."_".$keyday;
$yhadKey = "ZD_C_S_Y_".$mid."_".$gameid."_".$keyday;
$jhadKey = "ZD_C_S_J_".$mid."_".$gameid."_".$keyday;

if($tCstatus == 1){
	$thad = Loader_Redis::common()->get($thadKey);
	if($thad && $thad > 0){
		$tCstatus = 2;
	}
}

if($yCstatus == 1){
	$yhad = Loader_Redis::common()->get($yhadKey);
	if($yhad && $yhad > 0){
		$yCstatus = 2;
	}
}

if($jCstatus == 1){
	$jhad = Loader_Redis::common()->get($jhadKey);
	if($jhad && $jhad > 0){
		$jCstatus = 2;
	}
}



$tt = "游戏".$target3."局解锁";
$yt = "游戏".$target1."局解锁";
$jt = "游戏".$target2."局解锁";

if($tCstatus == 1){
	$tt = "可使用";
}else if($tCstatus == 2){
	$tt = "已使用";
}

if($yCstatus == 1){
	$yt = "可使用";
}else if($yCstatus == 2){
	$yt = "已使用";
}

if($jCstatus == 1){
	$jt = "可使用";
}else if($jCstatus == 2){
	$jt = "已使用";
}

$tempString = Loader_Redis::common()->get("ZDH_".$mid."_".$gameid);
if(!$tempString){
		$tempString = "";
}
$historyS = "";

//根据不同的游戏生成不同的历史记录样式
$fontcolor = "fff";
$bordercolor = "541409";
if($gameid == 1){
	$fontcolor = "fff";
	$bordercolor = "073e68";
}
if($tempString!=""){
	$arr = explode(",",$tempString);
	
	$tmpNum = 0;
	foreach($arr as $u){
		if($temp<7){
			if($u!=""){
			
				$strarr = explode("@",$u);
				if($strarr[1]==1){
					$mjtmp = "金币";
					$mimage = "sh_xiaojinbi.png";
				}else if($strarr[1]==2){
					$mjtmp = "喇叭";
					$mimage = "xiaolaba.png";
				}
				$s = "<div style='width:98%;padding:10px;color:#".$fontcolor.";font-size:32px;margin:0 auto;margin-top:10px;text-align:left;border-bottom:solid #".$bordercolor.";'><span style='float:left;margin-left:100px;width:320px;'>".$strarr[0]."</span><span style='float:left;margin-left:100px;width:300px;'>砸蛋获得".$mjtmp."奖励</span><img src='http://user.dianler.com/web/activity/zajindan/image/".$mimage."' style='float:left;margin-left:100px;width:44px;height:45px;'/><span style='float:left;margin-left:5px;width:150px;text-align:left;'>".$strarr[2]."</span><div style='clear:both;'></div></div>";
				$historyS = $s.$historyS;
			}
			$tempNum++;
		}
	}
}
require_once("view/index_".$gameid.".html");