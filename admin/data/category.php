<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "setView":
		$item = Data_Category::factory()->getOne($_GET);
		include 'view/category.set.php';
		break;
	case "set":
		$ret  = Data_Category::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Data_Category::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	
	default:
		$items = Data_Category::factory()->get();
		include 'view/category.list.php';
		break;
}





