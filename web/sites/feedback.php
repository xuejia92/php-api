<?php !defined('IN WEB') AND exit('Access Denied!');

$act = $_REQUEST['act'];

if($act == 'view'){
	$gameRoot = Sites_Config::$gameRoot;
	include 'view/feedback.php';
	
}else{
	$mid     = Helper::uint($_REQUEST['mid']);
	$sid     = 102;//Helper::uint($_REQUEST['sid']);//账号类型ID	
	$cid     = 4;//Helper::uint($_REQUEST['cid']);//渠道ID
	$ctype   = 4;//Helper::uint($_REQUEST['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
	$pid     = 1;//Helper::uint($_REQUEST['pid']);//客户端包类型ID
	$mnick   = $_REQUEST['mnick'] ? Loader_Mysql::DBMaster()->escape($_REQUEST['mnick']) : "";	
	$phoneno = $_REQUEST['phoneno'] ? Helper::filterInput($_REQUEST['phoneno']) : '';
	$pic     = $_REQUEST['picurl'] ? Helper::filterInput($_REQUEST['picurl']) : '';
	$content = $_REQUEST['content'] ? Helper::filterInput($_REQUEST['content']) : '';		
	$ip      = Helper::getip();
	$ret['result'] = 0;
	$ret['result'] = (int)Base::factory()->feedBack(3,$cid, $sid, $pid, $ctype, $content, $mid, $mnick, $phoneno, $pic,$ip);		

	echo json_encode($ret);
}



	