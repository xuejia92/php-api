<?php !defined('IN WEB') AND exit('Access Denied!');
include_once dirname(__FILE__) . "/socket/SocketPacket.php";
 
class Lib_Tcp{
	
	 const 	CMD_GET        = 0x0460;//获取用户的金币，乐券等信息
	 
     const 	CMD_SETMONEY   = 0x0462;//加金币
     
     const 	CMD_SETBANK    = 0x0463;//更新保险箱金币信息
     
     const 	CMD_SETROLL    = 0x0464;//更新乐劵
     
     const 	CMD_SETROLLEXP = 0x0465;//更新过期乐劵
     
     const 	CMD_NOTICEPAY  = 0x0090;//充值发信息
     
     const 	CMD_HORN       = 0x0071;//发喇叭
     
     const 	CMD_CHECK      = 0x0401;//检查用用户名
     
     const  CMD_SETEXP     = 0x0467;//设置经验
  	 
     const  CMD_SEND       = 0x0091;//向客户端发消息通用命令
     
     
	 private $MemDataServer = array(); 

	 private $aSockets = array(); 

	 public function __construct( $ip, $port ){
		$this->MemDataServer = array( $ip,$port );
	}	
	
	public function setMoney($mid,$money,$type=10){

		if(!$mid=Helper::uint($mid)){
			return false;
		}
	
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_SETMONEY);
		$packet->WriteUid($mid);
		
