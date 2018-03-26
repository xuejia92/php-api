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

$account  = "ldyouxi";
$password = 879458;
$target = "http://sms.chanzor.com:8001/sms.aspx";


while (true){
	$record = Loader_Redis::common()->brPop(Config_Keys::msglist(),2);

	if($record[1]){
		
		$phoneno = $record[1]['phoneno'];
		$content = $record[1]['msg'];
		$type    = $record[1]['type'];
		
		$data = "action=send&userid=&account=$account&password=$password&mobile=$phoneno&sendTime=&content=".rawurlencode("$content");
		$url_info = parse_url($target);
		$httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
		$httpheader .= "Host:" . $url_info['host'] . "\r\n";
		$httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
		$httpheader .= "Content-Length:" . strlen($data) . "\r\n";
		$httpheader .= "Connection:close\r\n\r\n";
		$httpheader .= $data;
		$fd = fsockopen($url_info['host'], 80);
		fwrite($fd, $httpheader);
		$gets = "";
		$count = 0;
		while(!feof($fd)) {
		      $gets .= fread($fd, 8204);
		      $count ++;
		      if($count > 2){
		      	Logs::factory()->debug($gets,'sendmsg1');
		      	break;
		      } 
		}
		fclose($fd);
		
		$start=strpos($gets,"<?xml");
		$data=substr($gets,$start);
		$xml=simplexml_load_string($data);
		$return = json_decode(json_encode($xml),true);
		
		Logs::factory()->debug($return,'sendmsg');
		
		Base::factory()->setMessageLogs('', $phoneno, $content, $type, $return['message']);
		//Loader_Mysql::DBMaster()->close();
	}
}








