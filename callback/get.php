<?php
include '../common.php';
$act = $_GET['act'];

switch ($act) {
	case 'cid':
		$aCid = Base::factory()->getChannel();
		echo json_encode($aCid);
	break;
	
	case 'jump':
		Loader_Redis::common()->incr('phonemsg', 1);
		header('Location: http://itunes.apple.com/us/app/dian-le-suo-ha/id788485362?ls=1&mt=8');
	break;
	
	case 'getuser':
		$userinfo = Member::factory()->getOneById($_GET['mid']);
		if ($userinfo){
		  $userinfo['iconurl'] = Member::factory()->getIcon('', $_GET['mid'],'middle','','');
		}
		
		die(json_encode($userinfo));
	break;
	
	case 'getPlayHistoryCount':

		$mid = $_GET['mid'];
		if(!$mid){
			die('0');
		}

		$aGame =Config_Game::$game;
		$sum = 0;
		foreach ($aGame as $gameid=>$gname) {
			$playCount = Member::factory()->getPlayHistoryCount($mid, $gameid);

			if($playCount > 0){
				$sum = $sum + $playCount;
			}
		}
		
		echo $sum;
	break;
	
	
	case 'getInfoByUserName':
		
		$username = $_GET['username'];
		$userinfo = Member::factory()->getUserInfoByUserName($username);
		
		
		if($userinfo){
			die(json_encode($userinfo));
		}
		
		die('0');
	break;
	
}