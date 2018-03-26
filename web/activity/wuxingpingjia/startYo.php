<?php 
!defined('IN WEB') AND exit('Access Denied!');

$time  = time();
$today = date('Y-m-d H:i:s',$time);
$keyday = date('Y-m-d',$time);

$ret = array();
$ret['flag']   = 0;  	//返回值
$ret['messid'] = 0;		//错误号
$ret['money']  = 0;	    //领取金额

//时间控制 超过这个时间不在进行处理
//if($time< strtotime("2014-3-11") || $time > strtotime("2014-12-30")){
//	echo json_encode($ret);
//	exit;
//}

//与自身活动相关的参数及一些载入逻辑
$gameid=$_REQUEST['gameid'];
$mid=$_REQUEST['mid'];
$sid=$_REQUEST['sid'];
$cid=$_REQUEST['cid'];
$pid=$_REQUEST['pid'];
$ctype=$_REQUEST['ctype'];
$mtkey      = $_REQUEST['mtkey']?$_REQUEST['mtkey']:'';
//buttonctype = 1 表示记录 我要去写评论了。 = 2 表示领奖
$buttonctype = $_REQUEST['buttonctype'];
$oldmoney=$_REQUEST['oldmoney'];
$timeTarget = 30;
$pinglunKeys = "wuxingpingjia_".$gameid."_".$mid;
$pingtimeKeys = "wuxingpingjiaTM_".$gameid."_".$mid;

//机器码
$deviceno = Member::factory()->getDevicenoBymid($mid);
$pinglunDevicenoKeys = "wuxingpingjia_".$gameid."_".$deviceno;
$pinglunDeviceno     = Loader_Redis::common()->get($pinglunDevicenoKeys);

$pingtime = Loader_Redis::common()->get($pingtimeKeys);
$pinglun = Loader_Redis::common()->get($pinglunKeys);

$jiangliNum = 0;
if($gameid == 1){
	$jiangliNum = 10000;
}else if($gameid == 3){
	$jiangliNum = 10000;
}else if($gameid == 4){
	$jiangliNum = 10000;
}

if(!$pinglunDeviceno){
	$pinglunDeviceno = 0;
}

if($mid == 14481){
	$pinglunDeviceno = 0;
}

if(!$pinglun){
	$pinglun = 0;
}
if(!$pingtime){
	$pingtime = $time;
}
if($mid == 4057){
Loader_Redis::common()->set("ytt",$buttonctype);
}
if($buttonctype == 1){
	

	if($pinglun == 1){
		//$ret['messid'] = 2;
		Loader_Redis::common()->set($pingtimeKeys,$time);
		$ret['money'] = $pinglun."ppp";
		echo json_encode($ret);
		exit;
	}else{
		Loader_Redis::common()->set($pinglunKeys,1);
		Loader_Redis::common()->set($pingtimeKeys,$pingtime);
		$ret['flag'] = 1;
		$ret['messid'] = 1;
		echo json_encode($ret);
		exit;
	}
}else if($buttonctype == 2){
	if($pinglun == 1){
		if($time-$pingtime>=$timeTarget){
			if($pinglunDeviceno==0){
				Loader_Redis::common()->set($pinglunKeys,2);
				Loader_Redis::common()->set($pinglunDevicenoKeys,1);
				Loader_Redis::common()->hSet(Config_Keys::stars5($gameid),$mid,1);
				//Loader_Redis::common()->set("ytest",Config_Keys::stars5($param['gameid']));
				//Loader_Redis::common()->set("ytest1",$pingtime);
				$jianglimoney = $jiangliNum;
				Logs::factory()->addWin($gameid,$mid,15,$sid, $cid, $pid,$ctype,0,$jianglimoney,$desc='wuxingpingjia');
				$ret['money']  = $jianglimoney;
				$ret['flag'] = 1;
				$ret['messid'] = 3;
				echo json_encode($ret);
				exit;
			}else{
				$ret['messid'] = 4;
				$ret['money'] = $pinglun."tt";
				echo json_encode($ret);
				exit;
			}
			
		}else{
			$ret['messid'] = 5;
			$ret['money'] = $pinglun."tt";
			echo json_encode($ret);
			exit;
		}
	}else{
	  $ret['messid'] = 4;
	  $ret['money'] = $pinglun."zz";
	  echo json_encode($ret);
	  exit;
	}
}
