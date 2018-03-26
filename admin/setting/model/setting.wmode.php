<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Wmode extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Wmode();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$param = $data;
		$idc = Config_Inc::$idc;
		if($idc != 1){
			$param['idc']   = $idc;
			$wmode          = Helper::curl("http://user.dianler.com/callback/setCidPid.php?act=wmode", $param);//到国内机房统一申请cid，pid
			$data['id']     = $wmode ? $wmode : $data['id'] ;
		}
		
		$id = $data['id'] ? Helper::uint($data['id']) : '';
		$idc = $data['idc'] ? $data['idc'] : $idc;
		
		$sql = "INSERT INTO $this->dbwmode SET wmodeID='$id', gamedesc='{$data['gamedesc']}',admindesc='{$data['admindesc']}',idc='$idc'
				ON DUPLICATE KEY UPDATE 
				wmodeID='$id', gamedesc='{$data['gamedesc']}',admindesc='{$data['admindesc']}',idc='$idc'";
		
		Loader_Mysql::DBMaster()->query($sql);
		
		Loader_Redis::common()->delete(Config_Keys::wmode());
		
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
		
		$sql = "DELETE FROM $this->dbwmode WHERE wmodeID=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get(){
		$idc = Config_Inc::$idc;
		$sql = "SELECT wmodeID as id,gamedesc,admindesc FROM $this->dbwmode WHERE idc='$idc'";
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);
		
		$sql = "SELECT wmodeID as id,gamedesc,admindesc FROM $this->dbwmode WHERE wmodeID=$id LIMIT 1";
		
		return Loader_Mysql::DBMaster()->getOne($sql);
	}
}