<?php !defined('IN WEB') AND exit('Access Denied!');

class Clientbase{	
	/**
	 * 检查版本更新
	 */
	public function checkVersions($param){
		
		$ctype         = Helper::uint($param['ctype']);
		$cid           = $param['cid'];		
		$versions      = $param['versions'];
		$gameid        = Helper::uint($param['gameid']);		
		$ret['result'] = $ret['updatetype']  = 0;
		$ret['url']    = $ret['description']  = '';

		if(!$ctype || !$cid || !$versions){
			return $ret;
		}

		$versionInfo = Base::factory()->getVersions($gameid,$cid,$ctype,$versions);

		if(!$versionInfo){
				return $ret;
		}
		
		$ret['result']      = 1;
		$ret['url']         = $versionInfo['url'] ? $versionInfo['url'] : "";
		$ret['updatetype']  = (int)$versionInfo['updatetype'];
		$ret['description'] = $versionInfo['description'] ? $versionInfo['description'] : '';
		
		return $ret;		
	}
	
	/**
	 * 获取公告
	 */
	public function getNotice($param){
		$ctype         = Helper::uint($param['ctype']);
		$cid           = Helper::uint($param['cid']);	
		$pid           = Helper::uint($param['pid']);
		$gameid        = Helper::uint($param['gameid']);
		$ret['result'] = 0;
		$ret['msg'] = array();

		$switch_bit = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$pid);
		$flag = $switch_bit >> 13 & 1;
		if($flag){
			return $ret;
		}

		$notices = Base::factory()->getNotice($gameid,$cid,$ctype);

		if(!empty($notices)){
			$ret['result'] = 1;
			foreach ($notices as $k=>$v){								
				$ret['msg'][$k]['title']   = $v['title']   ? $v['title']   : "";
				$ret['msg'][$k]['content'] = $v['content'] ? $v['content'] : "";
				$ret['msg'][$k]['url']     = $v['url']     ? $v['url']     : "";
			}
		}
		
		return $ret;	
	}
	
	/**
	 * 修改个人信息
	 */
	public function updateUser($param){
		$mid = Helper::uint($param['mid']);
	    $sid = Helper::uint($param['sid']);
		isset($param['param']['mnick']) && $info['mnick'] = Helper::filterInput($param['param']['mnick']);
		isset($param['param']['sex']) && $info['sex'] = $param['param']['sex'];
		isset($param['param']['hometown']) && $info['hometown'] = $param['param']['hometown'];   
		$ret['result'] = 0;
		//$ret['rewardFlag'] = 0 ;
		
		

		
		
		if(!$mid || !$info['mnick']){
			return $ret;
		}
		
		if(in_array($mid,Config_Game::$update_blacklist)){
			return $ret;
		}

		
		//$info['mnick'] = Loader_Tcp::callServer2CheckName()->checkUserName($info['mnick']);//调C++词库看用户名是否合法
		
		if(strrpos($info['mnick'], '*') || !$info['mnick']){
			$ret['result'] = -1;
			return $ret;
		}
		
		if(strrpos($info['mnick'], '金') || strrpos($info['mnick'], '币') || strrpos($info['mnick'], '售') || strrpos($info['mnick'], '卖') || strrpos($info['mnick'], 'Q') || strrpos($info['mnick'], 'q') ){
			$ret['result'] = -1;
			return $ret;
		}
		
		$flag = Member::factory()->setUserInfo($mid, $info);
		
		/*
		$otherInfo = Loader_Redis::common()->hGetAll(Config_Keys::other($mid));
		if($otherInfo['iconVersion']){
			$ret['rewardFlag'] = 1;
		}
		*/
	   // Loader_Redis::account()->hSet(Config_Keys::other($mid), 'updateDetail', NOW);//更新个人信息的标志
		
		$ret['result'] =  $flag ? 1 : 0;
		return $ret;
	}
	
	/**
	 * 上传图像
	 */
	public function uploadIcon($param){
	    
		$ret['result'] = 0;
	    $ret['icon'] = $ret['middle'] = $ret['big'] = '';
	    //$ret['rewardFlag'] = 0;
	    $mid = Helper::uint($param['mid']);
		if(in_array($mid,Config_Game::$update_blacklist)){
			return $ret;
		}
		
		if(Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'iconblist') == 1){//黑名单
			return $ret;
		}
		
		$allowpictype = array('jpg','jpeg','gif','png'); //允许上传类型
		$fileext      = strtolower(trim(substr(strrchr($_FILES["icon"]["name"], '.'), 1)));
		
		$score = Lib_imageFilter::filter()->GetScore($_FILES["icon"]["tmp_name"]);//过滤头像
		
		if ($score >= 60){
			return $ret;
		}
		
	    if($param['gameid'] !=1 && !in_array($fileext, $allowpictype)) {
			$ret['result'] = -1;
			return $ret;
		}
		
		//$tmp_name=dirname($file['tmp_name']).'/'.$file['title'].'.'.$fileext;//加上文件后缀
		//rename($file['tmp_name'],$tmp_name);
		
		$fields['file']   = $_FILES["icon"]["tmp_name"];
		$fields['param']  = json_encode($param);
		if(version_compare(phpversion(),'5.5.0') >= 0 && class_exists('CURLFile')){
		    $fields['file'] = new CURLFile($_FILES["icon"]["tmp_name"]);
		}else{
		    $fields['file'] = '@'.$_FILES["icon"]["tmp_name"];//加@符号curl就会把它当成是文件上传处理
		}
		
		//file_put_contents("mylog.txt", var_export($fields,true));
		
		
		
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'iconVersion', NOW);

