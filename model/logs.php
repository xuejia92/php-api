<?php !defined('IN WEB') AND exit('Access Denied!');

class Logs extends Config_Table
{
    protected static $_instance = array();
    
    /**
     * 创建一个实例
     *
     * @return Logs
     */
    public static function factory()
    {	
        if(!is_object(self::$_instance['logs']))
        {
            self::$_instance['logs'] = new Logs();
        }
        
        return self::$_instance['logs'];
    }
    
    /**
     * 给用户加钱/扣钱
     *
     * @param {int}  $mid    用户ID
     * @param {int}  $mode   途径标识  PS：一定要注意这个ID，加钱前请查询后台配置
     * @param {int}  $flag   加钱/扣钱 (0加，1扣)
     * @param {int}  $money  钱数
     * @param {str}  $desc   附加信息
     *
     * @return boolean 成功返回TRUE,否则返回FALSE
     */
    public function addWin($gameid,$mid, $mode,$sid, $cid, $pid,$ctype,$wflag, $money,$desc=''){
        $mode     = Helper::uint($mode);
        $wflag    = Helper::uint($wflag);
        $money    = Helper::uint($money);
        $sid      = Helper::uint($sid);
        $pid      = Helper::uint($pid);
        $cid      = Helper::uint($cid);
        $ctype    = Helper::uint($ctype);
        $desc     = Loader_Mysql::dbmaster()->escape($desc);
        
        error_log("gameid=".$gameid.",mid=".$mid.",mode=".$mode.",sid=".$sid.",cid=".$cid.",pid=".$pid);
        error_log("ctype=".$ctype.",wflag=".$wflag.",money=".$money.",desc=".$desc);

    	if ( !$gameid || !$mid || !$money ||!$mode ||!$sid || !$cid ||!$pid || !$ctype ) {
            error_log("addWin failed");
            return false;
        }
        
        if(in_array($mode, array(25,28))){
        	$_stime = microtime(true);
        }
        
        if(in_array($mode, array(15,34,31,32,19))){//活动中心，乐宝，翻翻乐加减金币时要判断用户是否在其它游戏玩
        	$serverInfo = Loader_Redis::userServer($mid)->hMget(Config_Keys::userServer($mid), array('svid','tid','level'));
			if($serverInfo['svid'] !=0 || $serverInfo['tid'] !=0  ){
				$this->debug(array('mid'=>$mid,'mode'=>$mode,'svid'=>$serverInfo['svid'],'tid'=>$serverInfo['tid']),'svid_error');
				return false;
			}
        }
        
        //防沉迷，超过5小时在玩不加金币 
        if(Loader_Redis::server()->get(Config_Keys::swicthVerify(),false,false) == 1){
        	$playTime = Loader_Redis::server()->hGet(Config_Keys::onlinetimehash(), $mid);
        	if(($playTime/3600) > 5){
        		//return false;
        	}
        }

        $money = ($wflag == 0 ? $money : -1*$money);
        //$wflag == 0 &&  $money = min(Config_Money::$max , $money );
        $flag =  Loader_Tcp::callServer($mid)->setMoney($mid,$money);//通知server加钱
        
        if(!$flag){//失败的情况再加一加一次，防止server数据没初始化
        	Loader_Tcp::callServer($mid)->get($mid);//初始化server内存数据
        	$flag = Loader_Tcp::callServer($mid)->setMoney($mid,$money);
        }
        
    	if(in_array($mode, array(25,28))){
        	$_etime = microtime(true);
        	$time1  = $_etime - $_stime;
        }

        $time = time();
        if($flag){
	        if(in_array($mode, array(25,28))){
	        	$_stime = microtime(true);
	        }
        	if( in_array($mode, array(2,14)) && in_array($mid, Config_Pay::$specialAccount)){
        		return (bool)$flag;
        	}
        	
        	Loader_Redis::common()->lPush(Config_Keys::winloglist($gameid), "('',$gameid,'$mid',$sid,'$cid','$pid','$ctype','$mode','$wflag','$money','$time','$desc')",false,false);
	        if(in_array($mode, array(25,28))){
	        	$_etime = microtime(true);
	        	$time2  = $_etime - $_stime;
	        	Logs::factory()->debug($time1.'|'.$time2,'getwallat1');
	        }
        }
        
        return (bool)$flag;
    }
        
