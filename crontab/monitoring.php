<?php
$dirName = dirname(dirname(__FILE__));
include $dirName.'/common.php';

$ps = " ps -ef | grep redislist.php | grep -v 'grep' | awk '{print $2}'";							
$exe  =shell_exec($ps);		
$arr = explode("\n",$exe);		


if( !$arr[0]  ){
	$tip = "the crontab server monitor restart sendmsg sucess.\n";
	Logs::factory()->debug($tip,'monitoring');
	shell_exec("/usr/local/php/bin/php /data/dis/wwwroot/ucenter/crontab/redislist.php");	
	exit(0);	
}
exit(0);
