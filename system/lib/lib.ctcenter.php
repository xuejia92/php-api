<?php !defined('IN WEB') AND exit('Access Denied!');
/**
 * 统计中心上报数据类
 * 包含UDP和TCP两种方式
 */
class Lib_Ctcenter{
	private $curlsv;//tcp上报sv
	private $udpsv;//udp上报sv


	public function __construct(){
		$rand = mt_rand(0, 1);//随机选择一个端口
		$udpIP = Config_Inc::$udpserverStat['ip'];
		$udpPorts = Config_Inc::$udpserverStat['port'];
		$port     = $udpPorts[$rand];
		
		$this->curlsv = Config_Inc::$tcpserverStat;
		$this->udpsv  = "udp://$udpIP:$port";
	}

	public function sendData($gameid,$ctype,$cid,$sid,$pid,$mid,$statid,$ip){
		if ( ! PRODUCTION_SERVER) {
			return 100;
		}
		
		$srv = $this->udpsv;
		$errno = 0;
		$errstr = '';
		$timeout = 1;

		$data = json_encode($data);	 
		$socket = stream_socket_client($srv, $errno, $errstr, $timeout);//连接UDP server
		if ( $socket) {
			stream_set_timeout($socket, $timeout);
			$gzdata = @gzcompress($data, 9); //压缩
			fwrite($socket, $gzdata);//写入数据
			fclose($socket);
		}
		
		return true;
	}
	
	/**
	 * 发送在线数据
	 *
	 */
	public function sendOnline($lmoall, $lmoplay, $time){
		if ( ( ! $lmoall = (int)$lmoall ) || (! $lmoplay = (int)$lmoplay) || (! $time = (int)$time)) {
			return 1000;
		}
		$mappid = $this->appid;
		$msid = $this->sid;
		$strdata = "$mappid|$msid|$lmoall|$lmoplay|$time";
		$time = time();
		$sig = md5($this->appid . $this->secret . $strdata . $time);
		$sendUrl = $this->curlsv . '?data=' . $strdata . '&sig=' . $sig . '&time=' . $time . '&appid=' . $this->appid . '&ctmodel=1';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $sendUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ( $ch);
		$ret = $result;
		if (curl_errno( $ch )) {
			$ret =  1003;//crul错误
		}
		return $ret;
	}
	
	
	
}