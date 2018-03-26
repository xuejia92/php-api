<?php !defined('IN WEB') AND exit('Access Denied!');

class Loader_Sendlog
{
    private  static $_instance = array();

	/**
     *	用户行为 
     * 
     *  @return Lib_Log
     */
    public static function behavior(){
    	if( !is_object( self::$_instance['behavior'] ) )
    	{
    		
    		self::$_instance['behavior'] = new Lib_Log( Config_Inc::$udpserverBehavior);
    	}
    	
    	return self::$_instance['behavior'];
    }
    
	/**
     *	统计中心
     * 
     *  @return Loader_Sendlog
     */
    public static function stat(){
    	if( !is_object( self::$_instance['stat'] ) )
    	{
    		self::$_instance['stat'] = new Lib_Log( Config_Inc::$udpserverStat);
    	}
    	
    	return self::$_instance['stat'];
    }
    
}