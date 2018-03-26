<?php 

/**
 * admin 入口文件
 * @author GaifyYang
 */
include 'common.php';

$mod  = $_REQUEST['m'] ? trim($_REQUEST['m']) : '';
$page = $_REQUEST['p'] ? trim($_REQUEST['p']) : '';

$mod  = $_REQUEST['m'] ? $_REQUEST['m'] : 'main';
$page = $_REQUEST['p'] ? $_REQUEST['p'] : 'sh';

$username = Helper::getCookie('sp_username');

if(!$username){
	header("Location: admin/main/sh.php?act=login");
	die();
}

if($username=='showhand' ){//特定用户转到看底牌页面
	$page = 'sh';
}else{
	header("Location: admin/main/sh.php?act=login");
}



$file =  ADMIN_PATH. "{$mod}/{$page}.php";

if(!is_file($file))
{
    exit('file is not exists...');
}

require_once($file);