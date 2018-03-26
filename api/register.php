<?php !defined('IN WEB') AND exit('Access Denied!');
class Register{	
	public function userName($param){
		$data['sid']         = Helper::uint($param['sid']);//账号类型ID	
		$data['cid']         = Helper::uint($param['cid']);//渠道ID
		$data['ctype']       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$data['pid']         = Helper::uint($param['pid']);//客户端包类型ID
		$data['versions']    = $param['versions'];						
		$data['ip']          = Helper::getip();

		$data['device_name'] = $param['param']['device_name'];//设备名称
		$data['os_version']  = $param['param']['os_version'];//操作系统版本号
		$data['net_type']    = $param['param']['net_type'] ? strtolower($param['param']['net_type']) : '';//网络设备类型
		
		$data['username']    = $param['param']['username'] ? Helper::filterInput(strtolower($param['param']['username'])) : '';
		$data['password']    = trim($param['param']['password']);
		$data['mnick']       = $param['param']['mnick'] ? Helper::filterInput($param['param']['mnick']) : 'player'.rand(1, 10000);
		$data['gameid']      = $param['gameid'] ? Helper::uint($param['gameid']) : 1;
		$openudid            = $param['param']['openudid'] ? $param['param']['openudid'] : '';
		$captcha             = $param['param']['captcha']  ? $param['param']['captcha'] : '';
		$ret['result']       = 0; 
		
		if(in_array($data['pid'],array(1558,1505,1552))){//品宣百度手机助手 暂时不能注册
			error_log("data pid forbiden");
			return $ret;
		}
		
		$ip = $data['ip'];
		$ip_arr = Lib_Ip::find($ip);
		if($ip_arr[0] == '美国'){
			$is_USA_ip = 1;
			$data['mnick'] = 'player'.rand(1, 10000);
		}
		
		// $status = M_Anticheating::factory()->registerLimit($ip, $data['cid'],$data['ctype'],$data['gameid'],$data['device_name'],$data['os_version'],$data['pid'],$data['versions']);
		// if(!$status){
		// 	return array('result'=>-3);
		// }
		
		$con_game1 = $data['versions'] >='3.5.3' && $data['gameid'] == 1;//添加图像验证码  梭哈：3.5.3以上
		$con_game7 = $data['gameid'] == 7 ? true : false;	//炸金花
		$con_game5 = $data['gameid'] == 5 ? true : false;	//捕鱼
		$con_game3 = $data['versions'] >='3.3.0' && $data['gameid'] == 3;//斗地主
		$con_game4 = $data['versions'] >='3.1.4' && $data['gameid'] == 4;//斗牛
		$con_game6 = $data['versions'] >='2.0.2' && $data['gameid'] == 6;//德州
		
		if($con_game1 || $con_game7 || $con_game4 || $con_game5 || $con_game6 || $con_game3){
			$device_no   = Member::factory()->getDeviceNo($param);
			$svr_captcha = Loader_Redis::common()->get(Config_Keys::captchas($device_no),false,false);
			$captcha     = strtolower($captcha);
			if($captcha != $svr_captcha){
				Logs::factory()->debug(array('param'=>$param,'svr'=>$svr_captcha,'device_no'=>$device_no),'captchas_errer');
				error_log("Disable valid code check");
				//return array('result'=>-10);
			}
		}
						
	    if(!$data['username'] || !$data['password']){//用户名或密码为空
			return array('result'=>-1);
		}
		
		if(!Helper::isUsername($data['username'])){//用户名不合法
			return array('result'=>-2);
		}
		
		$username = Loader_Tcp::callServer2CheckName()->checkUserName($data['username']);//调C++词库看用户名是否合法
		if(strrpos($username, '*')){
			return array('result'=>-2);
		}

		$openudid && $openudid_count = Loader_Redis::common()->hGet(Config_Keys::guestreglimit($data['gameid']), $openudid);//再利用openudid防刷(IOS)
		$limitKey = $data['device_no'] = Member::factory()->getDeviceNo($param);
		
		//被封的IP或设备号不能注册
		$flag       = strpos($data['device_name'],'Lan7');
		$deviceFlag = Loader_Redis::account()->get(Config_Keys::banAccount($limitKey),false,false);
		$ipFlag     = Loader_Redis::account()->get(Config_Keys::banAccount($ip),false,false);
	
		if($flag !== false || $deviceFlag || $ipFlag ){
			Logs::factory()->debug(array('flag'=>$flag,'deviceFlag'=>$deviceFlag,'ipFlag'=>$ipFlag,'param'=>$param),'ban_deny_userName');
			return array('result'=>-3);
		}
		
		if(in_array($limitKey, Config_Game::$register_blacklist)){//判断机器黑名单
			return array('result'=>-3);
		}
		
		if($data['versions'] <= '1.2.4' && $data['gameid'] == 1){
			Logs::factory()->debug(array('param'=>$param,'ip'=>$ip),'userName_register_deny');
			return array('result'=>-4);
		}
		
		$max = count(Config_Game::$game);
		if($limitKey){
			$count = Logs::factory()->limitCount($limitKey, 'reg', 1,true, 40*24*3600);

			if($is_USA_ip != 1){//针对非美国IP
				if($count > $max+1 || $openudid_count > 2){//单个设备限制
					$param['ip'] = Helper::getip();
					$return = json_encode($param);
					Lib_Apistat::report('register', 'limitCount', 0, 300, "result:300|".$return);
					//return array('result'=>-4);
				}
				
				$count = Logs::factory()->limitCount($ip, 'ip_reg', 1,true, 24*3600);
				if($count > $max*2){
					Logs::factory()->debug($ip.'|'.$data['username'].'|'.$data['device_name'].'|'.$data['gameid'].'|'.$data['cid'],'ip_register_deny');
					//return array('result'=>-6);
				}
			}
		}
				
		$param['ip'] = $ip;
		$return = json_encode($param);
		Lib_Apistat::report('register', 'success', 0, 200, "result:200|".$return);

		$username_flag = Loader_Redis::account()->sAdd(Config_Keys::userName(), $data['username'],false,false);	
		if($username_flag == false){
			return array('result'=>-5);//用户名重复
		}
		
		if(Helper::isMobile($data['username'])){
			$phone_flag    = Loader_Redis::account()->sAdd(Config_Keys::phoneNo(), $data['username'],false,false);
			if($phone_flag == false){
				return array('result'=>-5);//用户名重复
			}
		}				
		
		$result= (int)Member::factory()->registerByUserName($data);
		$ret['result'] = $result ? 1 : 0;
		$ret['result'] && $openudid && Loader_Redis::common()->hIncrBy(Config_Keys::guestreglimit($data['gameid']), $openudid,1);

		return $ret;		
	}
	
