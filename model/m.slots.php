<?php !defined('IN WEB') AND exit('Access Denied!');
//数值掩码
define("LOGIC_MASK_COLOR",			0xF0)	;							//花色掩码
define("LOGIC_MASK_VALUE",			0x0F)	;							//数值掩码

//扑克类型
define("OX_VALUE0",					 0);									//混合牌型
define("OX_THREE_SAME",				102);									//三条牌型
define("OX_FOUR_SAME",				103);									//炸弹牛
define("OX_FIVEKING",				105);									//五花牛
define("OX_FIVESMALL",				106);								    //五小牛

define("MAX_COUNT",					  5);									//牌的最大数目

class M_Slots {

	private static $m_cbCardListData = array
			(
			0x01,0x02,0x03,0x04,0x05,0x06,0x07,0x08,0x09,0x0A,0x0B,0x0C,0x0D,	//方块 A - K
			0x11,0x12,0x13,0x14,0x15,0x16,0x17,0x18,0x19,0x1A,0x1B,0x1C,0x1D,	//梅花 A - K
			0x21,0x22,0x23,0x24,0x25,0x26,0x27,0x28,0x29,0x2A,0x2B,0x2C,0x2D,	//红桃 A - K
			0x31,0x32,0x33,0x34,0x35,0x36,0x37,0x38,0x39,0x3A,0x3B,0x3C,0x3D	//黑桃 A - K	
			);
			
	private static $_instance;

	public static function  factory(){
		if(!is_object(self::$_instance)){
			self::$_instance = new M_Slots();
		}
		
		return self::$_instance;
	}
			
	
	private function getCardLogicValue($cbCardData){
		//扑克属性
		$bCardColor=$this->getCardColor($cbCardData);
		$bCardValue=$this->getCardValue($cbCardData);
	
		//转换数值
		return ($bCardValue>10)? 10 : $bCardValue;
	}
	
	private function getCardValue($cbCardData){
		return $cbCardData & LOGIC_MASK_VALUE;
	}
	
	private function getCardColor($cbCardData){
		return $cbCardData & LOGIC_MASK_COLOR;
	}

	public function getCardType(array $cbCardData, $cbCardCount){
		//五小牛判断
		$bCount     = $bNum = 0;
		$bTempCardDate = array();

		foreach ($cbCardData as $card) {
			$bTempCount = $this->getCardLogicValue($card);
			$bCount += $bTempCount;
		}

		foreach ($cbCardData as $card) {
			if($this->getCardValue($card) < 5)
			{
				$bNum++;
			}
		}
		
		if($bNum == 5 && $bCount <= 10)
		{
			return OX_FIVESMALL;
		} 

		$bTempCardDate = $cbCardData;

		//五花牛判断
		$bKingCount = $bTenCount = 0;
		foreach ($bTempCardDate as $card) {
			if($this->getCardValue($card)>10)
			{
				$bKingCount++;
			}
			else if($this->getCardValue($card)==10)
			{
				$bTenCount++;
			}
		}
		
		if($bKingCount==MAX_COUNT)
		{
			return OX_FIVEKING;
		} 
	
		////炸弹牌型
		$bSameCount   = 0;
		$bSecondValue = $this->getCardValue($bTempCardDate[1]);
		
		foreach ($bTempCardDate as $card) {
			if($bSecondValue == $this->getCardValue($card))
			{
				$bSameCount++;
			}
		}

		if($bSameCount==4)
		{
			return OX_FOUR_SAME;
		} 

		//无牛-牛牛
		$bTemp = array();
		$bSum  = 0;	
		$i     = 0;	
		foreach($cbCardData as $v)
		{
			$bTemp[$i]=$this->getCardLogicValue($v);
			$bSum+=$bTemp[$i];
			$i++;
		}

		for ($i=0;$i<$cbCardCount-1;$i++)
		{
			for ($j=$i+1;$j<$cbCardCount;$j++)
			{
				if(($bSum-$bTemp[$i]-$bTemp[$j])%10==0)
				{
					return (($bTemp[$i]+$bTemp[$j])>10)?($bTemp[$i]+$bTemp[$j]-10):($bTemp[$i]+$bTemp[$j]);
				}
			}
		}

		return OX_VALUE0;
	}
	
