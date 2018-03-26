<?php !defined('IN WEB') AND exit('Access Denied!');

class Member extends Config_Table
{
    private  static $_instance = array();
    private $cache = array();
    
    /**
     * @return Member 
     */
    public static function factory(){
        if(!is_object(self::$_instance['members'])){
            self::$_instance['members'] = new Member;
        }

        return self::$_instance['members'];        
    }

    /**
     * 根据平台ID与站点ID获取用户信息
     * 
     * @author gaifyyang
     * @param string  $sitemid 用户平台ID
     * @param int     $sid     站点ID
     * @param bool    $inCache 是否从缓存中获取
     * @return array 
     */
    public function getOneBySitemid($sitemid, $sid=100, $inCache=true){       
        $sid     = Helper::uint($sid);    
        $sitemid = Loader_Mysql::dbmaster()->escape($sitemid);
         
        if(!$sitemid || !$sid){
            return array();
        }
        $mid = $this->sitemid2mid($sitemid, $sid);  

        return $mid ? $this->getOneById($mid, $inCache) : array();
    }
	
	/**
     * 根据用户账号查找用户信息
     * 
     * @param string  $sitemid 用户平台ID
     * @param int     $sid     站点ID
     * @param bool    $inCache 是否从缓存中获取
     * @return array 
     */
    public function getOneByUserName($username, $sid=102, $inCache=false){       
        $sid     = Helper::uint($sid);    
        
		$query = "SELECT sitemid FROM $this->username_register WHERE username LIKE '%$username%'";  	
        $record = Loader_Mysql::DBMaster()->getAll($query);
		
		$ret = array();
		foreach($record as $key=>$val){
			$sitemid = $val['sitemid'];
			$mid = $this->sitemid2mid($sitemid, $sid);
			$info = $this->getOneById($mid, $inCache);
			$ret[] = $info;
		}

        return $ret;
    }
	
	/**
     * 根据手机号查找用户信息
     * 
     * @author gaifyyang
     * @param string  $sitemid 用户平台ID
     * @param int     $sid     站点ID
     * @param bool    $inCache 是否从缓存中获取
     * @return array 
     */
    public function getOneByUserPhone($phone, $sid=101, $inCache=false){       
        $sid     = Helper::uint($sid);    
        
		$query = "SELECT sitemid FROM $this->phonenumber_register WHERE phoneno = '$phone'";  	
        $record = Loader_Mysql::DBMaster()->getOne($query);
		$sitemid = $record['sitemid'];
		$mid = $this->sitemid2mid($sitemid, $sid);    
        return $mid ? $this->getOneById($mid, $inCache) : array();
    }
    
	/**
     * 根据用户昵称查找用户信息
     * 
     * @author gaifyyang
     * @param string  $sitemid 用户平台ID
     * @param int     $sid     站点ID
     * @param bool    $inCache 是否从缓存中获取
     * @return array 
     */
    public function getUserByNick($mnick,$inCache=true){       
        
		$query = "SELECT * FROM {$this->userinfo}0 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record0 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}1 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record1 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}2 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record2 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}3 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record3 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}4 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record4 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}5 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record5 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}6 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record6 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}7 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record7 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}8 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record8 = Loader_Mysql::DBMaster()->getAll($query);
		
		$query = "SELECT * FROM {$this->userinfo}9 WHERE mnick LIKE '%$mnick%' LIMIT 50";  	
        $record9 = Loader_Mysql::DBMaster()->getAll($query);
		
		$ret = array_merge($record0, $record1,$record2,$record3,$record4,$record5,$record6,$record7,$record8,$record9); ;
		   
        return $ret;
    }
	
    /**
     * 根据用户的游戏ID获取用户信息
     * 
     * @author gaifyyang
     * @param  int  $mid      用户游戏ID
     * @param  bool $inCache  是否从缓存中获取
     * @return array
     */
    public function getOneById($mid, $inCache=true){
        if(! $mid = Helper::uint($mid)){
            return array();
        }

        $userInfo = $this->getUserInfo($mid,$inCache);
         
       // file_put_contents("bbbbbbbbbbbb1112.txt", var_export($mid,true),FILE_APPEND);
       // file_put_contents("bbbbbbbbbbbb1112.txt", var_export($userInfo,true),FILE_APPEND);
        
        
        $gameInfo = $this->getGameInfo($mid);    
        
       // file_put_contents("bbbbbbbbbbbb1113.txt", var_export($gameInfo,true),FILE_APPEND);
        
        $info = array_merge($gameInfo,$userInfo);
        
       // file_put_contents("bbbbbbbbbbbb1113.txt", var_export($info,true),FILE_APPEND);
        
        return $info['mid'] == $mid ? $info : array(); 
    }
    
    /**
     * 获取gameinfo表里的个人信息
     * 
     * @author gaifyyang
     * @param  int       $mid
     * @param  blooean   $incache
     * @return array  
     */
    public function getUserInfo($mid, $incache = true){
    	if( !$mid = Helper::uint($mid) ){
    	    
    	    
    		return array();
    	}   	
    	
    	if($this->cache['userinfo'][$mid]){
    		return $this->cache['userinfo'][$mid];
    	}
    	
    	$userInfoKey = Config_Keys::getUserInfo($mid);
    	$cacheFlag = true;
    	
    	
    	//file_put_contents("bbbbbbbbbbbb1117.txt", var_export($userInfoKey,true),FILE_APPEND);
    	
    	if($incache){
    		$userInfo = Loader_Redis::minfo($mid)->hGetAll($userInfoKey);    
    		
    		//file_put_contents("bbbbbbbbbbbb1118.txt", var_export($userInfo,true),FILE_APPEND);
    		
        	$userInfo = is_array($userInfo) ? $userInfo : array();  
    		if($userInfo['mid'] != $mid){
        		$cacheFlag = false;
        	}else{
        		return $userInfo;
        	}
    	}
    	
    	
    	if( !$cacheFlag || !$incache ){
    		$table = $this->getTable($mid, $this->userinfo, 10);
    		$query = "SELECT * FROM $table WHERE mid = '$mid'";		
            $userInfo = Loader_Mysql::DBMaster()->getOne($query, MYSQL_ASSOC); 
            $userInfo['ip'] = long2ip($userInfo['ip']);           
            Loader_Redis::minfo($mid)->hMset($userInfoKey, $userInfo, 2*24*3600);
    	}
    	   	
    	return $userInfo['mid'] == $mid ? $userInfo : array();   
    }
    
    /**
     * 获取userinfo表里的游戏信息
     * 
     * @author gaifyyang
     * @param  int       $mid
     * @param  blooean   $incache
     * @return array  
     */
    public function getGameInfo($mid){
    	if( !$mid = Helper::uint($mid) ){
    		return array();
    	}
    	
    	if($this->cache['gameinfo'][$mid]){
    		return $this->cache['gameinfo'][$mid];
    	}
    	
    	$gameInfo = Loader_Tcp::callServer($mid)->get($mid);
    	if(!$gameInfo){
    		return array();
    	}
    	
		//roll和roll数据 操作
		global $lang;
		$n1 = (int)(date("Y",NOW))+1;
		$n2 = (int)(date("Y",NOW))+2;
		$lang_roll = Config_Lang::$roll[$lang] ? Config_Lang::$roll[$lang] : Config_Lang::$roll[1];
		$gameInfo['rollString']  = $lang_roll.$n1.'/1/1';//先过期的
		$gameInfo['roll1String'] = $lang_roll.$n2.'/1/1';//后过期的

		$m = date("m",NOW);
		if($m == "11" || $m == '12'){
			$tempRollString = $gameInfo['rollString'];
			$gameInfo['rollString'] = $gameInfo['roll1String'];
			$gameInfo['roll1String'] = $tempRollString;
			$this->setRoll2exp($mid, $gameInfo['roll'], $gameInfo['roll1']);
		}
		
		if($m == "1"){
			$this->delExpRoll($mid, $gameInfo['roll1']);
		}
		
    	return $gameInfo;
    }
    
