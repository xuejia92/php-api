<?php
!defined('IN WEB') AND exit('Access Denied!');

include 'model/activity.jiaochatuiguag.php';

$url = PRODUCTION_SERVER? "http://user.dianler.com/index.php" : "http://utest.dianler.com/ucenter/index.php";

$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$act      = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';
$times    = date('Y-m-d',NOW);

switch ($act){
    case 'set':
        $which = $_REQUEST['which'];
        $rtn = Activity_Jiaochatuiguang::factory()->setFlag($gameid, $mid, $sid, $cid, $pid, $ctype, $times, $which);
        echo json_encode($rtn);
        break;
    
    default:
        $rtn = Activity_Jiaochatuiguang::factory()->getFlag($gameid, $mid, $times);
        extract($rtn);
        include_once 'view/index.php';
        break;
        
}