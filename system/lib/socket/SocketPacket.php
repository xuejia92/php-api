<?php
include_once(dirname(__FILE__) . "/EncryptDecrypt.php");

class SocketPacket {
	const SERVER_VERSION = 1;//版本号
	const SERVER_SUB_CMD = 0; //子命令
	const SERVER_ENCODE  = 0;//加密类型
	const SERVER_SEQ     = 0;//序列号
	const SERVER_SOURCE  = 100;//消息类型
	const SERVER_TYPE    = 100;//操作类型
	const SERVER_CODE    = 0;//校验code
	const PACKET_HEADER_SIZE = 18;
	const PACKET_BUFFER_SIZE = 8192;
	
	private $m_packetBuffer;
	private $m_packetSize;
	private $m_MainCmd;
	private $m_SubCmd;	
	private $m_CheckCode;
	private $m_Version;
	private $m_Source;
	private $m_Seq;
	private $m_Encode;
	private $m_Uid;
	private $m_Type;

	public function __construct(){
		$this->m_Version = self::SERVER_VERSION;
		$this->m_SubCmd  = self::SERVER_SUB_CMD;
		$this->m_Encode  = self::SERVER_ENCODE;
		$this->m_Seq     = self::SERVER_SEQ;
		$this->m_Source  = self::SERVER_SOURCE;
		$this->m_Type    = self::SERVER_TYPE;
		$this->m_CheckCode    = self::SERVER_CODE;		
	}
	
	public function WriteBegin($CmdType){
		$this->m_MainCmd = $CmdType;
	}
	
	public function WriteUid($uid){
		$this->m_Uid = $uid;
	}
	
	public function WriteEnd(){
		$EncryptObj = new EncryptDecrypt();
 		$code = 0;// $EncryptObj->EncryptBuffer($this->m_packetBuffer, 0, $this->m_packetSize);
     
		$content .= pack("N",$this->m_packetSize +14); //len 
		$content .= pack("C", $this->m_Version);	   //ver
		$content .= pack("n", $this->m_MainCmd);	   //main_cmd
		$content .= pack("C", $this->m_SubCmd);	       //sub_cmd
		$content .= pack("C", $this->m_Encode);	   
		$content .= pack("n", $this->m_Seq);	    
		$content .= pack("C", $this->m_Source);
		$content .= pack("C", $this->m_Type);
		$content .= pack("N", $this->m_Uid);
		$content .= pack("C", $this->m_CheckCode);
		$this->m_packetBuffer = $content . $this->m_packetBuffer;
	}
		
	public function GetPacketBuffer(){
		return $this->m_packetBuffer;
	}
	
	public function GetPacketSize(){
		return $this->m_packetSize + self::PACKET_HEADER_SIZE ;
	}
	
	public function WriteInt($value) {
		$this->m_packetBuffer .= pack ( "N", $value );
		$this->m_packetSize += 4;
	}
	
	public function WriteBigInt($value) {
		$v = pack("NN",$value >> 32,$value&0xFFFFFFFF);
		$this->m_packetBuffer .= $v;
		$this->m_packetSize += 8;
	}
	
	public function WriteUInt($value) {
		$this->m_packetBuffer .= pack ( "I", $value );
		$this->m_packetSize += 4;
	}
	
	public function WriteByte($value) {
		$this->m_packetBuffer .= pack ( "C", $value );
		$this->m_packetSize += 1;
	}
	
	public function WriteShort($value) {
		$this->m_packetBuffer .= pack ( "n", $value );
		$this->m_packetSize += 2;
	}
	
	public function WriteString($value) {		
		$len = strlen ( $value ) + 1;
		$this->m_packetBuffer .= pack ( "N", $len );
		$this->m_packetBuffer .= $value;
		$this->m_packetBuffer .= pack ( "C", 0 );
		$this->m_packetSize += $len + 4;
	}

	public function ParsePacket(){
		if( $this->m_packetSize < self::PACKET_HEADER_SIZE ){
			return false;
		}

		$header = substr($this->m_packetBuffer, 0, 18);		
		$arr = unpack("nLen/CVer/nMainCmd/CSubCmd/CEncode/nSeq/CSource/CType/NUid/CCheckCode", $header);	

		if ($arr['Ver'] != $this->m_version || $arr['SubCmd'] != $this->m_SubCmd){
			//return -1;
		}
		if($arr['MainCmd'] <= 0 || $arr['MainCmd'] >= 32000){
			return -2;
		}
		if($arr['Len'] >= 0 && $arr['Len'] > self::PACKET_BUFFER_SIZE - self::PACKET_HEADER_SIZE ){
			return -3;
		}
	 	$this->m_CheckCode  = $arr['CheckCode'];
		$this->m_packetBuffer = substr($this->m_packetBuffer, 18);

	//  $DecryptObj = new EncryptDecrypt();
	//  $code = $DecryptObj->DecryptBuffer($this->m_packetBuffer, $arr['Len'], $this->m_cbCheckCode);

		return 0;		
	}
	
	public function SetRecvPacketBuffer($packet_buff, $packet_size){
		$this->m_packetBuffer = $packet_buff;
		$this->m_packetSize  = $packet_size;
	}

	public function ReadInt(){
		$temp = substr($this->m_packetBuffer, 0, 4);
		$value = unpack("N", $temp);
		$this->m_packetBuffer = substr($this->m_packetBuffer, 4);
		return $value[1];
	}
	
	public function ReadUInt(){
		$temp = substr($this->m_packetBuffer, 0, 4);
		list(,$var_unsigned)=unpack("L",$temp);
		return floatval(sprintf("%u",$var_unsigned));
	}
	
	public function ReadBigInt() {
		$temp = substr($this->m_packetBuffer, 0, 8);
		list ($hi,$lo) = array_values(unpack("N*N*",$temp));
		if ($hi <0) $hi += (1 << 32);
		if ($ho <0) $lo += (1 << 32);
		$v =  ($hi << 32) + $lo;
		$this->m_packetBuffer = substr($this->m_packetBuffer, 8);
		return $v;
	}

	public function ReadShort(){
		$temp = substr($this->m_packetBuffer, 0, 2);
		$value = unpack("n", $temp);

		$temp = $value[1];
		if ($temp & 0x8000)
		{
			$temp = $temp & 0x7FFF;
			$temp = ~$temp;
			$temp = $temp & 0x7FFF;
			$temp = ($temp + 1) * -1;
		}

		$this->m_packetBuffer = substr($this->m_packetBuffer, 2);
		return $temp;		
	}
	
	public function ReadString(){
		$len = $this->ReadInt();				
		$value = substr($this->m_packetBuffer, 0, $len);
		$this->m_packetBuffer = substr($this->m_packetBuffer, $len);
		return $value;				
	}
	
	public function ReadByte(){
		$temp = substr($this->m_packetBuffer, 0, 1);
		$value = unpack("C", $temp);
		$this->m_packetBuffer = substr($this->m_packetBuffer, 1);
		return $value[1];	
	}
}//end-class