<?php !defined('IN WEB') AND exit('Access Denied!');
/**
 * 
 * 日记中心 
 */

class Lib_Log{
	
	private  $udpServer;
	
	private  $timeout = 1;
	
	private  $gameid;
	

	public function __construct($gameid,$ip,$port ){
		
		$this->gameid = $gameid;

		if(!$ip || !$port){
			die("The lack of IP or port");
		}
		
		$this->udpServer = "udp://$ip:$port";
	}
	
	public function sendData($itemid,$mid,$gameid,$ctype,$cid,$sid,$pid,$ip,$count=1){
		if(!$mid){
			//return false;
		}
		
		$data = array(  'time'  =>NOW,
					    'gameid'=>$this->gameid,
						'ctype' =>(int)$ctype,
						'cid'   =>(int)$cid,
						'sid'   =>(int)$sid,
						'pid'   =>(int)$pid,
						'mid'   =>(int)$mid,
						'itemid'=>(int)$itemid,
						'ip'    =>$ip,
						'count' =>$count
				);
				
		$date = date("Y-m-d",NOW);
		$key = Config_Keys::stat($date, $itemid);
		$pid   = (int)$pid;
		$cid   = (int)$cid;
		$ctype = (int)$ctype;
		
		$index = $gameid.'-'.$ctype.'-'.$cid.'-'.$pid;
		Loader_Redis::common()->hIncrBy($key, $index, $count);	
		$this->sendUdp($data);
	}
	
	/**
	 * udp发包
	 */
	public function sendLog($mid,$act,$request,$response){	
		
		if(!$mid){
			return false;
		}
		
		/*
		if(is_array($response) && isset($response['serverInfo'])){
			unset($response['serverInfo']);
		}
		*/
		if(is_array($response) && isset($response['middle'])){
			unset($response['middle']);
			unset($response['big']);
			unset($response['icon']);
		}
		
		$data = array('time'  =>NOW,
					  'gameid'=>$this->gameid,
					  'mid'   =>(int)$mid,
					  'act'=>$act,
					  'request'=>$request,
					  'response'=>$response,
				);

		$this->sendUdp($data);
	}
		
	private function sendUdp($data){

		$srv = $this->udpServer;
		$errno = 0;
		$errstr = '';
		$timeout = $this->timeout;

		$data = json_encode($data);	 
		$socket = stream_socket_client($srv, $errno, $errstr, $timeout);//连接UDP server
		if ( $socket) {
			stream_set_timeout($socket, $timeout);
			$data = @gzcompress($data, 9); //压缩
			fwrite($socket, $data);//写入数据
			fclose($socket);
		}
	}
}