/* 		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://192.168.1.111/cdn_icon/up.php");
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1); //连接超时
		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
		$data=curl_exec ($ch);
		$info=curl_getinfo($ch);
		curl_close($ch);
		 */
		
		
		
		$iconPath = Config_Inc::$iconPath; //本地文件夹路径
		$iconDomain = Config_Inc::$iconDomain; //域名路径
		
		$subname = $mid % 10000;
		if(!is_dir($iconPath . 'icon/')) mkdir($iconPath . 'icon/',0777);
		if(!is_dir($iconPath . 'icon/' . $subname . '/')) mkdir($iconPath . 'icon/' . $subname  . '/',0777);
		
		//本地上传
		$new_name = $iconPath . 'icon/' . $subname  . '/' . $mid . '.jpg';
		$tmp_name = $_FILES["icon"]["tmp_name"];
		
		if(copy($tmp_name, $new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && move_uploaded_file($tmp_name, $new_name))) {
		} elseif(@rename($tmp_name, $new_name)) {
		} else {
			return $ret;
		}
		
		Helper::makethumb( $iconPath . 'icon/' . $subname  .'/'. $mid . '.jpg', 160, 160, $iconPath.'icon/' .$subname .'/'   . $mid . '_icon.jpg' );
		Helper::makethumb( $iconPath . 'icon/' . $subname  .'/'. $mid . '.jpg', 180, 180, $iconPath.'icon/' .$subname .'/' . $mid . '_middle.jpg' );
		Helper::makethumb( $iconPath . 'icon/' . $subname  .'/'. $mid . '.jpg', 260, 260, $iconPath.'icon/' .$subname .'/' . $mid . '_big.jpg' );
		
		$ret['icon'] = $iconDomain.$subname.'/'.$mid . '_icon.jpg?v='.NOW;
		$ret['middle'] = $iconDomain.$subname.'/'.$mid . '_middle.jpg?v='.NOW;
		$ret['big'] = $iconDomain.$subname.'/'.$mid . '_big.jpg?v='.NOW;
		$ret['result'] = 1;

		$otherInfo = Loader_Redis::account()->hGetAll(Config_Keys::other($mid));
		
		//file_put_contents("mylog.txt", var_export($otherInfo,true));
		
		
		if($otherInfo['updateDetail']){
			$ret['rewardFlag'] = 1;
		}
		
		//file_put_contents("mylog.txt", var_export($ret,true));
		
		//$ret['result'] = 1;	
		return $ret;
	}

	/**
	 * 获取商品列表
	 */
	public function getShopList($param){
		$ctype           = $param['ctype'];
    	$clientVersion   = $param['param']['version'];  
    	$ret['result']   = 0;
    	
    	if(!$ctype){
    		return $ret;
    	}
    	
    	$ret = Pay::factory()->getShopList($ctype, $clientVersion);
    	return $ret;
	}
	
	/**
	 * 
	 * 完善个人信息领奖
	 */
	public function rewardMoney($param){
		$sid  = Helper::uint($param['sid']);
		$bid  = $param['bid'];
		$mid  = Helper::uint($param['mid']);	
		$gameid = Helper::uint($param['gameid']);			
		$ret['result'] = 0;
		
		if(!$sid || !$bid ||!$mid || !$gameid){
			return $ret;
		}
		
		if(Logs::factory()->limitCount($mid, 6, 1) > 1){
			$ret['result'] = -1;
			return $ret; 
		}
		
		$ret['rewardmoney'] = Config_Money::$updateDetail;
		$ret['result'] = (int)Logs::factory()->addWin($gameid,$mid, $sid, 6, 0, Config_Money::$updateDetail, '', $bid, '', true);	
		
		return $ret;
	}
	
	/**
	 * 破产补助
	 */
	public function getBankruptReward($param){
		$sid        = Helper::uint($param['sid']);
		$cid        = Helper::uint($param['cid']);
		$pid        = Helper::uint($param['pid']);	
		$ctype      = Helper::uint($param['ctype']);			
		$mid        = Helper::uint($param['mid']);	
		$gameid     = Helper::uint($param['gameid']);		
		$ret['result'] = 0;
		
		if(!$gameid || !$sid || !$cid || !$pid || !$ctype || !$mid){
			return $ret;
		}
		
		$ret['result']  =  $ret['rewardMoney'] = $ret['money'] = 0;
		
		$gameInfo = Member::factory()->getGameInfo($mid);
		
		if($gameInfo['money'] + $gameInfo['freezemoney'] >= 1000){
			return array('result'=>-1);
		}
		
		$times = Logs::factory()->limitCount($mid, 4, 1,true,Helper::time2morning());		
		if($times > 2){
			$ret['result'] = -2;
			return $ret;
		}
		
		$rand = mt_rand(1, 100);
		if($rand <= 40){
			$rewardMoney   = Config_Money::$bankrupt;
			$ret['other1'] = 3000;
			$ret['other2'] = 4000;
		}elseif($rand <= 70){
			$rewardMoney   = 3000;
			$ret['other1'] = 4000;
			$ret['other2'] = 2000;
		}else{
			$rewardMoney   = 4000;
			$ret['other1'] = 2000;
			$ret['other2'] = 3000;
		}
		
		$ret['result'] = (int)Logs::factory()->addWin($gameid,$mid, 4,$sid, $cid, $pid,$ctype, 0,$rewardMoney);		
		if($ret['result']){
			$ret['rewardMoney'] = $rewardMoney;
			$ret['money']  = $gameInfo['money'] + $rewardMoney;
		}
		
		return $ret;	
	}
	
	public function getPayInfo($param){
		$ret['result'] = $ret['money'] = $ret['ptype'] = $ret['exchangenum'] = $ret['vipexptime'] = $ret['propexptime'] = $ret['viptype'] = 0;
		$pdealno    = $param['param']['pdealno'];
		$mid        =  Helper::uint($param['mid']);
		$gameid     = Helper::uint($param['gameid']);
		$sid        = Helper::uint($param['sid']);
		$cid        = Helper::uint($param['cid']);
		$pid        = Helper::uint($param['pid']);	
		$ctype      = Helper::uint($param['ctype']);
		if(!$mid || ! $pdealno ){
			return $ret;
		}
		
		$payInfo       = Pay::factory()->getPayByDealNo($pdealno);
				
		if(!$payInfo){
			return $ret;
		}
		
		$ret['wmcfg']     = '';
		if($payInfo['ptype'] == 5){//理财产品
    		$ret['wmcfg'] = array($payInfo['pbankno'],2,0,0,0,0,0,0,0);
		}
		
		if($payInfo['source'] == 3){//破产开宝箱支付
			$oclass      = 'Config_Game'.$gameid;
			$serverInfo  = call_user_func_array(array($oclass, "getServerInfo"),array($cid,1,1,$payInfo['pmode']));//server配置
			$bankruptpay = (int)$serverInfo['bankruptpay']['gold'];
			
			$ret['box1'] = $bankruptpay;
			$min         = $bankruptpay - 10000;
			$max         = $bankruptpay + 60000;
			$ret['box2'] = mt_rand($min, $max);
		}

		$gameInfo      = Member::factory()->getGameInfo($mid);
		$ret['horn']   = (int)Loader_Redis::ote($mid)->hget(Config_Keys::other($mid), 'horn');//获取喇叭次数
		$ret['money']  = $gameInfo['money'];
		
		$vipexptime         = (int)Loader_Redis::account()->ttl(Config_Keys::vip($mid));
		$ret['vipexptime']  = Helper::uint($vipexptime) ?  ceil(Helper::uint($vipexptime)/86400) : 0;
		$ret['viptype']     = (int)Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);

		$propexptime        = Loader_Redis::account()->ttl(Config_Keys::props($gameid,$mid));//如果之前购买了道具，则天数累加
		$ret['propexptime'] = Helper::uint($propexptime) ?  ceil(Helper::uint($propexptime)/86400) : 0;

		$ret['ptype']       = (int)$payInfo['ptype'];
		$ret['exchangenum'] = (int)$payInfo['pexchangenum'];
		$ret['viptime']     = (int)$payInfo['viptime'];
		$ret['roll']        = (int)$gameInfo['roll'];
		$ret['roll1']       = (int)$gameInfo['roll1'];
		$ret['source']      = (int)$payInfo['source'];
		$ret['pdealno']     = $pdealno ? $pdealno : '';
		$ret['tips']   = "";
		
		$ptype = $payInfo['ptype'];
		if($ptype == 1){
			$ret['tips'] = "购买成功，得到".$payInfo['pexchangenum']."金币";
		}else if($ptype == 2){
			if($cid==8)//如果是天涯渠道
                                    {
				$ret['tips'] = "恭喜您,获得 8888金币，3天至尊VIP，".$payInfo['pexchangenum']."乐券";
			}
		}else if($ptype == 3){
			$ret['tips'] = "购买VIP成功，开启仓库和私人场，赠送".$payInfo['pexchangenum']."金币";
		}else if($ptype == 4){
			$ret['tips'] = "购买小喇叭成功，获得".$payInfo['pexchangenum']."个小喇叭";
		}
		
		$ctask = (int)Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'ctask');
        $ctask = $ctask  >> 32 << 32 | $ret['money']; //写最新的金币数到金币任务
        Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'ctask', $ctask);

		$ret['result']      = 1;
		return $ret;
	}	
	
	/**
	 * 连续登陆奖励
	 */
	public function loginReward($param){
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$cid         = Helper::uint($param['cid']);//渠道ID
		$ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid         = Helper::uint($param['pid']);//客户端包类型ID
		$versions    = $param['versions'];	
		$mid         = Helper::uint($param['mid']);
		$gameid      = Helper::uint($param['gameid']);
		
		$ret['result'] = $ret['rewardMoney'] = $ret['continuousLoginDay'] = $ret['money'] = 0;
		
		$lst = 'lst'.$gameid;
		$loginInfo 			= Loader_Redis::ote($mid)->hMget(Config_Keys::other($mid), array('lastLoginTime','continuousLoginDay',$lst));
		$continuousLoginDay = $loginInfo['continuousLoginDay'];
		$lastLoginTime      = $loginInfo[$lst] ? $loginInfo[$lst] : $loginInfo['lastLoginTime'] ;
		$lastLoginTime      = date("Ymd",$lastLoginTime);
		$yesterday          = date("Ymd",strtotime("-1 days"));
		
		$ret['continuousLoginDay'] = $continuousLoginDay = $lastLoginTime == $yesterday ? $continuousLoginDay + 1 : 1;
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'continuousLoginDay', $continuousLoginDay);
		$continuousLoginDay = min($continuousLoginDay,7);
		
		$ret['rewardMoney'] = $rewardMoney = (int)Config_Money::$loginReward[$continuousLoginDay];
		$wmode = array(1=>7,2=>8,3=>9,4=>10,5=>11,6=>12,7=>13);
		
		if(Logs::factory()->limitCount($mid, 7, 1, true,Helper::time2morning()) > 1){
			$ret['result'] = -1;
			return $ret;
		}
		
		$ret['result'] = (int)Logs::factory()->addWin($gameid,$mid, $wmode[$continuousLoginDay],$sid, $cid, $pid,$ctype, 0,$rewardMoney);
		$gameinfo = Member::factory()->getGameInfo($mid);
		$ret['money'] = $gameinfo['money'];
		
		Loader_Redis::common()->set(Config_Keys::loginReward($mid),$gameid,false,false,Helper::time2morning());//连续登陆奖领取标志（用以区分低版本的连续登陆奖与大转盘）
		
		$ret['money'] = $gameid == 1 ? ($ret['money']-$ret['rewardMoney']) : $ret['money'];
		
		return $ret;
	}
	
	/**
	 * 获取牌局任务配置
	 */
	public function getTaskConfig($param){
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$cid         = Helper::uint($param['cid']);//渠道ID
		$ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid         = Helper::uint($param['pid']);//客户端包类型ID
		$versions    = $param['versions'];	
		$mid         = Helper::uint($param['mid']);
		
		$ret['result'] = $ret['roomid'] = $ret['task_num'] = $ret['complete_num'] = 0;
		$task = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid),'ntask');//获取账号的其它信息
		
		if($task){
			$binary = sprintf("%028s",decbin($task));

			$status = substr($binary,0,4);
			$room1_status = substr($status,3,1);
			$room2_status = substr($status,2,1);
			$room3_status = substr($status,1,1);
	
			$room_complete_num[3] = bindec(substr($binary,4, 8));
			$room_complete_num[2] = bindec(substr($binary,12,8));
			$room_complete_num[1] = bindec(substr($binary,20,8));
			
			$roomid = $room3_status == 1 ? 0 : ($room2_status == 1 ? 3 : ($room1_status == 1 ? 2 : 1) );
			
			$gameInfo = Member::factory()->getGameInfo($mid);
			$money    = $gameInfo['money'];
			if($money>=100000){//如果金币数能进入高级场，则直接进入
				$roomid = 3;
			}
			
			$task_num     = (int)Loader_Redis::server()->hGet(Config_Keys::taskconfig(), "playcount".$roomid);
			$complete_num = (int)$room_complete_num[$roomid];
		}else{
			$roomid    = 1;
			$task_num  = (int)Loader_Redis::server()->hGet(Config_Keys::taskconfig(), "playcount".$roomid);
		}
		
		$ret['result']       = 1;
		$ret['roomid']       = (int)$roomid ;
		$ret['task_num']     = (int)$task_num;
		$ret['complete_num'] = (int)$complete_num;

		return $ret;
	}
	
	/**
	 *获取用户游戏信息
	 */
	public function getGameInfo($param){
		
		$gameid       = Helper::uint($param['gameid']);
		$mid          = Helper::uint($param['mid']);//账号类型ID	
		
		if(!$mid){
			$userGameInfo['result'] = 0;
			return $userGameInfo;
		}
		
		$userGameInfo = Member::factory()->getGameInfo($mid,false);
		$accountInfo                 = Loader_Redis::ote($mid)->hMget(Config_Keys::other($mid), array('helpnum','continuousLoginDay','bankPWD','binding','horn','wi:'.$gameid,'ls:'.$gameid,'da:'.$gameid));//获取账号的其它信息
		$userGameInfo['wintimes']    = (int)$accountInfo['wi:'.$gameid];//赢的次数
		$userGameInfo['losetimes']   = (int)$accountInfo['ls:'.$gameid];//输的次数
		$userGameInfo['drawtimes']   = (int)$accountInfo['da:'.$gameid];//和的次数
		
		$userGameInfo['vip']         = (int)Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);//会员标识
		$userGameInfo['horn']        = (int)$accountInfo['horn'];
		$vipexptime                  = (int)Loader_Redis::account()->ttl(Config_Keys::vip($mid));
		$userGameInfo['vipexptime']  = $vipexptime ? ceil($vipexptime/86400) : 0;
		
		$userGameInfo['flashsaletime'] = Loader_Redis::common()->ttl(Config_Keys::limitqiang($gameid, $mid));//限时抢购剩余时间
		
		if( in_array($gameid,array(1,4,6,7))){
			$userGameInfo['vip'] = min(1,$userGameInfo['vip']);
		}
		
		if($userGameInfo){
			$userGameInfo['result'] = 1;
		}else{
			$userGameInfo['result'] = 0;
		}

		return $userGameInfo;		
	}
	
	/**
	 * 游戏激活统计
	 */
	public function statActivate($param){
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$cid         = Helper::uint($param['cid']);//渠道ID
		$ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid         = Helper::uint($param['pid']);//客户端包类型ID
		$versions    = $param['versions'];	
		$mid         = Helper::uint($param['mid']);
		$device_no   = $param['param']['device_no'] ? $param['param']['device_no'] : Helper::getip();
		$gameid      = Helper::uint($param['gameid']);
		
		$rtn['result'] = 0;
		if(!$cid || !$ctype ||!$pid || !$device_no){
			return $rtn;
		}
				
		$flag = Loader_Redis::account()->hSet(Config_Keys::androidKey(),$device_no,1);
		if($flag){			
			Loader_Udp::stat()->sendData(62,$mid,$gameid,$ctype,$cid,$sid,$pid,Helper::getip()); //新激化用户（根据device_no去重）
		}
		$rtn['result'] = 1;
		return $rtn;
	}
	
	/**
	 * 通过短信获取保险箱密码
	 */
	public function getBankPassword($param){
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$cid         = Helper::uint($param['cid']);//渠道ID
		$ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid         = Helper::uint($param['pid']);//客户端包类型ID
		$versions    = $param['versions'];	
		$mid         = Helper::uint($param['mid']);
		$ret['result'] =  0;
		
		$isVip       = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);	
		$accountInfo = Loader_Redis::ote($mid)->hMget(Config_Keys::other($mid), array('bankPWD','binding'));
		
		if(!$isVip){
			return array('result'=>-1);
		}
		
		if(!$accountInfo['bankPWD']){
			return array('result'=>-2);
		}
		
		if(!$accountInfo['binding'] && $sid !=101){
			return array('result'=>-3);
		}
		
		$type     = $accountInfo['binding'] ? 1 : 2;
		$userInfo = Member::factory()->getOneById($mid);
		$phoneno  = Member::factory()->getPhoneno($userInfo['sitemid'], $type);
		
		if(!$phoneno){
			return array('result'=>-4);
		}
		
		if(Loader_Redis::common()->get($phoneno,false,false) == 1){
			$ret['result'] =  1;
			return $ret;
		}
		
		$newBankPWD    = mt_rand(1000, 9999);
		$md5BankPWD    = md5($newBankPWD);
		Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'bankPWD', $md5BankPWD);
		
		$msg = "您保险箱的密码已重置为：$newBankPWD";
		$ret['result'] = (int)Base::factory()->sendMessage($msg, $phoneno, 3);
		Loader_Redis::common()->set($phoneno, 1,false,false,86400);
		return $ret;
	}
	
	//防沉迷身份验证
	public function authentication($param){
		$mid   = Helper::uint($param['mid']);
		$id_no = Helper::filterInput($param['param']['id_no']);
		$ret['result'] = 0;
		
		if(!$mid || !$id_no){
			return $ret;
		}
		
		$status = Base::factory()->checkIdNo($id_no);
		if(!$status){
			return array('result'=>-1);
		}
		
		$status = Base::factory()->check18seh($id_no);
		if(!$status){
			return array('result'=>-2);
		}
		
		Loader_Redis::server()->hSet(Config_Keys::verifyhash(), $mid, 1);
		Base::factory()->setAntiaddictionLog($mid, $id_no);
		$ret['result'] = 1;
		return $ret;
	}
	
	/**
	 * 获取排行榜
	 */
	public function getRank($param){
	    
	    
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$cid         = Helper::uint($param['cid']);//渠道ID
		$ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid         = Helper::uint($param['pid']);//客户端包类型ID
		$type        = $param['param']['type'];	
		$mid         = Helper::uint($param['mid']);
		$gameid      = Helper::uint($param['gameid']);
		$ret['result'] =  $ret['rank'] = 0;
		
		
		
		if($gameid == 2){//百家乐兼容
			switch ($type) {
				case 1:
					$type = 3;
					break;
				case 2:
					$type = 1;
					break;
				case 3:
					$type = 2;
					break;		
			}
		}
		
		//file_put_contents("log.txt", var_export($mid,true),FILE_APPEND);
		
		
		//$type 2:金币榜，1:赚钱榜
		//$gameid 7
		//$mid 5225813
		
		$aRank  = Base::factory()->getRank($type,20,$mid,$gameid);	
		
		//file_put_contents("mylog.txt", var_export($aRank,true));
		
		$myRank = Base::factory()->getMyRank($mid, $type,$gameid);//自己的排名
		
		//file_put_contents("log.txt", var_export($myRank,true),FILE_APPEND);
		
		
		array_push($aRank, $myRank);
		
		if(!$aRank){
			return $ret;
		}
		
		$ret['result']    = 1;
		$ret['resetTime'] = "00:00";
		$ret['info']      = $aRank;
		return $ret;
	}
	
	public function setDownloadStatus($param){
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$cid         = Helper::uint($param['cid']);//渠道ID
		$ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid         = Helper::uint($param['pid']);//客户端包类型ID
		$mid         = Helper::uint($param['mid']);
		$gameid      = Helper::uint($param['gameid']);
		$other_gameid= Helper::uint($param['param']['other_gameid']);
		
		$ret['result'] = 0;		
		$deviceNo = Member::factory()->getDeviceNo($param);
		
		if(!$deviceNo || !$other_gameid){
			return $ret;
		}
		
		$status = Base::factory()->setDownOtherGameStatus($deviceNo, $other_gameid);
		
		$ret['result'] = 1;
		
		return $ret;
	}
	
	public function getPromotionStatus($param){
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$cid         = Helper::uint($param['cid']);//渠道ID
		$ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid         = Helper::uint($param['pid']);//客户端包类型ID
		$mid         = Helper::uint($param['mid']);
		$gameid      = Helper::uint($param['gameid']);
		$ret['result'] = 0;	
		$deviceNo = Member::factory()->getDeviceNo($param);
		
		if(!$deviceNo || !$gameid){
			return $ret;
		}
		
		$ret['result'] = 1;
		$ret['games']  = Base::factory()->getPromotionStatus($mid,$deviceNo, $gameid,$ctype);
		
		return $ret;		
	}
	
	public function getPromotionStatus2($param){
	    $sid         = Helper::uint($param['sid']);//账号类型ID
	    $cid         = Helper::uint($param['cid']);//渠道ID
	    $ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad
	    $pid         = Helper::uint($param['pid']);//客户端包类型ID
	    $mid         = Helper::uint($param['mid']);
	    $gameid      = Helper::uint($param['gameid']);
	    $height      = Helper::uint($param['param']['high']);
	    $ret['result'] = 0;
	    $deviceNo = Member::factory()->getDeviceNo($param);
	
	    if(!$deviceNo || !$gameid){
	        return $ret;
	    }
	
	    $ret['result'] = 1;
	    $ret['games']  = Base::factory()->getPromotionStatus2($mid,$deviceNo,$gameid,$ctype,$height);
	
	    return $ret;
	}
	
	public function rewardPromotion($param){
		$sid         = Helper::uint($param['sid']);//账号类型ID	
		$cid         = Helper::uint($param['cid']);//渠道ID
		$ctype       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid         = Helper::uint($param['pid']);//客户端包类型ID
		$mid         = Helper::uint($param['mid']);
		$gameid      = Helper::uint($param['gameid']);
		$other_gameid= Helper::uint($param['param']['other_gameid']);
		$ret['result'] = $ret['rewardMoney'] = $ret['money'] = 0;	
		
		$deviceNo = Member::factory()->getDeviceNo($param);
		
		if(!$deviceNo || !$gameid || !$cid || !$other_gameid || !$mid || !$pid){
			return $ret;
		}

		$rewardCountBymid = Loader_Redis::ote($mid,'slave')->hGet(Config_Keys::other($mid), 'proreward');
		if($rewardCountBymid > (count(Config_Game::$game) -1 )){
			Logs::factory()->debug(array('count'=>$rewardCountBymid,'param'=>$param),'rewardPromotion');
			return $ret;
		}
		
		if($rewardCountBymid == 2){
	    	$accountInfo = Loader_Redis::ote($mid,'slave')->hGetAll(Config_Keys::other($mid));
	    	$playCount   = M_Anticheating::factory()->getPlayCount($accountInfo, 3, $mid);
	    	if($playCount < 2){
	    		M_Anticheating::factory()->batAccount($mid, $gameid, 'system',1000,'拿3次更多游戏奖励但少于2局牌局');
				$flag = 1;
	    	}
	    }
		
		$rewardMoney = mt_rand(1000, 3000);
		
		$status = Base::factory()->rewardBydownOtherGame($deviceNo, $mid, $gameid, $ctype, $sid, $cid, $pid, $other_gameid,$rewardMoney);
		
		if($status == 1){
			$ret['result'] = -1;
			return $ret;
		}
		
		if($status == 3){
			$ret['result'] = -2;
			return $ret;
		}
		
		if($status == 2){
			Loader_Redis::ote($mid)->hIncrBy(Config_Keys::other($mid), 'proreward', 1);
			$gameinfo = Member::factory()->getGameInfo($mid);
			$ret['rewardMoney'] = $rewardMoney;
			$ret['money']       = $gameinfo['money'];
			$ret['result']      = 1;
		}
		
		return $ret;
	}
	
	public function guestBingding($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		$sitemid  = $param['param']['sitemid']; 
		$type     = $ret['type']    = Helper::uint($param['param']['type']);
		$phoneno  = $ret['phoneno'] = $param['param']['phoneno'] ? Helper::isMobile($param['param']['phoneno']) : '';
		$username = $ret['username']= $param['param']['username'] ? Helper::filterInput(strtolower($param['param']['username'])) : '';
		$client_idcode = Helper::uint($param['param']['idcode']);
		$password = $ret['password']= trim($param['param']['password']);
		$openudid = $param['param']['openudid'] ? $param['param']['openudid'] : '';
		$captcha  = $param['param']['captcha']  ? $param['param']['captcha'] : '';
		$versions = $param['versions'] ? $param['versions'] : '';
		
		$ret['result']   = $ret['rewardMoney'] = $ret['money']  = 0;

		if(!$mid || !$sitemid || !in_array($sid, array(100,102))){
			return $ret;
		}
		
		$limitKey  = Member::factory()->getDeviceNo($param);
		
		if(Loader_Redis::common()->hExists(Config_Keys::bingLimit(), $limitKey)){//该设备已经绑定过
			return array('result'=>-1);
		}
		
		if($openudid && Loader_Redis::common()->hExists(Config_Keys::bingLimit(), $openudid)){//再利用OPenudid来防刷
			return array('result'=>-1);
		}

		if(Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'gbd')){
			return array('result'=>-1);
		}
		
		$rewardMoney = Config_Money::$bingReward;
		
		if($type == 1){//游客绑定点乐账号
			
			if(Helper::isMobile($username)){//先判断是否手机账号
				$ret['result'] = -3;
				return $ret;
			}	
			
			$con_game1 = $versions >='3.5.3' && $gameid == 1;//添加图像验证码  梭哈：3.5.3以上
			$con_game7 = $gameid == 7 ? true : false;	//炸金花
			$con_game5 = $gameid == 5 ? true : false;	//捕鱼
			$con_game4 = $versions >='3.1.4' && $gameid == 4;//斗牛
			$con_game6 = $versions >='2.0.2' && $gameid == 6;//德州
			
			if($con_game1 || $con_game7 || $con_game4 || $con_game5 || $con_game6){
				$device_no   = $data['device_no'] = Member::factory()->getDeviceNo($param);
				$svr_captcha = Loader_Redis::common()->get(Config_Keys::captchas($device_no),false,false);
				$captcha     = strtolower($captcha);
				if($captcha != $svr_captcha){
					Logs::factory()->debug(array('param'=>$param,'svr'=>$svr_captcha),'captchas_errer_Bingding');
					return array('result'=>-10);
				}
			}
			
			if(!$username || !$password){//用户名或密码为空
				$ret['result'] = -2;
				return $ret;
			}
			
			if(!Helper::isUsername($username)){//用户名不合法
				$ret['result'] = -3;
				return $ret;
			}
			
			$username = Loader_Tcp::callServer2CheckName()->checkUserName($username);//调C++词库看用户名是否合法
			if(strrpos($username, '*')){
				$ret['result'] = -3;
				return $ret;
			}
			
			$flag = Loader_Redis::account()->sAdd(Config_Keys::userName(), $ret['username'],false,false);		
			if($flag == false){
				$ret['result'] = -4;//用户名重复
				return $ret;
			}
						
			$flag = Member::factory()->guest2dianlerAccount($ret['username'], $ret['password'], $mid);
		}
		
		if($type == 2){//游客绑定手机号码
			
			if(!$phoneno || !$password){//手机或密码为空
				$ret['result'] = -2;
				return $ret;
			}
						
			$server_idcode = Loader_Redis::common()->get(Config_Keys::codeCheck($ret['phoneno']), false, false);		
			if($server_idcode != $client_idcode){
				$ret['result'] = -5;
				return $ret;
			}
			
			$flag = Loader_Redis::account()->sAdd(Config_Keys::phoneNo(), $ret['phoneno'], false, false);		
			if($flag == false){
				$ret['result'] = -4;
				return $ret;
			}
			
			$data['mid']      = $mid;
			$data['phoneno']  = $ret['phoneno'];
			$data['password'] = $ret['password'];
			
			$flag = Member::factory()->registerByPhoneNumber($data,2);
		}
		
		if($flag){
			if($flag){
				$flag = Logs::factory()->addWin($gameid, $mid, 21, $sid, $cid, $pid, $ctype, 0, $rewardMoney);
			}
			
			$gameInfo           = Member::factory()->getGameInfo($mid);
			$ret['rewardMoney'] = $rewardMoney;
			$ret['money']       = $gameInfo['money']; 
			$ret['result']      = 1;
			Loader_Redis::common()->hSet(Config_Keys::bingLimit(), $limitKey, 1);//设置该设备已经成功绑定
			$openudid && Loader_Redis::common()->hSet(Config_Keys::bingLimit(), $openudid, 1);//设置该设备已经成功绑定
			Loader_Redis::ote($mid)->hSet(Config_Keys::other($mid), 'gbd',1);
		}
		
		return $ret;
	}
	
	//领取局数任务奖励
	public function getTaskInnings($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		$roomid   = Helper::uint($param['param']['roomid']);
		
		$ret['result'] = $ret['rewardMoney'] = $ret['rewardRoll'] = $ret['nextStep'] = $ret['box1'] = $ret['box2'] = 0;
		
		if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid){
			return $ret;
		}
		
		if(!in_array($roomid,array(1,2,3,4))){
			return $ret;
		}
		
		$record = Base::factory()->taskInnings($mid, $roomid, $gameid, $sid, $cid, $pid, $ctype);

		if($record === false){
			return $ret;
		}
		$ret['nextStep']    = (int)$record['nextstep'];
		$ret['rewardMoney'] = $record['money'];
		$ret['rewardRoll']  = $record['roll'];
		$ret['box1']        = $record['box1'];
		$ret['box2']        = $record['box2'];
		$ret['result']      = 1;
		
		return $ret;
	}
	
	public function getBonus($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		$prizeid  = Helper::uint($param['param']['prizeid']);
		
		$ret['result'] =  0;
		
		if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid || !$prizeid){
			return $ret;
		}
		
		$ret['result'] = (int)Base::factory()->getBonus($mid, $prizeid, $gameid, $sid, $cid, $pid, $ctype);	
		
		return $ret;
	}
	
	public function getWheelInfo($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		
		$ret['result'] = 0;
		$ret['bonus']  = array();
		
		if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid){
			return $ret;
		}
		
		if(Loader_Redis::common()->get(Config_Keys::loginReward($mid),false,false)){//在其它游戏和低版本领取了登陆奖，则不能抽
			$ret['result'] = 1;
			return $ret;
		}
		
		$continuousLoginDay  = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid),'continuousLoginDay');

		$times = Base::factory()->getWheelTimes($continuousLoginDay);

		$vip = Loader_Redis::account()->get(Config_Keys::vip($mid),false,false);
		$vip && $times ++;

		$json_bonus   = Loader_Redis::ote($mid)->hGet(Config_Keys::other($mid), 'bonus');
		$bonus        = $ret['bonus'] = json_decode($json_bonus,true);
		$drawed_times = count($bonus);
			
		if($drawed_times >= $times){
			$ret['result'] = 1;
			return $ret;
		}
			
		$times = $times - $drawed_times;
		$ret['bonus'] = Base::factory()->drawWheel($mid,$times,$bonus);

		$ret['result'] = 1;
		return $ret;
	}
	
	/**
	 * 游客注册开关
	 */
	public function getGuestRegisterSwitch($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		
		$ret['result'] = $ret['switch'] = 1;
		
		$ip     = Helper::getip();
		$ip_arr = Lib_Ip::find($ip);
		
		/* 
		if($ip_arr[0] == '美国' ){
			$ret['switch'] = 0;
		}
		 */

		$switch_bit = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$pid);
		$flag = $switch_bit >> 11 & 1;
		
		if($flag){
			$ret['switch'] = 0;
		}

		return $ret;
	}
	
	/**
	 * 获取比赛配置
	 */
	public function getMatchConfig($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		
		$ret['result'] = 1;
		
		$ret['mathconfig'] = Loader_Redis::server()->hGetAll("LandLord_RoomConfig:5");
		
		$ret['awardConfig'] = Loader_Redis::server()->hGetAll("LandLord_AwardConfig:5");
		
		return $ret;
	}
	
	public function setMatchPhone($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		$phoneno  = Loader_Mysql::DBMaster()->escape($param['param']['phoneno']);
		$prize    = Loader_Mysql::DBMaster()->escape($param['param']['prize']);
		$rank     = Helper::uint($param['param']['rank']);
		
		$ret['result'] = 0;
		if(!$phoneno || !$prize || !$mid || !$rank ){
			return $ret;
		}
		
		$ret['result'] = (int)Base::factory()->saveMathPrizePhone($mid, $phoneno, $prize,$rank);
		
		return $ret;
	}
	
	//检测修改用户名是否合法
	public function checkMnick($param){		
		$ret['result']    = 1;
		$username = $param['param']['username'] ? Helper::filterInput(strtolower($param['param']['username'])) : '';

		
		$_username = Loader_Tcp::callServer2CheckName()->checkUserName($username);//调C++词库看用户名是否合法
		
//file_put_contents("mylog.txt", var_export($_username,true));
		
		if(strrpos($_username, '*')){
			$ret['result']    = 0;
			return $ret;
		}
		
		if(strrpos($info['mnick'], '金') || strrpos($info['mnick'], '币') || strrpos($info['mnick'], '售') || strrpos($info['mnick'], '卖') || strrpos($info['mnick'], 'Q') || strrpos($info['mnick'], 'q') ){
			$ret['result']    = 0;
			return $ret;
		}

		//file_put_contents("mylog.txt", var_export($ret,true));
		
		return $ret;
	}
	
	//获取 扑克的一些游戏信息
	public function getPokerGameInfo($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		$ret['result'] = 1;
		
		$info = Loader_Redis::ote($mid)->hMget(Config_Keys::other($mid), array('mw:6','mv:6'));
		
		$ret['mw'] = (int)$info['mw:6'];
		$ret['mv'] = (int)$info['mv:6'];
		
		return $ret;
	}
	
	//翻牌抽奖
	public function turnover($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		
		$ret['result']  = $ret['money'] = $ret['roll'] = 0;
		$ret['winning'] = $ret['others'] = array();
		
		if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid ){
			return $ret;
		}
		
		$cards = Base::factory()->getTurnover($mid, $gameid, $sid, $cid, $pid, $ctype);	
		
		if($cards === false){
			return $ret;
		}
		
		if($cards['winning']['coin']){
			$ret['winning']  = array(1,$cards['winning']['coin']);
			$ret['others'][] = array(2,$cards['others']['roll']);
		}else{
			$ret['winning']  = array(2,$cards['winning']['roll']);
		}
		
		shuffle($cards['others']['coin']);
		
		foreach ($cards['others']['coin'] as $k=>$value) {
			$ret['others'][] = array(1,$value);
		}

		$gameInfo      = Member::factory()->getGameInfo($mid);
		$ret['money']  = $gameInfo['money'];
		$ret['roll']   = $gameInfo['roll'];
		$ret['result'] = 1;
		
		return $ret;
	}
	
	//斗牛任务
	public function getTaskReward($param){
		$mid      = Helper::uint($param['mid']);
		$sid      = Helper::uint($param['sid']);
		$cid      = Helper::uint($param['cid']);//渠道ID
		$ctype    = Helper::uint($param['ctype']);//客户端类型
		$pid      = Helper::uint($param['pid']);
		$gameid   = Helper::uint($param['gameid']);
		$type     = Helper::uint($param['param']['type']);
		$roomid   = Helper::uint($param['param']['roomid']);
		
		$ret['rewardMoney'] = $ret['rewardRoll'] = $ret['money'] = $ret['roll'] = $ret['result'] = 0;
		
		if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid || !$type){
			return $ret;
		}
		
		switch ($type) {
			case 1:
				$ret['rewardMoney'] = (int)Base::factory()->getTaskTimeReward($mid, $gameid, $sid, $cid, $pid, $ctype);
				if(!$ret['rewardMoney']){
					return $ret;
				}
		
			break;
			case 2:
				$ret['rewardMoney'] = (int)Base::factory()->getTaskRoundReward($mid, $roomid, $gameid, $sid, $cid, $pid, $ctype);
				if(!$ret['rewardMoney']){
					return $ret;
				}
			break;
			case 3:
				$ret['rewardRoll'] = (int)Base::factory()->getTaskCoinReward($mid, $gameid, $sid, $cid, $pid, $ctype);
				if(!$ret['rewardRoll']){
					return $ret;
				}
			break;
		}
		
		$gameInfo      = Member::factory()->getGameInfo($mid);
		$ret['money']  = $gameInfo['money'];
		$ret['roll']   = $gameInfo['roll'];
		$ret['result'] = 1;
		
		return $ret;
	}
	
	public function getActivityList($param){
	    $path = PRODUCTION_SERVER? 'http://user.dianler.com/' : 'http://utest.dianler.com/ucenter/';
	    
	    $paramString = '';
	    
	    foreach($param as $key=>$value){
	        $paramString = $paramString.'&'.$key.'='.$value;
	    }
	    $userInfo     = Member::factory()->getOneById($param['mid']);
	    
	    $paramString  = $paramString.'&mnick='.urlencode($userInfo['mnick'])."&money=".$userInfo['money'];
	    
	    $showAty = Activity_Manager::getActivityList($param);
	    $activityNum = count($showAty);
	    $i = 0;
	    if ($activityNum>0){
	        foreach ($showAty as $value){
	            $ret['activity'][$i] = array(
	                'activityIcon' => $path.$value['icon'],
	                'activityDesc' => $value['desc'],
	                'activityURL'  => $value['url'].$paramString
	            );
	            $i++;
	        }
	        $ret['haveActivity'] = 1;
	    }else {
	        $ret['activity'][$i] = array(
	            'activityIcon' => '',
	            'activityDesc' => '',
	            'activityURL'  => ''
	        );
	        $ret['haveActivity'] = 0;
	    }
	    $ret['result'] = 1;
	
	    return $ret;
	}
	
	// 获取奖励
	public function getBonus2($param){
	    $mid      = Helper::uint($param['mid']);
	    $sid      = Helper::uint($param['sid']);
	    $cid      = Helper::uint($param['cid']);//渠道ID
	    $ctype    = Helper::uint($param['ctype']);//客户端类型
	    $pid      = Helper::uint($param['pid']);
	    $gameid   = Helper::uint($param['gameid']);
	    $prizeid   = Helper::uint($param['prizeid']);
	     
	    $ret['result'] =  0;
	     
	    if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid){
	        return $ret;
	    }
	
	    $ret = Base::factory()->getBonus2($mid, $gameid, $sid, $cid, $pid, $ctype);
	
	    return $ret;
	}
	
	public static function getFishALLInfo($param){
	    $mid      = Helper::uint($param['mid']);
	    $sid      = Helper::uint($param['sid']);
	    $cid      = Helper::uint($param['cid']);//渠道ID
	    $ctype    = Helper::uint($param['ctype']);//客户端类型
	    $pid      = Helper::uint($param['pid']);
	    $gameid   = Helper::uint($param['gameid']);
	     
	    $ret['result'] =  1;
	    
	    $ret['skillMoney'] = array(50,50,50,50,50,1000);
	    
	    $skillTime = Loader_Redis::game()->hGetAll("FishSkillActiveTime");
	    foreach ($skillTime as $kv){
	        $ret['skillTime'][] = (int)$kv;
	    }
	    
	    $skillWaitTime = Loader_Redis::game()->hGetAll("FishSkillCoolTime");
	    foreach ($skillWaitTime as $wv){
	        $ret['skillWaitTime'][] = (int)$wv;
	    }
	    
// 	    $ret['skillTime'] = array(30,10,7,20,10,5);
	    
// 	    $ret['skillWaitTime'] = array(9,8,9,8,40,30);
	    
	    $ret['fishMul'][0] = 2;
	    $ret['fishMul'][1] = 2;
	    $ret['fishMul'][2] = 3;
	    $ret['fishMul'][3] = 4;
	    $ret['fishMul'][4] = 5;
	    $ret['fishMul'][5] = 6;
	    $ret['fishMul'][6] = 7;
	    $ret['fishMul'][7] = 8;
	    $ret['fishMul'][8] = 9;
	    $ret['fishMul'][9] = 10;
	    $ret['fishMul'][10] = 12;
	    $ret['fishMul'][11] = 15;
	    $ret['fishMul'][12] = 18;
	    $ret['fishMul'][13] = 20;
	    $ret['fishMul'][14] = 30;
	    $ret['fishMul'][15] = 40;
	    $ret['fishMul'][16] = 0;
	    $ret['fishMul'][17] = 0;
	    $ret['fishMul'][18] = 0;
	    $ret['fishMul'][19] = 0;
	    $ret['fishMul'][20] = 0;
	    $ret['fishMul'][21] = 0;
	    $ret['fishMul'][22] = 100;
	    $ret['fishMul'][23] = 200;
	    $ret['fishMul'][24] = 300;
	    $ret['fishMul'][25] = 0;
        
	    for ($i=2; $i<=3; $i++){
	        $time = Loader_Redis::account()->ttl(Config_Keys::gunStyle($mid, "$i"));
	        $style["$i"] = ceil(Helper::uint($time)/86400);
	    }
	    
	    $currentStyle = Loader_Redis::ote($mid)->hGet("OTE|$mid", 'currentStyle');
	    $rate         = Loader_Redis::game()->hGetAll('FishGunAddCoin');
	    
	    $ret['gunStyleConfig'] = array(
	        array(
	            'id'=>2,
	            'name'=>"黄金炮礼包",
	            'validTill'=>$style['2'],
	            'inUsed'=>$currentStyle==2 ? 1 : 0,
	            'rate'=>$rate['type:2']*1
	        ),
	        array(
	            'id'=>3,
	            'name'=>"钻石炮礼包",
	            'validTill'=>$style['3'],
	            'inUsed'=>$currentStyle==3 ? 1 : 0,
	            'rate'=>$rate['type:3']*1
	        ),
	    );
	    
	    $gunLevelConfig    = Loader_Redis::game()->hGetAll('FishUnlockCoin');
	    $mulGunCoin        = Loader_Redis::game()->hGetAll('FishMulGunCoin');
	    
	    foreach ($gunLevelConfig as $k=>$v){
	        $i = str_replace('level:','',$k);
	        if ($i<=5){
	            $index = 1;
	        }
	        if ($i>5 && $i<=10){
	            $index = 2;
	        }
	        if ($i>10 && $i<=16){
	            $index = 3;
	        }
	        
	        $ret['gunLevelConfig'][] = array(
	            'id'=>$i,
	            'need'=>$v,
	            'money'=>$mulGunCoin['level:'.$i],
	            'imgaeIndex'=>$index,
	        );
	    }
	    
	    return $ret;
	}
	
	public function setGunLevel($param){
	    $mid      = Helper::uint($param['mid']);
	    $sid      = Helper::uint($param['sid']);
	    $cid      = Helper::uint($param['cid']);//渠道ID
	    $ctype    = Helper::uint($param['ctype']);//客户端类型
	    $pid      = Helper::uint($param['pid']);
	    $gameid   = Helper::uint($param['gameid']);
	    $level    = Helper::uint($param['param']['level']);
	    
	    $ret['result'] =  0;
	    
	    if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid){
	        return $ret;
	    }
	    
	    $ret = Base::factory()->setGunLevel($gameid, $mid, $sid, $cid, $pid, $ctype, $level);
	    
	    return $ret;
	}
	
	public function gunStyle($param){
	    $mid      = Helper::uint($param['mid']);
	    $sid      = Helper::uint($param['sid']);
	    $cid      = Helper::uint($param['cid']);//渠道ID
	    $ctype    = Helper::uint($param['ctype']);//客户端类型
	    $pid      = Helper::uint($param['pid']);
	    $gameid   = Helper::uint($param['gameid']);
	     
	    $ret['result'] =  0;
	     
	    if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid){
	        return $ret;
	    }
	     
	    $ret = Base::factory()->gunStyle($mid);
	     
	    return $ret;
	}
	
	public function statDownloadStatus($param){
	    $mid      = Helper::uint($param['mid']);
	    $sid      = Helper::uint($param['sid']);
	    $cid      = Helper::uint($param['cid']);//渠道ID
	    $ctype    = Helper::uint($param['ctype']);//客户端类型
	    $pid      = Helper::uint($param['pid']);
	    $gameid   = Helper::uint($param['gameid']);
	    $status   = Helper::uint($param['param']['status']);
	     
	    $ret['result'] =  0;
	     
	    if(!$mid || !$sid || !$cid || !$ctype || !$pid || !$gameid){
	        //return $ret;
	    }
	    
	    $flag = Loader_Redis::common()->get(Config_Keys::drf($mid),false,false);
	    Loader_Udp::stat()->sendData(271,$mid,$gameid,$ctype,$cid,$sid,$pid,Helper::getip()); //下载次数
	    if(!$flag){
	    	Loader_Udp::stat()->sendData(272,$mid,$gameid,$ctype,$cid,$sid,$pid,Helper::getip()); //下载人数
	    	switch ($status) {
	    		case 1:
	    			Loader_Udp::stat()->sendData(268,$mid,$gameid,$ctype,$cid,$sid,$pid,Helper::getip()); //下载成功人数;
	    		break;
	    		
	    		case 2:
	    			Loader_Udp::stat()->sendData(269,$mid,$gameid,$ctype,$cid,$sid,$pid,Helper::getip()); //加载资源失败人数
	    		break;
	    		
	    		case 3:
	    			Loader_Udp::stat()->sendData(270,$mid,$gameid,$ctype,$cid,$sid,$pid,Helper::getip()); //连接网络失败人数
	    		break;
	    	}
	    	Loader_Redis::common()->set(Config_Keys::drf($mid), 1, false,false,7*24*3600);
	    }
	    
	    $str = $gameid.'|'.$cid.'|'.$ctype.'|'.$mid.'|'.$status;
	   	Logs::factory()->debug($str,'statDownloadStatus');//临时写log
		$ret['result'] =  1;
	    return $ret;
	}
	
	public function activityCenter($param){
	    extract($param);
	    
	    $device_no = $param['device_no'];
	    $ret['result'] =  0;
	    
	    if (!$mid || !$gameid || !$cid || !$pid || !$ctype || !$versions || !$device_no || !$lang || !$mtkey){
	        return $ret;
	    }
	    
	    $aUser['mid']      = $mid;
	    $aUser['gameid']   = $gameid;
	    $aUser['cid']      = $cid;
	    $aUser['ctype']    = $ctype;
	    $aUser['pid']      = $pid;
	    $aUser['versions'] = $versions;
	    $aUser['device_no']    = $device_no;
	    $aUser['lang']     = $lang;
	    $aUser['mtkey']    = $mtkey;
	    
	    $ret['result'] =  1;
	    $ret['url'] = Activity_Manager::getActivityCenterUrl($aUser);
	    
	    return $ret;
	}
	
	public function createroom($param){
	    
	    $roomid = intval(Loader_Redis::room()->getRoomid());
	    if ($roomid == 0) {
	    	$resp["status"] = -1;
	        $resp["msg"] = "房间创建失败";
	        return $resp;
	    }
	    $roominfo = array();
	    
	    $roominfo["roomid"] = $roomid;
	    $roominfo["password"] = $param["param"]["passwd"];
        
	    unset($param["param"]["passwd"]);
	  	    
	    $roominfo["data"]["bringmoney"]  = $param["param"]["bringmoney"];
	    $roominfo["data"]["inningtimes"] = $param["param"]["inningtimes"];
	    $roominfo["data"]["basescore"]   = $param["param"]["basescore"];
	    $roominfo["data"]["basegold"]    = $param["param"]["basegold"];
	    $roominfo["data"]["playercount"] = $param["param"]["playercount"];
	    
	    $roominfojson = stripslashes(json_encode($roominfo));
	    
	    $status = Loader_Redis::game()->hSet("flower_friend_room:info", $roomid, $roominfojson, false, false);
	     	
	    if($status >= 0)
	    {
	        $resp["status"] = 1;
	        $resp["msg"] = "房间创建成功";
	        $resp["roominfo"] = $roominfo;	         
	    }
	    else
	    {
	        $resp["status"] = -1;
	        $resp["msg"] = "房间创建失败";
	    }
	    return $resp;
	}
	
	public function searchroom($param){ 
	   
	    $room_id = $param["param"]["room_id"]; 
	    $memberlist = Loader_Redis::game()->hGetAll("flower_friend_room:members:".$room_id);
	    
	    $memberarr = array();
	    foreach ($memberlist as $k => $v)
	    {
	        $tmp["userID"] = $k;
	        $tmp["nic_name"] = $v;
	        array_push($memberarr, $tmp);
	    }
	    	    
	    $result = Loader_Redis::common()->hget("flower_friend_room:info", $room_id);
	    if ($result) {
	        $ret["status"] = 0;
	        $ret["roominfo"]   = json_decode($result);
	        $ret["memberlist"] = $memberarr;
	    } else {
	        $ret["status"] = -1;
	        $ret["msg"]    = "无该房间信息";
	    }
	    return $ret;
	}
}
