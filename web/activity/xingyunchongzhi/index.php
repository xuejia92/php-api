<?php !defined('IN WEB') AND exit('Access Denied!');

include 'model/activity.xingyunchongzhi.php';

$url = PRODUCTION_SERVER? "http://user.dianler.com/index.php" : "http://utest.dianler.com/ucenter/index.php";
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$time     = date("Y-m-d",NOW);
$moneyNum = (int)Loader_Redis::common()->hGet("xingyunchongzhirecord|$time", $mid);//已抽奖次数
$rate     = (float)Loader_Redis::common()->hGet("chongzhibeishu|$time", $mid);//已抽中倍率

//抽奖概率控制
$prize_arr = array(
    '0' => array('id'=>1,'min'=>152,'max'=>208,'prize'=>'3','v'=>0),
    '1' => array('id'=>2,'min'=>32,'max'=>88,'prize'=>'2','v'=>0),
    '2' => array('id'=>3,'min'=>92,'max'=>148,'prize'=>'1.4','v'=>0),
    '3' => array('id'=>4,'min'=>array(0,332),'max'=>array(28,360),'prize'=>'1.3','v'=>20),
    '4' => array('id'=>5,'min'=>212,'max'=>268,'prize'=>'1.2','v'=>45),
    '5' => array('id'=>6,'min'=>272,'max'=>328,'prize'=>'1.1','v'=>35)
);

$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

switch ($act){
    case 'go':
        $result = Activity_Xingyunchongzhi::factory()->getArry($gameid, $mid, $sid, $cid, $pid, $ctype, $time, $prize_arr);
        echo json_encode($result);
        break;
        
    default:
        Loader_Redis::common()->hSet("xingyunrenshu|$time|$gameid|$ctype", $mid, 1, false, false, 15*24*3600);//点击人数统计
        Loader_Redis::common()->incr("xingyundianji|$time|$gameid|$ctype", 1, 15*24*3600);
        include 'view/index.php';
        break;
}
