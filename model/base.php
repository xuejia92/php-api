<?php !defined('IN WEB') AND exit('Access Denied!');

class Base extends Config_Table
{
	
	private static $_instance;
	
	public static function factory(){
		
		if(!self::$_instance){
			self::$_instance = new Base;
		}
		
		return self::$_instance;	
	}
	
	/**
	 * 获取公告
	 */
	public function getNotice($gameid,$cid,$ctype){
		$cid    = Helper::uint($cid);
		$ctype  = Helper::uint($ctype);
		$gameid = Helper::uint($gameid);
		if( !$gameid || !$cid || !$ctype){
			return false;
		}		
		
		$noticeInfo = Loader_Redis::common()->get(Config_Keys::notice($gameid,$cid,$ctype));
		$noticeInfo = false;
		if( $noticeInfo === false ){
			$sql = "SELECT * FROM $this->notice WHERE  ".NOW." > stime AND ".NOW."<etime AND gameid LIKE '%$gameid%' ORDER BY `ctime` DESC";
			$noticeInfo = Loader_Mysql::DBMaster()->getAll($sql);

			if(!$noticeInfo){
				return false;
			}
			
			$notice_cache = array();
			foreach ($noticeInfo as $notice) {
				$aCid     = explode(',', $notice['cid']);	
				$aGameid  = explode(',', $notice['gameid']);		
				$checkCtype = $notice['ctype'] == 0 ? true : ($notice['ctype'] == $ctype ? true : false);				
				if(in_array($cid, $aCid) && in_array($gameid,$aGameid) && $checkCtype){					
					$notice_cache[] = $notice;
				}
			}

			if(count($notice_cache) > 0){
				Loader_Redis::common()->set(Config_Keys::notice($gameid,$cid,$ctype), $notice_cache,false,true,86400*3);
				return $notice_cache;
			}else{
				Loader_Redis::common()->set(Config_Keys::notice($gameid,$cid,$ctype), 0 ,false,true,86400);
				return false;
			}
		}
		
		return $noticeInfo;
	}
	
	/**
	 * 获取版本更新提示
	 */
	public function getVersions($gameid,$cid,$ctype,$versions){		
		$cid      = Helper::uint($cid);
		$ctype    = Helper::uint($ctype);
		$gameid   = Helper::uint($gameid);
		if( !$gameid || !$cid || !$ctype || !$versions){
			return false;
		}	

		$sql = "SELECT * FROM $this->versions WHERE ctype='$ctype' AND status =1 AND gameid='$gameid' AND cid LIKE '%$cid%' ORDER BY `time` DESC ";
		$versionInfos = Loader_Mysql::DBMaster()->getAll($sql);	
		
		$ver = array();
			
		
		//在设置版本升级的时候 低版本强制更新 要后添加
		foreach ($versionInfos as $versionInfo) {
			$aCid     = explode(",", $versionInfo['cid']);

			if(in_array($cid, $aCid)){
				$ver =  $versionInfo;
				//如果找到的这一条的需要版本号小于当前版本号则继续查找
				if($versions<$versionInfo['versions']){
					break;
				}
			}
		}
		
		switch ($ver['con']) {
			case '>':
				$flag = $versions > $versionInfo['versions'];
				break;
			case '<':
				$flag = $versions < $versionInfo['versions'];
				break;
			case '=':
				$flag = $versions == $versionInfo['versions'];
				break;
			case '>=':
				$flag = $versions >= $versionInfo['versions'];
				break;
			case '<=':
				$flag = $versions <= $versionInfo['versions'];
				break;
		}

		
		if($flag){
			return $ver;
		}

		return false;
	}
	
	public function getChannel($cid=0){
		$cid = Helper::uint(cid);
		
		$idc = Config_Inc::$idc;
		$sql    = "SELECT * FROM $this->settingcid WHERE idc='$idc'";
		$record = Loader_Mysql::DBMaster()->getAll($sql);

		$aChannel   = array();
		foreach ($record as $v) {
			$aChannel[$v['cid']] = $v['cname'];
		}
		
		if($cid == 0){
			return $aChannel;
		}else{
			return $aChannel[$cid] ? $aChannel[$cid] : '';
		}		
	}
	
	public function getPack($pid=0){
		$pid = Helper::uint($pid);
		
		$idc = Config_Inc::$idc;
		$sql    = "SELECT * FROM $this->settingpid WHERE idc='$idc'";
		$record = Loader_Mysql::DBMaster()->getAll($sql);

		$aPack   = array();
		foreach ($record as $v) {
				$aPack[$v['pid']] = $v['pname'];
		}
		
		if($pid == 0){
			return $aPack;
		}else{
			return $aPack[$pid] ? $aPack[$pid] : '';
		}		
	}
	
	public function getGameidByPid($pid){
		$pid = Helper::uint($pid);
		$sql = "SELECT gameid FROM $this->settingpid WHERE pid='$pid'";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		
		return $row['gameid'];
	}
	
	public function getMoneyDescByWmode($wmode=0){

		//$logInfo = Loader_Redis::common()->get(Config_Keys::wmode());
		
		if(!$logInfo){
			$sql = "SELECT * FROM $this->wmode";
			$ifno = Loader_Mysql::DBMaster()->getAll($sql);
			$logInfo = array();
			foreach ($ifno as $k =>$v) {
				$logInfo[$v['wmodeID']] = $v;
			}
			Loader_Redis::common()->set(Config_Keys::wmode(),$logInfo,false,true,30*24*3600);
		}
		
		return $logInfo[$wmode]['admindesc'] ? $logInfo[$wmode]['admindesc'] : $logInfo;		
	}
	
	public function getAccountType($sid=0){
		$sid = Helper::uint($sid);
		
		$accountType = Loader_Redis::common()->get(Config_Keys::accountType());

		if(!$accountType){
			$sql    = "SELECT * FROM $this->settingsid ";
			$record = Loader_Mysql::DBMaster()->getAll($sql);

			$accountType   = array();
			foreach ($record as $v) {
				$accountType[$v['sid']] = $v['sname'];
			}

			Loader_Redis::common()->set(Config_Keys::accountType(),$accountType,false,true,30*24*3600);
		}

		if($sid == 0){
			return $accountType;
		}else{
			return $accountType[$sid] ? $accountType[$sid] : '';
		}					
	}
	
