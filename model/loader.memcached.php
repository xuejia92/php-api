<?php !defined('IN WEB') AND exit('Access Denied!');

class Loader_Memcached
{
    protected static $_instance = array();
    
    /**
     * 创建一个实例
     *     
     * @return Lib_Memcached     
     */
    public static function cache()
    {
        if(!is_object(self::$_instance['cache']))
        {
            self::$_instance['cache'] = new Lib_Memcached(Config_Inc::$memcache);
        }
        
        return self::$_instance['cache'];
    }
    
    /**
     * 用户信息散列
     *
     * @param {int} $mid 用户ID
     *
     * @return object Lib_Memcached
     */
    public static function minfo($mid)
    {
        $hash = isset(Config_Inc::$memcacheMinfo[2]) ? intval($mid%3) : 0;
        
        if(!is_object(self::$_instance['minfo'][$hash]))
        {
            self::$_instance['minfo'][$hash] = is_array(Config_Inc::$memcacheMinfo) ? new Lib_Memcached(isset(Config_Inc::$memcacheMinfo[2]) ? Config_Inc::$memcacheMinfo[$hash] : Config_Inc::$memcacheMinfo) : self::cache();
        }
        
        return self::$_instance['minfo'][$hash];
    }

}
