<?php

include 'model/activity.duanwu.php';

$url = PRODUCTION_SERVER? "http://user.dianler.com/index.php" : "http://utest.dianler.com/ucenter/index.php";
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$time     = date("Y-m-d",NOW);
$tapType  = $_REQUEST['tapType']; 

$prize_array = array(
    '1' => array(
        '1' => array('id'=>1, 'min'=>888, 'max'=>1000, 'chance'=>560),
        '2' => array('id'=>2, 'min'=>1888, 'max'=>2000, 'chance'=>40),
        '3' => array('id'=>3, 'min'=>2888, 'max'=>3000, 'chance'=>0),
        '4' => array('id'=>4, 'min'=>0, 'max'=>0, 'chance'=>400),
        '5' => array('id'=>5, 'min'=>0, 'max'=>0, 'chance'=>0),
    ),
    '2' => array(
        '1' => array('id'=>1, 'min'=>888, 'max'=>1000, 'chance'=>50),
        '2' => array('id'=>2, 'min'=>1888, 'max'=>2000, 'chance'=>440),
        '3' => array('id'=>3, 'min'=>2888, 'max'=>3000, 'chance'=>10),
        '4' => array('id'=>4, 'min'=>0, 'max'=>0, 'chance'=>300),
        '5' => array('id'=>5, 'min'=>0, 'max'=>0, 'chance'=>200),
    ),
    '3' => array(
        '1' => array('id'=>1, 'min'=>888, 'max'=>1000, 'chance'=>250),
        '2' => array('id'=>2, 'min'=>1888, 'max'=>2000, 'chance'=>250),
        '3' => array('id'=>3, 'min'=>2888, 'max'=>3000, 'chance'=>470),
        '4' => array('id'=>4, 'min'=>0, 'max'=>0, 'chance'=>27),
        '5' => array('id'=>5, 'min'=>0, 'max'=>0, 'chance'=>3),
    ),
    '4' => array(
        '1' => array('id'=>1, 'min'=>888, 'max'=>1000, 'chance'=>0),
        '2' => array('id'=>2, 'min'=>1888, 'max'=>2000, 'chance'=>40),
        '3' => array('id'=>3, 'min'=>2888, 'max'=>3000, 'chance'=>460),
        '4' => array('id'=>4, 'min'=>0, 'max'=>0, 'chance'=>200),
        '5' => array('id'=>5, 'min'=>0, 'max'=>0, 'chance'=>300),
    ),
    '5' => array(
        '1' => array('id'=>1, 'min'=>888, 'max'=>1000, 'chance'=>0),
        '2' => array('id'=>2, 'min'=>1888, 'max'=>2000, 'chance'=>0),
        '3' => array('id'=>3, 'min'=>2888, 'max'=>3000, 'chance'=>800),
        '4' => array('id'=>4, 'min'=>0, 'max'=>0, 'chance'=>100),
        '5' => array('id'=>5, 'min'=>0, 'max'=>0, 'chance'=>100),
    ),
);


$act = $_REQUEST['act'];
switch ($act) {
    case 'set':
        $result = Activity_Duanwu::factory()->setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $time, $prize_array, $tapType);
        echo json_encode($result);
        break;

    default:
        $result = Activity_Duanwu::factory()->getPlayInfo($mid, $gameid, $time);
        $tili   = $result[0];
        $ticket = $result[1];
        $cost   = $result[2];
        $one    = $result[3];
        $two    = $result[4];
        $three  = $result[5];
        $four   = $result[6];
        $five   = $result[7];
        $SX     = $result[8];

        Loader_Redis::common()->hSet("duanwuUsernum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
        Loader_Redis::common()->incr("duanwuUserci|$gameid|$ctype|$time", 1, 90*24*3600);
        
        include 'view/index.php';
        break;
}
