<?php !defined('IN WEB') AND exit('Access Denied!');
class Config_Flag {
	const status_ok				  = 200; //处理成功
	const status_check_error 	  = 100; //权限检查错误
	const status_param_error	  = 101; //参数错误
	const status_argment_access	  = 102; //缺少参数
	const status_method_error	  = 103; //方法错误
	const status_register_error	  = 104; //玩家注册失败
	const status_user_error       = 105;	//获取玩家信息失败
	const status_faild_access     = 106; //被封号
	const status_file_error       = 107; //上传文件格式错误
	const status_account_error	  = 108; //用户名或密码不正确
	

	/**
	 * 格式化返回数据
	 */
	public static function mkret($flag, $data = array()) {
		if($data){
			$data = Helper::convertInt($data);
		}
		global $class;
		global $method;
		
		$request =  $_POST['api'];
		$class   = $class  ?  $class  : 'json';
		$method  = $method ? $method  : 'json';
		
		$success = $flag == 200 ? 1 : 0;

		$ret['time'] = time(); 
		$ret['flag'] = $flag;
		$ret['data'] = $data;
		$ret = json_encode( $ret );
		
		$msg = $flag == 200 ? '' : $ret;
		Lib_Apistat::report($class, $method, $success, $flag, $msg.'|'.$request);
		
		Logs::factory()->debug("response :".print_r($ret, 1));
		die($ret);
	}	
	
 	/**
	 * 客户端传过来的参数处理
	 *
	 * @param  json格式  $post 客户端参数 $_POST['api']
	 */
	 public static function param( $post ){
		$ret['data'] = array();

		$post = stripslashes( $post );
		
		if( ! isset( $post )) {
			self::mkret(self::status_argment_access,$ret);
		}
		if( ! $param = json_decode( $post, true )){
			Logs::factory()->debug($post,'json');
			self::mkret(self::status_argment_access,$ret);
        }
		return $param;
	 }
}