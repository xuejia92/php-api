<?php
!defined('IN WEB') AND exit('Access Denied!');

include 'model/activity.shengdan.php';

$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$versions = $_REQUEST['versions'];
$mnick    = $_REQUEST['mnick'];
$act      = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';
$times    = date('Y-m-d',NOW);

switch ($act){
    
    case 'exchange':        //兑换袜子
        $type = $_REQUEST['type'];
        $increase = Activity_Shengdan::factory()->exchangeSocks($gameid, $mid, $sid, $cid, $pid, $ctype, $times);
        $return  = Activity_Shengdan::factory()->getCurrenInfo($gameid, $mid, $times) ;
        $return['status']   = $increase;
        echo json_encode($return);
        break;
    
    case 'getMoney':        //兑换金币
        $type   = $_REQUEST['type'];
        $reduce = Activity_Shengdan::factory()->getMoney($gameid, $mid, $sid, $cid, $pid, $ctype, $type, $times);
        $return  = Activity_Shengdan::factory()->getCurrenInfo($gameid, $mid, $times) ;
        $return['status']   = $reduce;
        echo json_encode($return);
        break;
        
    default:
        $possible = Activity_Shengdan::factory()->getCurrenInfo($gameid, $mid, $times) ;
        extract($possible);
        require_once 'view/index.php';
        break;
}