	//获取特定扑克
	public function randCardSpecialType(array &$cbCardBuffer, &$bType)
	{
	
		if($bType <= 10)//牛牛及牛牛以下的牌
		{
			$this->randCardSpecialValue($cbCardBuffer,$bType);
			return;
		}
		else if($bType == OX_FOUR_SAME)//四炸
		{
			$this->randCardSpecialFourType($cbCardBuffer);	
			return;
		}
		else if ($bType == OX_FIVEKING)//五花牛
		{
			$this->randCardSpecialKingType($cbCardBuffer);	
			return;
		}
		else if ($bType == OX_FIVESMALL)//五小牛
		{
			$this->randCardSpecialSmallType($cbCardBuffer);	
			return;
		}
		
	}
	
	//补牌
	public function discountedOuts($cbCardBuffer,$unsetCard){
		$bRestCardList = $cardTypes = array();
		
		//处理补牌的情况
		if($cbCardBuffer){
			$bRestCardList = array_diff(self::$m_cbCardListData, $cbCardBuffer);
		}

		foreach ($bRestCardList as $card) {
			if($unsetCard == $card){
				continue;
			}
			$tmpCard = $cbCardBuffer;
			array_push($tmpCard,$card);
			$cardType = $this->getCardType($tmpCard,5);
			$cardTypes[$cardType] = $tmpCard;
		}
		
		krsort($cardTypes);
		return $cardTypes;
	}
	
	public function randCardSpecialValue(array &$cbCardBuffer,&$bType){
		$cbTypeCard  = $bRestCardList = array();
		$bStackCount = 0;
		$i = $j = 0;
			
		$bRestCardList = self::$m_cbCardListData;
		

		while (true)
		{
			if($bStackCount > 100)
			{
				$bType = $this->getCardType($cbTypeCard,5);
				$cbCardBuffer = $cbTypeCard;//如果取100次都取不了特定的牌型 则返回最后一次
				Logs::factory()->debug(array('bType'=>$bType,'bStackCount'=>$bStackCount,'cbTypeCard'=>$cbTypeCard),'randCardSpecialValue');
				break;
			}
					
			for($i = 51; $i > 0; --$i)
			{
				$j = mt_rand()%$i;
				$bTemp = $bRestCardList[$i];
				$bRestCardList[$i] = $bRestCardList[$j];
				$bRestCardList[$j] = $bTemp;
			}
			$cbTypeCard = array_slice($bRestCardList, 0,5);
	
			$bStackCount++;
			if($this->getCardType($cbTypeCard,5) == $bType)
			{
				$cbCardBuffer = $cbTypeCard;
				break;
			}
		}
	}
	
	//生成四炸
	public function randCardSpecialFourType(array &$cbCardBuffer)
	{

		$fournum = mt_rand()%13 + 1;
		$bStackCount = 0;
		
		while (true) 
		{
			$cbTypeCard  = $bRestCardList = array();
			$i = $j = $index = 0;
			if($bStackCount > 20){//可能取到五小牛的情况，如果这样直取到炸弹为止，最多运行20次
				break;
			}
			
			for($i = 0; $i < 52; $i++)
			{
				if($this->getCardValue(self::$m_cbCardListData[$i]) == $fournum)
				{
					$cbTypeCard[$index++] = self::$m_cbCardListData[$i];
				}
				else
				{
					$bRestCardList[$j++] = self::$m_cbCardListData[$i];
				}
			}
			
			$rand = mt_rand(0, 46);
			array_push($cbTypeCard, $bRestCardList[$rand]);
			$bStackCount ++;

			if($this->getCardType($cbTypeCard,5) == OX_FOUR_SAME)
			{
				break;
			}
		}
		
		$cbCardBuffer = $cbTypeCard;
				
		for($i = 4; $i > 0; --$i)
		{
			$j = mt_rand()% $i;
			$bTemp = $cbCardBuffer[$i];
			$cbCardBuffer[$i] = $cbCardBuffer[$j];
			$cbCardBuffer[$j] = $bTemp;
		}
	}
	
