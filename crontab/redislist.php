<?php
$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';


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


while (true){
	$record = Loader_Redis::common()->brPop(array(Config_Keys::walletList(),Config_Keys::msglist()),1);

	switch ($record[0]) {
		case 'MGT'://发短信
			if($record[1]){
				
				$phoneno = $record[1]['phoneno'];
				$content = $record[1]['msg'];
				$type    = $record[1]['type'];
				Logs::factory()->debug($record[1],'MGT');
				Base::factory()->sendMsg($phoneno, $content, $type);
			}
		break;
		
		case 'WLT'://发钱包
			if($record[1]){
				sleep(15);
				$giver  = $record[1]['giver'];
				$money  = $record[1]['money'];
				$gameid = $record[1]['gameid'];
				$sid    = $record[1]['sid'];
				$cid    = $record[1]['cid'];
				$pid    = $record[1]['pid'];
				$ctype  = $record[1]['ctype'];
				
				//Logs::factory()->debug($record[1],'WLT');
				Base::factory()->giveWallet($giver,$money,$gameid,$sid, $cid, $pid, $ctype);
				//Logs::factory()->debug(111,'WLT1');				
				sleep(1);
				
				$maxMoney = $money * 0.05;
				for($i=0;$i<4;$i++){
					$mnick    = Member::factory()->getMnickByRand();
					$mnick    = trim($mnick);
					$msg      = "恭喜玩家".$mnick."抢到了".$maxMoney."红包,玩牌越多,红包抢得越多!";
					$msg      = trim($msg);
					Logs::factory()->debug($msg,'WLT1');	
					Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);//type  100 表示发到全部游戏
				}
			}
		break;
		
		default:
			;
		break;
	}
}