    /**
     * 生成日志文件
     *
     * @param {mix} $data 数据
     * @param {str} $file 保存的文件名
     *
     * @return void
     */
	public function debug($data, $file='debug')
    {
    	$tempFile = $file;
    	if(strpos($tempFile, '/')){
    		$aDir = explode('/',$tempFile);
    		$dir = $aDir[0];
    		if(!file_exists(LOG_PATH.$dir)){
    			umask(0002);
				mkdir(LOG_PATH.$dir);
    		}
    	}
        $file = LOG_PATH . $file.".php";
        if (!file_exists($file))
        {
        	$fh = fopen($file, 'w');
        	fwrite($fh,"<?php die(0);\r\n");
        }
        else 
        {
        	if (@filesize($file) > 2097152)	//2M
        	{
        		$newfile = LOG_PATH.$tempFile."_".date("Y-m-d_H.i.s").".php";	//所有日志都保存，不用以前的直接覆盖了
        		
        		@rename($file , $newfile);
        		
        		$fh = fopen($file, 'w');
        		
        		fwrite($fh,"<?php die(0);\r\n");
        	}
        	else 
        	{
        		$fh = fopen($file, "a+");
        	}
        }
        
        fwrite($fh,"/*-------------------".date("Y-m-d H:i:s",time())."-------------------*/\r\n".var_export($data, TRUE) . ";\r\n\r\n");
        
        fclose($fh);
    }
    
	/**
	 * 每天的次数(累加器)限制.比如每天领奖的次数 
	 * @param int $mid 用户ID
	 * @param int $ltype 计数器类型: 10发feed ...请调用者加到这里
	 * @param int $lcount 要加的数量. 如果是0则只获取
	 * @param bool $update 是否要加加,默认为true,有时候只想获得当前的数量
	 * @return int 当前的最新值.注意如果没有正确取到缓存则返回一个最大值(如果从来没设置过也是)
	 */
	public function limitCount($mid, $type, $lcount, $update=true,$expire=86400) {
		
		$lcount = Helper::uint($lcount);
		
		if(!$mid){
			return 0;
		}
		
		$cacheKey = Config_Keys::timeLimit($type,$mid);
		
		$lvalue = Loader_Redis::common()->get( $cacheKey,false,false );
		
		if ( $update == true ) {
			$lvalue = Loader_Redis::common()->incr($cacheKey, $lcount,  $expire); //需要加则递增.确保设置该Key
		}

		return $lvalue;
	}	
	
	/**
	 * 
	 * 取得分表的表名
	 * @param int $id  
	 * @param int $tableName 分表的表名
	 * @param int $num  分表的个数
	 * @param int $flag  是否返回原表
	 */
	private function _getTable( $id, $tableName, $num, $flag = false ){
		$tableID = $id % $num + 1;		
		return $flag == true ? $tableName : $tableName.'_'.$tableID;	
	}
	
	/**
	 * 
	 * 分表  logMember
	 * 
	 */
	public function changeTableLogMember($gameid){
		$table =  $this->logmember.$gameid ;
		$sSql = "SELECT COUNT(id) as num FROM {$table}";
		$record = Loader_Mysql::DBLogchip()->getOne($sSql);
		
		if( $record['num'] < 6000000){
			return false;
		}
		
		$tableTemp = $table . '_temp'; //临时表名称
		$tableDate = $table . date('Ymd'); //目标表名称
				
		$sSql = "CREATE TABLE IF NOT EXISTS {$tableTemp} LIKE {$table}";
		Loader_Mysql::DBLogchip()->query($sSql);

		$sSql = "RENAME TABLE {$table} TO {$tableDate},{$tableTemp} TO {$table}"; //交换两个表的表名称
		Loader_Mysql::DBLogchip()->query($sSql);
	}
	
