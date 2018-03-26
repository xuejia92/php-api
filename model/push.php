<?php !defined('IN WEB') AND exit('Access Denied!');
class Push extends Config_Table {
	
 	protected static $_instance;
 	
 	CONST IS_PRODUCT = true;//是否生产环境
	
	/**
	 * 创建一个实例
	 *
	 * @author gaifyyang
	 * @return Push
	 */
	public static function factory(){
	    if(!is_object(self::$_instance)){
		    self::$_instance = new Push;
		}
		
		return self::$_instance;
	}
	
	 /**
	 * 保存推送机器码
	 * 
	 * @author gaifyyang
	 * @param str $token 机器码
	 * @param int $mid
	 * @param string $sid 应用ID
	 * @return true
	 */

	public function savePushDevice($mid,$token,$ctype,$cid,$sid,$pid,$gameid ) {
		$token = str_replace(array('<', '>', ' '), '', $token);
		$mid = Helper::uint( $mid );
		if(strlen($token) < 64 || ! $mid ){
			return false;
		}

		$cid   = Helper::uint($cid);
		$ctype = Helper::uint($ctype);
		$pid   = Helper::uint($pid);
		$sid   = Helper::uint($sid);

		//把推送数据分开记录到redis的队列和hash中		
		$aHsetData[0] = $token;
		$aHsetData[1] = $ctype;
		$aHsetData[2] = $cid;
		$aHsetData[3] = $sid;
		$aHsetData[4] = $pid;
		$aHsetData[5] = NOW;
		$aHsetData[6] = $gameid;
		$jsonData = json_encode($aHsetData);	
			
		$flag = Loader_Redis::push()->hSet(Config_Keys::pushinfo(), $mid, $jsonData);	

		if($flag){
			Loader_Redis::push()->rPush(Config_Keys::pushmid(), $mid,false,false);
		}
		
		$pushedFlag = Loader_Redis::push()->hGet(Config_Keys::pushed(), $token);
		if($pushedFlag){
			$type2itemid = array(1=>178,2=>101);
			Loader_Udp::stat()->sendData($type2itemid[$pushedFlag], $mid, $gameid, $ctype, $cid, $sid, $pid, 0);//推返回统计
			//Loader_Redis::push()->hDel(Config_Keys::pushed(), $token);
		}

		return true;
	}
 			
	/**
	 * 自动批量推送
	 * 
	 * @author gaifyyang
	 * @param array $devices
	 * @return true
	 */
	public function getPushSlice($pushkey,$start,$end,$limit=50){

		$aMids     = Loader_Redis::push()->lGetRange($pushkey, $start, $end,false,false);
		
		$aPushInfo = Loader_Redis::push()->hMget(Config_Keys::pushinfo(), $aMids);

		$records = array();
		$i = 0;
		
		if(!$aPushInfo){
			return array();
		}
		foreach ($aPushInfo as $mid => $info) {
			$aInfo = json_decode($info,true);
			$records[$i]['device'] = $aInfo[0];
			$records[$i]['mid']    = $mid;
			$records[$i]['cid']    = $aInfo[2];
			$records[$i]['ctype']  = $aInfo[1];
			$records[$i]['sid']    = $aInfo[3];
			$records[$i]['gameid'] = $aInfo[6] ? $aInfo[6] : 1;
			$i ++ ;
		}
		
		/*
		if($start < 50){//测试账号
			$testData = $this->_getTestPush();
			$records = array_merge($testData,$records);
			Logs::factory()->debug(array('records'=>$records,'key'=>$pushkey),'pushtest');			
		}
		*/
		return $records ? $records : array();		
	}
	
	//测试账号
	private  function _getTestPush(){
		$aData[0] = array('device'=>'ef32d54bced26e37c4dc9db7159af3493440e5e3e5a1729d646303b07656febd','mid'=>'113821','cid'=>2,'ctype'=>'2','ctype'=>'2','sid'=>'102','gameid'=>1);
		return $aData;
	}
	
	
	/**
	 * 主动推送
	 */
	public function pushMsgBymid($mid,$gameid=0,$type=1){		
		$type = Helper::uint($type);
		$mid = Helper::uint($mid);
		if (!$mid|| !$type) {
			return false;
		}

		$msg = Config_Push::$msg[$type];

		$jsonPushInfo = Loader_Redis::push()->hGet(Config_Keys::pushinfo(), $mid);
		
		if(!$jsonPushInfo){
			return false;
		}
		
		$aPushInfo    = json_decode($jsonPushInfo,true);
		$info['device']  = $aPushInfo[0];
		$info['ctype']   = $aPushInfo[1];
		$info['cid']     = $aPushInfo[2];
		$info['sid']     = $aPushInfo[3];
		$info['pid']     = $aPushInfo[4];

		if($gameid == 0){
			$gameid  = $aPushInfo[6] ? $aPushInfo[6] : 1;
		}
		
		var_dump($gameid);
		echo "<br/>";
		$oo = Ios_Api::factory($gameid,$info['ctype'],self::IS_PRODUCT);

		$oo->connect();			
		$oo->sendMsg(array($info), $msg);			
		$oo->disconnect();	
	}
	
