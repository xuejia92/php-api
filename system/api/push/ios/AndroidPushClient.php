<?php
/**
 * android 推送处理类
 *
 */
class AndroidPushClient {
	
	public $gurl;		//google android send notiftion
	private $auth_key;	//google auth service key
	public static $curl_options = array(
									CURLOPT_TIMEOUT			=>	60,
									CURLOPT_CONNECTTIMEOUT	=> 	10,
									CURLOPT_POST			=>	TRUE,
									CURLOPT_RETURNTRANSFER	=>	TRUE
									);
	public $icoins = 0;
	public $acoins = array(
						"pay"	=>	3000,	//付费用户推送3000金币
						"bukpt"	=>	1000	//破产用户送1000金币
						);
	public $stype = '';
	public $gameid = 1;
	
	/**
	 * 构造函数
	 *
	 * @param $gid
	 * @param string $type
	 */
	public function __construct($gid=1,$type=''){
		$this->gurl = "https://android.apis.google.com/c2dm/send";
		$this->auth_key = "DQAAAM0AAABbWt-4xo5-ieHHdDg2yxr-KdnLIrqTpiSfMMwX1exwM-nI4bb0l682m_RCUZDZAtEtA13EM3SpnruVXMbAyiHeIVWkVgXUrQ8OaJ2KFpyxPaaGvIfr-n3Oqavv7V1U0AF6IZjKnIKyPAQ2KpDaLGCRFF5-SRQX4M2hT1M-q3bKRUi8GIBeb-_tyS56WvGVKiMoWTZsJMCJBc9tX9uFXZI8tbZpZEmsp4O7kn4XwG2i-hdiV9azkDR37ap4Gko3tdgOVeIRvAoUNP4sjdJUQAeS"; 
		$this->gameid = $gid;
		
		if(!empty($type)){
			$this->icoins = array_key_exists($type,$this->acoins) ? intval( $this->acoins[$type]) : 0;
			$this->stype = $type;
		}
	}
	
	/**
	 * 返回推送消息
	 * @param $info 客外参数
	 * @return string
	 */	
	public function getPushInfo($info=''){
		
		$gameid = functions::uint( $this->gameid);
		$hour = date('G');	//0-23
		$type = $this->stype;
		
		$ainfo[1][0] = array("锄禾日当午，啥都不靠谱，闲来没事做，手机斗地主","泡妞太贵，唱歌太累，还是博雅斗地主最实惠","晚上上博雅斗地主玩一玩，睡觉就是香。今晚你上了么？","{$info}带你开房斗地主","自从玩了博雅斗地主，腰不酸了，腿不疼了，走路更有劲了，吃嘛嘛香！你还等啥，赶紧来吧。");
		$ainfo[1][1] = array("昨晚没睡好，早上醒得太早？是地主在学鸡叫！快来打败他吧~明天睡个好觉！");
		$ainfo[1][2] = array("李白乘舟将欲行，忽闻岸上比赛声，桃花潭水深千尺，为斗地主不怕死。","不在博雅斗地主中“暴富”、就在博雅斗地主中“暴负”，精彩比赛挑战你的极限敢来不敢来！","你快回来、一个人始终玩不来，你快回来！比赛有你才精彩。","想赢你就斗，爱斗才会赢！110000金币等你拿！","居家不出门，网上邀高人, 新人王选拔赛即将开始！","龙争虎“斗” 胜者为王 , 110000金币等着你");
		$ainfo[1][3] = array("日照香炉生紫烟，3000金币在眼前。拇指轻点真悠闲，不羡鸳鸯不羡仙","吉星高照！系统派送了3000金币，快来拿吧！","吉星高照！系统派送了3000金币，点击领取！","吉星高照！刚刚获得系统派送的3000金币。","吉星高照！刚刚获得系统派送的3000金币。");
		$ainfo[1][4] = array("垂死病中惊坐起，今日奖励未领取","春眠不觉晓，领奖要趁早","鸿运当头，收到系统派送的1000金币，快来拿吧！","你走运啦！收到系统派送的1000金币，点击领取！","亲~你走运了！快来拿钱吧！");
		$ainfo[2][0] = array("锄禾日当午，啥都不靠谱，闲来没事做，手机锄大地","泡妞太贵，唱歌太累，还是博雅锄大地最实惠","晚上上博雅锄大地玩一玩，睡觉就是香。今晚你上了么？","{$info}带你开房鋤大地","自从玩了博雅鬥地主，腰不酸了，腿不疼了，走路更有劲了，吃嘛嘛香！你还等啥，赶紧来吧。");
		
		$apush = $ainfo[$gameid][0];
		/*各种条件*/
		in_array($hour,array(9,10,11)) && $apush = $ainfo[$gameid][1];
		in_array($hour,array(20,21,22)) && $apush = $ainfo[$gameid][2];
		$type=="pay" && $apush = $ainfo[$gameid][3];
		$type=="bukpt" && $apush = $apush[$gameid][4]; 
		
		$randkey = array_rand($apush,1);
		return $apush[$randkey];
	}
	/**
	 * 推送消息
	 *
	 * @param string $rid
	 * @param $gameid 游戏 1 斗地主 2 锄大地
	 * @return unknown
	 */
	public function pushAndroidInfo($rid){
		if(empty($rid)){
			return false;
		}
		//curl_setopt参数
		$options = self::$curl_options;
		$options[CURLOPT_URL] = $this->gurl;
		
		$info = $this->getPushInfo();	//随机取一条消息
		$icoins = $this->icoins;		//推送是否有金币赠送
		
		//post参数
		$params = array(
			"registration_id"	=>	$rid,
			"collapse_key"		=>	1,
			"data.sender"		=>	$info,
			"data.coins"		=>	$icoins,		//预留 如果送金币的话就>0
			"Email"				=>	"appleboyaa@gmail.com",
			"Passwd"			=>	"boyaa123"
		);
		oo::logs()->debug($params,"androidpush.txt");
		$postdata = http_build_query($params,null,"&");
		$options[CURLOPT_POSTFIELDS] = $postdata;
	
		//http header
		$headers = array();
		$headers[] = "Content-Length: " . strlen($postdata);
		$headers[] = "Authorization: GoogleLogin auth=" . $this->auth_key;
	
		$options[CURLOPT_HTTPHEADER] = $headers;
	
		//debug
		//$options[CURLOPT_HEADER] = TRUE;
		//$options[CURLOPT_VERBOSE] = TRUE;
		$ch = curl_init();
		if(!function_exists('curl_setopt_array')){
			foreach((array)$options as $key=>$value){
				curl_setopt($ch, $key, $value);
			}
		} else {
			curl_setopt_array( $ch,$options);
		}
		$result = curl_exec($ch);
		curl_close( $ch);
		if($result){
			oo::logs()->debug($result,"androidpush.txt");
			$tmp = explode("=",$result);
			return ($tmp[0]=="id") ? true : false;
		}else{
			return false;
		}
	}
	
}

?>