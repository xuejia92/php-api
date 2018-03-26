<?php !defined('IN WEB') AND exit('Access Denied!');
class Login{	
	public function guest($param){
		$data['sid']         = 100;//账号类型ID	
		$data['cid']         = Helper::uint($param['cid']);//渠道ID
		$data['ctype']       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$data['pid']         = Helper::uint($param['pid']);//客户端包类型ID
		$data['versions']    = $param['versions'];						
		$data['ip']          = Helper::getip();
		$data['lang']        = Helper::uint($param['lang']);//设备语言

		$data['mnick']       = $param['param']['mnick']      ? Helper::filterInput($param['param']['mnick']) : 'player'.rand(1, 10000) ;
		$data['device_name'] = $param['param']['device_name'];//设备名称
		$data['os_version']  = $param['param']['os_version'];//操作系统版本号
		$data['net_type']    = $param['param']['net_type']    ? strtolower($param['param']['net_type']) : '';//网络设备类型
		$data['device_no']   = $param['param']['device_no']   ? $param['param']['device_no']   : $data['ip'];//android 机器码
		$data['gameid']      = $param['gameid']               ? Helper::uint($param['gameid']) : 1;//游戏ID
		$data['deviceToken'] = $param['param']['deviceToken'] ? $param['param']['deviceToken'] : 0;//推送的机器码
		$openudid            = $param['param']['openudid'] ? $param['param']['openudid'] : '';
		$ip = $data['ip'];
		$device_no = $data['device_no'] = Member::factory()->getDeviceNo($param);
		
		
		$sitemid = $data['ctype'] == 1 ?  Member::factory()->getSitemidByKey($device_no) : Member::factory()->getSitemidByIosKey($device_no, $openudid);	
		error_log("sitemid=".$sitemid);
		
		!$sitemid && Config_Flag::mkret(Config_Flag::status_register_error);
				
		$username = '游客';
		

		$newSitemid = Loader_Redis::account('slave')->hGet(Config_Keys::bangdingRecord(), $sitemid);
		if($newSitemid){//处理游客已经绑定其它账号的情况 
			$arr         = explode(':', $newSitemid);
			$sitemid     = $arr[0];
			$data['sid'] = $arr[1];
			$username = Member::factory()->getUserNameBySitemid($sitemid, $data['sid']); 
		}

		$aUser = Member::factory()->getOneBySitemid($sitemid, $data['sid'],false);
		
		if( !$aUser ){//注册
			$flag       = strpos($data['device_name'],'Lan7');
			$deviceFlag = Loader_Redis::account('slave')->get(Config_Keys::banAccount($device_no),false,false);
			$ipFlag     = Loader_Redis::account('slave')->get(Config_Keys::banAccount($ip),false,false);
	
			if($flag !== false || $deviceFlag || $ipFlag ){
				error_log("1111-----");
			    Logs::factory()->debug(array('flag'=>$flag,'deviceFlag'=>$deviceFlag,'ipFlag'=>$ipFlag,'param'=>$param),'ban_deny_guest');
				Config_Flag::mkret(Config_Flag::status_register_error);
			}
			
			$max   = count(Config_Game::$game);
			
			$uuid = $param['param']['uuid'];
			if($uuid){
				$count = Logs::factory()->limitCount($uuid, 'uuid', 1,true, 24*3600);
				if($count > 2){//uuid防刷
				    error_log("22222-----");
					Logs::factory()->debug($ip.'|'.$data['device_no'].'|'.$data['device_name'].'|'.$data['gameid'].'|'.$data['cid'].'|'.$uuid,'uuid_guest_deny');
					Config_Flag::mkret(Config_Flag::status_register_error);
				}
			}

			$count = Logs::factory()->limitCount($ip, 'ip_reg', 1,true, 24*3600);
			if($count > $max*2 && $ip != "58.210.18.172" && strstr($ip, "192.168.1")===false){//IP防刷
			    error_log("33333-----");
				Logs::factory()->debug($ip.'|'.$data['device_no'].'|'.$data['device_name'].'|'.$data['gameid'].'|'.$data['cid'],'ip_guest_deny');
				Config_Flag::mkret(Config_Flag::status_register_error);
			}

			$mnick = Loader_Tcp::callServer2CheckName()->checkUserName($data['mnick']);//调C++词库看用户名是否合法
			if(strrpos($mnick, '*')){
				$data['mnick'] = 'player'.rand(1, 10000);
			}
			
			$data['sitemid']   = $sitemid;
			$aUser = Member::factory()->insert( $data );
		}
		
		if( !$aUser ){//没有该用户
			Config_Flag::mkret(Config_Flag::status_user_error);
		}
		$aUser['reg_pid']     = $aUser['pid'];//注册时的pid
		$aUser['versions']    = $data['versions'];
		$aUser['cid']         = $data['cid'];
		$aUser['pid']         = $data['pid'];
		$aUser['ctype']       = $data['ctype'];
		$aUser['lang']        = $data['lang'];//设备语言
		$aUser['devicename']  = $data['device_name'];
		$aUser['osversion']   = $data['os_version'];//操作系统版本号
		$aUser['nettype']     = $data['net_type'];//网络设备类型
		$aUser['device_no']   = $device_no;//机器码
		$data['ip'] && $aUser['ip'] = $data['ip'];
		$aUser['gameid']      = $data['gameid'];
		$aUser['deviceToken'] = $data['deviceToken'];
		$aUser['mtype']       = Helper::uint($param['param']['mobileOperator']);//1 移动卡  2 联通卡  3 电信卡
		
		$aUser['result']      = 1; 
		$aUser['mnick']       = Helper::filterInput($aUser['mnick']);
		
		$this->doUserInfo( $aUser );
		
		$aUser['username'] = $username; 
		return $aUser;
	}
	
