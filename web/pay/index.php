<?php !defined('IN WEB') AND exit('Access Denied!');


$id = $_GET['id'];

switch ($_REQUEST['act']) {
	case "stat":

		break;			
		
	default:	
		$nick = "ykf";			
		$items = Pay::factory()->getPayChannelList($id);
		include 'view/main.php';
		break;
}