	public function checkUserName($param){		
		$ret['result']    = 1;
		$username = $param['param']['username'] ? Helper::filterInput(strtolower($param['param']['username'])) : '';

		if((!Helper::isUsername($username)) || (Helper::isMobile($username))){//用户名不合法
			
			return array('result'=>-1);
		}
		
		$_username = Loader_Tcp::callServer2CheckName()->checkUserName($username);//调C++词库看用户名是否合法
		if(strrpos($_username, '*')){
			return array('result'=>-1);
		}
		
		$flag = Loader_Redis::account()->sContains(Config_Keys::userName(), $username,false,false);	
		if($flag){
			return array('result'=>-2);		
		}
		
		return $ret;
	}
	
	public function checkPhoneNumber($param){		
		$ret['result']    = 1;
		$phoneno = $param['param']['phoneno'] ? Helper::filterInput($param['param']['phoneno']) : '';
		
		if(!Helper::isMobile($phoneno)){//手机号不合法
			return array('result'=>-1);
		}
		
		$flag = Loader_Redis::account()->sContains(Config_Keys::phoneNo(), $phoneno,false,false);	
		if($flag){
			return array('result'=>-2);		
		}
		
		return $ret;
	}
	
	public function phoneNumber($param){
		$data['sid']         = 101;//账号类型ID	
		$data['cid']         = Helper::uint($param['cid']);//渠道ID
		$data['ctype']       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$data['pid']         = Helper::uint($param['pid']);//客户端包类型ID
		$data['mid']         = Helper::uint($param['mid']);//
		$data['versions']    = $param['versions'];						
		$data['ip']          = Helper::getip();

		$data['device_name'] = $param['param']['device_name'];//设备名称
		$data['os_version']  = $param['param']['os_version'];//操作系统版本号
		$data['net_type']    = $param['param']['net_type'] ? strtolower($param['param']['net_type']) : '';//网络设备类型
		
		$data['phoneno']     = $param['param']['phoneno'] ? Helper::filterInput($param['param']['phoneno']) : '';
		$data['password']    = trim($param['param']['password']);
		$data['mnick']       = $param['param']['mnick'] ? Helper::filterInput($param['param']['mnick']) : 'player'.rand(1, 10000);
		$data['type'] 		 = $param['param']['type'];// 1 手机号注册  2 游客绑定
		$data['idcode'] 	 = Helper::uint($param['param']['idcode']);
		$data['gameid']      = $param['gameid'] ? Helper::filterInput($param['gameid']) : '';
		$openudid            = $param['param']['openudid'] ? $param['param']['openudid'] : '';
		$ret['result']       = 0; 
		$ip                  = $data['ip'];
		
		if(!$data['phoneno'] || !$data['password']){//用户名或密码为空
			return array('result'=>-1);
		}
		
		if(!Helper::isMobile($data['phoneno'])){//手机不合法
			return array('result'=>-2);
		}

		$server_idcode = Loader_Redis::common()->get(Config_Keys::codeCheck($data['phoneno']), false, false);		
		/*
		if(!$data['idcode'] || ($server_idcode != $data['idcode'])){
			Logs::factory()->debug(array('param'=>$param,'server_code'=>$server_idcode),'register_phone_idcode_error');
			return array('result'=>-4);
		}
		*/		
		
		$openudid && $openudid_count = Loader_Redis::common()->hGet(Config_Keys::guestreglimit($data['gameid']), $openudid);//再利用openudid防刷(IOS)
		$limitKey = $data['device_no'] = Member::factory()->getDeviceNo($param);
		
		//被封的IP或设备号不能注册
		$flag       = strpos($data['device_name'],'Lan7');
		$deviceFlag = Loader_Redis::account()->get(Config_Keys::banAccount($limitKey),false,false);
		$ipFlag     = Loader_Redis::account()->get(Config_Keys::banAccount($ip),false,false);
	
		if($flag !== false || $deviceFlag || $ipFlag ){
			Logs::factory()->debug(array('flag'=>$flag,'deviceFlag'=>$deviceFlag,'ipFlag'=>$ipFlag,'param'=>$param),'ban_deny_phoneNumber');
			return array('result'=>-3);
		}
		
		$max   = count(Config_Game::$game);
		if($limitKey){
			$count = Logs::factory()->limitCount($limitKey, 'reg', 1,true, 20*24*3600);
			if($count > $max+1 || $openudid_count > 2){//单个设备限制
				$param['ip'] = Helper::getip();
				$return = json_encode($param);
				Lib_Apistat::report('register', 'limitCount', 0, 300, "result:300|".$return);
				Logs::factory()->debug(array('param'=>$param,'server_code'=>$server_idcode),'register_phone_limitkey_error');
				return array('result'=>-6);
			}
		}
		
		$phone_flag    = Loader_Redis::account()->sAdd(Config_Keys::phoneNo(), $data['phoneno'],false,false);	
		$username_flag = Loader_Redis::account()->sAdd(Config_Keys::userName(), $data['phoneno'],false,false);//兼容客户端bug		
		if($username_flag == false || $phone_flag == false){
			return array('result'=>-5);//用户名重复
		}
		
		$count = Logs::factory()->limitCount($ip, 'ip_reg', 1,true, 24*3600);
		if($count > $max*2){
			Logs::factory()->debug($ip.'|'.$data['phoneno'].'|'.$data['device_name'].'|'.$data['gameid'].'|'.$data['cid'],'ip_register_deny_phone');
			return array('result'=>-7);
		}

		$ret['result'] = (int)Member::factory()->registerByPhoneNumber($data,$data['type']);
		
		return $ret;		
	}
	
