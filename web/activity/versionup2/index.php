<?php
include 'model/activity.versionup.php';

$url = PRODUCTION_SERVER? "http://user.dianler.com/index.php" : "http://utest.dianler.com/ucenter/index.php";
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$mnick    = $_REQUEST['mnick'];
$money    = $_REQUEST['money'];
$versions = $_REQUEST['versions'];
$time     = date("Y-m-d",NOW);

$act = $_REQUEST['act'];
switch ($act) {
    case 'set':
        $state = Activity_Versionup::factory()->setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $versions, $time);
        $status = Activity_Versionup::factory()->getPlayInfo($mid, $gameid, $ctype, $versions);
        echo $state;       
        
        break;

    default:
        $status = Activity_Versionup::factory()->getPlayInfo($mid, $gameid, $ctype, $versions, $time);
        $action = Loader_Redis::common()->hGet('activity_versionup_config', $gameid, false, true);
        $version = $action[$ctype];
        $bonus  = $action['bonus'];
        
        Loader_Redis::common()->hSet("versionupUsernum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
        Loader_Redis::common()->incr("versionupUserci|$gameid|$ctype|$time", 1, 90*24*3600);
        
        include "view/index$gameid.php";
        break;
}