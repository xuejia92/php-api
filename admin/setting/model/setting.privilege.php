<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Privilege extends Setting_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Privilege;
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		
		$username = $data['username'];
		$password = $data['password'];
		
		$setting_get = Helper::uint($data['setting_get']);
		$setting_set = Helper::uint($data['setting_set']);
		
		$monitor_get = Helper::uint($data['monitor_get']);
		$monitor_set = Helper::uint($data['monitor_set']);
		
		$feedback_get = Helper::uint($data['feedback_get']);
		$feedback_set = Helper::uint($data['feedback_set']);
		
		$user_get = Helper::uint($data['user_get']);
		$user_set = Helper::uint($data['user_set']);
		
		$data_get = Helper::uint($data['data_get']);
		$data_set = Helper::uint($data['data_set']);
		
		$pri_setting    = $setting_get      + $setting_set;
		$pri_monitor = $monitor_get   + $monitor_set;
		$pri_feedback   = $feedback_get     + $feedback_set;
		$pri_user       = $user_get         + $user_set;
		$pri_data       = $data_get         + $data_set;
		
		$pri_setting    > 0 && $aPrivilege['setting']      = $pri_setting;
		$pri_monitor > 0 && $aPrivilege['monitor']      = $pri_monitor;
		$pri_feedback   > 0 && $aPrivilege['feedback']     = $pri_feedback;
		$pri_user       > 0 && $aPrivilege['user']         = $pri_user;
		$pri_data       > 0 && $aPrivilege['data']         = $pri_data;
		
		$json_pri = json_encode($aPrivilege);
		
		$id = $data['id'] ? Helper::uint($data['id']) : '';
		
		if(!$username || !$password){
			return false;
		}
		
		if($id){
			//根据用户ID获取玩家的密码，对比数据库中的密码 和 post传递过来的密码。
	        $sql = "SELECT * FROM $this->dbadminmember WHERE id=$id LIMIT 1";
	        $row = Loader_Mysql::DBMaster()->getOne($sql);
	        $oldPassword = $row['password'];
	
	        //如果不相同 表示重新设置了密码，那么就需要 md5。否则不需要md5
	        if($oldPassword != $password){
	            $password = md5($password);
	        }
		}else{
			$password = md5($password);
		}
	   
		$sql = "INSERT INTO $this->dbadminmember SET id='$id',username='$username',password='$password',privilege='$json_pri'
				ON DUPLICATE KEY UPDATE 
				username='$username',password='$password',privilege='$json_pri'";
		
		Loader_Mysql::DBMaster()->query($sql);

		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function del($data){
		if(!$id = Helper::uint($data['id'])){
			return false;
		}
		
		$sql = "DELETE FROM $this->dbadminmember WHERE id=$id LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function get(){
		$sql = "SELECT * FROM $this->dbadminmember";
		$records = Loader_Mysql::DBMaster()->getAll($sql);
		
		foreach ($records as $k=>$record) {
			$str = '';
			$aPri = json_decode($record['privilege'],true);
			$str .= isset($aPri['setting']) ? ($aPri['setting'] == 3 ? '<a style=color:red>设置</a>[查看+修改]':'<a style=color:red>设置</a>[查看]') :"";
			$str .= "&nbsp;&nbsp;";
			$str .= isset($aPri['monitor']) ? ($aPri['monitor'] == 3 ? '<a style=color:red>监控</a>[查看+修改]':'<a style=color:red>监控</a>[查看]') :"";
			$str .= "&nbsp;&nbsp;";
			$str .= isset($aPri['user']) ? ($aPri['user'] == 3 ? '<a style=color:red>用户</a>[查看+修改]':'<a style=color:red>用户</a>[查看]') :"";
			$str .= "&nbsp;&nbsp;";
			$str .= isset($aPri['feedback']) ? ($aPri['feedback'] == 3 ? '<a style=color:red>反馈</a>[查看+修改]':'<a style=color:red>反馈</a>[查看]') :"";
			$str .= "&nbsp;&nbsp;";
			$str .= isset($aPri['data']) ? ($aPri['data'] == 3 ? '<a style=color:red>数据</a>[查看+修改]':'<a style=color:red>数据</a>[查看]') :"";
			$str .= "&nbsp;&nbsp;";
			$records[$k]['privilege'] = $str;
		}
		
		return $records;
	}
	
	public function checkPrivilege($username,$m,$act,$p=''){
		if(!$username){
			Main_Flag::ret_fail("对不起，你没有查看的权限");
		}
		
		if($m == 'main'){
			return false;
		}
		
		$sql    = "SELECT * FROM $this->dbadminmember WHERE username='$username'";
		$aPri = Loader_Mysql::DBMaster()->getOne($sql);
		
		$privilege = json_decode($aPri['privilege'],true);
		
		if(!$privilege[$m] && $m != 'action'){
			Main_Flag::ret_fail("对不起，你没有查看的权限");
		}
		
		$opt_pri['setting']  = array('set','del','sort');
		$opt_pri['feedback'] = array('set','del','sort','ignore','reply','reloadFeedback');
		$opt_pri['monitor']  = array('set','del','sort');
		$opt_pri['user']     = array('set','del','sort','resetBankPassword','resetAccountPassword');
		$opt_pri['data']     = array('set','del','sort','updateStatus');
		
		if($p == 'privilege' && in_array($act,array('set','del')) && !in_array($username,array("gary","luofengcai"))){
			Main_Flag::ret_fail("对不起，你没有相关操作权限");
		}
		
		if($privilege[$m] != 3 && isset($opt_pri[$m]) && in_array($act,$opt_pri[$m])){
			Main_Flag::ret_fail("对不起，你没有相关操作权限");
		}		
	}
	
	public function getOne($data){
		$id = Helper::uint($data['id']);
		$sql = "SELECT * FROM $this->dbadminmember WHERE id=$id LIMIT 1";
		$record = Loader_Mysql::DBMaster()->getOne($sql);
		$record['privilege'] = json_decode($record['privilege'],true);
		return $record;
	}
}