    //把乐券移动到待过期字段
    private function setRoll2exp($mid,$roll,$roll1){
    	if(!$roll){
    		return false;
    	}
    	
    	if($roll1){
    		return false;
    	}

    	$result = Loader_Tcp::callServer($mid)->setRoll($mid, -$roll);	//	减roll
    	if($result){
			Loader_Tcp::callServer($mid)->setRollExp($mid, $roll);//加roll1
		}
    	
    	return true;
    }
    
    //删除过期乐券
    private function delExpRoll($mid,$roll1){
    	if(!$roll1){
    		return false;
    	}
    	
		Loader_Tcp::callServer($mid)->setRollExp($mid, -$roll1);//减roll1
		return true;
    }
    
    /**
     * 根据平台ID与站点ID转换mid
     * 
     * @author gaifyyang
     * @param string  $sitemid 用户平台ID
     * @param int     $sid     站点ID
     * @return int|blooean 
     */
    public function sitemid2mid( $sitemid,$sid ){
    	$sitemid = Loader_Mysql::dbmaster()->escape($sitemid);
    	$sid     = Helper::uint($sid);
    	if( !$sitemid || !$sid ){
    		return false;
    	}
    	
    	if($mid = Config_Game::$make2special[$sitemid]){//特殊账号
    		return $mid;
    	}
    	
    	$cacheKey = Config_Keys::sitemid2mid($sitemid, $sid);
    	$mid = Loader_Redis::minfo($sitemid)->get($cacheKey,false,false);

    	if ( !$mid ) {
    		$query = "SELECT mid FROM $this->sitemid2mid WHERE sitemid='$sitemid' AND sid='$sid' ORDER BY mid DESC LIMIT 1";  	
        	$record = Loader_Mysql::DBMaster()->getOne($query);
        	$mid = $record['mid'];
        	Loader_Redis::minfo($sitemid)->set($cacheKey, $mid,false,false,50*24*3600);
    	}

    	return $mid ? $mid : false;
    }
    
    /**
     * 用户注册
     * 
     * @author gaifyyang
     * @param  array $userInfo 用户信息
     * @return array
     */
    public function insert( &$userInfo ){
    	if(empty($userInfo)){
		    return array();
		}
		$time = NOW;
		
		
		//file_put_contents("aaaaaaaaaaaaaa40.txt", var_export($userInfo,true));
		
		
		$sitemid     =  Loader_Mysql::DBMaster()->escape($userInfo['sitemid']);
		$mnick       =  Loader_Mysql::DBMaster()->escape($userInfo['mnick']);
		$sid         =  Helper::uint($userInfo['sid']);
		$cid         =  $userInfo['cid'];
		$pid         =  $userInfo['pid'];
		$ctype       =  $userInfo['ctype'];
		$mtime       =  json_encode(array($userInfo['gameid']=>$time));
		$rand_sex    =  mt_rand(1, 2);
		$sex         =  isset($userInfo['sex']) ? Helper::uint($userInfo['sex']) : $rand_sex;
		$hometown    =  Loader_Mysql::DBMaster()->escape($userInfo['hometown']);	
		$device_name = Loader_Mysql::DBMaster()->escape($userInfo['device_name']);
		$os_version  = Loader_Mysql::DBMaster()->escape($userInfo['os_version']);
		$net_type    = Helper::uint($userInfo['net_type']);
		$ip          = ip2long($userInfo['ip']);
		$device_no   = $userInfo['device_no'];
		$gameid      = $userInfo['gameid'];
		
        $mid   = $this->_createMid($sitemid,$sid,$device_no);    
        
        $openid = $userInfo['openid'];
        
        
        if(in_array($mid,Config_Pay::$specialAccount)){//如果新注册的用户与保留的特殊账号相同，则重新生成一个mid
        	$mid   = $this->_createMid($sitemid,$sid,$device_no);   
        }
        
        $table = $this->getTable($mid, $this->userinfo, 10);
        
        $query = "INSERT INTO $table SET mid='$mid',sitemid='$sitemid',mnick='$mnick',sid='$sid',mtime='$mtime',
          		 ip='$ip',sex='$sex',hometown='$hometown',cid='$cid',pid='$pid',ctype='$ctype',devicename='$device_name',
          		 osversion='$os_version',nettype='$net_type',openid='$openid'";
        
        
        Loader_Mysql::DBMaster()->query($query);
        
        if ( Loader_Mysql::DBMaster()->affectedRows() ) {
        	$table = $this->getTable($mid, $this->gameinfo, 10);
	        $query = "INSERT INTO $table SET mid=$mid, gameid='$gameid'";
	        Loader_Mysql::DBMaster()->query($query);
	        $flag = Loader_Mysql::DBMaster()->affectedRows();
	        
	        //$registerMoney = $cid == 15 ? Config_Money::$spfirstin : Config_Money::$firstin[$userInfo['gameid']];
            $registerMoney = Config_Money::$firstin[$gameid];
            
            //file_put_contents("MMMMMMMMMMMMMMMM111110.txt", var_export($registerMoney,true),FILE_APPEND);
            
	        Logs::factory()->addWin($gameid,$mid, 1,$sid, $cid, $pid,$ctype, 0,$registerMoney);//首注加钱
	        
	        return  $flag ?  $this->getOneById($mid) : array();
        }
                
        return array();
    }
    
    
    
    /**
     * 微信登录插入微信注册表
     *
     * @author jackyu
     * @param  array $userInfo 用户信息
     * @return array
     */
    public function insertwx($wxinfo){
        
        $query = "INSERT INTO `uc_register_wx` SET mid=".$wxinfo["mid"].", sitemid='".$wxinfo["sitemid"]."',nic_name='".$wxinfo["nic_name"]."',openid='".$wxinfo["openid"]."'";
       
file_put_contents("MMMMMMMMMMMMMMMM111110.txt", var_export($query,true),FILE_APPEND); 
        $flag = Loader_Mysql::DBMaster()->query($query);
        
        return $flag;
    }
    
    
    
    /**
     * 
     * 登陆时分配密钥给用户
     * @param int $mid
     */
 	public function setMtkey($mid,$deviceno){
 		
 		$flag = Loader_Redis::common()->get("$deviceno|mtkey",false,false);
 		
 		if(!$flag){//每个机器每天只生产一次
 			$mtkey       = md5(NOW . $mid . '$#@!^');
			$userInfoKey = Config_Keys::getUserInfo($mid);
			Loader_Redis::minfo($mid)->hSet($userInfoKey, 'mtkey', $mtkey);
			Loader_Redis::common()->set("$deviceno|mtkey",1,false,false,Helper::time2Seven());
			
 		}else{
 			$userInfoKey = Config_Keys::getUserInfo($mid);
 			
			$mtkey = Loader_Redis::minfo($mid)->hGet($userInfoKey, 'mtkey');
			
			if(!$mtkey){
				$mtkey       = md5(NOW . $mid . '$#@!^');
				$userInfoKey = Config_Keys::getUserInfo($mid);
				
				Loader_Redis::minfo($mid)->hSet($userInfoKey, 'mtkey', $mtkey);
				
				Loader_Redis::common()->set("$deviceno|mtkey",1,false,false,Helper::time2Seven());
			}
 		}
		
		return $mtkey;   
    }

