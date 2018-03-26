<?php !defined('IN WEB') AND exit('Access Denied!');

class Action_Model extends Config_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Action_Model();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$aid         = $data['aid'] ? Helper::uint($data['aid']) : '';		
		$con         = $data['con'];
		$type        = Helper::uint($data['type']);
		$stime       = $data['stime'] ? strtotime($data['stime']) : '';
		$etime       = $data['etime'] ? strtotime($data['etime']) : '';
		
		if(!$data['title'] || !$data['stime'] ||!$data['etime'] ){
			return false;
		}
		
		extract($data);
		
		$sql = "INSERT INTO $this->action_base SET aid='$aid',bid='$bid',type='$type',`title`='$title',desc='$desc',
				stime='$stime',etime='$etime',imgurl='$imgurl',ctime='".NOW."'
				ON DUPLICATE KEY UPDATE 
				bid='$bid',type='$type',`title`='$title',desc='$desc',stime='$stime',etime='$etime',imgurl='$imgurl',ctime='".NOW."'";
		
		Loader_Mysql::DBMaster()->query($sql);
		
		$sql = "INSERT INTO $this->action_condition SET aid='$aid', ctype='$ctype',cvalue1='$cvalue1',`con`='$con',cvalue2='$cvalue2',
				pid='$pid',pamount='$pamount',prate='$prate',pvalue='$pvalue',daylimit='$daylimit',totallimit='$totallimit'
				ON DUPLICATE KEY UPDATE 
				 ctype='$ctype',cvalue1='$cvalue1',`con`='$con',cvalue2='$cvalue2',
				pid='$pid',pamount='$pamount',prate='$prate',pvalue='$pvalue',daylimit='$daylimit',totallimit='$totallimit'";
		
		Loader_Mysql::DBMaster()->query($sql);

		Loader_Memcached::cache()->delete(Config_Keys::versions($sid));
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function offline($data){
		if(!$aid = Helper::uint($data['aid'])){
			return false;
		}
		
		$sql = "UPDATE $this->action_base SET status=2 WHERE aid=$aid LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function del($data){
		if(!$aid=Helper::uint($data['aid'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->action_base WHERE aid=$aid LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		$sql = "DELETE FROM $this->action_condition WHERE aid=$aid LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return true;
	}
	
	public function getAll(){
		$sql = "SELECT * FROM $this->action_base";
		$actBase = Loader_Mysql::DBMaster()->getAll($sql);
		
		$sql = "SELECT * FROM $this->action_condition";
		$condition = Loader_Mysql::DBMaster()->getAll($sql);
		
		$array = array();
		foreach ($actBase as $k=>$base) {
			foreach ($condition as $con) {
				if($base['aid'] == $con['aid']){
					$array[$k] = array_merge($base,$con);
					break;
				}
			}
		}
		
		return $array;		
	}
	
	public function getOne($data){
		$aid = Helper::uint($data['aid']);
		
		$sql = "SELECT * FROM $this->action_base a LEFT JOIN $this->action_condition b ON a.aid=b.aid WHERE aid=$id LIMIT 1";
		
		return Loader_Mysql::DBMaster()->getOne($sql);
	}
		
}