<?php !defined('IN WEB') AND exit('Access Denied!');

class Data_Item extends Data_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Data_Item();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$itemname   = $data['itemname'];
		$catid      = $data['catid'] ? Helper::uint($data['catid']) : '';		
		$itemid     = $data['id'] ? Helper::uint($data['id']) : '';

		if(!$itemname || !$catid ){
			return false;
		}
		
		$time = NOW;
		$sql = "INSERT INTO $this->dbitem SET itemid='$itemid', catid='$catid',itemname='$itemname',status=1
				ON DUPLICATE KEY UPDATE 
				catid='$catid',itemname='$itemname',status=1";		
		Loader_Mysql::DBStat()->query($sql);	

		return Loader_Mysql::DBStat()->affectedRows();
	}
	
	public function updateStatus($data){
		$status     = Helper::uint($data['status']);
		if(!$itemid = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "UPDATE $this->dbitem SET status=$status WHERE itemid=$itemid LIMIT 1";
		Loader_Mysql::DBStat()->query($sql);
		
		return true;
	}
	
	public function del($data){
		if(!$itemid = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbitem WHERE itemid=$itemid LIMIT 1";
		Loader_Mysql::DBStat()->query($sql);
		
		return Loader_Mysql::DBStat()->affectedRows();
	}
	
	public function get($data,$gameid=1,$con=array()){
		$aWhere = array();
    	$aWhere[] = $data['catid'] ? "catid={$data['catid']}" : "catid=1";
    	$aWhere[] = "status=1" ;
    	
		$sql = "SELECT * FROM $this->dbitem WHERE ".implode(" AND ", $aWhere)." ORDER BY `order` ASC";
		$items = Loader_Mysql::DBStat()->getAll($sql);		
		
		$cats = Data_Category::factory()->getCategory();
		$rtn = array();
		
		$a    = explode('_', $data['navTabId']);
		$flag = count($a);

		foreach ($items as $k=>$item) {
			
			if($data['catid'] == 3 && $gameid == 5){//捕鱼娱牌局记录
				
				if(in_array($item['itemid'], array(23,56,57,58,22,186))){
					continue;
				}
			}

			if(!in_array($data['catid'],array(10,12,9,1)) && $con){	
				//判断 这个项目的数据是否为空 如果是 则不显示。
				$is_null = Data_Stat::factory()->checkItemNull($item['itemid'],$gameid, $con);

				//针对实时数据 不在这个条件范围里面
				if(!$is_null && !in_array($item['itemid'],array('1','8','9','11','4','12','55','62',99,98,177,178,179,180,181,182,183))){
					continue;
				}
			}
			
			if($flag > 2 && $data['catid'] == 7 && $item['itemid'] == 67){
				continue;
			}
			$rtn[$k]            = $item;
			$rtn[$k]['catname'] = $cats[$item['catid']];
		}
		return $rtn;		
	}
	
	public function getList($data,$gameid=1,$con=array()){
		$aWhere = array();
		$aWhere[] = $data['catid'] ? "catid={$data['catid']}" : "catid=1";

		$sql = "SELECT * FROM $this->dbitem WHERE ".implode(" AND ", $aWhere)." ORDER BY `order` ASC";
		$items = Loader_Mysql::DBStat()->getAll($sql);	

		$cats = Data_Category::factory()->getCategory();
		$rtn  = array();
		
		foreach ($items as $k=>$item) {

			if(!in_array($data['catid'],array(10,12)) && $con){	
				$is_null = Data_Stat::factory()->checkItemNull($item['itemid'],$gameid, $con);
				if(!$is_null){
					continue;
				}
			}

			if($flag > 2 && $data['catid'] == 7 && $item['itemid'] == 67){
				continue;
			}
			$rtn[$k]            = $item;
			$rtn[$k]['catname'] = $cats[$item['catid']];
		}

		return $rtn;		
	}
	
	public function getOne($data){
		$itemid = Helper::uint($data['id']);		
		$sql = "SELECT * FROM $this->dbitem WHERE itemid=$itemid LIMIT 1";		
		$rows = Loader_Mysql::DBStat()->getOne($sql);
		
		return $rows;
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
			$sql = "UPDATE $this->dbitem SET `order`=$i WHERE itemid=$id LIMIT 1 ";
			Loader_Mysql::DBStat()->query($sql);
		}
		
		return true;
    }
}