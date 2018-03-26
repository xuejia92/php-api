<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "setView":
		$item = Setting_Pmode::factory()->getOne($_GET);
		include 'view/pmode.set.php';
		break;
	case "set":
		$ret  = Setting_Pmode::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Setting_Pmode::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	

	case "updateStatus":
		$return  = Setting_Pmode::factory()->updateStatus($_GET);
		if($return){
			Main_Flag::ret_sucess("更改成功！");
		}else{
			Main_Flag::ret_fail("更改失败！");
		}
		break;		
		
	case "sort":
		$return  = Setting_Pmode::factory()->sort($_POST);
		if($return){
			Main_Flag::ret_sucess("排序成功！");
		}else{
			Main_Flag::ret_fail("排序失败！");
		}
		break;	
	default:
		$items = Setting_Pmode::factory()->get();
		include 'view/pmode.list.php';
		break;
}