		$packet->WriteInt($mid); 
		$packet->WriteByte($type); 
		$packet->WriteBigint($money); 
		$packet->WriteEnd();

		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'setMoney'),'serverresponse');
			return false;
		}

		$ret_code = $retPacket->ReadShort();
		$ret_msg  = $retPacket->ReadString();
		$gameInfo = array();

		if($ret_code == 0){
			$gameInfo['mid']         = $retPacket->ReadInt();
			$gameInfo['money']       = $retPacket->ReadBigint();
			$gameInfo['freezemoney'] = $retPacket->ReadBigint();
			$gameInfo['roll']        = $retPacket->ReadInt();
			$gameInfo['roll1']       = $retPacket->ReadInt();
			$gameInfo['coin']        = $retPacket->ReadInt();
			$gameInfo['exp']         = $retPacket->ReadInt();
		}else{
			Logs::factory()->debug($ret,'TCP-setMoney');
		}
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];	
		socket_close($this->aSockets[$ip][$port]);
		return $gameInfo;
	}
	
	public function sendMsg($mid,$type,$msg){
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_SEND);
		$packet->WriteUid(0);
		
		$packet->WriteInt($mid); 
		$packet->WriteShort($type); 
		$packet->WriteString($msg); 

		$packet->WriteEnd();

		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'sendMsg'),'serverresponse');
			return false;
		}

		$ret = $retPacket->ReadShort();

		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);
		if($ret === 0){
			return true;
		}else{
			Logs::factory()->debug(array($mid,$type,$msg,'setmsg'),'serverresponse');
			return false;
		}
	}
	
	public function setBank($mid,$money,$type=10){

		if(!$mid=Helper::uint($mid)){
			return false;
		}
		
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_SETBANK);
		$packet->WriteUid($mid);
		
		$packet->WriteInt($mid); 
		$packet->WriteByte($type); 
		$packet->WriteBigint($money); 
		$packet->WriteEnd();

		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'setBank'),'serverresponse');
			return false;
		}

		$ret_code = $retPacket->ReadShort();
		$ret_msg  = $retPacket->ReadString();
		
		$gameInfo = array();
				
		if($ret_code == 0){
			$gameInfo['mid']         = $retPacket->ReadInt();
			$gameInfo['money']       = $retPacket->ReadBigint();
			$gameInfo['freezemoney'] = $retPacket->ReadBigint();
			$gameInfo['roll']        = $retPacket->ReadInt();
			$gameInfo['roll1']       = $retPacket->ReadInt();
			$gameInfo['coin']        = $retPacket->ReadInt();
			$gameInfo['exp']         = $retPacket->ReadInt();
		}else{
			Logs::factory()->debug($ret,'TCP-setBank');
		}	
		
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);
		return $gameInfo;
	}
	
	public function setExp($mid,$exp,$type=10){

		if(!$mid=Helper::uint($mid)){
			return false;
		}
		
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_SETEXP);
		$packet->WriteUid($mid);
		
		$packet->WriteInt($mid); 
		$packet->WriteByte($type); 
		$packet->WriteInt($exp); 
		$packet->WriteEnd();

		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'setExp'),'serverresponse');
			return false;
		}

		$ret_code = $retPacket->ReadShort();
		$ret_msg  = $retPacket->ReadString();
		
		$gameInfo = array();
				
		if($ret_code == 0){
			$gameInfo['mid']         = $retPacket->ReadInt();
			$gameInfo['money']       = $retPacket->ReadBigint();
			$gameInfo['freezemoney'] = $retPacket->ReadBigint();
			$gameInfo['roll']        = $retPacket->ReadInt();
			$gameInfo['roll1']       = $retPacket->ReadInt();
			$gameInfo['coin']        = $retPacket->ReadInt();
			$gameInfo['exp']         = $retPacket->ReadInt();
		}else{
			Logs::factory()->debug($ret,'TCP-setBank');
		}	
		
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);
		return $gameInfo;
	}
	
	public function setRoll($mid,$roll,$type=10){

		if(!$mid=Helper::uint($mid)){
			return false;
		}
		
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_SETROLL);
		$packet->WriteUid($mid);
		$packet->WriteInt($mid); 
		$packet->WriteByte($type); 
		$packet->WriteInt($roll); 
		$packet->WriteEnd();
		
		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'setRoll'),'serverresponse');
			return false;
		}
		
		$ret_code = $retPacket->ReadShort();
		$ret_msg  = $retPacket->ReadString();

		$gameInfo = array();

		if($ret_code == 0){
			$gameInfo['mid']         = $retPacket->ReadInt();
			$gameInfo['money']       = $retPacket->ReadBigint();
			$gameInfo['freezemoney'] = $retPacket->ReadBigint();
			$gameInfo['roll']        = $retPacket->ReadInt();
			$gameInfo['roll1']       = $retPacket->ReadInt();
			$gameInfo['coin']        = $retPacket->ReadInt();
			$gameInfo['exp']         = $retPacket->ReadInt();
		}else{
			Logs::factory()->debug($ret,'TCP-setRoll');
		}	
		
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);

		return $gameInfo;
	}
	
	public function setRollExp($mid,$roll1,$type=10){

		if(!$mid=Helper::uint($mid)){
			return false;
		}
		
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_SETROLLEXP);
		$packet->WriteUid($mid);
		
		$packet->WriteInt($mid); 
		$packet->WriteByte($type); 
		$packet->WriteInt($roll1); 
		$packet->WriteEnd();

		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'setRollExp'),'serverresponse');
			return false;
		}

		$ret_code = $retPacket->ReadShort();
		$ret_msg  = $retPacket->ReadString();
		
		$gameInfo = array();
				
		if($ret_code == 0){
			$gameInfo['mid']         = $retPacket->ReadInt();
			$gameInfo['money']       = $retPacket->ReadBigint();
			$gameInfo['freezemoney'] = $retPacket->ReadBigint();
			$gameInfo['roll']        = $retPacket->ReadInt();
			$gameInfo['roll1']       = $retPacket->ReadInt();
			$gameInfo['coin']        = $retPacket->ReadInt();
			$gameInfo['exp']         = $retPacket->ReadInt();
		}else{
			Logs::factory()->debug($ret,'TCP-setRollExp');
		}	
		
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);
		return $gameInfo;
	}
	
	public function payNotice($mid,$pbankno){

		if(!$mid=Helper::uint($mid)){
			return false;
		}
		
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_NOTICEPAY);
		$packet->WriteUid($mid);
		
		$packet->WriteInt($mid); 
		$packet->WriteString($pbankno); 

		$packet->WriteEnd();

		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array($mid,$pbankno,'payNotice'),'serverresponse');
			return false;
		}

		$ret = $retPacket->ReadShort();
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);
		
		if($ret === 0){
			return true;
		}else{
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'payNotice'),'serverresponse');
			return false;
		}
	}
	
	public function setHorn($message,$gameid,$type=100){
		$message = str_replace("\0", "", $message);
		$message = str_replace("\n", "", $message);
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_HORN);
		$packet->WriteUid(0);
		
		$packet->WriteShort($type); 
		$packet->WriteShort($gameid); 
		$packet->WriteString($message); 

		$packet->WriteEnd();

		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'setHorn'),'serverresponse');
			return false;
		}

		$ret = $retPacket->ReadShort();
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);
		if($ret === 0){
			return true;
		}else{
			Logs::factory()->debug(array($message,$gameid,'sethorn'),'serverresponse');
			return false;
		}
	}
	
	public function checkUserName($message, $type=10){

		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_CHECK);
		$packet->WriteUid(0);
		
		$packet->WriteShort($type); 
		$packet->WriteString($message); 

		$packet->WriteEnd();

		if(! $flag = $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();

		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'checkUserName'),'serverresponse');
			return false;
		}

		$ret = $retPacket->ReadShort();
		$message = $retPacket->ReadString();
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);
		
		return $message;
	}
	
	public function get($mid){
		
		if($mid < 1000 || $mid > 100000000){
			return false;
		}
		
		$packet = new SocketPacket();
		$packet->WriteBegin(self::CMD_GET);
		$packet->WriteUid($mid);
		
		$packet->WriteInt($mid); 
		$packet->WriteEnd();

		if(! $this->SendData($this->MemDataServer[0], $this->MemDataServer[1], $packet, true)){
			return false;
		}

		if(($recvLen = @socket_recv($this->aSockets[$this->MemDataServer[0]][$this->MemDataServer[1]], $data, 4096, 0)) === false){
			return false;
		}

		$retPacket = new SocketPacket();
		$retPacket->SetRecvPacketBuffer($data, $recvLen);
		$ret = $retPacket->ParsePacket();
		
		if($ret != 0){
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'get','mid'=>$mid),'serverresponse');
			return false;
		}

		$ret_code = $retPacket->ReadShort();
		$ret_msg  = $retPacket->ReadString();
		
		$gameInfo = array();
				
		if($ret_code == 0){
			$gameInfo['mid'] = $retPacket->ReadInt();
			$gameInfo['money']       = $retPacket->ReadBigint();
			$gameInfo['freezemoney'] = $retPacket->ReadBigint();
			$gameInfo['roll']        = $retPacket->ReadInt();
			$gameInfo['roll1']       = $retPacket->ReadInt();
			$gameInfo['coins']       = $retPacket->ReadInt();
			$gameInfo['exp']         = $retPacket->ReadInt();
		}else{
			Logs::factory()->debug(array('ret'=>$ret,'cmd'=>'get','mid'=>$mid),'callServerError-get');
		}	
		
		$ip   = $this->MemDataServer[0];
		$port = $this->MemDataServer[1];
		socket_close($this->aSockets[$ip][$port]);
		return $gameInfo;
	}
		
	/**
	 * 连接Tcp服务器
	 * @param String $ip
	 * @param int $port
	 * @param Boolean $reuse 是否使用上一次创建好的连接.用在一个脚本中与同一个端口多次通讯.只有存钱Server支持,其他都要强制重新连接
	 * @return socket/false
	 */
	private function connect($ip, $port, $reuse=false){
		if( is_resource( $this->aSockets[$ip][$port]) && $reuse){ //已经连接并且支持...
			return $this->aSockets[$ip][$port];
		}
		
		if(($socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false){
			Logs::factory()->debug(@implode('-', array(socket_strerror(socket_last_error()), __LINE__, $ip, $port)), 'C_connect_creat.txt');
			return false;
		}
		
		@socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>1, "usec"=>0));
		@socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>1, "usec"=>0));
		
		for($i=0;$i<5;$i++){
			$is_connect = @socket_connect($socket, $ip, $port);	
			if($is_connect){
				break;
			}
			usleep(50);
			Logs::factory()->debug(@implode('-', array(socket_strerror(socket_last_error()), __LINE__, $ip, $port,i)), 'socket_connect');
		}
		
		if($is_connect === false){
			return false;
		}

		socket_set_block( $socket);
		
		return $this->aSockets[$ip][$port] = $socket;
	}
	/**
	 * 实际做的发送操作
	 * @param String $ip
	 * @param int $port
	 * @param GameSocketPacket $packet
	 * @return Boolean
	 */
	private function SendData($ip, $port, &$packet, $reuse=false){
		if(! $this->connect($ip, $port, $reuse)){
			return false;
		}
		
		for($i=0;$i<5;$i++){
			$is_write = @socket_write($this->aSockets[$ip][$port], $packet->GetPacketBuffer(), $packet->GetPacketSize() );	
			if($is_write){
				break;
			}
			usleep(50);
			Logs::factory()->debug(@implode('-', array(socket_strerror(socket_last_error()), __LINE__, $ip, $port,i)), 'socket_write');
		}
		
		if($is_write === false){
			return false;
		}
		
    	return true;
	}
	 
}//end-class
