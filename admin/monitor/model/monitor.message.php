<?php !defined('IN WEB') AND exit('Access Denied!');
class Monitor_Message extends Config_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Monitor_Message();
		}
		return self::$_instances;
	}
	
	public function get($data){
		$aWhere = array();		
	    //$data['status']  && $aWhere[] = "status=".Helper::uint($data['status']);
	    $data['type']    && $aWhere[] = "type=".Helper::uint($data['type']);
	    if($data['stime']){
	    	$stime = date("Y-m-d",strtotime($data['stime']));
	    	$aWhere[] = "ctime>='$stime'";
	    }else{
	    	$stime = date('Y-m-d',strtotime("-15 day"));
	    	$aWhere[]  = "ctime>='$stime'";
	    }
	    
	    if($data['etime']){
	    	$etime    = date("Y-m-d H:i:s",strtotime($data['etime']." 23:59:59"));
	    	$aWhere[] = "ctime<='$etime'";
	    }else{
	    	$etime         = date("Y-m-d",strtotime("+1 day"));
	    	$aWhere[] = "ctime<='$etime'";
	    }
	    
		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);		
		$recordStart = ($currentPage - 1) * $numPerPage;
		
		$sql  = "SELECT * FROM $this->message_logs WHERE ".implode(" AND ", $aWhere)." ORDER BY ctime DESC LIMIT  $recordStart,$numPerPage ";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		return array($rows,$stime);
	}
	
	public function getCount($data){
		$aWhere = array();
	    //$data['status']  && $aWhere[] = "status=".Helper::uint($data['status']);
	    $data['type']    && $aWhere[] = "type=".Helper::uint($data['type']);
	    
		if($data['stime']){
			$stime = date("Y-m-d",strtotime($data['stime']));
	    	$aWhere[] = "ctime>='$stime'";
	    }else{
	    	$stime = date('Y-m-d',strtotime("-15 day"));
	    	$aWhere[] = "ctime>='$stime'";
	    }
	    
	    if($data['etime']){
	    	$etime         = date("Y-m-d H:i:s",strtotime($data['etime']." 23:59:59"));
	    	$aWhere[] = "ctime<='$etime'";
	    }else{
	    	$etime         = date("Y-m-d",strtotime("+1 day"));
	    	$aWhere[] = "ctime<='$etime'";
	    }

		$sql  = "SELECT count(*) as count FROM $this->message_logs WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return (int)$row['count'];
	}
	
	public function getBalance(){		
		$account  = "ldyouxi";
		$password = 879458;
		$target = "http://sms.chanzor.com:8001/sms.aspx";

		$data = "action=overage&userid=&account=$account&password=$password";
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
		while(!feof($fd)) {
		      $gets .= fread($fd, 128);
		}
		fclose($fd);
		
		$start=strpos($gets,"<?xml");
		$data=substr($gets,$start);
		$xml=simplexml_load_string($data);
		$return = json_decode(json_encode($xml),true);
		return  $return['overage'];
	}

	public function stat($data){
		$aWhere = array();
		/*
		if($data['status'] && $data['status'] !=1 ){
			$aWhere[] = "status!=1";
		}else{
			$aWhere[] = "status =1";
		}
		*/
	    $data['type']    && $aWhere[] = "type=".Helper::uint($data['type']);
	    
		if($data['stime']){
	    	$stime = date("Y-m-d",strtotime($data['stime']));
	    	$aWhere[] = "ctime>='$stime'";
	    }else{
	    	$d = date("d");
			$d < 15 && $last = 15;
	    	$stime = $d < 15 ? date("Y-m-d",strtotime("-$last day")) :  date("Y-m-d",strtotime("-$d day"));
	    	$aWhere[] = "ctime>='$stime'";
	    }
	    
	    if($data['etime']){
	    	$etime         = date("Y-m-d H:i:s",strtotime($data['etime']." 23:59:59"));
	    	$aWhere[] = "ctime<='$etime'";
	    }else{
	    	$etime         = date("Y-m-d",strtotime("+1 day"));
	    	$aWhere[] = "ctime<='$etime'";
	    	$flag     =1;
	    }

	    $sql  = "SELECT DATE_FORMAT(ctime,'%Y-%m-%d') days,count(id) count FROM $this->message_logs WHERE ".implode(" AND ", $aWhere)."  GROUP BY days ORDER BY null";
	    $rows = Loader_Mysql::DBMaster()->getAll($sql);

	    $aDate = $this->showDate($stime, $etime, $flag);
		
	    $rtn = array();
	    foreach ($aDate as $k=>$date){
	    	$rtn[$k] = array('days'=>$date,'count'=>0);
	    	foreach ($rows as $row){
	    		if($row['days'] == $date){
	    			$rtn[$k]['count'] = $row['count'];
	    		}
	    	}
	    }

	    return array($rtn,$stime);  
	}
	
	private function showDate($stime,$etime,$flag=0){
		
		for($date=strtotime($stime);$date <=strtotime($etime);$date+=86400){ 
	    	$array[] = date("Y-m-d",$date); //打印 
		}
		
		return $array;	
	}
	
	public function countSend(){

		$sql = "SELECT count(id) count1 FROM $this->message_logs";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		$rtn['countAll'] = $row['count1'];
		
		$sql = "SELECT count(id) count2 FROM $this->message_logs WHERE type=1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		$rtn['countIdcode'] = $row['count2'];
		
		$sql = "SELECT count(id) count3 FROM $this->message_logs WHERE type=2";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		$rtn['countAccount'] = $row['count3'];
		
		$sql = "SELECT count(id) count4 FROM $this->message_logs WHERE type=3";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		$rtn['countOther'] = $row['count4'];
		
		$sql = "SELECT count(id) count5 FROM $this->message_logs WHERE status=1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		$rtn['countSucc'] = $row['count5'];
		
		$sql = "SELECT count(id) count6 FROM $this->message_logs WHERE status!=1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		$rtn['countFail'] = $row['count6'];
		
		return $rtn;
	}
}