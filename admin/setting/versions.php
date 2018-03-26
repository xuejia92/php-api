<?php !defined('IN WEB') AND exit('Access Denied!');

$aSid = Main_Model::factory()->getSid();

switch ($_REQUEST['act']) {
	case "setView":
		$item = Setting_Versions::factory()->getOne($_GET);

		include 'view/versions.set.php';
		break;
	case "set":
		$ret  = Setting_Versions::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Setting_Versions::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	
	default:
		$items = Setting_Versions::factory()->get();
		include 'view/versions.list.php';
		break;
}