	public function account($param){
		$data['sid']         = Helper::uint($param['sid']);//账号类型ID	
		$data['cid']         = Helper::uint($param['cid']);//渠道ID
		$data['ctype']       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$data['pid']         = Helper::uint($param['pid']);//客户端包类型ID
		$data['versions']    = $param['versions'];						
		$data['ip']          = Helper::getip();

		$data['device_name'] = $param['param']['device_name'] ? $param['param']['device_name'] : '';//设备名称
		$data['os_version']  = $param['param']['os_version']  ? $param['param']['os_version'] : '';//操作系统版本号
		$data['net_type']    = $param['param']['net_type']    ? $param['param']['net_type'] : '';//网络设备类型
		$data['device_no']   = $param['param']['device_no']   ? $param['param']['device_no'] : $data['ip'];//android 机器码	
		$data['deviceToken'] = $param['param']['deviceToken'] ? $param['param']['deviceToken'] : 0;//推送的机器码
		$data['phoneno']     = $param['param']['phoneno']     ? Helper::filterInput($param['param']['phoneno'])  : '';//手机号码
		$data['username']    = $param['param']['username']    ? Helper::filterInput(strtolower($param['param']['username'])) : '';//用户名
		$data['password']    = $param['param']['password']    ? Helper::filterInput($param['param']['password']) : '';//密码
		$data['deviceToken'] = $param['param']['deviceToken'] ? $param['param']['deviceToken'] : 0;//推送的机器码
		$data['sessionid']   = $param['param']['sessionid']   ? $param['param']['sessionid'] : 0;//乐游会话ID
		
		$data['mnick']       = $param['param']['mnick']       ? Helper::filterInput($param['param']['mnick']) : 'player'.rand(1, 10000);//昵称
		$data['sex']         = Helper::uint($param['param']['sex']) ;//性别
		$data['sitemid']     = $param['param']['sitemid']     ? $param['param']['sitemid'] : 0;//第三方平台唯一ID
		$data['siteurl']     = $param['param']['siteurl']     ? $param['param']['siteurl'] : "";//第三方平台用户头像URL
		
		$data['vendor']      = $param['param']['vendor']      ? $param['param']['vendor'] : '';//厂商ID
		$data['advertising'] = $param['param']['advertising'] ? $param['param']['advertising'] : '';//广告ID
		$data['mac_address'] = $param['param']['mac_address'] ? $param['param']['mac_address'] : '';//mac id
		$data['openudid']    = $param['param']['openudid']    ? $param['param']['openudid'] : '';//openudid
		$data['gameid']      = $param['gameid']               ? Helper::uint($param['gameid']) : 1;//游戏ID
		
		$data['openid'] = $param['param']['openid']?$param['param']['openid']:"";
		$data['nic_name'] = $param['param']['nic_name']?$param['param']['nic_name']:"";
		
		
		if( in_array($data['gameid'], array(1,6)) && $data['sid'] == 101){
			$data['username'] = $data['phoneno'];
		}
		
		if(Helper::isMobile($data['username'])){//先判断是否手机账号
			$accountInfo = Member::factory()->getSitemidByPhoneNumber($data['username'], $data['password']);
			$username    = $accountInfo['phoneno'];	
			$data['sid'] = 101;
			
			if(!$accountInfo){
				$accountInfo = Member::factory()->getAccountByBinding($data['username'], $data['password']);
				$username    = $accountInfo['username'];
				$data['sid'] = 102;
			}
			
			if(!$accountInfo){//如果没有找到，则去点乐账号表查找 
				$data['sid'] = 102;
			}
		}

		
		//file_put_contents("aaaaaaaaaaaaaa.txt", var_export($data,true));
		
		
		if(!$accountInfo){
			switch ($data['sid']) {
				case 101://手机号码
					$accountInfo = Member::factory()->getSitemidByPhoneNumber($data['phoneno'], $data['password']);
					$username    = $accountInfo['phoneno'];
				break;
				case 102://普通账号
					$accountInfo = Member::factory()->getSitemidByuserName($data['username'], $data['password']);
					$username    = $accountInfo['username'];
				break;
				case 103://乐游账号
					$accountInfo   = Member::factory()->leyoLogin($data['sessionid']);
					$username      = $data['mnick'] = $accountInfo['mnick'];
				break;
				case 104://qq账号
					$accountInfo['sitemid'] = $data['sitemid'];
					$username = $accountInfo['mnick']   = $data['mnick'];
				break;
				case 105://oppo账号登陆
				case 106://安智市场账号	
				case 107://移动基地支付
				case 108://悠悠村账号
				case 109://360账号		
				case 110://vivo账号
				case 111://小米账号
				case 112://千寻账号
				case 113://爱思账号
				case 114://xy账号
				case 115://铁克司雷	
				case 116://百度		
				case 117://起凡	
				case 118://海马
				case 119://FB
				case 120://微信账号
				    
				    $accountInfo   = Member::factory()->getSitemidByopenId($data['openid'],$data['nic_name']);
					$username    = $accountInfo['nic_name'];
				    break;
				case 121://华为账号
				case 122://支付宝账号
				case 123://电信爱游戏 	
					$accountInfo = array('sitemid'=>$data['sitemid'],'mnick'=>$data['mnick']);;
					$username = $accountInfo['mnick']   = $data['mnick'];	
				break;
			}
		}
		
//file_put_contents("aaaaaaaaaaaaaa1.txt", var_export($accountInfo,true));
		
		if($data['sid'] != "120")
		{
		    !$accountInfo && Config_Flag::mkret(Config_Flag::status_account_error);
		}
		
		$data['sitemid'] = $sitemid  = $accountInfo['sitemid'];
		$aUser = Member::factory()->getOneBySitemid($sitemid, $data['sid'],false);

		
		
//file_put_contents("aaaaaaaaaaaaaa2.txt", var_export($data,true));
		
		
		if( !$aUser ){//没有该用户
		    
			if(in_array($data['cid'], array('128'))){//不限制账号注册的渠道
				$no_limit_register = 1;
			}
		
			$ip = $data['ip'];
			
			// $status = M_Anticheating::factory()->registerLimit($ip, $data['cid'],$data['ctype'],$data['gameid'],$data['device_name'],$data['os_version'],$data['pid'],$data['versions']);
			// if(!$status){
			// 	Lib_Apistat::report('account', 'limitCount', 0, 300, "result:300|".$return);
			// 	Config_Flag::mkret(Config_Flag::status_user_error);
			// }
			
			$ip_arr = Lib_Ip::find($ip);

			//file_put_contents("aaaaaaaaaaaaaa2.txt", var_export($ip_arr,true));
			
			if(in_array($data['sid'],array(103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123))){
				$openudid && $openudid_count = Loader_Redis::common('slave')->hGet(Config_Keys::guestreglimit($data['gameid']), $openudid);//再利用openudid防刷(IOS)
				$limitKey = $data['device_no'] = Member::factory()->getDeviceNo($param);
				
				
				if($limitKey && !$no_limit_register){
					$max = count(Config_Game::$game);
					$count = Logs::factory()->limitCount($limitKey, 'reg', 1,true, 20*24*3600);
					if($count > $max+1 || $openudid_count > 3){//单个设备限制
						$param['ip'] = Helper::getip();
						$return = json_encode($param);
						Lib_Apistat::report('account', 'limitCount', 0, 300, "result:300|".$return);
						Config_Flag::mkret(Config_Flag::status_user_error);
					}
				}
				
				
				$aUser = Member::factory()->insert( $data );
			}else{
				Config_Flag::mkret(Config_Flag::status_user_error);
			}
		}
		
		$device_no = Member::factory()->getDeviceNo($param);
				
		$aUser['reg_pid']     = $aUser['pid'];//注册时的pid
		$aUser['versions']    = $data['versions'];
		$aUser['cid']         = $data['cid'];
		$aUser['pid']         = $data['pid'];
		$aUser['ctype']       = $data['ctype'];
		$aUser['devicename']  = $data['device_name'];
		$aUser['osversion']   = $data['os_version'];//操作系统版本号
		$aUser['nettype']     = $data['net_type'];//网络设备类型
		$aUser['device_no']   = $device_no;//机器码
		$data['ip'] && $aUser['ip'] = $data['ip'];
		$aUser['gameid']      = $data['gameid'];
		$aUser['deviceToken'] = $data['deviceToken'];
		$aUser['mtype']       = Helper::uint($param['param']['mobileOperator']);//1 移动卡  2 联通卡  3 电信卡
		$aUser['result']      = 1; 
		$aUser['mnick']       = Helper::filterInput($aUser['mnick']);
		$this->doUserInfo( $aUser );
		$aUser['username'] = $username; 
		if(in_array($aUser['sid'],array(104,105,106,117,109,119,120))){
			$aUser['big']   = $aUser['middle'] = $aUser['icon'] = $data['siteurl'];//第三方平台用户头像URL
			$aUser['mnick'] = $data['mnick'];
		}

//file_put_contents("mylog5.txt", var_export($aUser,true));

		//file_put_contents("aaaaaaaaaaaaaa3.txt", var_export($aUser,true));
		
		
		return $aUser;		
	}
	
