<?php !defined('IN WEB') AND exit('Access Denied!');

$aSid  = Base::factory()->getAccountType();
$aCid  = Base::factory()->getChannel();
$aPid  = Base::factory()->getPack();
$aGame = Config_Game::$game;

switch ($_REQUEST['act']) {
	case "getpayrank":
		$items = User_Account::factory()->getPayRank($_REQUEST);
		include 'view/pay.rank.php';
		break;
		
	case "getWinCoinRank":
		$items = User_Account::factory()->rankWinCoin($_REQUEST);
		include 'view/wincoin.rank.php';
		break;

	case "setWinCoinBlasklist":
		$ret = User_Account::factory()->setBlacklist($_REQUEST['mid']);
		if($ret){
			Main_Flag::ret_sucess("设置成功！");
		}else{
			Main_Flag::ret_fail("设置失败！");
		}
		break;

	case "delWinCoinBlasklist":
		$ret = User_Account::factory()->delBlacklist($_REQUEST['mid']);
		if($ret){
			Main_Flag::ret_sucess("设置成功！");
		}else{
			Main_Flag::ret_fail("设置失败！");
		}
		break;

	case "getDeviceRank":
		$items = User_Account::factory()->getDeviceRank($_REQUEST['type']);
		if($items){
			foreach ($items as $k=>$item) {
				$items[$k]['deviceno']    = Member::factory()->getDevicenoBymid($item['mid']);
				$items[$k]['mtime']       = json_decode($item['mtime'],true);
				$items[$k]['mactivetime'] = json_decode($item['mactivetime'],true);
				$items[$k]['mentercount'] = json_decode($item['mentercount'],true);
				$dflag                    = Loader_Redis::account()->get(Config_Keys::banAccount($items[$k]['deviceno']),false,false);
				$items[$k]['dflag']       = $dflag === false ? 0 : 1;
				$ipflag = Loader_Redis::account()->get(Config_Keys::banAccount($items[$k]['ip']),false,false);
				$items[$k]['ipflag']      = $ipflag === false ? 0 : 1;
			}
		}
		
		include 'view/device.rank.php';
		break;

	case "getDeviceRankDay":
		$items = User_Account::factory()->getDeviceRankDay($_REQUEST['time'],$_REQUEST['type']);
		if($items){
			foreach ($items as $k=>$item) {
				$items[$k]['deviceno']    = Member::factory()->getDevicenoBymid($item['mid']);
				$items[$k]['mtime']       = json_decode($item['mtime'],true);
				$items[$k]['mactivetime'] = json_decode($item['mactivetime'],true);
				$items[$k]['mentercount'] = json_decode($item['mentercount'],true);
				$dflag                    = Loader_Redis::account()->get(Config_Keys::banAccount($items[$k]['deviceno']),false,false);
				$items[$k]['dflag']       = $dflag === false ? 0 : 1;
				$ipflag = Loader_Redis::account()->get(Config_Keys::banAccount($items[$k]['ip']),false,false);
				$items[$k]['ipflag']      = $ipflag === false ? 0 : 1;
			}
		}
		
		include 'view/deviceday.rank.php';
		break;		
		
	default:
		$items = User_Account::factory()->getRank($_REQUEST);
		include 'view/user.rank.php';
		break;
		
		
}		







