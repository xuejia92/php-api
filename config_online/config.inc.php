<?php !defined('IN WEB') AND exit('Access Denied!');

define('PRODUCTION_SERVER', false);

class Config_Inc{
	
	public static $idc = 1;
	
	public static $platForm = '';//平台名称，主要针对web端
	
	public static $gameid   = 7; //游戏ID，后台统计用
	
	public static $dbmaster = array(array('10.25.74.110', 'root', 'rkts2016', 'ucenter'));
    public static $userServer = array('ip'=>'10.25.74.95', 'port'=>22000); 
    public static $dbslave  = array(array('10.25.74.110', 'root', 'rkts2016', 'ucenter')); 
    
    public static $dbstat  = array(array('10.25.74.110', 'root', 'rkts2016', 'stat')); 

    public static $dbexchange = array(array('10.25.74.110', 'root', 'rkts2016', 'exchange')); 
    
    public static $dblogchip = array(array('10.25.74.110', 'root', 'rkts2016', 'logchip'));
    
    public static $redisMinfo =  array(   0=>array('10.25.74.110', '4501'),
    									  1=>array('10.25.74.110', '4501'),
    								  );
    	
    public static $redisUser      = array('10.25.74.110', '4501');

    public static $redisUserSlave = array('10.25.74.110', '4501');
    
    public static $redisGame      = array('10.25.74.110', '4501'); 

    public static $redisbullfight = array('10.25.74.110', '4501');    //斗牛

    public static $redistexas     = array('10.25.74.110', '4501');     //德州扑克
    
    public static $redisflower    = array('10.25.74.110', '4501');       //炸金花

    public static $redisCommon    = array('10.25.74.110', '4501'); 		
    
    public static $redisCommonSlave = array('10.25.74.110', '4501');

    public static $redisAccount     = array('10.25.74.110', '4501'); 
    
    public static $redisAccountSlave = array('10.25.74.110', '4501');

    public static $redisOte = array('10.25.74.110', '4501');

    public static $redisOteSlave  = array('10.25.74.110', '4501');

    public static $redisPush      =  array('10.25.74.110', '4501'); 

    public static $redisRoom      =  array('10.25.74.110', '4400'); // 房间池

    //public static $gameServerAddr = array('ip'=>'10.25.74.95','port'=>'28000');//游戏信息回头操作
    
    public static $udpserverBehavior  = array('ip'=>'10.25.74.110','port'=>8001);//udp发送用户行为日记 端口
    
    public static $udpserverStat  = array('ip'=>'10.25.74.110','port'=>8001);//udp发送统计数据端口

    public static $iconPath          = '/home/wwwroot/default/flower/data/';
	
	public static $iconDomain        = 'http://139.129.202.70/flower/data/icon/';
	
	public static $feedbackPicDomain = 'http://139.129.202.70/flower/data/feedback/';
	
	public static $hallAddr          = array('ip'=> "66778899.biz", 'port' => 26000);
	
	public static $serverHorn        = array('ip'=>'10.25.74.95', 'port'=>24000);//小喇叭服务器
	
	public static $serverCheckUserName  = array('ip'=>'10.25.74.95','port'=>22000);//检查用户名
       
    public static $moneyServer          = array('ip'=> "10.25.74.95",'port' => 27000);

	public static $activityUrl = "http://139.129.202.70/flower/web/activity/zhajinhua/activity.html";
}
