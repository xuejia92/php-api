<?php 
include '../../common.php';
$redirect = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$url = "Location:http://adm.dianler.com/admin/main/login.php?redirect=$redirect";

switch ($_GET['act']) {
	case 'checkLogin':
		$flag = Main_Model::factory()->checkLogin($_POST['username'], $_POST['password']);
		if(!$flag){
			Main_Model::factory()->showMsg("?m=main&p=login&redirect=$redirect","用户名或密码错误");
		}
		header($url);
	break;
	case 'exit':
		Helper::delCookie('dianler_adm');
		header($url);
	break;
	default:
	    $cookie = Helper::getCookie('dianler_adm');
	    if ($cookie){
	        header("Location: ../../admin.php");
	    }else{
	        header($url);
	    }
	break;
}
