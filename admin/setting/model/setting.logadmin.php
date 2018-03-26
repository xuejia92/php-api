<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Logadmin extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Logadmin();
		}
		
		return self::$_instances;
	}
		
	public function get($data){
		$aWhere = array();
		$data['username']    &&  $aWhere[] = "username='{$data['username']}'";
		$data['model']       &&  $aWhere[] = "model='{$data['model']}'";
		$data['act']         &&  $aWhere[] = "act='{$data['act']}'";
		if($data['stime']){
	    	$stime = date("Y-m-d",strtotime($data['stime']));
	    	$aWhere[] = "ctime>='$stime'";
	    }else{
	    	$stime = date('Y-m-d',strtotime("-15 day"));
	    	$aWhere[]  = "ctime>='$stime'";
	    }
		
		$aWhere[] = $data['etime'] ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
	    
		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
		
		$sql  = "SELECT * FROM $this->adminlog WHERE ".implode(" AND ", $aWhere)." ORDER BY ctime DESC LIMIT  $recordStart,$numPerPage ";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
			
		return array($rows,$stime);
	}
	
	public function getCount($data){
		$aWhere = array();
		$data['username']    &&  $aWhere[] = "username='{$data['username']}'";
		$data['model']       &&  $aWhere[] = "model='{$data['model']}'";
		$data['act']         &&  $aWhere[] = "act='{$data['act']}'";
		if($data['stime']){
	    	$stime = date("Y-m-d",strtotime($data['stime']));
	    	$aWhere[] = "ctime>='$stime'";
	    }else{
	    	$stime = date('Y-m-d',strtotime("-15 day"));
	    	$aWhere[]  = "ctime>='$stime'";
	    }
		$aWhere[] = $data['etime'] ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
	    
		$sql  = "SELECT count(*) as count FROM $this->adminlog WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return (int)$row['count'];
	}
}