	public function userNameBinding($param){
		$mid     = Helper::uint($param['mid']);
		$sitemid = $param['param']['sitemid']; 
		$phoneno = $param['param']['phoneno'] ? Helper::filterInput($param['param']['phoneno']) : ''; 
		$client_idcode = Helper::uint($param['param']['idcode']);
		$ret['result'] = 0;
		
		if(!Helper::isMobile($phoneno)){
			return array('result'=>-1);
		}
		
		$server_idcode = Loader_Redis::common()->get(Config_Keys::codeCheck($phoneno), false, false);		
		if($server_idcode != $client_idcode){
			return array('result'=>-2);
		}
		
		$ret['result'] = (int)Member::factory()->updateBindingStatus($sitemid,$mid);
		
		return $ret;
	}
	
	public function getPassword($param){
		$phoneno = $param['param']['phoneno'] ? Helper::filterInput($param['param']['phoneno']) : ''; 
		$mid     = Helper::uint($param['mid']);
		$ret['result'] = 0;
		
		if(!Helper::isMobile($phoneno)){
			return array('result'=>-1);
		}
		
		$count = Logs::factory()->limitCount($phoneno, 101, 1,true,Helper::time2morning());//每天现在获取一次
		if($count>5){
			return array('result'=>-2);
		}
		
		$result = Member::factory()->getPasswordByphoneNo($phoneno);
		
		if(!$result){
			return array('result'=>-3);
		}

		$result = Base::factory()->sendMessage($result, $phoneno, 2,$mid);
		$ret['result'] = 1;
		
		return $ret;
	}
	
