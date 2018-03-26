<?php
set_time_limit(0);
$dirName = dirname(dirname(__FILE__));

include $dirName.'/common.php';


$amount = Loader_Redis::common()->get(Config_Keys::fflcoin(),false,false);//翻翻乐全局金币数据统计

$limit = 500000;

echo $amount;
echo "\n";

if($amount > $limit){
	
	$cut_down = $amount - $limit;
	echo $cut_down;
	echo "\n";
	Loader_Redis::common()->incr(Config_Keys::fflcoin(),-$cut_down,Helper::time2morning());
	
	Logs::factory()->debug(array('cut_down_money'=>$cut_down),'do_slots_cutdown');
}