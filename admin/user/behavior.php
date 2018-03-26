<?php !defined('IN WEB') AND exit('Access Denied!');

$aGame = Config_Game::$game;
$aCid  = Base::factory()->getChannel();

$aCtype = Config_Game::$clientTyle;

if($_GET['act'] == 'showact'){
	include 'view/behavior.showaction.php';
}else{
	$behavior = Setting_Behavior::factory()->get($_REQUEST['gameid']);
	$aBehavior = array();
	foreach ($behavior as $behaviors) {
		$aBehavior[$behaviors['beid']] = $behaviors['betitle'];
	}
	$items  = User_Behavior::factory()->get($_POST);
	include 'view/behavior.list.php';
}



