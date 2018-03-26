<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Cid extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Cid();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$param = $data;
		if(!$cname = $data['cname'] ){
			return false;
		}
		
		if(!$ctype = $data['ctype']){
			return false;
		}
		
		$idc = Config_Inc::$idc;
		
		if($idc != 1){
			$param['idc']    = $idc;
			$param['gameid'] = implode(',', $data['gameid']);
			$cid       = Helper::curl("http://user.dianler.com/callback/setCidPid.php?act=cid", $param);//到国内机房统一申请cid，pid
			$data['id'] = $cid ? $cid : $data['id'] ;
		}
		
		$idc = $data['idc'] ? $data['idc'] : $idc;
		$gameid     = implode(',', $data['gameid']);
		$vertype    = $data['vertype'];
		
		$id = $data['id'] ? Helper::uint($data['id']) : '';
		
		$sql = "INSERT INTO $this->dbcid SET cid='$id', cname='$cname',ctype='$ctype',gameid='$gameid',idc='$idc',vertype='$vertype'
				ON DUPLICATE KEY UPDATE 
				cname='$cname',ctype='$ctype',gameid='$gameid',idc='$idc',vertype='$vertype'";

		Loader_Mysql::DBMaster()->query($sql);
		$num = Loader_Mysql::DBMaster()->affectedRows();
		
		if($num == 1){
			return Loader_Mysql::DBMaster()->insertID();
		}else{
			return 1;
		}
	}
	
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbcid WHERE cid=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		$sql = "DELETE FROM $this->dbpid WHERE cid=$id";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get($data){
		
		$ctype  = $data['ctype']  ? Helper::uint($data['ctype']) : 1;
		$gameid = $data['gameid'] ? Helper::uint($data['gameid']) : 1;
		
		$idc = Config_Inc::$idc;
		
		$sql  = "SELECT cid as id,cname,ctype,gameid,vertype FROM $this->dbcid WHERE ctype=$ctype AND idc='$idc' ORDER BY cid ASC";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		$records = array();
		foreach ($rows as $row) {
			if($games = $row['gameid']){
				$aGame = explode(',', $games);
				if(in_array($gameid,$aGame)){
					$records[] = $row;
				}
			}
		}
		
		return $records;
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);
		
		$sql = "SELECT cid as id,cname,ctype,gameid,vertype FROM $this->dbcid WHERE cid=$id LIMIT 1";
		
		return Loader_Mysql::DBMaster()->getOne($sql);
	}
	
	public function getAll(){
		$sql = "SELECT * FROM $this->dbcid";
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	

	public function getSort($data){
	    $ctype     = $data['ctype']==0 ? 'ctype IN (1,2)' : 'ctype='.$data['ctype'];
	    $vertype   = $data['vertype']==0 ? 'vertype IN(1,2,3)' : 'vertype='.$data['vertype'];
	    $sql = "SELECT * FROM $this->dbcid WHERE $ctype AND $vertype";
	    $rows = Loader_Mysql::DBMaster()->getAll($sql);
	
	    return $rows;
	}
}