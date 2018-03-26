<?php   !defined('IN WEB') AND exit('Access Denied!');
$act = $_REQUEST['act'];

switch ($act){
    case 'getone':
        $items = Setting_Goldoperation::factory()->getOne($_REQUEST);

        include 'view/goldoperation.list.php';
        break;
        
    default:
        $items = Setting_Goldoperation::factory()->get();
        include 'view/goldoperation.list.php';
}

