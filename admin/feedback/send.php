<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "set":
		$ret  = Feedback_Model::factory()->toSend($_REQUEST);
		if($ret){
			Main_Flag::ret_sucess("发送成功！");
		}else{
			Main_Flag::ret_fail("发送失败！");
		}
		break;
		
	case "setView":
		$item  = Feedback_Model::factory()->getSendOne($_REQUEST);

		include 'view/feedback.send.php';
		break;	

	default:		
		
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = Feedback_Model::factory()->getSendCount($_REQUEST);		
		$items = Feedback_Model::factory()->getSend($_REQUEST);
		include 'view/feedback.sendlist.php';
		break;
}

