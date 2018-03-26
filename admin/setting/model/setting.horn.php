<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Horn extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Horn();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$content    = Loader_Mysql::DBSlave()->escape($data['content']);
		$gameid     = Helper::uint($data['gameid']);

		$time       = NOW;
		$content2server = "系统消息：". $content;
		
		if($gameid){
			$flag = Loader_Tcp::callServer2Horn()->setHorn($content2server,$gameid,10);
		}else{
			$flag = Loader_Tcp::callServer2Horn()->setHorn($content2server,0,100);
		}
				
		return $flag;
	}
	
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbhorn WHERE id=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get(){
		$items = Loader_Redis::common()->lGetRange("trumpetmsg", 0, -1,false,false);
		
		$records = array();
		$i = 0;
		foreach ($items as $k=>&$item) {
			$aItem = explode(":", $item);
			
			$content = $aItem[4].$aItem[5];
			if(strpos($content, "4291147") || strpos($content, "4291149") || strpos($content, "3670731") || strpos($content, "466504395") || strpos($content, "88109833")){
				continue;
			}
			
			$records[$i]['id']         = $i+1;
			$records[$i]['mid']        = $aItem[2];
			$records[$i]['gameid']     = $aItem[3];
			$records[$i]['time']       = $aItem[1];
			$records[$i]['mnick']      = $aItem[4];
			$records[$i]['content']    = $aItem[5];
			$i++;
		}
		return $records;		
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);		
		$sql = "SELECT * FROM $this->dbhorn WHERE id=$id LIMIT 1";		
		$row = Loader_Mysql::DBMaster()->getOne($sql);

		return $row;
	}
}