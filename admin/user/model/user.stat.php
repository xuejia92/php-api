<?php !defined('IN WEB') AND exit('Access Denied!');

class User_Stat extends Config_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new User_Stat();
		}
		
		return self::$_instances;
	}
	
	public function getProvincePay($param){
		$gameid = $param['gameid'] ? $param['gameid'] : 'all';
		$pmode  = $param['pmode']  ? $param['pmode']  : 'all';
		$date   = $param['date']   ? $param['date'] : date("Y-m-d",strtotime("-1 days"));
		
		$yesterday = date("Y-m-d",strtotime("$date -1 days"));
		$days = array($date,$yesterday);
		
		$series = array();
		foreach ($days as $k=>$day) {
			$records = Loader_Redis::common()->get("PST|".$day);
			
			if($records){  
				arsort($records[$pmode][$gameid]);
				foreach ($records[$pmode][$gameid] as $province =>$amount) {
					$row[$province][$day]      = $amount;
				}
			}
		}

		if(!$row){
			return array();
		}
		
		$i = 0;
		foreach ($row as $province=>$val) {
			$pro[] = $province;

			foreach ($val as $day=>$amount) {
				$tmp[$day][$province] = $amount;
			}

			$i++;
		}
		$k = 0;

		foreach ($tmp as $day=>$val) {
			$series[$k]['name']   = $day;
			foreach ($val as $p=>$v) {
				$series[$k]['data'][] = $v;
			}
			$k ++ ;
		}

		$ret['series']   = $series;
		$ret['province'] = $pro;
		
		return $ret;
	}
	
	
}	