	private function doUserInfo( &$aUser ){

		$aUser['mstatus'] = Loader_Redis::account('slave')->get(Config_Keys::banAccount($aUser['mid']),false,false);//查看账号状态
		$accountInfo      = Loader_Redis::ote($aUser['mid'],'slave')->hGetAll(Config_Keys::other($aUser['mid']));//获取账号的其它信息
		
		//file_put_contents("YYYYYYYYYY1111.txt", var_export($accountInfo,true),FILE_APPEND);
		
		
		if(!$aUser['mstatus']){
			$aUser['mstatus'] = Loader_Redis::account('slave')->get(Config_Keys::banAccount($aUser['device_no']),false,false);//查看机器码状态
		}
		
		if($aUser['mstatus'] !== false || $accountInfo['bat'] == 1){
			Config_Flag::mkret(Config_Flag::status_faild_access);
		}
		
		$aUser['mstatus'] = (int)$aUser['mstatus'];
		
		//file_put_contents("YYYYYYYYYY1112.txt", var_export($aUser,true),FILE_APPEND);
		
		$this->getSingleByGameid($aUser);
		
		/*
		$check_status = M_Anticheating::factory()->loginLimit($aUser);//查看是否符合登陆规则
		if(!$check_status){
			Config_Flag::mkret(Config_Flag::status_faild_access);
		}
		*/
		
		//file_put_contents("YYYYYYYYYY1113.txt", var_export($aUser,true),FILE_APPEND);
		
		$aUser['todayFirst']  = (int)$istodayfirst = ( date( 'Ymd', $aUser['mactivetime']) != date( 'Ymd', time()) ); // 今天第一次登录
		
		$aUser['binding']     = (int)$accountInfo['binding'];//是否已经绑定手机
		$aUser['wintimes']    = (int)$accountInfo['wi:'.$aUser['gameid']];//赢的次数
		$aUser['losetimes']   = (int)$accountInfo['ls:'.$aUser['gameid']];//输的次数
		$aUser['drawtimes']   = (int)$accountInfo['da:'.$aUser['gameid']];//和的次数
		//$aUser['firstpay']    = min(1,$accountInfo['firstpay']);//是否首次充值
		$aUser['helpnum']     = (int)$accountInfo['helpnum'];//提示次数 斗牛专用
		$aUser['feedback']    = (int)$accountInfo['feedback'];//反馈回复标志
		$aUser['ctask']       = (int)$accountInfo['ctask'];//金币任务奖励标志
				
		$aUser['lastLoginTime']   = $accountInfo['lastLoginTime'] = $accountInfo['lst'.$aUser['gameid']] ? $accountInfo['lst'.$aUser['gameid']] : $accountInfo['lastLoginTime'];
		$continuousLoginDay       = $accountInfo['continuousLoginDay'];
		$aUser['loginRewardFlag'] = Logs::factory()->limitCount($aUser['mid'], 7, 0,false) ? 0 : 1;
		$mactivetime              = date("Ymd",(int)$aUser['mactivetime']) == date("Ymd",NOW)  ? $accountInfo['lastLoginTime']  : $aUser['mactivetime'];
		$continuousLoginDay       = $aUser['loginRewardFlag'] == 1 ? $continuousLoginDay + 1 : $continuousLoginDay;
		
		$aUser['continuousLoginDay']  = $continuousLoginDay = date("Ymd",(int)$mactivetime) == date("Ymd",strtotime("-1 days")) ? $continuousLoginDay : 1;
		$aUser['continuousLoginDay']  = min($aUser['continuousLoginDay'],5);
		$aUser['todayReward']     = Config_Money::$loginReward[min($continuousLoginDay,7)];
		$aUser['tomorrowReward']  = Config_Money::$loginReward[min($continuousLoginDay+1,7)];
		
		$aUser['vip']         = (int)Loader_Redis::account('slave')->get(Config_Keys::vip($aUser['mid']),false,false);//会员标识
		
		if( in_array($aUser['gameid'],array(1,4,6,7))){
			$aUser['vip'] = min(1,$aUser['vip']);
		}

		$aUser['mw']          = (int)$accountInfo['mw:7'];//炸金花最大赢取金币
		$vipexptime           = (int)Loader_Redis::account('slave')->ttl(Config_Keys::vip($aUser['mid']));
		$aUser['vipexptime']  = $aUser['vip'] && $vipexptime ? ceil($vipexptime/86400) : 0;
		$aUser['bank']        = $accountInfo['bankPWD'] ? 1 : 0;//是否开启保险箱
		$aUser['horn']        = (int)$accountInfo['horn'];//小喇叭次数
		$aUser['vipconfig']   = Config_Money::$vipConfig[1];//vip配置
		
		$aUser['prop']        = (int)Loader_Redis::account('slave')->get(Config_Keys::props($aUser['gameid'], $aUser['mid']),false,false);//道具 斗地主：计牌器
		$propexptime          = (int)Loader_Redis::account('slave')->ttl(Config_Keys::props($aUser['gameid'], $aUser['mid']));
		$aUser['propexptime'] = $propexptime = Helper::uint($propexptime) ?  ceil(Helper::uint($propexptime)/86400) : 0;//过期时间
		
		
		if($istodayfirst){//每天第一次进入游戏
			//M_Anticheating::factory()->check_paiju($accountInfo, $aUser['mid'],$aUser['gameid'],$aUser['cid'],$aUser['version'],$aUser['mtime']);//检查牌局
			$this->doTodayFirst($aUser);
			if( ($aUser['gameid'] == 3 && $aUser['versions'] >= '1.2.0') || ($aUser['gameid'] == 1 && $aUser['versions'] >= '2.0.0')){//初始化大转盘
				Base::factory()->initCheelByFirstLogin($aUser['mid'], $aUser['mactivetime'], $accountInfo['continuousLoginDay']);
			}
		}
		
		$initPrize = Base::factory()->initPrize($istodayfirst, $aUser);//每日大转盘奖品新版本
		
		$aUser['tomorrowChanges'] = Base::factory()->getWheelTimes($continuousLoginDay);//取得明天的抽奖次数
		$aUser['probability']     = Config_Game::$probability_client;
		
		$star = Base::factory()->getTurnoverStar($mactivetime, $accountInfo['star'],$aUser['mid']);
		$aUser['lp1'] = $star;
		$aUser['lp2'] = min(5,$star + 1);
		
		$aUser['mtkey'] = Member::factory()->setMtkey($aUser['mid'],$aUser['device_no']);

		$status = Loader_Redis::server()->hGet(Config_Keys::verifyhash(), $aUser['mid']);
		$aUser['antiaddiction'] = $status ? 1 : 0;//防沉迷状态  1 已经通过身份验证  0 未通过验证或验证失败
		
		$oclass               = 'Config_Game'.$aUser['gameid'];
		$aUser['level']       = Config_Game::getLevel($aUser['exp']);//等级
		//$aUser['gameid']      == 3 && $aUser['designation'] = call_user_func_array(array($oclass,  "getDesignation" ),array($aUser['money']));//称号
		//$aUser['gameid']      == 3 && $aUser['match'] = call_user_func_array(array($oclass,  "getMathConfig" ),array());//斗地主比赛配置
		
		
		$aUser['serverInfo']  = call_user_func_array(array($oclass, "getServerInfo"),array($aUser['cid'],$aUser['mtype'],$aUser['versions'],$aUser['pmode'],$aUser['mid'],$aUser['lang']));//server配置
		
		$aUser['yuleInfo']    = Config_Yule::getYuleInfo($aUser);//娱乐场配置	

		$evaluateUrl          = call_user_func_array(array($oclass,  "getVar" ),array('evaluateUrl'));
		$aUser['evaluateUrl'] = $evaluateUrl[$aUser['cid']] ? $evaluateUrl[$aUser['cid']] : $evaluateUrl[1];//评价URL
				
		$aUser['bankruptcy']  = 1000;//破产条件 
		$aUser['bingReward']  = Config_Money::$bingReward;//绑定账号奖励
		$aUser['gameid']      == 2 && $aUser['bankConfig']      = Config_Game2::$bankConfig;//百家乐转账配置
		$aUser['firstpayroll'] = Config_Money::$firstPayRewardRoll;//首充送乐券
		$aUser['fastpayroll']  = Config_Money::$fastPayRewardRoll;//快速充值送乐券
		
		
		//file_put_contents("wwwwwwwwwwww1111.txt", var_export($aUser,true),FILE_APPEND);
		
		if(!$aUser['sex']){
			$aUser['sex'] = mt_rand(1, 2);
		}

		$aUser['big']     = Member::factory()->getIcon($aUser['sitemid'], $aUser['mid'],'big',$aUser['gameid'],$aUser['sex']);
		$aUser['middle']  = Member::factory()->getIcon($aUser['sitemid'], $aUser['mid'],'middle',$aUser['gameid'],$aUser['sex']);
		$aUser['icon']    = Member::factory()->getIcon($aUser['sitemid'], $aUser['mid'],'icon',$aUser['gameid'],$aUser['sex']);

		Config_Switch::optSwitch($aUser);//各种开关控制
		Config_Switch::rewardSwitch($aUser);//一些奖励开关配置
		
		$aUser['pmode']       = Config_Pay::getPmode($aUser['mtype'], $aUser['pid'], $aUser['ipArr'][1],$aUser['gameid']);//支付方式配置

		if( NOW < strtotime(Config_Game::$firstpayLimitDay)){
			$arr_firstpay_flag = Loader_Redis::common('slave')->get(Config_Keys::firstpay($aUser['mid']),false,false);
			$arr_firstpay_flag = explode(",", $arr_firstpay_flag);
		}else{
			$arr_firstpay_flag = explode(",", $accountInfo['firstpay']);
		}

		if( $aUser['firstpay'] == 0 && in_array($aUser['gameid'],$arr_firstpay_flag)){//首冲判断
			$aUser['firstpay'] = 1;
		}

		if($aUser['firstpay'] == 1 && $aUser['flashsaletime'] != -1){
			$istodayfirst && Loader_Redis::common()->set(Config_Keys::limitqiang($aUser['gameid'],$aUser['mid']), 1,false,false,3600);
			$aUser['flashsaletime'] = Loader_Redis::common('slave')->ttl(Config_Keys::limitqiang($aUser['gameid'], $aUser['mid']));//限时抢购剩余时间
		}
		
		//活动中心
		Base::factory()->payMemberLogin($aUser['mid']);//付费用户登陆，发信息告知
		$aUser['hasproreward'] = (int)Base::factory()->checkPromotionStatusBylogin($aUser['device_no'], $aUser['gameid'],$aUser['aMtime'],$aUser['ctype']);//检查更广游戏推广奖励
		$aUser['activityCenter'] = Config_Inc::$activityUrl;
		$showAty = Activity_Manager::getActivityList($aUser);
		$num = count($showAty);
		
		$aUser['activityIcon']   = $aUser['activityDesc'] = "";
		if($num>0 && $aUser['hasActivity'] == 1){
			$aUser['activityIcon']   = $showAty[0]['image'];
			$aUser['activityDesc']   = $showAty[0]['desc'];
			$aUser['hasActivity']    = 1;
		}
		
		if ($aUser['gameid'] == 3){
		    $aUser['enterRoomID'] = 1;
		}
		
		if(in_array($aUser['gameid'],array(5,3,6,7))){//捕鱼当前炮等级
		    $aUser['gunLevel'] = $accountInfo['gunLevel'] ? $accountInfo['gunLevel'] : 1;
		}

		if($aUser['pid'] == 1){//版权测试旧包
			Config_Flag::mkret(Config_Flag::status_user_error);
		}

		if( $aUser['gameid'] == 1 &&  $aUser['versions'] < '1.2.0'){//兼容showhand
			$aUser['sitemid'] = (int)$aUser['sitemid'];
		}

		
		if(in_array($aUser['mid'],array(3058909,3036300,2935367,3062703)) ){
			$aUser['pmode'] = 58;
		}

		$aUser['mmp']   = (int)$accountInfo['mmp'];// 1 不显示 0 显示
		$aUser['pmode'] != 18 && $aUser['mmp'] = 1;//移动MM 0.1元商品购买限制
		
		
		$shopVersions = Loader_Redis::common('slave')->hGetAll(Config_Keys::shopVersions());
		$aUser['vershop'] = Helper::uint($shopVersions['vershop']);
		$aUser['verwm']   = Helper::uint($shopVersions['verwm']);
		
		
		//file_put_contents("wwwwwwwwwwww1112.txt", var_export($aUser,true),FILE_APPEND);
		
		$this->unsetUserFiled($aUser);
	}
	
