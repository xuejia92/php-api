<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Pid extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Pid();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$param = $data;
		if(!$pname = $data['pname'] ){
			return false;
		}
		
		if(!$cid = Helper::uint($data['cid'])){
			return false;
		}
		
		if(!$gameid = $data['gameid']){
			return false;
		}
		
		$childid = Helper::uint($data['childid']);
		
		$idc = Config_Inc::$idc;
		
		if($idc != 1){
			$param['idc'] = $idc;
			$param['gameid'] = implode(',', $data['gameid']);
			$pid = Helper::curl("http://user.dianler.com/callback/setCidPid.php?act=pid", $param);//到国内机房统一申请cid，pid
			$data['id'] = $pid ? $pid : $data['id'] ;
		}
		
		$idc = $data['idc'] ? $data['idc'] : $idc;
		
		$id  = $data['id'] ? Helper::uint($data['id']) : '';
		
		$sql = "INSERT INTO $this->dbpid SET pid='$id', pname='$pname',cid=$cid,gameid='$gameid',idc='$idc',childid='$childid'
				ON DUPLICATE KEY UPDATE 
				pname='$pname',cid=$cid,gameid='$gameid',idc='$idc',childid='$childid'";

		Loader_Mysql::DBMaster()->query($sql);
		$num = Loader_Mysql::DBMaster()->affectedRows();
		
		if($num == 1){
			return Loader_Mysql::DBMaster()->insertID();
		}else{
			return 1;
		}
	}
	
	public function getSwitch($pid){
		$switch_bit     = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$pid);
		$switch_arr = array();
		for($i=0;$i<20;$i++){
			$flag = $switch_bit >> $i & 1;
			if($flag){
				$switch_arr[] = $i+1;
			}
		}
		
		return $switch_arr;
	}
	
	public function setSwitch($pid,$switch){
		if(!$switch || !is_array($switch)){
			$switch = array();
		}

		$_tmp = array();
		for($i=1;$i<=20;$i++){
			if (in_array($i,$switch)){
				$_tmp[$i] = 1;
			}else{
				$_tmp[$i] = 0;
			}
		}
		
		$mask       = array_reverse($_tmp);
		$mask_bit   = implode("", $mask);
		$switch_int =  bindec($mask_bit);
		Loader_Redis::common()->hSet(Config_Keys::optswitch(), $pid, $switch_int);
		
		return true;
	}
	
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbpid WHERE pid=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get($data){
		
		$cid    = $data['cid'] ? Helper::uint($data['cid']) : 1;
		$gameid = $data['gameid'] ? Helper::uint($data['gameid']) : 1;
		
		$idc = Config_Inc::$idc;
		$sql  = "SELECT pid as id,pname,cid,gameid,childid FROM $this->dbpid WHERE cid=$cid AND gameid='$gameid' AND idc='$idc' ORDER BY pid ASC";
		return Loader_Mysql::DBMaster()->getAll($sql);

	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);
		
		$sql = "SELECT pid as id,pname,cid,gameid,childid FROM $this->dbpid WHERE pid=$id LIMIT 1";
		
		return Loader_Mysql::DBMaster()->getOne($sql);
	}
}