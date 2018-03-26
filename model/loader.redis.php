<?php !defined('IN WEB') AND exit('Access Denied!');

class Loader_Redis
{
    private  static $_instance = array();
    
    
    /**
     * 用户信息散列
     *
     * @param {int} $mid 用户ID
     *
     * @return Lib_Redis
     */
    public static function minfo($mid)
    {
        $hash = isset(Config_Inc::$redisMinfo[1]) ? intval($mid%2) : 0;
        
        if(!is_object(self::$_instance['minfo'][$hash]))
        {
            self::$_instance['minfo'][$hash] = is_array(Config_Inc::$redisMinfo) ? new Lib_Redis(isset(Config_Inc::$redisMinfo[1]) ? Config_Inc::$redisMinfo[$hash] : Config_Inc::$redisCommon) : self::common();
        }
        
        return self::$_instance['minfo'][$hash];
    }
    
    /**
     *	公用redis 
     * 
     *  @return Lib_Redis
     */
    public static function common($mode='master'){
    	
    	$addr = $mode == 'master' ? Config_Inc::$redisCommon : Config_Inc::$redisCommonSlave;
    	
    	if( !is_object( self::$_instance['common'.$mode] ) )
    	{
    		self::$_instance['common'.$mode] = new Lib_Redis( $addr );
    		
    	}
    	return self::$_instance['common'.$mode];
    }
    
	 /**
     *	公用redis 
     * 
     *  @return Lib_Redis
     */
    public static function game(){
    	if( !is_object( self::$_instance['game'] ) )
    	{
    		self::$_instance['game'] = new Lib_Redis( Config_Inc::$redisGame);
    		
    	}
    	return self::$_instance['game'];
    }
    
	/**
     *	百家乐server redis端口
     * 
     *  @return Lib_Redis
     */
    public static function baccarat(){
    	if( !is_object( self::$_instance['baccarat'] ) )
    	{
    		self::$_instance['baccarat'] = new Lib_Redis( Config_Inc::$redisBaccarat);
    		
    	}
    	return self::$_instance['baccarat'];
    }
	
	/**
     *	Game redis  sever专用 
     * 
     *  @return Lib_Redis
     */
    public static function server(){
    	
    	if( !is_object( self::$_instance['server'] ) )
    	{
    		self::$_instance['server'] = new Lib_Redis( Config_Inc::$redisGame);
    	}
    	
    	return self::$_instance['server'];
    }
    
	/**
     *	账号 专用 
     * 
     *  @return Lib_Redis
     */
    public static function account($mode='master'){
    	$addr = $mode == 'master' ? Config_Inc::$redisAccount : Config_Inc::$redisAccountSlave;
    	if( !is_object( self::$_instance['account'.$mode] ) )
    	{
    		self::$_instance['account'.$mode] = new Lib_Redis( $addr );
    	}
    	
    	return self::$_instance['account'.$mode];
    }
    
	/**
     *	存用户的账号，游戏牌局等非核心资料
     * 
     *  @return Lib_Redis
     */
    public static function ote($mid,$mode='master'){
    	$mod  = $mid % 5;
		$addr = $mode == 'master' ? Config_Inc::$redisOte : Config_Inc::$redisOteSlave;
		$port = $addr[1];

        $addr = array($addr[0], $port);
    	if( !is_object( self::$_instance['ote'.$mode][$mid] ) )
    	{
    		self::$_instance['ote'.$mode][$mid] = new Lib_Redis( $addr);
    	}
    	
    	return self::$_instance['ote'.$mode][$mid];
    }
    
    /**
     *	推送 专用 
     * 
     *  @return Lib_Redis
     */
    public static function push(){
    	if( !is_object( self::$_instance['push'] ) )
    	{
    		self::$_instance['push'] = new Lib_Redis( Config_Inc::$redisPush);
    	}
    	
    	return self::$_instance['push'];
    }
    
 	/**
     *	排行榜
     * 
     *  @return Lib_Redis
     */
    public static function rank($gameid){
    	if( !is_object( self::$_instance['rank'][$gameid] ) )
    	{   
    		switch ($gameid) {
    			case 2:
    			$addr = Config_Inc::$redisBaccarat;
    			break;
    			case 3:
    			$addr = Config_Inc::$redisPush;
    			break;
    			case 4:
    			$addr = Config_Inc::$redisbullfight;
    			break;
    			case 6:
    			$addr = Config_Inc::$redistexas;
    			break;
    			case 7:
			    $addr = Config_Inc::$redisflower;
			    break;
			    
    			default:
    				$addr = Config_Inc::$redisPush;;
    			break;
    		}
    		self::$_instance['rank'][$gameid] = new Lib_Redis( $addr);
    	}
    	
    	return self::$_instance['rank'][$gameid];
    }
    
	/**
     *	用户服务器redis
     *  @return Lib_Redis
     */
    public static function userServer($mid,$mode='master'){
    	$mod  = $mid % 10;
		$addr = $mode == 'master' ? Config_Inc::$redisUser : Config_Inc::$redisUserSlave;
		$port = $addr[1][0] + $mod;
		$addr = array($addr[0], $port);
		
		if(!is_object(self::$_instance['userServer'.$mode][$mid])){
			self::$_instance['userServer'.$mode][$mid] = new Lib_Redis($addr);
		}
		
		return self::$_instance['userServer'.$mode][$mid];
    }
    
	/**
     *	用户服务器redis 根据端口号
     *  @return Lib_Redis
     */
    public static function userServerByPort($port,$mode='master'){
    	$addr = $mode == 'master' ? Config_Inc::$redisUser : Config_Inc::$redisUserSlave;
    	$ip   = $addr[0];
		$addr = array($ip, $port);

		if(!is_object(self::$_instance['userServerByPort'.$mode][$port])){
			self::$_instance['userServerByPort'.$mode][$port] = new Lib_Redis($addr);
		}
		
		return self::$_instance['userServerByPort'.$mode][$port];
    }
      
    /**
     *  房间池redis 
     * 
     *  @return Lib_Redis
     */
    public static function room(){
        if( !is_object( self::$_instance['room'] ) )
        {
            self::$_instance['room'] = new Lib_Redis( Config_Inc::$redisRoom);
            
        }
        return self::$_instance['room'];
    }  
}