	private function doTodayFirst(&$aUser){
		Stat::factory()->userAction($aUser);//统计
		
		if( in_array($aUser['gameid'],array(1,3,4,6,7)) && $aUser['ctype'] == 2 ){//广告统计
            if($aUser['mentercount'] == 0){
                 Base::factory()->optLimeiAd($aUser['device_no'], $aUser);
                 if($aUser['cid'] == 155 && $aUser['gameid'] == 7){
                 	Base::factory()->optYoumi($aUser['device_no'], $aUser);
                 }
             }
        }        
				
		$lst = 'lst'.$aUser['gameid'];
		Loader_Redis::ote($aUser['mid'])->hSet(Config_Keys::other($aUser['mid']), $lst, $aUser['mactivetime']);//保存连续登陆奖励的昨天登陆奖励
		
		$ctask = $aUser['ctask'];
		$money = $aUser['money'] + $aUser['freezemoney'];
        $ctask = $ctask  >> 32 << 32 | $money; //写最新的金币数到金币任务
        Loader_Redis::ote($aUser['mid'])->hSet(Config_Keys::other($aUser['mid']), 'ctask', $ctask);

		Loader_Redis::rank($aUser['gameid'])->zAdd(Config_Keys::wealth(), $aUser['money'], $aUser['mid'],false,false);//实时排行棒
		Member::factory()->addLogin($aUser); //每天登陆，更新一些登录的信息;
		
		$aUser['deviceToken'] && Push::factory()->savePushDevice($aUser['mid'], $aUser['deviceToken'], $aUser['ctype'], $aUser['cid'], $aUser['sid'], $aUser['pid'],$aUser['gameid']);
	}
	
