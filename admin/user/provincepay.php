<?php !defined('IN WEB') AND exit('Access Denied!');

$aGame  = Config_Game::$game;
$aPmode = Setting_Pmode::factory()->getPmode();

$items = User_Stat::factory()->getProvincePay($_REQUEST);


include 'view/province.pay.php';







