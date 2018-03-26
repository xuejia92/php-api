<?php 
!defined('IN WEB') AND exit('Access Denied!');

$ip = Helper::getip();
$ip_arr = Lib_Ip::find($ip);

$param['gameid']    = Helper::uint($_REQUEST['gameid']);
$param['mid']       = Helper::uint($_REQUEST['mid']);
$param['sid']       = Helper::uint($_REQUEST['sid']);
$param['cid']       = Helper::uint($_REQUEST['cid']);
$param['pid']       = Helper::uint($_REQUEST['pid']);
$param['ctype']     = Helper::uint($_REQUEST['ctype']);
$param['lang']      = Helper::uint($_REQUEST['lang']);
$param['versions']  = Loader_Mysql::DBMaster()->escape($_REQUEST['versions']);
$param['mtkey']     = Loader_Mysql::DBMaster()->escape($_REQUEST['mtkey']);
$param['device_no'] = Loader_Mysql::DBMaster()->escape($_REQUEST['device_no']);
$paramString = '';

foreach($param as $key=>$value){
	$paramString = $paramString.'&'.$key.'='.$value;
}
$userGameInfo = Member::factory()->getGameInfo($param['mid'],false);
$userInfo     = Member::factory()->getOneById($param['mid'],false);

$paramString  = $paramString.'&mnick='.urlencode($userInfo['mnick'])."&money=".$userGameInfo['money'];

$showAty = array();
$showAty = Activity_Manager::getActivityList($param);

//统计不同游戏
$time   = date("Y-m-d");
Loader_Redis::common()->hIncrBy('total_'.$param['gameid'].'_'.$time, $param['mid'], 1);
Loader_Redis::common()->setTimeout('total_'.$param['gameid'].'_'.$time, 30*24*3600);

//根据活动的数目来决定显示的页面0：显示暂无活动。1：直接显示活动页面。2以及2个以上:列表形式展现
$num = count($showAty);

if($num == 0){
	require_once('noactivity.php');
}else if($num == 1){
	$url = ($showAty[0]['url']).$paramString;
	header("Location:$url"); /* Redirect browser */
}else{
	require_once('list_'.$param['gameid'].'.php');
}

