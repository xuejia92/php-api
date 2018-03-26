<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "setView":
		$gameid      = $_REQUEST['gameid'];
		$aPayChannel = Setting_Msgpay::factory()->getPayChannel();
		$items       = Setting_Msgpay::factory()->get($_GET);
		$item[1]     = $items[$gameid][1] ? $items[$gameid][1] : array();
		$item[2]     = $items[$gameid][2] ? $items[$gameid][2] : array();
		$item[3]     = $items[$gameid][3] ? $items[$gameid][3] : array();
		include 'view/paymsg.set.php';
		break;
	case "set":
		$ret  = Setting_Msgpay::factory()->set($_POST);

		if($ret){
			Main_Flag::ret_sucess("修改成功！");
		}else{
			Main_Flag::ret_fail("修改失败！");
		}
		break;
		
	case "sort":
		
		if($_POST['pid']){
			$ret  = Setting_Msgpay::factory()->sortPidPmode($_REQUEST);
		}else{
			$ret  = Setting_Msgpay::factory()->sort($_REQUEST);
		}

		if($ret){
			Main_Flag::ret_sucess("排序成功！");
		}else{
			Main_Flag::ret_fail("排序失败！");
		}
		break;

	case "sortView":
		$gameid     = $_GET['gameid'];
		$mtype      = $_GET['mtype'];
		$row        = Setting_Msgpay::factory()->get($_GET);
		$paychannel = $row[$gameid][$mtype]['pmode'];
		$aChannel   = explode(',', $paychannel);
		$paychannel = $row[$gameid][$mtype]['name'];
		$aChannelName   = explode(',', $paychannel);
		
		include 'view/paymsg.sort.php';
		break;

	case "sortPidView":
		$pid        = $_GET['pid'];
		$mtype      = $_GET['mtype'];
		$row        = Setting_Msgpay::factory()->getPmodeByPid($pid);
		$aChannel = $row[$mtype]['pmode'];
		$paychannel = $row[$mtype]['name'];
		$aChannelName   = explode(',', $paychannel);
		include 'view/paymsg.sort.php';
		break;	
		
	case "del":
		$ret  = Setting_Msgpay::factory()->del($_REQUEST);

		if($ret){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;		
		
	case "sortallpid":
		$ret  = Setting_Msgpay::factory()->sort2allPid($_REQUEST);

		if($ret){
			Main_Flag::ret_sucess("所有包排序成功！");
		}else{
			Main_Flag::ret_fail("排序失败！");
		}
		break;		
		
	case "getPayChannel":
		$gameid = Helper::uint($_GET['gameid']);
		$mtype  = Helper::uint($_GET['mtype']);
		$paychannel = Setting_Msgpay::factory()->get($_GET);
		$paychannel = $paychannel[$gameid][$mtype]['pmode'];
		$aChannel = explode(',', $paychannel);
		$allChannel = Setting_Msgpay::factory()->getPayChannel();
		
		include 'view/paychannel.php';
		break;
		
	case "getPayChannelByPid":
		$pid    = Helper::uint($_GET['pid']);
		$mtype  = Helper::uint($_GET['mtype']);
		$paychannel = Setting_Msgpay::factory()->getPmodeByPid($pid);
		$aChannel   = $paychannel[$mtype]['pmode'];
		
		if(!$aChannel || !$aChannel[0]){
			$gameid = Helper::uint($_GET['gameid']);
			$paychannel = Setting_Msgpay::factory()->get($_GET);
			$paychannel = $paychannel[$gameid][$mtype]['pmode'];
			$aChannel = explode(',', $paychannel);
		}
		
		//var_dump($paychannel);
		//$aChannel = explode(',', $paychannel);
		$allChannel = Setting_Msgpay::factory()->getPayChannel();
		
		include 'view/paychannel.php';
		break;	
		
	case "getProvice":
		$items  = Setting_Msgpay::factory()->getProvice($_REQUEST);
		$aPmode = Setting_Msgpay::factory()->getPayChannel();
		include 'view/paymsg.provicelist.php';
		break;
		
	case "setProviceView":
		$item   = Setting_Msgpay::factory()->getOnePmodeProvice($_REQUEST);
		$aPmode  = Setting_Msgpay::factory()->getPayChannel();
		include 'view/paymsg.proviceset.php';
		break;

	case "setProvice":
		$ret = Setting_Msgpay::factory()->setProvince($_REQUEST);

		if($ret){
			Main_Flag::ret_sucess("设置成功！");
		}else{
			Main_Flag::ret_fail("设置失败！");
		}
		break;
	case "delProvice":
		$ret = Setting_Msgpay::factory()->delProvice($_GET);
		if($ret){
			Main_Flag::ret_sucess("删除成功！");
		}else{
			Main_Flag::ret_fail("删除失败！");
		}
		break;
		
	default:
		$items = Setting_Msgpay::factory()->get();
		include 'view/paymsg.list.php';
		break;
}





