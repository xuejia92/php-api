<?php !defined('IN WEB') AND exit('Access Denied!');
class Main_Model extends Setting_Table{
	
	private static $_instance;
	
	public static function factory(){
		if(!self::$_instance){
			self::$_instance = new Main_Model();
		}
		return self::$_instance;
	}
	
	public function getSid(){
		
		$sql = "SELECT * FROM $this->dbsid";
		
		return Loader_Mysql::DBMaster()->getAll($sql);	
	}
	
	public function getCid(){
		$sql = "SELECT * FROM $this->dbcid";
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	
	public function getPayChannel(){
		$sql  = "SELECT * FROM $this->dbpmode";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		$rtn = array();		
		if($rows){
			foreach ($rows as $row) {
				$rtn[$row['pmode']] = $row['payname'];
			}
		}
		return $rtn;		
	}
	
	public function checkLogin($usrname,$password,$cookie_pix="dianler_adm"){
		$usrname = Loader_Mysql::DBMaster()->escape($usrname);
		$password = Loader_Mysql::DBMaster()->escape($password);

		if(!$usrname || !$password){
			return false;
		}
		
		$password = md5($password);
		$sql = "SELECT * FROM $this->dbadminmember WHERE username='$usrname' AND password='$password' LIMIT 1";
		
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		if($row['username']){
			Helper::setCookie($cookie_pix,$row['username'],7*24*3600);
			return true;
		}else{
			return false;
		}
	}
	
	public function showMsg($url,$tip="操作成功！",$mess=null){
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
	    echo "<html xmlns='http://www.w3.org/1999/xhtml'>";
	    echo "<head>";
	    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
	    echo "<title>无标题文档</title>";
	    echo "<meta http-equiv='refresh' content='2; url={$url} ' />";
	    echo "<style type='text/css'>";
	    echo "*{margin:0;padding:0;}";
	    echo "#notice{margin:10% auto 0 auto; width:20%; border:#09F solid 1px;}";
	    echo "#notice h2{height:25px;line-height:25px; text-align:center; background:#09F;color:#CCC;font-size:14px;}";
	    echo "#notice p{margin:10px 0;height:20px; line-height:20px; text-align:center; font-size:12px;}";
	    echo "#notice a{color:#093}";
	    echo "#notice  a:hover{ color:#F00;text-decoration:underline;}";
	    echo "</style>";
	    echo "</head>";
	    echo "<body>";
	    echo "<div id='notice'>";
	    echo "<h2>系统信息提示</h2>";
	    echo "<p>$tip</p>";
	    echo $mess;
	    echo "<p>2秒后返回指定页面！</p>";
	    echo "<p>如果浏览器无法跳转，<a href=$url>请点击此处</a></p>";
	    echo "</div>";
	    echo "</body>";
	    echo "</html>";
	    die();
	}
	
	public function setAdminLog($username,$m,$p,$act,$req,$ip){
		$time = NOW;
		$sql = "INSERT INTO  $this->adminlog VALUES ('','$username','$m','$p','$act','$req',$time,'$ip')";
		
		Loader_Mysql::DBMaster()->query($sql);
		
		return true;
	}
	
	public function getPrivilege($data,$place,$username){
	    $permission = Loader_Redis::account()->hGetAll(Config_Keys::admins($username));
	    $per = array();
	    if ($permission){
            foreach ($permission as $key=>$value){
                $keyAty = explode(':', $key);
                $where = $keyAty[0];
                if (is_numeric($where)){
                    $item  = $keyAty[1];
                    $type  = $keyAty[2];
                    
                    for ($i=0;$i<=20;$i++){
                        $per[$where][$type][$item][$i] = $value >> $i & 1;
                    }
                }else if ($where == 'nick'){
                    $per[$place][$where] = $value;
                }
            }
            $per = $per[$place];
	    }

        return $per;
	}
	
    public function writePrivilege($data,$permission) {
        $m     = $_REQUEST['m'];
        $p     = $_REQUEST['p'];
        $act   = $_REQUEST['act'];
    
        if (!$m && !$p && !$act){
            return;
        }
        
        $menu      = Main_Menu::$menu;
        $page      = Main_Menu::$page;
        $actList   = Main_Menu::$act;
        
        $menuid    = array();
        foreach ($menu as $menuID=>$menuName){
            if ($m == $menuName){
                $menuid[] = $menuID;
            }
        }
        
        if (!count($menuid)){
            return;
        }
         
        foreach ($menuid as $menid){
            $pageid    = array();
            foreach ($page[$menid] as $pageId=>$pageName){
                if (in_array($p, $pageName)){
                    $pageid[] = $pageId;
                }
            }
            
            if (!count($pageid)){
                return;
            }
        
            foreach ($pageid as $id){
                if (in_array($act, $actList[$menid][$id])){
                    $result = $permission['write'][$menid-1][$id-1];
                    if(!$result){
                        Main_Flag::ret_fail("对不起，你没有相关操作权限");
                    }
                }
            }
        }
    }
    
    public static function specailAccount($name) {
        $write = Loader_Redis::account()->hGet(Config_Keys::admins($name), '3:0:write');
        if ($write >>1 & 1){
            return true;
        }
        return false;
    }
}