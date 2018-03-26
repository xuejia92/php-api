<?php !defined('IN WEB') AND exit('Access Denied!');

class Setting_Msgpay {

	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new Setting_Msgpay();
		}
		
		return self::$_instances;
	}
	
	public function set($data){
		$pmode1   = $data['shop_pmode1'];
		$pmode2   = $data['shop_pmode2'];
		$pmode3   = $data['shop_pmode3'];
		$aGameid = $data['gameid'];
		
		if(!$aGameid ){
			return false;
		}
		
		if(!$pmode1 || !$pmode2 || !$pmode3){
			return false;
		}
		
		foreach ($aGameid as $gameid) {
			Loader_Redis::common()->hSet(Config_Keys::msgPay($gameid), 1, $pmode1);
			Loader_Redis::common()->hSet(Config_Keys::msgPay($gameid), 2, $pmode2);
			Loader_Redis::common()->hSet(Config_Keys::msgPay($gameid), 3, $pmode3);
		}

		return true;
	}
	
	public function del($data){
		$gameid = $data['gameid'];
		
		return Loader_Redis::common()->delete(Config_Keys::msgPay($gameid));
	}
	
	public function get($data=array()){
		$aGame = Config_Game::$game;
		
		$pmode2name = $this->getPayChannel();
		
		$row = array();
		foreach ($aGame as $gameid=>$gname) {
			$records = Loader_Redis::common()->hGetAll(Config_Keys::msgPay($gameid));
			foreach ($records as $mtype=>$v) {
				$aPmode = explode(",", $v);
				$tmp = array();
				foreach ($aPmode as $pmode) {
					$tmp[] = $pmode2name[$pmode];
				}
				$row[$gameid][$mtype]['name']  = implode(", ", $tmp);
				$row[$gameid][$mtype]['pmode'] = $v;
			}
		}

		return $row;
	}
	
	public function getPmodeByPid($pid){
		$pmodes = Loader_Redis::common()->hGetAll(Config_Keys::msgPayPid($pid));

		$pmode2name = $this->getPayChannel();
		$tmp = $row = $array = array();
		
		if(!$pmodes){
			return array();
		}
		
		foreach ($pmodes as $mtype=>$pmode) {
			$tmp = explode(",", $pmode);
			$array = array();
			foreach ($tmp as $v) {
				$array[] = $pmode2name[$v];
			}
			$row[$mtype]['name']  = implode(',', $array);
			$row[$mtype]['pmode'] = $tmp;
		}
		
		return $row;
	}
	
	public function setPmodeByPid($data=array()){
		$pid    = $data['id'];
		$pmode1 = $data['shop_pmode1'];
		$pmode2 = $data['shop_pmode2'];
		$pmode3 = $data['shop_pmode3'];
		Loader_Redis::common()->hSet(Config_Keys::msgPayPid($pid),1,$pmode1);
		Loader_Redis::common()->hSet(Config_Keys::msgPayPid($pid),2,$pmode2);
		Loader_Redis::common()->hSet(Config_Keys::msgPayPid($pid),3,$pmode3);

		return true;
	}
	
	public function sort($data){
		$gameid = $data['gameid'];
		$mtype  = $data['mtype'];
		$ids = $data['ids'];
		$pos = $data['pos'];

		if(empty($ids) || empty($pos) || !$gameid){
			return false;
		}

		$id2sort = array();
		foreach ($ids as $k=>$id) {
			$id2sort[$id] = $pos[$k];
		}
		
		asort($id2sort);
		$sort2id = array_keys($id2sort);
		
		$pmode_str = implode(',', $sort2id);
		
		Loader_Redis::common()->hSet(Config_Keys::msgPay($gameid), $mtype, $pmode_str);
		
		return true;
	}
	
	public function sort2allPid($data){
		$gameid = $data['gameid'];
		$pid    = $data['id'];
		
		if($pid){
			$pids = array($pid=>'');
		}else{
			$pids = Base::factory()->getPack();
		}

		for($mtype=1;$mtype<4;$mtype++){
			$pmode_str = Loader_Redis::common()->hGet(Config_Keys::msgPay($gameid), $mtype);
			$pmode_arr = explode(",", $pmode_str);

			foreach ($pids as $pid=>$pname) {
				$pmodes = Loader_Redis::common()->hGet(Config_Keys::msgPayPid($pid), $mtype);
				
				if(!$pmodes){
					continue;
				}
				
				$_pmode = explode(',', $pmodes);
				
				$_temp = array_flip ( $_pmode );
				foreach ($pmode_arr as $k=>$v) {
					if(in_array($v,$_pmode)){
						$_temp[$v] = $k + 1;
					}
				}
				
				if($_temp){
					asort($_temp);
					$pmode = array_keys($_temp);
					$pmode = implode(',', $pmode);
					Loader_Redis::common()->hSet(Config_Keys::msgPayPid($pid), $mtype, $pmode);
				}
			}
		}
		return true;
	}
	
	public function sortPidPmode($data){
		$pid    = $data['pid'];
		$mtype  = $data['mtype'];
		$ids    = $data['ids'];
		$pos    = $data['pos'];
		
		if(empty($ids) || empty($pos)){
			return false;
		}

		$id2sort = array();
		foreach ($ids as $k=>$id) {
			$id2sort[$id] = $pos[$k];
		}
		
		asort($id2sort);
		$sort2id = array_keys($id2sort);
		
		$pmode_str = implode(',', $sort2id);
		
		Loader_Redis::common()->hSet(Config_Keys::msgPayPid($pid), $mtype, $pmode_str);
		
		return true;
	
	}
	
	public function getPayChannel(){
		$sql  = "SELECT * FROM uc_setting_pmode ORDER BY `order` ASC";
		$rows = Loader_Mysql::DBMaster()->getAll($sql);
		
		$rtn = array();		
		if($rows){
			foreach ($rows as $row) {
				$rtn[$row['pmode']] = $row['payname'];
			}
		}
		return $rtn;		
	}
	
	public function setProvince($data){
	    $gameid    = $data['gameid'];
		$pmode     = $data['pmode'];
		$province1 = $data['province1'];
		$province2 = $data['province2'];
		$province3 = $data['province3'];

		if(!$gameid || !$pmode){
			return false;
		}
		
		Loader_Redis::common()->hSet(Config_Keys::msgPayProvinceBlocked($gameid,$pmode), 1, $province1);
		Loader_Redis::common()->hSet(Config_Keys::msgPayProvinceBlocked($gameid,$pmode), 2, $province2);
		Loader_Redis::common()->hSet(Config_Keys::msgPayProvinceBlocked($gameid,$pmode), 3, $province3);
		return true;
	}
	
	public function getProvice($data){
		$gameid = $data['gameid'] ? $data['gameid'] : 1;
		$key  = "MSGPC|$gameid|*";
		$keys = Loader_Redis::common()->getKeys($key);
		
		$row = array();
		foreach ($keys as $key) {
			$arr   = explode('|', $key);
			$pmode = $arr[2];
			$row[$gameid][$pmode] = Loader_Redis::common()->hGetAll($key);
		}
		ksort($row[$gameid]);
		
		return $row;
	}
	
	public function getOnePmodeProvice($data=array()){
	    $gameid = $data['gameid'];
		$pmode  = $data['pmode'];
		
		return Loader_Redis::common()->hGetAll(Config_Keys::msgPayProvinceBlocked($gameid,$pmode));
	}
	
	public function delProvice($data){
	    $gameid = $data['gameid'];
		$pmode  = $data['pmode'];

		return Loader_Redis::common()->delete(Config_Keys::msgPayProvinceBlocked($gameid,$pmode));
	}
	
}