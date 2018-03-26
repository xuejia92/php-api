<?php
include '../common.php';

$param['mid']          = $mid          = Helper::uint($_POST['mid']);
$param['gameid']       = $gameid       = Helper::uint($_POST['gameid']);
$param['sid']          = $sid          = Helper::uint($_POST['sid']);
$param['cid']          = $cid          = Helper::uint($_POST['cid']);
$param['pid']          = $pid          = Helper::uint($_POST['pid']);
$param['ctype']        = $ctype        = Helper::uint($_POST['ctype']);
$param['gid']          = $gid          = Helper::uint($_POST['gid']);
$param['reqroll']      = $reqroll      = Helper::uint($_POST['reqroll']);
$param['type']         = $type         = Helper::uint($_POST['type']);
$param['addmoney']     = $addmoney     = Helper::uint($_POST['addmoney']);
$sig                   = $_POST['sig'];

$key = "exchangeshowhand$#!@!#@#$1412";
$sig_new = md5($mid.$sid.$cid.$pid.$ctype.$gid.$reqroll.$key);

Logs::factory()->debug($_POST,'exchange');
if($sig != $sig_new){
	die('-1');
}

$gameInfo = Member::factory()->getGameInfo($mid,false);
$roll     = (int)$gameInfo['roll'];
$roll1    = (int)$gameInfo['roll1'];
$totalRoll = $roll+$roll1;
if($reqroll>$totalRoll){
	die('-2');
}

$flag = (int)Logs::factory()->setRoll($gameid,$mid, $sid, $cid, $pid, $ctype,$reqroll);

if($flag){
	if($type == 2){//兑换金币
		Logs::factory()->addWin($gameid, $mid, 18, $sid, $cid, $pid, $ctype, 0, $addmoney);
	}

	//兑换人数
	Loader_Udp::stat()->sendData(39,$mid,$gameid,$ctype,$cid,$sid,$pid,'');
	//兑换金额
	Loader_Udp::stat()->sendData(40,$mid,$gameid,$ctype,$cid,$sid,$pid,'',$reqroll);
	//奖品发放次数
	Loader_Udp::stat()->sendData(41,$mid,$gameid,$ctype,$cid,$sid,$pid,'');
}

//发喇叭
$userinfo = Member::factory()->getUserInfo($mid,false);
$roll2prize = array('500'=>'10','1500'=>'30','2500'=>'50');
$mnick    = $userinfo['mnick'];
$mnick    = trim($mnick);
$mnick = $mnick ? $mnick : "GT-I9100";

if($type == 1){
	$msg      = "系统消息:恭喜".$mnick."轻轻松松兑换".$roll2prize[$reqroll]."元话费！";
}else{
	$msg      = "系统消息:恭喜".$mnick."轻轻松松兑换".$addmoney."金币！";
}

Logs::factory()->debug($msg,'exchange_horn');

if($type !=1){
	Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
}
die("$flag");
