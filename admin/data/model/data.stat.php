<?php !defined('IN WEB') AND exit('Access Denied!');

class Data_Stat extends Data_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Data_Stat();
		}
		
		return self::$_instances;
	}
	
	public function getItemDetail($data){		
		$aWhere = array();
		$gameid = Helper::uint($data['gameid']);
		$catid  = Helper::uint($data['catid']);
		$pid    = Helper::uint($data['pid']);
		$cid    = Helper::uint($data['cid']);
		$ctype  = Helper::uint($data['ctype']);
		
		if($data['stime']){
			$stime = $data['stime'];
	    }else{
	    	$stime = date("Y-m-d",strtotime("-15 day"));
	    }

	    $etime = $data['etime'] ? $data['etime'] : date("Y-m-d");
	    $aWhere[] = $data['roomid'] ? "roomid=".Helper::uint($data['roomid']) : "roomid=0";
	    $aWhere[] = "date>= '$stime'";
	    $aWhere[] = "date<= '$etime'";
	    
		$select = "date,amount,itemid";
		if($pid){
			$aWhere[] = "pid=".$pid;
			$filed = 'pid';
			$value = $pid;
			
	    }elseif($cid){
	    	$aWhere[] = "cid=".$cid;
	    	$filed = 'cid';
	    	$value = $cid;
	    }elseif($ctype){
	    	$aWhere[] = "ctype=".$ctype;
	    	$filed = 'ctype';
	    	$value = $ctype;
	    }else{
	    	$aWhere[] = "cid=0";
	    	$aWhere[] = "ctype=0";
	    	$aWhere[] = "pid=0";
	    	$gameid && $aWhere[] = "gameid=".$gameid;
	    	$filed = 'gameid';
	    	$value = $gameid;
	    }

		//选出统计类型下的所有统计项
	    $aItemid = Data_Item::factory()->get($data,$gameid,$aWhere);

	    if(!$aItemid){
	    	return array();
	    }
	    
	    $itemids = $rtn = array();
	    foreach ($aItemid as $key=>$val) {
	    	$itemids[$key] = $val['itemid'];
		    if($data['catid'] == 10){//留存数据，调用回头的
		    	$coverItemid = array(90=>11,89=>55,83=>1,84=>12,85=>2,86=>13,87=>8,88=>4);
		    	$dayConfig   = array(11=>0,55=>0,1=>1,12=>1,2=>3,13=>3,8=>7,4=>7);
		    	$itemids[$key] = $coverItemid[$val['itemid']];
		    }
	    }
	    	    
	    $table = $gameid ? $this->dbsum.$gameid : $this->dbsum;
	    $sql   = "SELECT $select FROM $table WHERE ".implode(" AND ", $aWhere)." AND itemid IN (".implode(" , ", $itemids).")";
	    $rows  = $this->cache($sql);
	    $aDate = $this->showDate($stime, $etime);
	    
	    $dates = array();
	    foreach($aDate as $day){
	    	$dates[$day] = $itemids;
	    }  

	    $lastDay = end($aDate);

	 	foreach ($dates as $day=>$itemids) {
			foreach ($itemids as $itemid) {
				$rtn[$day][$itemid] = 0;
				foreach ($rows as $row) {
					//今日之前的数据 来自数据库
				    if($day == $row['date']  && $itemid == $row['itemid']){
				    	$rtn[$day][$itemid] = $row['amount'] ? $row['amount'] : 0;
				    }
		    	}
		    	
				//今日的实时数据 来自redis
			   if($lastDay == $day && in_array($catid,array('1','8','9','11','4','12'))){
				    $rtn[$day][$itemid] =$this->_getLastData($gameid,$day,$itemid,$filed,$value);
				}
			}
	    }

	    if($data['catid'] == 10){//留存数据
	    	foreach ($rtn as $day=>&$rows) {
	    		foreach ($rows as $itemid=>$val){
	    			$t = date("Y-m-d",strtotime("$day + $dayConfig[$itemid] days"));
	    			$rows[$itemid] = $rtn[$t][$itemid];
	    			if($itemid == 11){
	    				$rows[$itemid] = $rtn[$t][$itemid] + $rtn[$t][55];
	    			}
	    		}
	    	}
	    }

	    return array($rtn,$aItemid,$aDate,$stime);  
	}
	
	private function cache($sql){
		$key = md5($sql);
		$rows = Loader_Redis::common()->get($key);
		
		//if(!$rows){
		    $rows = Loader_Mysql::DBStat()->getAll($sql);
		    date('G') >= 7 && $rows && Loader_Redis::common()->set($key,$rows,false,true,15*24*3600);
		//}
		
		return $rows;
	}
	
	private function showDate($stime,$etime){
		for($date=strtotime($stime);$date <=strtotime($etime);$date+=86400){ 
	    	$array[] = date("Y-m-d",$date); 
		}
		return $array;	
	}
	
	public function getDataShow($data){
		$aWhere = array();
		$gameid = $data['gameid'] ? Helper::uint($data['gameid']) : "";
		$catid  = Helper::uint($data['catid']);
		$pid    = Helper::uint($data['pid']);
		$cid    = Helper::uint($data['cid']);
		$ctype  = Helper::uint($data['ctype']);
		$itemid = Helper::uint($data['itemid']);
		
		if($data['stime']){
			$stime = $data['stime'];
	    }else{
	    	$stime = date("Y-m-d",strtotime("-15 day"));
	    }
	    
	    $etime = strtotime($data['etime']);
	    
	    $etime = $data['etime'] ? date("Y-m-d",strtotime("-1 day",$etime)) : date("Y-m-d",strtotime("-1 day"));
	    
	    $aWhere[] = $data['roomid'] ? "roomid=".Helper::uint($data['roomid']) : "roomid=0";
	    $aWhere[] = "date>= '$stime'";
	    $aWhere[] = "date<= '$etime'";
	    
	    $coverItemid = array(90=>11,89=>55,83=>1,84=>12,85=>2,86=>13,87=>8,88=>4);
		$itemid = $coverItemid[$itemid] ? $coverItemid[$itemid] : $itemid;
	    $aWhere[] = "itemid = '$itemid'";

		$select = "date,amount,itemid";
		if($pid){
			$aWhere[] = "pid=".$pid;
			$filed = 'pid';
			$value = $pid;
			
	    }elseif($cid){
	    	$aWhere[] = "cid=".$cid;
	    	$filed = 'cid';
	    	$value = $cid;
	    }elseif($ctype){
	    	$aWhere[] = "ctype=".$ctype;
	    	$filed = 'ctype';
	    	$value = $ctype;
	    }else{
	    	$aWhere[] = "cid=0";
	    	$aWhere[] = "ctype=0";
	    	$aWhere[] = "pid=0";
	    	$gameid && $aWhere[] = "gameid=".$gameid;
	    	$filed = 'gameid';
	    	$value = $gameid;
	    }
	    
	    $sql = "SELECT $select FROM ".$this->dbsum."$gameid WHERE ".implode(" AND ", $aWhere);
	    $rows = Loader_Mysql::DBStat()->getAll($sql);
		$aDate = $this->showDate($stime, $etime);
		
	    $dates = array();
	    foreach($aDate as $day){
	    	$dates[$day] = 0;
	    }
	   
	    $lastDay = end($aDate);
	    $rtn = array();
	 	foreach ($dates as $day=>$itemids) {
	 	    $rtn[$day] = 0;
			foreach ($rows as $row) {
				if($day == $row['date']){
				    $rtn[$day] = $row['amount'] ? (floatval($row['amount'])) : 0;
				}
		    }
		 	if($lastDay == $day && in_array($catid,array('1','8','9','11','4','12'))){
				$rtn[$day] =$this->_getLastData($gameid,$day,$itemid,$filed,$value);
			}
	    }

		return array($aDate,array_values($rtn));
	}
	
	private function _getLastData($gameid,$day,$itemid,$filed,$value){
		$count = 0;	
		$aData = Loader_Redis::common()->hGetAll(Config_Keys::stat($day, $itemid));
		if(!$aData){
			return $count;	
		}
		foreach ($aData as $key=>$count) {
			$aKey    = explode('-', $key);
			$_gameid = $aKey[0].'gameid';
			$_ctype  = $aKey[1].'ctype';
			$_cid    = $aKey[2].'cid';
			$pid     = $aKey[3];
			
			$countGame[$itemid][$_gameid]           = $count + (int)$countGame[$itemid][$_gameid];
			$countCtype[$itemid][$_gameid][$_ctype] = $count + (int)$countCtype[$itemid][$_gameid][$_ctype];
			$countCid[$itemid][$_gameid][$_cid]     = $count + (int)$countCid[$itemid][$_gameid][$_cid];
			$countPid[$itemid][$_gameid][$pid]      = $count;
		}

		switch ($filed) {
			case 'cid':
			$count = (int)$countCid[$itemid][$gameid.'gameid'][$value.'cid'];
			break;
			case 'pid':
			$count = (int)$countPid[$itemid][$gameid.'gameid'][$value];
			break;
			case 'ctype':
			$count = (int)$countCtype[$itemid][$gameid.'gameid'][$value.'ctype'];
			break;
			case 'gameid':
			$count = (int)$countGame[$itemid][$gameid.'gameid'];
			break;
		}
		
		return $count;
	}
	
	public function getLogPlay($data){
		$aWhere = array();
		$gameid = Helper::uint($data['gameid']);
		$catid  = Helper::uint($data['catid']);
		$pid    = Helper::uint($data['pid']);
		$cid    = Helper::uint($data['cid']);
		$ctype  = Helper::uint($data['ctype']);
	    $stime = $data['stime'] ? strtotime($data['stime']) : strtotime("-6 hour");
	    $etime = $data['etime'] ? strtotime($data['etime']) : NOW;
	    $aWhere[] = "otime>=$stime";
	    $aWhere[] = "otime<=$etime";
	    $aWhere[] = $data['roomid'] ? "roomid=".Helper::uint($data['roomid']) : "roomid=0";
	    
		if($pid){
	    	$aWhere[] = "pid=".Helper::uint($data['pid']);
	    	$sql = "SELECT otime,playsum ,onlinesum FROM $this->dblogonline{$gameid} WHERE ".implode(" AND ", $aWhere);
	    }elseif($cid){
	    	$aWhere[] = "cid=".Helper::uint($data['cid']);
	    	$sql = "SELECT otime,playsum ,onlinesum FROM $this->dblogonline{$gameid} WHERE ".implode(" AND ", $aWhere);
	    }elseif($ctype){
	    	$aWhere[] = "ctype=".Helper::uint($data['ctype']);
	    	$aWhere[] = "cid=0";
	    	$sql = "SELECT otime,sum( `onlinesum` ) onlinesum ,sum( `playsum` ) playsum  FROM $this->dblogonline{$gameid} WHERE ".implode(" AND ", $aWhere)." GROUP BY `otime` ";
	    }else{
	    	$aWhere[] = "ctype IN (1,2)";
	    	$aWhere[] = "cid=0";
	    	$sql = "SELECT otime,sum( `onlinesum` ) onlinesum ,sum( `playsum` ) playsum  FROM $this->dblogonline{$gameid} WHERE ".implode(" AND ", $aWhere)." GROUP BY `otime` ";
	    }
	    

	    $rows = Loader_Mysql::DBStat()->getAll($sql);
	    
	    $aTime = $aPlaysum = $aOnlinesum  = $aContent = $aDate = array();
	    
	    foreach ($rows as $row) {
	    	$aTime[]      = date("m-d H:i",$row['otime']);
	    	$aPlaysum[]   = (int)$row['playsum'];
	    	$aOnlinesum[] = (int)$row['onlinesum'];
	    }

	    return array($aPlaysum,$aOnlinesum,$aTime,$stime,$etime);
	}
	
	public function checkItemNull($itemid,$gameid,$aWhere){
		
		$table = $gameid ? $this->dbsum.$gameid : $this->dbsum;
		$sql = "SELECT SUM(amount) amount FROM $table WHERE ".implode(" AND ", $aWhere)." AND itemid ='$itemid'";
		$row = Loader_Mysql::DBStat()->getOne($sql);
		
		if($row['amount']){
			return true;
		}else{
			return false;
		}
	}
	
	public function getYuleDetail($data) {
	    $stime = $data['stime'] ? strtotime($data['stime']) : strtotime("-14 days");
	    $etime = $data['etime'] ? strtotime($data['etime']) : time();
	    
	    $result = array();
	    while ($stime<$etime){
	        $time = date("Y-m-d",$stime);
    	    $sql = "SELECT itemid,date,amount FROM stat.stat_sum WHERE itemid IN (75,156,184,260) AND date='$time'";
    	    $rows = Loader_Mysql::DBStat()->getAll($sql);

    	    foreach ($rows as $row){
    	        $itme  = $row['itemid'];
    	        $result[$stime][$itme]    = $row['amount'];
    	    }
    	    ksort($result[$stime]);
    	    $stime+=86400;
	    }
	    ksort($result);
	    
	    return $result;
	}
}