	/**
	 * 
	 * 分表  logwin
	 * 
	 */
	public function changeTableLogwin($gameid){
		$table =  $this->winlog.$gameid ;
		$sSql = "SELECT COUNT(wid) as num FROM {$table}";
		$record = Loader_Mysql::DBLogchip()->getOne($sSql);
		
		if( $record['num'] < 3000000){
			return false;
		}
		
		$tableTemp = $table . '_temp'; //临时表名称
		$tableDate = $table . date('Ymd'); //目标表名称
				
		$sSql = "CREATE TABLE IF NOT EXISTS {$tableTemp} LIKE {$table}";
		Loader_Mysql::DBLogchip()->query($sSql);

		$sSql = "RENAME TABLE {$table} TO {$tableDate},{$tableTemp} TO {$table}"; //交换两个表的表名称
		Loader_Mysql::DBLogchip()->query($sSql);
	}
	
	/**
	 * 
	 * 分表  logroll
	 * 
	 */
	public function changeTableLogRoll($gameid){
		$table =  $this->logroll.$gameid ;
		$sSql = "SELECT COUNT(id) as num FROM {$table}";
		$record = Loader_Mysql::DBLogchip()->getOne($sSql);
		
		if( $record['num'] < 6000000){
			return false;
		}
		
		$tableTemp = $table . '_temp'; //临时表名称
		$tableDate = $table . date('Ymd'); //目标表名称
				
		$sSql = "CREATE TABLE IF NOT EXISTS {$tableTemp} LIKE {$table}";
		Loader_Mysql::DBLogchip()->query($sSql);

		$sSql = "RENAME TABLE {$table} TO {$tableDate},{$tableTemp} TO {$table}"; //交换两个表的表名称
		Loader_Mysql::DBLogchip()->query($sSql);
	}
	
	/**
     * 给用户加乐卷/扣乐卷
     *
     * @param {int}  $mid    用户ID
     * @param {int}  $mode   途径标识  PS：一定要注意这个ID，加钱前请查询后台配置
     * @param {int}  $flag   加乐卷/扣乐卷 (0加，1扣)
     * @param {int}  $money  乐卷数
     * @param {str}  $rmode   加减类型  1 兑换消耗乐卷 2 系统扣减  3 完成局数任务发放 4 玩牌所得 （server） 5抽奖所得 6 激化码兑换所得 7 首充赠送 8 快速充值赠送
     *
     * @return boolean 成功返回TRUE,否则返回FALSE
     */
    public function setRoll($gameid,$mid,$sid, $cid, $pid,$ctype,$roll,$flag=1,$rmode=1){
        $flag     = Helper::uint($flag);
        $roll     = Helper::uint($roll);
        $sid      = Helper::uint($sid);
        $pid      = Helper::uint($pid);
        $cid      = Helper::uint($cid);
        $ctype    = Helper::uint($ctype);
        $mid      = Helper::uint($mid);
        $gameid   = Helper::uint($gameid);
        
    	if ( !$gameid || !$mid || !$roll  ||!$sid || !$cid ||!$pid || !$ctype ) {
            return false;
        }

        $roll = ($flag == 0 ? $roll : -1*$roll);
     
        $m = date("m");
	    if($m == '11' || $m == '12'){
	    	if($flag == 0){//加乐券
	        	$result = Loader_Tcp::callServer($mid)->setRoll($mid, $roll);
	        }else{//减乐券
	        	$result = Loader_Tcp::callServer($mid)->setRollExp($mid, $roll);
	        }
	     }else{
	        $result = Loader_Tcp::callServer($mid)->setRoll($mid, $roll);
	     }

        if(!$result){
        	Loader_Tcp::callServer($mid)->get($mid);//初始化server内存数据
	        if($m == '11' || $m == '12'){
	        	if($flag == 0){//加乐券
	        		$result = Loader_Tcp::callServer($mid)->setRoll($mid, $roll);
	        	}else{//减乐券
	        		$result = Loader_Tcp::callServer($mid)->setRollExp($mid, $roll);
	        	}
	        }else{
	        	$result = Loader_Tcp::callServer($mid)->setRoll($mid, $roll);
	        }
        }

        $gameInfo = $result;
        $rollnow  = (int)$gameInfo['roll'];

        if($result){
        	$sql = "INSERT DELAYED INTO $this->logroll{$gameid} SET ctype=$ctype,cid=$cid,sid=$sid,pid=$pid,mid=$mid,rollnow=$rollnow,amount=$roll,rmode=$rmode,ctime=".NOW;
        	Loader_Mysql::DBLogchip()->query($sql);
        	$result = Loader_Mysql::DBLogchip()->affectedRows();
        }
        return (bool)$result;
    }
}