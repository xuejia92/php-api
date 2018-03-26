<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Goldoperation extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Goldoperation;
		}
		
		return self::$_instances;
	}
	
	public function get(){
        $sql = "SELECT * FROM $this->adminlogmoney ORDER BY id DESC";
        
	    $items = Loader_Mysql::DBMaster()->getAll($sql);
	    
	    return $items;
	}
	
	public function getOne($data){

	    $startime = strtotime($data['stime']);
	    $endtime  = strtotime("+1 day",strtotime($data['etime']));
	    if ($data['username']){
    	    $username = trim($data['username']);
    	    $sql = "SELECT username,money,time,ip FROM $this->adminlogmoney WHERE username='$username' AND time BETWEEN '$startime' AND '$endtime'";
	    }else {
	        $sql = "SELECT username,money,time,ip FROM $this->adminlogmoney WHERE time BETWEEN '$startime' AND '$endtime'";
	    }
	    $row = Loader_Mysql::DBMaster()->getAll($sql);
	    return $row;
	}
	
}