<?php
include 'model/activity.wuxingpingjia.php';

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
    
    case 'comment':
        Activity_Wuxingpingjia::factory()->goComments($gameid, $mid, $sid, $cid, $pid, $ctype);
        break;
    
    case 'set':
        $state = Activity_Wuxingpingjia::factory()->setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $time);
        $status = Activity_Wuxingpingjia::factory()->getPlayInfo($mid, $gameid, $ctype, $cid);
        echo $state;

        break;

    default:
        Loader_Redis::common()->hSet("wuxingpingjiaUsernum|$gameid|$ctype|$time", $mid, 1, false, false, 90*24*3600);
        Loader_Redis::common()->incr("wuxingpingjiaUserci|$gameid|$ctype|$time", 1, 90*24*3600);
        
        $status     = Activity_Wuxingpingjia::factory()->getPlayInfo($mid, $gameid, $ctype, $cid);
        $action     = Loader_Redis::common()->hGet('activity_wuxingpingjia_config', $gameid, false, true);
        $activity_anotherGameCid = Loader_Redis::common()->hGet('another_game_cid', $gameid, false, true);
        $version    = $action[$ctype];
        $bonus      = $action['bonus'];
        if (in_array($cid, $activity_anotherGameCid)){
            $address    = $action['url'][$cid];
        }else {
            $address    = $action['url'][0];
        }

        include "view/index$gameid.php";
        break;
}