	public function randCardSpecialKingType(array &$cbCardBuffer)
	{
		$cbTypeCard  = $bRestCardList = $cbCardArray = array();
		$bStackCount = 0;
	
		$i = $j = $index = 0;

		for($i = 0; $i < 52; $i++)
		{
			if($this->getCardValue(self::$m_cbCardListData[$i]) > 10)
			{
				$cbCardArray[$index++] = self::$m_cbCardListData[$i];
			}
		}
	
		for($i = 11; $i > 0; --$i)
		{
			$j = mt_rand() % $i;
			$bTemp = $cbCardArray[$i];
			$cbCardArray[$i] = $cbCardArray[$j];
			$cbCardArray[$j] = $bTemp;
		}
		
		$cbTypeCard = $cbCardArray;

		$index = 0;
		for($i = 0; $i < 52; $i++)
		{
			if(    self::$m_cbCardListData[$i] != $cbTypeCard[0] && self::$m_cbCardListData[$i] != $cbTypeCard[1]
				&& self::$m_cbCardListData[$i] != $cbTypeCard[2] && self::$m_cbCardListData[$i] != $cbTypeCard[3]
				&& self::$m_cbCardListData[$i] != $cbTypeCard[4])
			{
				$bRestCardList[$index++] = self::$m_cbCardListData[$i];
			}
		}
		
		$cbCardBuffer = array_slice($cbTypeCard,0,5);
		
		if($this->getCardType($cbCardBuffer,5) != OX_FIVEKING)
		{
			return false;
		}
	}
	
	public function randCardSpecialSmallType(array &$cbCardBuffer)
	{
		$cbTypeCard = $cbCardArray = $bRestCardList = array();
		$bStackCount = 0;
	
		$i = $j = $index = 0;

		for($i = 0; $i < 52; $i++)
		{
			if($this->getCardValue(self::$m_cbCardListData[$i]) < 5)
			{
				$cbCardArray[$index++] = self::$m_cbCardListData[$i];
			}
		}
		
		while (true)
		{
			if($bStackCount > 50)//结果相加可能会大于10，所以要运行多次
				break;
			for($i = 15; $i > 0; --$i)
			{
				$j = mt_rand()%$i;
				$bTemp = $cbCardArray[$i];
				$cbCardArray[$i] = $cbCardArray[$j];
				$cbCardArray[$j] = $bTemp;
			}
			
			$cbTypeCard = $cbCardArray;
			
			$cbCardBuffer = array_slice($cbTypeCard,0,5);

			if($this->getCardType($cbCardBuffer,5) == OX_FIVESMALL)
			{
				break;
			}
				
			$bStackCount++;
		}
	}	
	
