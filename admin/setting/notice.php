<?php !defined('IN WEB') AND exit('Access Denied!');

$aCid = Main_Model::factory()->getCid();

switch ($_REQUEST['act']) {
	case "setView":
		$item  = Setting_Notice::factory()->getOne($_GET);
		$aGame = $item['gameid'] ? explode(',', $item['gameid']) : array();
		include 'view/notice.set.php';
		break;
	case "set":
		$ret  = Setting_Notice::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Setting_Notice::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	
	default:
		$items = Setting_Notice::factory()->get();
		include 'view/notice.list.php';
		break;
}





