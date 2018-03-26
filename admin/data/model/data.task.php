<?php !defined('IN WEB') AND exit('Access Denied!');

class Data_Task extends Data_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Data_Task();
		}
		
		return self::$_instances;
	}
	
	public function getTaskOverview($data){
		$aWhere = array();
		$gameid = $data['gameid'] ? Helper::uint($data['gameid']) : 1;
		$roomid = Helper::uint($data['roomid']);
		$ctime  = $data['ctime'] ? $data['ctime'] : date("Y-m-d",strtotime("-1 days"));
		
		$roomid && $aWhere[] = "level=$roomid";
		$aWhere[] = "ctime='$ctime'";
		
		$table    = "$this->dbtaskdaily$gameid";
		$roomid   = max(1,$roomid);
		switch ($gameid) {
		    case 1:
		        $cachKey   = "task:".$roomid;
		        break;
		    case 3:
		        $cachKey   = "landtask:".$roomid;
		        break;
		    case 4:
		        $cachKey   = "bulltask:".$roomid;
		        break;
		}
		
		$taskData  = Loader_Redis::server()->hGetAll($cachKey);
		
		$taskInfo = array();
		if ($gameid == 1){
		    foreach ($taskData as $key=>$data) {
		        $tmpInfo = explode('|',$data);
		        $taskInfo[$key] = array();
		        $taskInfo[$key]['task:']    = $cachKey;
		        $taskInfo[$key]['taskid']   = $key;
		        $taskInfo[$key]['itemname'] = $tmpInfo[0];
		        $taskInfo[$key]['minmoney'] = $tmpInfo[2];
		        $taskInfo[$key]['maxmoney'] = $tmpInfo[3];
		    }
		}else {
		    foreach ($taskData as $key=>$data) {
		        $tmpInfo = explode('|',$data);
		        $taskInfo[$key] = array();
		        $taskInfo[$key]['task:']    = $cachKey;
		        $taskInfo[$key]['taskid']   = $key;
		        $taskInfo[$key]['itemname'] = $tmpInfo[0];
		        $taskInfo[$key]['minmoney'] = $tmpInfo[1];
		        $taskInfo[$key]['maxmoney'] = $tmpInfo[2];
		    }
		}
		/*$roomid   = max(1,$gameid);
		switch ($roomid) {
			case 1:
			$cachKey   = "task:".$roomid;
			break;
			case 3:
			$cachKey   = "landlordtask:".$roomid;
			break;
		}
		
		$taskData  = Loader_Redis::server()->hGetAll($cachKey);
		
		$taskInfo = array();
		foreach ($taskData as $key=>$data) {
			$tmpInfo = explode('|',$data);
			$taskInfo[$key] = array();
			$taskInfo[$key]['task:']    = $cachKey;
			$taskInfo[$key]['taskid']   = $key;
			$taskInfo[$key]['itemname'] = $tmpInfo[0];
			$taskInfo[$key]['minmoney'] = $tmpInfo[2];
			$taskInfo[$key]['maxmoney'] = $tmpInfo[3];
		}*/
		
		$items = array(
						"任务ID",
						"任务名称",
						"乐券数范围",
						"下发量",
						"完成量",
						"完成率",
						"乐券发放量"
					);
		
		$sql = "SELECT taskid,level AS roomid, SUM( issued ) AS issued, SUM( complete ) AS complete, SUM( roll ) AS roll FROM $table WHERE ".implode(" AND ", $aWhere) ."GROUP BY taskid ";
		$rows = Loader_Mysql::DBStat()->getAll($sql);
		
		$count['issued'] = $count['complete'] = $count['roll'] = $count['proportation'] = 0;
		foreach ($rows as $k=>&$v) {
			$count['issued']   = $v['issued']   + (int)$count['issued'];
			$count['complete'] = $v['complete'] + (int)$count['complete'];
			$count['roll']     = $v['roll']     + (int)$count['roll'];
			$v['itemname'] = $taskInfo[$v['taskid']]['itemname'];
			$v['minmoney'] = $taskInfo[$v['taskid']]['minmoney'];
			$v['maxmoney'] = $taskInfo[$v['taskid']]['maxmoney'];
			$v['proportation'] = $v['issued'] ? (sprintf("%.2f", ($v['complete']/$v['issued'])*100))."%" : '0%';
		}
		$count['proportation'] = $count['issued'] ? (sprintf("%.2f", ($count['complete']/$count['issued'])*100))."%" : '0%';
		return array($items,$rows,$count);
	}
	
	
}