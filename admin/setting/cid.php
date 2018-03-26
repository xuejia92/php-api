<?php !defined('IN WEB') AND exit('Access Denied!');

$aCtype = Config_Game::$clientTyle;

switch ($_REQUEST['act']) {
	case "setView":
		$item = Setting_cid::factory()->getOne($_GET);
		$aGame = $item['gameid'] ? explode(',', $item['gameid']) : array();
		include 'view/cid.set.php';
		break;
	case "set":
		$ret  = Setting_cid::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("增加成功！");
		}else{
			Main_Flag::ret_fail("增加失败！");
		}
		break;

	case "del":
		$return  = Setting_cid::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	
	default:
		$items = Setting_cid::factory()->get($_REQUEST);
		include 'view/cid.list.php';
		break;
}





