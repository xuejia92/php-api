<?php
include '../common.php';

$param['mid']          = $mid          = Helper::uint($_POST['mid']);
$param['act']          = $act          = $_POST['act'];
$sig                   = $_POST['sig'];

$key = "ioserfji!*~fgggg65";

$sig_new = md5($mid.$act.$key);

Logs::factory()->debug($_POST,'set');
if($sig != $sig_new){
	die('-1');
}

switch ($act) {
	case 'money':
		$userInfo = Member::factory()->getOneById($mid);
		$aGameid  = array_keys(json_decode($userInfo['mactivetime'],true));
		$gameid   = $aGameid[0];
		$cid      = $userInfo['cid'];
		$pid      = $userInfo['pid'];
		$sid      = $userInfo['sid'];
		$ctype    = $userInfo['ctype'];
		$wmode    = 36;
		$money    = 2888;
		$flag     = Logs::factory()->addWin($gameid, $mid, $wmode, $sid, $cid, $pid, $ctype, 0, $money);
		break;
	
	default:
		;
	break;
}

die($flag);
