<?php !defined('IN WEB') AND exit('Access Denied!');
/**
 * 获取历史记录
 */
	
	$ret['result'] = 0;
	$mid = Helper::uint($_REQUEST['mid']);
		
	$history = Base::factory()->getMyfeed(3,$mid);

	if($history){
		$ret['result'] = 1;
		$ret['msg'] = $history;
	}
		
		
	echo json_encode($ret);
	
	
