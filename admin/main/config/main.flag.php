<?php !defined('IN WEB') AND exit('Access Denied!');

class Main_Flag {
		
	public static function ret_sucess($tip){
		$return['statusCode'] = 200;
		$return['message']    = $tip;
		$return['navTabId']   = $_REQUEST['navTabId'];	
		$_REQUEST['close']== 1 && $return["callbackType"] = "closeCurrent";
		$msg = json_encode($return);		
		die($msg);
	}
	
	public static function ret_fail($tip){
		$return['statusCode'] = 300;
		$return['message']    = $tip;
		$return['navTabId']   = $_REQUEST['navTabId'];	
		$_REQUEST['close']== 1 && $return["callbackType"] = "closeCurrent";		 
		$msg = json_encode($return);		
		die($msg);
	}
	
	public static function ret_timeout($tip){
		$return['statusCode'] = 301;
		$return['message']    = $tip;
		$return['navTabId']   = $_REQUEST['navTabId'];	
		$_REQUEST['close']== 1 && $return["callbackType"] = "closeCurrent";		 
		$msg = json_encode($return);		
		die($msg);
	}

}
