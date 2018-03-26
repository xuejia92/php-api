<?php !defined('IN WEB') AND exit('Access Denied!');

$aSid   = Base::factory()->getAccountType();
$aCid   = Base::factory()->getChannel();
$aPid   = Base::factory()->getPack();
$aGame  = Config_Game::$game;
$aCtype = Config_Game::$clientTyle;

switch ($_REQUEST['act']) {
	case "detail":
		$item = User_Account::factory()->get($_GET);
		$item['iconurl'] = Member::factory()->getIcon($item['sitemid'], $item['mid'],'middle',$_REQUEST['gameid'],$item['sex']);
		$accountInfo    = Loader_Redis::ote($item['mid'])->hGetAll(Config_Keys::other($item['mid']));
		$accountInfo['bat'] == 1 && $item['mstatus'] = 'forever';
		$isvip          = Loader_Redis::account()->get(Config_Keys::vip($item['mid']),false,false);
		$vipday         = Loader_Redis::account()->ttl(Config_Keys::vip($item['mid']));
		$vipday         = ceil($vipday/86400);
		$gag            = Loader_Redis::account()->hGet(Config_Keys::gaghash(), $item['mid']);
		$deviceno       = Member::factory()->getDevicenoBymid($item['mid']);
		$item = array_merge($item,$accountInfo);
		$item['mtime']       = json_decode($item['mtime'],true);
		$item['mactivetime'] = json_decode($item['mactivetime'],true);
		$item['mentercount'] = json_decode($item['mentercount'],true);
		$item['iconblist']   = Loader_Redis::ote($item['mid'])->hGet(Config_Keys::other($item['mid']), 'iconblist');
		include 'view/account.detail.php';
		break;
	case "boardDetail":
		$item        = User_Logplay::factory()->getBoardDetail($_GET);
		$members     = $item[0];
		$mnicks      = $item[1];
		$subPlay     = $item[2];
		$ending      = $item[3];
		$title       = $item[4];
		$others      = $item[5];
		$publicCard  = $item[5];

		include 'view/account.boarddetail.php';
		break;
	case "set":
		$ret  = User_Account::factory()->set($_POST);
		if($ret){
			Main_Flag::ret_sucess("修改成功！");
		}else{
			Main_Flag::ret_fail("修改失败！");
		}
		break;
	case "moneylog":
		$tables      = User_Account::factory()->getTable("log_win".$_REQUEST['gameid']);
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = User_Account::factory()->getMoneyLogCount($_REQUEST);	
		$items       = User_Account::factory()->getMoneyLog($_REQUEST);
		include 'view/account.moneylog.php';
		break;	
		
	case "rolllog":
		$tables = User_Account::factory()->getTable("log_roll".$_REQUEST['gameid']);
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = User_Account::factory()->getRollLogCount($_REQUEST);	
		$items       = User_Account::factory()->getRollLog($_REQUEST);
		include 'view/account.rolllog.php';
		break;	

    case "fishDetail":
        $roomConfig = User_Account::factory()->fishRoomCfg();
        $item       = User_Account::factory()->fishDetail($_GET);
        include 'view/account.fishboarddetail.php';
        break;
		
    case "fishlog":
        $roomConfig= User_Account::factory()->fishRoomCfg();
        $tables = User_Account::factory()->getTable("log_member_fish");
        $numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
        $currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
        $totalCount  = User_Account::factory()->getFishLogCount($_REQUEST);
        $items       = User_Account::factory()->getFishLog($_REQUEST);
        include 'view/account.fishlog.php';
        break;
		
	case "yulelog":
	    $roomConfig = User_RoomConfig::$yulelogRoomConfig;
	    $tables = User_Account::factory()->getTable("log_member".$_REQUEST['gameid']);
	    $numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
	    $currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
	    $totalCount  = User_Account::factory()->getPlayLogCount($_REQUEST);
	    $items       = User_Account::factory()->getPlayLog($_REQUEST);
	    include 'view/account.yulelog.php';
	    break;
		    
	case "playlog":
		$roomConfig = User_RoomConfig::$playlogRoomConfig;
		$tables = User_Account::factory()->getTable("log_member".$_REQUEST['gameid']);
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = User_Account::factory()->getPlayLogCount($_REQUEST);	
		$items       = User_Account::factory()->getPlayLog($_REQUEST);
		include 'view/account.playlog.php';
		break;
		
	case "banklog":
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = User_Account::factory()->getLogBankCount($_REQUEST);	
		$items       = User_Account::factory()->getLogBank($_REQUEST);
		include 'view/account.banklog.php';
		break;	
	
	case "exchangelog":
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = User_Account::factory()->getExchangeCount($_REQUEST);	
		$items       = User_Account::factory()->getExchangelog($_REQUEST);
		include 'view/account.exchangelog.php';
		break;

	case "payment":
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = User_Account::factory()->getPaymentCount($_REQUEST);	
		$items       = User_Account::factory()->getPaymentLog($_REQUEST);
		$aPmode      = Setting_Pmode::factory()->getPmode();
		$aPtype      = array('1'=>'金币','2'=>'乐币','3'=>'会员卡','4'=>'道具','7'=>'黄金炮礼包','8'=>'钻石炮礼包');
		include 'view/account.payment.php';
		break;	
		
	case "resetBankPassword":
		$ret  = User_Account::factory()->resetBankPassword($_POST);

		if($ret){
			Main_Flag::ret_sucess("修改成功！");
		}else{
			Main_Flag::ret_fail("修改失败！");
		}
		break;

	case "setBankpwdView":
		include 'view/account.bankpwd.php';
		break;

	case "setVIPView":
		$vipday         = Loader_Redis::account()->ttl(Config_Keys::vip($item['mid']));
		$vipday         = ceil($vipday/86400);
		include 'view/account.vip.php';
		break;

	case "resetVIP":
		$ret = User_Account::factory()->resetVip($_POST);
		
		if($ret){
			Main_Flag::ret_sucess("修改成功！");
		}else{
			Main_Flag::ret_fail("修改失败！");
		}
		
		break;	
		
	case "resetAccountPassword":
		$ret  = User_Account::factory()->resetAccountPassword($_POST);

		if($ret){
			Main_Flag::ret_sucess("修改成功！");
		}else{
			Main_Flag::ret_fail("修改失败！");
		}
		break;	
		
	case "setAccountpwdView":
		include 'view/account.accountpwd.php';
		break;
		
	case "setGagView":
		include 'view/account.gag.php';
		break;	

	case "setGag":
		$ret  = User_Account::factory()->setGan($_POST);

		if($ret){
			Main_Flag::ret_sucess("修改成功！");
		}else{
			Main_Flag::ret_fail("修改失败！");
		}
		break;
		
	case 'cleanWeixinBinding':
	    $ree = $ret = 0;
	    if ($_REQUEST['openid']){
    	    Loader_Redis::common()->delete('weixinUserBindStatus|'.$_REQUEST['openid']);
    	    $ree  = Loader_Redis::common()->hDel('weixinUserid', $_REQUEST['openid']);
    	    $ret  = Loader_Redis::ote($_REQUEST['mid'])->hDel(Config_Keys::other($_REQUEST['mid']), 'weixinbinding');
	    }
	    if($ree && $ret){
	        Main_Flag::ret_sucess("清除绑定成功！");
	    }else{
	        Main_Flag::ret_fail("清除绑定失败！");
	    }
	    break;
		
    case "getBanDay":
        include 'view/account.banday.php';
        break;

    case "banMid":
        $ret = User_Account::factory()->banMid($_REQUEST);
        if($ret){
            Main_Flag::ret_sucess("封号成功！");
        }else{
            Main_Flag::ret_fail("封号失败！");
        }
        
        break;
        
	case "banDeviceNo":
		$ret  = User_Account::factory()->banDeviceNo($_REQUEST['deviceno']);

		if($ret){
			Main_Flag::ret_sucess("封机器成功！");
		}else{
			Main_Flag::ret_fail("封机器失败！");
		}
		break;
		
	case "banIP":
		$ret  = User_Account::factory()->banIp($_REQUEST['ip']);

		if($ret){
			Main_Flag::ret_sucess("封机器成功！");
		}else{
			Main_Flag::ret_fail("封机器失败！");
		}
		break;	

	case "resetDeviceNo":

		$ret = User_Account::factory()->resetbanDeviceNo($_REQUEST['deviceno']);

		if($ret){
			Main_Flag::ret_sucess("解封成功！");
		}else{
			Main_Flag::ret_fail("解封失败！");
		}
		break;
	
	case "setOptIconView":
		include 'view/account.iconblist.php';
		break;	
		
	case "opticon":
		$mid  = $_REQUEST['mid'];
		$opts = $_POST['opt'];
		$ret = User_Account::factory()->optIcon($mid, $opts);

		if($ret){
			Main_Flag::ret_sucess("操作成功！");
		}else{
			Main_Flag::ret_fail("操作失败！");
		}
		break;		

	case "resetbanIp":

		$ret = User_Account::factory()->resetbanIp($_REQUEST['ip']);

		if($ret){
			Main_Flag::ret_sucess("解封成功！");
		}else{
			Main_Flag::ret_fail("解封失败！");
		}
		break;	

	case "getNewRegister":
		$_REQUEST['gameid']   = $_REQUEST['gameid'] ? $_REQUEST['gameid'] : 1;
		$_REQUEST['ctype']    = $_REQUEST['ctype'];
		$_REQUEST['cid']      = $_REQUEST['cid'];
		$_REQUEST['date']     = $_REQUEST['date']   ? $_REQUEST['date'] : date("Y-m-d",strtotime("-1 days"));
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum']    = max(Helper::uint($_REQUEST['pageNum']),1);

		$totalCount  = User_Account::factory()->getNewRegisterCount($_REQUEST);		
		$items       = User_Account::factory()->getNewRegisterList($_REQUEST);
		
		
		include 'view/newregisterlist.php';
		break;	

	case "getdevices":
		$deviceno = $_REQUEST['deviceno'];
		if($deviceno){
			$mids = Member::factory()->getMidByDeviceno($deviceno);
		}

		if($mids){
			$items = Member::factory()->getAllByIds($mids);
			if($items){
				foreach ($items as $k=>$item) {
					$items[$k]['deviceno']    = $deviceno;
					$items[$k]['mtime']       = json_decode($item['mtime'],true);
					$items[$k]['mactivetime'] = json_decode($item['mactivetime'],true);
					$items[$k]['mentercount'] = json_decode($item['mentercount'],true);
					$items[$k]['dflag']       = (int)Loader_Redis::account()->get(Config_Keys::banAccount($items[$k]['mid']),false,false);
					!$items[$k]['dflag'] && $items[$k]['dflag'] = (int)Loader_Redis::ote($items[$k]['mid'])->hGet(Config_Keys::other($items[$k]['mid']), 'bat');
				}
			}
		}

		include 'view/account.devices.php';
		break;

	case "getips":
		$ip = $_REQUEST['ip'];
		if($ip){
			$mids = Member::factory()->getMidByIp($ip);
		}

		if($mids){
			$items = Member::factory()->getAllByIds($mids);
			if($items){
				foreach ($items as $k=>$item) {
					$items[$k]['ip']          = $ip;
					$items[$k]['mtime']       = json_decode($item['mtime'],true);
					$items[$k]['mactivetime'] = json_decode($item['mactivetime'],true);
					$items[$k]['mentercount'] = json_decode($item['mentercount'],true);
					$items[$k]['dflag']       = (int)Loader_Redis::account()->get(Config_Keys::banAccount($items[$k]['mid']),false,false);
					!$items[$k]['dflag'] && $items[$k]['dflag'] = (int)Loader_Redis::ote($items[$k]['mid'])->hGet(Config_Keys::other($items[$k]['mid']), 'bat');
				}
			}
		}

		include 'view/account.devices.php';
		break;	

	case "bataccountlog":	
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = User_Account::factory()->getLogBataccountCount($_REQUEST);	
		$items       = User_Account::factory()->getLogBataccount($_REQUEST);
		include 'view/account.bataccountlog.php';
		break;
		
	case "getbatlist":	
		$_REQUEST['stime'] = $_REQUEST['stime']  ? $_REQUEST['stime'] : date("Y-m-d");
		$_REQUEST['etime'] = $_REQUEST['etime']  ? $_REQUEST['etime'] : date("Y-m-d");

		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = User_Account::factory()->getLogBataccountCount($_REQUEST);	
		$items       = User_Account::factory()->getLogBataccount($_REQUEST);
		$batAdmids   = User_Account::factory()->getBatAdminList();
		include 'view/bataccount.list.php';
		break;	
			
	default:
		if(!empty($_REQUEST['username']) || !empty($_REQUEST['mnick'])){
			$items  = User_Account::factory()->get($_REQUEST);
			//$items = array($ret);
		}else{
			$ret  = User_Account::factory()->get($_REQUEST);		
			if($ret){
				$items = array($ret);
			}
		}

		if($items){
			foreach ($items as $k=>$item) {
				$items[$k]['deviceno']    = Member::factory()->getDevicenoBymid($item['mid']);
				$items[$k]['mtime']       = json_decode($item['mtime'],true);
				$items[$k]['mactivetime'] = json_decode($item['mactivetime'],true);
				$items[$k]['mentercount'] = json_decode($item['mentercount'],true);
				$items[$k]['dflag']       = Loader_Redis::account()->get(Config_Keys::banAccount($items[$k]['deviceno']),false,false);
				$items[$k]['ipflag']      = Loader_Redis::account()->get(Config_Keys::banAccount($items[$k]['ip']),false,false);
			}
		}
		
		include 'view/account.list.php';
		break;
}





