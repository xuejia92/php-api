<?php
$prostr = shell_exec(" ps -ef|grep monitoring.php |grep -v 'grep' |awk  '{print $2}'");

$aPro = explode("\n", $prostr);

if(count($aPro)){
	foreach ($aPro as $pid) {
		if($pid){
			shell_exec("kill -9 $pid");
		}
	}
}