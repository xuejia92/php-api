<?php

include 'model/activity.yangniansongfu.php';

$url = PRODUCTION_SERVER? "http://user.dianler.com/index.php" : "http://utest.dianler.com/ucenter/index.php";
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$time     = date("Y-m-d",NOW);

$gameConfig = array(
    '1' => array(1=>5, 2=>10, 3=>15, 4=>20, 5=>30, 6=>30),
    '3' => array(1=>5, 2=>10, 3=>15, 4=>20, 5=>30, 6=>30),
    '4' => array(1=>10, 2=>20, 3=>30, 4=>40, 5=>60, 6=>60)
);

$prize_arr = array(
    '0' => array('id'=>1,'prize'=>'6','v'=>0),
    '1' => array('id'=>2,'prize'=>'100','v'=>0),
    '2' => array('id'=>3,'prize'=>'8888','v'=>25),
    '3' => array('id'=>4,'prize'=>'888','v'=>75)
);

$act = $_REQUEST['act'];

switch ($act){
    
    case 'go':
        $return = Activity_Yangniansongfu::factory()->setPlayInfo($mid, $gameid, $sid, $cid, $pid, $ctype, $gameConfig, $prize_arr, $time);
        echo json_encode($return);
        break;
        
    default: 
        Loader_Redis::common()->hSet("yangniansongfuRenshu|$time|$gameid", $mid, 1, false, false, 15*24*3600);       //记录进入活动人数
        Loader_Redis::common()->incr("yangniansongfuRenci|$time|$gameid", 1, 15*24*3600);                            //记录进入活动次数
        
        $rtn    = Activity_Yangniansongfu::factory()->getPlayInfo($mid, $gameid, $ctype, $gameConfig, $time);
        extract($rtn);
        include 'view/index.php';
        break;
}
