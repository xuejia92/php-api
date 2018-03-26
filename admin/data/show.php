<?php !defined('IN WEB') AND exit('Access Denied!');

$cats = Data_Category::factory()->getCategory($_REQUEST);
$_REQUEST['cid'] && $aPid = Setting_Pid::factory()->get(array('cid'=>$_REQUEST['cid'],'gameid'=>$_REQUEST['gameid']));

switch ($_REQUEST['act']) {
    case "yule":
        $aDate = Data_Stat::factory()->getYuleDetail($_REQUEST);
        
        include 'view/data.yule.php';
        
        break;
    
	case "detail":
		if($_REQUEST['catid'] == 2){
			$aRtn = Data_Stat::factory()->getLogPlay($_REQUEST);
			$aTime      = $aRtn[2];
	    	$aPlaysum   = $aRtn[0];
	    	$aOnlinesum = $aRtn[1];
	    	$stime      = $aRtn[3];
	    		    	
		}else{
			$aRtn = Data_Stat::factory()->getItemDetail($_REQUEST);
		
			$aItem = $aRtn[1];
			$aContent = $aRtn[0];
			$stime    = $aRtn[3];
			$aDate    = $aRtn[2];
			
		}
		
		include 'view/data.detail.php';
		break;
		
	case "chart":
		$aRtn = Data_Stat::factory()->getDataShow($_REQUEST);
		$aDate    = $aRtn[0];
		$aContent = $aRtn[1];
		
		$a		  = $aContent;
		
		foreach($a as $k=>&$v){
			$v = $v-100;
		}
		
		//获取包名
		$pname    = Base::factory()->getPack($_REQUEST['pid']);
		
		include 'view/data.chart.php';
		break;

	default:
		$aRtn = Data_Stat::factory()->getItemDetail($_REQUEST);
	
		$aItem    = $aRtn[1];
		$aContent = $aRtn[0];
		$stime    = $aRtn[3];
		$aDate    = $aRtn[2];

		include 'view/data.show.php';
		break;
}