	public function bet($mid,$betMoney, $gameid, $sid, $cid, $pid, $ctype)
	{
		$lastCardInfo     = Loader_Redis::common()->hGetAll(Config_Keys::ffl($mid));
		$systemCoinPool   = Loader_Redis::common()->get(Config_Keys::fflcoin(),false,false);
		
		if($lastCardInfo['status'] && $lastCardInfo['status'] != 4){
			$this->settlement($mid, $gameid, $sid, $cid, $pid, $ctype);//状态不对，则自动结算
			//return false;
		}
		
		if($lastCardInfo['betTime']){//已经下注，但没结算
			$this->settlement($mid, $gameid, $sid, $cid, $pid, $ctype);//状态不对，把上次的先结算
			//return false;
		}
		
		if($lastCardInfo['winMoney'] > 5000000)
		{
			foreach (Config_Slots::$startGamesProbability['win'] as $winMoney=>$config) {
				if($lastCardInfo['winMoney']<=$winMoney)
				{
					$probability = $config;
					break;
				}
			}
		}
			
		if($lastCardInfo['winMoney'] < 0 && abs($lastCardInfo['winMoney']) > 10000000)
		{
			
			foreach (Config_Slots::$startGamesProbability['lost'] as $lostMoney=>$config) {
				if(abs($lastCardInfo['winMoney'])<=$lostMoney)
				{
					$probability = $config;
					break;
				}
			}
		}

		if($systemCoinPool < 0 && abs($systemCoinPool) > 1500000)
		{
			$probability = Config_Slots::$startGamesProbability['systemlost'];
		}
				
		if(!$probability)
		{
			$probability = Config_Slots::$startGamesProbability['normal'];
		}
		
		$rand = mt_rand() % 100 + 1;
		$tmp = 0;

		foreach ($probability as  $card =>$pro) {
			$tmp = $tmp + $pro;
			if($rand <= $tmp){
				$cardType = (int)$card;
				break;
			}
		}
		
		if($cardType >= 8){//控制下注
			$all_systemCoinPool = (int)Loader_Redis::common()->get(Config_Keys::ffa(),false,false);
			if( $all_systemCoinPool < 0 || ((Config_Slots::$odds[$cardType] * $betMoney) > ($all_systemCoinPool + 5000000))){
				global $param;
				Logs::factory()->debug(array('mid'=>$param['mid'],'cardType'=>$cardType,'all_systemCoinPool'=>$all_systemCoinPool,'reward'=>Config_Slots::$odds[$cardType] * $betMoney),'cron_bet');
				$cardType = mt_rand(1, 7);
			}
		}
		
		Logs::factory()->debug(array('probability'=>$probability,'mid'=>$mid,'betMoney'=>betMoney,'rand'=>$rand,'card'=>$cardType,'lastCardInfo'=>$lastCardInfo),'bet');
		
		$cbCardBuffer = array();
		$this->randCardSpecialType($cbCardBuffer, $cardType);
		
		$cbCardBuffer = implode(',', $cbCardBuffer);
		$lastWinMoney = Loader_Redis::common()->hGet(Config_Keys::ffl($mid), 'winMoney');
				
		$info['betMoney']    = $betMoney;
		$info['cardType']    = $cardType;
		$info['cardInfo']    = $cbCardBuffer;
		$info['winMoney']    = date("Ymd",$lastCardInfo['betTime']) != date("Ymd",NOW) ? 0 : $lastWinMoney - $betMoney;
		$info['betSum']      = $betMoney;
		$info['status']      = 0;
		$info['rewardMoney'] = 0;
		$info['betTime']     = NOW;
		
		$flag = Logs::factory()->addWin($gameid, $mid, 31, $sid, $cid, $pid, $ctype, 1, $betMoney,json_encode($info));
		
		if(!$flag)
		{
			return false;
		}
		
		Loader_Redis::common()->incr(Config_Keys::fflcoin(),$betMoney,Helper::time2morning());//全局金币数据统计
		Loader_Redis::common()->incr(Config_Keys::ffa(),$betMoney,Helper::time2morning());//总池
		Loader_Redis::common()->hMset(Config_Keys::ffl($mid), $info,24*3600);
		
		$info['rewardMoney'] = Config_Slots::$odds[$info['cardType']] * $info['betMoney'];//本轮所得的金币数
		return $info;
	}
	
	public function changeCard($mid,$deductMoney,$unsetCard, $gameid, $sid, $cid, $pid, $ctype)
	{
		$info = Loader_Redis::common()->hGetAll(Config_Keys::ffl($mid));
		$stats = $info['status'];
		
		if($stats >= 3){
			return false;
		}

		$info['cardInfo'] = explode(',', $info['cardInfo']);
		if(!in_array($unsetCard,$info['cardInfo'])){
			return false;
		}
				
		$newCardInfo = $this->calNewCards($info, $unsetCard,$mid);
		$newcard     = end($newCardInfo['cardInfo']);
						
		Logs::factory()->debug(array('oldcardinfo'=>$info,'newcardinfo'=>$newCardInfo,'newcard'=>$newcard),'changecard');
		
		$info['cardType'] = $newCardInfo['cardType'];
		$info['betSum']   = $info['betSum'] + $deductMoney;
		$info['cardInfo'] = implode(",", $newCardInfo['cardInfo']);
		$info['winMoney'] = $info['winMoney'] - $deductMoney;
		$info['status'] ++ ;
		
		$flag = Logs::factory()->addWin($gameid, $mid, 32, $sid, $cid, $pid, $ctype, 1, $deductMoney,json_encode($info));//扣钱
		
		$info['newCard'] = $newcard;
		
		if(!$flag)
		{
			return false;
		}
		
		Loader_Redis::common()->hMset(Config_Keys::ffl($mid), $info,24*3600);
		Loader_Redis::common()->incr(Config_Keys::fflcoin(),$deductMoney,Helper::time2morning());//全局金币数据统计
		Loader_Redis::common()->incr(Config_Keys::ffa(),$deductMoney,Helper::time2morning());//总池
		
		$info['rewardMoney'] = Config_Slots::$odds[$info['cardType']] * $info['betMoney'];//本轮所得的金币数
		return $info;
	}
	
