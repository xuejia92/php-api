<?php
$dirName = dirname(dirname(__FILE__));

include $dirName.'/common.php';
set_time_limit(0);

$timehi = date('Hi');
$rankType = array('money','roll');


if($timehi % 10 == 0){
	$stime = microtime(true);
	Member::factory()->setRankList(0);
	$etime = microtime(true);
	$crosstime = $etime - $stime;
	Logs::factory()->debug($crosstime,'rank_cross_time');
}


if($timehi == "0506"){
	foreach ($rankType as $type) {
		foreach (Config_Game::$game as $gameid=>$gamename) {
			Member::factory()->setRankList($gameid,100,$type);
		}
	}
}


if($timehi % 50 == 0){
	$stime = microtime(true);
	$aType = array('deviceno','ip');
	foreach ($aType as $type) {
		Member::factory()->rankDevice($type);
	}
	$etime     = microtime(true);
	$crosstime = $etime - $stime;
	Logs::factory()->debug($crosstime,'rank_device_time');
}


if($timehi % 40 == 0){
	$stime = microtime(true);
	$aType = array('deviceno','ip');
	$date  = date("Y-m-d",NOW);
	foreach ($aType as $type) {
		Member::factory()->rankDeviceBydate($date,$type);
	}
	$etime     = microtime(true);
	$crosstime = $etime - $stime;
	Logs::factory()->debug($crosstime,'rank_device_bydate_time');
}









