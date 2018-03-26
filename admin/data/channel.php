<?php 
$cats = Data_Category::factory()->getCategory($_REQUEST);

$gameid = $_REQUEST['gameid'];
$ctype  = $_REQUEST['ctype'];

switch ($_REQUEST['act']){
    case 'detail':
        $aRtn = Data_Channel::factory()->getItemDetail($_REQUEST);
        
        $aContent   = $aRtn[0];
        $aItem      = $aRtn[1];
        $channel    = $aRtn[2];
        $aDate      = $aRtn[3];
        
        include 'view/channel.detail.php';
        break;
    
    default:
        $aRtn = Data_Channel::factory()->getItemDetail($_REQUEST);
        
        $aContent   = $aRtn[0];
        $aItem      = $aRtn[1];
        $channel    = $aRtn[2];
        $aDate      = $aRtn[3];
        
        include 'view/channel.list.php';
        break;
}