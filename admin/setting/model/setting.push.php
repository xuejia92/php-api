<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Push extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Push();
		}
		
		return self::$_instances;
	}
	
	public function set($data){		
		$cid        = $data['cid_cid'];
		$gameid     = Helper::uint($data['gameid']);
		$ctype      = Helper::uint($data['ctype']);
		$ptype      = Helper::uint($data['ptype']);
		$pcon       = Loader_Mysql::DBSlave()->escape($data['pcon']);
		$msg        = Loader_Mysql::DBSlave()->escape($data['msg']);
		$ptime      = Loader_Mysql::DBSlave()->escape($data['ptime']);
		$id         = $data['id'] ? Helper::uint($data['id']) : '';		
		
		if($ptype == 2){
			$ftime = $data['ftime'];
			$etime = $data['etime'];
			$pcon  = $ftime.','.$etime;
		}
		
		if($ptype != 1){
			$ptime = strtotime($data['ptime2']);
		}else{
			$ptime = $data['ptime1'];
		}
		
		$sql = "INSERT INTO $this->push SET id='$id',gameid='$gameid',ctype='$ctype',cid='$cid',pcon='$pcon',ptype='$ptype',msg='$msg',ptime='$ptime',ctime='".NOW."',status=1
				ON DUPLICATE KEY UPDATE gameid='$gameid',ctype='$ctype',cid='$cid',pcon='$pcon',ptype='$ptype',msg='$msg',ptime='$ptime',ctime='".NOW."',status=1";
		
		Loader_Mysql::DBMaster()->query($sql);		
				
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->push WHERE id=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
				
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function setStatus($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$status = $data['status'];
		
		$sql = "UPDATE $this->push SET status='$status' WHERE id=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
				
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get(){
		$sql   = "SELECT * FROM $this->push";
		$items =  Loader_Mysql::DBMaster()->getAll($sql);		
		$channel = Base::factory()->getChannel();

		if($items){
			foreach ($items as $k=>$item) {
				$items[$k]['ctype'] = Config_Game::$clientTyle[$item['ctype']] ? Config_Game::$clientTyle[$item['ctype']] : "全部";
	
				if($item['cid']){
					$items[$k]['cid'] = $channel[$cid];
				}else{
					$items[$k]['cid'] = "全部";
				}
			}
		}
		
		return $items;	
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);		
		$sql = "SELECT * FROM $this->push WHERE id=$id LIMIT 1";		
		$rows =Loader_Mysql::DBMaster()->getOne($sql);	
		
		$channel = Base::factory()->getChannel();
		
		$rows['cname'] = $rows['cid'] ? $channel[$rows['cid']] : "全部";
		
		if($rows['ptype'] == 2){
			$arr = explode(",", $rows['pcon']);
			$rows['ftime'] = $arr[0];
			$rows['etime'] = $arr[1];
		}
		
		return $rows;
	}
}