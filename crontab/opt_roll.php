<?php
include '../common.php';
set_time_limit(0);
$m = date("m");

die();
//到11月份把乐券设为过期字段

if(1){
	
	//$max_mid = Member::factory()->getMaxmid();
	//echo $max_mid;
	//echo "\n";
	//sleep(3);
	$max_mid = 900000;
	for($mid=661556;$mid<=$max_mid;$mid++){
		Loader_Tcp::callServer($mid)->get($mid);//初始化server内存数据
		$gameInfo = Member::factory()->getGameInfo($mid);
		$roll     = $gameInfo['roll'];
		if($roll){
			$result = Loader_Tcp::callServer($mid)->setRoll($mid, -$roll);	//	减roll
			if($result){
				$result = Loader_Tcp::callServer($mid)->setRollExp($mid, $roll);//加roll1
			}
		}
		echo $roll;
		echo "\n";
		echo $mid;
		echo "\n";
		
	}
}


die();

//12月底，过过期且未使用的乐券归0

if(0){
	$max_mid = Member::factory()->getMaxmid();
	
	for($mid = 965530;$mid<=$max_mid;$mid++){
		Loader_Tcp::callServer($mid)->get($mid);//初始化server内存数据
		$gameInfo = Member::factory()->getGameInfo($mid);
		$roll1     = $gameInfo['roll1'];
		if($roll1){
			$result = Loader_Tcp::callServer($mid)->setRollExp($mid, -$roll1);//加roll1
		}
		
		echo $mid;
		echo "\n";
	}
}