	private function getSingleByGameid(&$aUser){
		$aUser['aMactivetime'] = $aMactivetime = json_decode($aUser['mactivetime'],true);
		$aUser['mactivetime']  = (int)$aMactivetime[$aUser['gameid']];
		
		$aUser['aMentercount'] = $aMentercount = json_decode($aUser['mentercount'],true);
		$aUser['mentercount']  = (int)$aMentercount[$aUser['gameid']];
		
		$aUser['aMtime']       = $aMtime = json_decode($aUser['mtime'],true);
		$aUser['mtime']        = (int)$aMtime[$aUser['gameid']];
	}
	
	private function unsetUserFiled(&$aUser){
		
		$aUnsetFiled = array('device_no','ipArr','mtype','deviceToken','aMactivetime','aMentercount','aMtime','aPid','aVersions','devicename','osversion','nettype','vendor','gameid','cid','pid','ctype','versions','mtime','mstatus');
		
		foreach ($aUnsetFiled as $filed) {
			if($aUser[$filed]){
				unset($aUser[$filed]);
			}
		}
	}
	
	/* 
	// 绑定微信openid
	public function bindingopenid(&$aUser){
	    
	    exit('2222');
	    
	    $server_mtkey = Loader_Redis::minfo('5225866')->hGet($userInfoKey, 'mtkey');
	    
	    $cacheKey = Config_Keys::getGameInfo("5225866");
	    
	    
	    //print_r($cacheKey);
	    
	    $aInfo = Loader_Redis::minfo( "5225866" )->hGetAll( $cacheKey );
	    
	    
	    $openid = "oW0y_vo15LE3J-whTKzLb-4iMRRM";
	    
	    return $aUser;
	}
	 */
	
	
	// 微信通过openid登录
	public function wxlogin($param){
	     
	    file_put_contents("ZZZZZZZZZ000.txt", var_export($param,true),FILE_APPEND);
	    
	    /* 
	    
	    $mid = "5225866";
	    $ret['roominfo'] = Loader_Redis::common()->hget("flower_friend_room",'ROOMID_'.$mid);//获取喇叭次数
	    
	    // 已经存在房间
	    if($ret['roominfo'] != "")
	    {
	        file_put_contents("RRRRRRRRRRRRRRRR11111111.txt", var_export($ret,true),FILE_APPEND);
	        
	        $rs = json_decode($ret['roominfo']);
	        
	        file_put_contents("RRRRRRRRRRRRRRRR11111112.txt", var_export($rs,true),FILE_APPEND);
	    }
	    // 尚未存在房间
	    else {
	       
	    }
	    
	    exit;
	      */
	    
	    
	    //$ret['roominfo']   = (int)Loader_Redis::common()->hget("ROOMID|".$mid, "flower_friend_room");//获取喇叭次数
	    
	    
	    
	    $openid = $param['param']['openid'] ? $param['param']['openid'] : '';
	    
	    $accountInfo = Member::factory()->getSitemidByopenId($openid);
	    
	    //file_put_contents("ZZZZZZZZZ0001.txt", var_export($accountInfo,true),FILE_APPEND);
	    //file_put_contents("rrrrrrrrrrrrr11111221.txt", var_export($param,true),FILE_APPEND);
	    
	    $data['sid']         = Helper::uint($param['sid']);//账号类型ID
	    $data['cid']         = Helper::uint($param['cid']);//渠道ID
	    $data['ctype']       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad
	    $data['pid']         = Helper::uint($param['pid']);//客户端包类型ID
	    $data['versions']    = $param['versions'];
	    $data['ip']          = Helper::getip();
	     
	    $data['device_name'] = $param['param']['device_name'] ? $param['param']['device_name'] : '';//设备名称
	    $data['os_version']  = $param['param']['os_version']  ? $param['param']['os_version'] : '';//操作系统版本号
	    $data['net_type']    = $param['param']['net_type']    ? $param['param']['net_type'] : '';//网络设备类型
	    $data['device_no']   = $param['param']['device_no']   ? $param['param']['device_no'] : $data['ip'];//android 机器码
	    $data['deviceToken'] = $param['param']['deviceToken'] ? $param['param']['deviceToken'] : 0;//推送的机器码
	    $data['phoneno']     = $param['param']['phoneno']     ? Helper::filterInput($param['param']['phoneno'])  : '';//手机号码
	    $data['username']    = $param['param']['username']    ? Helper::filterInput(strtolower($param['param']['username'])) : '';//用户名
	    $data['password']    = $param['param']['password']    ? Helper::filterInput($param['param']['password']) : '';//密码
	    $data['deviceToken'] = $param['param']['deviceToken'] ? $param['param']['deviceToken'] : 0;//推送的机器码
	    $data['sessionid']   = $param['param']['sessionid']   ? $param['param']['sessionid'] : 0;//乐游会话ID
	    //$data['mnick']       = $param['param']['mnick']       ? Helper::filterInput($param['param']['mnick']) : 'player'.rand(1, 10000);//昵称
	    
	    $data['mnick']       = $param['param']['nic_name']?$param['param']['nic_name']:'player'.rand(1, 10000);//昵称
	    
	    $data['sex']         = Helper::uint($param['param']['sex']) ;//性别
	    $data['sitemid']     = $param['param']['sitemid']     ? $param['param']['sitemid'] : 0;//第三方平台唯一ID
	    $data['siteurl']     = $param['param']['siteurl']     ? $param['param']['siteurl'] : "";//第三方平台用户头像URL
	    $data['vendor']      = $param['param']['vendor']      ? $param['param']['vendor'] : '';//厂商ID
	    $data['advertising'] = $param['param']['advertising'] ? $param['param']['advertising'] : '';//广告ID
	    $data['mac_address'] = $param['param']['mac_address'] ? $param['param']['mac_address'] : '';//mac id
	    $data['openudid']    = $param['param']['openudid']    ? $param['param']['openudid'] : '';//openudid
	    $data['gameid']      = $param['gameid']               ? Helper::uint($param['gameid']) : 1;//游戏ID
	    $data['openid'] = $param['param']['openid']?$param['param']['openid']:"";
	    $data['nic_name'] = $param['param']['nic_name']?$param['param']['nic_name']:"";
	    
	    //$data['siteurl'] = $param['param']['nic_name']?$param['param']['siteurl']:"";
	    
	    $username = $data['nic_name'];
	    
	    
	    //file_put_contents("rrrrrrrrrrrrr11111222.txt", var_export($data,true),FILE_APPEND);
	    
	    $device_no = Member::factory()->getDeviceNo($param);
	    
	    $aUser['reg_pid']     = $aUser['pid'];//注册时的pid
	    $aUser['versions']    = $data['versions'];
	    $aUser['cid']         = $data['cid'];
	    $aUser['pid']         = $data['pid'];
	    $aUser['ctype']       = $data['ctype'];
	    $aUser['devicename']  = $data['device_name'];
	    $aUser['osversion']   = $data['os_version'];//操作系统版本号
	    $aUser['nettype']     = $data['net_type'];//网络设备类型
	    $aUser['device_no']   = $device_no;//机器码
	    
	    $data['ip'] && $aUser['ip'] = $data['ip'];
	    $aUser['gameid']      = $data['gameid'];
	    $aUser['deviceToken'] = $data['deviceToken'];
	    $aUser['mtype']       = Helper::uint($param['param']['mobileOperator']);//1 移动卡  2 联通卡  3 电信卡
	    $aUser['result']      = 1;
	    
	    //$aUser['mnick']       = Helper::filterInput($aUser['mnick']);
	    $aUser['mnick']       = Helper::filterInput($data['mnick']);
	    
	    $aUser['siteurl'] = $data['siteurl'];
	     
	    //file_put_contents("rrrrrrrrrrrrr11111251.txt", var_export($accountInfo,true),FILE_APPEND);
	    
	    // 用户已存在
	    if($accountInfo)
	    {
	        
	        $device_no = Member::factory()->getDeviceNo($param);
	        
	        $aUser['reg_pid']     = $aUser['pid'];//注册时的pid
	        $aUser['versions']    = $data['versions'];
	        $aUser['cid']         = $data['cid'];
	        $aUser['pid']         = $data['pid'];
	        $aUser['ctype']       = $data['ctype'];
	        $aUser['devicename']  = $data['device_name'];
	        $aUser['osversion']   = $data['os_version'];//操作系统版本号
	        $aUser['nettype']     = $data['net_type'];//网络设备类型
	        $aUser['device_no']   = $device_no;//机器码
	        $data['ip'] && $aUser['ip'] = $data['ip'];
	        $aUser['gameid']      = $data['gameid'];
	        $aUser['deviceToken'] = $data['deviceToken'];
	        $aUser['mtype']       = Helper::uint($param['param']['mobileOperator']);//1 移动卡  2 联通卡  3 电信卡
	        $aUser['result']      = 1;
	        $aUser['mnick']       = Helper::filterInput($aUser['mnick']);
	        
	        
	        $mid = Member::factory()->sitemid2mid($accountInfo["sitemid"], $data['sid']);
	        $aUser["mid"] = $mid;
	        
	        $this->doUserInfo( $aUser );
	        
	        $aUser['username'] = $username;
	        
	        // 微信头像
	        $aUser['big']   = $aUser['middle'] = $aUser['icon'] = $data['siteurl'];//第三方平台用户头像URL
	        
	        $aUser["status"] = 1;
	        $aUser["msg"] = "登录成功";
	        
	        
	        $gameInfo = Loader_Tcp::callServer($mid)->get($mid);
	     
	        $aUser["money"] = $gameInfo["money"];
	        $aUser['sid']         = Helper::uint($param['sid']);
	        $aUser['openid']         = $openid;
	        
	        $tableId = Loader_Redis::game()->hGet("flower_friend_room:intable", $mid);
	        if ($tableId) {
	            $aUser["friendTid"] = $tableId;
	        } else {
	            $aUser["friendTid"] = 0;
	        }
	        
	        return $aUser;
	    }
	    // 用户不存在
	    else
	    {
	        $data["username"] = $this->generate_username();
	        $data["password"] = "123456";
	        
	        $mid = (int)Member::factory()->registerByUserName($data);
	        
	        $userinfo = Member::factory()->getUserInfo($mid);
	        
	        $sitemid = $userinfo["sitemid"];
	        
	        
	        $wxinfo["sitemid"] = $sitemid;
	        $wxinfo["mid"] = $mid;
	        $wxinfo["openid"] = $openid;
	        $wxinfo["nic_name"] = $username;
	        
	        $inserrs = Member::factory()->insertwx($wxinfo);
	        
	        $rs["mid"] = $mid;
	        $rs["sid"] = $data["sid"];
	        $rs["name"] = $username;
	        $rs["sitemid"] = $sitemid;
	        
	        $aUser["mid"] = $mid;
	         
	        $aUser['username'] = $username;
	        
	        $this->doUserInfo( $aUser );
	        
	        $aUser['big']   = $aUser['middle'] = $aUser['icon'] = $data['siteurl'];
	        //$rs = $aUser;
	        
	        $aUser["status"] = 1;
	        $aUser["msg"] = "注册用户并登录成功";
	        
	        
	        $aUser["money"] = Config_Money::$firstin["7"];
	        $aUser['sid']         = Helper::uint($param['sid']);
	        $aUser['openid']         = $openid;
	        
	        //$userInfoKey = Config_Keys::getUserInfo($mid);
	        //$server_mtkey = Loader_Redis::minfo($mid)->hGet($userInfoKey, 'mtkey');

	        return $aUser;
	    }
	    
	}
	
	function generate_username( $length = 6 ) {
	    // 密码字符集，可任意添加你需要的字符
	    $chars = 'abcdefghijklmnopqrstuvwxyz';
	    $username = '';
	    for ( $i = 0; $i < $length; $i++ )
	    {
	        // 这里提供两种字符获取方式
	        // 第一种是使用substr 截取$chars中的任意一位字符；
	        // 第二种是取字符数组$chars 的任意元素
	        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	        $username .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	    }
	    return $username;
	}
	
	
	
	
}
