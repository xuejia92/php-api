<?php
include '../common.php';


die();

$key = "BAT|*";


$keys = Loader_Redis::account()->getKeys($key);

$i = 0;
foreach ($keys as $key) {
	$arr = explode("|", $key);
	$mid = $arr[1];
		
	$gameinfo = Member::factory()->getGameInfo($mid);
	if(!$gameinfo){
		continue;
	}
	
	if($i > 1){
		//return ;
	}
	
	if($gameinfo['freezemoney']){
		echo $mid;
		echo "\n";
	}
	
	Member::factory()->getMoneyFromBank($mid, $gameinfo['freezemoney'],4);

	$money    = $gameinfo['money'] + $gameinfo['freezemoney'];
	
	Logs::factory()->addWin(4,$mid, 5,102, 1, 1346,2, 1,$money);
	
	//Loader_Redis::ote($mid)->delete(Config_Keys::other($mid));
	
	//echo $mid;
	//echo "\n";
	$i++;
}






die();
set_time_limit(0);

for($mid=1100000;$mid<=1300000;$mid++){
	
	$lastLoginTime = Loader_Redis::account()->hget(Config_Keys::other($mid),'lastLoginTime');
	
	$day = strtotime("-70 days");
	
	if($lastLoginTime <= $day){
		
		echo $mid;
		echo "\n";
		echo date("Y-m-d",$lastLoginTime);
		echo "\n";
		$flag = Loader_Redis::account()->delete(Config_Keys::other($mid));
		var_dump($flag);
		echo "\n";
	
	}

}