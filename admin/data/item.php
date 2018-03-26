<?php !defined('IN WEB') AND exit('Access Denied!');

$cats = Data_Category::factory()->getCategory();

switch ($_REQUEST['act']) {
	case "setView":
		$item = Data_Item::factory()->getOne($_GET);
		include 'view/item.set.php';
		break;
	case "set":
		$ret  = Data_Item::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Data_Item::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;

	case "updateStatus":
		$return  = Data_Item::factory()->updateStatus($_GET);
		if($return){
			Main_Flag::ret_sucess("更改成功！");
		}else{
			Main_Flag::ret_fail("更改失败！");
		}
		break;		
		
	case "sort":
		$return  = Data_Item::factory()->sort($_POST);
		if($return){
			Main_Flag::ret_sucess("排序成功！");
		}else{
			Main_Flag::ret_fail("排序失败！");
		}
		break;		
	default:
		$items = Data_Item::factory()->getList($_REQUEST);
		include 'view/item.list.php';
		break;
}





