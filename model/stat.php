<?php !defined('IN WEB') AND exit('Access Denied!');

class Stat extends Config_Table{
	
	private static $_instance;
	
	public static function factory(){
		
		if(!self::$_instance){
			self::$_instance = new Stat;
		}
		
		return self::$_instance;	
	}

	public function userAction( &$aUser) {
		
		//获取唯一码 区分android用户和ios用户 
		$device_no = $aUser['device_no'];
		
		if($aUser['mentercount'] == 0){
			//假如客户端没有请求激活接口，则统计激活  解决一些线上版本没有发激化请求 2015-3-10
			$flag = Loader_Redis::account()->hExists(Config_Keys::androidKey($aUser['gameid']), $device_no);
			if(!$flag && !in_array($aUser['cid'],Config_Game::$platformAccount)){
				Loader_Redis::account()->hSet(Config_Keys::androidKey($aUser['gameid']),$device_no,1);
				Loader_Udp::stat()->sendData(62,$aUser['mid'],$aUser['gameid'],$aUser['ctype'],$aUser['cid'],$aUser['sid'],$aUser['pid'],$aUser['ip']); 
				Loader_Redis::account()->hSet(Config_Keys::deviceList($aUser['gameid']), $device_no, 1 ,false,false,30*86400);//把机器码存入
			}

			Loader_Udp::stat()->sendData(9,$aUser['mid'],$aUser['gameid'],$aUser['ctype'],$aUser['cid'],$aUser['sid'],$aUser['pid'],$aUser['ip']); //当日注册数
			$status = Loader_Redis::account()->hGet(Config_Keys::androidKey($aUser['gameid']), $device_no);
			$isPlatformUser = in_array($aUser['cid'],Config_Game::$platformAccount);
			if($isPlatformUser){
				Loader_Udp::stat()->sendData(62,$aUser['mid'],$aUser['gameid'],$aUser['ctype'],$aUser['cid'],$aUser['sid'],$aUser['pid'],$aUser['ip']); //第三方平台新激活用户
			}
			
			if($status == 1 || $isPlatformUser){//兼容第三方账号的统计，如乐游账号不会打开注册页面，避免此类账号不能被统计（2014-03-27）
				
				//if($aUser['gameid'] == 7 && in_array($aUser['cid'], array(148,149))){
					//Logs::factory()->addWin($aUser['gameid'],$aUser['mid'], 1,$aUser['sid'], $aUser['cid'], $aUser['pid'],$aUser['ctype'], 0,96000);//特殊渠道注册送金币
				//}
				
				$day = date("Ymd",NOW);
				Loader_Udp::stat()->sendData(55,$aUser['mid'],$aUser['gameid'],$aUser['ctype'],$aUser['cid'],$aUser['sid'],$aUser['pid'],$aUser['ip']); //新增用户（根据device_no去重）
				Loader_Redis::account()->hSet(Config_Keys::androidKey($aUser['gameid']), $device_no,2);
				Loader_Redis::account()->lPush(Config_Keys::registerMidNew($day), $aUser['gameid'].'-'.$aUser['mid'],false,false);//把当天新增用户放到队列
				Loader_Redis::common()->hSet(Config_Keys::logMemory($day, $aUser['gameid']), $aUser['mid'], $aUser['money']+$aUser['freezemoney'],false,false,7*24*3600);//资产分布
				Loader_Redis::common()->incr($day.'-'.$aUser['gameid'].'-'.$aUser['ctype'].'-'.$aUser['cid'].'-'.$aUser['versions'],1,60*24*3600);//以天为单位统计游戏版本分布
			}
		}
		
		if( $aUser['todayFirst'] == 1 ) {	//今天第一次登录

			date("Y-m-d", $aUser['mtime']) == date('Y-m-d',strtotime("-1 day"))   && $itemid = 1;//昨注回头
			date('Y-m-d', $aUser['mtime']) == date('Y-m-d', strtotime('-3 day'))  && $itemid = 2;//3注回头
			date('Y-m-d', $aUser['mtime']) == date('Y-m-d', strtotime('-5 day'))  && $itemid = 3;//5注回头
			date('Y-m-d', $aUser['mtime']) == date('Y-m-d', strtotime('-7 day'))  && $itemid = 8;//7注回头
			date('Y-m-d', $aUser['mtime']) == date('Y-m-d', strtotime('-14 day')) && $itemid = 5;//14注回头
			date('Y-m-d', $aUser['mtime']) == date('Y-m-d', strtotime('-21 day')) && $itemid = 6;//21注回头
			date('Y-m-d', $aUser['mtime']) == date('Y-m-d', strtotime('-30 day')) && $itemid = 7;//30注回头
			
			$flag = Loader_Redis::common()->get($device_no.'|'.$aUser['gameid'],false,false);
			if(!$flag){
				$itemid && Loader_Udp::stat()->sendData($itemid,$aUser['mid'],$aUser['gameid'],$aUser['ctype'],$aUser['cid'],$aUser['sid'],$aUser['pid'],$aUser['ip']);
			}

			if( $aUser['mentercount'] > 0 && !$flag ) {
				$day = date("Ymd",NOW);
				Loader_Udp::stat()->sendData( 11,$aUser['mid'],$aUser['gameid'],$aUser['ctype'],$aUser['cid'],$aUser['sid'],$aUser['pid'],$aUser['ip']);//活跃用户
				Loader_Redis::account()->lPush(Config_Keys::activeMid($day), $aUser['gameid'].'-'.$aUser['mid'],false,false);//把当天活跃用户放到队列
				Loader_Redis::common()->hSet(Config_Keys::logMemory($day, $aUser['gameid']), $aUser['mid'], $aUser['money']+$aUser['freezemoney'],false,false,7*24*3600);//资产分布
				Loader_Redis::common()->incr($day.'-'.$aUser['gameid'].'-'.$aUser['ctype'].'-'.$aUser['cid'].'-'.$aUser['versions'],1,60*24*3600);//以天为单位统计游戏版本分布
			}
			
			Loader_Redis::common()->set($device_no.'|'.$aUser['gameid'], 1,false,false,Helper::time2morning());
			$this->saveDeviceNo($aUser['mid'], $device_no,$aUser['sitemid'],$aUser['sid']);//保存机器码
		}
	}
	
	private function saveDeviceNo($mid,$deviceNo,$sitemid,$sid){
		
		$arr['mid']      = $mid;
		$arr['sitemid']  = $sitemid;
		$arr['sid']      = $sid;
		$arr['deviceno'] = $deviceNo;
		$arr['ip']       =  Helper::getip();
		$arr['time']     = NOW;
		Loader_Redis::common()->lPush(Config_Keys::deviceUpdateList(), $arr);
	}
	
	public function redisData2db($gameid,$aSet){

		$allow = array('sid','ctype','cid','pid','date','amount','itemid','gameid');
		
		$fields = array();
		foreach ($aSet as $key => $value){
			if( in_array($key, $allow, true)){
				if($key == 'date'){
					$value = date("Y-m-d",strtotime($value));
				}
				if($key == 'amount' && !$value){
					return false;
				}
				$fields[] = "`$key`='{$value}'";
			}
		}
		
		if(count($fields) <= 0) return false;

		$query = "INSERT INTO ".$this->statsum."$gameid SET " . implode(',', $fields);
		Loader_Mysql::DBStat()->query($query);
		
		return true;
	}
	
