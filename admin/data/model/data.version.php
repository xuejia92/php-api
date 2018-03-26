<?php !defined('IN WEB') AND exit('Access Denied!');

class Data_Version extends Data_Table{

	private static $_instance;
	
	public static function factory(){
		if(!is_object(self::$_instance)){
			self::$_instance = new Data_Version();
		}
	
		return self::$_instance;
	}
	
	public function get($data)
	{  
		$s_cid   = $data['cid']   ? $data['cid'] : '*';
		$s_ctype = $data['ctype'] ? $data['ctype'] : '*';
		$gameid = Helper::uint($data['gameid']);
		$day = $data['date'] ? $data['date'] : date("Ymd",strtotime("-1 days"));
		$date   = date("Ymd",strtotime($day));
		
		if($date <= '20150120'){
			$datas = Loader_Redis::common()->hGetAll(Config_Keys::versionData());
			$records = array();
			foreach ($datas as $key =>$val) 
			{
				$pos  = strrpos($key, $date.'-'.$gameid);
				if($pos !== false)
				{
					$tmp     = explode("-", $key);
					$version = end($tmp);
					$rows[$version] = (int)$val;
				}
			}
		}else{
			$key = $date.'-'.$data['gameid'].'-'.$s_ctype.'-'.$s_cid.'-*';
			$arr_keys = Loader_Redis::common()->getKeys($key);
			$rows = array();
			foreach ($arr_keys as $k=>$key) {
				$val = Loader_Redis::common()->get($key,false,false);
				$tmp     = explode("-", $key);
				$version = end($tmp);
				$rows[$version] = (int)$rows[$version] + (int)$val;
			}
		}

		krsort($rows);
		foreach ($rows as $k=>$v) {
			$records[] = array($k,(int)$v);
		}
		return $records;
	}
}