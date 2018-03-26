<?php

include 'model/activity.xingyunlaba.php';

$url = PRODUCTION_SERVER? "http://user.dianler.com/index.php" : "http://utest.dianler.com/ucenter/index.php";
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$mnick    = $_REQUEST['mnick'];
$money    = $_REQUEST['money'];
$time     = date("Y-m-d",NOW);
$userGameInfo 	= Member::factory()->getGameInfo($mid,false);

//抽奖概率控制
$prize_arr = array(
    '1' => array('id'=>1,'prize'=>0,'v'=>680),
    '2' => array('id'=>2,'prize'=>2,'v'=>290),
    '3' => array('id'=>3,'prize'=>4,'v'=>29),
    '4' => array('id'=>4,'prize'=>20,'v'=>1)
);


$act = $_REQUEST['act'];
switch ($act) {
    case 'set':
        
        $result = Activity_Xingyunlaba::factory()->setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $money, $time, $prize_arr);
        echo json_encode($result);
        break;
        
    default:
        
        Loader_Redis::common()->hSet("xingyunlabaUsernum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
        Loader_Redis::common()->incr("xingyunlabaUserci|$gameid|$ctype|$time", 1, 90*24*3600);
        
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
        
        include 'view/index.php';
        break;
}
