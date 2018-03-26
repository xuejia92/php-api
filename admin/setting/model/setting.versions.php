<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Versions extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Versions();
		}
		
		return self::$_instances;
	}
	
	public function set($data){		
		$cid        = $data['cid_cid'];
		$gameid     = Helper::uint($data['gameid']);
		$ctype      = Helper::uint($data['ctype']);
		$condition  = Loader_Mysql::DBSlave()->escape($data['condition']);
		$updatetype = Helper::uint($data['updatetype']);
		$url        = Loader_Mysql::DBSlave()->escape($data['url']);
		$description= Loader_Mysql::DBSlave()->escape($data['description']);
		$updatetype = Helper::uint($data['updatetype']);
		$usertype   = Helper::uint($data['usertype']);
		$id         = $data['id'] ? Helper::uint($data['id']) : '';		
		$con        = $data['con'];
		$versions   = $data['versions'];
		
		if(!$cid || !$versions ){
			return false;
		}
		
		$sql = "INSERT INTO $this->dbversions SET id='$id',ctype='$ctype',cid='$cid',con='$con',`versions`='$versions',updatetype='$updatetype',gameid=$gameid,
				url='$url',description='$description',time='".NOW."',status=1
				ON DUPLICATE KEY UPDATE ctype='$ctype',
				cid='$cid',con='$con',`versions`='$versions',updatetype='$updatetype',url='$url',description='$description',time='".NOW."',gameid=$gameid,status=1";
		
		Loader_Mysql::DBMaster()->query($sql);		
		
		$channel = Base::factory()->getChannel();
		$aCid = array_keys($channel);

		foreach (Config_Game::$game as $gameid=>$gameName) {
			foreach ($aCid as $cid) {			
				for($i=1;$i<=4;$i++){
					Loader_Redis::common()->delete(Config_Keys::versions($gameid,$cid,$i));
				}						
			}
		}
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbversions WHERE id=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		$channel = Base::factory()->getChannel();
		$aCid = array_keys($channel);
		
		foreach (Config_Game::$game as $gameid=>$gameName) {
			foreach ($aCid as $cid) {			
				for($i=1;$i<=4;$i++){
					Loader_Redis::common()->delete(Config_Keys::versions($gameid,$cid,$i));
				}						
			}
		}
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get(){
		$sql   = "SELECT * FROM $this->dbversions";
		$items =  Loader_Mysql::DBMaster()->getAll($sql);
		$channel = Base::factory()->getChannel();

		if($items){
			foreach ($items as $k=>$item) {
				$items[$k]['ctype'] = Config_Game::$clientTyle[$item['ctype']] ? Config_Game::$clientTyle[$item['ctype']] : "全部";
	
				if($item['cid']){
					$aCid = explode(",", $item['cid']);
					$c = '';
					foreach ($aCid as $cid){
						$c .= $channel[$cid] . ',';						
					}
					$items[$k]['cid'] = rtrim($c,',');
				}
			}
		}
		
		return $items;	
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);		
		$sql = "SELECT * FROM $this->dbversions WHERE id=$id LIMIT 1";		
		$rows =Loader_Mysql::DBMaster()->getOne($sql);	
		
		$aCid = explode(',', $rows['cid']);
		$channel = Base::factory()->getChannel();
		
		$rows['cname'] = '';
		foreach ($aCid as $cid) {
			$rows['cname'] .= $channel[$cid] . ',';			
		}
		$rows['cname'] = rtrim($rows['cname'],',');
		
		return $rows;
	}
}