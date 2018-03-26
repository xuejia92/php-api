<?php !defined('IN WEB') AND exit('Access Denied!');

$gameid = $_REQUEST['gameid'];
$date   = $_REQUEST['date'];
$aCid   = Base::factory()->getChannel();
$aCtype = Config_Game::$clientTyle;;

$records = Data_Version::factory()->get($_REQUEST);

include 'view/version.list.php';


