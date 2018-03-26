<?php !defined('IN WEB') AND exit('Access Denied!');

class Sites_Model
{
    protected static $_instance;

	/**
	 * 创建一个实例
	 * @return Site_Model
	 */
    public static function factory()
	{
	    if(!is_object(self::$_instance))
		{
		    self::$_instance = new Sites_Model();
		}
		
		return self::$_instance;
	}
	
	public function account($param){
		$data['sid']         = 102;//账号类型ID	
		$data['ctype']       = 4;
		$data['cid']         = 69;
		$data['pid']         = 1255;
		$data['versions']    = 1;				
		$data['ip']          = Helper::getip();
		$data['username']    = $param['username']    ? Helper::filterInput(strtolower($param['username'])) : '';//用户名
		$data['password']    = $param['password']    ? Helper::filterInput($param['password']) : '';//密码
		$data['gameid']      = $param['gameid']      ? Helper::uint($param['gameid']) : 3;//游戏ID
		
		if(!$data['username'] || !$data['password'] ){
			return -1;
		}
		
		$accountInfo = Member::factory()->getSitemidByuserName($data['username'], $data['password']);
		$username    = $accountInfo['username'];

		if(!$accountInfo){
			return -2;//用户名或密码错误
		}
		$data['sitemid'] = $sitemid  = $accountInfo['sitemid'];
		$aUser = Member::factory()->getOneBySitemid($sitemid, $data['sid'],false);
		
		if( !$aUser ){//没有该用户
			return -3;
		}
		
		$aUser['versions']  = $data['versions'];
		$aUser['cid']       = $data['cid'];
		$aUser['pid']       = $data['pid'];
		$aUser['device_no'] = $data['ip'];
		$aUser['ctype']     = $data['ctype'];
		$aUser['gameid']    = $data['gameid'];
		$data['ip'] && $aUser['ip'] = $data['ip'];
				
		$this->doUserInfo( $aUser );
		$aUser['username'] = $username; 
		return $aUser;		
	}
	
	public function checkAccount($param){
		$data['sid']         = 102;//账号类型ID	
		$data['username']    = $param['username']    ? Helper::filterInput(strtolower($param['username'])) : '';//用户名
		$data['password']    = $param['password']    ? Helper::filterInput($param['password']) : '';//密码
		$data['gameid']      = $param['gameid']      ? Helper::uint($param['gameid']) : 3;//游戏ID
		
		if(!$data['username'] || !$data['password'] ){
			return -1;
		}
		
		$accountInfo = Member::factory()->getSitemidByuserName($data['username'], $data['password']);

		if(!$accountInfo){
			return -2;//用户名或密码错误
		}
		$data['sitemid'] = $sitemid  = $accountInfo['sitemid'];
		$aUser = Member::factory()->getOneBySitemid($sitemid, $data['sid'],false);
		
		if( !$aUser ){//没有该用户
			return -3;
		}
				
		return 1;
	}
	
	/**
	 * ajax检查验证码
	 */
	public function checkIdcode($param){
		$data['idcode'] = $param['idcode'] ? Helper::filterInput($param['idcode']) : '';
		
		if(strtolower($_SESSION['idcode']) != strtolower($data['idcode']) ){
			return 0;
		}
		
		return 1;
	}
	
