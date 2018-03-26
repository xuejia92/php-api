<?php !defined('IN WEB') AND exit('Access Denied!');

$userInfo = Sites_Model::factory()->account($_REQUEST);

if(!$userInfo){
	die("用户名或密码错误！");
}

/**
 * flash参数
 */
$urlinfo['gameurl']   = Site_Config::$gameRoot;//根目录地址
$urlinfo['flashurl']  = Site_Config::$flashRoot; //FLASH存放目录
$urlinfo['security']  = Site_Config::$security ;

/**
 * js参数
 */
$GAME = array(
	'userInfo' => $userInfo,
);

//FLASH本地调试
if($_REQUEST['uselocal'] == 188)
{
	$urlinfo['flashurl'] = 'http://chesstest.boyaa.com:90/weibo/core/';
	$urlinfo['xmlurl']   = $urlinfo['flashurl'];
}

include('view/index.php');