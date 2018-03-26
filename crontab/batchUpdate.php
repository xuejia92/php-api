<?php
$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';

//批量更新winlog
foreach (Config_Game::$game as $gameid=>$gname) {
	$stime = microtime(true);
	$i = 0;
	$str = "";
	while ($row = Loader_Redis::common()->rPop(Config_Keys::winloglist($gameid),false,false)) {
		if($row){
			$i++;
			$str .= $row.', ';
		}
	}
	if(!$str){
		continue;
	}

	$str   = rtrim($str,', ');
	$table = "logchip.log_win".$gameid;
	
	$sql   = "INSERT INTO $table  VALUES  $str";
	Loader_Mysql::DBLogchip()->query($sql);
	$row = Loader_Mysql::DBLogchip()->affectedRows();
	$etime = microtime(true);
	$cross_time = $etime - $stime;
	Logs::factory()->debug(array('mysqlrow'=>$row,'i'=>$i,'cross_time'=>$cross_time,'gameid'=>$gameid),'bathUpdate_winlog');
}


//批量更新device
$timehi = date('Hi');
if($timehi % 55 == 0){
	$stime = microtime(true);
	$str = "";
	$i = 0;
	while ($record = Loader_Redis::common()->rPop(Config_Keys::deviceUpdateList())) {
		if(is_array($record) && count($record) > 0){
			$i++;
			$mid      = $record['mid'];
			$sitemid  = $record['sitemid'];
			$sid      = $record['sid'];
			$deviceno = $record['deviceno'];
			$ip       = $record['ip'];
			$time     = $record['time'];
			$str .= " ($mid,'$sitemid',$sid,'$deviceno','$ip','$time'), ";
		}
	}
	
	$str = rtrim($str,', ');
	
	$sql = "INSERT INTO uc_sitemid2mid (mid,sitemid,sid,deviceno,ip,time) 
			VALUES
			".$str."
			 ON DUPLICATE KEY UPDATE 
			mid = values(mid), 
			deviceno = values(deviceno), 
			ip = values(ip), 
			time = values(time)";
	

	Loader_Mysql::DBMaster()->query($sql);
	$row = Loader_Mysql::DBMaster()->affectedRows();
	
	$etime = microtime(true);
	$cross_time = $etime - $stime;
	Logs::factory()->debug(array('mysqlrow'=>$row,'i'=>$i,'cross_time'=>$cross_time),'bathUpdate_device');
}

