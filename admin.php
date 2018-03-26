<?php 

/**
 * admin 入口文件
 * @author GaifyYang
 */
include 'common.php';

$mod  = $_REQUEST['m'] ? trim($_REQUEST['m']) : '';
$page = $_REQUEST['p'] ? trim($_REQUEST['p']) : '';

$mod  = $_REQUEST['m'] ? $_REQUEST['m'] : 'main';
$page = $_REQUEST['p'] ? $_REQUEST['p'] : 'index';

$file =  ADMIN_PATH. "{$mod}/{$page}.php";

if(!is_file($file))
{
    exit('file is not exists...');
}

$username = Helper::getCookie('uc_username');

if(!$username){
	header("Location: admin/main/login.php");
	die();
}

$act      = $_REQUEST['act'];

if(strpos($_SERVER['HTTP_REFERER'], "login.php")){
	$act = "login";
}

if($act && !strrpos($act,"get") && !strrpos($act,"detail") && !strrpos($act,"main") && $act !='showact'){
	Main_Model::factory()->setAdminLog($username, $mod, $page, $act, $_SERVER['QUERY_STRING'], Helper::getip());
}

Setting_Privilege::factory()->checkPrivilege($username,$mod,$act,$page);

require_once($file);