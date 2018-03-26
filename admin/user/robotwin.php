<?php !defined('IN WEB') AND exit('Access Denied!');

include 'model/user.robotwin.php';
$array = Robot_win::factory()->bankerwsin();

$dragon     = $array[0];
$many       = $array[1];
$fishinfo   = $array[2];
$texas      = $array[3];
$glod       = $array[4];

include 'view/robot.win.php';