<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Sid extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Sid();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$param = $data;
		if(!$sname = $data['sname'] ){
			return false;
		}
				
		$idc = Config_Inc::$idc;
		if($idc != 1){
			$param['idc']   = $idc;
			$sid            = Helper::curl("http://user.dianler.com/callback/setCidPid.php?act=sid", $param);//到国内机房统一申请cid，pid
			$data['id']     = $sid ? $sid : $data['id'] ;
		}
		
		$id = $data['id'] ? Helper::uint($data['id']) : '';
		$idc = $data['idc'] ? $data['idc'] : $idc;

		$sql = "INSERT INTO $this->dbsid SET sid='$id', sname='$sname',idc='$idc'
				ON DUPLICATE KEY UPDATE 
				sname='$sname',idc='$idc'";
		
		Loader_Mysql::DBMaster()->query($sql);
		Loader_Redis::common()->delete(Config_Keys::accountType());
		
		$num =  Loader_Mysql::DBMaster()->affectedRows();
		
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
		
		$sql = "DELETE FROM $this->dbsid WHERE sid=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get(){
		$idc = Config_Inc::$idc;
		$sql = "SELECT sid as id,sname FROM $this->dbsid WHERE idc='$idc'";
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);
		
		$sql = "SELECT sid as id,sname FROM $this->dbsid WHERE sid=$id LIMIT 1";
		
		return Loader_Mysql::DBMaster()->getOne($sql);
	}
}