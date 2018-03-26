<?php
include '../common.php';

if($_GET['act'] == 'cid'){
	$data = $_POST;
	$data['gameid'] = explode(',', $data['gameid']);
	$cid = Setting_Cid::factory()->set($data);
	if($cid !=1){
		echo $cid;
	}
}

if($_GET['act'] == 'pid'){
	$data = $_POST;
	$data['gameid'] = explode(',', $data['gameid']);
	$pid = Setting_Pid::factory()->set($data);
	if($pid !=1){
		echo $pid;
	}
}

if($_GET['act'] == 'sid'){
	$data = $_POST;
	$sid = Setting_Sid::factory()->set($data);
	if($sid !=1){
		echo $sid;
	}
}

if($_GET['act'] == 'wmode'){
	$data = $_POST;
	$wmode = Setting_Wmode::factory()->set($data);
	if(wmode !=1){
		echo wmode;
	}
}

Logs::factory()->debug($_REQUEST,'setcidpid');

if($_GET['act'] == 'pmode'){
	$data = $_POST;
	$pmode = Setting_Pmode::factory()->set($data);
	if($pmode !=1){
		echo $pmode;
	}
}