	private function doUserInfo( &$aUser ){
		
	$aUser['mstatus'] = (int)Loader_Redis::account()->get(Config_Keys::banAccount($aUser['mid']),false,false);//查看账号状态
		
		if(!$aUser['mstatus']){
			$aUser['mstatus'] = (int)Loader_Redis::account()->get(Config_Keys::banAccount($aUser['device_no']),false,false);//查看机器码状态
		}
		
		if($aUser['mstatus']){
			$aUser['mstatus'] = ceil(($aUser['mstatus'] - NOW)/86400);
		}
		$aUser['mtkey'] = Member::factory()->setMtkey($aUser['mid'],$aUser['ip']);
		
		$this->getSingleByGameid($aUser);
		
		$aUser['todayFirst']  = (int)$istodayfirst = ( date( 'Ymd', $aUser['mactivetime']) != date( 'Ymd', time()) ); // 今天第一次登录
		
		$accountInfo          = Loader_Redis::account()->hGetAll(Config_Keys::other($aUser['mid']));//获取账号的其它信息
		$aUser['binding']     = (int)$accountInfo['binding'];//是否已经绑定手机
		$aUser['wintimes']    = (int)$accountInfo['wi:'.$aUser['gameid']];//赢的次数
		$aUser['losetimes']   = (int)$accountInfo['ls:'.$aUser['gameid']];//输的次数
		$aUser['drawtimes']   = (int)$accountInfo['da:'.$aUser['gameid']];//和的次数
		$aUser['firstpay']    = (int)$accountInfo['firstpay'];//是否首次充值
		$aUser['helpnum']     = (int)$accountInfo['helpnum'];//提示次数 斗牛专用
		$aUser['feedback']    = (int)$accountInfo['feedback'];//反馈回复标志
		
		$continuousLoginDay       = $accountInfo['continuousLoginDay'];
		$aUser['loginRewardFlag'] = Logs::factory()->limitCount($aUser['mid'], 7, 0,false) ? 0 : 1;
		$mactivetime        = $aUser['loginRewardFlag'] == 1 ? $aUser['mactivetime']   : $accountInfo['lastLoginTime'];
		$continuousLoginDay = $aUser['loginRewardFlag'] == 1 ? $continuousLoginDay + 1 : $continuousLoginDay;
		$continuousLoginDay       = date("Ymd",(int)$mactivetime) == date("Ymd",strtotime("-1 days")) ? $continuousLoginDay : 1;
		$aUser['todayReward']     = Config_Money::$loginReward[min($continuousLoginDay,7)];
		$aUser['tomorrowReward']  = Config_Money::$loginReward[min($continuousLoginDay+1,7)];
			
		if($istodayfirst){//每天第一次进入游戏
			$this->doTodayFirst($aUser);
		}

		$aUser['vip']         = (int)Loader_Redis::account()->get(Config_Keys::vip($aUser['mid']),false,false);//会员标识
		$vipexptime           = (int)Loader_Redis::account()->ttl(Config_Keys::vip($aUser['mid']));
		$aUser['vipexptime']  = $aUser['vip'] && $vipexptime ? ceil($vipexptime/86400) : 0;
		$aUser['bank']        = $accountInfo['bankPWD'] ? 1 : 0;//是否开启保险箱
		$aUser['horn']        = (int)$accountInfo['horn'];//小喇叭次数
		$aUser['vipconfig']   = Config_Money::$vipConfig[1];//vip配置
		
		$status = Loader_Redis::server()->hGet(Config_Keys::verifyhash(), $aUser['mid']);
		$aUser['antiaddiction'] = $status ? 1 : 0;//防沉迷状态  1 已经通过身份验证  0 未通过验证或验证失败
		
		$oclass               = 'Config_Game'.$aUser['gameid'];
		$aUser['level']       = Config_Game::getLevel($aUser['exp']);//等级
		$aUser['gameid'] == 3 && $aUser['designation'] = call_user_func_array(array($oclass,  "getDesignation" ),array($aUser['money']));//称号
	
		$aUser['bankruptcy']  = 1000;//破产条件 

		$aUser['big']         = Member::factory()->getIcon($aUser['sitemid'], $aUser['mid'],'big',$aUser['gameid']);
		$aUser['middle']      = Member::factory()->getIcon($aUser['sitemid'], $aUser['mid'],'middle',$aUser['gameid']);
		$aUser['icon']        = Member::factory()->getIcon($aUser['sitemid'], $aUser['mid'],'icon',$aUser['gameid']);
						
	}
	
	private function doTodayFirst(&$aUser){
		Stat::factory()->userAction($aUser);//统计
		
		Loader_Redis::account()->hSet(Config_Keys::other($aUser['mid']), 'lastLoginTime', $aUser['mactivetime']);//每日登陆奖励
			
		Member::factory()->addLogin($aUser); //每天登陆，更新一些登录的信息;
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
		
		$aUnsetFiled = array('aMactivetime','aMentercount','aMtime','aPid','aVersions','devicename','osversion','nettype','vendor','ip','gameid','cid','pid','ctype','mstatus','versions','mtime');
		
		foreach ($aUnsetFiled as $filed) {
			if($aUser[$filed]){
				unset($aUser[$filed]);
			}
		}
	}
	