    /**
     * 每天第一次登陆更新相关信息
     * 
     * @author gaifyyang
     * @param int  $mid   用户ID
     * @return boolean|int 失败返回false，成功返回影响行数
     */
    public function addLogin(&$userInfo){
    	if (!$mid = Helper::uint($userInfo['mid'])) {
    		return false;
    	}
    	
    	$userInfo['devicename'] = Loader_Mysql::DBMaster()->escape($userInfo['devicename']);
	    $userInfo['mnick']      = Loader_Mysql::DBMaster()->escape($userInfo['mnick'] );

		$time = NOW; 
    	$aMactivetime = $userInfo['aMactivetime'];
    	$aMentercount = $userInfo['aMentercount'];
    	$aMactivetime[$userInfo['gameid']] = $time;
    	$aMentercount[$userInfo['gameid']] = (int)$aMentercount[$userInfo['gameid']] + 1;
    	$mactivetime  = json_encode($aMactivetime);
    	$mentercount  = json_encode($aMentercount);
    	
    	$mtimeadd = "";
    	if($userInfo['mentercount'] == 0){//新注册用户，添加注册时间和游戏ID
    		if($userInfo['sid'] != 100 ){//游客登陆不验证  2015-4-24
	    		$f = $this->accountDisconnector($userInfo['gameid'], $userInfo['reg_pid'], $userInfo['pid'],$userInfo['mid']);
	    		if(!$f){//检查账号隔离
	    			Config_Flag::mkret(Config_Flag::status_account_error);
	    		}
    		}
    		$aMtime = $userInfo['aMtime'];
    		$aMtime[$userInfo['gameid']] = $time;
    		$mtime  = json_encode($aMtime);
    		$mtimeadd = ",mtime = '$mtime'";
    		//$table = $this->getTable($mid, $this->gameinfo, 10);
    		//$sql = "INSERT INTO $table SET mid='$mid', gameid='{$userInfo['gameid']}' ON DUPLICATE KEY UPDATE gameid='{$userInfo['gameid']}'";
    		//Loader_Mysql::DBMaster()->query($sql);
    	}

    	$ip = ip2long($userInfo['ip']);
    	$table = $this->getTable($mid, $this->userinfo, 10);
    	$query = "UPDATE $table SET mactivetime='$mactivetime', mentercount='$mentercount',cid='{$userInfo['cid']}',pid='{$userInfo['pid']}',
    			  ctype='{$userInfo['ctype']}',ip='{$ip}',devicename='{$userInfo['devicename']}',versions='{$userInfo['versions']}',
    			  osversion='{$userInfo['osversion']}',nettype='{$userInfo['nettype']}',mnick='{$userInfo['mnick']}' $mtimeadd
    	 	      WHERE mid='$mid' LIMIT 1";
    	Loader_Mysql::DBMaster()->query($query);
    	$flag = Loader_Mysql::DBMaster()->affectedRows();
    	
    	if($flag){
    		$userInfoKey = Config_Keys::getUserInfo($mid);
    		$info = Loader_Redis::minfo($mid)->hMget($userInfoKey,array('mactivetime','mentercount','versions','ip','devicename','osversion','nettype','cid','pid','sid','ctype','mnick'));
    		$info = is_array($info) ? $info : array();
    		$userInfo['mentercount'] == 0 && $info['mtime'] = $mtime;
    		$info['mactivetime'] = $mactivetime;
    		$info['mentercount'] = $mentercount;
    		$info['versions']    = $userInfo['versions'];
    		$info['pid']         = $userInfo['pid'];
    		$info['ip']          = $userInfo['ip'];
    		$info['devicename']  = $userInfo['devicename'];
    		$info['osversion']   = $userInfo['osversion'];
    		$info['nettype']     = $userInfo['nettype'];
    		$info['cid']     	 = $userInfo['cid'];
    		$info['sid']         = $userInfo['sid'];
    		$info['ctype']       = $userInfo['ctype'];
    		$info['mnick']       = trim($userInfo['mnick']);
        	return Loader_Redis::minfo($mid)->hMset($userInfoKey, $info, 2*24*3600);
    	}
    	
    	return false;   	
    }
    
    /**
     * 获取多个用户的资料
     *
     * @param {array} $mids 用户游戏ID列表
     *
     * @return array
     */
    public function getAllByIds(array $mids){
        if(!$mids){
            return array();
        }
        
        $mids = array_unique($mids);    
        $keys = $list = $return = array();
        
        foreach ($mids as $mid) {
        	$list[$mid] = Member::factory()->getOneById($mid);
        }
        
        //确保顺序传入来的一致
        foreach($mids as $mid){
            empty($list[$mid]) ? '' : ($return[] = $list[$mid]);
        }
        
        return (array)$return;
    }
    
	/**
	 * 
	 * 更新等级到数据库
	 * @author GaifyYang
	 * 
	 * @param int $mid
	 * @param int $score 积分
	 * @return int 等级 
	 */
	public function updateLevel( $mid,$score ){
		$level = $this->getNewLevel($mid, $score);
		
		$gameInfo = Member::factory()->getGameInfo($mid);
		
		//有新级，则更新数据库存
		if ($gameInfo['level'] !=  $level) {
			$table = $this->getTable($mid, $this->gameinfo, 10);
    	
	    	$sql = "UPDATE $table SET level = $level WHERE mid = $mid  LIMIT 1";  	
	    	Loader_Mysql::DBMaster()->query($sql);
		}
		
		return $level;
	}
	
    /**
     * 
     * 得到自增mid
     */
    private function _createMid($sitemid,$sid,$device_no){
    	$ip    = Helper::getip();
    	$time  = NOW;
    	$query = "INSERT INTO $this->sitemid2mid SET mid='',sitemid='$sitemid',sid='$sid',deviceno='$device_no',ip='$ip',time='$time'";
    	Loader_Mysql::DBMaster()->query($query);
    	
    	return Loader_Mysql::DBMaster()->insertID();
    }
    
	/**
	 * 
	 * 取得分表的表名
	 * @param int $id  
	 * @param int $tableName 分表的表名
	 * @param int $num  分表的个数
	 * @param int $flag  是否返回原表
	 */
	public function getTable( $id, $tableName, $num, $flag = false ){
		$tableID = $id % $num ;		
		$table = $flag == true ? $tableName : $tableName.$tableID;	

		return $table;
	}
    
    /**
     * 
     * 在原表里取用户信息的数据
     * @param int $mid
     */
    private function _getUserInfoFromOriginal( $mid ){
    	$query = "SELECT * FROM $this->userinfo WHERE mid = '$mid'";			
        return Loader_Mysql::DBMaster()->getOne($query, MYSQL_ASSOC);
    }
    
    /**
     * 
     * 在原表里取用户的游戏数据
     * @param int $mid
     */
	private function _getGameInfoFromOriginal( $mid ){
    	$query = "SELECT * FROM $this->member WHERE mid = '$mid'";			
        return Loader_Mysql::DBMaster()->getOne($query, MYSQL_ASSOC);
    }
    
	/**
	 * 通过IOS手机设备号获取sitemid
	 * 
	 */
	public function getSitemidByIosKey($deviceNo,$openudid)
	{		
		$deviceNo = Loader_Mysql::DBMaster()->escape($deviceNo);
		$openudid = Loader_Mysql::DBMaster()->escape($openudid);
		$openudid = $openudid ? $openudid : '000000';
		
		$table = $this->ios_register;
		$query = " SELECT sitemid
		           FROM {$table}
				   WHERE device_no='{$deviceNo}' OR openudid='$openudid'
				   LIMIT 1 ";

        $info = Loader_Mysql::DBMaster()->getOne($query);
        $sitemid = $info['sitemid'];

        if ( !$sitemid ) {
        	$sitemid = $this->_createSitemid();
			if($sitemid){
				$query = " INSERT INTO {$table}
		           SET sitemid='{$sitemid}',device_no='{$deviceNo}', openudid='$openudid'";
	    
				Loader_Mysql::DBMaster()->query($query);
			}        	
        }else{
        	$query = " UPDATE  {$table} SET openudid='$openudid' WHERE sitemid=$sitemid LIMIT 1";
			Loader_Mysql::DBMaster()->query($query);
        }
        
        return $sitemid;
        
	}
	
	/**
	 * 通过android手机设备号获取sitemid
	 * @param {string} $deviceNo 设备号
	 * @return int
	 */
	public function getSitemidByKey($deviceNo)
	{
		$table = $this->android_register;
		
	    $deviceNo = Loader_Mysql::DBMaster()->escape($deviceNo);

        $query = " SELECT sitemid
		           FROM {$table}
				   WHERE device_no='{$deviceNo}'
				   LIMIT 1 ";

        $info = Loader_Mysql::DBMaster()->getOne($query);
        $sitemid = $info['sitemid'];

        if ( !$sitemid ) {
        	$sitemid = $this->_createSitemid();
			if($sitemid){
				$query = " INSERT INTO {$table}
		           SET sitemid='{$sitemid}',device_no='{$deviceNo}' ";
	    
				Loader_Mysql::DBMaster()->query($query);
			}        	
        }

        return $sitemid;
	}
	