	public function settlement($mid,$gameid, $sid, $cid, $pid, $ctype)
	{
		$info = Loader_Redis::common()->hGetAll(Config_Keys::ffl($mid));
		
		if($info['status'] == 4)
		{
			return false;
		}
		
		$cardType = $info['cardType'];
		
		$rewardMoney = Config_Slots::$odds[$cardType] * $info['betMoney'];

		$info['status']     = 4;
		$info['winMoney']   = $info['winMoney'] + $rewardMoney;

		$flag = Logs::factory()->addWin($gameid, $mid, 33, $sid, $cid, $pid, $ctype, 0, $rewardMoney,json_encode($info));
		
		if(!$flag)
		{
			return false;
		}
		
		$info['rewardMoney']   = $rewardMoney;
		
		Loader_Redis::common()->incr(Config_Keys::fflcoin(),-$rewardMoney,Helper::time2morning());//全局金币数据统计
		Loader_Redis::common()->incr(Config_Keys::ffa(),-$rewardMoney,Helper::time2morning());//总池
		Loader_Redis::common()->hMset(Config_Keys::ffl($mid), $info,24*3600);
		$info['betSum'] = $info['betSum'];
		
		return $info;
	}
	
	private function calRate($rates,$rand)
	{
		$tmep = 0;
		foreach ($rates as $flag => $rate) 
		{
			$tmep = $tmep + $rate;
			if($rand <= $tmep)
			{
				return $flag;
			}
		}
	}
	
	private function sortCardTypes($oldCardType,$newCardTypes,$flag,$min=0)
	{	
		$biggerCardTypes  = array();
		$smallerCardTypes = array();
		$equalCardTypes   = array();		
		foreach ($newCardTypes as $value) 
		{
			if($value > $oldCardType && $value >= $flag)
			{
				$biggerCardTypes[]  = $value;
			}
			elseif($value < $flag && $value >= $min )
			{
				$equalCardTypes[]   = $value;
			}
			else
			{
				$smallerCardTypes[] = $value;
			}
		}
		
		$smallerCount = count($smallerCardTypes);
		$equalCount   = count($equalCardTypes);
		$biggerCount  = count($biggerCardTypes);
		
		//取一个大于之前牌型的牌型
		if($biggerCount > 0)
		{

			$biggerCardType = $biggerCardTypes[rand(0,($biggerCount-1))];
			
			if($biggerCardType == 103)//控制炸弹牛出现的次数
			{
				$four_same_flag = Loader_Redis::common()->get(Config_Keys::ffllimit(103),false,false);
				$four_same_flag = 1;
			}
						
			if($biggerCardType == 105)//控制五花牛出现的次数
			{
				$five_king_flag = Loader_Redis::common()->get(Config_Keys::ffllimit(105),false,false);
			}
			
			if($biggerCardType == 106){//控制五小牛出现的次数
				$five_small_flag = Loader_Redis::common()->get(Config_Keys::ffllimit(106),false,false);
			}
			
			if($five_king_flag || $five_small_flag || $four_same_flag){
				if($equalCount > 0)
				{
					$biggerCardType = $equalCardTypes[0];
				}
				elseif($smallerCount > 0)
				{
					$biggerCardType = $smallerCardTypes[0];
				}
	
				Logs::factory()->debug(array('five_king_flag'=>$five_king_flag,'five_small_flag'=>$five_small_flag,'four_same_flag'=>$four_same_flag,'biggertypes'=>$biggerCardTypes,'eq'=>$equalCardTypes,'small'=>$smallerCardTypes,'new'=>$biggerCardType),'reset_flag');
			}

			if($biggerCardType == 105){
				Loader_Redis::common()->set(Config_Keys::ffllimit($biggerCardType), 1,false,false,24*3600);//五花牛12个小时内只能出现一次
			}
			
			if($biggerCardType == 106){
				Loader_Redis::common()->set(Config_Keys::ffllimit($biggerCardType), 1,false,false,24*3600);//五小牛24小时内只能出现一次
			}
			
			if($biggerCardType == 103){
				Loader_Redis::common()->set(Config_Keys::ffllimit($biggerCardType), 1,false,false,24*3600);//五小牛2小时内只能出现一次
			}
		}
		elseif($equalCount > 0)//如果没有则取等于的
		{
			$biggerCardType = $equalCardTypes[0];
		}
		else//等于没有再找小于的
		{
			$biggerCardType = $smallerCardTypes[0];
		}

		//取一个等于之前牌型的牌型
		if($equalCount > 0)
		{
			$equalCardType  = $equalCardTypes[rand(0,($equalCount-1))];
		}
		elseif(count($smallerCardTypes) > 0) 
		{
			$equalCardType  = $smallerCardTypes[rand(0,($smallerCount-1))];
		}
		else 
		{
			$equalCardType = end($biggerCardTypes);
		}
					
		//取一个小于之前牌型的牌型	
		if($smallerCount > 0)
		{
			$smallerCardType  = $smallerCardTypes[rand(0,($smallerCount-1))];
		}
		elseif($equalCount > 0)
		{
			$smallerCardType  = $equalCardTypes[rand(0,($equalCount-1))];

		}else
		{
			$smallerCardType  = $biggerCardTypes[rand(0,($biggerCount-1))];

		}

		return array($biggerCardType,$equalCardType,$smallerCardType);
	}
	