	/**
	 * 普通账号注册
	 */
	public function userName($param){
		$data['sid']         = 102;//账号类型ID	
		$data['ctype']       = 4;
		$data['cid']         = 69;
		$data['pid']         = 1255;					
		$data['ip']          = Helper::getip();

		$data['username']    = $param['username']  ? Helper::filterInput(strtolower($param['username'])) : '';
		$data['password']    = trim($param['password']);
		$data['mnick']       = $data['username'];
		$data['idcode']      = $param['idcode']    ? Helper::filterInput($param['idcode']) : '';
		$data['secretkey']   = $param['secretkey'] ? Helper::filterInput($param['secretkey']) : '';
		$data['gameid']      = $param['gameid']    ? Helper::uint($param['gameid']) : 3;//游戏ID
		$data['sex']         = mt_rand(1, 2);
		$ret['result']       = 0; 
		
	    if(!$data['username'] || !$data['password']){//用户名或密码为空
			return -1;
		}
		
		if(!Helper::isUsername($data['username'])){//用户名不合法
			return -2;
		}

		$username = Loader_Tcp::callServer2CheckName()->checkUserName($data['username']);//调C++词库看用户名是否合法
		if(strrpos($username, '*')){
			return -2;
		}
		
		if(!$data['secretkey']){
			return -3;
		}
		
		if(strtolower($_SESSION['idcode']) != strtolower($data['idcode']) ){
			return -4;
		}
		
		$limitKey =  Member::factory()->getDeviceNo($data);

		if($limitKey){
			$count = Logs::factory()->limitCount($limitKey, 'reg', 1,true, Helper::time2morning());
			if($count > 10){//单个设备每天只注册限制
				return -6;
			}
		}

		$flag = Loader_Redis::account()->sAdd(Config_Keys::userName(), $data['username'],false,false);		
		if($flag == false){
			return -5;//用户名重复
		}

		$mid = Member::factory()->registerByUserName($data);	
		
		if($mid){
			Loader_Redis::account()->hSet(Config_Keys::other($mid), 'secretkey', $data['secretkey']);
		}
		
		return $flag;
	}
	
	/**
	 * 检测用户名是否合法
	 */
	public function checkUserName($param){		
		$username = $param['username'] ? Helper::filterInput($param['username']) : '';

		if((!Helper::isUsername($username)) || (Helper::isMobile($username))){//用户名不合法
			//return -1;
		}
		
		$_username = Loader_Tcp::callServer2CheckName()->checkUserName($username);//调C++词库看用户名是否合法
		if(strrpos($_username, '*')){
			return -1;
		}
		
		$flag = Loader_Redis::account()->sContains(Config_Keys::userName(), $username,false,false);	
		if($flag){
			return -2;		
		}
		
		return 1;
	}
	
	/**
	 * 检测手机号码是否合法
	 */
	public function checkPhoneNumber($param){		
		$phoneno = $param['phoneno'] ? Helper::filterInput($param['phoneno']) : '';
		
		if(!Helper::isMobile($phoneno)){//手机号不合法
			return -1;
		}
		
		$flag = Loader_Redis::account()->sContains(Config_Keys::phoneNo(), $phoneno,false,false);	
		if($flag){
			return -2;		
		}
		
		return 1;
	}
	
	/**
	 * 手机号码注册
	 */
	public function phoneNumber($param){
		$data['sid']         = Helper::uint($param['sid']);//账号类型ID	
		$data['ctype']       =  $data['cid']  = 4;
		$data['pid']         = $data['versions'] = 0;						
		$data['ip']          = Helper::getip();
		
		$data['phoneno']     = $param['phoneno'] ? Helper::filterInput($param['phoneno']) : '';
		$data['password']    = trim($param['password']);
		$data['mnick']       = $param['mnick'] ? Helper::filterInput($param['mnick']) : '';
		$data['type'] 		 = $param['type'];// 1 手机号注册  2 游客绑定
		$data['idcode'] 	 = Helper::uint($param['idcode']);
		$data['gameid']      = $param['gameid']      ? Helper::uint($param['gameid']) : 3;//游戏ID

		
		if(!$data['phoneno'] || !$data['password']){//用户名或密码为空
			return -1;
		}
		
		if(!Helper::isMobile($data['phoneno'])){//手机不合法
			return -2;
		}

		$server_idcode = Loader_Redis::common()->get(Config_Keys::codeCheck($data['phoneno']), false, false);		
		if(!$data['idcode'] || ($server_idcode != $data['idcode'])){
			return -4;
		}
		
		$flag = Loader_Redis::account()->sAdd(Config_Keys::phoneNo(), $data['phoneno'],false,false);		
		if($flag == false){
			return -5;//手机号重复
		}
		
		return (int)Member::factory()->registerByPhoneNumber($data,$data['type']);	
	}
	
