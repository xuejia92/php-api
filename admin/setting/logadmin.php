<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {

	default:		
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = Setting_Logadmin::factory()->getCount($_REQUEST);		
		$result      = Setting_Logadmin::factory()->get($_REQUEST);
		$items = $result[0];
		$stime = $result[1];
		
		include 'view/logadmin.list.php';
		break;
}

