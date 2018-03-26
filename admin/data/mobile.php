<?php

$aCtype = Config_Game::$clientTyle;
$record = Data_Mobile::factory()->getItemDetail($_REQUEST);
$filter = $_REQUEST['filter'];
$gameid = $_REQUEST['gameid'];
if ($gameid=='1'){
    $item = 110;
}else if ($gameid=='3'){
    $item = 111;
}else if ($gameid=='4'){
    $item = 112;
}else if ($gameid=='5'){
    $item = 114;
}else if ($gameid=='6'){
    $item = 113;
}

$act    = $_REQUEST['act'];

switch ($act){
    case 'detail':
        include 'view/mobile.detail.php';
        break;
    
    default:
        include 'view/mobile.show.php';
        break;
}