<?php !defined('IN WEB') AND exit('Access Denied!');
class Main_Sh extends Setting_Table{
	
	private static $_instance;
	
	public static function factory(){
		if(!self::$_instance){
			self::$_instance = new Main_Sh();
		}
		return self::$_instance;
	}
	
	public function getAll($type){
		switch ($type) {
			case 1://龙虎斗
				$key = "ServerDragonTable10";
			break;
			case 2://斗牛百人
				$key = "ServerBullTable81";
			break;
			
			default:
				;
			break;
		}
		
		return Loader_Redis::server()->hGetAll($key);	}
	
	public function getMembers($type){
		$result = array();
		switch ($type) {
			case 1://龙虎斗
				$key = "ServerDragon10bet";
				
				for($i=1;$i<=3;$i++){
					$str = '';
					$rows = Loader_Redis::server()->hGetAll($key.$i);
					foreach ($rows as $mid=>$betMoney) {
						if($betMoney < 100000){
							continue;
						}
						$gameInfo = Member::factory()->getGameInfo($mid);
						$str  .= 'mid:'.$mid."&nbsp;&nbsp;";
						$str  .= "下注金币:<span style='color:red'>".$betMoney."</span>&nbsp;&nbsp;";
						$str .= '金币:'.$gameInfo['money']."&nbsp;&nbsp;";;
						$str .= '保险箱:'.$gameInfo['freezemoney']."&nbsp;&nbsp;";;
						$str .= "<br/>";
						$result['m_bet'.$i] = $str;
					}
				}
			break;
			case 2://斗牛百人
				$key = "ServerBull81bet";
				$str = '';
				for($i=1;$i<=4;$i++){
					$str = '';
					$rows = Loader_Redis::server()->hGetAll($key.$i);
					foreach ($rows as $mid=>$betMoney) {
						if($betMoney < 100000){
							continue;
						}
						$gameInfo = Member::factory()->getGameInfo($mid);
						$str  .= 'mid:'.$mid."&nbsp;&nbsp;";
						$str  .= "下注金币:<span style='color:red'>".$betMoney."</span>&nbsp;&nbsp;";
						$str .= '金币:'.$gameInfo['money']."&nbsp;&nbsp;";
						$str .= '保险箱:'.$gameInfo['freezemoney']."&nbsp;&nbsp;";
						$str .= "<br/>";
						$result['m_bet'.$i] = $str;
					}
				}
			break;
		}
		
		return $result;
	}
	
	public function update($data){
		$type = $data['type'];
		switch ($type) {
			case 1://龙虎斗
				$key = "ServerDragonTable10";
			break;
			case 2://斗牛百人
				$key = "ServerBullTable81";
			break;
			
			default:
				;
			break;
		}
		
		$status = Loader_Redis::server()->hGet($key, 'status');//1 空闲  2 下注  3 结算
		
		if( $status != 2){
			//return false;
		}
		
		$num = Helper::uint($data['num']);
		
		Loader_Redis::server()->hSet($key, "result", $num);
		
		return true;
	}
	
	
	
	
	
}