	/**
	 * 普通账号绑定手机
	 */
	public function userNameBinding($param){
		$mid     = Helper::uint($param['mid']);
		$sitemid = $param['sitemid']; 
		$phoneno = $param['phoneno'] ? Helper::filterInput($param['phoneno']) : ''; 
		$client_idcode = Helper::uint($param['idcode']);
	
		if(!Helper::isMobile($phoneno)){
			return -1;
		}
		
		$server_idcode = Loader_Redis::common()->get(Config_Keys::codeCheck($phoneno), false, false);		
		if($server_idcode != $client_idcode){
			return -2;
		}
		
		return (int)Member::factory()->updateBindingStatus($sitemid,$mid);
	}
	
	/**
	 * 通过手机号码获取账号和密码
	 */
	public function getPassword($param){
		$type      = Helper::uint($param['type']);  //1 通过密匙找  2 通过手机号码找
		$phoneno   = $param['phoneno'] ? Helper::filterInput($param['phoneno']) : ''; 
		$secretkey = $param['secretkey'] ? Helper::filterInput($param['secretkey']) : ''; 
		$username  = $param['username'] ? Helper::filterInput($param['username']) : ''; 

		if($type == 1){
			if(!$username || !$secretkey){
				return -1;
			}
			
			$ret = Member::factory()->getPasswordBySecretkey($username, $secretkey);
			if(!$ret){
				return -2;
			}
			
			return $ret;
	
		}else{
			if(!$phoneno = Helper::isMobile($phoneno)){
				return -3;
			}
						
			$result = Member::factory()->getPasswordByphoneNo($phoneno);
			if(!$result){
				return -5;
			}
			
			$count = Logs::factory()->limitCount($phoneno, 'getpwd', 1,true,Helper::time2morning());//每天现在获取一次
			if($count>1){
				return -4;
			}
			
			Base::factory()->sendMessage($result, $phoneno, 2,'');
			return 1;
		}
	}
	
	/**
	 * 重置密码 
	 */
	public function resetPassword($param){
		$sid         = $param['sid']; 
		$sitemid     = $param['sitemid']     ? Helper::filterInput($param['sitemid'])   : ''; 
		$oldpassword = $param['oldpassword'] ? Helper::filterInput($param['oldpassword']) : ''; 	
		$newpassword = $param['newpassword'] ? Helper::filterInput($param['newpassword']) : ''; 	
		
		return (int)Member::factory()->resetPassword($sitemid,$sid, $oldpassword,$newpassword );		
	}
	
	/**
	 * 通过手机号下发验证码
	 */
	public function getIdcode($param){
		$phoneno       = $param['phoneno'] ? Helper::filterInput($param['phoneno']) : '';
		$sitemid       = $param['sitemid'];
		$type          = Helper::uint($param['type']);
		
		if(!$phoneno){
			return 0;
		}

		if(!Helper::isMobile($phoneno)){
			return -1;
		}

		if($type == 2){
			if(!$sitemid){
				return $ret;
			}
			
			if(Logs::factory()->limitCount($sitemid, 100, 1,true,Helper::time2morning()) > 5){
				return -2;
			}
			Member::factory()->userNameBinding($sitemid, $phoneno);
		}

		if($type == 1){
			if(Logs::factory()->limitCount($phoneno, 102, 1,true,Helper::time2morning()) > 10){
				return -2;
			}
		}
				
		$idcode = mt_rand(100000, 999999);					
		$flag   = Loader_Redis::common()->set(Config_Keys::codeCheck($phoneno), $idcode, false, false, 10*60);
		
		if($flag){
			Base::factory()->sendMessage($idcode, $phoneno, 1);
		}
		
		return 1;		
	}
	
	/**
	 * 下发账号
	 */
	public function issuedAccount($param){
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$data['gameid']      = $param['gameid']      ? Helper::uint($param['gameid']) : 3;//游戏ID
		$data['ctype']       =  $data['cid']  = 4;
		$data['pid']         = $data['versions'] = 0;	

		$rtn['username'] = $rtn['password'] = '';
		if(!$cid || !$ctype ||!$pid ){
			return $rtn;
		}

		for($i=0;$i<20;$i++){
			$username = Member::factory()->issuedAccount();
			if($username){
				break;
			}
		}

		if(!$username){
			return $rtn;
		}
		
		$rtn['username'] = $username;
		$rtn['password'] = (string)mt_rand(100000, 999999);

		return $rtn;
	}
}