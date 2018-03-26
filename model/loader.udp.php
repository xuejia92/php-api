<?php !defined('IN WEB') AND exit('Access Denied!');

class Loader_Udp
{
    private  static $_instance = array();

	/**
     *	用户行为 
     * 
     *  @return Lib_Log
     */
    public static function behavior($gameid){
    	if( !is_object( self::$_instance['behavior'] ) )
    	{
    		$gameid = $gameid ? $gameid : 1;
    		$addr   = Config_Inc::$udpserverBehavior;
    		$ip     = $addr['ip'];
    		$port   = $addr['port'];
    		self::$_instance['behavior'] = new Lib_Log( $gameid,$ip, $port);
    	}
    	
    	return self::$_instance['behavior'];
    }
    
	/**
     *	统计中心
     * 
     *  @return Lib_Log
     */
    public static function stat(){
    	if( !is_object( self::$_instance['stat'] ) )
    	{
    		global $param;
    		$gameid = $param['gameid'];
    		$gameid = $gameid ? $gameid : 1;
    		$addr   = Config_Inc::$udpserverBehavior;
    		$ip     = $addr['ip'];
    		$port   = $addr['port'];
    		self::$_instance['stat'] = new Lib_Log($gameid, $ip, $port);
    	}
    	
    	return self::$_instance['stat'];
    }
    
}