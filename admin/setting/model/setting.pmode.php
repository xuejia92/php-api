<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Pmode extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Pmode();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$param = $data;
		if(!$payname = $data['payname'] ){
			return false;
		}
		
		$idc = Config_Inc::$idc;
		if($idc != 1){
			$param['idc']   = $idc;
			$pmode          = Helper::curl("http://user.dianler.com/callback/setCidPid.php?act=pmode", $param);//到国内机房统一申请cid，pid
			$data['id']     = $pmode ? $pmode : $data['id'] ;
		}
		
		$id       = $data['id'] ? Helper::uint($data['id']) : '';
		$idc      = $data['idc'] ? $data['idc'] : $idc;
		$rows     = $this->get();
		$count    = count($rows);
		$itemid   = $data['itemid'] ? Helper::uint($data['itemid']) : '';
		
		$sql = "INSERT INTO stat.stat_item SET itemid='$itemid',catid=4,itemname='$payname',status=0
				ON DUPLICATE KEY UPDATE 
				catid=4,itemname='$payname',status=0";
		
		Loader_Mysql::DBStat()->query($sql);
		$itemid = Loader_Mysql::DBStat()->insertID();
		
		$sql = "INSERT INTO $this->dbpmode SET pmode='$id', payname='$payname',idc='$idc',itemid='$itemid'
				ON DUPLICATE KEY UPDATE 
				payname='$payname',idc='$idc',itemid='$itemid'";
		
		Loader_Mysql::DBMaster()->query($sql);
		
		$num = Loader_Mysql::DBMaster()->affectedRows();
		
		if($num == 1){
			return Loader_Mysql::DBMaster()->insertID();
		}else{
			return 1;
		}

	}
	
	public function updateStatus($data){
		$pmode  = Helper::uint($data['id']);
		$status = Helper::uint($data['status']);
		$sql = "UPDATE $this->dbpmode SET status=$status WHERE pmode=$pmode LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return true;
	}
    
    public function sort($data){
    	$ids = $data['ids'];
		$pos = $data['pos'];

		if(empty($ids) || empty($pos)){
			return false;
		}

		$id2sort = array();
		foreach ($ids as $k=>$id) {
			$id2sort[$id] = $pos[$k];
		}

		asort($id2sort);
		$sort2id = array_keys($id2sort);
		$count = count($pos);
		for($i=0;$i<$count;$i++){
			$id = $sort2id[$i];
			$sql = "UPDATE $this->dbpmode SET `order`=$i WHERE pmode=$id LIMIT 1 ";
			Loader_Mysql::DBMaster()->query($sql);
		}

		return true;
    }
    
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		$itemid = $data['itemid'] ? Helper::uint($data['itemid']) : '';
		
		if ($itemid){
    		$sql = "DELETE FROM stat.stat_item WHERE itemid=$itemid LIMIT 1";
    		Loader_Mysql::DBStat()->query($sql);
		}
		
		$sql = "DELETE FROM $this->dbpmode WHERE pmode=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get(){
		$idc = Config_Inc::$idc;
		$sql = "SELECT pmode as id,payname,status,`order`,itemid FROM $this->dbpmode WHERE idc='$idc' order BY `order` ASC";
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);
		
		$sql = "SELECT pmode as id,payname,itemid FROM $this->dbpmode WHERE pmode=$id LIMIT 1";
		
		return Loader_Mysql::DBMaster()->getOne($sql);
	}
	
	public function getPmode(){
		$sql = "SELECT pmode,payname,status,`order` FROM $this->dbpmode order BY `order` ASC";
		$all = Loader_Mysql::DBMaster()->getAll($sql);
		$record = array();
		
		foreach ($all as $k=>$v) {
			$record[$v['pmode']] = $v['payname'];
		}
		
		return $record;
	}
}