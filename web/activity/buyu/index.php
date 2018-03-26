<?php

include 'model/activity.buyu.php';

$url = PRODUCTION_SERVER? "http://user.dianler.com/index.php" : "http://utest.dianler.com/ucenter/index.php";
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$time     = date("Y-m-d",NOW);
$tapType  = $_REQUEST['tapType']; 

$act = $_REQUEST['act'];
switch ($act) {
    case 'set':
        $result = Activity_Buyu::factory()->setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $time, $tapType);
        echo json_encode($result);
        break;

    default:
        $buttonStatus   = Activity_Buyu::factory()->getPlayInfo($mid, $gameid);
        $result         = Activity_Buyu::factory()->getFishingInfo($mid);
        $button1Status  = $buttonStatus[0];
        $button2Status  = $buttonStatus[1];
        $button3Status  = $buttonStatus[2];
        $fish1  = $result[0];
        $fish2  = $result[1];
        $fish3  = $result[2];
        
        Loader_Redis::common()->hSet("buyuUsernum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
        Loader_Redis::common()->incr("buyuUserci|$gameid|$ctype|$time", 1, 90*24*3600);
        
        include 'view/index.php';
        break;
}
