<?php !defined('IN WEB') AND exit('Access Denied!');

class User_Account extends Config_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new User_Account();
		}
		
		return self::$_instances;
	}
	
	public function get($param){
		$mid     = Helper::uint($param['mid']);
		$sitemid = Loader_Mysql::DBSlave()->escape($param['sitemid']);
		$sid     = Helper::uint($param['sid']);
		$username = $param['username'];
		$mnick = $param['mnick'];
		$phone = $param['phone'];
		
		if(in_array($mid, Config_Pay::$specialAccount)){
			$login_name = Helper::getCookie('dianler_adm');
			$spec = Main_Model::factory()->specailAccount($login_name);
			if(!$spec){
				return array();
			}
		}
		
		if($mid){
			$info = Member::factory()->getOneById($mid,false);
			$info['mstatus'] = (int)Loader_Redis::account()->get(Config_Keys::banAccount($mid),false,false);
			if($info['mstatus']){
				$info['mstatus'] = ceil(($info['mstatus'] - NOW)/86400);
			}

			$info['registername'] = $this->getRegisterNameBysitemid($info['sitemid'], $info['sid']);
			return $info;
		}
		
		if(!empty($sitemid) && !empty($sid)){
			$info = Member::factory()->getOneBySitemid($sitemid,$sid,false);
			$info['mstatus'] = (int)Loader_Redis::account()->get(Config_Keys::banAccount($mid),false,false);
			if($info['mstatus']){
				$info['mstatus'] = ceil(($info['mstatus'] - NOW)/86400);
			}
			$info['registername'] = $this->getRegisterNameBysitemid($info['sitemid'], $info['sid']);
			return $info;
		}
		
		if(!empty($username)){
			$info = Member::factory()->getOneByUserName($username);
			foreach($info as $key=>&$user){
				$user['registername'] =  $this->getRegisterNameBysitemid($user['sitemid'], $user['sid']);
				
			}
			return $info;
		}
		
		if(!empty($mnick)){
			$info = Member::factory()->getUserByNick($mnick);
			
			foreach($info as $key=>&$user){
				$user['registername'] =  $this->getRegisterNameBysitemid($user['sitemid'], $user['sid']);
				
			}
			return $info;
		}
		
		if($phone){
			$info = Member::factory()->getOneByUserPhone($phone);
			$info['registername'] = $phone;
			return $info;
		}
		
		return false;
	}
	
	public function getRegisterNameBysitemid($sitemid,$sid){
		if($sid == 101){
			$table = $this->phonenumber_register;
		}elseif($sid == 102){
			$table = $this->username_register;
		}else{
			$userinfo = Member::factory()->getOneBySitemid($sitemid,$sid);
			return $userinfo['mnick'];
		}
		
		$sql = "SELECT * FROM $table WHERE sitemid=$sitemid LIMIT 1 ";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return $sid == 101 ? $row['phoneno'] : $row['username'];
	}

	public function banDeviceNo($deviceno){
		$login_name = Helper::getCookie('dianler_adm');
		if($deviceno){
			$mids = Member::factory()->getMidByDeviceno($deviceno);
		}
		
		$strtotime = NOW + 180*24*3600;
		foreach ($mids as $mid) {
			$userInfo = Member::factory()->getUserInfo($mid);
			$aMtime   = json_decode($userInfo['mtime'],true);
			$aGame    = array_flip ( $aMtime );
 			$gameid = current ( $aGame ) ;
			M_Anticheating::factory()->batAccount($mid, $gameid,$login_name,1000,'账号数过多,管理员封号');
		}
		
		Loader_Redis::account()->set(Config_Keys::banAccount($deviceno), $strtotime, false,false,180*24*3600);
		
		return true;
	}
	
	public function banIp($ip){
		$login_name = Helper::getCookie('dianler_adm');
		if($ip){
			$mids = Member::factory()->getMidByIp($ip);
		}
		
		foreach ($mids as $mid) {
			$userInfo = Member::factory()->getUserInfo($mid);
			$aMtime   = json_decode($userInfo['mtime'],true);
			$aGame    = array_flip ( $aMtime );
 			$gameid = current ( $aGame ) ;
			M_Anticheating::factory()->batAccount($mid, $gameid,$login_name,1000,'账号数过多,管理员封号');
		}
		
		$flag = Loader_Redis::account()->set(Config_Keys::banAccount($ip), 86400, false,false,24*3600);

		return true;
	}
	
	public function resetbanDeviceNo($deviceno){
		$login_name = Helper::getCookie('dianler_adm');
		if($deviceno){
			$mids = Member::factory()->getMidByDeviceno($deviceno);
		}

		foreach ($mids as $mid) {			
			$flag = Loader_Redis::account()->delete(Config_Keys::banAccount($mid));
			Loader_Redis::ote($mid)->hDel(Config_Keys::other($mid), 'bat');
			M_Anticheating::factory()->setBatLog($mid, -1,$login_name);
		}
		
		Loader_Redis::account()->delete(Config_Keys::banAccount($deviceno));
		return true;
	}
	
	public function resetbanIp($ip){
		$login_name = Helper::getCookie('dianler_adm');
		if($ip){
			$mids = Member::factory()->getMidByIp($ip);
		}

		foreach ($mids as $mid) {
			$flag = Loader_Redis::account()->delete(Config_Keys::banAccount($mid));
			Loader_Redis::ote($mid)->hDel(Config_Keys::other($mid), 'bat');
			M_Anticheating::factory()->setBatLog($mid, -1,$login_name);
		}
		
		Loader_Redis::account()->delete(Config_Keys::banAccount($ip));
		return true;
	}
	
	public function set($param){
		$login_name = Helper::getCookie('dianler_adm');
		$mid     = Helper::uint($param['mid']);
		$money   = Helper::uint($param['money']);
		$exp     = Helper::uint($param['exp']);
		$score   = Helper::uint($param['score']);
		$coins   = Helper::uint($param['coins']);
		$roll    = Helper::uint($param['roll']);
		$mstatus = $param['mstatus'];
		$gameid  = Helper::uint($param['gameid']);
		$horn    = Helper::uint($param['horn']);
		$freezemoney = Helper::uint($param['freezemoney']);
		
		$money && $aSet['money'] = $param['mcon'] == 1 ? $money : -$money;
		$coins && $aSet['coins'] = $param['bcon'] == 1 ? $coins : -$coins;
		$roll  && $aSet['roll']  = $param['rcon'] == 1 ? $roll  : -$roll;
		$exp   && $aSet['exp']   = $exp;
		$score && $aSet['score'] = $score;
		$freezemoney && $aSet['freezemoney'] = $freezemoney;

		if($mstatus == 7 || $mstatus ==15 || $mstatus ==180 ){
			M_Anticheating::factory()->batAccount($mid, $gameid,$login_name,$mstatus);
			return true;
		}elseif($mstatus == 1){
			$flag        = Loader_Redis::account()->delete(Config_Keys::banAccount($mid));
			$deviceno    = Member::factory()->getDevicenoBymid($mid);
			Loader_Redis::account()->delete(Config_Keys::banAccount($deviceno));
			Loader_Redis::ote($mid)->hDel(Config_Keys::other($mid), 'bat');
			M_Anticheating::factory()->setBatLog($mid, -1,$login_name);
			return true;
		}elseif($mstatus === 'forever'){
			M_Anticheating::factory()->batAccount($mid, $gameid,$login_name,1000);
			return true;
		}
		if($horn){
			$horn = $param['hcon'] == 1 ? $param['horn']  : -$param['horn'];
			
			$now_horn = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'horn');
			
			if($now_horn + $horn < 0){
				return false;
			}
			Loader_Redis::ote($mid)->hIncrBy(Config_Keys::other($mid), 'horn', $horn);
			return true;
		}
		
		if((! $mid = Helper::uint( $mid)) || (! is_array( $aSet)) || ! count( $aSet)){
			return false;
		}
		
		$allow = array('coins','score','roll','money','freezemoney'); //允许更改的字段
		
		$cacheKey = Config_Keys::getGameInfo($mid);
		$aInfo = Loader_Redis::minfo( $mid )->hGetAll( $cacheKey );
		$aInfo = is_array($aInfo) ? $aInfo : array();  

		$con = $fields = array();
		foreach ($aSet as $key => $value){
			if( in_array($key, $allow, true)){
				$fields[]    = "`$key`=`$key`+{$value}";
				$aInfo[$key] = $aInfo[$key] + $value;
				$con[]       = "`$key`+ '{$value}' >= 0";
			}
			
			$aUser = Member::factory()->getOneById($mid,false);
			if($key == 'money'){
				 $f = $param['mcon'] == 1 ? 0 : 1;
				 $wmmode = $f == 0 ? 3 : 5;
				 
				 if($value < 0){
				 	$user_money = $aUser['money'];
				 	if($user_money < abs($value)){
				 		return false;
				 	}
				 }
				 $aUser['sid'] = $aUser['sid'] ? $aUser['sid'] : 100;
				 $status = Logs::factory()->addWin($gameid,$aUser['mid'], $wmmode,$aUser['sid'], $aUser['cid'], $aUser['pid'],$aUser['ctype'], $f,abs($value));//

				 $username  = Helper::getCookie('dianler_adm');
				 $tmoney    = $aSet['money'];
				 $time      = NOW;
				 $ip        = Helper::getip();
				 $sql       = "INSERT INTO ucenter.uc_admin_logmoney SET id='',username='$username',money='$tmoney',mid='$mid',time='$time',ip='$ip'";
				 Loader_Mysql::DBMaster()->query($sql);
			}
			
			if($key == 'freezemoney'){
				$this->cutFreezemoney($gameid, $mid, $value, $aUser['sid'], $aUser['pid'], $aUser['ctype'], $aUser['cid']);
				 $username  = Helper::getCookie('dianler_adm');
				 $tmoney    = -$aSet['freezemoney'];
				 $time      = NOW;
				 $ip        = Helper::getip();
				 $sql       = "INSERT INTO ucenter.uc_admin_logmoney SET id='',username='$username',money='$tmoney',mid='$mid',time='$time',ip='$ip'";
				 Loader_Mysql::DBMaster()->query($sql);
			}
			
			if($key == 'roll'){
				$f = $param['rcon'] == 1 ? 0 : 1;
				if($value < 0){
				 	$user_roll = $aUser['roll'] +$aUser['roll1'] ;
				 	if($user_roll < abs($value)){
				 		return false;
				 	}
				 }
				
				Logs::factory()->setRoll($gameid,$aUser['mid'], $aUser['sid'], $aUser['cid'], $aUser['pid'], $aUser['ctype'],abs($value),$f, 2);
			}
		}

		if(count($fields) > 0){
			$table = Member::factory()->getTable($mid, $this->gameinfo, 10);
			$aInfo['mid'] == $mid && $flag && Loader_Redis::minfo($mid)->hMset($cacheKey, $aInfo, 30*24*3600);
			return true;
		}else{
			return true;
		}
	}
	
	public function getMoneyLog($data){		
		$aWhere = array();

		$aWhere[] = $data['stime']  ? "wtime>=".strtotime($data['stime']) : "wtime>=".strtotime(date("Y-m-d",strtotime("-5 days")));
		$aWhere[] = $data['etime']  ? "wtime<=".strtotime($data['etime']." 23:59:59") : "wtime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    $data['gid'] && $aWhere[] = "gameid=".Helper::uint($data['gid']);
	    $this->_dolog($data['mid'], $data['stime'], $data['etime']);

		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
		
		$table    =  $this->winlog.'_tmp';
		$sql  = "SELECT * FROM $table WHERE ".implode(" AND ", $aWhere)." ORDER BY wtime DESC LIMIT  $recordStart,$numPerPage ";
		$rows = Loader_Mysql::DBLogchip()->getAll($sql);
		
		foreach ($rows as $k=>&$row){
			
			if(in_array($row['wmode'],array(31,32,33))){
				//$desc = '|';
				$descArr = json_decode($row['wdesc'],true);
				//$desc    .= '下注数：'.$descArr['betMoney'].';';
				//$desc    .= '牌型：'.Config_Slots::$cardType2Name[$descArr['cardType']].';';
				
				$cardInfo = explode(',', $descArr['cardInfo']);
				$tmp = array();
				
				foreach ($cardInfo as $r) {
					$tmp[] = sprintf("0x%02X", $r);
				}
				
				if($descArr['newCard']){
					$descArr['newCard'] = sprintf("0x%02X", $descArr['newCard']);
				}
				
				//var_dump($tmp);
				
				$descArr['cardInfo'] = implode(',', $tmp);

				//$desc    .= '牌信息：'.$tmp[0].','.$tmp[1].','.$tmp[2].','.$tmp[3].','.$tmp[4].';';
				//$desc    .= '一天内累计输赢：'.$descArr['winMoney'].';';
				//$desc    .= '状态：'.$descArr['status'].';';
				//if($descArr['rewardMoney']){
					//$desc    .= '结算金币：'.$descArr['rewardMoney'].';';
				//}
				
				$row['wdesc'] = json_encode($descArr);
			}
			
			
			$row['desc'] = Base::factory()->getMoneyDescByWmode($row['wmode']);
		}
		
		return $rows;		
	}
	
	private function _dolog($mid,$stime,$etime){
		$act = $_GET['act'];
		
		switch ($act) {
			case 'moneylog':
				$table  = 'log_win';
				$add    = "wid=VALUES(wid)";
				$select = "`gameid`, `mid`, `sid`, `cid`, `pid`, `ctype`, `wmode`, `wflag`, `money`, `wtime`, `wdesc`";
			break;
			case 'banklog':
				$table  = 'log_bank';
				$gameid = $_GET['gid'];
				$add    = "id=VALUES(id)";
				$select = "`mid`, `type`, `amount`, `money`, `freezemoney`, `tomid`, `ctime`, `gameid`";
			break;
			case 'rolllog':
				$table  = 'log_roll';
				$add    = "id=VALUES(id)";
				$select = "`ctype`, `cid`, `pid`, `sid`, `mid`, `amount`, `rollnow`, `level`, `taskid`, `rmode`, `ctime`, `gameid`";
			break;
		}
		
		$table_tmp = $table.'_tmp';
		$sql = "TRUNCATE $table_tmp";
		Loader_Mysql::DBLogchip()->query($sql);
		
		foreach (Config_Game::$game as $gameid=>$name) {
			$table_src = $table.$gameid;
			$table_logs = $this->getTable($table_src);

			foreach ($table_logs as $tb) {
				$sql = "INSERT INTO $table_tmp SELECT $select FROM $tb WHERE mid = $mid ";
				Loader_Mysql::DBLogchip()->query($sql);
			}
		}
	}
	
	public function getMoneyLogCount($data){
		$aWhere = array();
		$table    = $data['table']  ? $data['table'] : $this->winlog.$data['gameid'];
		$aWhere[] = $data['stime']  ? "wtime>=".strtotime($data['stime']) : "wtime>=".strtotime(date("Y-m-d",strtotime("-5 days")));
		$aWhere[] = $data['etime']  ? "wtime<=".strtotime($data['etime']." 23:59:59") : "wtime<=".NOW;
		$data['gid'] && $aWhere[] = "gameid=".Helper::uint($data['gid']);
		
	    //$this->_dolog($data['mid'], $data['stime'], $data['etime']);
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    $table    =  $this->winlog.'_tmp';
		$sql  = "SELECT count(*) as count FROM $table WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		return (int)$row['count'];
	}
	
	public function fishRoomCfg (){
	    
	    $rows  = Loader_Redis::game()->getKeys("Fish_RoomCfg:*");
	    $roomCfg   = array();
	    foreach ($rows as $row){
	        $key   = substr($row, 13);
	        $times = Loader_Redis::game()->hGet($row, 'gunmin');
	        $roomCfg[$key+7] = $times.'倍房'.'('.($key+7).')';
	    }
	    
	    return $roomCfg;
	}
	
	public function fishDetail($data){
	    
	    $aWhere    = array();
	    $table     = $data['table'] ? $data['table'] : $this->logmember.'_fish';
	    $aWhere    = "id=".Helper::uint($data['boardid']);
	    
	    $sql  = "SELECT * FROM $table WHERE $aWhere";
	    $rows = Loader_Mysql::DBLogchip()->getOne($sql);
	    
	    //echo '<pre>';
	    //var_dump($data);
	    //var_dump($rows);
	    
        $killedtype = json_decode($rows['killedtype']);
        foreach ($killedtype as $type){
            $fishType  = $type[0];
            $useNum    = $type[1];
            $killNum   = $type[2];
            $rows['killedname'][] = $fishType.'号鱼'.'&nbsp;&nbsp;&nbsp;&nbsp;共打:'.$useNum.'炮&nbsp;&nbsp;&nbsp;&nbsp;打中:'.$killNum; 
        }
        
        if ($rows['skills']){
            $skills2name   = array(1=>'自动',2=>'极速',3=>'定屏',4=>'锁定',5=>'狂暴',6=>'万炮');
            $skills        = json_decode($rows['skills']);
            foreach ($skills as $skillstype){
                $skillid   = $skillstype[0];
                $skUseNum  = $skillstype[1];
                $skMoney   = $skillstype[2];
                
                $rows['skillsname'][] = $skills2name[$skillid].'&nbsp;&nbsp;&nbsp;&nbsp;次数： '.$skUseNum.'&nbsp;&nbsp;&nbsp;&nbsp;金币： '.$skMoney;
            }
        }
        
        if ($rows['unlock']){
            for ($i=1;$i<16;$i++){
                $unlocktype = $rows['unlock'] & 1 << $i;
                if ($unlocktype==0){
                    $unlock = $i;
                    break;
                }
            }
            
            $rows['unlockname'] = "解锁".$unlock."号炮";
        }
	    //var_dump($rows);
	    
	    return $rows;
	}
	
	
	public function getFishLog($data){
	    
	    $aWhere = array();
	    $table    =  $data['table'] ? $data['table'] : $this->logmember.'_fish';
	    $aWhere[] = $data['stime']  ? "etime>=".strtotime($data['stime']) : "etime>=".strtotime("-5 days");
	    $aWhere[] = $data['etime']  ? "etime<=".strtotime($data['etime']) : "etime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    $data['gid']!=5 && $aWhere[] = "gid=".Helper::uint($data['gid']);
	    $data['roomid'] && $aWhere[] = "roomid=".Helper::uint($data['roomid']);
	    
	    $currentPage = max(Helper::uint($data['pageNum']),1);
	    $numPerPage  =  Helper::uint($data['numPerPage']);
	    $recordStart = ($currentPage - 1) * $numPerPage;
	    
	    $sql  = "SELECT * FROM $table WHERE ".implode(" AND ", $aWhere)." ORDER BY etime DESC LIMIT  $recordStart,$numPerPage ";
	    $rows = Loader_Mysql::DBLogchip()->getAll($sql);
	    
	    return $rows;
	}
	
	public function getFishLogCount($data){
	    $aWhere = array();
	    $table    =  $data['table'] ? $data['table'] :$this->logmember.'_fish';
	    $aWhere[] = $data['stime']  ? "etime>=".strtotime($data['stime']) : "etime>=".strtotime("-5 days");
	    $aWhere[] = $data['etime']  ? "etime<=".strtotime($data['etime']) : "etime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    $data['gid']!=5 && $aWhere[] = "gid=".Helper::uint($data['gid']);
	    $data['roomid'] && $aWhere[] = "roomid=".Helper::uint($data['roomid']);
	
	    $sql  = "SELECT count(*) as count FROM $table WHERE ".implode(" AND ", $aWhere);
	    $row = Loader_Mysql::DBLogchip()->getOne($sql);
	    return (int)$row['count'];
	}
	
	public function getPlayLog($data){		

		$aWhere = array();
		$table    =  $data['table'] ? $data['table'] : $this->logmember.$data['gameid'];
		$aWhere[] = $data['stime']  ? "etime>=".strtotime($data['stime']) : "etime>=".strtotime("-5 days");
		$aWhere[] = $data['etime']  ? "etime<=".strtotime($data['etime']) : "etime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    if ($data['cmid']){
	       $cmid = $data['cmid'];
	       $aWhere[] = "boardid LIKE '%$cmid%'";
	    }
		
	    if ($data['level']){
	        $range = explode("-", $data['level']);
	        switch (count($range)){
	            case 1:
	                $aWhere[] = "level = $range[0]";
	                break;
	            case 2:
	                $aWhere[] = "level BETWEEN $range[0] AND $range[1]";
	                break;
	        }
	    }else {
    	    if ($data['act']=='playlog' && $data['gameid']==5){
    	        $aWhere[] = "level BETWEEN 4 AND 11";
    	    }
    	    if ($data['act']=='yulelog' && $data['gid']==5){
    	        $aWhere[] = "level < 4";
    	    }
	    }
		
		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
		
		$login_name = Helper::getCookie('dianler_adm');
		$spec = Main_Model::factory()->specailAccount($login_name);
		if(!$spec){
			if($data['gameid'] != 5){
				if(($data['level'] == 4 && $data['gameid'] == 4) || ($data['level'] == 5 && $data['gameid'] == 1) ){
					$aWhere[] = "level=0";
				}elseif( in_array($data['gameid'], array(1,6,7))){
					$aWhere[] = "level!=4 AND level!=5 ";
				}
			}
		}
		
		$sql  = "SELECT * FROM $table WHERE ".implode(" AND ", $aWhere)." ORDER BY etime DESC LIMIT  $recordStart,$numPerPage ";
		$rows = Loader_Mysql::DBLogchip()->getAll($sql);
		
		$endtype2name   = array(0=>'正常结束',2=>'弃牌',3=>'逃跑');

		switch ($data['act']){
		    case 'playlog':
		        $roomConfig = User_RoomConfig::$playlogRoomConfig[$data['gameid']];
		        break;
		    case 'yulelog':
		        $roomConfig = User_RoomConfig::$yulelogRoomConfig[$data['gid']];
		        break;
		}
		
		foreach ($rows as $k=>$row){
		    $roomName = '';
		    foreach ($roomConfig as $room=>$roominfo){
		        if ($roominfo['upper']){
		            if ($row['level']>=$roominfo['level'] && $row['level']<=$roominfo['upper']){
		                $roomName = $roominfo['roomName'];
		            }
		        }else {
		            if ($row['level']==$roominfo['level']){
		                $roomName = $roominfo['roomName'];
		            }
		        }
		    }
		    
			if($data['gameid'] == 4 && $row['level'] == 5){
				$endtype2name = array(
				    106=>'五小牛',105=>'五花牛',103=>'炸弹牛',10=>'牛牛',9=>'牛九',8=>'牛八',7=>'牛七',6=>'牛六',5=>'牛五',4=>'牛四',3=>'牛三',2=>'牛二',1=>'牛丁',0=>'没牛'
				);
			}
				
			if ($data['gameid'] == 5){
			    if($row['level'] == 3){
			        $endtype2name   = array(1=>'龙',2=>'和',3=>'虎');
			        $boardid = explode("|", $row['boardid']);
			        $rows[$k]['playerType'] = $boardid[1]==$data['mid'] ? '庄家' : '';
			    }
			
			    if ($row['level'] >= 4 && $row['level'] <= 11){
			        $endtype2name   = array(1=>'自动',2=>'极速',3=>'定屏',4=>'锁定',5=>'狂暴',6=>'万炮');
			
			        $dd      = $row['endtype'] >> 16;
			        $ee      = $row['endtype'] & 0x0000FFFF;
			        switch ($dd){
			            case 1:
			                $type = $endtype2name[$ee];
			                break;
			            case 2:
			                $type = '解锁'.$ee.'级炮';
			                break;
			            default:
			                $type = $ee.'号鱼';
			                break;
			        }
			        
			        if (!$dd){
			            $fire    = $row['chatcost'] >> 16;
			            $get     = $row['chatcost'] & 0x0000FFFF;
			            $rows[$k]['playerType'] = '打：'.$fire.' 中：'.$get;
			        }
			        $roomName = $roomName.'('.$row['level'].')';
			    }
			}
			$rows[$k]['roomName']  = $roomName ? $roomName : $row['level'];
			$rows[$k]['endtype']   = $type ? $type : $endtype2name[$row['endtype']];
		}

		return $rows;		
	}

	public function getPlayLogCount($data){
		$aWhere = array();
		$table    =  $data['table'] ? $data['table'] :$this->logmember.$data['gameid'];
		$aWhere[] = $data['stime']  ? "etime>=".strtotime($data['stime']) : "etime>=".strtotime("-5 days");
		$aWhere[] = $data['etime']  ? "etime<=".strtotime($data['etime']) : "etime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    
		$aWhere[] = "mid=".Helper::uint($data['mid']);
		
        if ($data['level']){
            $range = explode("-", $data['level']);
            switch (count($range)){
                case 1:
                    $aWhere[] = "level = $range[0]";
                    break;
                case 2:
                    $aWhere[] = "level BETWEEN $range[0] AND $range[1]";
                    break;
            }
        }else {
            if ($data['act']=='playlog' && $data['gameid']==5){
                $aWhere[] = "level BETWEEN 4 AND 11";
            }
            if ($data['act']=='yulelog' && $data['gid']==5){
                $aWhere[] = "level < 4";
            }
        }
		
		$sql  = "SELECT count(*) as count FROM $table WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		return (int)$row['count'];
	}
	
	public function getPaymentLog($data){		
		$aWhere = array();
		$table    =  $data['table']? $data['talbe'] : $this->payment;
		$aWhere[] = $data['stime']  ? "ptime>=".strtotime($data['stime']) : "ptime>=".strtotime("-5 days");
		$aWhere[] = $data['etime']  ? "ptime<=".strtotime($data['etime']." 23:59:59") : "ptime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);

		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
		
		$sql  = "SELECT * FROM $table WHERE ".implode(" AND ", $aWhere)." ORDER BY ptime DESC LIMIT  $recordStart,$numPerPage ";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		$status2name = array(0=>'提交订单',2=>'交易完成',3=>'退单',4=>'冻结',5=>'人工审核');
		
		foreach ($rows as $k=>$row){
			$rows[$k]['pstatus']  = $status2name[$row['pstatus']];
		}

		return $rows;		
	}
	
	
	public function getPaymentCount($data){
		$aWhere = array();
		$table    =  $data['table'] ? $data['talbe'] :$this->payment;
		$aWhere[] = $data['stime']  ? "ptime>=".strtotime($data['stime']) : "ptime>=".strtotime("-5 days");
		$aWhere[] = $data['etime']  ? "ptime<=".strtotime($data['etime']." 23:59:59") : "ptime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    
		$sql  = "SELECT count(*) as count FROM $table WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return (int)$row['count'];
	}
	
	public function getExchangeCount($data){
		$aWhere = array();
		$table    =  $data['table'] ? $data['talbe'] :"exchange.ex_logexchange";
		$aWhere[] = $data['stime']  ? "extime>=".strtotime($data['stime']) : "extime>=".strtotime("-5 days");
		$aWhere[] = $data['etime']  ? "extime<=".strtotime($data['etime']." 23:59:59") : "extime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    
		$sql  = "SELECT count(*) as count FROM $table WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBExchange()->getOne($sql);
		return (int)$row['count'];
	}
	
	public function getExchangelog($data){		
		$aWhere = array();
		$table    =  $data['table']? $data['talbe'] : "ex_logexchange";
		$aWhere[] = $data['stime']  ? "extime>=".strtotime($data['stime']) : "extime>=".strtotime("-5 days");
		$aWhere[] = $data['etime']  ? "extime<=".strtotime($data['etime']." 23:59:59") : "extime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);

		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
		$v = implode(" AND ", $aWhere);
		//直接传递sql语句过去
		$data['aWhere']      =  $v ;
		$data['recordStart'] = $recordStart   ;
		$data['numPerPage']  = $numPerPage   ;

		//拉取数据
		$deliver_url   = 'http://192.168.1.147/exchange/callback/test.php';
		$key = '1212';
		$data['sig'] = md5($mid.$sid.$cid.$pid.$ctype.$gid.$data['reqroll'].$key);
		
		$result = Helper::curl($deliver_url, $data);
		$rows = json_decode($result);
		$ret = array();
		
		foreach ($rows as $k=>$row){
			$ret[$k]['extime']  = $row->extime;
			$ret[$k]['roll']  = $row->roll;
			$ret[$k]['rollnow']  = $row->rollnow;
			$ret[$k]['phone']  = $row->phone;
			$ret[$k]['status']  = $row->status;
			$ret[$k]['gid']  = $row->gid;
		}

		return $ret;		
	}
	
	public function getRank($data){
		$gameid = Helper::uint($data['gameid']);
		$type   = $data['type'] ? $data['type'] : 'money';

		$items =  Loader_Redis::common()->get(Config_Keys::rank($gameid,$type));
		
		$temp = array();
		foreach ($items as $k=>$row) {
			if(in_array($row['mid'], Config_Pay::$specialAccount)){
					$login_name = Helper::getCookie('dianler_adm');
					$spec = Main_Model::factory()->specailAccount($login_name);
					if(!$spec){
						continue;
					}else{
						$temp[] = $row;
					}
			}else{
				$temp[] = $row;
			}
		}
		
		return $temp;
	}
	
	
	private function getMoneyRank(){
		$num  = 200;
		$typeRankKey   = Config_Keys::wealth();
		$preLogRankKey = Config_Keys::wealthhash();
		$rankInfo = Loader_Redis::rank()->zReverseRange($typeRankKey, 0, $num-1,true);
		if(!$rankInfo){
			return array();
		}
		
		$randInfo = array();
		$rank     = 0;
		foreach ($rankInfo as $mid=>$score) {
			if($mid > 1000){
				$preScore = (int)Loader_Redis::rank()->hGet($preLogRankKey, $mid);
				$arrUserInfo             =Member::factory()->getOneById($mid,false);
		        $randInfo[$rank]['mid']  = $mid;
				$randInfo[$rank]['money'] = $arrUserInfo['money'];
				$randInfo[$rank]['freezemoney'] = $arrUserInfo['freezemoney'];
				$randInfo[$rank]['wintimes'] = $arrUserInfo['wintimes'];
				$randInfo[$rank]['losetimes'] = $arrUserInfo['losetimes'];
				$randInfo[$rank]['drawtimes'] = $arrUserInfo['drawtimes'];
				$randInfo[$rank]['mnick']= empty($arrUserInfo['mnick']) ? '' : $arrUserInfo['mnick'];
				$randInfo[$rank]['rank'] = $rank + 1;
				$rank ++;
			}
		}
		
		return $randInfo;
	}
	
	
	public function resetBankPassword($data){
		$mid     = Helper::uint($data['mid']);
		$password  = trim($data['password']);
		
		if(!$mid || !$password){
			return false;
		}

		$isVip   = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);	
		$hasBank = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bankPWD');
		
		if($hasBank ){
			$password  = md5($password);
			Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'bankPWD', $password);
			return true;
		}
		return false;
		
	}
	
	public function resetAccountPassword($data){
		$sitemid   = Helper::uint($data['sitemid']);
		$sid       = Helper::uint($data['sid']);
		$password  = trim($data['password']);
		
		if(!$sitemid || !$password || !$sid){
			return false;
		}
		
		$table = $sid == 101 ? $this->phonenumber_register : $this->username_register ;
		$password = md5($password);		

		$sql = "UPDATE $table SET password='$password' WHERE sitemid=$sitemid LIMIT 1";
		Loader_Mysql::DBMaster()->query($sql);
		return  Loader_Mysql::DBMaster()->affectedRows() ? 1 : 0;
	}
	
	public function getLogBank($data){
		$aWhere[] = $data['stime']  ? "ctime>=".strtotime($data['stime']) : "ctime>=".strtotime("-7 days");
		$aWhere[] = $data['etime']  ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    
	    $currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
	    
	    $data['gid'] && $aWhere[] = "gameid=".Helper::uint($data['gid']);
	    $this->_dolog($data['mid'], $data['stime'], $data['etime']);
	    $table = $this->logbank.'_tmp';
		$sql = "SELECT * FROM $table WHERE  ".implode(" AND ", $aWhere) ." ORDER BY ctime DESC LIMIT  $recordStart,$numPerPage ";
		
		return Loader_Mysql::DBLogchip()->getAll($sql);
	}
	
	public function getLogBankCount($data){
		$aWhere[] = $data['stime']  ? "ctime>=".strtotime($data['stime']) : "ctime>=".strtotime("-7 days");
		$aWhere[] = $data['etime']  ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    $data['gid'] && $aWhere[] = "gameid=".Helper::uint($data['gid']);
	    $table = $this->logbank.'_tmp';
		$sql = "SELECT count(*) as count FROM $table WHERE  ".implode(" AND ", $aWhere) ;
		
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		return (int)$row['count'];
	}
	
	public function getLogBataccount($data){
		$aWhere[] = $data['stime']  ? "btime>=".strtotime($data['stime']) : "btime>=".strtotime("-30 days");
		$aWhere[] = $data['etime']  ? "btime<=".strtotime($data['etime']." 23:59:59") : "btime<=".NOW;
	    $data['mid']    && $aWhere[] = "mid=".Helper::uint($data['mid']);
	    $data['badmin'] && $aWhere[] = "badmin='{$data['badmin']}'";

	    if($data['btype']){
	    	if($data['btype'] == -1){
	    		$aWhere[] = "btype=-1";
	    	}else{
	    		$aWhere[] = "btype!=-1";
	    	}
	    }
	    
	    $currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
	    
	    $table = $this->logbataccount;
		$sql = "SELECT * FROM $table WHERE  ".implode(" AND ", $aWhere) ." ORDER BY btime DESC LIMIT  $recordStart,$numPerPage ";
		
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	
	public function getLogBataccountCount($data){
		$aWhere[] = $data['stime']  ? "btime>=".strtotime($data['stime']) : "btime>=".strtotime("-30 days");
		$aWhere[] = $data['etime']  ? "btime<=".strtotime($data['etime']." 23:59:59") : "btime<=".NOW;
	    $data['mid']    && $aWhere[] = "mid=".Helper::uint($data['mid']);
	    $data['badmin'] && $aWhere[] = "badmin='{$data['badmin']}'";
	    
		if($data['btype']){
	    	if($data['btype'] == -1){
	    		$aWhere[] = "btype=-1";
	    	}else{
	    		$aWhere[] = "btype!=-1";
	    	}
	    }

	    $table = $this->logbataccount;
		$sql = "SELECT count(*) as count FROM $table WHERE  ".implode(" AND ", $aWhere) ;
		
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return (int)$row['count'];
	}
	
	public function getBatAdminList(){
		$sql = "SELECT `badmin` FROM `uc_logbataccount` group by `badmin` ORDER BY null";
		return Loader_Mysql::DBMaster()->getAll($sql);
	}
	
	public function setGan($data){
		$mid = Helper::uint($data['mid']);
		$gag = Helper::uint($data['gag']);
		
		if(!$mid){
			return false;
		}

		Loader_Redis::account()->hSet(Config_Keys::gaghash(), $mid, $gag);
		
		return true;
	}
	
	public function getTable($table){
		if(!$table){
			return false;
		}
		
		$sql = "SHOW TABLES like '%$table%'";
		$rows = Loader_Mysql::DBLogchip()->getAll($sql);
		$tables = array();
		foreach ($rows as $row) {
			foreach ($row as $v){
				$tables[] = $v;
			}
		}
		
		return $tables;
	}
	
	
	public function getNewRegisterList($data){
		$gameid   = $data['gameid'] ? Helper::uint($data['gameid']) : 1;
		$ctype    = $data['ctype'];
		$cid      = $data['cid'];
		$date     = date("Y-m-d",(strtotime($data['date']) + 86400));
		$step     = $data['numPerPage'] ? $data['numPerPage']-1 : 29;
		$current  = $data['pageNum']    ? $data['pageNum'] : 0;
		
		$start = ($current - 1) * $step + ($current - 1);
		$end   = $start + $step;
		
		
		$key = Config_Keys::dayreg($gameid,$date);
		
		if ($ctype || $cid){
    		$mids = Loader_Redis::account()->lGetRange($key, 0, -1,false,false);
    		
    		$records = array();
    		foreach ($mids as $key=>$mid) {
    			$userInfo = Member::factory()->getOneById($mid);
    			if ($ctype){
    			     if ($cid){
        			     if ($ctype==$userInfo['ctype'] && $cid==$userInfo['cid']) {
        			         $userInfo['deviceno']    = Member::factory()->getDevicenoBymid($mid);
        			         $userInfo['mtime']       = json_decode($userInfo['mtime'],true);
        			         $userInfo['mentercount'] = json_decode($userInfo['mentercount'],true);
        			         $userInfo['registername'] = $this->getRegisterNameBysitemid($userInfo['sitemid'], $userInfo['sid']);
        			         $records[$key] = $userInfo;
        			     }
    			     }else if ($ctype==$userInfo['ctype']){
    			         $userInfo['deviceno']    = Member::factory()->getDevicenoBymid($mid);
    			         $userInfo['mtime']       = json_decode($userInfo['mtime'],true);
    			         $userInfo['mentercount'] = json_decode($userInfo['mentercount'],true);
    			         $userInfo['registername'] = $this->getRegisterNameBysitemid($userInfo['sitemid'], $userInfo['sid']);
    			         $records[$key] = $userInfo;
    			     }
    			}else {
    			    if ($cid){
    			        if ($cid==$userInfo['cid']){
    			            $userInfo['deviceno']    = Member::factory()->getDevicenoBymid($mid);
    			            $userInfo['mtime']       = json_decode($userInfo['mtime'],true);
    			            $userInfo['mentercount'] = json_decode($userInfo['mentercount'],true);
    			            $userInfo['registername'] = $this->getRegisterNameBysitemid($userInfo['sitemid'], $userInfo['sid']);
    			            $records[$key] = $userInfo;
    			        }
    			    }else {
        			    $userInfo['deviceno']    = Member::factory()->getDevicenoBymid($mid);
        			    $userInfo['mtime']       = json_decode($userInfo['mtime'],true);
        			    $userInfo['mentercount'] = json_decode($userInfo['mentercount'],true);
        			    $userInfo['registername'] = $this->getRegisterNameBysitemid($userInfo['sitemid'], $userInfo['sid']);
        			    $records[$key] = $userInfo;
    			    }
    			}
    		}
    		return array_slice($records, $start, $step+1);
		}else {
		    $mids = Loader_Redis::account()->lGetRange($key, $start, $end,false,false);
		    foreach ($mids as $key=>$mid) {
		        $userInfo = Member::factory()->getOneById($mid);
                $userInfo['deviceno']    = Member::factory()->getDevicenoBymid($mid);
                $userInfo['mtime']       = json_decode($userInfo['mtime'],true);
                $userInfo['mentercount'] = json_decode($userInfo['mentercount'],true);
                $userInfo['registername'] = $this->getRegisterNameBysitemid($userInfo['sitemid'], $userInfo['sid']);
                $records[$key] = $userInfo;
		    }
		    return $records;
		}
	}
	
	public function getNewRegisterCount($data){
	    $gameid   = $data['gameid'] ? Helper::uint($data['gameid']) : 1;
	    $ctype    = $data['ctype'];
	    $cid      = $data['cid'];
	    $date     = date("Y-m-d",(strtotime($data['date']) + 86400));
	    
	    $key = Config_Keys::dayreg($gameid,$date);
	    
	    if ($ctype || $cid){
    	    $mids = Loader_Redis::account()->lGetRange($key, 0, -1,false,false);
    	    
    	    $records = 0;
    	    foreach ($mids as $key=>$mid) {
    	        $userInfo = Member::factory()->getOneById($mid);
    	        if ($ctype){
    	            if ($cid){
            		     if ($ctype==$userInfo['ctype'] && $cid==$userInfo['cid']) {
            		         $records++;
            		     }
    	            }else if ($ctype==$userInfo['ctype']){
    	                $records++;
    	            }
    	        }else {
    	            if ($cid){
    	                if ($cid==$userInfo['cid']){
    	                    $records++;
    	                }
    	            }else {
    	                $records++;
    	            }
    	        }
    	    }
    	    return $records;
	    }else {
	        return Loader_Redis::account()->lSize($key);
	    }
		/*$gameid  = $data['gameid'] ? Helper::uint($data['gameid']) : 1;
		$date    = date("Y-m-d",(strtotime($data['date']) + 86400));
		
		$key = Config_Keys::dayreg($gameid,$date);*/
		
		//return $records;//Loader_Redis::account()->lSize($key);
	}
	
	public function cutFreezemoney($gameid,$mid,$money,$sid,$pid,$ctype,$cid){
		$gameid  = $gameid ? Helper::uint($gameid) : 1;
		$mid     = Helper::uint($mid);
		$money   = Helper::uint($money);
		
		$gameInfo    = Member::factory()->getGameInfo($mid);
		if($gameInfo['freezemoney'] < $money){
			return false;
		}
		
		$gameInfo = Member::factory()->getMoneyFromBank($mid, $money,$gameid);
		if(!$gameInfo){
			return false;
		}

		$row['freezemoney'] = (int)$gameInfo['freezemoney'];
		$row['money']       = (int)$gameInfo['money'];
		
		Logs::factory()->addWin($gameid,$mid, 5,$sid, $cid, $pid,$ctype, 1,abs($money));
	}
	
	public function getRollLog($data){		
		$aWhere = array();
		$aWhere[] = $data['stime']  ? "ctime>=".strtotime($data['stime']) : "ctime>=".strtotime("-5 days");
		$aWhere[] = $data['etime']  ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
		$data['gid'] && $aWhere[] = "gameid=".Helper::uint($data['gid']);
		$this->_dolog($data['mid'], $data['stime'], $data['etime']);
	    $table = $this->logroll.'_tmp';
		$currentPage = max(Helper::uint($data['pageNum']),1);
		$numPerPage  =  Helper::uint($data['numPerPage']);
		
		$recordStart = ($currentPage - 1) * $numPerPage;
		
		$sql  = "SELECT * FROM $table WHERE ".implode(" AND ", $aWhere)." ORDER BY ctime DESC LIMIT  $recordStart,$numPerPage ";
		$rows = Loader_Mysql::DBLogchip()->getAll($sql);
		
		$rmode2name = array(1=>'兑换消耗乐卷',2=>'系统加减 ',3=>'完成牌局任务',4=>'牌型任务',5=>'抽奖所得',6=>'激化码兑换所得',7=>'首充赠送',8=>'快速充值赠送',100=>'捕鱼所得');
		
		foreach ($rows as $k=>&$row){
			$row['desc'] = $rmode2name[$row['rmode']];
		}
		
		return $rows;		
	}
	
	public function getRollLogCount($data){
		$aWhere = array();
		$table    = $data['table']  ? $data['table'] : $this->logroll.$data['gameid'];
		$aWhere[] = $data['stime']  ? "ctime>=".strtotime($data['stime']) : "ctime>=".strtotime("-5 days");
		$aWhere[] = $data['etime']  ? "ctime<=".strtotime($data['etime']." 23:59:59") : "ctime<=".NOW;
		$data['gid']  && $aWhere[] = "gameid=".Helper::uint($data['gid']);
	    $aWhere[] = "mid=".Helper::uint($data['mid']);
	    $table = $this->logroll.'_tmp';
		$sql  = "SELECT count(*) as count FROM $table WHERE ".implode(" AND ", $aWhere);
		$row = Loader_Mysql::DBLogchip()->getOne($sql);
		return (int)$row['count'];
	}
	
	public function getPayRank($data){
		$aWhere = array();
		$stime  = strtotime(date("Ymd"));
		$aWhere[] = $data['stime']  ? "ptime>=".strtotime($data['stime']) : "ptime>=$stime";
		$aWhere[] = $data['etime']  ? "ptime<=".strtotime($data['etime']." 23:59:59") : "ptime<=".NOW;
		$data['gameid']&& $aWhere[] ="gameid=".Helper::uint($data['gameid']);
		
		$sql  = "SELECT mid,sum(pamount) amount FROM ucenter.uc_payment WHERE ".implode(" AND ", $aWhere)." GROUP BY mid ORDER BY amount DESC LIMIT 100 ";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);

		foreach ($rows as $k=>&$row) {
			$userinfo = Member::factory()->getUserInfo($row['mid']);
			$sql    = "SELECT mid FROM ucenter.uc_payment WHERE ".implode(" AND ", $aWhere) ." AND mid=".$row['mid'];
			Loader_Mysql::DBMaster()->query($sql);
			$record = Loader_Mysql::DBMaster()->affectedRows();
			$row['times'] = $record;
			$row['mnick'] = $userinfo['mnick'];
		}
		
		return $rows;
	}
	
	public function resetVip($data){
		$vipday  = $data['vip'];
		$vipday  = min($vipday,365);
		$exptime = Helper::uint($data['exptime']);
		$mid     = $data['mid'];
		
		$viptype    = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);
		$vipexptime = Loader_Redis::account()->ttl(Config_Keys::vip($mid));//如果之前购买了VIP，则天数累加
		$vipexptime = Helper::uint($vipexptime) ?  ceil(Helper::uint($vipexptime)/86400) : 0;
		$exptime = $exptime + $vipexptime;
		
		if(!$vipday){
			Loader_Redis::account()->delete(Config_Keys::vip($mid));
			return true;
		}
		
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

		return true;
	}
	
	public function rankWinCoin(){
		$num  = 200;
		$typeRankKey   = "realcoin";
		$rankInfo = Loader_Redis::rank(3)->zReverseRange($typeRankKey, 0, $num-1,true);
		if(!$rankInfo){
			return array();
		}
		
		$randInfo = array();
		$rank     = 0;
		foreach ($rankInfo as $mid=>$score) {
			if($mid > 1000){
				$randInfo[$rank]['flag']  = Loader_Redis::push()->hGet("blacklistland", $mid);
				$arrUserInfo              = Member::factory()->getOneById($mid,false);
		        $randInfo[$rank]['mid']   = $mid;
				$randInfo[$rank]['winmoney'] = $score;
				$randInfo[$rank]['money'] = $arrUserInfo['freezemoney'] + $arrUserInfo['money'];
				$randInfo[$rank]['mnick']= empty($arrUserInfo['mnick']) ? '' : $arrUserInfo['mnick'];
				$randInfo[$rank]['rank'] = $rank + 1;
				$rank ++;
			}
		}
		
		return $randInfo;
	}
	
	public function setBlacklist($mid){
		Loader_Redis::push()->hSet("blacklistland", $mid, 1);
		return true;
	}
	
	public function delBlacklist($mid){
		Loader_Redis::push()->hDel("blacklistland", $mid);
		return true;
	}
	
	public function getDeviceRank($type='deviceno'){
		$typeRankKey   = Config_Keys::devicerank($type);
		
		$items =  Loader_Redis::common()->get($typeRankKey);
		
		return $items;
	}
	
	public function getDeviceRankDay($date,$type='deviceno'){
		$date = $date ? $date : date("Y-m-d");
		$typeRankKey   = Config_Keys::deviceRankByDate($date, $type);
		$items =  Loader_Redis::common()->get($typeRankKey);

		return $items;
	}
	
	public function optIcon($mid,$opts){
		$subname  = $mid % 10000;
		$iconPath = Config_Inc::$iconPath; //本地文件夹路径
		
		if(in_array(1, $opts)){
			Helper::curl("http://192.168.1.111/cdn_icon/del.php", array('mid'=>$mid),'get');
			
			$aUser  = Member::factory()->getUserInfo($mid);
			$aMtime = json_decode($aUser['mtime'],true);
			$data['content'] = "尊敬的玩家：您上传的头像含有不合法内容，系统已经将你头像删除并给予您一次警告，后续请遵循游戏规则，如再上传不合法图片，将处封号！";
			foreach ($aMtime as $gameid=>$ctime) {
				$data['mid']    = $mid;
				$data['gameid'] = $gameid;
				$data['fid']    = NOW + mt_rand(1, 500);
				Feedback_Model::factory()->toSend($data);
			}
		}
		
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'iconVersion', NOW);
		
		if(in_array(2, $opts)){
			Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'iconblist', 1);
		}
		
		if(in_array(3, $opts)){
			Loader_Redis::ote($mid)->hDel(Config_Keys::other($mid), 'iconblist', 1);
		}
		
		return true;
	}

	public function banMid($data){
	    $mstatus   = $data['mstatus'];
	    $mid       = $data['mid'];
	    $gameid    = $data['gameid'];
	    $login_name = Helper::getCookie('dianler_adm');
	    
	    if($mstatus == 7 || $mstatus ==15 || $mstatus ==180 ){
	        M_Anticheating::factory()->batAccount($mid, $gameid,$login_name,$mstatus);
	        return true;
	    }elseif($mstatus == 1){
	        $flag        = Loader_Redis::account()->delete(Config_Keys::banAccount($mid));
	        $deviceno    = Member::factory()->getDevicenoBymid($mid);
	        Loader_Redis::account()->delete(Config_Keys::banAccount($deviceno));
	        Loader_Redis::ote($mid)->hDel(Config_Keys::other($mid), 'bat');
	        M_Anticheating::factory()->setBatLog($mid, -1,$login_name);
	        return true;
	    }elseif($mstatus === 'forever'){
	        M_Anticheating::factory()->batAccount($mid, $gameid,$login_name,1000);
	        return true;
	    }
	}
	
}