	/**
	 * 通过用户名获取 sitemid
	 * @param {string} $userName 用户名
	 * @param {string} $password 密码
	 * @return int
	 */
	public function getSitemidByuserName($username,$password)
	{
	    $username = Loader_Mysql::DBMaster()->escape($username);
	    $password = md5($password);

        $query = " SELECT sitemid,username FROM {$this->username_register} WHERE username='{$username}' AND password='{$password}' LIMIT 1 ";
        $info = Loader_Mysql::DBMaster()->getOne($query);
        $sitemid = $info['sitemid'];

        return $sitemid ? $info : false;
	}
	
	
	
	
	/**
	 * 通过openid获取 sitemid
	 * @param {string} $openid openid
	 * @param {string} $nic_name 昵称
	 * @return int
	 */
	public function getSitemidByopenId($openid,$nic_name = ""){
	    
	    $query = " SELECT sitemid,nic_name FROM {$this->wx_register} WHERE openid='{$openid}' LIMIT 1 ";
	    
	    $info = Loader_Mysql::DBMaster()->getOne($query);
	    
	    //$sitemid = $info['sitemid'];
        return $info ? $info : false;
	}
	
	
	
	
	
	
	
	/**
	 * 通过手机号获取sitemid
	 * @param {string} $phoneNum 手机号码
	 * @return int
	 */
	public function getSitemidByPhoneNumber($phoneNo,$password)
	{
	    $phoneNo  = Loader_Mysql::DBMaster()->escape($phoneNo);
	    $password = md5($password);
	    	    
        $query = " SELECT sitemid,phoneno FROM {$this->phonenumber_register} WHERE phoneno='{$phoneNo}' AND password='{$password}' LIMIT 1 ";
        
        $info = Loader_Mysql::DBMaster()->getOne($query);
        $sitemid = $info['sitemid'];
        
        return $sitemid ? $info : false;
	}
	
	/**
	 * 手机号码注册
	 * @param {string} $phoneNum 手机号码
	 * @param  type 1 手机号码注册  2 android游客账号绑定
	 * @return int
	 */
	public function registerByPhoneNumber(&$data,$type=1){
		
		$phoneno  = Loader_Mysql::DBMaster()->escape($data['phoneno']);
		$password = $data['password'];
		
		if(!$phoneno || !$password){
			return false;
		}
		
		if($type == 2){
			$mid = Helper::uint($data['mid']);
		}
		
		$password = md5($password);
		$data['sitemid'] = $sitemid = $this->_createSitemid();
		if($sitemid){
			$query = " INSERT INTO {$this->phonenumber_register} SET sitemid='{$sitemid}',phoneno='{$phoneno}',password='{$password}'";	    
			Loader_Mysql::DBMaster()->query($query);			
			$flag = $type == 1 ? (bool)$this->insert($data) : (bool)$this->guestBinding($sitemid,$data['mid']);//用户信息表	
						
			return $flag;
		}
		
		return false;	
	}
	
	/**
	 * 
	 * 游客绑定手机号码
	 */
	public function guestBinding($sitemid,$mid){
		if(!$sitemid || !$mid){
			return false;
		}
		$tbgameinfo = $this->getTable($mid, $this->gameinfo, 10);
		$tbuserinfo = $this->getTable($mid, $this->userinfo, 10);
		
		$sql = "UPDATE $tbuserinfo SET sitemid='$sitemid',sid=101 WHERE mid='$mid' LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
				
		$sql = "UPDATE $this->sitemid2mid SET sitemid='$sitemid',sid=101 WHERE mid=$mid LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		$oldUser    = $this->getOneById($mid);
		$oldSitemid = $oldUser['sitemid'];
		
		Loader_Redis::account()->hSet(Config_Keys::bangdingRecord(), $oldSitemid, $sitemid.':101');//记录游客绑定新账号的sitemid影射
    	Loader_Redis::minfo($oldSitemid)->delete(Config_Keys::sitemid2mid($oldSitemid, 100));
    	Loader_Redis::minfo($mid)->hMset(Config_Keys::getUserInfo($mid),array('sitemid'=>$sitemid,'sid'=>101));

		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	/**
	 * 
	 * 游客绑定点乐账号
	 */
	public function guest2dianlerAccount($username,$password,$mid){

		$password = md5($password);
		$sitemid = $this->_createSitemid();
		
		if(!$sitemid){
			return false;
		}
		$query = " INSERT INTO $this->username_register SET sitemid='{$sitemid}',username='{$username}',password='{$password}'";	    
		Loader_Mysql::DBMaster()->query($query);
		
		$tbgameinfo = $this->getTable($mid, $this->gameinfo, 10);
		$tbuserinfo = $this->getTable($mid, $this->userinfo, 10);
		
		$sql = "UPDATE $tbuserinfo SET sitemid='$sitemid',sid=102 WHERE mid='$mid' LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
				
		$sql = "UPDATE $this->sitemid2mid SET sitemid='$sitemid',sid=102 WHERE mid=$mid LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		$oldUser    = $this->getOneById($mid);
		$oldSitemid = $oldUser['sitemid'];
		
		Loader_Redis::account()->hSet(Config_Keys::bangdingRecord(), $oldSitemid, $sitemid.':102');//记录游客绑定新账号的sitemid影射
    	Loader_Redis::minfo($oldSitemid)->delete(Config_Keys::sitemid2mid($oldSitemid, 100));
		Loader_Redis::minfo($mid)->hMset(Config_Keys::getUserInfo($mid),array('sitemid'=>$sitemid,'sid'=>102));
		return Loader_Mysql::DBMaster()->affectedRows();
	}

	/**
	 * 用户名注册
	 */
	public function registerByUserName(&$data){
		$username = Loader_Mysql::DBMaster()->escape($data['username']);
	    $password = $data['password'];
	    
	    if(!$username || !$password){
	    	return false;
	    }
	    
	    $password = md5($password);
		$data['sitemid'] = $sitemid = $this->_createSitemid();
		
		//file_put_contents("ucenterlog.txt", var_export($data,true),FILE_APPEND);
		
		if($sitemid){
			$query = " INSERT INTO {$this->username_register} SET sitemid='{$sitemid}',username='{$username}',password='{$password}' ";	    
			Loader_Mysql::DBMaster()->query($query);			
			$userInfo = $this->insert($data);//用户信息表			
			return $userInfo['mid'];
		}
		
		return false;	
	}	
	
	/**
	 * 用户名绑定手机（开始绑定）
	 */
	public function userNameBinding($sitemid,$phoneno){
		$sitemid = Helper::uint($sitemid);
		$phoneno = Loader_Mysql::DBMaster()->escape($phoneno);
		
		if(!$sitemid || !$phoneno){
			return false;
		}
		
		$sql = "SELECT sitemid FROM $this->phonenumber_register WHERE phoneno = '$phoneno' LIMIT 1";
		$record = Loader_Mysql::DBMaster()->getOne($sql);
		if($record){//判断之前是否已经用该手机号码注册
			return false;
		}
		
		$sql = "SELECT sitemid FROM $this->account_binding WHERE status =1 AND ( sitemid=$sitemid OR phoneno='$phoneno' ) LIMIT 1";
		$record = Loader_Mysql::DBMaster()->getOne($sql);
		if($record['sitemid']){
			return false;
		}	
		
		$time = NOW;

		$sql = "INSERT INTO $this->account_binding SET sitemid='$sitemid',phoneno='$phoneno',ctime='$time' 
			   ON DUPLICATE KEY UPDATE sitemid ='$sitemid',phoneno = '$phoneno',ctime ='$time'";
		
		Loader_Mysql::DBMaster()->query($sql);
		
		return true;		
	}
	
	/**
	 * 更新绑定状态（绑定成功）
	 */
	public function updateBindingStatus($sitemid,$mid){
		$sitemid = Helper::uint($sitemid);
		$mid     = Helper::uint($mid);
		if(!$sitemid || !$mid ){
			return false;
		}
		
		$sql = "UPDATE $this->account_binding SET status=1 WHERE sitemid=$sitemid LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
			
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'binding', 1);
		return Loader_Mysql::DBMaster()->affectedRows();
	}
		
