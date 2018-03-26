<?php 
/**
 * mutipush-server
 * 
 */
$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';
set_time_limit(0);

//Logs::factory()->debug(1,'push_run_test');
//每天11点提前计算今天需要推送的用户
$timehi = date('H:i');


if($timehi > '09:30' && $timehi < '22:00'){	
	//Logs::factory()->debug(1,'push_config');	
	Push::factory()->calmid2push();
	
}

$pushkey = Loader_Redis::common()->rPop(Config_Keys::pushconfig(),false,false);

if($pushkey){
	$aConfig = explode("|", $pushkey);
	$id      = $aConfig[0];
	$ptype   = $aConfig[1];
	$gameid  = $aConfig[2];
	$ctype   = $aConfig[3];
	$cid     = $aConfig[4];
	$msg = Push::factory()->getPushMsgByid($id);
	
	Logs::factory()->debug($aConfig,'push_key');
	
}else{
	exit(0);
}


$start = 0;
$step  = 60;
$end = 50;
//主进程逻辑
$pid = pcntl_fork();
// 创建子进程失败
if($pid == -1){
	exit("error: start server fail. \n" );
}elseif($pid > 0){
	exit(0);
}
posix_setsid(); 
pcntl_signal(SIGCHLD, SIG_IGN);
$pid = posix_getpid(); 

while(true) {	

    $records = Push::factory()->getPushSlice($pushkey,$start,$end,$step);

    if (count($records) == 0) {
    	exit(0);
    }
        
    $start = $end + 1;
    $end   = $start + $step;
    
  	$oo = Ios_Api::factory($gameid,$ctype,Push::IS_PRODUCT);
	$oo->connect();
	$oo->sendMsg($records, $msg, $ptype);
	$oo->disconnect();	
	
	Logs::factory()->debug($end,'push_server'.$gameid);
    //sleep(1);
    usleep(10000);
}



