<?php !defined('IN WEB') AND exit('Access Denied!');

class Loader_Mysql
{
    protected static $_instance = array();
    
    /**
     * @return Lib_Mysql
     */
    public static function DBMaster()
    {
        if(!is_object(self::$_instance['dbmaster']))
        {
            self::$_instance['dbmaster'] = new Lib_Mysql(Config_Inc::$dbmaster);
        }
        
        return self::$_instance['dbmaster'];        
    }
    
    /**
     * @return Lib_Mysql
     */
    public static function DBSlave()
    {
        if(!is_object(self::$_instance['dbslave']))
        {
            self::$_instance['dbslave'] = new Lib_Mysql(Config_Inc::$dbslave);
        }
        
        return self::$_instance['dbslave'];
    }
    
	/**
     * @return Lib_Mysql
     */
    public static function DBStat()
    {
        if(!is_object(self::$_instance['dbstat']))
        {
            self::$_instance['dbstat'] = new Lib_Mysql(Config_Inc::$dbstat);
        }
        
        return self::$_instance['dbstat'];
    }
	
	/**
     * @return Lib_Mysql
     */
    public static function DBExchange()
    {
        if(!is_object(self::$_instance['dbexchange']))
        {
            self::$_instance['dbexchange'] = new Lib_Mysql(Config_Inc::$dbexchange);
        }
        
        return self::$_instance['dbexchange'];
    }
    
	/**
     * @return Lib_Mysql
     */
    public static function DBLogchip()
    {
        if(!is_object(self::$_instance['Logchip']))
        {
            self::$_instance['Logchip'] = new Lib_Mysql(Config_Inc::$dblogchip);
        }
        
        return self::$_instance['Logchip'];
    }
   
}