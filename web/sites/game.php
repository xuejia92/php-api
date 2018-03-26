<?php !defined('IN WEB') AND exit('Access Denied!');

$userInfo = Sites_Model::factory()->account($_POST);

switch ($userInfo) {
	case '-1':
		header("Location: ./");
		break;
	case '-2':
		header("Location: ./");
		break;
	case '-3':
		header("Location: ./");
		break;	
}

$host = 'http://'.$_SERVER['HTTP_HOST'];//为了解决 www.xy30m.com 与xy30m.com 跨域的问题

Helper::setCookie("username", $_POST['username'],30*3600*24);
Helper::setCookie("password", $_POST['password'],30*3600*24);

/**
 * flash参数
 */

$flashver = Helper::versionPlugin(FLASH_VER_PATH);

$urlinfo['gameurl']   = $host.Sites_Config::$gameRoot;//根目录地址
$urlinfo['apiurl']    = $host.Sites_Config::$apiurl;//api 
$urlinfo['flashurl']  = $host.Sites_Config::$flashRoot.$flashver['num'].'/'; //FLASH存放目录
$urlinfo['flashimgurl']  = $host.Sites_Config::$flashImgRoot; //FLASH图片存放目录
$urlinfo['security']  = $host.Sites_Config::$security ;

$oclass               = 'Config_Game'.$userInfo['gameid'];
$userInfo['serverInfo']  = call_user_func_array(array($oclass, "getServerInfo"),array($userInfo['cid']));//server配置

$gameInfo['flashServerIp']   = $userInfo['serverInfo']['hallAddr']['ip'];
$gameInfo['flashServerPort'] = $userInfo['serverInfo']['hallAddr']['port'];
$gameInfo['roomConfig'] = '';

//FLASH本地调试
//$urlinfo['flashurl'] = $host.'/ucenter/statics/landlord_swf/core_up/';;


/**
 * js参数
 */
$GAME = array(
	'userInfo' => $userInfo,
	'urlApp'   => $host.Sites_Config::$appurl,
	'urlRoot'  => $host.Sites_Config::$gameRoot,
 );

include('view/game.php');