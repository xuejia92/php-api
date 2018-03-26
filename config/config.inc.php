<?php !defined('IN WEB') AND exit('Access Denied!');

define('PRODUCTION_SERVER', false);

class Config_Inc{
	
	public static $idc = 1;
	
	public static $platForm = '';//平台名称，主要针对web端
	
	public static $gameid   = 7; //游戏ID，后台统计用
	
	public static $dbmaster = array(array('192.168.1.233', 'root', '123', 'ucenter'));
    
    public static $dbslave  = array(array('192.168.1.233', 'root', '123', 'ucenter')); 
    
    public static $dbstat  = array(array('192.168.1.233', 'root', '123', 'stat')); 

    public static $dbexchange = array(array('192.168.1.233', 'root', '123', 'exchange')); 
    
    public static $dblogchip = array(array('192.168.1.233', 'root', '123', 'logchip'));
    
    public static $redisMinfo =  array(   0=>array('192.168.1.233', '4501'),
    									  1=>array('192.168.1.233', '4502'),
    								   );
    	
    public static $redisUser      = array('192.168.1.233', '4501');

    public static $redisUserSlave = array('192.168.1.233', '4501');
    
    public static $redisGame      = array('192.168.1.233', '4501'); 

    public static $redisbullfight = array('192.168.1.233', '4501');    //斗牛

    public static $redistexas     = array('192.168.1.233', '4501');     //德州扑克
    
    public static $redisflower    = array('192.168.1.233', '4501');       //炸金花

    public static $redisCommon    = array('192.168.1.233', '4501'); 		
    
    public static $redisCommonSlave = array('192.168.1.233', '4501');

    public static $redisAccount     = array('192.168.1.233', '4501'); 
    
    public static $redisAccountSlave = array('192.168.1.233', '4501');

    public static $redisOte = array('192.168.1.233', '4501');

    public static $redisOteSlave  = array('192.168.1.233', '4501');

    public static $redisPush      =  array('192.168.1.233', '4501'); 

    public static $redisRoom      =  array('192.168.1.233', '4400'); // 房间池

    //public static $gameServerAddr = array('ip'=>'10.25.74.95','port'=>'28000');//游戏信息回头操作
    
    public static $udpserverBehavior  = array('ip'=>'192.168.1.234','port'=>8001);//udp发送用户行为日记 端口
    
    public static $udpserverStat  = array('ip'=>'192.168.1.234','port'=>8001);//udp发送统计数据端口

    public static $iconPath          = '/home/wwwroot/default/flower/data';
	
	public static $iconDomain        = 'http://192.168.1.233/flower/data/icon/';
	
	public static $feedbackPicDomain = 'http://192.168.1.233/flower/data/feedback/';
	
	public static $hallAddr          = array('ip'=>'192.168.1.234', 'port' => 26000);
	
	public static $serverHorn        = array('ip'=>'192.168.1.234', 'port'=>24000);//小喇叭服务器
	
	public static $serverCheckUserName  = array('ip'=>'192.168.1.234', 'port'=>22000);//检查用户名
       
    public static $moneyServer          = array('ip'=>'192.168.1.234', 'port' => 27000);

	public static $activityUrl = "http://192.168.1.233/flower/web/activity/zhajinhua/activity.html";

}
