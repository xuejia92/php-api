<?php !defined('IN WEB') AND exit('Access Denied!');

class Data_Click extends Data_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Data_Click();
		}
		
		return self::$_instances;
	}
	
	
	
	
}