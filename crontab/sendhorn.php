<?php
$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';

$msg = "普天同庆，系统将在12:30、20:30、21:30分别发放1000000金币红包，请大家在游戏中抢红包，（屏幕出现红包、立即点击，仅支持最新版本）";

Loader_Tcp::callServer2Horn()->setHorn($msg,3,10);//type  100 表示发到全部游戏
sleep(8);


$timehi = date('Hi');
if($timehi >= '0000' && $timehi <= '20:00' ){
	return false;
}
	

$mnick = Member::factory()->getMnickByRand();	
$rand = mt_rand(1,3);
$roll2prize = array('1'=>'10','2'=>'30','3'=>'50');

$msg      = "系统消息:恭喜".$mnick."轻轻松松兑换".$roll2prize[$rand]."元话费！";
//Loader_Tcp::callServer2Horn()->setHorn($msg,1,10);//type  100 表示发到全部游戏


$mnick = Member::factory()->getMnickByRand();	
$rand = mt_rand(1,3);
$roll2prize = array('1'=>'10','2'=>'30','3'=>'50');

$msg      = "系统消息:恭喜".$mnick."轻轻松松兑换".$roll2prize[$rand]."元话费！";
//Loader_Tcp::callServer2Horn()->setHorn($msg,4,10);//type  100 表示发到全部游戏
//Loader_Tcp::callServer2Horn()->setHorn($msg);

