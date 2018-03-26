<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "reply":
		$ret  = Feedback_Model::factory()->reply($_REQUEST);
		if($ret){
			Main_Flag::ret_sucess("操作成功！");
		}else{
			Main_Flag::ret_fail("操作失败！");
		}
		break;
	case "reloadFeedback":
		Main_Flag::ret_sucess("");
		break;					
	default:		
		$aCid  = Base::factory()->getChannel();
		$aCtype = Config_Game::$clientTyle;
		$aSid = Base::factory()->getAccountType();
		$aPid   = Base::factory()->getPack();
		
		$item = Feedback_Model::factory()->getOne($_GET['id']);
		$historys = Feedback_Model::factory()->getHistory($_GET['mid']);
		$userInfo = User_Account::factory()->get(array('mid'=>$_GET['mid']));
		$isvip    = Loader_Redis::account()->get(Config_Keys::vip($_GET['mid']),false,false);

		include 'view/feedback.reply.php';
		break;
}

