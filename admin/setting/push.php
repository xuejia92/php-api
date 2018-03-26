<?php !defined('IN WEB') AND exit('Access Denied!');

$aCid = Main_Model::factory()->getCid();

switch ($_REQUEST['act']) {
	case "setView":
		$item = Setting_Push::factory()->getOne($_GET);

		include 'view/push.set.php';
		break;
	case "set":
		$ret  = Setting_Push::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Setting_Push::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;
	case "setStatus":
		$return  = Setting_Push::factory()->setStatus($_GET);
		if($return){
			Main_Flag::ret_sucess("操作成功！");
		}else{
			Main_Flag::ret_fail("操作失败！");
		}
		break;		
	default:
		$items = Setting_Push::factory()->get();
		include 'view/push.list.php';
		break;
}





