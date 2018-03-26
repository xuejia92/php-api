<?php !defined('IN WEB') AND exit('Access Denied!');

class Feedback_Model extends Config_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Feedback_Model();
		}
		
		return self::$_instances;
	}
	
	public function get($data,$aCid,$aCtype){
		$aWhere = array();
		$data['cid']    &&  $aWhere[] = "cid=".$data['cid'];
		$data['ctype']  &&  $aWhere[] = "ctype=".$data['ctype'];
		$data['gameid'] &&  $aWhere[] = "gameid=".$data['gameid'];
		$data['mid']    &&  $aWhere[] = "mid=".$data['mid'];
		$aWhere[] = $data['stime'] ? "ctime>=".strtotime($data['stime']) : "ctime>=".strtotime("-15 days");
		$aWhere[] = $data['etime'] ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
	    $aWhere[] = "status=".Helper::uint($data['status']);
	    
		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
		
		$sql  = "SELECT * FROM (SELECT * FROM $this->feedback ORDER BY ctime desc) `temp` WHERE ".implode(" AND ", $aWhere)." GROUP BY mid ORDER BY ctime DESC LIMIT  $recordStart,$numPerPage ";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		foreach ($rows as $k=>$row){
			$rows[$k]['clientName'] = $aCtype[$row['ctype']];
			$rows[$k]['cname']       = $aCid[$row['cid']];
		}
		
		return $rows;
	}
	
	public function get2excel($data,$aCid,$aCtype){
		$aWhere = array();
		$data['cid']    &&  $aWhere[] = "cid=".$data['cid'];
		$data['ctype']  &&  $aWhere[] = "ctype=".$data['ctype'];
		$data['gameid'] &&  $aWhere[] = "gameid=".$data['gameid'];
		$data['mid']    &&  $aWhere[] = "mid=".$data['mid'];
		$aWhere[] = $data['stime'] ? "ctime>=".strtotime($data['stime']) : "ctime>=".strtotime("-15 days");
		$aWhere[] = $data['etime'] ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
	    $aWhere[] = "status=".Helper::uint($data['status']);
	    		
		$sql  = "SELECT * FROM  $this->feedback WHERE ".implode(" AND ", $aWhere)." ORDER BY ctime DESC ";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		foreach ($rows as $k=>$row){
			$sql    = "SELECT content,rtime FROM $this->feedback_reply WHERE fid={$row['id']}";
			$record = Loader_Mysql::DBMaster()->getOne($sql);
			$rows[$k]['rcontent']   = $record['content'];
			$rows[$k]['rtime']      = $record['rtime'];
			$rows[$k]['clientName'] = $aCtype[$row['ctype']];
			$rows[$k]['cname']      = $aCid[$row['cid']];
		}
		
		return $rows;
	}
		
	public function getCount($data){
		$aWhere = array();
		$data['cid']    &&  $aWhere[] = "cid=".$data['cid'];
		$data['ctype']  &&  $aWhere[] = "ctype=".$data['ctype'];
		$data['gameid'] &&  $aWhere[] = "gameid=".$data['gameid'];
		$data['mid']    &&  $aWhere[] = "mid=".$data['mid'];
		$aWhere[] = $data['stime'] ? "ctime>=".strtotime($data['stime']) : "ctime>=".strtotime("-15 days");
		$aWhere[] = $data['etime'] ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
	    $aWhere[] = "status=".Helper::uint($data['status']);
	    
		$sql  = "SELECT id FROM $this->feedback WHERE ".implode(" AND ", $aWhere)." GROUP BY mid";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function ignore($id){
		$id = Helper::uint($id);
		$sql = "UPDATE $this->feedback SET status = 2 WHERE id=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		return Loader_Mysql::DBMaster()->affectedRows();		
	}

	public function getOne($id){
		$id = Helper::uint($id);		
		$sql = "SELECT * FROM $this->feedback WHERE id=$id LIMIT 1";
		$feedback = Loader_Mysql::DBMaster()->getOne($sql);
		
		if($feedback['status'] == 1){
			$sql = "SELECT rtime,content FROM $this->feedback_reply WHERE fid='{$feedback['id']}' LIMIT 1";
			$reply = Loader_Mysql::DBMaster()->getOne($sql);
			$rows['reply'] = $reply;
		}
		
		$rows['feedback'] = $feedback;
		return $rows;
	}
	
	public function getHistory($mid){
		$mid = Helper::uint($mid);
			
		$sql      = "SELECT id,mid,content,status,ctime,pic FROM $this->feedback WHERE mid=$mid ORDER BY ctime DESC LIMIT 50 ";
		$feedback = Loader_Mysql::DBMaster()->getAll($sql);
		$_rnt = array();
		if($feedback){
			foreach ($feedback as $k => $feed) {
				$_rnt[$k]['feedback'] = $feed; 
				$_rnt[$k]['reply']    = "";
				if($feed['status'] == 1){
					$sql = "SELECT * FROM $this->feedback_reply WHERE fid='{$feed['id']}' LIMIT 1";
					$reply = Loader_Mysql::DBMaster()->getOne($sql);
					$_rnt[$k]['reply'] = $reply;
				}
			}
		}
		return $_rnt;	
	}
	
	public function reply($data){
		$id = Helper::uint($data['id']);
	
		$time = NOW;
		$sql = "INSERT INTO $this->feedback_reply SET fid=$id,content='{$data['content']}',rtime=$time ON DUPLICATE KEY UPDATE content='{$data['content']}', rtime=$time";
		Loader_Mysql::DBMaster()->query($sql);
		
		$flag = Loader_Mysql::DBMaster()->affectedRows();
		if($flag){
			$sql = "UPDATE $this->feedback SET status=1 WHERE id=$id LIMIT 1";
			Loader_Mysql::DBMaster()->query($sql);
			$sql = "SELECT mid FROM $this->feedback WHERE id=$id";
			$row = Loader_Mysql::DBMaster()->getOne($sql);
			foreach (Config_Game::$game as $gameid=>$gameName) {
				Loader_Redis::ote($row['mid'])->hSet(Config_Keys::other($row['mid']), 'feedback', 1);
				Loader_Redis::common()->delete(Config_Keys::feedback($gameid,$row['mid']));
			}
			Loader_Redis::common()->delete(Config_Keys::feedback(100,$row['mid']));
			
			Loader_Tcp::sendMsg2Client($row['mid'])->sendMsg($row['mid'], 10, '');//通知用户客户已经回复反馈
		}

		return $flag;		
	}
	
	public function toSend($data){
		$mid    = Helper::uint($data['mid']);
		$gameid = Helper::uint($data['gameid']);
		$fid    = $data['fid'];
		
		$fid    = $fid ? $fid : NOW;
		$time   = NOW;
		
		$sql = "INSERT INTO $this->feedback_reply SET fid='$fid',mid='$mid',gameid='$gameid',content='{$data['content']}',rtime=$time ON DUPLICATE KEY UPDATE mid='$mid',content='{$data['content']}',gameid=$gameid, rtime=$time";
		Loader_Mysql::DBMaster()->query($sql);
		
		$flag = Loader_Mysql::DBMaster()->affectedRows();
		if($flag){
			Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'feedback', 1);
			Loader_Redis::common()->delete(Config_Keys::feedback($gameid,$mid));
			Loader_Tcp::sendMsg2Client($mid)->sendMsg($mid, 10, '');//通知用户客户已经回复反馈
		}

		return $flag;		
	}
	
	public function getSendOne($data){
		$fid    = $data['fid'];
		
		$sql = "SELECT * FROM $this->feedback_reply WHERE fid='$fid' LIMIT 1 ";
		return Loader_Mysql::DBMaster()->getOne($sql);
	
	}
	
	public function getSend($data){
		$aWhere = array();
		$data['mid']    &&  $aWhere[] = "mid=".$data['mid'];
		$data['gameid'] &&  $aWhere[] = "gameid=".$data['gameid'];
		$aWhere[] = $data['stime'] ? "rtime>=".strtotime($data['stime']) : "rtime>=".strtotime("-15 days");
		$aWhere[] = $data['etime'] ? "rtime<=".strtotime($data['etime']." 23:59:59") : "rtime<=".NOW;
		$aWhere[] = "mid != 0";
	    
		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
		
		$sql  = "SELECT * FROM $this->feedback_reply WHERE ".implode(" AND ", $aWhere)." ORDER BY rtime DESC LIMIT  $recordStart,$numPerPage ";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		return $rows;
	}
	
	public function getSendCount($data){
		$aWhere = array();
		$data['mid']    &&  $aWhere[] = "mid=".$data['mid'];
		$data['gameid'] &&  $aWhere[] = "gameid=".$data['gameid'];
		$aWhere[] = $data['stime'] ? "rtime>=".strtotime($data['stime']) : "rtime>=".strtotime("-15 days");
		$aWhere[] = $data['etime'] ? "rtime<=".strtotime($data['etime']." 23:59:59") : "rtime<=".NOW;
		$aWhere[] = "mid != 0";
	    
		$sql  = "SELECT count(*) as count FROM $this->feedback_reply WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return (int)$row['count'];
	}
	
}