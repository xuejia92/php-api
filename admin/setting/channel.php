<?php !defined('IN WEB') AND exit('Access Denied!');

$a = $_GET['a'];
$id = Helper::uint($_GET['id']);
$sou = $_GET['source'];

switch ($a) {
	case 'notice':
		if($id){
			$notices = Setting_Notice::factory()->getOne(array('id'=>$id));
			$aChannel = explode(',', $notices['cid']);
		}
	break;
	
	case 'version':
		if($id){
			$notices = Setting_Versions::factory()->getOne(array('id'=>$id));
			$aChannel = explode(',', $notices['cid']);
		}
	break;
	
	case 'sort':
        $bCid = Setting_Cid::factory()->getSort($_REQUEST);
        $aChannel = explode(',', $notices['cid']);
        
        echo json_encode($bCid);
    break;
	
	default:
		;
	break;
}

$aCid = Setting_Cid::factory()->getAll();

if (!$sou){
    include 'view/channel.php';
}




