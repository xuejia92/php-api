<?php !defined('IN WEB') AND exit('Access Denied!');

$roomConfig = array(
				'1'=>'房间1',
				'2'=>'房间2',
				'3'=>'房间3',
				'4'=>'房间4',
				'5'=>'房间5'
);

switch ($_REQUEST['act']) {
	
	case "overview":
		
		$aRtn = Data_Task::factory()->getTaskOverview($_REQUEST);
		$aItem    = $aRtn[0];
		$aContent = $aRtn[1];
		$count    = $aRtn[2];
		include 'view/task.detail.php';
	    break;
}





