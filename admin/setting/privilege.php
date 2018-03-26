<?php !defined('IN WEB') AND exit('Access Denied!');


$login_name = Helper::getCookie('uc_username');
if(!in_array($login_name,array('gary'))){
	return '';
}
		

switch ($_REQUEST['act']) {
	case "setView":
		$item = Setting_Privilege::factory()->getOne($_GET);
		include 'view/privilege.set.php';
		break;
	case "set":
		$ret  = Setting_Privilege::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Setting_Privilege::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	
	default:
		$items = Setting_Privilege::factory()->get();
		include 'view/privilege.list.php';
		break;
}





