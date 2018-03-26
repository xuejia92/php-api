<?php !defined('BOYAA') AND exit('Access Denied!');
/**
 * mutipush-server
 * PHP多进程IOS推送服务器
 * 
 */

//每天11点提前计算今天需要推送的用户
$timehi = date('H:i');
if($timehi > '10:30' && $timehi < '18:00'){		
	$cfg = Push_Admin::factory()->getConfig2Cal();
	Push_Push::factory()->calToday2Push($cfg);
	Logs::factory()->debug($cfg,'getConfig2Cal');
}

$configs = Push_Admin::factory()->getPushConfig();
Logs::factory()->debug($configs,'getPushConfig');

if($configs){
	$sid    = $configs['sid'];
	$msg    = $configs['msg'];
	$bid    = $configs['bid'];
	$cid    = $configs['cid'];//配置ID
	$type   = $configs['type'];//推送类型
}else{
	exit(0);
}

set_time_limit(0 ); //不超时

$cacheKey = "push|".$cid."|".$bid;

//初始化进程数
Loader_Memcached::cache()->set($cacheKey,0);

$start = 0;
$step = $end = 80;
//主进程逻辑
$pid = pcntl_fork();
// 创建子进程失败
if($pid == -1){
	exit("error: start server fail. \n" );
}elseif($pid > 0){
	exit(0 );
}
posix_setsid(); 
pcntl_signal(SIGCHLD, SIG_IGN);
$pid = posix_getpid(); 

while(true) {	
	Logs::factory()->debug(Loader_Memcached::cache()->get($cacheKey) ,'m_start');
	
	//限制子进程数，当子进程数超过5时，休眠20s，当子进程数小于5时，再继续派生子进程，这样保证始终有5个进程在工作
    while( (int)Loader_Memcached::cache()->get($cacheKey) > 4 ){
        sleep(3);
    }
    
    //父进程分批获取待推送的数据
    $records = Push_Push::factory()->getPushSlice($configs,$start,$end,$step);
	Loader_Redis::push()->close();	
    
	//Logs::factory()->debug($records ,'m_records');
	//推送完毕，父进程退出
    if (count($records) == 0) {
    	exit(0);
    }
        
    $start = $end + 1;
    $end   =  $start + $step;
	
    //进程数加1
	Loader_Memcached::cache()->increment($cacheKey,1,0,0);
	
	//父进程派生子进程  父进程不用等待子进程返回，继续执行循环派生子进程
	$pid = pcntl_fork();
    if( $pid == -1){
        Logs::factory()->debug($pid,'m_fork_error');   
    }elseif( $pid == 0 ){

    	$oo = Ios_Api::factory($sid);

		$oo->connect();
		
		$oo->sendMsg($records, $msg, true, $type);
		
		$oo->disconnect();

		//子进程数减1
		Loader_Memcached::cache()->increment($cacheKey,1,0,1);
		
        exit(0);        
        //子进程 end
    }
    usleep(5000 + rand(1,100));
}



