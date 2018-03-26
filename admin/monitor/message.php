<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "stat":
		$balance = Monitor_Message::factory()->getBalance();
		$result = Monitor_Message::factory()->stat($_REQUEST);
		$items = $result[0];
		$stime = $result[1];
		$item  = Monitor_Message::factory()->countSend();
		include 'view/message.stat.php';
		break;		
	default:		
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = Monitor_Message::factory()->getCount($_REQUEST);		
		$result      = Monitor_Message::factory()->get($_REQUEST);
		$items = $result[0];
		$stime = $result[1];
		
		include 'view/message.list.php';
		break;
}

