<?php

include 'model/activity.yangnianhongbao.php';

$url = PRODUCTION_SERVER? "http://user.dianler.com/index.php" : "http://utest.dianler.com/ucenter/index.php";
$gameid	  =	$_REQUEST['gameid'];
$mid	  =	$_REQUEST['mid'];
$sid	  =	$_REQUEST['sid'];
$cid	  =	$_REQUEST['cid'];
$pid	  =	$_REQUEST['pid'];
$ctype	  =	$_REQUEST['ctype'];
$mnick    = $_REQUEST['mnick'];
$time     = date("Y-m-d",NOW);

$prize_arr = array(
    '1' => array(
        '0' => array('id'=>1,'min'=>0,'max'=>0,'prize'=>'一等奖','v'=>0),
        '1' => array('id'=>2,'min'=>0,'max'=>0,'prize'=>'二等奖','v'=>0),
        '2' => array('id'=>3,'min'=>5000,'max'=>18888,'prize'=>'三等奖','v'=>15),
        '3' => array('id'=>4,'min'=>666,'max'=>2000,'prize'=>'四等奖','v'=>85)
    ),
    '2' => array(
        '0' => array('id'=>1,'min'=>0,'max'=>0,'prize'=>'一等奖','v'=>0),
        '1' => array('id'=>2,'min'=>0,'max'=>0,'prize'=>'二等奖','v'=>0),
        '2' => array('id'=>3,'min'=>5000,'max'=>18888,'prize'=>'三等奖','v'=>25),
        '3' => array('id'=>4,'min'=>666,'max'=>2000,'prize'=>'四等奖','v'=>75)
    ),
    '3' => array(
        '0' => array('id'=>1,'min'=>0,'max'=>0,'prize'=>'一等奖','v'=>0),
        '1' => array('id'=>2,'min'=>0,'max'=>0,'prize'=>'二等奖','v'=>0),
        '2' => array('id'=>3,'min'=>5000,'max'=>18888,'prize'=>'三等奖','v'=>85),
        '3' => array('id'=>4,'min'=>666,'max'=>2000,'prize'=>'四等奖','v'=>15)
    )
);

$act        = $_REQUEST['act'];
$tapType    = $_REQUEST['tapType'];

switch ($act){
    case 'go':
        $result = Activity_Yangnianhongbao::factory()->setPlayInfo($gameid, $mid, $sid, $cid, $pid, $ctype, $tapType, $prize_arr, $time);
        echo json_encode($result);
        break;

    default:
        Loader_Redis::common()->hSet("yangnianhongbaoRenshu|$time", $mid, 1, false, false, 15*24*3600);       //记录进入活动人数
        Loader_Redis::common()->incr("yangnianhongbaoRenci|$time", 1, 15*24*3600);                            //记录进入活动次数
        
        $result     = Activity_Yangnianhongbao::factory()->getPlayInfo($mid, $time);
        $chongzhi   = $result['chongzhi'];
        $liuyuan    = $result['liuyuan'];
        $shieryuan  = $result['shieryuan'];
        $wushiyuan  = $result['wushiyuan'];
        //$shengyu    = $result['shengyu'];
        //$cishu      = $result['cishu'];
        
        include_once 'view/index.php';
        break;
}