	/**
	 * 更改密码
	 * 
	 */
	public function resetPassword($sitemid,$sid,$oldpassword,$newpassword){
		
		$sitemid = Helper::uint($sitemid);
		$sid     = Helper::uint($sid);
		
		if(!$sid|| !$sitemid ){
			return 0;
		}

		$table = $sid == 101 ? $this->phonenumber_register : $this->username_register ;
		$oldpassword = md5($oldpassword);		
		$sql = "SELECT password FROM $table WHERE sitemid=$sitemid LIMIT 1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		
		if($oldpassword != $row['password']){
			return -1;
		}
		
		$newpassword = md5($newpassword);		
		$sql = "UPDATE $table SET password='$newpassword' WHERE sitemid=$sitemid LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		return  Loader_Mysql::DBMaster()->affectedRows() ? 1 : 0;
	}
	
	/**
	 * 通过手机号获取账号和密码
	 */
	public function getPasswordByphoneNo($phoneno){
		if(!$phoneno = Loader_Mysql::DBMaster()->escape($phoneno)){
			return false;
		}

		$sql    = "SELECT * FROM $this->phonenumber_register WHERE phoneno='$phoneno' LIMIT 1";
		$record = Loader_Mysql::DBMaster()->getOne($sql);

		$table = $this->phonenumber_register;
		if(!$record){
			$sql    = "SELECT * FROM $this->account_binding WHERE phoneno='$phoneno' LIMIT 1";
			$record = Loader_Mysql::DBMaster()->getOne($sql);
			$table = $this->username_register;
		}
		
		if(!$record){
			return false;
		}
		
		$newPassword = mt_rand(100000, 999999);
		$md5passerod = md5($newPassword);

		$sql = "UPDATE $table SET password='$md5passerod' WHERE sitemid='{$record['sitemid']}' LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		if($table == $this->phonenumber_register){
			return array('username'=>$record['phoneno'],'password'=>$newPassword);
		}else{
			$sql = "SELECT username FROM $this->username_register WHERE sitemid='{$record['sitemid']}'";
			$array = Loader_Mysql::DBMaster()->getOne($sql);
			return array('username'=>$array['username'],'password'=>$newPassword);
		}
	}
	
	/**
	 * 通过密匙获取密码
	 * 
	 * string $userName 账号名称
	 * string $secretkey 安全密匙
	 * 
	 */
	function getPasswordBySecretkey($userName,$secretkey){
		$userName  = Loader_Mysql::DBMaster()->escape($userName);
		$secretkey = trim($secretkey);
		
		if(!$userName || !$secretkey ){
			return false;
		}
		
		$sql = "SELECT sitemid FROM $this->username_register WHERE username='$userName' LIMIT 1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		$sitemid = $row['sitemid'];
		$mid = $this->sitemid2mid($sitemid, 102);
		$server_secretkey = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'secretkey');
		
		$newPassword = mt_rand(100000, 999999);
		$md5passerod = md5($newPassword);
		
		if(!$sitemid || !$mid || !$server_secretkey){
			return false;
		}

		$sql = "UPDATE $this->username_register SET password='$md5passerod' WHERE sitemid='$sitemid' LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		
		if($server_secretkey == $secretkey){
			return array('username'=>$userName,'password'=>(string)$newPassword);
		}
		
		return false;
	}
	
	/**
	 * 
	 * 获取手机号码
	 * @param int $sitemid
	 * @param int $type  1 showhand_account_binding 2 showhand_register_phonenumber
	 */
	public function getPhoneno($sitemid,$type){
		$sitemid = Helper::uint($sitemid);
		$type    = Helper::uint($type);
		
		if(!$sitemid || !$type){
			return false;
		}
		
		/*
		if($type == 1){
			$sql = "SELECT phoneno FROM $this->account_binding WHERE sitemid='$sitemid' AND status=1 LIMIT 1";
		}else{
			$sql = "SELECT phoneno FROM $this->phonenumber_register WHERE sitemid='$sitemid' LIMIT 1 ";
		}
		*/
		
		$sql = "SELECT phoneno FROM $this->account_binding WHERE sitemid='$sitemid' AND status=1 LIMIT 1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		
		if(!$row['phoneno']){
			$sql = "SELECT phoneno FROM $this->phonenumber_register WHERE sitemid='$sitemid' LIMIT 1 ";
			$row = Loader_Mysql::DBMaster()->getOne($sql);
		}

		return $row['phoneno'] ? $row['phoneno'] : '';
	}
	
	public function leyoLogin($sessionid){
		if(!trim($sessionid)){
			return array();
		}
		$req['m']  = 'Query_user';
		$req['sid']  = $sessionid;
		$req['cpid'] = '127';
		$key = 'dc51dde5cfa44fce917d10754be75687';
		ksort($req, SORT_STRING);
		
		$s = '';
		foreach ($req as $value) {
			$s .= $value.'&';
		}

		$s .= $key;
		$req['checksign'] = md5($s);
		
		$return = Helper::curl('http://sdk.leyogame.cn/api', $req,'get');
		if(!$return){//重试
			$return = Helper::curl('http://sdk.leyogame.cn/api', $req,'get');
			if(!$return){
				return array();
			}
		}
		
		$oo = new SimpleXMLElement($return);
		
		$item       = $oo->data;
		$mnick      = (string)$item['nickName'];
		$sitemid    = (string)$item['uid'];
		$checksign  = (string)$item['checksign'];

		if(!$sitemid){
			return array();
		}
		
		return array('sitemid'=>$sitemid,'mnick'=>$mnick);
	}

	
	
	
  	/**
     * 修改用户资料
     * 
     * @author gaifyyang
     * @param  int    $mid
     * @param  array  $aSet 要修改的内容
     * @return array|blooean  
     */
	public function setUserInfo($mid, $aSet){
		if((! $mid = Helper::uint( $mid)) || (! is_array( $aSet)) || ! count( $aSet)){
			return false;
		}

		//替换掉一些特殊字符

		$aSet['mnick'] = Loader_Mysql::DBMaster()->escape($aSet['mnick']);
		$allow = array('mnick', 'sex', 'hometown', 'versions', 'sitemid', "sid",'ip','devicename','osversion','nettype'); //允许更改的字段
		
		$cacheKey = Config_Keys::getUserInfo($mid);
		$aInfo = Loader_Redis::minfo( $mid )->hGetAll( $cacheKey );
		$aInfo = is_array($aInfo) ? $aInfo : array();  

		$fields = array();
		foreach ($aSet as $key => $value){
			if( in_array($key, $allow, true)){
				$fields[] = "`$key`='{$value}'";
				$aInfo[$key] = $value;
			}
		}
		
		if(count($fields) <= 0) return false;
		
		$table = $this->getTable($mid, $this->userinfo, 10);
		
		$query = "UPDATE $table SET " . implode(',', $fields) . " WHERE mid='$mid' LIMIT 1";
		$flag = Loader_Mysql::DBMaster()->query( $query );
		$isUpdate = Loader_Mysql::DBMaster()->affectedRows();

		$aInfo['mid'] == $mid && Loader_Redis::minfo($mid)->hMset($cacheKey, $aInfo, 2*24*3600);

		//file_put_contents("KLLLLLLLLLLLLLLL111.txt", var_export($aInfo,true),FILE_APPEND);
		
		if ( $flag ) {
			return $aSet;
		}
		
		return false;
	}

 	/**
     * 
     * 得到register_sitemid表的自增Sitemid
     */
    private function _createSitemid(){
    	$sSql = "INSERT INTO {$this->register_sitemid} (sitemid) VALUES ('')";        	
    	Loader_Mysql::DBMaster()->query($sSql);       	
		return (int)Loader_Mysql::DBMaster()->insertID();
    }
    
	/**
     * 取出头像
    */
	public  function getIcon($sitemid,$mid,$size='middle',$gameid=1,$sex=1){
		//$iconDomain  = //"http://cdnicon.dianler.com/icon/";
	    
	    /* 
	    'big' => 'http://www.baidu.com/icon/5817/5225817_big.jpg?v=0',
	    'middle' => 'http://www.baidu.com/icon/5817/5225817_middle.jpg?v=0',
	    'icon' => 'http://www.baidu.com/icon/5817/5225817_icon.jpg?v=0',
	    
	    5225816
	     */
	    
        //$iconDomain  = "http://192.168.1.12/flower/data/icon/";
        
        $iconDomain = Config_Inc::$iconDomain; //域名路径
        
        //5816
		$subname     = $mid % 10000;
		
		$iconVersion = (int)Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'iconVersion');
		$icon = $iconDomain. $subname . '/' . $mid . '_'.$size.'.jpg?v='.$iconVersion;	
		
		
		return $icon;
	}

	public function saveMoney2Bank($mid,$money,$gameid){
		$mid   = Helper::uint($mid);
		$money = Helper::uint($money);
		
		if(!$mid || !$money){
			return false;
		}
		
		$table = $this->getTable($mid, $this->gameinfo, 10);
		
		$return = Loader_Tcp::callServer($mid)->setMoney($mid, -$money);
		
		if($return){
			$gameInfo = Loader_Tcp::callServer($mid)->setBank($mid, $money);
			$this->setBankLog($mid, $money, $gameInfo['money'], $gameInfo['freezemoney'], $gameid, 1);
			return $gameInfo;
		}
		
		return false;
	}
	
	
	public function getMoneyFromBank($mid,$money,$gameid){
		$mid   = Helper::uint($mid);
		$money = Helper::uint($money);
		
		if(!$mid || !$money){
			return false;
		}
		
		$return = Loader_Tcp::callServer($mid)->setBank($mid, -$money);
		
		if($return){
			$gameInfo = Loader_Tcp::callServer($mid)->setMoney($mid, $money);
			$this->setBankLog($mid, $money, $gameInfo['money'], $gameInfo['freezemoney'], $gameid, 2);
			return $gameInfo;
		}
		
		return false;
	}
	
	public function transfer($mid,$tomid,$money,$gameid,$sid, $cid, $pid, $ctype,$fee,$prefreezemoney){
		$mid   = Helper::uint($mid);
		$money = Helper::uint($money);
		
		if(!$mid || !$money){
			return false;
		}
		
		$return = Loader_Tcp::callServer($mid)->setBank($mid, -$money);
		
		if($return){
			$return = Loader_Tcp::callServer($tomid)->setBank($tomid, $money);
		}
		
		if($return){
			$flag = Logs::factory()->addWin($gameid,$mid, 23, $sid, $cid, $pid, $ctype, 1, $fee); 
			$recerGameinfo     = Member::factory()->getOneById($tomid);
	    	$recfreezemoney    = $recerGameinfo['freezemoney'];
	    	$recprefreezemoney = $recfreezemoney - $money;
	    	$gameInfo    = Loader_Tcp::callServer($mid)->get($mid);
	    	$money_now   = $gameInfo['money'];
	    	$freezemoney = $gameInfo['freezemoney'];
	    	
	    	$this->setBankLog($mid, $money, $money_now, $freezemoney, $gameid, 3,$tomid,$prefreezemoney,$recprefreezemoney,$recfreezemoney,$fee);
	    	
	    	return array('money'=>$money_now,'freezemoney'=>$freezemoney);
		}

		return false;
	}
	
	/**
	 * 
	 * 获取转账记录 
	 * @param int $mid
	 */
	public function getTransferLog($mid,$gameid=2){
		if(!$mid = Helper::uint($mid)){
			return false;
		}
		
		$table = $this->logbank.$gameid;
		
		$sql = "SELECT mid,amount,tomid,ctime FROM $table WHERE type=3 AND (mid=$mid OR tomid=$mid) ORDER BY ctime DESC LIMIT 30";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		foreach ($rows as $k=>$row) {
			$rows[$k]['type'] = 1;
			//$rows[$k]['tomid']  = $row['tomid'];
			if($mid != $row['mid']){
				$rows[$k]['type'] = 2;
				$rows[$k]['tomid']  = $row['mid'];
			}
		}
		
		return $rows;
	}
	
	public function setBankLog($mid,$amount,$money_now,$freezemoney,$gameid,$type,$tomid='',$prefreezemoney='',$recprefreezemoney='',$recfreezemoney='',$fee=''){
		$ctime = NOW;
		$table = $this->logbank.$gameid;
		if($gameid == 2 ){
			$sql = "INSERT INTO $table set 
    			`mid` = $mid,
    			`type`=$type,
    			`amount`='$amount',
    			`money`='$money_now',
    			`freezemoney`='$freezemoney',
    			`tomid`='$tomid',
    			`fee`='$fee',
    			`ctime`='$ctime',
    			`prefreezemoney`='$prefreezemoney',
    			`recprefreezemoney`='$recprefreezemoney',
    			`recfreezemoney`='$recfreezemoney'";
		}else{
			$sql = "INSERT INTO $table SET mid='$mid',type='$type',amount='$amount',money='$money_now',freezemoney='$freezemoney',tomid='$tomid',ctime='$ctime'";
		}
		
		Loader_Mysql::DBLogchip()->query($sql);
		return Loader_Mysql::DBLogchip()->affectedRows();
	}
	
	/**
	 *
	 * 加减乐卷
	 * @param int $mid
	 * @param int $roll
	 */
	public function setRoll($mid, $roll){
		$mid  = Helper::uint($mid);
		$roll = (int)$roll;

		if(!$mid ||!$roll){
			return false;
		}
		
		$table = $this->getTable($mid, $this->gameinfo, 10);
		if($roll<0){
			$query = "SELECT * FROM $table WHERE mid = '$mid'";
			$gameInfo = Loader_Mysql::DBMaster()->getOne($query, MYSQL_ASSOC);

			$nowRoll  = $gameInfo['roll']?$gameInfo['roll']:0;
			$nowRoll1 = $gameInfo['roll1']?$gameInfo['roll1']:0;
			$totalRoll = $nowRoll+$nowRoll1;

			$tmpRoll = $roll*(-1);
			//先判断总数是否够扣除
			if($totalRoll<$tmpRoll){return false;}

			//如果是总数刚好等于要扣除的数
			if($totalRoll==$tmpRoll){
				$sql = "UPDATE $table SET roll=0,roll1=0 WHERE mid=$mid LIMIT 1";
			}else{
				//根据月份优先扣除哪个字段
				$m = date("m",NOW);
				if($m == "11" || $m == '12'){
					//11月1号到12月31号先检查roll1是否够扣除
					if($nowRoll1>$tmpRoll){
						$sql = "UPDATE $table SET roll1=roll1+$roll WHERE mid=$mid LIMIT 1";
					}else{
					//不需要考虑两个都小于需要扣除的数的情况，因为前面已经做过判断
						$v   = $roll+$nowRoll1;
						$sql = "UPDATE $table SET roll=roll+$v,roll1=0 WHERE  mid=$mid LIMIT 1";
					}
				}else{
					//其他月份先检查roll是否够扣除
					if($nowRoll>$tmpRoll){
						$sql = "UPDATE $table SET roll=roll+$roll WHERE mid=$mid LIMIT 1";
					}else{
					//不需要考虑两个都小于需要扣除的数的情况，因为前面已经做过判断
						$v = $roll+$nowRoll;
						$sql = "UPDATE $table SET roll=0,roll1=roll1+$v WHERE mid=$mid LIMIT 1";
					}
				}
			}
		}else{
			$sql = "UPDATE $table SET roll=roll+$roll WHERE mid=$mid LIMIT 1";
		}
		
		Loader_Mysql::DBMaster()->query($sql);
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	/**
	 * 统计排行榜 
	 */
	public function setRankList($gameid, $limit = 100, $type = "money"){
		
		if(!$limit = Helper::uint($limit)){
			return false;
		}
		
		if(!in_array($type, array('money','roll'))){
			return false;
		}
		
		switch ($type){
			case 'money':
				$orderBy = "money+freezemoney";
				break;
			case 'play':
				$orderBy = "wintimes+losetimes";
				break;
			case 'totaltime':
				$orderBy = "totaltime";
				break;
			case 'roll':
				$orderBy = "roll+roll1";
				break;			
		}
		

		$query = '';
		$like = $gameid == 0 ? "1" : " gameid LIKE '%$gameid%'";
		for($i=0;$i<10;$i++){
			$table = $this->gameinfo.$i;
			if($i>0){
				$query .= " UNION ALL ";
			}            
			$query .= "(SELECT mid,money,freezemoney,roll,roll1 FROM {$table} WHERE $like  ORDER BY {$orderBy} DESC,mid ASC LIMIT $limit)";
			if($i==9){
				$query .= " ORDER BY {$orderBy} DESC,mid ASC LIMIT $limit";
			}
		}
	
		$rows = Loader_Mysql::DBMaster()->getAll($query);

		foreach ($rows as $k=>$row) {
			$gameInfo = Member::factory()->getUserInfo($row['mid']);
			$rows[$k]['mnick'] = $gameInfo['mnick'];
		}
	
		if($rows){
			Loader_Redis::common()->set(Config_Keys::rank($gameid,$type), $rows);
		}

		return true;
	}
	
	/**
	 * 随机生成账号
	 */
	public function issuedAccount(){
		$aRank    = array('a','b','c','d','e','f','g','h','i','j','k','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$aIndex   = array_rand($aRank, 3);
		$rank_num = mt_rand(100, 999);
		$username = $aRank[$aIndex[0]].$aRank[$aIndex[1]].$aRank[$aIndex[2]].$rank_num;
				
		$flag = Loader_Redis::account()->sContains(Config_Keys::userName(), $username,false,false);
		if(!$flag){
			return $username;
		}
		
		return false;
	}
	
	/**
	 * 
	 * 把一个账号转移到另外一个账号（供保留的账号使用）
	 * @param int $oldmid 旧的ID 
	 * @param int $newmid 新的ID
	 */
	public function changeMid2another($oldmid,$newmid){
		$oldmid = Helper::uint($oldmid);
		$newmid = Helper::uint($newmid);
		
		if(!$oldmid || !$newmid){
			return false;
		}
		
		$tbuserinfo1 = $this->getTable($oldmid, $this->userinfo, 10);
		$tbuserinfo2 = $this->getTable($newmid, $this->userinfo, 10);
		$tbgameinfo1 = $this->getTable($oldmid, $this->gameinfo, 10);
		$tbgameinfo2 = $this->getTable($newmid, $this->gameinfo, 10);
		
		$sql = "SELECT a.*,b.* FROM $tbuserinfo1 a ,$tbgameinfo1 b  WHERE a.mid=$oldmid AND b.mid=$oldmid ";
		$oldUserinfo = Loader_Mysql::DBMaster()->getOne($sql);
		
		if($oldUserinfo){
			extract($oldUserinfo);
			$sql = "INSERT INTO $tbuserinfo2
    			SET mid=$newmid,sid='$sid',cid='$cid',ctype='$ctype',pid='$pid',mnick='$mnick',sitemid='{$sitemid}',sex='$sex',hometown='$hometown',mstatus='$mstatus',mactivetime='$mactivetime',mentercount='$mentercount',mtime='$mtime',versions='$versions',ip='$ip',devicename='$devicename',osversion='$osversion',nettype='$nettype'
    			ON DUPLICATE KEY UPDATE 
    			sid='$sid',cid='$cid',ctype='$ctype',pid='$pid',mnick='$mnick',sitemid='{$sitemid}',sex='$sex',hometown='$hometown',mstatus='$mstatus',mactivetime='$mactivetime',mentercount='$mentercount',mtime='$mtime',versions='$versions',ip='$ip',devicename='$devicename',osversion='$osversion',nettype='$nettype'";
			Loader_Mysql::DBMaster()->query($sql);
			
			$sql = "INSERT INTO $tbgameinfo2
    			SET mid=$newmid,money='$money',coins='$coins',freezemoney='$freezemoney',roll='$roll',exp='$exp'
    			ON DUPLICATE KEY UPDATE 
    			money='$money',coins='$coins',freezemoney='$freezemoney',roll='$roll',exp='$exp'";
			Loader_Mysql::DBMaster()->query($sql);
		}
		
		$oldInfo   = $this->getGameInfo($oldmid);
		$oldMoney  = $oldInfo['money'];
		$oldFree   = $oldInfo['freezemoney'];
		
		$newInfo   = $this->getGameInfo($newmid);
		$newMoney  = $newInfo['money'];
		$newFree   = $newInfo['freezemoney'];
		
		$flag1 = $this->setMoney($oldmid, -$oldMoney);
		$flag2 = $this->setMoney($newmid, -$newMoney);
		Loader_Tcp::callServer($oldmid)->setBank($oldmid, -$oldFree);
		if($flag1 && $flag2){
			$flag = $this->setMoney($newmid, $oldMoney);
			 Loader_Tcp::callServer($newmid)->setBank($newmid, $oldFree);
		}
		
		return $flag;
	}
	
	public function setMoney($mid,$money){
        $flag =  Loader_Tcp::callServer($mid)->setMoney($mid,$money);//通知server加钱
        
        if(!$flag){//失败的情况再加一加一次，防止server数据没初始化
        	Loader_Tcp::callServer($mid)->get($mid);//初始化server内存数据
        	$flag = Loader_Tcp::callServer($mid)->setMoney($mid,$money);
        }
        return $flag;
	}
	
	public function getDeviceNo(&$param){
		$device_no = '';
		
		switch ($param['ctype']) {
			case 1:
				if($param['param']['device_no'] && !in_array($param['param']['device_no'], array('111111111111111','Unknown'))){
					$device_no = $param['param']['device_no'];
				}else{
					$device_no = $param['param']['uuid'] ? $param['param']['uuid'] : Helper::getip();
				}
								
				break;
			case 2:
			case 3:
				$os = (int)$osv = substr($param['param']['os_version'] , 0, 1);
				$device_no = $os>=7 ?  $param['param']['advertising'] : $param['param']['mac_address']; 
				$device_no = str_replace(":","",$device_no);
				$device_no = $device_no ? $device_no : Helper::getip();
			break;
			case 4:
				$device_no = Helper::getip();
			break;
		}
		
		//ile_put_contents("log.txt","device_no :: ".$device_no,FILE_APPEND);
		
		return $device_no;
	}
	
	public function accountDisconnector($clientGameid,$serverPid,$clientPid,$mid=''){
		
		$switch_bit = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$serverPid);
		$flag       = $switch_bit >> 10 & 1;
		if($flag){
			$limitGameid = Base::factory()->getGameidByPid($serverPid);
			if($clientGameid != $limitGameid){//在本包注册的账号不能去其他游戏登陆
				Logs::factory()->debug(array('mid'=>$mid,'clientGameid'=>$clientGameid,'serverPid'=>$serverPid,'clientPid'=>$clientPid),'accountDisconnector1');
				return false;
			}
		}
		
		$switch_bit = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$clientPid);
		$flag       = $switch_bit >> 10 & 1;
		if($flag){
			$register_gameid = Base::factory()->getGameidByPid($serverPid);
			if($register_gameid != $clientGameid){//其它包注册的账号不能在本包登陆
				Logs::factory()->debug(array('mid'=>$mid,'clientGameid'=>$clientGameid,'serverPid'=>$serverPid,'clientPid'=>$clientPid),'accountDisconnector2');
				return false;
			}
		}
				
		return true;
	}
	
	public function getDevicenoBymid($mid){
		$mid = Helper::uint($mid);
		$sql = "SELECT deviceno FROM $this->sitemid2mid WHERE mid='$mid'";
		$row = Loader_Mysql::DBSlave()->getOne($sql);
		return $row['deviceno'];
	}
	
	public function getMidByDeviceno($device){
		$device = Loader_Mysql::DBSlave()->escape($device);
		$sql  = "SELECT mid FROM $this->sitemid2mid WHERE deviceno='$device'";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);

		if($rows){
			$records = array();
			foreach ($rows as $row) {
				$records[] = $row['mid'];
			}
		}
		return $records;
	}
	
	public function getMidByIp($ip){
		$ip = Loader_Mysql::DBSlave()->escape($ip);
		$sql  = "SELECT mid FROM $this->sitemid2mid WHERE ip='$ip'";
		$rows = Loader_Mysql::DBSlave()->getAll($sql);
		
		if($rows){
			$records = array();
			foreach ($rows as $row) {
				$records[] = $row['mid'];
			}
		}
		return $records;
	}
	
	public function getUserNameBySitemid($sitemid,$sid){
		if($sid == 101){
			$table = $this->phonenumber_register;
			$filed = 'phoneno';
		}else{
			$table = $this->username_register;
			$filed = 'username';
		}
		
		$sql = "SELECT $filed FROM $table WHERE sitemid='$sitemid' LIMIT 1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		
		return $row[$filed];
	}
	
	public function getUserInfoByUserName($username){
		$username = Loader_Mysql::DBMaster()->escape($username);
		if(!$username){
			return false;
		}
		
		$sql = "SELECT sitemid FROM $this->username_register WHERE username='$username' LIMIT 1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		$sitemid = $row['sitemid'];
		
		if(!$sitemid){
			return false;
		}
		
		return Member::factory()->getOneBySitemid($sitemid,102);
	}
	
	public function setVip($mid,$gameid,$viptime){
		$exptime    = (int)$viptime;
		$viptype    = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);
		$vipexptime = Loader_Redis::account()->ttl(Config_Keys::vip($mid));//如果之前购买了VIP，则天数累加
		$vipexptime = Helper::uint($vipexptime) ?  ceil(Helper::uint($vipexptime)/86400) : 0;
		$exptime    = $exptime + $vipexptime;
		
		$vipday = $viptime;
		if($viptype == 2 || !$viptype){
			$vipday = $vipday < 30  ? 7 : $vipday;
		}

		switch ($vipday) {
    		case 365://年卡
    			Loader_Redis::account()->set(Config_Keys::vip($mid), 100,false,false,24*3600*$exptime);
    		break;
    				
    		case 90://季卡
    			$type = $viptype == 100 ? 100 : 10;
    			Loader_Redis::account()->set(Config_Keys::vip($mid), $type,false,false,24*3600*$exptime);
    		break;
   
    		case 7://周卡
    			$type = $viptype > 2 ? $viptype : 2;
    			Loader_Redis::account()->set(Config_Keys::vip($mid), $type,false,false,24*3600*$exptime);
    		break;
    				
    		default://月卡
    			$type = $viptype >= 10 ? $viptype : 1;
    			Loader_Redis::account()->set(Config_Keys::vip($mid), $type,false,false,24*3600*$exptime);
    		break;
    	}
    	
    	$gameid     = 3;
	    $exptime    = (int)$viptime;
		$vipexptime = Loader_Redis::account()->ttl(Config_Keys::props($gameid,$mid));//如果之前购买了计牌器，则天数累加
		$vipexptime = Helper::uint($vipexptime) ?  ceil(Helper::uint($vipexptime)/86400) : 0;
		$exptime    = $exptime + $vipexptime;
		Loader_Redis::account()->set(Config_Keys::props($gameid,$mid), 1,false,false,24*3600*$exptime);
		Loader_Tcp::callServer($mid)->setMoney($mid,0);//通知server 处理在房间玩牌的情况

    	return true;
	}
	
	public function getMaxmid(){
		$sql = "SELECT mid FROM $this->sitemid2mid ORDER BY mid DESC LIMIT 1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		
		return $row['mid'];
	}
	
	public function getMnickByRand(){
		
		$mid = mt_rand(1000, 50000);
		
		$userInfo = Member::factory()->getUserInfo($mid);
		//Loader_Mysql::DBMaster()->close();//server模式调用时，防止端口用完
		$mnick    = $userInfo['mnick'];
		
		if(!$mnick){
			$mnick = 'GT-S7562';
		}
		
		$this->clearCache("userinfo",$mid);
		
		$mnick = rtrim($mnick);
		
		return $mnick;
	}
	
	/**
	 * 根据游戏获取输赢记录
	 */
	public function getPlayHistoryCount($mid,$gameid){
		$accountInfo = Loader_Redis::ote($mid)->hGetAll(Config_Keys::other($mid));//获取账号的其它信息
		$wintimes    = (int)$accountInfo['wi:'.$gameid];//赢的次数
		$losetimes   = (int)$accountInfo['ls:'.$gameid];//输的次数
		
		return $wintimes + $losetimes;
	}
	
	/**
	 * 根据绑定的手机号码登陆
	 */
	public function getAccountByBinding($phone,$passwd){
		
		$sql = "SELECT sitemid FROM $this->account_binding WHERE phoneno='$phone' AND status=1 LIMIT 1";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		
		$sitemid = $row['sitemid'];
		
		if(!$sitemid){
			return false;
		}
		
		$password = md5($passwd);
		$sql = "SELECT sitemid FROM $this->username_register WHERE sitemid='$sitemid' AND password='$password' LIMIT 1";
		$info = Loader_Mysql::DBMaster()->getOne($sql);
		
		$info['username'] = $phone;
		$sitemid = $info['sitemid'];
        
        return $sitemid ? $info : false;
	}
	
	public function rankDevice($type='deviceno'){
		$sql  = "SELECT *, count( mid ) total FROM $this->sitemid2mid WHERE $type !=''  GROUP BY $type ORDER BY total DESC LIMIT 100";
		$rows = Loader_Mysql::DBSlave()->getAll($sql);
		
		$record = array();
		foreach ($rows as $k=>$row) {
			$record[$k]['total']    = $row['total'];
			$record[$k]['mid']      = $row['mid'];
			$record[$k]['deviceno'] = $row['deviceno'];
			$record[$k]['ip']       = $row['ip'];
		}
		
		Loader_Redis::common()->set(Config_Keys::devicerank($type),$record,false,true,24*3600); 
	}
	
	public function rankDeviceBydate($date,$type='deviceno'){
		$stime  = strtotime($date);
		$etime  = strtotime($date." 23:59:59");
		$sql    = "SELECT *, count( mid ) total FROM $this->sitemid2mid WHERE $type !='' AND time > '$stime' AND time<=$etime GROUP BY $type ORDER BY total DESC LIMIT 100";
		$rows   = Loader_Mysql::DBSlave()->getAll($sql);
		
		$record = array();
		foreach ($rows as $k=>$row) {
			$record[$k]['total']    = $row['total'];
			$record[$k]['mid']      = $row['mid'];
			$record[$k]['deviceno'] = $row['deviceno'];
			$record[$k]['ip']       = $row['ip'];
		}
		
		Loader_Redis::common()->set(Config_Keys::deviceRankByDate($date,$type),$record,false,true,15*24*3600); 
	}
	
	//增加记牌器
	public function optProps($gameid,$mid,$exptime){
		$vipexptime = Loader_Redis::account()->ttl(Config_Keys::props($gameid,$mid));//如果之前购买了道具，则天数累加
		$vipexptime = Helper::uint($vipexptime) ?  ceil(Helper::uint($vipexptime)/86400) : 0;
		$exptime = $exptime + $vipexptime;
		Loader_Redis::account()->set(Config_Keys::props($gameid,$mid), 1,false,false,24*3600*$exptime);
	}
	
	public function setGunStyle($mid,$gameid,$style,$viptime){
	    
	    $exptime       = (int)$viptime;
	    $remainingtime = Loader_Redis::account()->ttl(Config_Keys::gunStyle($mid, $style));//如果之前购买了，则天数累加
	    $remainingtime = Helper::uint($remainingtime) ?  Helper::uint($remainingtime) : 0;
	    $exptime       = 24*3600*$exptime;
	    $exptime       = $exptime + $remainingtime;
	    
        Loader_Redis::account()->set(Config_Keys::gunStyle($mid, $style), 1,false,false,$exptime);
        
        Loader_Redis::ote($mid)->hSet("OTE|$mid", 'currentStyle', $style);
	
	    return true;
	}
	
	public function clearCache($mode,$mid=''){
		if($mid){
			unset($this->cache[$mode][$mid]);
		}else{
			unset($this->cache[$mode]);
		}
	}

    public function bindingopenid($wxinfo) {
        if (!$wxinfo['mid'] || !$wxinfo['openid']) {
            return false;
        }

        $table = $this->getTable($wxinfo['mid'], $this->userinfo, 10);
        $sql = "UPDATE $table SET openid = '{$wxinfo['openid']}' WHERE mid = {$wxinfo['mid']}  LIMIT 1";    
        Loader_Mysql::DBMaster()->query($sql);

        return $this->insertwx($wxinfo);
    }
}