	public function getCidByCtype($ctype){
		if(!$ctype = Helper::uint($ctype)){
			return false;
		}
		
		$sql = "SELECT cid FROM $this->settingcid WHERE ctype=$ctype";
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	
	public function getPidByCid($cid){
		if(!$cid = Helper::uint($cid)){
			return false;
		}
		
		$sql = "SELECT pid FROM $this->settingpid WHERE cid=$cid";
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	
	private function getPreRegisterSum($gameid,$date){
		$aData = Loader_Redis::common()->hGetAll(Config_Keys::stat($date, 55));
		
		foreach ($aData as $key=>$amount) {
			$aKey   = explode('-', $key);
			$gameid = $aKey[0].'gameid';
			$ctype  = $aKey[1].'ctype';
			$cid    = $aKey[2].'cid';
			$pid    = $aKey[3];
			
			$data['gameid'][$gameid][$gameid] = $amount + (int)$data['gameid'][$gameid][$gameid];
			$data['ctype'][$gameid][$ctype]   = $amount + (int)$data['ctype'][$gameid][$ctype];
			$data['cid'][$gameid][$cid]       = $amount + (int)$data['cid'][$gameid][$cid];
			$data['pid'][$gameid][$pid]       = $amount + (int)$data['pid'][$gameid][$pid];
		}
			
		if($data['ctype']){
			foreach ($data['ctype'] as $gameid=>$aCtype) {
				foreach ($aCtype as $ctype=>$val) {
					$data['ctype'][(int)$gameid][(int)$ctype] = $val;
				}
			}
		}
	
		if($data['cid']){
			foreach ($data['cid'] as $gameid=>$aCid) {
				foreach ($aCid as $cid=>$val) {
					$data['cid'][(int)$gameid][(int)$cid] = $val;
				}
			}
		}
			
		if($data['pid']){
			foreach ($data['pid'] as $gameid=>$aPid) {
				foreach ($aPid as $pid=>$val) {
					$data['pid'][(int)$gameid][(int)$pid] = $val;
				}
			}
		}
		
		if($data['gameid']){
			foreach ($data['gameid'] as $gameid=>$aGameid) {
				foreach ($aGameid as $gameid=>$val) {
					$data['gameid'][(int)$gameid][(int)$gameid] = $val;
				}
			}
		}
				
		return $data;
	}
	
	public function cal_rate($gameid,$day,$count,$itemid,$order,$filed,$filedid){
		
		if(!$count = Helper::uint($count)){
			return false;
		}
	
		if($itemid == 1){//计算昨注回头率
			$yesterday = date("Y-m-d",strtotime("-2 day"));
			$data = $this->getPreRegisterSum($gameid,$yesterday);
			$register_count = $data[$filed][$gameid][$filedid];
			if(!$register_count) return '';
			if($count > $register_count){
				$count = $register_count;
			}
			$rate = sprintf("%.2f", $count/$register_count *100 ).'%';
			Stat::factory()->redisData2db($gameid,array('itemid'=>12,$filed=>$filedid,'amount'=>$rate,'date'=>$day));
		}
		
		if($itemid == 2){//计算3注回头率
			$before3day = date("Y-m-d",strtotime("-4 day"));
			$data = $this->getPreRegisterSum($gameid,$before3day);
			$register_count = $data[$filed][$gameid][$filedid];
			if(!$register_count) return '';
			if($count > $register_count){
				$count = $register_count;
			}
			$rate = sprintf("%.2f", $count/$register_count *100 ).'%';
			Stat::factory()->redisData2db($gameid,array('itemid'=>13,$filed=>$filedid,'amount'=>$rate,'date'=>$day));
		}
		if($itemid == 3){//计算5注回头率
			$before5day = date("Y-m-d",strtotime("-6 day"));
			$data = $this->getPreRegisterSum($gameid,$before5day);
			$register_count = $data[$filed][$gameid][$filedid];
			if(!$register_count) return '';
			if($count > $register_count){
				$count = $register_count;
			}
			$rate = sprintf("%.2f", $count/$register_count *100 ).'%';
			Stat::factory()->redisData2db($gameid,array('itemid'=>14,$filed=>$filedid,'amount'=>$rate,'date'=>$day));
		}
					
		if($itemid == 8){//计算7注回头率
			$before7day = date("Y-m-d",strtotime("-8 day"));
			$data = $this->getPreRegisterSum($gameid,$before7day);
			$register_count = $data[$filed][$gameid][$filedid];
			if(!$register_count) return '';
			if($count > $register_count){
				$count = $register_count;
			}
			$rate = sprintf("%.2f", $count/$register_count *100 ).'%';
			Stat::factory()->redisData2db($gameid,array('itemid'=>4,$filed=>$filedid,'amount'=>$rate,'date'=>$day));
		}
					
		if($itemid == 5){//计算14注回头率
			$before14day = date("Y-m-d",strtotime("-15 day"));
			$data = $this->getPreRegisterSum($gameid,$before14day);
			$register_count = $data[$filed][$gameid][$filedid];
			if(!$register_count) return '';
			if($count > $register_count){
				$count = $register_count;
			}
			$rate = sprintf("%.2f", $count/$register_count *100 ).'%';
			Stat::factory()->redisData2db($gameid,array('itemid'=>16,$filed=>$filedid,'amount'=>$rate,'date'=>$day));
		}
					
		if($itemid == 6){//计算21注回头率
			$before21day = date("Y-m-d",strtotime("-22 day"));
			$data = $this->getPreRegisterSum($gameid,$before21day);
			$register_count = $data[$filed][$gameid][$filedid];
			if(!$register_count) return '';
			if($count > $register_count){
				$count = $register_count;
			}
			$rate = sprintf("%.2f", $count/$register_count *100 ).'%';
			Stat::factory()->redisData2db($gameid,array('itemid'=>17,$filed=>$filedid,'amount'=>$rate,'date'=>$day));
		}
					
		if($itemid == 7){//计算30注回头率
			$before30day = date("Y-m-d",strtotime("-31 day"));
			$data = $this->getPreRegisterSum($gameid,$before30day);
			$register_count = $data[$filed][$gameid][$filedid];
			if(!$register_count) return '';
			if($count > $register_count){
				$count = $register_count;
			}
			$rate = sprintf("%.2f", $count/$register_count *100 ).'%';
			Stat::factory()->redisData2db($gameid,array('itemid'=>18,$filed=>$filedid,'amount'=>$rate,'date'=>$day));
		}
	}
	
	public function getItemid($catid){
		if(!$catid = Helper::uint($catid)){
			return false;
		}
		$sql = "SELECT * FROM stat.stat_item WHERE catid='$catid' ORDER BY `order` ASC ";
		return Loader_Mysql::DBStat()->getAll($sql);	
	}
	
	public function stat_register_sum($gameid,$itemid,$date,$todayCount,$filed,$filevalue){
		if($itemid == 55){
			$add = "";
			if($filed == 'gameid'){
				$add = " AND cid=0 AND ctype=0 AND pid=0 ";
			}
			
			$todayCount = Helper::uint($todayCount);
			$day = date("Y-m-d",strtotime("-2 day"));
			$sql = "SELECT amount FROM ".$this->statsum."$gameid WHERE itemid=10 AND date='$day' AND $filed=$filevalue $add LIMIT 1";
			$row = Loader_Mysql::DBStat()->getOne($sql);
			$yesterdayCount = (int)$row['amount'];		
			$registerSum = $yesterdayCount + $todayCount;
			$date = date("Y-m-d",strtotime($date));
			$sql = "INSERT INTO ".$this->statsum."$gameid SET itemid=10, date='$date',$filed=$filevalue,amount=$registerSum";
			Loader_Mysql::DBStat()->query($sql);
		}		
	}
	
	public function getAmountFromDB($gameid,$itemid,$date,$filed,$filevalue){
		$add = " AND roomid=0 ";
		if($filed == 'gameid'){
			$add = " AND cid=0 AND ctype=0 AND pid=0 AND roomid=0 ";
		}

		$sql = "SELECT amount FROM ".$this->statsum."$gameid WHERE itemid='$itemid' AND date='$date' AND $filed=$filevalue $add   LIMIT 1";
		$row = Loader_Mysql::DBStat()->getOne($sql);
		return $row['amount'];
	}
	
	public function stat_active_rate($gameid,$itemid,$date,$todayCount,$filed,$filevalue){
		if($itemid == 11){
			$add = "";
			if($filed == 'gameid'){
				$add = " AND cid=0 AND ctype=0 AND pid=0 ";
			}
			$todayCount = Helper::uint($todayCount);
			if(!$todayCount) return '';
			$date = date("Y-m-d",strtotime($date));
			$sql = "SELECT amount FROM ".$this->statsum."$gameid WHERE itemid=10 AND date='$date' AND $filed=$filevalue $add LIMIT 1";
			$row = Loader_Mysql::DBStat()->getOne($sql);
			$registerSum = max((int)$row['amount'],1);
			$rate = sprintf("%.2f", $todayCount/$registerSum *100 ).'%';
			$sql = "INSERT INTO ".$this->statsum."$gameid SET itemid=26, date='$date',$filed=$filevalue,amount='$rate'";
			Loader_Mysql::DBStat()->query($sql);
		}		
	}
	
	public function statGameInfo($gameid=0,$filed='money'){

		$total = 0;
		$where = $gameid != 0 ? "gameid LIKE '%$gameid%'" : "1";
		for($i=0;$i<10;$i++){
			$table = $this->gameinfo.$i;
			if($filed=='money'){
				$filed = "`money`+`freezemoney`";
			}
			$sql = "SELECT sum($filed) total FROM $table WHERE $where ";
			$info = Loader_Mysql::DBSlave()->getOne($sql);
			$total = $total + $info['total'];
		}

		return $total;
	}
	
	public function statLogPlay($gameid,$stime,$etime,$aGet,$filed='money'){
		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "etime>=$stime";
		$aWhere[] = "etime<=$etime";
		
		$sql   = "SELECT sum($filed) total FROM $this->logmember$gameid WHERE ".implode(" AND ", $aWhere);
		$row   = Loader_Mysql::DBLogchip()->getOne($sql);
		$total = $row['total'];
		
		$cutTable     = $this->isCutTable($this->logmember.$gameid);
		if($cutTable){
			$sql      = "SELECT sum($filed) total FROM $cutTable WHERE ".implode(" AND ", $aWhere);
			$row      = Loader_Mysql::DBLogchip()->getOne($sql);
			$cutTotal = $row['total'];
			$total    = $total + $cutTotal;
		}
				
		return $total;
	}
	
	public function statCount($gameid,$stime,$etime,$aGet,$filed='money',$isDISTINCT = true)
	{
		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}

		$aWhere[] = "etime>=$stime";
		$aWhere[] = "etime<=$etime";
		
		$cutTable     = $this->isCutTable($this->logmember.$gameid);

		if($isDISTINCT){
			$sql = 	"SELECT count(DISTINCT $filed) total FROM $this->logmember$gameid WHERE ".implode(" AND ", $aWhere);
		}else{
			$sql = 	"SELECT count($filed) total FROM $this->logmember$gameid WHERE ".implode(" AND ", $aWhere);
		}
		
		$row   = Loader_Mysql::DBLogchip()->getOne($sql);
		$total = $row['total'];
		
		if($cutTable){
			if($isDISTINCT){
				$sql = 	"SELECT count(DISTINCT $filed) total FROM ".$cutTable." WHERE ".implode(" AND ", $aWhere);
			}else{
				$sql = 	"SELECT count($filed) total FROM ".$cutTable." WHERE ".implode(" AND ", $aWhere);
			}
			$row      = Loader_Mysql::DBLogchip()->getOne($sql);
			$cutTotal = $row['total'];
			$total    = $total + $cutTotal;
		}
		
		return $total;			
	}
	
	public function isCutTable($table){
		if(!$table){
			return false;
		}
		
		$table = substr ($table ,  8 ); 
		
		$sql = "SHOW TABLES like '%$table%'";
		$rows = Loader_Mysql::DBLogchip()->getAll($sql);
		$tables = array();
		foreach ($rows as $row) {
			foreach ($row as $v){
				$tables[] = $v;
			}
		}

		$yesterday    = date("Ymd",strtotime("-1 days"));
		$lastCutTable = $table.$yesterday;

		$cutTable = '';
		if(in_array($lastCutTable,$tables)){
			$cutTable = $lastCutTable;
		}
		
		return $cutTable;
	}

	public function getLogPlayCount($gameid,$stime,$etime,$aGet,$filed='boardid',$gid=0){
		$rtn['gameid'] = $rtn['cid'] = $rtn['ctype'] = $rtn['pid'] =  array();
		$aCid = Base::factory()->getChannel();		
		$aPid = Base::factory()->getPack();
		
		foreach ($aGet as $con) {
			if($gameid == 6 && strpos($con, 'level') !== false){
				switch ($con) {
					case "level=1":
						$aWhere[] = "level<=6";
					break;
					case "level=2":
						$aWhere[] = "level>6 AND level<=12";
					break;
					case "level=3":
						$aWhere[] = "level>12 AND level<=21";
					break;
					case "level=4":
						$aWhere[] = "level>22 AND level<=24";
					break;
					case "level=5":
						$aWhere[] = "level=25";
					break;
					case "level=6":
					    $aWhere[] = "level>26 AND level<=32";
				    break;
					default:
						$aWhere[] = $con;
					break;
				}
			}elseif($gameid == 4 && strpos($con, 'level') !== false ){
				strpos($con, 'level=8') === false && $aWhere[] = "level !=5";
				$aWhere[] = $con;
			}else{
				$aWhere[] = $con;
			}
		}
		
		$aWhere[]     = "stime>=$stime";
		$aWhere[]     = "stime<=$etime";
		$cutTable     = $this->isCutTable($this->logmember.$gameid);

		foreach ($aCid as $cid=>$cname) {
			$sql = "SELECT id FROM $this->logmember$gameid WHERE ".implode(" AND ", $aWhere)." AND cid=$cid GROUP BY $filed ";
			Loader_Mysql::DBLogchip()->query($sql);
			$rtn['cid'][$cid] = (int)Loader_Mysql::DBLogchip()->affectedRows();
			
			if($cutTable){
				$sql = "SELECT id FROM $cutTable WHERE ".implode(" AND ", $aWhere)." AND cid=$cid GROUP BY $filed ";
				Logs::factory()->debug($sql,'getLogPlayCount');
				Loader_Mysql::DBLogchip()->query($sql);
				$lastDayData = (int)Loader_Mysql::DBLogchip()->affectedRows();
				$rtn['cid'][$cid] = $rtn['cid'][$cid] + $lastDayData;
			}
		}
		
		foreach (Config_Game::$clientTyle as $ctype=>$clientname) {
			$sql = "SELECT id FROM $this->logmember$gameid WHERE ".implode(" AND ", $aWhere)." AND ctype=$ctype GROUP BY $filed ";
			Loader_Mysql::DBLogchip()->query($sql);
			$rtn['ctype'][$ctype] = (int)Loader_Mysql::DBLogchip()->affectedRows();
			
			if($cutTable){
				$sql = "SELECT id FROM $cutTable WHERE ".implode(" AND ", $aWhere)." AND ctype=$ctype GROUP BY $filed ";
				Loader_Mysql::DBLogchip()->query($sql);
				$lastDayData = (int)Loader_Mysql::DBLogchip()->affectedRows();
				$rtn['ctype'][$ctype] = $rtn['ctype'][$ctype] + $lastDayData;
			}
		}
		
		/*
		foreach ($aPid as $pid=>$pname) {
			$sql = "SELECT id FROM $this->logmember$gameid WHERE ".implode(" AND ", $aWhere)." AND pid=$pid GROUP BY $filed ";
			$row = Loader_Mysql::DBSlave()->getOne($sql);
			$rtn['pid'][$pid] = (int)Loader_Mysql::DBMaster()->affectedRows();
		}
		*/
		
		$sql = "SELECT id FROM $this->logmember$gameid WHERE ".implode(" AND ", $aWhere)." GROUP BY $filed ";
		Loader_Mysql::DBLogchip()->query($sql);
		$gameid = ($filed == 'boardid' || in_array($aGet[0], array("level=8","level=26","level=6")) )  ? $gameid : $gid ;
		$rtn['gameid'][$gameid] = (int)Loader_Mysql::DBLogchip()->affectedRows();
		
		if($cutTable){
			$sql = "SELECT id FROM $cutTable WHERE ".implode(" AND ", $aWhere)." GROUP BY $filed ";
			Loader_Mysql::DBLogchip()->query($sql);
			$lastDayData = (int)Loader_Mysql::DBLogchip()->affectedRows();
			$rtn['gameid'][$gameid] = $rtn['gameid'][$gameid] + $lastDayData;
		}

		return  $rtn;
	}
	
	public function getPlaySlotsCount($gameid,$stime,$etime,$aGet,$filed='mid'){
		$rtn['cid'] = $rtn['ctype'] = $rtn['pid'] =  array();
		$aCid = Base::factory()->getChannel();		
		$aPid = Base::factory()->getPack();
		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "wtime>=$stime";
		$aWhere[] = "wtime<=$etime";
		$cutTable = $this->isCutTable($this->winlog.$gameid);
		
		foreach ($aCid as $cid=>$cname) {
			$sql = "SELECT wid  FROM $this->winlog$gameid WHERE ".implode(" AND ", $aWhere)." AND cid=$cid GROUP BY $filed ";
			Loader_Mysql::DBLogchip()->query($sql);
			$rtn['cid'][$cid] = Loader_Mysql::DBLogchip()->affectedRows();
			
			if($cutTable){
				$sql = "SELECT wid FROM $cutTable WHERE ".implode(" AND ", $aWhere)." AND cid=$cid GROUP BY $filed ";
				Loader_Mysql::DBLogchip()->query($sql);
				$row = Loader_Mysql::DBLogchip()->affectedRows();
				$rtn['cid'][$cid] = $rtn['cid'][$cid] + $row;
			}
		}
		
		foreach (Config_Game::$clientTyle as $ctype=>$clientname) {
			$sql = "SELECT wid FROM $this->winlog$gameid WHERE ".implode(" AND ", $aWhere)." AND ctype=$ctype GROUP BY $filed ";
			Loader_Mysql::DBLogchip()->query($sql);
			$rtn['ctype'][$ctype] = (int)Loader_Mysql::DBLogchip()->affectedRows();
			if($cutTable){
				$sql = "SELECT wid FROM $cutTable WHERE ".implode(" AND ", $aWhere)." AND ctype=$ctype GROUP BY $filed ";
				Loader_Mysql::DBLogchip()->query($sql);
				$row = Loader_Mysql::DBLogchip()->affectedRows();
				$rtn['ctype'][$ctype] = $row + $rtn['ctype'][$ctype];
			}
		}
		
		foreach ($aPid as $pid=>$pname) {
			$sql = "SELECT wid FROM $this->winlog$gameid WHERE ".implode(" AND ", $aWhere)." AND pid=$pid GROUP BY $filed ";
			Loader_Mysql::DBLogchip()->query($sql);
			$rtn['pid'][$pid] = Loader_Mysql::DBLogchip()->getOne($sql);
		}
		
		$sql = "SELECT wid FROM $this->winlog$gameid WHERE ".implode(" AND ", $aWhere)." GROUP BY $filed ";
		Loader_Mysql::DBLogchip()->query($sql);
		$rtn['gameid'][$gameid] = Loader_Mysql::DBLogchip()->affectedRows();

		if($cutTable){
			$sql = "SELECT wid FROM $cutTable WHERE ".implode(" AND ", $aWhere)." GROUP BY $filed ";
			Loader_Mysql::DBLogchip()->query($sql);
			$row = Loader_Mysql::DBLogchip()->affectedRows();
			$rtn['gameid'][$gameid] = $rtn['gameid'][$gameid] + $row;
		 }
		
		return  $rtn;
	}
	
	//百人场金币消耗
	public function getBaiRenMoney($stime,$etime,$aGet=array()){
		
		$aWhere = array();
		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		$aWhere[] = "etime>=$stime";
		$aWhere[] = "etime<=$etime";
		$cutTable = $this->isCutTable($this->logmember.$gameid);
		
		$sql = "select sum(money) winmoney FROM ".$this->logmember."4 WHERE " .implode(" AND ", $aWhere)." AND mid < 1000 AND money >= 0 AND level = 8";
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		$robotWinMoney = $row['winmoney'];
		
		if($cutTable){
			$sql = "select sum(money) winmoney FROM $cutTable WHERE " .implode(" AND ", $aWhere)." AND mid < 1000 AND money >= 0 AND level = 8";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$robotWinMoney = $robotWinMoney + $row['winmoney'];
		}
		
		$sql = "select sum(tax) wintax FROM ".$this->logmember."4 WHERE  " .implode(" AND ", $aWhere)."  AND mid < 1000 AND money >= 0 AND level = 8";
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		$winTax = $row['wintax'];
		
		if($cutTable){
			$sql = "select sum(tax) wintax FROM $cutTable WHERE  " .implode(" AND ", $aWhere)."  AND mid < 1000 AND money >= 0 AND level = 8";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$winTax = $winTax + $row['wintax'];
		}
		
		$realWinMoney = $robotWinMoney + $winTax;
		
		$sql = "select sum(money) lostmoney FROM ".$this->logmember."4 WHERE " .implode(" AND ", $aWhere)." AND mid < 1000 and money < 0 and level = 8";
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		$robotLostMoney = $row['lostmoney'];
		
		if($cutTable){
			$sql = "select sum(money) lostmoney FROM $cutTable WHERE " .implode(" AND ", $aWhere)." AND mid < 1000 and money < 0 and level = 8";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$robotLostMoney = $robotLostMoney + $row['lostmoney'];
		}
		
		$sql = "select sum(tax) losttax FROM ".$this->logmember."4 WHERE " .implode(" AND ", $aWhere)." AND  mid < 1000 AND money < 0 AND level = 8";
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		$lostTax = $row['losttax'];
		
		if($cutTable){
			$sql = "select sum(tax) losttax FROM $cutTable WHERE " .implode(" AND ", $aWhere)." AND  mid < 1000 AND money < 0 AND level = 8";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$lostTax = $lostTax + $row['losttax'];
		}
		
		$realLoseMoney = $robotLostMoney + $lostTax;
		
		return $realWinMoney + $realLoseMoney;
	}
	
	public function statLogWin($gameid,$stime,$etime,$aGet,$flag=1){
		$rtn['cid'] = $rtn['ctype'] = $rtn['pid'] = $rtn['all'] = array();
		$aCid = Base::factory()->getChannel();		
		$aPid = Base::factory()->getPack();
		$aWhere = array();

		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "wtime>=$stime";
		$aWhere[] = "wtime<=$etime";
		$cutTable = $this->isCutTable($this->winlog.$gameid);
		foreach ($aCid as $cid=>$cname) {
			$sql = "SELECT sum(money) money FROM $this->winlog$gameid WHERE ".implode(" AND ", $aWhere)." AND cid=$cid ";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$rtn['cid'][$cid] = $flag ? abs($row['money']) : $row['money'];
			
			if($cutTable){
				$sql   = "SELECT sum(money) money FROM $cutTable WHERE ".implode(" AND ", $aWhere)." AND cid=$cid ";
				$row   = Loader_Mysql::DBLogchip()->getOne($sql);
				$total = $flag ? abs($row['money']) : $row['money'];
				$rtn['cid'][$cid] = $rtn['cid'][$cid] + $total;
			}
		}
		
		foreach (Config_Game::$clientTyle as $ctype=>$clientname) {
			$sql = "SELECT sum(money) money FROM $this->winlog$gameid WHERE ".implode(" AND ", $aWhere)." AND ctype=$ctype ";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$rtn['ctype'][$ctype] = $flag ? abs($row['money']) : $row['money'];
			
			if($cutTable){
				$sql   = "SELECT sum(money) money FROM $cutTable WHERE ".implode(" AND ", $aWhere)." AND ctype=$ctype ";
				$row   = Loader_Mysql::DBLogchip()->getOne($sql);
				$total = $flag ? abs($row['money']) : $row['money'];
				$rtn['ctype'][$ctype] = $rtn['ctype'][$ctype] + $total;
			}
		}
		
		foreach ($aPid as $pid=>$pname) {
			$sql = "SELECT sum(money) money FROM $this->winlog$gameid WHERE ".implode(" AND ", $aWhere)." AND pid=$pid ";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$rtn['pid'][$pid] = $flag ? abs($row['money']) : $row['money'];
			if($cutTable){
				$sql = "SELECT sum(money) money FROM $cutTable WHERE ".implode(" AND ", $aWhere)." AND pid=$pid ";
				$row = Loader_Mysql::DBLogchip()->getOne($sql);
				$total = $flag ? abs($row['money']) : $row['money'];
				$rtn['pid'][$pid] = $rtn['pid'][$pid] + $total;
			}
		}
		
		$sql = "SELECT sum(money) money FROM $this->winlog$gameid WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		$rtn['all'][0] = $flag ?  abs($row['money']) : $row['money'];
		
		if($cutTable){
			$sql = "SELECT sum(money) money FROM $cutTable WHERE ".implode(" AND ", $aWhere);
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$total = $flag ?  abs($row['money']) : $row['money'];
			$rtn['all'][0] = $rtn['all'][0] + $total;
		}
		
		return $rtn;
	}
	
	public function statPayment($gameid,$stime,$etime,$aGet,$filed='money'){
		$rtn['cid'] = $rtn['ctype'] = $rtn['pid'] = $rtn['gameid'] = array();
		$aCid = Base::factory()->getChannel();		
		$aPid = Base::factory()->getPack();
		$aWhere = array();

		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "ptime>=$stime";
		$aWhere[] = "ptime<=$etime";
		
		foreach ($aCid as $cid=>$cname) {
			$this->_statPayment($aWhere, $filed, 'cid', $cid, $rtn);
		}
		
		foreach (Config_Game::$clientTyle as $ctype=>$clientname) {
			$this->_statPayment($aWhere, $filed, 'ctype', $ctype, $rtn);
		}
		
		foreach ($aPid as $pid=>$pname) {
			$this->_statPayment($aWhere, $filed, 'pid', $pid, $rtn);
		}
		
		$this->_statPayment($aWhere, $filed, 'gameid', $gameid, $rtn);
		
		return $rtn;
	}
	
	private function _statPayment($aWhere,$filed,$type,$value,&$rtn){
		$sqlAdd = $type == 'gameid' ? "" : " AND $type=$value ";
		switch ($filed) {
			case 'pamount':
			case 'pexchangenum':
				$sql = "SELECT sum($filed) total FROM $this->payment WHERE ".implode(" AND ", $aWhere).$sqlAdd;
				$row = Loader_Mysql::DBSlave()->getOne($sql);
				$rtn[$type][$value] = sprintf("%.2f", $row['total']);
			break;
			case 'mid':
				$sql = "SELECT id FROM $this->payment WHERE ". implode(" AND ", $aWhere) .$sqlAdd." GROUP BY mid ";
				Loader_Mysql::DBSlave()->query($sql);
				$rtn[$type][$value] = (int)Loader_Mysql::DBSlave()->affectedRows();
			break;
			case 'order':
				$sql = "SELECT count(*) total FROM $this->payment WHERE ".implode(" AND ", $aWhere) .$sqlAdd;
				$row = Loader_Mysql::DBSlave()->getOne($sql);
				$rtn[$type][$value] = (int)$row['total'];
			break;	
		}
	}
	
	public function getStatSum($gameid,$aGet){
		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		if(count($aWhere) < 1) $aWhere[] = 1;
		$sql = "SELECT amount FROM ".$this->statsum."$gameid WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBStat()->getOne($sql);
		return $row['amount'];
	}
	
	public function setStatSum($gameid,$aSet){
		
		$table = $gameid == 0 ? $this->statsum : $this->statsum.$gameid;
		
		foreach ($aSet as $key=>$val) {
			 if( $key == 'amount' && ($val == 0 || $val == '0.00')){
			 	return false;
			 }
			
			$aFiled[] = "`$key` = '$val'";
		}
		
		if(count($aFiled) < 1) $aFiled[] = 1;
		$sql = "INSERT INTO $table  SET ".implode(" , ", $aFiled);
		Loader_Mysql::DBStat()->query($sql);
		return  Loader_Mysql::DBStat()->affectedRows();
	}
	
	public function getStatItem($catid){
		$sql = "SELECT * FROM stat.stat_item WHERE catid=$catid ORDER BY `order` ASC";
		Loader_Mysql::DBStat()->getAll($sql);
	}
	
	public function getLogPlayAverage($gameid,$stime,$etime,$aGet){
		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "otime>=$stime";
		$aWhere[] = "otime<=$etime";
		
		if(count($aWhere) < 1) $aWhere[] = 1;
		
		$sql = "SELECT otime,sum( `onlinesum` ) onlinesum ,sum( `playsum` ) playsum FROM $this->logronline$gameid WHERE ".implode(" AND ", $aWhere);
		$amounts = Loader_Mysql::DBStat()->getOne($sql);
		
		$sql = "SELECT count(*) num FROM $this->logronline$gameid WHERE ".implode(" AND ", $aWhere) ." AND onlinesum !=0";
		$row = Loader_Mysql::DBStat()->getOne($sql);
		$count_onlinesum = $row['num'];
		
		$sql = "SELECT count(*) num FROM $this->logronline$gameid WHERE ".implode(" AND ", $aWhere) ." AND playsum !=0";
		$row = Loader_Mysql::DBStat()->getOne($sql);
		$count_playsum = $row['num'];
		
		$amounts['onlinesum'] && $count_onlinesum   && $ave_online = ceil($amounts['onlinesum'] / $count_onlinesum);
		$amounts['playsum']   && $count_playsum     && $ave_play   = ceil($amounts['playsum']   / $count_playsum);
		
		return array($ave_online,$ave_play);
	}
	
	public function getRegisterMid($stime,$etime){
		for($i=0;$i<10;$i++){
			$table = $this->userinfo.$i;
			$sql  = "SELECT mid FROM $table WHERE mtime >= $stime AND mtime<=$etime";
			$rows = Loader_Mysql::DBMaster()->getAll($sql);
			foreach ($rows as $row) {
				Loader_Redis::account()->lPush(Config_Keys::registerMid(), $row['mid'],false,false);
			}
		}
	}
	
	public function statNewUserByMid($stime,$etime){
		$__stime = microtime(true);
		foreach (Config_Game::$game as $gameid=>$gameName) {
			$cutTable_member[$gameid] = $this->isCutTable($this->logmember.$gameid);
			$cutTable_winlog[$gameid] = $this->isCutTable($this->winlog.$gameid);
		}

		$day = date("Ymd",$stime);
		$registerMid = array();
		
		$aSql = array();
		while ($str_mid = Loader_Redis::account()->rPop(Config_Keys::registerMidNew($day),false,false)) {
			$aMid   = explode("-", $str_mid);
			$gameid = $aMid[0];
			$mid    = $aMid[1];
			if(!$mid){
				continue;
			}
			
			$aSql[$gameid] .= " ($mid), ";
			$registerMid[]  = $mid;
			$date           = date("Y-m-d",NOW);
			Loader_Redis::account()->rPush(Config_Keys::dayreg($gameid,$date), $mid, false, false);
			
			$aUser = Member::factory()->getUserInfo($mid);
			$sql = "SELECT mid,sum(pamount) pamount,count( mid ) num FROM $this->payment WHERE  ptime >= $stime AND ptime<=$etime AND mid=$mid AND pmode!=11 ";
			$row = Loader_Mysql::DBSlave()->getOne($sql);

			if($row['pamount']){
				//日注册用户中当日成功付费人数
				$amount['59'][$gameid]['cid'][$aUser['cid']]     ++;
				$amount['59'][$gameid]['ctype'][$aUser['ctype']] ++;
				$amount['59'][$gameid]['gameid']                 ++;
				
				//日注册用户中当日成功付费总金额
				$amount['60'][$gameid]['cid'][$aUser['cid']]     = $amount['60'][$gameid]['cid'][$aUser['cid']]     + $row['pamount'];
				$amount['60'][$gameid]['ctype'][$aUser['ctype']] = $amount['60'][$gameid]['ctype'][$aUser['ctype']] + $row['pamount'];
				$amount['60'][$gameid]['gameid']                 = $amount['60'][$gameid]['gameid']                 + $row['pamount'];
			}
			
			if($row['num']){
				//日注册用户中当日成功付费次数
				$amount['195'][$gameid]['cid'][$aUser['cid']]     = $amount['195'][$gameid]['cid'][$aUser['cid']]     + $row['num'];
				$amount['195'][$gameid]['ctype'][$aUser['ctype']] = $amount['195'][$gameid]['ctype'][$aUser['ctype']] + $row['num'];
				$amount['195'][$gameid]['gameid']                 = $amount['195'][$gameid]['gameid']                 + $row['num'];
			}
		}
		
		foreach (Config_Game::$game as $gameid=>$gname) {
			$sql = "TRUNCATE {$this->logregister}$gameid";
			Loader_Mysql::DBLogchip()->query($sql);
			
			$aSql[$gameid] = rtrim($aSql[$gameid],', ');
			$sql  = "INSERT IGNORE INTO {$this->logregister}$gameid VALUES ";
			$sql .= $aSql[$gameid];
			Loader_Mysql::DBLogchip()->query($sql);
		}
		
		foreach (Config_Game::$game as $gameid=>$gname) {
			$sqlAdd = $gameid == 5 ?  " AND gid=5 " : "";
			$sql = "SELECT count(a.id) num FROM {$this->logmember}$gameid a ,{$this->logregister}$gameid b WHERE a.mid=b.mid and a.etime>$stime and a.etime<$etime $sqlAdd group by a.mid";
			$rows = Loader_Mysql::DBLogchip()->getAll($sql);
			
			if($cutTable_member[$gameid]){
				$sql = "SELECT count(a.id) num FROM $cutTable_member[$gameid] a ,{$this->logregister}$gameid b WHERE a.mid=b.mid and a.etime>$stime and a.etime<$etime $sqlAdd group by a.mid";
				$lastRows = Loader_Mysql::DBLogchip()->getAll($sql);
			}
			
			$lastRows && $rows = array_merge($rows,$lastRows);
			foreach ($rows as $row) {
				if($row['num'] >=1){
					$amount['56'][$gameid]['gameid'] ++;
					$amount['198'][$gameid]['gameid']  = $amount['198'][$gameid]['gameid'] + $row['num'];
				}
				
				if($row['num'] >=3){
					$amount['57'][$gameid]['gameid'] ++;
				}
				
				if($row['num'] >=5){
					$amount['58'][$gameid]['gameid'] ++;
				}
			}
			
			$sql = "SELECT count( DISTINCT a.mid) num  FROM {$this->winlog}$gameid a, {$this->logregister}$gameid b WHERE a.mid=b.mid AND  wtime >= $stime AND wtime<=$etime AND wmode=4";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$num = $row['num'];
			if($cutTable_winlog[$gameid]){
				$sql = "SELECT count( DISTINCT a.mid) num FROM ".$cutTable_winlog[$gameid]." a, {$this->logregister}$gameid b WHERE a.mid=b.mid AND  wtime >= $stime AND wtime<=$etime  AND wmode=4 ";
				$row = Loader_Mysql::DBLogchip()->getOne($sql);	
				$num = $num + $row['num'];		
			}
			$amount['71'][$gameid]['gameid'] = $num;
			
			foreach (Config_Game::$clientTyle as $ctype=>$ctypename) {
				$sql = "SELECT count(a.id) num FROM {$this->logmember}$gameid a ,{$this->logregister}$gameid b WHERE a.mid=b.mid AND a.etime>$stime AND a.etime<$etime AND ctype=$ctype $sqlAdd  GROUP BY  a.mid";
				$rows = Loader_Mysql::DBLogchip()->getAll($sql);
				
				if($cutTable_member[$gameid]){
					$sql = "SELECT count(a.id) num FROM $cutTable_member[$gameid] a ,{$this->logregister}$gameid b WHERE a.mid=b.mid AND a.etime>$stime AND a.etime<$etime AND ctype=$ctype $sqlAdd GROUP BY a.mid";
					$lastRows = Loader_Mysql::DBLogchip()->getAll($sql);
				}
				
				$lastRows && $rows = array_merge($rows,$lastRows);
				foreach ($rows as $row) {
					if($row['num'] >=1){
						$amount['56'][$gameid]['ctype'][$ctype] ++;
						$amount['198'][$gameid]['ctype'][$ctype] = $amount['198'][$gameid]['ctype'][$ctype] + $row['num'];;
					}
					
					if($row['num'] >=3){
						$amount['57'][$gameid]['ctype'][$ctype] ++;
					}
					
					if($row['num'] >=5){
						$amount['58'][$gameid]['ctype'][$ctype] ++;
					}
				}
				
				$sql = "SELECT count( DISTINCT a.mid) num  FROM {$this->winlog}$gameid a, {$this->logregister}$gameid b WHERE a.mid=b.mid AND ctype=$ctype AND  wtime >= $stime AND wtime<=$etime AND wmode=4";
				$row = Loader_Mysql::DBLogchip()->getOne($sql);
				$num = $row['num'];
				if($cutTable_winlog[$gameid]){
					$sql = "SELECT count( DISTINCT a.mid) num FROM ".$cutTable_winlog[$gameid]." a, {$this->logregister}$gameid b WHERE a.mid=b.mid AND ctype=$ctype AND  wtime >= $stime AND wtime<=$etime AND wmode=4 ";
					$row = Loader_Mysql::DBLogchip()->getOne($sql);	
					$num = $num + $row['num'];		
				}
				$amount['71'][$gameid]['ctype'][$ctype] = $num;
			}
			
			$aCid = Base::factory()->getChannel();
			foreach ($aCid as $cid=>$cname) {
				$sql = "SELECT count(a.id) num FROM {$this->logmember}$gameid a ,{$this->logregister}$gameid b WHERE a.mid=b.mid AND a.etime>$stime AND a.etime<$etime AND cid=$cid $sqlAdd  GROUP BY  a.mid";
				$rows = Loader_Mysql::DBLogchip()->getAll($sql);
				
				if($cutTable_member[$gameid]){
					$sql = "SELECT count(a.id) num FROM $cutTable_member[$gameid] a ,{$this->logregister}$gameid b WHERE a.mid=b.mid AND a.etime>$stime AND a.etime<$etime AND cid=$cid $sqlAdd GROUP BY a.mid";
					$lastRows = Loader_Mysql::DBLogchip()->getAll($sql);
				}
				
				$lastRows && $rows = array_merge($rows,$lastRows);
				foreach ($rows as $row) {
					if($row['num'] >=1){
						$amount['56'][$gameid]['cid'][$cid] ++;
						$amount['198'][$gameid]['cid'][$cid] = $amount['198'][$gameid]['cid'][$cid] + $row['num'];;
					}
					
					if($row['num'] >=3){
						$amount['57'][$gameid]['cid'][$cid] ++;
					}
					
					if($row['num'] >=5){
						$amount['58'][$gameid]['cid'][$cid] ++;
					}
				}
				
				$sql = "SELECT count( DISTINCT a.mid) num  FROM {$this->winlog}$gameid a, {$this->logregister}$gameid b WHERE a.mid=b.mid AND cid=$cid AND  wtime >= $stime AND wtime<=$etime AND wmode=4";
				$row = Loader_Mysql::DBLogchip()->getOne($sql);
				$num = $row['num'];
				if($cutTable_winlog[$gameid]){
					$sql = "SELECT count( DISTINCT a.mid) num FROM ".$cutTable_winlog[$gameid]." a, {$this->logregister}$gameid b WHERE a.mid=b.mid AND cid=$cid AND  wtime >= $stime AND wtime<=$etime AND wmode=4 ";
					$row = Loader_Mysql::DBLogchip()->getOne($sql);	
					$num = $num + $row['num'];		
				}
				$amount['71'][$gameid]['cid'][$cid] = $num;
			}
		}

		$__etime = microtime(true);
		$cross   = $__etime - $__stime;
		Logs::factory()->debug($cross,'statNewUserByMid_cross');
		Loader_Redis::common()->set("newRegisterPayment|$day", $registerMid, true, true, 32*24*3600);
		$registerMid = null;
		
		return $amount;
	}
	
	public function statActiveUserByMid($stime,$etime){
		
		$__stime = microtime(true);
		foreach (Config_Game::$game as $gameid=>$gameName) {
			$cutTable_member[$gameid] = $this->isCutTable($this->logmember.$gameid);
			$cutTable_winlog[$gameid] = $this->isCutTable($this->winlog.$gameid);
		}
		$day = date("Ymd",$stime);
		$activeMid = array();
		
		while ($str_mid = Loader_Redis::account('slave')->rPop(Config_Keys::activeMid($day),false,false)) {
			$aMid   = explode("-", $str_mid);
			$gameid = $aMid[0];
			$mid    = $aMid[1];
			
			if(!$mid){
				continue;
			}
			
			$aSql[$gameid] .= " ($mid), ";
			$activeMid[]    = $mid;
		}	
			
		foreach (Config_Game::$game as $gameid=>$gname) {
			$sql = "TRUNCATE {$this->logactive}$gameid";
			Loader_Mysql::DBLogchip()->query($sql);
			
			$aSql[$gameid] = rtrim($aSql[$gameid],', ');
			$sql  = "INSERT IGNORE INTO {$this->logactive}$gameid VALUES ";
			$sql .= $aSql[$gameid];
			Loader_Mysql::DBLogchip()->query($sql);
		}
		
		foreach (Config_Game::$game as $gameid=>$gname) {
			$sqlAdd = $gameid == 5 ?  " AND gid=5 " : "";
			$sql  = "SELECT count( DISTINCT a.mid ) num FROM {$this->logmember}$gameid a ,{$this->logactive}$gameid b WHERE a.mid=b.mid and a.etime>$stime and a.etime<$etime $sqlAdd ";
			$rows = Loader_Mysql::DBLogchip()->getOne($sql);
			$num  = $rows['num'];
			if($cutTable_member[$gameid]){
				$sql      = "SELECT count( DISTINCT a.mid ) num FROM $cutTable_member[$gameid] a ,{$this->logactive}$gameid b WHERE a.mid=b.mid and a.etime>$stime and a.etime<$etime $sqlAdd ";
				$lastRows = Loader_Mysql::DBLogchip()->getOne($sql);
				$num      = $num + $lastRows['num'];
			}
			
			$amount['23'][$gameid]['gameid']  = $num;
			
			$sql = "SELECT count( DISTINCT a.mid) num  FROM {$this->winlog}$gameid a, {$this->logactive}$gameid b WHERE a.mid=b.mid AND  wtime >= $stime AND wtime<=$etime AND wmode=4";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$num = $row['num'];
			if($cutTable_winlog[$gameid]){
				$sql = "SELECT count( DISTINCT a.mid) num FROM ".$cutTable_winlog[$gameid]." a, {$this->logactive}$gameid b WHERE a.mid=b.mid AND  wtime >= $stime AND wtime<=$etime  AND wmode=4 ";
				$row = Loader_Mysql::DBLogchip()->getOne($sql);	
				$num = $num + $row['num'];		
			}
			$amount['71'][$gameid]['gameid'] = $num;
			
			foreach (Config_Game::$clientTyle as $ctype=>$ctypename) {
				$sql  = "SELECT count( DISTINCT a.mid) num FROM {$this->logmember}$gameid a ,{$this->logactive}$gameid b WHERE a.mid=b.mid AND a.etime>$stime AND a.etime<$etime AND ctype=$ctype $sqlAdd ";
				$rows = Loader_Mysql::DBLogchip()->getOne($sql);
				$num  = $rows['num'];
				if($cutTable_member[$gameid]){
					$sql = "SELECT count( DISTINCT a.mid) num FROM ".$cutTable_member[$gameid]." a ,{$this->logactive}$gameid b WHERE a.mid=b.mid AND a.etime>$stime AND a.etime<$etime AND ctype=$ctype $sqlAdd";
					$lastRows = Loader_Mysql::DBLogchip()->getOne($sql);
					$lastNum  = $lastRows['num'];
					$num      = $num + $lastRows['num'];
				}
				
				$amount['23'][$gameid]['ctype'][$ctype] = $num;
				
				$sql = "SELECT count( DISTINCT a.mid) num  FROM {$this->winlog}$gameid a, {$this->logactive}$gameid b WHERE a.mid=b.mid AND ctype=$ctype AND  wtime >= $stime AND wtime<=$etime AND wmode=4";
				$row = Loader_Mysql::DBLogchip()->getOne($sql);
				$num = $row['num'];
				if($cutTable_winlog[$gameid]){
					$sql = "SELECT count( DISTINCT a.mid) num FROM ".$cutTable_winlog[$gameid]." a, {$this->logactive}$gameid b WHERE a.mid=b.mid AND ctype=$ctype AND  wtime >= $stime AND wtime<=$etime AND wmode=4 ";
					$row = Loader_Mysql::DBLogchip()->getOne($sql);	
					$num = $num + $row['num'];		
				}
				$amount['71'][$gameid]['ctype'][$ctype] = $num;
			}
			
			$aCid = Base::factory()->getChannel();
			foreach ($aCid as $cid=>$cname) {
				$sql  = "SELECT count( DISTINCT a.mid) num FROM {$this->logmember}$gameid a ,{$this->logactive}$gameid b WHERE a.mid=b.mid AND a.etime>$stime AND a.etime<$etime AND cid=$cid $sqlAdd ";
				$rows = Loader_Mysql::DBLogchip()->getOne($sql);
				$num  = $rows['num'];
				if($cutTable_member[$gameid]){
					$sql = "SELECT count( DISTINCT a.mid) num FROM $cutTable_member[$gameid] a ,{$this->logactive}$gameid b WHERE a.mid=b.mid AND a.etime>$stime AND a.etime<$etime AND cid=$cid $sqlAdd";
					$lastRows = Loader_Mysql::DBLogchip()->getOne($sql);
					$num      = $num + $lastRows['num'];
				}
				
				$amount['23'][$gameid]['cid'][$cid] = $num;
				
				$sql = "SELECT count( DISTINCT a.mid) num  FROM {$this->winlog}$gameid a, {$this->logactive}$gameid b WHERE a.mid=b.mid AND cid=$cid AND  wtime >= $stime AND wtime<=$etime AND wmode=4";
				$row = Loader_Mysql::DBLogchip()->getOne($sql);
				$num = $row['num'];
				if($cutTable_winlog[$gameid]){
					$sql = "SELECT count( DISTINCT a.mid) num FROM ".$cutTable_winlog[$gameid]." a, {$this->logactive}$gameid b WHERE a.mid=b.mid AND cid=$cid AND  wtime >= $stime AND wtime<=$etime AND wmode=4 ";
					$row = Loader_Mysql::DBLogchip()->getOne($sql);	
					$num = $num + $row['num'];		
				}
				$amount['71'][$gameid]['cid'][$cid] = $num;
			}
		}
		
		$__etime = microtime(true);
		$cross   = $__etime-$__stime;
		Logs::factory()->debug($cross,'statActiveUserByMid_crosstime');
		$today = date("Ymd",time());
		Loader_Redis::common()->set('activeMid_'.$today, $activeMid, true, true, 7*24*3600);
		$activeMid = null;
		return $amount;
	}
	
	/**
	 * 新激活用户统计（根据device_no去重）
	 */
	public function statActivate($device_no,$gameid,$mid,$ctype,$cid,$sid,$pid,$ip){
		$flag = Loader_Redis::account()->hExists(Config_Keys::androidKey($gameid), $device_no);
		
		if(!$flag && !in_array($cid,Config_Game::$platformAccount)){
			Loader_Redis::account()->hSet(Config_Keys::androidKey($gameid),$device_no,1);
			Loader_Udp::stat()->sendData(62,$mid,$gameid,$ctype,$cid,$sid,$pid,$ip);
			if($cid == 112){
				Logs::factory()->debug($device_no.'|'.$gameid.'|'.$mid.'|'.$ctype.'|'.$cid.'|'.$sid.'|'.$pid.'|'.$ip,'statActiovate');
			}
			
			Loader_Redis::account()->hSet(Config_Keys::deviceList($gameid), $device_no, 1 ,false,false,30*86400);//把机器码存入
		}
	}
	
	public function statRoll($gameid,$stime,$etime,$aGet,$flag=1){
		$rtn['cid'] = $rtn['ctype'] = $rtn['pid'] = $rtn['gameid'] = array();
		$aCid = Base::factory()->getChannel();		
		$aPid = Base::factory()->getPack();
		$aWhere = array();

		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "ctime>=$stime";
		$aWhere[] = "ctime<=$etime";
				
		foreach ($aCid as $cid=>$cname) {
			$sql = "SELECT sum(amount) amount FROM $this->logroll$gameid WHERE ".implode(" AND ", $aWhere)." AND cid=$cid ";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$rtn['cid'][$cid] = $flag ? abs($row['amount']) : $row['amount'];
		}
		
		foreach (Config_Game::$clientTyle as $ctype=>$clientname) {
			$sql = "SELECT sum(amount) amount FROM $this->logroll$gameid WHERE ".implode(" AND ", $aWhere)." AND ctype=$ctype ";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$rtn['ctype'][$ctype] = $flag ? abs($row['amount']) : $row['amount'];
		}
		/*
		foreach ($aPid as $pid=>$pname) {
			$sql = "SELECT sum(amount) amount FROM $this->logroll$gameid WHERE ".implode(" AND ", $aWhere)." AND pid=$pid ";
			$row = Loader_Mysql::DBLogchip()->getOne($sql);
			$rtn['pid'][$pid] = $flag ? abs($row['amount']) : $row['amount'];
		}
		*/
		
		$sql = "SELECT sum(amount) amount FROM $this->logroll$gameid WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		$rtn['gameid'][$gameid] = $flag ?  abs($row['amount']) : $row['amount'];
		
		return $rtn;
	}

	public function statLogRoll($gameid,$stime,$etime,$aGet,$filed='amount'){
		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "ctime>=$stime";
		$aWhere[] = "ctime<=$etime";
		
		$sql = "SELECT sum($filed) total FROM $this->logroll$gameid WHERE ".implode(" AND ", $aWhere);
		
		$row = Loader_Mysql::DBLogchip()->getOne($sql);

		return  $row['total'];
	}
	
	public function provincePayStat(){
		$yesterday = date("Y-m-d",strtotime('-1 day'));
		$stime     = strtotime($yesterday);
		$etime     = strtotime("$yesterday 23:59:59 ");
		$sql = "SELECT ip,pamount,gameid,pmode FROM $this->payment WHERE ptime>$stime and $etime<=$etime";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		$amount = array();
		foreach ($rows as $row) {
			$arr             = Lib_Ip::find($row['ip']);
			$amount['all']['all'][$arr[1]]                  = (int)$amount['all']['all'][$arr[1]] + (int)$row['pamount'];
			$amount['all'][$row['gameid']][$arr[1]]         = (int)$amount['all'][$row['gameid']][$arr[1]] + (int)$row['pamount'];
			$amount[$row['pmode']]['all'][$arr[1]]          = (int)$amount[$row['pmode']]['all'][$arr[1]] + (int)$row['pamount'];
			$amount[$row['pmode']][$row['gameid']][$arr[1]] = (int)$amount[$row['pmode']][$row['gameid']][$arr[1]] + (int)$row['pamount'];
		}
		
		Loader_Redis::common()->set(Config_Keys::payStat($yesterday), $amount,false,true,30*24*3600);
	}
	
	/**
	 * 付费用户统计
	 */
	public function payUserByMid() {
	    $today = date("Ymd",time());
	    $activeList = Loader_Redis::common()->get('activeMid_'.$today,true,true);//活跃用户Mid

	    $array = array(1,4,8,16,31);
	    $time = strtotime(date('Y-m-d',NOW));
	    
	    foreach ($array as $day){
	        foreach (Config_Game::$game as $gameid=>$gamename){
    	        $stime = strtotime("-".$day." days",$time);
    	        $etime = $stime+86400;
    	    
    	        $sql = "SELECT * FROM uc_payment WHERE ptime>=$stime AND ptime<$etime AND pstatus=2 AND gameid=$gameid AND pmode!=11 GROUP BY mid";
    	        $query[$day][$gameid] = $result = Loader_Mysql::DBSlave()->getAll($sql);
    	    
    	        $chargeMid = array();
    	        foreach ($result as $rows){
    	            $chargeMid[] = $rows['mid'];
    	        }
    	        $activeMid[$day][$gameid] = array_intersect($chargeMid, $activeList);
	        }
	    }
	    
    	$counting = array();
    
        foreach ($query as $datetime=>$array){
            foreach ($array as $gameid=>$userInfo){
                foreach ($userInfo as $infoDetail){
                    if ($datetime==1){//当日付费用户数
                        $counting['244'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                        $counting['244'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                        $counting['244'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                        $counting['244'][$infoDetail['gameid']]['gameid']++;
                    }
                    
                    if ($datetime==4){//3日付费用户数
                        $counting['246'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                        $counting['246'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                        $counting['246'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                        $counting['246'][$infoDetail['gameid']]['gameid']++;
                    }
                    
                    if ($datetime==8){//7日付费用户数
                        $counting['248'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                        $counting['248'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                        $counting['248'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                        $counting['248'][$infoDetail['gameid']]['gameid']++;
                    }
                    
                    if ($datetime==16){//15日付费用户数
                        $counting['250'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                        $counting['250'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                        $counting['250'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                        $counting['250'][$infoDetail['gameid']]['gameid']++;
                    }
                    
                    if ($datetime==31){//30日付费用户数
                        $counting['252'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                        $counting['252'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                        $counting['252'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                        $counting['252'][$infoDetail['gameid']]['gameid']++;
                    }
                    
                    if (in_array($infoDetail['mid'], $activeMid[$datetime][$gameid])){
                        if ($datetime==4){//3日付费留存
                            $counting['245'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                            $counting['245'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                            $counting['245'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                            $counting['245'][$infoDetail['gameid']]['gameid']++;
                        }
                                
                        if ($datetime==8){//7日付费留存
                            $counting['247'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                            $counting['247'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                            $counting['247'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                            $counting['247'][$infoDetail['gameid']]['gameid']++;
                        }
                                
                        if ($datetime==16){//15日付费留存
                            $counting['249'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                            $counting['249'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                            $counting['249'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                            $counting['249'][$infoDetail['gameid']]['gameid']++;
                        }
                                
                        if ($datetime==31){//30日付费留存
                            $counting['251'][$infoDetail['gameid']]['cid'][$infoDetail['cid']]++;
                            $counting['251'][$infoDetail['gameid']]['ctype'][$infoDetail['ctype']]++;
                            $counting['251'][$infoDetail['gameid']]['pid'][$infoDetail['pid']]++;
                            $counting['251'][$infoDetail['gameid']]['gameid']++;
                        }
                    }
                }
            }
	   }
       return $counting;
	}
	
	/**
	 * 新增用户付费统计
	 */
	public function newRegisterPayment(){
	    $array = array(4,8,16,31);
	    $time = strtotime(date('Ymd',NOW));
	    
	    $amount = array();
	    foreach ($array as $day){
	        $stime = strtotime("-".$day." days",$time);
            $dataTime = date('Ymd',$stime);
            $query = Loader_Redis::common()->get("newRegisterPayment|$dataTime", true, true);
	        if ($query){
	            foreach ($query as $mid){
	                $sql = "SELECT * FROM uc_payment WHERE ptime>=$stime AND ptime<$time AND pstatus=2 AND pmode!=11 AND mid=$mid";
	                $result = Loader_Mysql::DBMaster()->getAll($sql);
	                foreach ($result as $key=>$array){
	    
	                    if ($day==4){//3日付费额
	                        $amount['254'][$array['gameid']]['cid'][$array['cid']] += $array['pamount'];
	                        $amount['254'][$array['gameid']]['ctype'][$array['ctype']] += $array['pamount'];
	                        $amount['254'][$array['gameid']]['pid'][$array['pid']] += $array['pamount'];
	                        $amount['254'][$array['gameid']]['gameid'] += $array['pamount'];
	                    }
	    
	                    if ($day==8){//7日付费额
	                        $amount['255'][$array['gameid']]['cid'][$array['cid']] += $array['pamount'];
	                        $amount['255'][$array['gameid']]['ctype'][$array['ctype']] += $array['pamount'];
	                        $amount['255'][$array['gameid']]['pid'][$array['pid']] += $array['pamount'];
	                        $amount['255'][$array['gameid']]['gameid'] += $array['pamount'];
	                    }
	    
	                    if ($day==16){//15日付费额
	                        $amount['256'][$array['gameid']]['cid'][$array['cid']] += $array['pamount'];
	                        $amount['256'][$array['gameid']]['ctype'][$array['ctype']] += $array['pamount'];
	                        $amount['256'][$array['gameid']]['pid'][$array['pid']] += $array['pamount'];
	                        $amount['256'][$array['gameid']]['gameid'] += $array['pamount'];
	                    }
	    
	                    if ($day==31){//30日付费额
	                        $amount['257'][$array['gameid']]['cid'][$array['cid']] += $array['pamount'];
	                        $amount['257'][$array['gameid']]['ctype'][$array['ctype']] += $array['pamount'];
	                        $amount['257'][$array['gameid']]['pid'][$array['pid']] += $array['pamount'];
	                        $amount['257'][$array['gameid']]['gameid'] += $array['pamount'];
	                    }
	                }
	                usleep(100);
	            }
	        }
	    }
	    return $amount;
	}
	
	function getDataFromLog($gameid,$itemid,$stime,$etime,$gname=''){
		for($date=strtotime($stime);$date <=strtotime($etime);$date+=86400){ 
			$day = date("Ymd",$date);
			$src_dir = "/data1/dis/log/stat/$gameid/$day/";
			$dis_dir = "/data1/dis/log/stat/$gameid/tmp/";
			
			if(!is_dir($dis_dir)){
				mkdir($dis_dir);
			}
			
			$src_file = $src_dir.$itemid.".log";
			$dis_file = $dis_dir.$itemid."_$day.log";
			shell_exec("cat $src_file | awk '{print$1,$5}'>$dis_file");
			
			
			if($itemid == 11){
				$src_file = $src_dir."55.log";
				$dis_file = $dis_dir."55_$day.log";
				shell_exec("cat $src_file | awk '{print$1,$5}'>$dis_file");
			}
			
		}
		
		$files_str = "";
		for($date=strtotime($stime);$date <=strtotime($etime);$date+=86400){ 
			$day = date("Ymd",$date); 
			$file1 = "/data1/log/stat/$gameid/tmp/".$itemid."_".$day.".log";
			
			$file2 = '';
			
			if($itemid == 11){
				$file2 = "/data1/log/stat/$gameid/tmp/55_$day.log";
			}

			$files_str .= " $file1 $file2 ";
		}
		
		$dis_file = $dis_dir."all.log";
		shell_exec("cat $files_str > $dis_file");
		
		$sort_file = $dis_dir."all_sort.log";
		shell_exec("cat $dis_file |awk '{print$2,$1}' |sort -u > $sort_file");
		
		$ctype2name = array(1=>'android',2=>'IOS');
		$_tmp[0] = $_tmp[1] = $_tmp[2] = 0;
		for($ctype=1;$ctype<=2;$ctype++){
			$row = shell_exec("cat $sort_file |awk '{print $2}'|grep $ctype |wc -l");
			$_tmp[$ctype] = (int)$row;
			$_tmp[0] = $_tmp[0] + $row;
		}
		
		return $_tmp;
	}
	
	//从log还原数据到redis
	public function reductionFromLog($gameid,$itemid,$date){
		$day = date("Ymd",strtotime($date)); 
		$src_dir = "/data1/log/stat/$gameid/$day/";
		$dis_dir = "/data1/log/stat/$gameid/tmp/";
			
		if(!is_dir($dis_dir)){
			mkdir($dis_dir);
		}
			
		$src_file = $src_dir.$itemid.".log";
		$dis_file = $dis_dir.$itemid."_$day.log";
		shell_exec("cat $src_file | awk '{print$5}'>$dis_file");
		
		$str = file_get_contents($dis_file);//获得内容
		$ids = explode("\n",$str);//分行存入数组
		
		$key = '';
		if($itemid == 55){
			$key = Config_Keys::registerMidNew($day);
		}
		
		if($itemid == 11){
			$key = Config_Keys::activeMid($day);
		}
		
		foreach ($ids as $id) {
			Loader_Redis::account()->lPush($key, $gameid.'-'.$id,false,false);//redis队列
		}
		
		return true;
	}
	
	//捕鱼人数
	public function statFishMember($stime,$etime,$aGet,$gameid){

		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "etime>=$stime";
		$aWhere[] = "etime<=$etime";
		$cutTable = $this->isCutTable($this->logfish);
		$aWhere[] = "gid=$gameid";
		
		$sql    = "SELECT count( DISTINCT mid) num FROM $this->logfish WHERE ".implode(" AND ", $aWhere);
		$row    = Loader_Mysql::DBLogchip()->getOne($sql);
		$num    = $row['num'];

		if($cutTable){
			$sql    = "SELECT count( DISTINCT mid) num FROM $cutTable WHERE ".implode(" AND ", $aWhere);
			$row    = Loader_Mysql::DBLogchip()->getOne($sql);
			$num2   = $row['num'];
			$num = $num + $num2;
		 }
		
		return  $num;
	}
	
	//捕鱼炮弹数
	public function statFishSum($stime,$etime,$aGet,$sum,$gameid=''){

		foreach ($aGet as $con) {
			$aWhere[] = $con;
		}
		
		$aWhere[] = "etime>=$stime";
		$aWhere[] = "etime<=$etime";
		$cutTable = $this->isCutTable($this->logfish);
		if($gameid){
			$aWhere[] = "gid=$gameid";
		}
		
		$sql    = "SELECT SUM($sum) num FROM $this->logfish WHERE ".implode(" AND ", $aWhere);
		$row    = Loader_Mysql::DBLogchip()->getOne($sql);
		$num    = $row['num'];

		if($cutTable){
			$sql    = "SELECT SUM($sum) num FROM $cutTable WHERE ".implode(" AND ", $aWhere);
			$row    = Loader_Mysql::DBLogchip()->getOne($sql);
			$num2   = $row['num'];
			$num = $num + $num2;
		 }
		
		return  $num;
	}
	
}