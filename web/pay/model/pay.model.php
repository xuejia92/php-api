<?php !defined('IN WEB') AND exit('Access Denied!');
class Pay_Model{
	private static $_instance;
	
	public static function factory(){
		
		if(!self::$_instance){
			self::$_instance = new Pay_Model;
		}
		
		return self::$_instance;	
	}
	
	public function check(){
	
	}
	
	
}