	private function calNewCards($betinfo,$unsetCard,$mid)
	{
		$betinfo['cardInfo'] = array_diff($betinfo['cardInfo'], array($unsetCard));
		
		$newCardInfos = $this->discountedOuts($betinfo['cardInfo'], $unsetCard);//换牌后能取得的所有牌型

		$newCardTypes = array_keys($newCardInfos);
				
		$rand = mt_rand() % 100 + 1;
		
		$lastCardInfo     = Loader_Redis::common()->hGetAll(Config_Keys::ffl($mid));
		$systemCoinPool   = Loader_Redis::common()->get(Config_Keys::fflcoin(),false,false);
		
		$compareMoney = 0;
		
		if($lastCardInfo['winMoney'] > 4000000)
		{
			$probability  = Config_Slots::$changeCardProbability['win'];
			$compareMoney = $betinfo['winMoney'];
		}
			
		if($lastCardInfo['winMoney'] < 0 && abs($lastCardInfo['winMoney']) > 7000000)
		{
			$probability  = Config_Slots::$changeCardProbability['lost'];
			$compareMoney = $betinfo['winMoney'];
		}
		
		if($systemCoinPool < 0 && abs($systemCoinPool) > 2000000)//系统输金币
		{
			$probability = Config_Slots::$changeCardProbability['win'];
		}
		
		if(!$probability)
		{
			$probability  = Config_Slots::$changeCardProbability['normal'];
			$compareMoney = $betinfo['betMoney'];
		}
				
		foreach ($probability as $key=>$rates) {
			if($compareMoney <= $key){
				$flag = $this->calRate($rates, $rand);//根据概率得到 "> = <"

				if($betinfo['cardType'] < 1)//没牛
				{	
					$sortCards = $this->sortCardTypes($betinfo['cardType'],$newCardTypes, 1,0);
					
				}
				elseif($betinfo['cardType'] < 5)//牛丁到牛四
				{
					$sortCards = $this->sortCardTypes($betinfo['cardType'],$newCardTypes, 5,1);
				}
				elseif($betinfo['cardType'] < 8)//牛五到牛七
				{
					$sortCards = $this->sortCardTypes($betinfo['cardType'],$newCardTypes, 8,5);
				}
				elseif($betinfo['cardType'] < 10)//牛八牛九
				{
					$sortCards = $this->sortCardTypes($betinfo['cardType'],$newCardTypes, 10,8);
				}
				elseif($betinfo['cardType'] < 103)//牛牛
				{
					$sortCards = $this->sortCardTypes($betinfo['cardType'],$newCardTypes, 103,10);
				}
				elseif($betinfo['cardType'] < 105)//炸弹牛
				{
					$sortCards = $this->sortCardTypes($betinfo['cardType'],$newCardTypes, 105,103);
				}
				elseif($betinfo['cardType'] < 106)//五花牛
				{
					$sortCards = $this->sortCardTypes($betinfo['cardType'],$newCardTypes, 106,105);
				}else //五小牛
				{
					$sortCards = $this->sortCardTypes($betinfo['cardType'],$newCardTypes, 107,106);
				}
				
				if($flag == '>' )
				{
					$newCardType = $sortCards[0];
				}
				elseif($flag == '=')
				{
					$newCardType = $sortCards[1];
				}
				else
				{
					$newCardType = $sortCards[2];
				}
				return array('cardType' =>$newCardType,'cardInfo'=>$newCardInfos[$newCardType]);
			}
		}
	}
			
}