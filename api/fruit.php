<?php !defined('IN WEB') AND exit('Access Denied!');
class Fruit{	
	public function rsync($param){
		$data['sid']         = Helper::uint($param['sid']);//账号类型ID	
		$data['cid']         = Helper::uint($param['cid']);//渠道ID
		$data['ctype']       = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$data['pid']         = Helper::uint($param['pid']);//客户端包类型ID
		$data['versions']    = $param['versions'];						
		$data['ip']          = Helper::getip();

		$data['mnick']       = $param['param']['mnick'];
		$data['device_name'] = $param['param']['device_name'];//设备名称
		$data['os_version']  = $param['param']['os_version'];//操作系统版本号
		$data['net_type']    = $param['param']['net_type']  ? strtolower($param['param']['net_type']) : '';//网络设备类型
		$data['device_no']   = $param['param']['device_no'] ? $param['param']['device_no']   : $data['ip'];//android 机器码
		$data['gameid']      = $param['gameid']             ? Helper::uint($param['gameid']) : 5;//游戏ID
		
		$device_no = Member::factory()->getDeviceNo($param);
		
		$sitemid = Member::factory()->getSitemidByKey($device_no,$data['ctype']);		
		!$sitemid && Config_Flag::mkret(Config_Flag::status_register_error);
		$aUser = Member::factory()->getOneBySitemid($sitemid, $data['sid']);
		
		if( !$aUser ){//注册

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
		$aUser['devicename']  = $data['device_name'];
		$aUser['osversion']   = $data['os_version'];//操作系统版本号
		$aUser['nettype']     = $data['net_type'];//网络设备类型
		$aUser['device_no']   = $device_no;//机器码
		$data['ip'] && $aUser['ip'] = $data['ip'];
		$aUser['gameid']      = $data['gameid'];
		
		$aUser['result']      = 1; 
		$this->doUserInfo( $aUser );

		return $aUser;
	}
	
	
}
