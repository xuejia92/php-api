<?php
!defined('IN WEB') AND exit('Access Denied!');

include 'model/activity.lebaocaicai.php';
$time = NOW;
$now = date('His',$time);
$keyday = date('Hi',$time);
if ($keyday<2000){
    $today = date("ymd",strtotime("-1 days"));
    $yesterday = date("ymd",strtotime("-2 days"));
}else {
    $today = date("ymd",$time);
    $yesterday = date("ymd",strtotime("-1 days"));
}

//与自身活动相关的参数及一些载入逻辑
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$versions = $_REQUEST['versions'];
$mnick = $_REQUEST['mnick'];

$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';


switch ($act){
    case 'buy'://购买
    	$which_type         = $_REQUEST['buy_type'];
		$buy_number         = $_REQUEST['number'];
    	$return['status'] = (int)Activity_Lebaocaicai::factory()->buy($gameid, $mid, $sid, $cid, $pid, $ctype, $money, $today, $buy_number, $which_type);
    	
    	$todayInfo = Activity_Lebaocaicai::factory()->getCurrentInfo($mid, $today);
    	$return['today_count'] = $todayInfo;
    	
    	//获取用户金币数量
		$gameInfo        = Member::factory()->getGameInfo($mid);
		$return['money'] = $gameInfo['money'];
 
        echo json_encode($return);
        break;
        
    case 'get_bonus'://领奖
    	
    	$flag = Activity_Lebaocaicai::factory()->getReward($gameid, $mid, $sid, $cid, $pid, $ctype, $yesterday);
        echo $flag;
        break;
       
    default://默认页面信息
    	
    	$flag = Loader_Redis::common()->get("ac_lebao|".$mid,false,false);
    	
    	if(!$flag){//参与活动人数上报
    		Loader_Udp::stat()->sendData(150, $mid, $gameid, $ctype, $cid, $sid, $pid, 0,1);//上报统计中心
    		Loader_Redis::common()->set("ac_lebao|".$mid,1,false,false,Helper::time2morning());
    	}
    	
    	$lastInfo   = Activity_Lebaocaicai::factory()->getLastInfo($mid, $yesterday);//上一期的中奖信息
    	$todayInfo  = Activity_Lebaocaicai::factory()->getCurrentInfo($mid, $today);//本期购买的信息
    	//var_dump($lastInfo);
    	$rewardFlag = Loader_Redis::common()->get('lb_reward_flag|'.$yesterday.$mid,false,false);//领取奖金状态
    	if($rewardFlag){
    	   $reward = -1;//已领取
    	}else {
    	   $reward = 0;//未领取
    	}
    	extract($lastInfo);//把上期的数组转为变量

    	extract($todayInfo);//把本期的数组转为变量
    	
        require_once 'view/index.php';
        break;
        
}