	public function feedBack($gameid,$cid,$sid,$pid,$ctype,$content,$mid,$nick,$phoneno,$pic,$ip){
		$cid     = Helper::uint($cid);
		$sid     = Helper::uint($sid);
		$pid     = Helper::uint($pid);
		$ctype   = Helper::uint($ctype);		
		$gameid  = Helper::uint($gameid);
		$content = Loader_Mysql::DBMaster()->escape($content);
		$pic     = Loader_Mysql::DBMaster()->escape($pic);
		$nick    = Loader_Mysql::DBMaster()->escape($nick);
		$phoneno = Loader_Mysql::DBMaster()->escape($phoneno);
		$ip      = $ip ? ip2long($ip) : 0;
				
		if(!$gameid || !$cid || !$sid || !$pid || !$ctype  ){
			return false;
		}
		
		$sql = "INSERT INTO $this->feedback SET gameid=$gameid,cid=$cid,pid=$pid,sid=$sid,ctype=$ctype,mnick='$nick',content='$content',mid=$mid,phoneno='$phoneno',pic='$pic',ip='$ip',ctime=".NOW;
		Loader_Mysql::DBMaster()->query($sql);		
		Loader_Redis::common()->delete(Config_Keys::feedback($gameid,$mid));		
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	public function getMyfeed($gameid,$mid){
		$mid     = Helper::uint($mid);
		$gameid  = Helper::uint($gameid);
		if(!$mid || !$gameid ){
			return false;
		}
		
		$_rnt = Loader_Redis::common()->get(Config_Keys::feedback($gameid,$mid),false,true);
				
		if($_rnt === false){
			$lastTime = strtotime("-15 days");		
			$sql      = "SELECT * FROM $this->feedback WHERE mid=$mid AND ctime > $lastTime AND gameid=$gameid ORDER BY ctime ASC ";
			$feedback = Loader_Mysql::DBMaster()->getAll($sql);
			$_rnt = array();
			
			$sql = "SELECT * FROM $this->feedback_reply WHERE mid=$mid AND gameid=$gameid AND rtime > $lastTime ORDER BY rtime ASC LIMIT 20";
			$replys = Loader_Mysql::DBMaster()->getAll($sql);
				
			$i=0;
			$conSort = array();
			if($feedback){
				foreach ($feedback as $k => $feed) {
					$_rnt[$i]['feedback'] = $feed['content']; 
					$_rnt[$i]['reply']    = "";
					$conSort[$i] = $feed['ctime'];
					if($feed['status'] == 1){
						$sql = "SELECT * FROM $this->feedback_reply WHERE fid='{$feed['id']}' LIMIT 1";
						$reply = Loader_Mysql::DBMaster()->getOne($sql);
						$_rnt[$i]['reply'] = $reply['content'];
					}
					$i ++;
				}
				
				if($replys){
					foreach ($replys as $reply) {
						$conSort[$i] = $reply['rtime'];
						$_rnt[$i]['feedback'] = " "; 
						$_rnt[$i]['reply']    = $reply['content'];
						$i ++;
					}
				}
				
				array_multisort($conSort, SORT_ASC, $_rnt);
								
			}else{
				foreach ($replys as $reply) {
					$_rnt[$i]['feedback'] = " "; 
					$_rnt[$i]['reply']    = $reply['content'];
					$i ++;
				}
			}
			
			if(!$_rnt){
				Loader_Redis::common()->set(Config_Keys::feedback($gameid,$mid), 0,false,true,30*24*3600);
			}else{
				Loader_Redis::common()->set(Config_Keys::feedback($gameid,$mid), $_rnt,false,true,30*24*3600);
			}
		}
		
		Loader_Redis::ote($mid)->hDel(Config_Keys::other($mid), 'feedback');//删除标志

		return $_rnt;
	}
	
	public function setMessageLogs($mid,$phoneno,$content,$type,$result){
		$mid     = Helper::uint($mid);
		$type    = Helper::uint($type);
		$phoneno = Loader_Mysql::DBMaster()->escape($phoneno);
		$content = Loader_Mysql::DBMaster()->escape($content);
		$time    = date("Y-m-d H:i:s");

		$sql = "INSERT DELAYED  INTO $this->message_logs SET mid=$mid,phoneno='$phoneno',content='$content',ctime='$time',status='$result',type='$type',msgid='$msgid'";
		$flag = Loader_Mysql::DBMaster()->query($sql);
		return true;
	}
	
	public function sendMessage($content,$phoneno,$type,$mid=''){
		
		switch ($type) {
			case 1:
				$msg = "您本次验证码是：".$content."【豆丸游戏】";
			break;
			case 2:
				$msg = "感谢您使用点丸，您的账号是：".$content['username']."，密码是：".$content['password'];
			break;
			case 3:
				$msg = $content."【豆丸游戏】";
			break;
		}
		
		$info['type']     = $type;
		$info['phoneno'] = $phoneno;
		$info['msg']     = $msg;
		
		Loader_Redis::common()->lPush(Config_Keys::msglist(), $info);//存放到消息队列
		
		return true;
	}

	public function authentication($mid,$id_no){
		$mid   = Helper::uint($mid);
		$id_no = trim($id_no);
		if(!$mid || !$id_no){
			return false;
		}
	}
	
	public function checkIdNo($id_no){
		$id = strtoupper($id_no);
	    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
	    $arr_split = array();
	    if(!preg_match($regx, $id))
	    {
	        return false;
	    }
	    $arrCity = array(
			        '11','12','13','14','15','21','22',
			        '23','31','32','33','34','35','36',
			        '37','41','42','43','44','45','46',
			        '50','51','52','53','54','61','62',
			        '63','64','65','71','81','82','91'
    			);


    	if (!in_array(substr($id, 0, 2), $arrCity)){//检查城市合法
    		return false;
    	}
	    if(15==strlen($id)){//检查15位
	        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
	 
	        @preg_match($regx, $id, $arr_split);
	        //检查生日日期是否正确
	        $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
	        if(!strtotime($dtm_birth))
	        {
	            return false;
	        } else {
	            return true;
	        }
	    }
	    else           //检查18位
	    {
	        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
	        @preg_match($regx, $id, $arr_split);
	        $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
	        if(!strtotime($dtm_birth))  //检查生日日期是否正确
	        {
	            return false;
	        }else 
	        {
	        	return true;
	        }
	    }
	}
	
	public function check18seh($id_no){
		$length = strlen($id_no);
		if($length == 18){
	        $birthday = substr($id_no, 6, 4) . '-' . substr($id_no, 10, 2) . '-' . substr($id_no, 12, 2);
	    }else{
	    	$birthday = '19' . substr($id_no, 6, 2) . '-' . substr($id_no, 8, 2) . '-' . substr($id_no, 10, 2);
	    }
	    
		$date = (int)((date("Y")-18).date("m").date("d"));
		$birthday = str_replace("-", "", $birthday);
		if($birthday <= 0){
			return false;
		}
		if($birthday >= $date){
			return false;
	    }else{
	     	return true;
	    }
	}
	
	public function setAntiaddictionLog($mid,$id_no){
		$time = NOW;
		$sql = "INSERT INTO $this->antiaddiction VALUES ('',$mid,'$id_no',$time)";
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	
	/**
	 * 
	 * 获取排行榜
	 * @param  int $type 榜单类型 1 连胜榜  2 累计胜榜
	 * @param  int $num  榜单人数
	 * @param  int $mid  自己的游戏 ID 
	 */
	public function getRank($type,$num=50,$mid=0,$gameid=3){
		
	    //getRank($type,20,$mid,$gameid
	    //$type 2:金币榜，1:赚钱榜
	    //$gameid 7
	    //$mid 5225813
	    
		$type = Helper::uint($type);
		$num  = Helper::uint($num);
		$mid  = $orgmid = Helper::uint($mid);
		
		if(!$type || !$num ){
			return false;
		}
		
		switch ($type) {
			case 1://今日赚金榜
				$typeRankKey   = $gameid == 7 ? Config_Keys::flomaxwinonecoin() : Config_Keys::maxwinonecoin();
				$preLogRankKey = $gameid == 7 ? Config_Keys::floonecoinhash()   : Config_Keys::onecoinhash();
				break;
			case 2://财富排行榜
			    
			    //flowerwealth
				$typeRankKey   = $gameid == 7 ? Config_Keys::flowealth()     : Config_Keys::wealth();
				
				//flowerwealthhash
				$preLogRankKey = $gameid == 7 ? Config_Keys::flowealthhash() : Config_Keys::wealthhash();
				
				//file_put_contents("mylog.txt", var_export($preLogRankKey,true));
				
				break;
			case 3://连胜的排行榜
				$typeRankKey   = Config_Keys::winstreak();
				$preLogRankKey = Config_Keys::winhash();
				break;
			case 4://累计赢金币排行榜
				$typeRankKey   = Config_Keys::maxwincoin();
				$preLogRankKey = Config_Keys::coinhash();
				break;	
			case 5://赚金榜（减去输+台费）
				//$typeRankKey   = Config_Keys::realcoin();
				//$preLogRankKey = Config_Keys::realhash();
				$typeRankKey   = Config_Keys::wealth();
				$preLogRankKey = Config_Keys::wealthhash();
				$num = 20;
				break;		
			case 6://慈善榜
				$typeRankKey   = Config_Keys::charity();
				$preLogRankKey = Config_Keys::coinhash();
				break;	
		}
		
		$rankInfo = Loader_Redis::rank($gameid)->zReverseRange($typeRankKey, 0, $num-1,true);
		
		/*
		if($mid){
			$rank = (int)Loader_Redis::rank($gameid)->zRevRank($typeRankKey, $mid);//取自己的排名	
			$myInfo['score'] = (int)Loader_Redis::rank($gameid)->zScore($typeRankKey, $mid);
			$rankInfo[$mid] = $myInfo['score'];
 		}
		*/

		if(!$rankInfo){
			return array();
		}

		$randInfo = array();
		$rank     = 0;
		
		foreach ($rankInfo as $mid=>$score) {
			
			if($score <= 0 || !$mid ){
				continue;
			}
			
			if(in_array($mid, Config_Pay::$specialAccount)){
				continue;
			}
			
			if($type == 5){
				$randInfo[$rank]['askers']= (int)Loader_Redis::common()->sSize(Config_Keys::asker($mid,$gameid));
				$randInfo[$rank]['status']= (int)Loader_Redis::common()->sContains(Config_Keys::asker($mid,$gameid), $orgmid,false,false);
			}
			
			$preScore = (int)Loader_Redis::rank($gameid)->hGet($preLogRankKey, $mid);
			$arrUserInfo             = $mid < 1000 ? Loader_Redis::rank($gameid)->hMget(Config_Keys::other($mid), array('mnick','icon')) : Member::factory()->getOneById($mid,false);
	        $randInfo[$rank]['mid']  = $mid;
			
	        $rk = $rank + 1;
	        if($rk < $preScore){
	        	$randInfo[$rank]['trend'] = 0;
	        }elseif($rk == $preScore){
	        	$randInfo[$rank]['trend'] = 1;
	        }else{
	        	$randInfo[$rank]['trend'] = 2;
	        }

			$icon = Member::factory()->getIcon($arrUserInfo['sitemid'], $arrUserInfo['mid'],'middle',$gameid);
			$randInfo[$rank]['icon'] = $icon ? $icon : "";
			$randInfo[$rank]['mnick']= empty($arrUserInfo['mnick']) ? '' : $arrUserInfo['mnick'];
			$randInfo[$rank]['rank'] = $rank + 1;
			$randInfo[$rank]['score']= $score;
			$randInfo[$rank]['sid']  = (int)$arrUserInfo['sid'];
			$rank ++;
		}
		
		return $randInfo;
	}
	
	public function getMyRank($mid,$type,$gameid=3){
		$orgmid = Helper::uint($mid);
		
		switch ($type) {
			case 1://今日赚金榜
				$typeRankKey   = $gameid == 7 ? Config_Keys::flomaxwinonecoin() : Config_Keys::maxwinonecoin();
				$preLogRankKey = $gameid == 7 ? Config_Keys::floonecoinhash()   : Config_Keys::onecoinhash();
				break;
			case 2://财富排行榜
				$typeRankKey   = $gameid == 7 ? Config_Keys::flowealth()     : Config_Keys::wealth();
				$preLogRankKey = $gameid == 7 ? Config_Keys::flowealthhash() : Config_Keys::wealthhash();
				break;
			case 3://连胜的排行榜
				$typeRankKey   = Config_Keys::winstreak();
				$preLogRankKey = Config_Keys::winhash();
				break;
			case 4://累计赢金币排行榜
				$typeRankKey   = Config_Keys::maxwincoin();
				$preLogRankKey = Config_Keys::coinhash();
				break;	
			case 5://赚金榜（减去输+台费）
				//$typeRankKey   = Config_Keys::realcoin();
				//$preLogRankKey = Config_Keys::realhash();
				$typeRankKey   = Config_Keys::wealth();
				$preLogRankKey = Config_Keys::wealthhash();
				break;		
			case 6://慈善榜
				$typeRankKey   = Config_Keys::charity();
				$preLogRankKey = Config_Keys::coinhash();
				break;		
		}
		if($mid){
			$rank  = Loader_Redis::rank($gameid)->zRevRank($typeRankKey, $mid);//取自己的排名	
			$score = (int)Loader_Redis::rank($gameid)->zScore($typeRankKey, $mid);
 		}
 		
		if($type == 5){
			$randInfo['askers']= (int)Loader_Redis::common()->sSize(Config_Keys::asker($mid,$gameid));
			$randInfo['status']= (int)Loader_Redis::common()->sContains(Config_Keys::asker($mid,$gameid), $orgmid,false,false);
		}
 		
 		$preScore = (int)Loader_Redis::rank($gameid)->hGet($preLogRankKey, $mid);
		$arrUserInfo      = Member::factory()->getOneById($mid,false);
        $randInfo['mid']  = $mid;
        
	 	$rk = $rank + 1;
	    if($rk < $preScore){
	        $randInfo['trend'] = 0;
	    }elseif($rk == $preScore){
	        $randInfo['trend'] = 1;
	    }else{
	        $randInfo['trend'] = 2;
	    }
        
		$icon = Member::factory()->getIcon($arrUserInfo['sitemid'], $arrUserInfo['mid'],$gameid);
		$randInfo['icon'] = $icon ? $icon : "";
		$randInfo['mnick']= empty($arrUserInfo['mnick']) ? '' : $arrUserInfo['mnick'];
		$randInfo['rank'] = $rank === false ? 0 : $rank + 1;
		$randInfo['score']= (int)$score;
		$randInfo['sid']  = (int)$arrUserInfo['sid'];
		
		return $randInfo;
	}
	
	//付费用户上线，短信通知
	public function payMemberLogin($mid){
		if(in_array($mid,Config_Game::$paymids)){
			$msg = "用户".$mid."上线了，请留意！";
			
			$phoneno = '18127052328';

			$limit = Logs::factory()->limitCount($phoneno, 'msg', 1,true,30*60);
			if( $limit < 3 ){
				$this->sendMessage($msg, $phoneno, 3);
			}
			//$this->sendMessage($msg, '13823615583', 3);
			
		}
	}
	
	public function setDownOtherGameStatus($deviceNo,$other_gameid){
		
		$status = Loader_Redis::common()->hGet(Config_Keys::downloadReward(), $deviceNo."|".$other_gameid);
		
		if($status == 2 || $status == 3 ){//已经领取标志
			return false;
		}

		return Loader_Redis::common()->hSet(Config_Keys::downloadReward(),$deviceNo."|".$other_gameid, 1);
	}
	
	public function updateDownloadOtherGameStatus($deviceNo,$gameid){
		
		$status = Loader_Redis::common()->hGet(Config_Keys::downloadReward(), $deviceNo."|".$gameid);
		if(!in_array($status,array(2,3))){
			//针对IOS9不能识别其实游戏，不论是否点击更多游戏下载，都改变状态，发奖励
			return Loader_Redis::common()->hSet(Config_Keys::downloadReward(), $deviceNo."|".$gameid, 2);
		}
		return false;
	}
	
	public function rewardBydownOtherGame($deviceNo,$mid,$gameid,$ctype,$sid,$cid,$pid,$other_gameid,$money){
		$status = Loader_Redis::common()->hGet(Config_Keys::downloadReward(), $deviceNo."|".$other_gameid);
		
		if($status == 2){
			$flag = Logs::factory()->addWin($gameid, $mid, 20, $sid, $cid, $pid, $ctype, 0, $money);
			Loader_Redis::common()->hSet(Config_Keys::downloadReward(), $deviceNo."|".$other_gameid,3);
			
			
			$config = array(
							1=>array(3=>91,4=>92,6=>262,5=>279,7=>280),
							3=>array(1=>93,4=>94,6=>263,5=>281,7=>282),
							4=>array(1=>95,3=>96,6=>264,5=>283,7=>284),
							5=>array(1=>285,3=>286,4=>287,6=>288,7=>289),
							6=>array(1=>265,3=>266,4=>267,5=>290,7=>291),
							7=>array(1=>277,3=>276,4=>275,5=>278,6=>274),
						);
						
			$itemid = $config[$gameid][$other_gameid];	

			Loader_Udp::stat()->sendData($itemid, $mid, $gameid, $ctype, $cid, $sid, $pid, Helper::getip());
		}
		
		return $status;
	}
	
	public function getPromotionStatus($mid,$deviceNo,$gameid,$ctype){

		$oclass     = 'Config_Game'.$gameid;
		$promotions = call_user_func_array(array($oclass,  "getVar" ),array('promotion'));
		
		$rewardCountBymid = Loader_Redis::ote($mid,'slave')->hGet(Config_Keys::other($mid), 'proreward');
		if($rewardCountBymid > count(Config_Game::$game)){//防止在多台设备上刷，然后出现领奖状态
			$flag = 1;
		}

		$aGameid = array();
		foreach (Config_Game::$game as $gameid=>$gname) {
			$st = Loader_Redis::account('slave')->hGet(Config_Keys::androidKey($gameid), $deviceNo);
			if($st == 2){
				$aGameid[] = $gameid;
			}
		}

		foreach ($promotions as &$promotion) {
			if(in_array($promotion['gameid'],$aGameid)){
		        $this->updateDownloadOtherGameStatus($deviceNo, $promotion['gameid']);
	        }
			$status = Loader_Redis::common('slave')->hGet(Config_Keys::downloadReward(), $deviceNo."|".$promotion['gameid']);
			$promotion['status'] = ($status == 0 || $flag == 1) ? 1 :  (int)$status;
			$promotion['durl']   = $promotion['durl'][$ctype];
			$promotion['pack']   = $promotion['pack'][$ctype];
		}

		return $promotions;
	}
	
	public function getPromotionStatus2($mid,$deviceNo,$gameid,$ctype,$height){
	    
	    $promotions = Config_Game::$promotion2;
	
	    $rewardCountBymid = Loader_Redis::ote($mid,'slave')->hGet(Config_Keys::other($mid), 'proreward');
	    if($rewardCountBymid > count(Config_Game::$game)){//防止在多台设备上刷，然后出现领奖状态
	        $flag = 1;
	    }

		$aGameid = array();
		foreach (Config_Game::$game as $gid=>$gname) {
			$st = Loader_Redis::account('slave')->hGet(Config_Keys::androidKey($gid), $deviceNo);
			if($st == 2){
				$aGameid[] = $gid;
			}
		}
	
	    foreach ($promotions as $key=>&$promotion) {
	        if ($gameid==$promotion['gameid']){
	            unset($promotions[$key]);
	        }
	        
	    	if(in_array($promotion['gameid'],$aGameid)){
		        $this->updateDownloadOtherGameStatus($deviceNo, $promotion['gameid']);
	        }
	        
	        $status = Loader_Redis::common('slave')->hGet(Config_Keys::downloadReward(), $deviceNo."|".$promotion['gameid']);
	        $promotion['imgurl'] = is_array($promotion['imgurl'][$height]) ? $promotion['imgurl'][$height][$ctype] :  $promotion['imgurl'][$height];
	        $promotion['status'] = ($status == 0 || $flag == 1) ? 1 :  (int)$status;
	        $promotion['durl']   = $promotion['durl'][$ctype];
	        $promotion['pack']   = $promotion['pack'][$ctype];
	    }

	    
	    $arr = array();
	    foreach ($promotions as $promot){
	        $arr[] = $promot;
	    }
	
	    return $arr;
	}
	
	public function checkPromotionStatusBylogin($deviceNo,$gameid,$games,$ctype){
		$oclass     = 'Config_Game'.$gameid;
		$promotions = call_user_func_array(array($oclass,  "getVar" ),array('promotion'));
		
		$downloadCount = 0;
		$status = 1;
		
		unset(Config_Game::$game[5]);
		unset(Config_Game::$game[7]);
		
		foreach (Config_Game::$game as $gameid=>$gmane) {
			$st = Loader_Redis::account('slave')->hGet(Config_Keys::androidKey($gameid), $deviceNo);
			if($st){
				$downloadCount ++;
			}
		}
		
		if($downloadCount == count(Config_Game::$game)){
			$status = 0;
		}
				
		foreach ($promotions as &$promotion) {
			$st = Loader_Redis::common('slave')->hGet(Config_Keys::downloadReward(), $deviceNo."|".$promotion['gameid']);
			if($st == 2){//有奖领取
				$status = 1;
				break;
			}
		}
		
		return $status;
	}
	
	/**
	 * 验证是否为力美广告引进的用户
	 * @udid 用户udid号 
	 */
	public function optLimeiAd($udid,&$aUser){
		if(!trim($udid)){
			return false;
		}
		
		$pre     = $aUser['gameid'];
		if(in_array($aUser['cid'], array(102,155))){
			$pre = $aUser['gameid'].'|'.$aUser['cid'];
		}
		
		$key = Config_Keys::ad();
		$flag = Loader_Redis::common()->sContains($key, $udid.'|'.$pre,false,false);
		
		if($flag){
			Loader_Udp::stat()->sendData(98, $aUser['mid'], $aUser['gameid'], $aUser['ctype'], $aUser['cid'], $aUser['sid'], $aUser['pid'], $aUser['ip']);
			
			$gameid2app = array(
									3=>array(1=>882414740,23=>882414740),
									4=>array(1=>879994259,24=>879994259,102=>979795799),
									6=>array(112=>988296409),
									7=>array(141=>1008041119,155=>1052640797),
									1=>array(1=>788485362,25=>788485362),
								);
			$gameid = $aUser['gameid'];
			$cid    = $aUser['cid'];					
			$app = $gameid2app[$gameid][$cid];

			$url = "http://ios.wapx.cn/ios/receiver/activate?app=$app&udid=$udid&idfa=$udid";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
			$result = curl_exec ( $ch );
			curl_close($ch);
			
			Logs::factory()->debug(array('url'=>$url,'udid'=>$udid,'result'=>$result),'optLimeiAd');
			return $result;
		}
	}
	
	/**
	 * 多盟推广
	 */
	public function optDomob($udid,&$aUser){
		if(!trim($udid)){
			return false;
		}

		$flag = Loader_Redis::common()->sContains(Config_Keys::ad(), $udid.'|'.$aUser['gameid'],false,false);
		if($flag){//在别的渠道已经激活
			return false;
		}
		
		$key = Config_Keys::domob();
		$flag = Loader_Redis::common()->sContains($key, $udid.'|'.$aUser['gameid'],false,false);
		
		if($flag){
			Loader_Udp::stat()->sendData(314, $aUser['mid'], $aUser['gameid'], $aUser['ctype'], $aUser['cid'], $aUser['sid'], $aUser['pid'], $aUser['ip']);		
			
			$rank = mt_rand(0, 100);
			if($rank < 10){
				$time = date("Ymd");
				Loader_Redis::common()->incr("kl|".$time,1,30*24*3600);
				return false;
			}
			
			$gameid2app = array(3=>882414740,4=>879994259,6=>'DL988296409',7=>1008041119,1=>788485362);
			$appkey   = $gameid2app[$aUser['gameid']];
			$actime   = NOW;
			$sign_key = "0bd112c639bda9c51877ee2e1f12d2be";
			$mac  = $macmd5 = $ifamd5 = "";
			$sign = sprintf("%s,%s,%s,%s,%s,%s",$appkey,$mac,$macmd5,$udid,$ifamd5,$sign_key);
			$sign = md5($sign);
			$url  = "http://e.domob.cn/track/ow/api/callback?appkey=$appkey&mac=$mac&ifa=$udid&acttime=$actime&acttype=2&returnFormat=1&sign=$sign";
			$ch   = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
			$result = curl_exec ( $ch );
			curl_close($ch);
			
			Logs::factory()->debug(json_encode(array('url'=>$url,'udid'=>$udid,'result'=>$result)),'optDomob');
			return $result;
		}
	}
	
	/**
	 * 果盈推广
	 */
	public function optGouying($udid,&$aUser){
		if(!trim($udid)){
			return false;
		}

		$pre  = $aUser['gameid'].'|'.$aUser['cid'];
		$key  = Config_Keys::guoying();		
		$flag = Loader_Redis::common()->sContains($key, $udid.'|'.$pre,false,false);
		
		if($flag){
			
			$__key = 'C|'.$udid.'|'.$pre;
			$fg   = Loader_Redis::common()->get($__key,false,false);
			
			if($fg){
				return false;
			}
			
			/*
			$rank = mt_rand(0, 100);
			if($rank < 10){
				$time = date("Ymd");
				Loader_Redis::common()->incr("gykl|".$time,1,30*24*3600);
				return false;
			}
			*/
			Loader_Udp::stat()->sendData(320, $aUser['mid'], $aUser['gameid'], $aUser['ctype'], $aUser['cid'], $aUser['sid'], $aUser['pid'], $aUser['ip']);
			
			$gameid2app = array(
									3=>array(1=>882414740,23=>882414740),
									4=>array(1=>879994259,24=>879994259,102=>979795799),
									6=>array(112=>988296409),
									7=>array(141=>1008041119,155=>1052640797),
									1=>array(1=>788485362,25=>788485362),
								);
			$gameid = $aUser['gameid'];
			$cid    = $aUser['cid'];					
			$app    = $gameid2app[$gameid][$cid];

			$url = "http://api.yahaomai.com/cp/notify?app=$app&idfa=$udid";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
			$result = curl_exec ( $ch );
			curl_close($ch);
			Loader_Redis::common()->set($__key,1,false,false,7*24*3600);
			Logs::factory()->debug(array('url'=>$url,'udid'=>$udid,'result'=>$result),'optGouying');
			return $result;
		}
	}
	
	/**
	 * 有米推广
	 */
	public function optYoumi($udid,&$aUser){
		if(!trim($udid)){
			return false;
		}

		$pre  = $aUser['gameid'].'|'.$aUser['cid'];
		$key  = Config_Keys::youmi();		
		$flag = Loader_Redis::common()->sContains($key, $udid.'|'.$pre,false,false);
		
		if($flag){
			
			/*
			$__key = 'C|'.$udid.'|'.$pre;
			$fg   = Loader_Redis::common()->get($__key,false,false);
			
			if($fg){
				return false;
			}
			
			/*
			$rank = mt_rand(0, 100);
			if($rank < 10){
				$time = date("Ymd");
				Loader_Redis::common()->incr("gykl|".$time,1,30*24*3600);
				return false;
			}
			*/
			$callback_url = Loader_Redis::common()->hGet('yomi', $udid);
			Loader_Udp::stat()->sendData(334, $aUser['mid'], $aUser['gameid'], $aUser['ctype'], $aUser['cid'], $aUser['sid'], $aUser['pid'], $aUser['ip']);
			
			$gameid2app = array(
									3=>array(1=>882414740,23=>882414740),
									4=>array(1=>879994259,24=>879994259,102=>979795799),
									6=>array(112=>988296409),
									7=>array(141=>1008041119,155=>1052640797),
									1=>array(1=>788485362,25=>788485362),
								);
			$gameid = $aUser['gameid'];
			$cid    = $aUser['cid'];					
			$app    = $gameid2app[$gameid][$cid];

			$url = $callback_url;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
			$result = curl_exec ( $ch );
			curl_close($ch);
			Loader_Redis::common()->hDel('yomi', $udid);
			//Loader_Redis::common()->set($__key,1,false,false,7*24*3600);
			Logs::factory()->debug(array('url'=>$url,'udid'=>$udid,'result'=>$result),'optYoumi');
			return $result;
		}
	}

	public function checkMtkey($param){
		$mid    = $param['mid'];
		$mtkey  = $param['mtkey'];
		$method = $param['param']['method'];
		
		if($param['param']['method'] == 'Register.phoneNumber' && $param['param']['type'] == 2){
			$limit = true;
		}
		
		$must_check_api_method = array('Bank.create',
										'Bank.saveMoney',
										'Bank.resetPassword',
										'Bank.transfer',
										'Clientbase.updateUser',
										'Clientbase.uploadIcon',
										'Clientbase.getBankPassword',
										'Clientbase.rewardPromotion',
										'Clientbase.guestBingding',
                            		    'Clientbase.createroom',
                            		    'Clientbase.searchroom',
										'Clientbase.getBonus',
										'Clientbase.getTaskReward',
										'Clientbase.getBonus2',
										'Clientbase.getTaskInnings',
										'wallet.ask',
										'wallet.give',
										'wallet.get',
										'Slots.bet',
										'Slots.changeCard',
										'Slots.settlement',
										'Register.resetPassword'
		    
		);
		
		if(in_array($param['param']['method'],$must_check_api_method) || $limit){
			$userInfoKey = Config_Keys::getUserInfo($mid);
			$server_mtkey = Loader_Redis::minfo($mid)->hGet($userInfoKey, 'mtkey');
			
			error_log("checkMtkey : userInfoKey=".$userInfoKey.", $mid=".$mid);
			error_log("checkMtkey : mtkey=".$mtkey.", server_mtkey=".$server_mtkey);
			if($server_mtkey != $mtkey ){
				Logs::factory()->debug(array('param'=>$param,'ip'=>Helper::getip()),'mtkey_error');
				return false;
			}
		}

		return true;
	}
	
	public function askWallet($asker,$giver,$gameid){
		$rankGiver = Loader_Redis::rank($gameid)->zRevRank(Config_Keys::wealth(), $giver);//取红包发放者的排名

		if(!$rankGiver || ($rankGiver > 50)){//判断是否在榜
			//return false;
		}
		
		Loader_Redis::common()->sAdd(Config_Keys::asker($giver,$gameid), $asker,false,false,Helper::time2morning());
		
		$count = Loader_Redis::common()->sSize(Config_Keys::asker($giver,$gameid));

		return $count;
	}
	
	public function giveWallet($giver,$money,$gameid,$sid, $cid, $pid, $ctype){
	
		$minWallet  = Config_Game::$wallet['min_wallet'];//最小红包的金币数

		$online_users = $this->getOnlineUserCount($gameid);
		$online_total = count($online_users);
		
		$money_send = ceil($money * 0.1);//扣10%手续费
		
		if($money_send < 50000){
			$maxGiveNum = ceil($online_total * 0.3);
		}elseif($money_send < 100000){
			$maxGiveNum = ceil($online_total * 0.4);
		}elseif($money_send < 500000){
			$maxGiveNum = ceil($online_total * 0.6);
		}elseif($money_send < 1000000){
			$maxGiveNum = ceil($online_total * 0.8);
		}elseif($money_send < 5000000){
			$maxGiveNum = ceil($online_total * 0.9);
		}else{
			$maxGiveNum = $online_total;
		}
		
		if($minWallet * $maxGiveNum > $money_send){//钱不够发，则重新分配人数
			$maxGiveNum = ceil($money_send/$minWallet);
		}

		$users_tobe_give = $this->caltaker($giver, $gameid,$maxGiveNum);
		
		if(!$users_tobe_give){
			return false;
		}

		$count  = count($users_tobe_give);
		$total  = $money_send;
		$wallet = 0;

		for($i=$count;$i>0;$i--){
			$wallet = $i == 1 ? $total :  mt_rand($minWallet, ceil($total/$i));
			$total  = $total - $wallet;
			$wallet      = min($wallet,$money_send*0.03);
			$aWallet[] = $wallet;
		}
		
		$_temp = array();
		foreach ($users_tobe_give as $k=>$mid) {
			$wallet      = $aWallet[$k];
			$_temp[$mid] = $aWallet[$k];
			Loader_Redis::common()->hSet(Config_Keys::give2wallet($gameid), $mid, $wallet,false,false,Helper::time2morning());
		}
		
		foreach ($users_tobe_give as $mid) {
			Loader_Tcp::sendMsg2Client($mid)->sendMsg($mid, 11, '');//告知全体玩家有红包
		}
		
		$flag  = Logs::factory()->addWin($gameid ,$giver, 24, $sid, $cid, $pid, $ctype, 1, $money);
		//Loader_Mysql::DBLogchip()->close();
		$last_give_sum = (int)Loader_Redis::rank($gameid)->zScore(Config_Keys::charity(), $giver);
		$total         = $last_give_sum + $money;
		$rankInfo = Loader_Redis::rank($gameid)->zAdd(Config_Keys::charity(), $total, $giver,false,false);
		
		/*
		arsort($_temp);
		$arr = array_keys($_temp);
		for($i=0;$i<6;$i++){//抽3个拿到最大红包的用户发喇叭
			Loader_Redis::common()->set(Config_Keys::tosend($arr[$i]), 1,false,false,5*60);
		}
		*/
		
		return $flag;
	}
	
	public function giveWalletBySystem($gameid,$money){
		
		//$systemMaxGiveNum = Config_Game::$wallet['system_give_max_num'];//系统单次发红包的最大人数
		
		$minWallet  = Config_Game::$wallet['min_wallet'];//最小红包的金币数

		$online_users = $this->getOnlineUserCount($gameid);
		$online_total = count($online_users);
		
		$maxGiveNum = $online_total;
		
		if($minWallet * $maxGiveNum > $money){//钱不够发，则重新分配人数
			$maxGiveNum = ceil($money/$minWallet);
		}

		$users_tobe_give = $this->getAskerFromOnlineUser($maxGiveNum, $gameid, 1);

		if(!$users_tobe_give){
			return false;
		}

		$count     = count($users_tobe_give);
		$total     = $money;
		$wallet    = 0;

		for($i=$count;$i>0;$i--){
			$wallet = $i == 1 ? $total :  mt_rand($minWallet, ceil($total/$i));
			$total  = $total - $wallet;
			$wallet = min($wallet,$money * 0.03);
			$aWallet[] = $wallet;
		}
		
		$_temp = array();
		foreach ($users_tobe_give as $k=>$mid) {
			$wallet = $aWallet[$k];
			$_temp[$mid] = $aWallet[$k];
			Loader_Redis::common()->hSet(Config_Keys::give2wallet($gameid), $mid, $wallet,false,false,Helper::time2morning());
		}
		
		Logs::factory()->debug($_temp,'system_send_wallet_log');
		
		foreach ($users_tobe_give as $mid) {
			Loader_Tcp::sendMsg2Client($mid)->sendMsg($mid, 12, '');//告知全体玩家有红包
		}
		
		/*
		arsort($_temp);
		$arr = array_keys($_temp);
		for($i=0;$i<4;$i++){//抽3个拿到最大红包的用户发喇叭
			Logs::factory()->debug($arr[$i],'wallet2send');
			Loader_Redis::common()->set(Config_Keys::tosend($arr[$i]), 1,false,false,5*60);
		}
		*/
		
		return true;
	}
	
	private function caltaker($giver,$gameid,$maxGiveNum=20){

		$askers = $this->getAskerFromAsker($giver, $gameid, $maxGiveNum);

		$count_askers = count($askers);
		if($count_askers < $maxGiveNum){
			$maxGiveNum = $maxGiveNum - $count_askers;
			$online_users = $this->getAskerFromOnlineUser($maxGiveNum, $gameid,$giver);
		}

		$users_tobe_give = $count_askers > 0 ? array_merge($online_users,$askers) : $online_users;

		if(count($users_tobe_give) < 1){
			return false;
		}
		
		return $users_tobe_give;		
	}
	
	private function getAskerFromOnlineUser($maxGiveNum,$gameid,$giver){
		
		$online_users    = array();
		
		for($port=4520;$port<4530;$port++){
			$keys = Loader_Redis::userServerByPort($port)->getKeys("User:*");
			foreach ($keys as $key){
				$serverInfo = Loader_Redis::userServerByPort($port)->hMget($key, array('id','svid','tid','gameid'));
				if($serverInfo['svid'] !=0 && $serverInfo['tid'] !=0 && $serverInfo['gameid'] == $gameid && $serverInfo['id'] !=$giver  ){
					if(count($online_users) >= $maxGiveNum){
						Logs::factory()->debug($online_users,'getOnlineUser');
						return $online_users;
					}
					if($serverInfo['id'] > 1000){
						if(!in_array($serverInfo['id'], $online_users)){
							array_push($online_users, $serverInfo['id']);
						}
					}
				}
			}
		}

		return $online_users;
	}
	
	private function getOnlineUserCount($gameid){
		$online_users = array();
		for($port=4520;$port<4530;$port++){
			$keys = Loader_Redis::userServerByPort($port)->getKeys("User:*");
			foreach ($keys as $key){
				$serverInfo = Loader_Redis::userServerByPort($port)->hMget($key, array('id','svid','tid','gameid'));
				if($serverInfo['svid'] !=0 && $serverInfo['tid'] !=0 && $serverInfo['gameid'] == $gameid ){
					if($serverInfo['id'] > 1000){
						if(!in_array($serverInfo['id'], $online_users)){
							array_push($online_users, $serverInfo['id']);
						}
					}
				}
			}
		}
		
		return $online_users;
	}
	
	private function getAskerFromAsker($giver,$gameid,$maxGiveNum){
		$askers  = array();
		$num     = 100;

		for($i=0;$i<$num;$i++){
			$asker = Loader_Redis::common()->sRandMember(Config_Keys::asker($giver,$gameid),false,false);
			$serverInfo = Loader_Redis::userServer($asker)->hMget(Config_Keys::userServer($asker), array('svid','tid','gameid'));

			if($serverInfo['svid'] !=0 && $serverInfo['tid'] !=0 && $serverInfo['gameid'] == $gameid  ){
				if(count($askers) >= $maxGiveNum){
					return $askers;
				}
					
				if(!in_array($asker, $askers) && ($asker != $giver)){
					array_push($askers, $asker);
					Loader_Redis::common()->sRemove(Config_Keys::asker($giver,$gameid), $asker,false,false);
				}
			}
		}
		
		return $askers;
	}
	
	public function getWallet($mid,$gameid,$sid, $cid, $pid, $ctype,$wtype){
		$money = Loader_Redis::common()->hGet(Config_Keys::give2wallet($gameid), $mid);
		
		if(!$money){
			return false;
		}
		
		$wtype2wmode = array(11=>25,12=>28);
		
		$flag = Logs::factory()->addWin($gameid, $mid, $wtype2wmode[$wtype], $sid, $cid, $pid, $ctype, 0, $money);
		
		if($flag){
			Loader_Redis::common()->hDel(Config_Keys::give2wallet($gameid), $mid);
		}
		
		return $money;
	}
	
	public function sendMsg($phoneno,$content,$type){
		$account  = "ldyouxi";
		$password = 879458;
		$target = "http://sms.chanzor.com:8001/sms.aspx";
		
		$stime = microtime(true);
				
		$data = "action=send&userid=&account=$account&password=$password&mobile=$phoneno&sendTime=&content=".rawurlencode("$content");
		$url_info = parse_url($target);
		$httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
		$httpheader .= "Host:" . $url_info['host'] . "\r\n";
		$httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
		$httpheader .= "Content-Length:" . strlen($data) . "\r\n";
		$httpheader .= "Connection:close\r\n\r\n";
		$httpheader .= $data;
		$fd = fsockopen($url_info['host'], 80);
		fwrite($fd, $httpheader);
		$gets = "";
		$count = 0;
		while(!feof($fd)) {
		      $gets .= fread($fd, 8204);
		      $count ++;
		      if($count > 2){
		      	Logs::factory()->debug($gets,'sendmsg1');
		      	break;
		      } 
		}
		fclose($fd);
		
		$etime = microtime(true);
		
		$start=strpos($gets,"<?xml");
		$data=substr($gets,$start);
		$xml=simplexml_load_string($data);
		$return = json_decode(json_encode($xml),true);
		
		Logs::factory()->debug(array($return,$etime-$stime),'sendmsg');
		
		Base::factory()->setMessageLogs('', $phoneno, $content, $type, $return['message']);
		//Loader_Mysql::DBMaster()->close();
	}
	
	public function taskInnings($mid,$roomid,$gameid,$sid, $cid, $pid, $ctype){
		
		$reward['money'] = $reward['roll'] = $reward['nextstep'] = $reward['box1'] = $reward['box2'] = 0;
		
		$playCount = Loader_Redis::server()->hGet(Config_Keys::taskhash(),$mid);
		
		$comFlags = Loader_Redis::server()->hGet(Config_Keys::taskcomhash(),$mid);
		
		$key = $gameid == 3 ? Config_Keys::landLordroomConfig($roomid) : Config_Keys::texasRoomConfig($roomid);
		
		$configs = Loader_Redis::server()->hMget($key,array('roundnum','coinlow1','coinhigh1','coinlow2','coinhigh2','rolllow1','rollhigh1','rolllow2','rollhigh2'));
		
		$taskConfigs = $configs['roundnum'];
		
		switch ($roomid) {
			case 1:
				$realPlay = $playCount   & 0x00FF;//255
				$comFlag  = $comFlags    & 0x0F;//15
			break;
			case 2:
				$realPlay  = $playCount   >>8 & 0x00FF;
				$comFlag   = $comFlags    >>4 & 0x0F;
			break;
			case 3:
				$realPlay  = $playCount   >>16 & 0x00FF;
				$comFlag   = $comFlags    >>8  & 0x0F;
			break;
			case 4:
				$realPlay  = $playCount   >>24 & 0x00FF;
				$comFlag   = $comFlags    >>12 & 0x0F;
			break;
		}
				
		if($comFlag & 2 ){//两个任务都已经完成，返回状态
			return false;
		}
		elseif( $comFlag & 1){//如果第一个已经完成，处理第二个任务
			$config    = $taskConfigs >>8 & 0x00FF;
			if($realPlay < $config){
				return false;
			}
	
			$reward  = $this->calTaskReward($roomid, $configs, 2,$mid);
			$comFlag = $comFlag | 2;
		}
		else{//都没完成，则处理第一个任务
			$config   = $taskConfigs & 0x00FF;
			if($realPlay < $config){
				return false;
			}
			$reward = $this->calTaskReward($roomid, $configs, 1,$mid);
			$comFlag = $comFlag | 1;
			$reward['nextstep'] = $taskConfigs >>8 & 0x00FF;//下一阶段局数
		}
		
		switch ($roomid) {
			case 1:
				$comFlags  = $comFlags  | $comFlag;
				$playCount = $playCount & 0xFFFFFF00;//重置为0
			break;
			case 2:
				$comFlags  = $comFlags  | $comFlag  << 4;
				$playCount = $playCount & 0xFFFF00FF;//重置为0
			break;
			case 3:
				$comFlags  = $comFlags  | $comFlag << 8;
				$playCount = $playCount & 0xFF00FFFF;//重置为0
			break;
			case 4:
				$comFlags  = $comFlags  | $comFlag << 12;
				$playCount = $playCount & 0x00FFFFFF;//重置为0
			break;
		}
				
		Loader_Redis::server()->hSet(Config_Keys::taskcomhash(), $mid, $comFlags);
		Loader_Redis::server()->hSet(Config_Keys::taskhash(),$mid,$playCount);
			
		if($reward['money']){
			$flag = Logs::factory()->addWin($gameid, $mid, 26, $sid, $cid, $pid, $ctype, 0, $reward['money']);
		}else{
			$flag = Logs::factory()->setRoll($gameid,$mid, $sid, $cid, $pid, $ctype,$reward['roll'],0, 3);
		}

		return  $flag ? $reward : false;
	}
	
	public function getTaskTimeReward($mid,$gameid,$sid, $cid, $pid, $ctype){
		
		switch ($gameid) {
			case 4:
				$redisObj  = Loader_Redis::baccarat();
				$playInfo  = $redisObj->hGet('newtasktime', $mid);
				$configKey = 'BullFight_TaskConfig';
			break;
			
			default:
				;
			break;
		}
		
		$playTime   = $playInfo & 0x00FFFF;
		$status     = $playInfo >> 16 & 0x00FFFF;
		$status     = $status + 1;

		$config     = Loader_Redis::server()->hMget($configKey, array('time_'.$status,'timereward_'.$status));
		$limitTime  =  $config['time_'.$status];

		if($playTime < $limitTime){
			return false;
		}
		
		$lastInfo = $playInfo;
		$playInfo = $status << 16;
		$redisObj->hSet('newtasktime', $mid, $playInfo);
		
		$rewardMoney = $config['timereward_'.$status];
		$flag        = Logs::factory()->addWin($gameid, $mid, 38, $sid, $cid, $pid, $ctype, 0, $rewardMoney);
		
		if(!$flag){
			$redisObj->hSet('newtasktime', $mid, $lastInfo);
			return false;
		}
				
		return $rewardMoney;
	}
	
	public function getTaskRoundReward($mid,$roomid,$gameid,$sid, $cid, $pid, $ctype){
		
		switch ($gameid) {
			case 4:
				$redisObj   = Loader_Redis::baccarat();
				$playInfo   = $redisObj->hGet('newtaskround', $mid);
				$configKey  = 'BullFight_TaskConfig';
				$mask1      = array(1=>0xFFFF00,2=>0xFF00FF,3=>0x00FFFF);
				$mask2      = array(1=>16777216,2=>33554432,3=>67108864);
			break;
			
			default:
				;
			break;
		}
		
		$bit        = ($roomid-1) * 8;
		$playCount  = $playInfo >> $bit & 0x00FF;
		$config     = Loader_Redis::server()->hMget($configKey, array('roundconf','roundreward_'.$roomid));
		$limitCount = $config['roundconf'] >> $bit & 0x00FF;

		if($playCount < $limitCount){
			return false;
		}
		
		$lastInfo = $playInfo;
		$playInfo = $playInfo & $mask1[$roomid] | $mask2[$roomid];
		$redisObj->hSet('newtaskround', $mid, $playInfo);
		
		$rewardConfig = $config['roundreward_'.$roomid];
		$tmp          = explode(',', $rewardConfig);
		$rewardMoney  = round( mt_rand($tmp[0], $tmp[1]), -2);
		$flag         = Logs::factory()->addWin($gameid, $mid, 26, $sid, $cid, $pid, $ctype, 0, $rewardMoney);
		
		if(!$flag){
			$redisObj->hSet('newtaskround', $mid, $lastInfo);
			return false;
		}

		return $rewardMoney;
	}
	
	public function getTaskCoinReward($mid,$gameid,$sid, $cid, $pid, $ctype){
		switch ($gameid) {
			case 4:
				$configKey = 'BullFight_TaskConfig';
			break;
			
			default:
				;
			break;
		}
		
		$taskInfo  = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'ctask');
		$status    = $taskInfo >> 32 &  0xFFFFFFFF;
		$realCoin  = $taskInfo & 0xFFFFFFFF;
		
		if($status & 4){//如果 三个任务都领取完了
			return false;
		}
		
		$level = 1;
		if($status & 2 ){// 第二个已经完成，处理第三个 
			$level = 3;
		}elseif($status & 1){//第一个已经完成，处理第二个
			$level = 2;
		}
		
		$config    = Loader_Redis::server()->hMget($configKey, array('coin_'.$level,'coinreward_'.$level));
		$limitCoin = $config['coin_'.$level];

		if($realCoin < $limitCoin){
			return false;
		}
		
		$lastInfo = $taskInfo;
		$maskConfig = array(1=>4294967296,2=>8589934592,3=>17179869184);
		$taskInfo   = $taskInfo | $maskConfig[$level];
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'ctask', $taskInfo);
		
		$rewardRoll = $config['coinreward_'.$level];
		$flag       = Logs::factory()->setRoll($gameid,$mid, $sid, $cid, $pid, $ctype,$rewardRoll,0, 3);
		
		if(!$flag){
			Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'ctask', $lastInfo);
			return false;
		}

