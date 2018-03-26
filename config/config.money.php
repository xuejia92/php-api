<?php !defined('IN WEB') AND exit('Access Denied!');
class Config_Money{	
	public static $max     = 1000000000;//最大钱数
	
	//连续登陆天数奖励  天数=>金币
	public static $loginReward = array(
									  1=>500,
									  2=>800,
									  3=>900,	
									  4=>1000,	
									  5=>1500,	
									  6=>2000,
									  7=>2500,		
									  );
									  
	//破产补助 钱数
	public static $bankrupt = 2000;

	public static $vipConfig = array(//金币兑换会员卡	
									1   =>array('money'=>500000 ,'vipexptime'=>30),
									2   =>array('money'=>100000 ,'vipexptime'=>7),
									10  =>array('money'=>1500000,'vipexptime'=>90),
									100 =>array('money'=>5000000,'vipexptime'=>365),
								);		
	
	public static $firpayReward = 30000;//首次充值送金币(旧版本)
	
	public static $firpayRewardNew = array('2'=>'40000','6'=>'63000','5'=>'52000','10'=>'108000','1'=>'14000');//新版本首充礼包 0.99表示千寻
	public static $firpayGetVip    = array('2'=>'2','6'=>'3','5'=>'3','1'=>'2');//首充送VIP天数
	
	public static $bingReward   = 1000;//绑定点乐账号奖励
	
	public static $firstPayRewardRoll = 10;//新版本首充送乐券 3.0.0
	public static $fastPayRewardRoll  = 8;//新版本快速充值送乐券 3.0.0
	
	//注册赠送金币数
	public static $firstin = array(
									1=>4500,//梭哈
									2=>3000,//百家乐
									3=>4500,//斗地主
									4=>3000,//斗牛
									5=>4000,//水果机
									6=>4000,//德州
									7=>400000,//炸金花
								);
								
	public static $spfirstin = 6000; //特殊渠道的注册金币
}