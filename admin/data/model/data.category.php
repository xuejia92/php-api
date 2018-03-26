<?php !defined('IN WEB') AND exit('Access Denied!');

class Data_Category extends Data_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Data_Category();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$catname       = $data['catname'];
		$catid         = $data['id'] ? Helper::uint($data['id']) : '';		

		if(!$catname ){
			return false;
		}
		
		$time = NOW;
		$sql = "INSERT INTO $this->dbcategory SET catid='$catid',catname='$catname',ctime='$time'
				ON DUPLICATE KEY UPDATE 
				catname='$catname',ctime='$time'";		
		Loader_Mysql::DBStat()->query($sql);	

		return Loader_Mysql::DBStat()->affectedRows();
	}
	
	public function del($data){
		if(!$catid = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbcategory WHERE catid=$catid LIMIT 1";
		Loader_Mysql::DBStat()->query($sql);
		
		return Loader_Mysql::DBStat()->affectedRows();
	}
	
	public function get(){
		$sql = "SELECT * FROM $this->dbcategory";
		$items = Loader_Mysql::DBStat()->getAll($sql);		

		return $items;		
	}
	
	public function getOne($data){
		$catid = Helper::uint($data['id']);		
		$sql = "SELECT * FROM $this->dbcategory WHERE catid=$catid LIMIT 1";		
		$rows = Loader_Mysql::DBStat()->getOne($sql);
		
		return $rows;
	}
	
	public function getCategory($data=array()){
		
		$a    = explode('_', $data['navTabId']);
		$flag = count($a);
		
		$not_in = ($flag == 2 || !$data) ? "" : " WHERE catid != 5 ";
		$sql = "SELECT * FROM $this->dbcategory $not_in";
		$cats = Loader_Mysql::DBStat()->getAll($sql);
		
		$_temp = array();
		if($cats){
			foreach ($cats as $cat) {
				$_temp[$cat['catid']] = $cat['catname'];
			}
		}
		return $_temp;
	}
}