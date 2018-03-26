<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Behavior extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Behavior();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$betitle    = Loader_Mysql::DBSlave()->escape($data['betitle']);
		$beid       = Loader_Mysql::DBSlave()->escape($data['beid']);
		$gameid     = Loader_Mysql::DBSlave()->escape($data['gameid']);
		$id         = $data['id'] ? Helper::uint($data['id']) : '';		
		$time       = NOW;

		$sql = "INSERT INTO $this->dbbehavior SET id='$id',gameid='$gameid',beid='$beid',betitle='$betitle'
				ON DUPLICATE KEY UPDATE 
				gameid='$gameid',beid='$beid',betitle='$betitle'";
			
		Loader_Mysql::DBMaster()->query($sql);	
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbbehavior WHERE id=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get($data){
	    if ($data){
		    $sql = "SELECT * FROM $this->dbbehavior WHERE gameid IN('0',$data)";
	    }else {
	        $sql = "SELECT * FROM $this->dbbehavior";
	    }
		$items = Loader_Mysql::DBMaster()->getAll($sql);		

		return $items;		
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);		
		$sql = "SELECT * FROM $this->dbbehavior WHERE id=$id LIMIT 1";		
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return $row;
	}
}