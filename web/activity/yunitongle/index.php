<?php
include 'model/activity.yunitongle.php';

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
$tapType  = $_REQUEST['tapType'];

$prize_arr = array(
    '1' => array(
        '1' => array('id'=>1,'prize'=>'get','v'=>0),
        '2' => array('id'=>2,'prize'=>'none','v'=>100)
    ),
    '2' => array(
        '1' => array('id'=>1,'prize'=>'get','v'=>0),
        '2' => array('id'=>2,'prize'=>'none','v'=>100)
    ),
    '3' => array(
        '1' => array('id'=>1,'prize'=>'get','v'=>0),
        '2' => array('id'=>2,'prize'=>'none','v'=>100)
    ),
    '4' => array(
        '1' => array('id'=>1,'prize'=>'get','v'=>20),
        '2' => array('id'=>2,'prize'=>'none','v'=>80)
    )
);

$act = $_REQUEST['act'];
switch ($act) {
    case 'set':
        
        $result = Activity_Yunitongle::factory()->setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $money, $time, $prize_arr, $tapType);
        echo json_encode($result);
        break;

    default:

        Loader_Redis::common()->hSet("yunitongleUsernum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
        Loader_Redis::common()->incr("yunitongleUserci|$gameid|$ctype|$time", 1, 90*24*3600);

        $result     = Activity_Yunitongle::factory()->getPlayInfo($gameid, $mid, $ctype, $time);
        $tili       = $result['tili'];
        $lingquNum  = $result['lingquNum'];
        
        include 'view/index.php';
        break;
}