		return $rewardRoll; 
	}
	
	private function calTaskReward($roomid,$configs,$taskorder,$mid){
		$reward['money'] = $reward['roll'] = $reward['box1'] = $reward['box2'] = 0;
		
		$max_money = $configs["coinhigh$taskorder"];
		$min_money = $configs["coinlow$taskorder"];
		$max_roll  = $configs["rollhigh$taskorder"];
		$min_roll  = $configs["rolllow$taskorder"];
		
		switch ($roomid) {
			case 1: //初级场 100% 金币
				$reward['money'] = mt_rand($min_money,$max_money);
				$reward['box1']  = mt_rand($min_money,$max_money);
				$reward['box2']  = mt_rand($min_money,$max_money);
			break;
			case 2://中级场70%金币
				$rand = mt_rand(1, 100);
				if($rand <= 70){
					$reward['money'] = mt_rand($min_money,$max_money);
					$reward['box1']  = mt_rand($min_money,$max_money);
					$reward['box2']  = mt_rand($min_money,$max_money);
				}else{
					$reward['roll']  = mt_rand($min_roll,$max_roll );
					$reward['box1']  = mt_rand($min_roll,$max_roll);
					$reward['box2']  = mt_rand($min_roll,$max_roll);
				}
			break;
			case 3://高级场50%金币
				$rand = mt_rand(1, 100);
				if($rand <= 50){
					$reward['money'] = mt_rand($min_money,$max_money);
					$reward['box1']  = mt_rand($min_money,$max_money);
					$reward['box2']  = mt_rand($min_money,$max_money);
				}else{
					$reward['roll']  = mt_rand($min_roll,$max_roll);
					$reward['box1']  = mt_rand($min_roll,$max_roll);
					$reward['box2']  = mt_rand($min_roll,$max_roll);
				}
			break;
			case 4://大师场20%金币
				$rand = mt_rand(1, 100);
				if($rand <= 20){
					$reward['money'] = mt_rand($min_money,$max_money);
					$reward['box1']  = mt_rand($min_money,$max_money);
					$reward['box2']  = mt_rand($min_money,$max_money);
				}else{
					$reward['roll']  = mt_rand($min_roll,$max_roll);
					$reward['box1']  = mt_rand($min_roll,$max_roll);
					$reward['box2']  = mt_rand($min_roll,$max_roll);
				}
			break;
		}
		
		$viptype = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);
		if($viptype == 100){//年会员加送
			if($reward['money']){
				$reward['money'] = ceil($reward['money'] * 0.1 + $reward['money']);
			}
			
			if($reward['roll']){
				$reward['roll'] = ceil($reward['roll'] * 0.1 + $reward['roll']);
			}
		}
		
		return $reward;
	}
	
	public function getWheelTimes($loginTimes){
		if($loginTimes < 3 ){
			$times = 1;
		}elseif($loginTimes < 5){
			$times = 2;
		}else{
			$times = 3;
		}
		
		return $times;
	}
	
	public function drawWheel($mid,$times,$bonus){
		
		for($i=0;$i<$times;$i++){
			$prize_id = $this->calBonus();
			
			if(Config_Game::$probability[$prize_id][3]){//大奖时间间隔
				Loader_Redis::common()->set(Config_Keys::bonusTimeLimit($prize_id), 1, false,false,Config_Game::$probability[$prize_id][3]*60);
			}
			
			if(Config_Game::$probability[$prize_id][2]){//大奖中奖累加
				Loader_Redis::common()->incr(Config_Keys::pond($prize_id), 1,Helper::time2morning());
			}
			
			$bonus[]  = array($prize_id,0);
		}
		
		$bonus_json = json_encode($bonus);
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'bonus', $bonus_json);
		
		return $bonus;
	}
	
	private function calBonus(){
		
		$prize_id = $this->calProbability();
		
		$config = Config_Game::$probability[$prize_id];
		if($config[2] != 0){
			$total     = Loader_Redis::common()->get(Config_Keys::pond($prize_id),false,false);
			$timelimit = Loader_Redis::common()->get(Config_Keys::bonusTimeLimit($prize_id),false,false);
			if($total > $config[2] || $timelimit){
				for($i=0;$i<100;$i++){
					$pid = $this->calProbability();
					if(Config_Game::$probability[$pid][2] == 0){//抽到没有数量限制的奖品
						return $pid;
					}
				}
			}
		}
		
		return $prize_id;
	}
	
	private function calProbability(){
		
		$configs = Config_Game::$probability;
		$prize2probability = array();
		$prize2probability[0]=0;
		foreach ($configs as $prizeID=>$config) {
			$prize2probability[$prizeID] = $config[0]*100 + (int)$prize2probability[$prizeID-1];
		}
		$rand = mt_rand(1, 100);
		foreach ($prize2probability as $id=>$probability) {
			if($rand <= $probability){
				return $id;
			}
		}
		
		return 1;
	}
	
	public function getBonus($mid,$prizeid,$gameid,$sid, $cid, $pid, $ctype){
		$record = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bonus');
		$bonus  = json_decode($record,true);

		$row = array();
		foreach ($bonus as $bonu) {
			$row[$bonu[0]] = $bonu[1];
		}
		
		if(!isset($row[$prizeid])){
			return false;
		}
		
		if($row[$prizeid] == 1){
			return false;
		}
		
		if(in_array($prizeid,array(2,4,6,8))){	//	金币
			$rewardMoney = Config_Game::$probability[$prizeid][1];
			$flag = Logs::factory()->addWin($gameid, $mid, 27, $sid, $cid, $pid, $ctype, 0, $rewardMoney);
			
			if($rewardMoney == 100000){
				$userInfo = Member::factory()->getUserInfo($mid);
				$msg = "恭喜".$userInfo['mnick']."在转转乐独领风骚，喜得100000金币，每天登陆、人人有奖";
				Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);
			}
		}
		
		if(in_array($prizeid,array(5,9))){	//	会员 
			$rewardTime = Config_Game::$probability[$prizeid][1];
			Member::factory()->setVip($mid, $gameid, $rewardTime);
			
			if($rewardTime == 7){
				$userInfo = Member::factory()->getUserInfo($mid);
				$msg = "恭喜".$userInfo['mnick']."在转转乐小试牛刀，喜得会员7天，每天免费多抽1次，大奖就来了!";
				Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);
			}
		}
		
		if(in_array($prizeid,array(1,7))){	//	乐卷
			$rewardRoll = Config_Game::$probability[$prizeid][1];
			Logs::factory()->setRoll($gameid, $mid, $sid, $cid, $pid, $ctype, $rewardRoll,0,5);
			
			if($rewardRoll == 5){
				$userInfo = Member::factory()->getUserInfo($mid);
				$msg = "恭喜".$userInfo['mnick']."在转转乐呼风唤雨，喜得乐卷5张！";
				Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);
			}
		}
		
		if(in_array($prizeid,array(3))){	//	小喇叭
			$rewardHorn = Config_Game::$probability[$prizeid][1];
			Loader_Redis::ote($mid)->hIncrBy(Config_Keys::other($mid), 'horn', $rewardHorn);
		}
		
		foreach ($bonus as &$bonu) {
			if($bonu[0] == $prizeid && $bonu[1] != 1){
				$bonu[1] = 1;
				break;
			}
		}
		
		//$end = end($bonus);
		//if($end[1] == 1){//已经抽完
		Logs::factory()->limitCount($mid, 7, 1, true,Helper::time2morning());//兼容其它游戏和老版本
		//}
		
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'bonus',json_encode($bonus));
		
		Loader_Udp::stat()->sendData(Config_Game::$probability[$prizeid][4], $mid, $gameid, $ctype, $cid, $sid, $pid, Helper::getip());//上报统计中心
		return true;
	}
	
	public function initCheelByFirstLogin($mid,$lastLoginTime,$continuousLoginDay){
		if(Loader_Redis::common()->get(Config_Keys::loginReward($mid),false,false)){//在其它游戏或低版本领取了登陆奖，则不能抽
			return false;
		}
		
		if(Loader_Redis::common()->get(Config_Keys::lgm($mid),false,false)){//限制每天同一个账号在多个游戏中只能生成一次大转盘概率
			return false;
		}
		
		$lastLoginTime      = date("Ymd",$lastLoginTime);
		$yesterday          = date("Ymd",strtotime("-1 days"));

		$continuousLoginDay = $lastLoginTime == $yesterday ? $continuousLoginDay + 1 : 1;
		
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'continuousLoginDay', $continuousLoginDay);
		
		$times = $this->getWheelTimes($continuousLoginDay);

		$this->drawWheel($mid,$times,array());
		
		Loader_Redis::common()->set(Config_Keys::lgm($mid),1,false,false,Helper::time2morning());
	}

	private function calTurnoverRate($point){
		
		$rate['coin'] = 1;
		$rate['roll'] = 0;
		$d            = date("d");
		if(  $d % 2 != 0 && $point > 4){//大于1的奇数
			$rate['coin'] = 0.6;
			$rate['roll'] = 0.4;
		}
		
		$rand = mt_rand(1, 100);
		
		$type = $rand <= $rate['coin'] * 100 ? 'coin' : 'roll';
		
		$card['winning'][$type] = 0;
		$card['others']['coin'] = $card['others']['roll'] = array();
		
		$coinConfig = array(
								1=>array(500,1000),
								2=>array(500,700),
								3=>array(420,600),
								4=>array(450,600),
								5=>array(550,650),
								);
								
		$rollConfig = array(
								1=>array(1,3),
								2=>array(2,3),
								3=>array(2,3),
								4=>array(2,3),
								5=>array(2,3),
								);	

		$base_coin = round(mt_rand($point * $coinConfig[$point][0], $point * $coinConfig[$point][1]), -2);
		$base_roll = mt_rand($point * $rollConfig[$point][0], $point * $rollConfig[$point][1]);
				
		if($type == 'coin'){
			$card['winning']['coin'] = $base_coin;
		}else{
			$card['winning']['roll'] = $base_roll;
		}
		
		$card['others']['roll'] = $base_roll;

		switch ($point) {
			case 1:
				$card['others']['coin'][] = round($base_coin * 2   ,-2);
				$card['others']['coin'][] = round($base_coin * 10   ,-2);
				$type == 'roll' && $card['others']['coin'][] = round($base_coin * 5   ,-2);
			break;
			case 2:
				$card['others']['coin'][] = round($base_coin * 0.8 ,-2);
				$card['others']['coin'][] = round($base_coin * 9   ,-2);
				$type == 'roll' && $card['others']['coin'][] = round($base_coin * 4   ,-2);
			break;
			case 3:
				$card['others']['coin'][] = round($base_coin * 0.6 ,-2);
				$card['others']['coin'][] = round($base_coin * 8   ,-2);
				$type == 'roll' && $card['others']['coin'][] = round($base_coin * 3   ,-2);
			break;
			case 4:
				$card['others']['coin'][] = round($base_coin * 0.8 ,-2);
				$card['others']['coin'][] = round($base_coin * 6   ,-2);
				$type == 'roll' && $card['others']['coin'][] = round($base_coin * 2   ,-2);
			break;
			case 5:
				$card['others']['coin'][] = round($base_coin * 0.5 ,-2);
				$card['others']['coin'][] = round($base_coin * 0.9 ,-2);
				$type == 'roll' && $card['others']['coin'][] = round($base_coin * 0.2   ,-2);
			break;			
		}
		
		return $card;
	}
	
	public function getTurnover($mid,$gameid,$sid, $cid, $pid, $ctype){
		
		if(Loader_Redis::common()->get(Config_Keys::loginReward($mid),false,false)){//在其它游戏或低版本领取了登陆奖，则不能抽
			return false;
		}
		
		if(Logs::factory()->limitCount($mid, 7, 1, true,Helper::time2morning()) > 1){
			return false;
		}
		
		$lst = 'lst'.$gameid;
		$loginInfo 	= Loader_Redis::ote($mid)->hMget(Config_Keys::other($mid), array('lastLoginTime','star',$lst));
		$lastTime   = $loginInfo[$lst] ? $loginInfo[$lst] : $loginInfo['lastLoginTime'];
		$star       = $this->getTurnoverStar($lastTime, $loginInfo['star'],$mid);
		$card       = $this->calTurnoverRate($star);
		
		$isVip = Loader_Redis::account()->get(Config_Keys::vip($mid), false,false);
		$rate  = Config_Game::$loginVipConfig['rate'];
		if($card['winning']['coin']){
			$isVip && $card['winning']['coin'] = $card['winning']['coin'] * $rate;
			$flag = Logs::factory()->addWin($gameid, $mid, 37, $sid, $cid, $pid, $ctype, 0, $card['winning']['coin']);
		}else{
			$isVip && $card['winning']['roll'] = $card['winning']['roll'] * $rate;
			$flag = Logs::factory()->setRoll($gameid, $mid, $sid, $cid, $pid, $ctype, $card['winning']['roll'],0,5);
		}
		
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'star', $star);
		Logs::factory()->limitCount($mid, 7, 1, true,Helper::time2morning());//兼容其它游戏和老版本
		Loader_Redis::common()->set(Config_Keys::loginReward($mid),$gameid,false,false,Helper::time2morning());//用以区分低版本的连续登陆奖与大转盘

		//Loader_Udp::stat()->sendData(Config_Game::$turnover[$prizeid][1], $mid, $gameid, $ctype, $cid, $sid, $pid, Helper::getip());//上报统计中心
		return $card;
	}
	
	public function getTurnoverStar($lastLoginTime,$star,$mid){
		$isInit = Loader_Redis::common('slave')->get(Config_Keys::initLuckyPoint($mid),false,false);
		if( !$star || !$lastLoginTime || $isInit ){//初始值
			$star = 3;
			Loader_Redis::common()->set(Config_Keys::initLuckyPoint($mid), 1,false,false,Helper::time2morning());
		}else{
			$star               = (int)$star;
			$now                = date("Ymd",NOW);
			$lastLoginTime      = date("Ymd",$lastLoginTime);
			$diff = (strtotime($now) - strtotime($lastLoginTime)) / 86400;
			$star = $diff == 1 ? min($star+1, 5) : max(1,$star-$diff);
		}
		return $star;
	}
	
	/**
	 * 首充奖励
	 */
	public function firstPay($mid,$gameid,$pamount,$sid, $cid, $pid, $ctype,$versions){
		
		if( NOW < strtotime(Config_Game::$firstpayLimitDay)){
			$isReward = Loader_Redis::common('slave')->get(Config_Keys::firstpay($mid),false,false);
		}else{
			$isReward = Loader_Redis::ote($mid,'slave')->hGet(Config_Keys::other($mid), 'firstpay');
		}

		$arr_flag = explode(",", $isReward);

   		if( !in_array($gameid,$arr_flag)){//首冲 新版本(2014-10-15) 1.2.0
   			$pamount = round($pamount);
   			!in_array($pamount,array(2,5,6,10,1)) && $pamount=5;
   			$rewardMoney =  Config_Money::$firpayRewardNew[$pamount];
		    if($rewardMoney){
		    	if(in_array($gameid,array(5,6,7))){//德州首充
		    		$versions = '3.1.0';
		    	}
			    if($versions >= '3.0.0'){
			    	$horn = $versions < '3.1.0' ? 2 : 0 ;
			    	$roll = Config_Money::$firstPayRewardRoll;
			    	Logs::factory()->setRoll($gameid, $mid, $sid, $cid, $pid, $ctype, $roll,0,7);
			    }else{
			    	$horn = 10;
			    	$vipTime = (int)Config_Money::$firpayGetVip[$pamount];
					Member::factory()->setVip($mid, $gameid, $vipTime);
			    }
			    Loader_Redis::ote($mid)->hIncrBy(Config_Keys::other($mid), 'horn', $horn);
				$arr_flag[] = $gameid;
				$str_flag = implode(",", $arr_flag);
				
			    if( NOW < strtotime(Config_Game::$firstpayLimitDay)){
					Loader_Redis::common()->set(Config_Keys::firstpay($mid),$str_flag,false,false);
					Loader_Redis::common()->expireAt(Config_Keys::firstpay($mid), strtotime(Config_Game::$firstpayLimitDay));
				}else{
					Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'firstpay', $str_flag);
				}			    
   			}
	   	}
	   	
	   	return $rewardMoney;
	}
	
	/**
	 * 
	 * 破产开宝箱充值
	 */
	public function bankruPtpay($mid, $gameid, $sid, $cid, $pid, $ctype, $pamount){

		$rewardMoney = rand(10000, 30000);
		
		if($cid == 86){
			$rewardMoney = mt_rand(1000,3000);
		}

		return $rewardMoney;
	}
	
	/**
	 * 保存比赛获奖的手机号码
	 */
	public function saveMathPrizePhone($mid,$phoneno,$prize,$rank){
		
		$sql = "INSERT INTO $this->match_prize SET mid=$mid,rank=$rank,phone='$phoneno',prize='$prize',ctime=".NOW;
		Loader_Mysql::DBMaster()->query($sql);
		
		return Loader_Mysql::DBMaster()->affectedRows();
	}
	

	public function initPrize($istodayfirst, &$aUser){
		
		$mid  = $aUser['mid'];
		$flag = $aUser['loginRewardFlag'];
		/*if(Loader_Redis::common()->get(Config_Keys::loginReward($mid),false,false)){//在其它游戏或低版本领取了登陆奖，则不能抽
			return false;
		}
		
		if(!$flag){
			return false;
		}*/
	    
	    $prize_arr = Config_Game::$bonus_probability;
	    
	    //奖品个数限制
	    $limitCount = Loader_Redis::common('slave')->hGetAll('LoginBPD');
	    
	    if ($limitCount[6]>=50){
	        $prize_arr[6]['chance'] = 0;
	    }
	    
	    if ($limitCount[9]>=20){
	        $prize_arr[9]['chance'] = 0;
	    }
	    
	    if ($limitCount[12]>=10){
	        $prize_arr[12]['chance'] = 0;
	    }
	    
	    if ($limitCount[15]>=8){
	        $prize_arr[15]['chance'] = 0;
	    }
	    
	    //判断是否第一次登陆
	    $result['prize'] = $exists = (int)Loader_Redis::ote($mid,'slave')->hGet(Config_Keys::other($mid), 'prize');

	    if ($istodayfirst || $exists==0){
    	    foreach ($prize_arr as $key => $val) {
    	        $arry[$val['id']] = $val['chance'];
    	    }
    	
    	    $result = array();
    	
    	    //概率数组的总概率精度
    	    $proSum = array_sum($arry);
    	
    	    //概率数组循环
    	    foreach ($arry as $key => $proCur) {
    	        $randNum = mt_rand(1, $proSum);
    	        if ($randNum <= $proCur) {
    	            $result['prize'] = $key;
    	            break;
    	        } else {
    	            $proSum -= $proCur;
    	        }
    	    }
    	    
    	    //奖品个数限制
	        Loader_Redis::common()->hIncrBy('LoginBPD', $result['prize'], 1);
	        Loader_Redis::common()->setTimeout('LoginBPD', Helper::time2morning());
	        
    	    //记录奖品ID
    	    Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'prize', $result['prize']);
	    }
	    
	    //奖品id
	    $bonus_probability = Config_Game::$bonus_probability;
	    foreach ($bonus_probability as $val){
	        $result['bonus_probability'][] = array($val['id'],$val['prize']);
	    }
	    
	    //奖励概率
	    $bonusRate = Config_Game::$continuousLoginRate;
	    foreach ($bonusRate as $key=>$val){
	        $result['bonusRate'][] = array($key,$val);
	    }
	    //奖金
	    $result['bonus'] = $bonus_probability[$result['prize']]['prize'];
	    
	    //连续登陆奖励
	    $continuousLoginDay = $aUser['continuousLoginDay'];
	    if ($continuousLoginDay>5){
            $continuousLoginRate = $bonusRate[5];
	    }else {
            $continuousLoginRate = $bonusRate[$continuousLoginDay];
	    }
	    $result['continuousLoginReward'] = Helper::uint($result['bonus']*($continuousLoginRate-10)/10);
	    
	    //vip奖励
	    $result['vipReward'] = 0;
	    $isVip = Loader_Redis::account('slave')->get(Config_Keys::vip($mid), false,false);
	    if ($isVip) {
	        $result['vipReward'] = $result['bonus']*0.5;
	    }

	    $aUser['prize']                 = $result['prize'];
        $aUser['bonus_probability']     = $result['bonus_probability'];
        $aUser['bonusRate']             = $result['bonusRate'];
        $aUser['bonus']                 = $result['bonus'];
        $aUser['continuousLoginReward'] = $result['continuousLoginReward'];
        $aUser['vipReward']             = $result['vipReward'];
	}
	
	public function getBonus2($mid, $gameid, $sid, $cid, $pid, $ctype){
	    
	    $prize_arr           = Config_Game::$bonus_probability;
	    $continuousLoginRate = Config_Game::$continuousLoginRate;
	
	    error_log("prize_arr=".$prize_arr);
	    error_log("continuousLoginRate=".$continuousLoginRate);

	    //奖品id对应奖金
	    $prizeid = (int)Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'prize');
	    error_log("prizeid=".$prizeid);

	    $bonus = $prize_arr[$prizeid]['prize'];
	
	    $result = array();

	    if(Loader_Redis::common()->get(Config_Keys::loginReward($mid),false,false)){//在其它游戏或低版本领取了登陆奖，则不能抽
	        $result['result'] = 0;
	        return $result;
	    }
	    
		if(Logs::factory()->limitCount($mid, 7, 1, true,Helper::time2morning()) > 1){
			$result['result'] = 0;
	        return $result;
		}
	    
	    if(!$bonus){
	        $result['result'] = 0;
	        return $result;
	    }
	    
	    $userInfo = Member::factory()->getUserInfo($mid);
	
	    //连续登陆奖励
	    $atime          = json_decode($userInfo['mactivetime'],true);
	    $lastLoginTime  = $atime[$gameid];
	    $lst = 'lst'.$gameid;
	    $loginInfo 			= Loader_Redis::ote($mid)->hMget(Config_Keys::other($mid), array('lastLoginTime','continuousLoginDay',$lst));
		$continuousLoginDay = $loginInfo['continuousLoginDay'];
		$lastLoginTime      = $loginInfo[$lst] ? $loginInfo[$lst] : $loginInfo['lastLoginTime'] ;
		$lastLoginTime      = date("Ymd",$lastLoginTime);
		$yesterday          = date("Ymd",strtotime("-1 days"));
		$continuousLoginDay = $lastLoginTime == $yesterday ? $continuousLoginDay + 1 : 1;
		$continuousLoginDay = min($continuousLoginDay,7);
	    
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'continuousLoginDay', $continuousLoginDay);
		
	    $rate = 1;
	
	    if ($continuousLoginDay>5){
	        $rate = $continuousLoginRate[5];
	    }else {
	        $rate = $continuousLoginRate[$continuousLoginDay];
	    }
	
	    $loginReward = Helper::uint($bonus*($rate-10)/10);
	
	    //是否vip会员
	    $isVip = Loader_Redis::account()->get(Config_Keys::vip($mid), false,false);
	
	    //vip会员奖励倍数
	    $vipReward = 0;
	    
	    if ($isVip){
	        $vipReward = $bonus*0.5;
	    }
	
	    $total = $bonus+$loginReward+$vipReward;
	    
	    $flag = Logs::factory()->addWin($gameid, $mid, 27, $sid, $cid, $pid, $ctype, 0, $total);
	
	    //发布小喇叭
	    if ($total>=50000){
	        $msg = "恭喜".$userInfo['mnick']."连续登陆游戏，转盘中奖".$bonus."金币。";
	        Loader_Tcp::callServer2Horn()->setHorn($msg,$gameid,10);
	    }
	    
	    if ($total>=888888){
	        Logs::factory()->debug(array($gameid,$mid,$total),"Bonus2Above888888");
	    }
	    
	    $gameInfo   = Member::factory()->getGameInfo($mid);
	    
	    $result['result']  = 1;
	    $result['money']   = $gameInfo['money'];
	    
	    Logs::factory()->limitCount($mid, 7, 1, true,Helper::time2morning());//兼容其它游戏和老版本
	    Loader_Redis::common()->set(Config_Keys::loginReward($mid),$gameid,false,false,Helper::time2morning());//用以区分低版本的连续登陆奖与大转盘
	    
	    Loader_Udp::stat()->sendData(Config_Game::$bonus_probability[$prizeid]['countid'], $mid, $gameid, $ctype, $cid, $sid, $pid, Helper::getip());//上报统计中心
	    return $result;
	}
	
	//捕鱼购买炮等级
	public function setGunLevel($gameid, $mid, $sid, $cid, $pid, $ctype, $level) {
	    
	    $gameInfo   = Member::factory()->getGameInfo($mid);
        $userMoney  = $gameInfo['money'];
	    
        $result['gunLevel'] = $gunLevel = Loader_Redis::ote($mid)->hGet("OTE|$mid", 'gunLevel');
        
        $needMoney  = Loader_Redis::game()->hGet("FishUnlockCoin", "level:$level");
        
        $result = array();
        $result['result'] = 0;
        
        if ($gunLevel>=$level){
            return $result;
        }
        
        if ($userMoney>=$needMoney){
            $deductMoney = Logs::factory()->addWin($gameid, $mid, 36, $sid, $cid, $pid, $ctype, 1, $needMoney); //扣取金币
            if ($deductMoney){
                Loader_Redis::ote($mid)->hSet("OTE|$mid", 'gunLevel', $level);
                $result['result'] = 1;
                $result['gunLevel'] = $level;
            }
        }
        
        return $result;
	}
	
	//捕鱼炮样式
	public function gunStyle($mid) {
	    $result = array();
	    $result['result'] = 1;
	    
	    for ($i=2; $i<=3; $i++){
	        $time = Loader_Redis::account()->ttl(Config_Keys::gunStyle($mid, "$i"));
	        $result['gunStyle']["style$i"] = Helper::uint($time);
	    }
	    
	    return $result;
	}
}