	public function calmid2push(){

		$ymdhi = date("YmdHi",NOW);
		$sql = "SELECT * FROM $this->pushconfig WHERE status =1";
		$records = Loader_Mysql::DBMaster()->getAll($sql);

		$rows = array();
		foreach ($records as $record) {
			
			if($record['ptype'] == 1){
				$t    = $record['ptime'];
				$date = date("Ymd");
				$record['ptime'] = date("YmdHi",strtotime($date.' '.$t));
			}

			$flag = Loader_Redis::common()->get(Config_Keys::calpushflag($record['id']),false,false);//存放已经推过的标志
			
			if(strtotime($ymdhi) >= strtotime($record['ptime']) && !$flag ){
				Logs::factory()->debug($records,'calmid2push');
				Loader_Redis::common()->set(Config_Keys::calpushflag($record['id']), 1,false,false,Helper::time2morning());
				$ptype = $record['ptype'];
				
				$start = 0;
				$step  = $end = 1000;
	
				while (1) {
					$aMids = Loader_Redis::push()->lGetRange(Config_Keys::pushmid(), $start, $end,false,false);
						
					if(empty($aMids)){
						exit(0);
					}
						
					foreach ($aMids as $mid) {
						if($jsonPushInfo = Loader_Redis::push()->hGet(Config_Keys::pushinfo(), $mid)){
							$aPushInfo  = json_decode($jsonPushInfo,true);	
							$activeTime = $aPushInfo[5];
							$gameid     = $aPushInfo[6] ? $aPushInfo[6] : 1 ;
							$ctype      = $aPushInfo[1];
							$cid        = $aPushInfo[2];
							$pid        = $aPushInfo[4];
							$device     = $aPushInfo[0];
							
							$cid = 0;//暂时开所有渠道
												
							switch ($ptype) {
								case 1://N天没登陆常规推送
									$noActiveTime = strtotime("-".$record['pcon']." days");
									if(($activeTime <= $noActiveTime) || ($record['gameid'] == 1 && $mid == 1062) || ($record['gameid'] == 4 && $mid == 194281)){		

										//判断之前是否已经推过
										$cacheKey = Config_Keys::pushed();
										$status = Loader_Redis::push()->hGet($cacheKey, $device);
										
										if(!$status){	
											if($record['gameid'] == $gameid && $record['ctype'] == $ctype && ($record['cid'] == $cid || $record['cid'] == 0  )){
												/*
												$amount = Pay::factory()->getPayAmountBymid($mid);
												$date = date("Y-m-d");
												if($amount == 0 ){
													Loader_Redis::push()->hIncrBy('pushreward', $date.':49999', 1);
													$rewardMoney = 49999;
												}elseif($amount < 10){
													Loader_Redis::push()->hIncrBy('pushreward', $date.':199999', 1);
													$rewardMoney = 199999;
												}elseif($amount <= 100){
													Loader_Redis::push()->hIncrBy('pushreward', $date.':499999', 1);
													$rewardMoney = 499999;
												}elseif($amount <= 1000){
													Loader_Redis::push()->hIncrBy('pushreward', $date.':999999', 1);
													$rewardMoney = 999999;
												}else{
													Loader_Redis::push()->hIncrBy('pushreward', $date.':1999999', 1);
													$rewardMoney = 1999999;
												}
												*/
												Logs::factory()->debug($mid,'calmid2push2mid');
												Loader_Redis::push()->rPush(Config_Keys::topush($record['id'],$ptype,$gameid,$ctype,$cid), $mid,false,false,Helper::time2morning());
												//Loader_Redis::push()->hSet(Config_Keys::pushReward(), $device, $rewardMoney,false,false,7*3600*24);
											}	
															
											Loader_Redis::push()->hSet($cacheKey, $device, 2,false,false,7*3600*24);//保存7天（再每隔7天发送一次推送提醒）
										}		
									}	
									break;
									
								case 3://全局推	
									if($record['gameid'] == $gameid && $record['ctype'] == $ctype && ($record['cid'] == $cid || $record['cid'] == 0  )){
										
										//判断之前是否已经推过
										$cacheKey = Config_Keys::pushed();
										$status = Loader_Redis::push()->hGet($cacheKey, $device);
										if(!$status){
											Loader_Redis::push()->rPush(Config_Keys::topush($record['id'],$ptype,$gameid,$ctype,$cid), $mid,false,false,Helper::time2morning());
											Loader_Redis::push()->hSet($cacheKey, $device, 1,false,false,7*3600*24);//保存7天（再每隔7天发送一次推送提醒）
										}
									}																								
									break;
									
								case 2://活动推(某个时间段登陆)
									$aCondition = explode(",", $pushCondition);
									$start_noActiveTime = strtotime($aCondition[0]);
									$end_noActiveTime   = strtotime($aCondition[1]);
														
									if($activeTime > $start_noActiveTime && $activeTime < $end_noActiveTime && $record['gameid'] == $gameid && $record['ctype'] == $ctype && ($record['cid'] == $cid || $record['cid'] == 0  )){
										Loader_Redis::push()->rPush(Config_Keys::topush($record['id'],$ptype,$gameid,$ctype,$cid), $mid,false,false,Helper::time2morning());
									}
								break;
							}
						}
					}
					
					if($start==0 ){
						sleep(5);
						Loader_Redis::common()->lPush(Config_Keys::pushconfig(), Config_Keys::topush($record['id'],$ptype,$record['gameid'],$record['ctype'],$cid),false,false,Helper::time2morning());
					}
					
					$start = $end + 1;
					$end   = $start + $step;				
					usleep(1000);
				}
				
			}
		}
	}
	
	public function getPushMsgByid($id){
		$sql = "SELECT msg FROM $this->pushconfig WHERE id='$id'";
		$row = Loader_Mysql::DBMaster()->getOne($sql);
		return $row['msg'];
	}

}	