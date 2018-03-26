<?php !defined('IN WEB') AND exit('Access Denied!');
class Activity_Manager{
public static function getActivityList($param)
{
    $param['gameid']    = Helper::uint($param['gameid']);
    $param['mid']       = Helper::uint($param['mid']);
    $param['sid']       = Helper::uint($param['sid']);
    $param['cid']       = Helper::uint($param['cid']);
    $param['pid']       = Helper::uint($param['pid']);
    $param['ctype']     = Helper::uint($param['ctype']);
    $param['lang']      = Helper::uint($param['lang']);
    $param['versions']  = Loader_Mysql::DBMaster()->escape($param['versions']);
    $param['mtkey']     = Loader_Mysql::DBMaster()->escape($param['mtkey']);
    $param['device_no'] = Loader_Mysql::DBMaster()->escape($param['device_no']);
    
	$ip = Helper::getip();
	$ip_arr = Lib_Ip::find($ip);
	$paramString = '';

	foreach($param as $key=>$value){
		$paramString = $paramString.'&'.$key.'='.$value;
	}
	
	
	//file_put_contents("log.txt",$hallAddr,FILE_APPEND);
	
	
	
	$serverInfo = Loader_Redis::userServer($param['mid'])->hMget(Config_Keys::userServer($param['mid']), array('svid','tid','level','gameid'));
	
	
	
	$userInfo     = Member::factory()->getOneById($param['mid']);
	if($param['device_no']){
		$deviceno = $param['device_no'];
	}else{
		$deviceno = Member::factory()->getDevicenoBymid($param['mid']);
	}

	$paramString  = $paramString.'&mnick='.urlencode($userInfo['mnick'])."&money=".$userInfo['money'];

	$path = PRODUCTION_SERVER? '' : 'test_';
	
	
	// test_
	//file_put_contents("mylog.txt", var_export($path,true));
	
	
	
	
	$return = Loader_Redis::common()->get($path.'Activity_Config', false, true);//活动列表
		
	$switch_bit = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$param['pid']);//网页支付屏蔽
	$flag = $switch_bit  & 1;
	
	//获取活动数目
	$showAty = array();
	$time = time();
	
	//五星评论控制
	if($ip_arr[0] == '中国'){
		
		if(!$flag && $param['ctype'] != 1){
		    $wuxingpingjiaConfig  = Loader_Redis::common()->hGet("config_wuxingpingjia", $param['gameid'], false, true);
		    
		    $exists       = key_exists($param['cid'], $wuxingpingjiaConfig);
		    $tarversion   = $wuxingpingjiaConfig[$param['cid']]['version'];
		    $record       = Loader_Redis::common()->hGet("wuxingpingjia$tarversion:".$param['cid'], $deviceno);//五星评论记录
		    
		    if (($param['versions'] >= $tarversion) && $exists && (Helper::uint($record) != 2)){
	            $showAty[] = $return['test_wuxingpingjia'];
		    }
	    }
	}
	
	foreach ($return as $key => &$value) {
	    
	    //活动开启控制
	    if ($value['open'] == 1) {
	        
    		//上下线时间控制
    		if($time >= $value['start_time'] && $time <= $value['end_time']){
    		
    			//捕鱼活动
    			if ($key=='buyu' && $param['versions']<'3.5.1'){
    			    continue;
    			}
    			
    			//渠道开启
    			if ($value['openCid']!=''){
    			    $openCid = explode(',',$value['openCid']);
    			    if (!in_array($param['cid'], $openCid)){
    			        continue;
    			    }
    			}
    			
    			//客户端屏蔽
    			if ($value['closeCtype']){
    				$closeCtype = explode(',',$value['closeCtype']);
    				if (in_array($param['ctype'],$closeCtype)){
    					continue;
    				}
    			}
    		
    			//游戏屏蔽
    			if($value['closeGameid']){
    				$closeGameid = explode(',',$value['closeGameid']);
    				if (in_array($param['gameid'], $closeGameid)){
    					continue;
    				}
    			}
    			
    			//充值狂欢活动
    			if ($ip_arr[0] != '中国' && $key=='chongzhikuanghuan'){
    			    continue;
    			}
    			
    			
    			if (!$flag && $key=='chongzhikuanghuan'){
    			    $action_pay = 1;
    			}
    		
    			$showAty[] = $value;
    		}
	    }
	}
	
	//zst特殊处理
	if($param['cid']==158 && !$action_pay){
	    $showAty[] = $return['chongzhikuanghuan'];
	    $action_pay = 1;
	}
	
	if($ip_arr[0] == '中国'){
    	//网页支付控制
    	if(!$flag && !$action_pay){
           $showAty[] = $return['wangyezhifu'];
    	}
	}
			
	//IOS审核时活动显示指定活动
	if( $ip_arr[0] != '中国' || $flag ){
        $showAty = array();
        $showAty[] = $return['zhuochong'];
	}

	if ($param['mid']==3698520){
	    
	}
	
	//根据活动的数目来决定显示的页面0：显示暂无活动。1：直接显示活动页面。2以及2个以上:列表形式展现
	$num = count($showAty);
	if ($num==0){
	    $showAty[] = $return['zhuochong'];
	}
	
	// $activeAty = array();
	// foreach ($showAty as $aContent){
	//     $aContent = Activity_Lang::factory()->changeLang($aContent,$param['lang']);
	//     $activeAty[] = $aContent;
	// }
	return $showAty;
	//return $activeAty;
}
	
public static function getActivityCenterUrl($aUser){
    // $path = PRODUCTION_SERVER? 'http://user.dianler.com/' : 'http://utest.dianler.com/ucenter/';
    // $url = $path."index.php?m=activity&p=index&gameid=".$aUser['gameid']."&cid=".$aUser['cid']."&pid=".$aUser['pid']."&ctype=".$aUser['ctype']."&versions=".$aUser['versions']."&device_no=".$aUser['device_no']."&mid=".$aUser['mid']."&lang=".$aUser['lang']."&mtkey=".$aUser['mtkey'];
    $url = "http://139.129.202.70/flower/web/activity/zhajinhua/activity.html";
    return $url;
}
}