	public function resetPassword($param){
		$sid         = $param['sid']; 
		$sitemid     = $param['param']['sitemid']     ? Helper::filterInput($param['param']['sitemid'])   : ''; 
		$oldpassword = $param['param']['oldpassword'] ? Helper::filterInput($param['param']['oldpassword']) : ''; 	
		$newpassword = $param['param']['newpassword'] ? Helper::filterInput($param['param']['newpassword']) : ''; 	
		
		$mid         = $param['mid']; 
		$userInfo    = Member::factory()->getUserInfo($mid);
		$sitemid     = $userInfo['sitemid'];//解决游戏绑定普通账号后不能马上修改密码的情况 
		
		$ret['result']  = Member::factory()->resetPassword($sitemid,$sid, $oldpassword,$newpassword );		
		return $ret;
	}
	
	public function getIdcode($param){

		$ret['result'] = 0; 
		$phoneno       = $param['param']['phoneno'] ? Helper::filterInput($param['param']['phoneno']) : '';
		$sitemid       = $param['param']['sitemid'];
		$type          = Helper::uint($param['param']['type']);
		
		if(!$phoneno){
			return $ret;
		}

		if(!Helper::isMobile($phoneno)){
			return array('result'=>-1);
		}
		
		if(Loader_Redis::common()->get('ID'.$phoneno,false,false) == 1){//限定60S内不能重发
			$ret['result'] =  1;
			return $ret;
		}

		if($type == 2){//手机号码绑定
			if(!$sitemid){
				return $ret;
			}
						
			$status = Member::factory()->userNameBinding($sitemid, $phoneno);
			if(!$status){//已经绑定或已经被注册
				return array('result'=>-3);
				//return $ret;
			}
			
			if(Logs::factory()->limitCount($sitemid, 100, 1,true,Helper::time2morning()) > 5){
				return array('result'=>-2);
			}
		}

		if($type == 1){//获取验证码
			if(Logs::factory()->limitCount($phoneno, 102, 1,true,Helper::time2morning()) > 5){
				return array('result'=>-2);
			}
		}
				
		$idcode = mt_rand(100000, 999999);					
		$flag   = Loader_Redis::common()->set(Config_Keys::codeCheck($phoneno), $idcode, false, false, 10*60);
		
		if($flag){
			$result = Base::factory()->sendMessage($idcode, $phoneno, 1);
			$ret['result'] = 1;
			Loader_Redis::common()->set('ID'.$phoneno, 1,false,false,60);
		}
		
		return $ret;		
	}
	
	public function checkIdcode($param){
		$ret['result'] = 0; 
		$phoneno       = $param['param']['phoneno'] ? Helper::filterInput($param['param']['phoneno']) : '';
		$client_idcode = Helper::uint($param['param']['idcode']);

		$server_idcode = Loader_Redis::common()->get(Config_Keys::codeCheck($phoneno), false, false);		
		if(!$client_idcode || ($server_idcode != $client_idcode)){
			return $ret;
		}
		
		$ret['result'] = 1; 
		return $ret;		
	}
	
