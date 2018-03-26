<?php !defined('IN WEB') AND exit('Access Denied!');

$aCid = Base::factory()->getChannel();

/*
$all_switch_pids = Loader_Redis::common()->get("allswitch_config");
$IOSCheckPids    = $all_switch_pids['closepid'];

$yule_switch_pid = Loader_Redis::common()->get("yule_config");
$yulePids        = $yule_switch_pid['closepid'];

$firstpay_limit_pid = Loader_Redis::common()->get("firstpaylimit_config");
$firstPayPids       = $firstpay_limit_pid['closepid'];

$lianyun_switch_pid = Loader_Redis::common()->get("lianyun_config");
$otherGamePids      = $lianyun_switch_pid['closepid'];

$otherpay_pid   = Loader_Redis::common()->get("wangyezhifu_config");
$otherPayPids   = $otherpay_pid['closepid'];

$bankruptpay_switch_pid   = Loader_Redis::common()->get("bankruptpay_config");
$bankruptpayPids          = $bankruptpay_switch_pid['closepid'];

$msgpay_pid               = Loader_Redis::common()->get("msgpay_config");
$msgPayPids               = $msgpay_pid['closepid'];
*/

switch ($_REQUEST['act']) {
	case "setView":
		$switch = Setting_Pid::factory()->getSwitch($_GET['id']);
		$item   = Setting_Pid::factory()->getOne($_GET);
		$aPay   = Setting_Msgpay::factory()->getPmodeByPid($_GET['id']);
		include 'view/pid.set.php';
		break;
	case "set":
		$ret  = Setting_Pid::factory()->set($_POST);
		
		if($ret > 1 ){
			$_POST['id'] = $ret;
		}
		
		Setting_Msgpay::factory()->setPmodeByPid($_POST);

		//$_POST['pid'] = $data['id'];
		Setting_Msgpay::factory()->sort2allPid($_POST);
		
		Setting_Pid::factory()->setSwitch($_POST['id'], $_POST['switch']);
		
		if($ret){
			Main_Flag::ret_sucess("操作成功！");
		}else{
			Main_Flag::ret_fail("操作失败！");
		}
		break;

	case "del":
		$return  = Setting_Pid::factory()->del($_GET);
		if($return){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;	
	default:
		$items = Setting_Pid::factory()->get($_REQUEST);
		
		foreach ($items as $item) {
			$aPay[$item['id']]  = Setting_Msgpay::factory()->getPmodeByPid($item['id']);
		}

		include 'view/pid.list.php';
		break;
}





