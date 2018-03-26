<?php
include '../common.php';

$idfa      = $_GET['idfa'];
$udid      = $_GET['udid'];
$openudid  = $_GET['openudid'];
$os        = $_GET['os'];
$app       = $_GET['app'];
$gameid    = $_GET['gameid'];


Logs::factory()->debug($_REQUEST,'ad');

if(!$os){
	$device_no = $idfa;

	$stime  = strtotime("-6 days");
	$etime  = time();
	$cid    = $_GET['cid'] ? $_GET['cid'] : 112;
	
	$ret = array('message'=>'scuess','status'=>1);
	$flag =1;

	for($date=$stime;$date<=$etime;$date+=86400){ 
		$day   = date("Y-m-d",$date); 
		$key    = "U_DEVICES|$gameid|".$day;
		$status = Loader_Redis::account()->hGet($key, $device_no);
		if($status == 1){
			$flag = 0;
			$ret  = array('message'=>'false','status'=>0);
			break;
		}
	}
	
	if($flag){
		Loader_Udp::stat()->sendData(292, 1000, $gameid, 2, $cid, 102, 1575, Helper::getip());
	}

	die(json_encode($ret));
	
}else{
	Logs::factory()->debug($_REQUEST,'wp');
	if(trim($idfa)){
		$cid    = trim($_GET['cid']);
		$gameid = $_GET['gameid'] ? $_GET['gameid'] : 6;
		$pre    = $cid ? $gameid.'|'.$cid : $gameid;
		$os     = (int)$osv = substr($os , 0, 1);
		$idfa   = $os >= 7 ? $idfa : $udid;
		$mykey = Config_Keys::ad();
		$flag = Loader_Redis::common()->sAdd(Config_Keys::ad(), $idfa.'|'.$pre,false,false);
		if($flag){
			$cid = $cid ? $cid : 112;
			Loader_Udp::stat()->sendData(99, 1000, $gameid, 2, $cid, 102, 1575, Helper::getip());
		}
	}

	$ret = array('message'=>'成功','success'=>true);
	
	die(json_encode($ret));
}