	public function issuedAccount($param){
		$sid          = Helper::uint($param['sid']);//账号类型ID	
		$cid          = Helper::uint($param['cid']);//渠道ID
		$ctype        = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid          = Helper::uint($param['pid']);//客户端包类型ID
		$versions     = $param['versions'];         //游戏版本号	
		$mid          = Helper::uint($param['mid']);
		$ip           = Helper::getip();
		$device_no    = $param['param']['device_no'] ? $param['param']['device_no'] : $ip;
		$gameid       = $param['gameid']      ? Helper::uint($param['gameid']) : 1;//游戏ID
		
		$os_version   = $param['param']['os_version'];
		$advertising  = $param['param']['advertising'];
		$mac_address  = $param['param']['mac_address'];
		
		$rtn['result'] = 0;
		$rtn['username'] = $rtn['password'] = '';
		if(!$cid || !$ctype ||!$pid ){
			return $rtn;
		}
		

		// $ip_arr = Lib_Ip::find($ip);
		// if($cid == 1 && $gameid == 4){
		// 	if( in_array($ip_arr[9], array(610900,610100,350500,310000))){
		// 		return $rtn;
		// 	}
		// }
		
		// if( $ip_arr[1] == '福建' && $os_version == '7.1.2' && $param['param']['device_name'] == 'iPhone' ){
		// 	Logs::factory()->debug($ip_arr[1].'|'.$os_version.'|'.$param['param']['device_name'],'issuedAccount');
		// 	return $rtn;
		// }
		
		// if($gameid == 1 && $cid == 3 && in_array($os_version, array('4.0.4','4.0.3','4.0.3'))){
		// 	Logs::factory()->debug($ip_arr[1].'|'.$os_version.'|'.$param['param']['device_name'],'issuedAccount_os');
		// 	return $rtn;	
		// }
		
		// $uuid = $param['param']['uuid'];
		// if( in_array($uuid, array(  '56b6432e-9342-4601-999b-4990679d62ae',
		// 							'920bc009-9b8d-4a74-acc1-720b716ca987',
		// 							'96668aec-4fe5-4ea3-ae25-8f8ae1170d95',
		// 							'45248b1e-3e17-4d83-90e6-f5bcd0ad7fdd',
		// 							'51614ebf-bc8d-4899-9173-1f424af8daf8',
		// 							'f8b02906-700c-42b9-8290-a67b1d2c9d56',
		// 							'f51f61ba-1993-4153-b0f7-e81e7430dd82',
		// 							'170c3687-e2c1-4e21-b216-a20d2aa37abb',
		// 							'5b0aaa61-a299-44b5-b3fb-c332ceb571ff',
		// 							'c78c603b-5ffe-4b99-b76c-1b93b1f91cd9',
		// ))){
		// 	Logs::factory()->debug($ip_arr[1].'|'.$os_version.'|'.$param['param']['device_name'].'|'.$uuid,'issuedAccount_uuid');
		// 	return $rtn;
		// }

		//如果是IOS用户 这里需要知道前端发送的信息
		if($ctype == 2 ||$ctype == 3){
			$device_no = '';
			$os = (int)$osv = substr($os_version , 0, 1);
			$device_no = $os>=7 ?  $advertising : $mac_address; 
			$device_no = str_replace(":","",$device_no);
		}
				
		$device_no && Stat::factory()->statActivate($device_no, $gameid, $mid, $ctype, $cid, $sid, $pid, $ip);//新激活用户（根据device_no去重）
		
		// if( in_array($gameid,array(6)) && $ctype == 2 ){
		// 	Base::factory()->optDomob($device_no, $param);
		// }
		
		// if( in_array($gameid,array(4,7)) && $ctype == 2 ){
		// 	Base::factory()->optGouying($device_no, $param);
		// }
				
		for($i=0;$i<20;$i++){
			$username = Member::factory()->issuedAccount();
			if($username){
				break;
			}
		}
		
		if(!$username){
			return $rtn;
		}
		
		$rtn['result'] = 1;
		$rtn['username'] = $username;
		$rtn['password'] = (string)mt_rand(100000, 999999);
		return $rtn;
	}

	// 绑定微信openid
	public function bindingopenid($param){
		$mid = Helper::uint($param['mid']);
		$openid = $param['param']['openid'];
		$nic_name = $param['param']['nic_name'];
	    
	    if (!$mid || !$openid || !$nic_name) {
	    	return false;
	    }

	    // 判断该微信是否已绑定账号
	    $info = Member::factory()->getSitemidByopenId($openid);
	    if ($info) {
	    	$rtn['result'] = -1;
	    	return $rtn;
	    }

	    // 判断是否已绑定微信
	    $info = Member::factory()->getOneById($mid);
	    if ($info['openid']) {
	    	$rtn['result'] = -2;
	    	return $rtn;
	    }
	    $sitemid = $info['sitemid'];
	    $wxinfo = array('mid'=>$mid, 'openid'=>$openid, 'sitemid'=>$sitemid, 'nic_name'=>$nic_name);
	    $flag = Member::factory()->bindingopenid($wxinfo);

	    // 刷新缓存
	    Member::factory()->getOneById($mid, false);

	    $rtn['result'] = $flag ? 1 : 0;
	    return $rtn;
	}
}
