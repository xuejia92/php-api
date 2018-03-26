<?php
include 'model/activity.quanminpaijiang.php';

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
$taptype  = $_REQUEST['taptype'];

$need = array(
    '1' => array(
        '1' => 6,
        '2' => 25,
        '3' => 100
    ),
    '3' => array(
        '1' => 6,
        '2' => 15,
        '3' => 75
    ),
    '4' => array(
        '1' => 6,
        '2' => 20,
        '3' => 35
    )
);

$bonus = array(
    '1' => 888,
    '2' => 1888,
    '3' => 8888
);

$act = $_REQUEST['act'];
switch ($act) {
    case 'set':
        
        $result = Activity_Quanminpaijiang::factory()->setPlayInfo($gameid, $mid, $ctype, $sid, $cid, $pid, $time, $need, $bonus, $taptype);
        echo json_encode($result);
        break;

    default:
        $result = Activity_Quanminpaijiang::factory()->getPlayInfo($gameid, $mid, $ctype, $time);
        $sheng  = $result['sheng'];
        $bronze = $result['bronze'];
        $silver = $result['silver'];
        $gold   = $result['gold'];
        
        Loader_Redis::common()->hSet("quanminpaijiangUsernum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
        Loader_Redis::common()->incr("quanminpaijiangUserci|$gameid|$ctype|$time", 1, 90*24*3600);
        
        include 'view/index.php';
        break;
}