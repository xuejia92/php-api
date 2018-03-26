<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "setView":
		$item = Setting_behavior::factory()->getOne($_GET);
		include 'view/behavior.set.php';
		break;
	case "set":
		$ret  = Setting_behavior::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("操作成功！");
		}else{
			Main_Flag::ret_fail("操作失败！");
		}
		break;

	case "del":
		$return  = Setting_behavior::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	
	default:
		$items = Setting_behavior::factory()->get();
		include 'view/behavior.list.php';
		break;
}





