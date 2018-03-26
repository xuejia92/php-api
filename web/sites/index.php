<?php !defined('IN WEB') AND exit('Access Denied!');

session_start();

$act = $_REQUEST['act'];

switch ($act) {
	case 'register':
		$ret = Sites_Model::factory()->userName($_POST);
		die("$ret");
	break;
	case 'checkUserName':
		Stat::factory()->statActivate(Helper::getip(),3, 0, 4, 69, 102, 1255, 0);//新激活用户（根据device_no去重）
		$ret = Sites_Model::factory()->checkUserName($_POST);
		die("$ret");
	break;
	case 'checkAccount':
		$ret = Sites_Model::factory()->checkAccount($_POST);
		die("$ret");
	break;
	case 'checkIdcode':
		$ret = Sites_Model::factory()->checkIdcode($_POST);
		die("$ret");
	break;
	case 'getPassword':
		$ret = Sites_Model::factory()->getPassword($_POST);
		if( $_POST['type'] == 1 && !in_array($ret,array(-1,-2))){
			$ret = "你的账号信息：用户名：".$ret['username'].',密码：'.$ret['password'];
		}
		
		die("$ret");
	break;
	
	case 'idcode':
		$oo = new Lib_Validate();
		$oo->validate(3, 4, 105, 30, 100, 1,SYS_LIB_PATH.'fonts');
	break;
	
	case 'download':
		include('view/download.php');
	break;
	case 'termsofservice':
		include('view/termsofservice.php');
	break;
	
	default:
		$date = date("Y-m-d");
		$ip = Helper::getip();
		Loader_Redis::common()->hIncrBy("hainan_web|".$date, $ip, 1);
		$username = Helper::getCookie("username");
		$password = Helper::getCookie("password");
		include('view/login.php');
	break;
}

