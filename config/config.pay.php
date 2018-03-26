<?php !defined('IN WEB') AND exit('Access Denied!');

class Config_Pay {
	
	public static function getPmode($mtype,$pid,$location,$gameid)
	{
		$mtype = $mtype ? $mtype : 1;
		$paymode = 1;//默认是支付宝
		
		$pmode_str = Loader_Redis::common()->hGet(Config_Keys::msgPayPid($pid), $mtype);
				
		if(!$pmode_str){
			return $paymode;
		}

		$pmode_arr = explode(',', $pmode_str);
		
		if(empty($pmode_arr)){
			return $paymode;
		}
		
		$switch_bit = (int)Loader_Redis::common()->hGet(Config_Keys::optswitch(),$pid);
		$flag = $switch_bit >> 12 & 1;

		foreach ($pmode_arr as $k=>$pmode) {
			$provice_str = Loader_Redis::common()->hGet(Config_Keys::msgPayProvinceBlocked($gameid,$pmode), $mtype);
			
			if(strrpos($provice_str, '暂停') !== false ){
				continue;
			}
			
			if(!$provice_str){
				$paymode = $pmode;
				break;
			}
			
			$provice_arr = explode(',', $provice_str);
			if(empty($provice_arr)){
				$paymode = $pmode;
				break;
			}
			
			if ($flag){//特殊包，不检查省份,比如移动MM审核时的包
				$paymode = $pmode;
				break;
			}
			
    		if(!in_array($location, $provice_arr)){//判断是否在屏蔽的省份中
    			$paymode = $pmode;
    			break;
    		}
		}
				
		return $paymode;
	}
	
	public static $specialAccount = array(4040798,4179248,4039929,4436684,4436707,4436725,4436743,4413006,4412265,714567,4508537,4531782,4531822,4321983,5083739,5241908);
	
	public static $big2money      = array(5000=>109000000,10000=>218000000);
	
	public static $actReward      = array(
				    					5     =>5000,
				    					10    =>13000,
				    					58    =>90000,
				    					168   =>280000,
				    					268   =>500000,
				    					488   =>1000000,
				    					999   =>2400000,
				    					5000  =>12000000,
				    					10000 =>30000000
    					);
    					
    public static $sprank      = array(
				    					5000  =>array(25000000,29000000),
				    					10000 =>array(50000000,54000000),
    					);					
	
	public static $group_money = array(
										1=>array('amount'=>100000,'chip'=>2180000000),
										2=>array('amount'=>100000,'chip'=>2180000000),
										3=>array('amount'=>0,'chip'=>0),
										4=>array('amount'=>0,'chip'=>0),
									);
	
	public static $sp_user = array(//用户组ID =>array(mid)
									1 => array(4413006,4412265,5083739),
									2 => array(4436684,4436707,4436725,4436743,5241908),
									3 => array(4040798,4179248,4039929,714567,4508537),
									4 => array(4531782,4531822),
									);

}