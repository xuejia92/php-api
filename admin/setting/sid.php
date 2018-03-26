<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "setView":
		$item = Setting_Sid::factory()->getOne($_GET);
		include 'view/sid.set.php';
		break;
	case "set":
		$ret  = Setting_Sid::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Setting_Sid::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	
	default:
		$items = Setting_Sid::factory()->get();
		include 'view/sid.list.php';
		break;
}





