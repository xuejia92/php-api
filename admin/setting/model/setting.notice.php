<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Notice extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Notice();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$cid        = $data['cid_cid'];
		$title      = Loader_Mysql::DBSlave()->escape($data['title']);
		$content    = Loader_Mysql::DBSlave()->escape($data['content']);
		$url        = Loader_Mysql::DBSlave()->escape($data['url']);
		$ctype      = Helper::uint($data['ctype']);
		$id         = $data['id'] ? Helper::uint($data['id']) : '';		

		if(!$data['gameid'] || !$cid || !$content || !$data['stime'] || !$data['etime'] ){
			return false;
		}
		$gameid     = implode(',', $data['gameid']);
		$stime      = strtotime($data['stime']);
		$etime      = strtotime($data['etime']);
		$time       = NOW;
		
		$sql = "INSERT INTO $this->dbnotice SET id='$id',cid='$cid',content='$content',title='$title',gameid='$gameid',
				url='$url',ctype='$ctype',stime='$stime',etime='$etime',ctime=$time
				ON DUPLICATE KEY UPDATE 
				cid='$cid',content='$content',title='$title',gameid='$gameid',
				url='$url',ctype='$ctype',stime='$stime',etime='$etime',ctime=$time";
		
		Loader_Mysql::DBMaster()->query($sql);	
		
		$channel = Base::factory()->getChannel();
		$aCid = array_keys($channel);

		foreach ($data['gameid'] as $gameid) {
			foreach ($aCid as $cid) {		
				for($i=1;$i<=4;$i++){
					for($gameid=1;$gameid<10;$gameid++){
						Loader_Redis::common()->delete(Config_Keys::notice($gameid,$cid,$i));
					}
				}
			}
		}

		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbnotice WHERE id=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		$channel = Base::factory()->getChannel();
		$aCid = array_keys($channel);
		foreach ($aCid as $cid) {		
			for($i=1;$i<=4;$i++){
				for($gameid=1;$gameid<10;$gameid++){
					Loader_Redis::common()->delete(Config_Keys::notice($gameid,$cid,$i));
				}
			}
		}
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get(){
		$sql = "SELECT * FROM $this->dbnotice";
		$items = Loader_Mysql::DBMaster()->getAll($sql);		
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
				
				if($item['gameid']){
					$aGame = explode(",", $item['gameid']);

					$g = '';
					foreach ($aGame as $gameid){
						$g .= Config_Game::$game[$gameid] . ',';						
					}
					$items[$k]['gameid'] = rtrim($g,',');
				}
			}
		}
		
		return $items;		
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);		
		$sql = "SELECT * FROM $this->dbnotice WHERE id=$id LIMIT 1";		
		$rows = Loader_Mysql::DBMaster()->getOne($sql);
		
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