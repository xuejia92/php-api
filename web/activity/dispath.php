<?php

/**
 * 活动dispatch器
 * @author GaifyYang
 */
include 'common.php';

//活动需要的公共数据
$gameid     = Helper::uint($_REQUEST['gameid']);
$mid        = Helper::uint($_REQUEST['mid']);
$sid        = Helper::uint($_REQUEST['sid']);
$cid        = Helper::uint($_REQUEST['cid']);
$pid        = Helper::uint($_REQUEST['pid']);
$ctype      = Helper::uint($_REQUEST['ctype']);
$versions   = Loader_Mysql::DBMaster()->escape($_REQUEST['versions']);
$mtkey      = Loader_Mysql::DBMaster()->escape($_REQUEST['mtkey']);

//活动所在目录
$fpath  = $_REQUEST['fpath'] ? $_REQUEST['fpath'] : "";
$action = $_REQUEST['action'] ? $_REQUEST['action'] : "index";

$userInfoKey = Config_Keys::getUserInfo($mid);
$server_mtkey = Loader_Redis::minfo($mid)->hGet($userInfoKey, 'mtkey');

if($server_mtkey != $mtkey ){
	Logs::factory()->debug($_REQUEST,'mtkey_action_error');
	header("Content-Type: text/html;charset=utf-8");
	echo "<script type='text/javaScript'>alert('非法请求 !');</script>";
	
}else {
	
    $file =  WEB_PATH. "activity/{$fpath}/{$action}.php";
    
    if(!is_file($file))
    {
        exit('file is not exists...');
    }
    require_once($file);
}
