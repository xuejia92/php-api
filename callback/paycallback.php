<?php

include '../common.php';

$param['mid']          = $mid          = Helper::uint($_POST['mid']);
$param['gameid']       = $gameid       = Helper::uint($_POST['gameid']);
$param['sid']          = $sid          = Helper::uint($_POST['sid']);
$param['cid']          = $cid          = Helper::uint($_POST['cid']);
$param['pid']          = $pid          = Helper::uint($_POST['pid']);
$param['ip']           = $ip           = $_POST['ip'];
$param['ctype']        = $ctype        = Helper::uint($_POST['ctype']);
$param['pamount']      = $pamount      = floatval($_POST['pamount']);
$param['pexchangenum'] = $pexchangenum = Helper::uint($_POST['pexchangenum']);
$param['pdealno']      = $pdealno      = $_POST['pdealno'];
$param['ptype']        = $ptype        = Helper::uint($_POST['ptype']);
$param['pmode']        = $pmode        = Helper::uint($_POST['pmode']);
$param['viptime']      = $viptime      = Helper::uint($_POST['viptime']);
$param['wmconfig']     = $wmconfig     = $_POST['wmconfig'];
$param['source']       = $source       = $_POST['source'];
$param['versions']     = $versions     = $_POST['versions'];
$sig                   = $_POST['sig'];
$do                    = $_POST['do'];

//$key = "showhand$#!@!#@#$1412";
$key = "dd1234567890";
$sig_new = md5($mid.$sid.$cid.$ctype.$pexchangenum.$pdealno.$ptype.$key);

Logs::factory()->debug($_POST,'pay/callback~');
if($sig != $sig_new){
	die('-1');
}

if($do == 'deliver'){
	$result = Pay::factory()->payOrder($param);
	$result == 1 && Loader_Tcp::callServer2PayNotice($mid)->payNotice($mid, $pdealno);
	error_log("$result");
	die("$result");
}

if($do == 'refund'){
	$result = Pay::factory()->payback($pdealno);
	die("$result");
}

