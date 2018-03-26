<?php
$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';
set_time_limit(0);

if(date("md",NOW) >= 1001 &&  date("md",NOW) <= 1007){
	//return false;
}

$stime = strtotime("2015-09-01 00:00:00");
$etime = strtotime("2015-09-04 23:59:59");
if(NOW > $stime && NOW <= $etime){
	Loader_Tcp::callServer2Horn()->setHorn("系统消息: 9.1-9.4登陆即可获得记牌器一天!",3,10);
}

Loader_Tcp::callServer2Horn()->setHorn("系统消息: 充值返利活动限时进行中，详情请查看活动中心！",0,100);//type  100 表示发到全部游戏

sleep(10);

Loader_Tcp::callServer2Horn()->setHorn("系统消息: 捕鱼捉虫有奖啦,在娱乐场玩捕鱼发现问题反馈给我们可获得奖励哦!",1,10);

sleep(10);

Loader_Tcp::callServer2Horn()->setHorn("系统消息: 利用非法手段诈骗的玩家，如遇封号后果自负。",0,100);//type  100 表示发到全部游戏

sleep(5);

Loader_Tcp::callServer2Horn()->setHorn("系统消息: 金币是虚拟游戏积分，请勿私下交易，谨防诈骗。",0,100);//type  100 表示发到全部游戏

sleep(15);

Loader_Tcp::callServer2Horn()->setHorn("系统消息:恶意注册账号严重影响游戏环境，一经发现，将予以封号处理",0,100);//type  100 表示发到全部游戏

