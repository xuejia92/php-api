<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "setView":
		$item = Setting_Room::factory()->getOne($_GET);
		include 'view/room.set.php';
		break;
	case "set":
		$ret  = Setting_Room::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("修改成功！");
		}else{
			Main_Flag::ret_fail("修改失败！");
		}
		break;
	default:
		$items = Setting_Room::factory()->get();
		include 'view/room.list.php';
		break;
}





