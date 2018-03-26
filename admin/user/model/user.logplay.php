<?php !defined('IN WEB') AND exit('Access Denied!');

class User_Logplay extends Config_Table{
	
	private static $_instances;
	
	public static function factory(){
		if(!self::$_instances){
			self::$_instances = new User_Logplay();
		}
		
		return self::$_instances;
	}
	
	public function getBoardDetail($data){
		
		$gameid  = $data['gameid'];
		//获取牌局的ID
		$boardid = $data['boardid'] ? $data['boardid'] : "";
		
		switch ($gameid) {
			case 1:
				$ret = self::showhand($boardid);
				return $ret;
				break;
			case 3:
				$ret = self::landlord($boardid);
				return $ret;
				break;
			case 4:
				$ret = self::bullfight($boardid);
				return $ret;
				break;
			break;
			case 6:
				$ret = self::poker($boardid);
				return $ret;
				break;
			case 7:
				$ret = self::flower($boardid);
				return $ret;
				break;
			default:	
			default:
				;
			break;
		}
	}
	
	private static function showhand($boardid){
		$behaviors = array(
		              "[S]"=>"游戏开始",
					  "[U]"=>"用户",
					  "[C]"=>"跟注",
					  "[R]"=>"加注",
					  "[A]"=>"梭哈",
					  "[L]"=>"看牌",
					  "[LE]"=>"逃跑",
					  "[T]"=>"弃牌",
					  "[LG]"=>"掉线",
					  "[G]"=>"获取牌面信息",
					  "[O]"=>"游戏结束",
					  "[TO]"=>"下注超时",
					  );

		$v	 = explode('|',$boardid);
		//获取牌局日志所在的文件夹
		$fileName = $v[0];
		$fileName = substr($fileName,0,8);
		//获取日志文件名
		$logName  = str_replace("|","_",$boardid);
		
		//逐行读取
		$logContent = array();
		$file = fopen("http://log.play.com/Record/".$fileName."/".$logName, "r") or exit("Unable to open file!");
		while(!feof($file))
		{
			$logContent[] = fgets($file);
		}
		fclose($file);
		
		$cardInfo = $logContent[0];//牌局主要信息
		$playSub  = array_slice($logContent, 1,-1);//行牌过程
		$endInfo  = end($logContent);//结算信息
		
		$members = $mnicks = $subPlay = $ending = array();

		$array = explode(" ", $cardInfo);
		$titleInfo = '';
		$time = $array[1];
		$t = explode(":", $time);
		$titleInfo .= '开始时间:'.date("Y-m-d h:i:s",$t[1])."&nbsp;&nbsp;";
		$info = $array[2];
		$s = explode(":", $info);
		$info = $s[1];
		$a = explode("_",$info);
		$titleInfo .= "服务器ID:".$a[0]."&nbsp;&nbsp;";
		$titleInfo .= "房间等级:".$a[2]."&nbsp;&nbsp;";
		$titleInfo .= "底注:".$a[3]."&nbsp;&nbsp;";
		$titleInfo .= "台费:".$a[4]."&nbsp;&nbsp;";
		
		
		$array = array_slice ( $array ,  3 ); 
		foreach ($array as $k=>$arr){
			$cards = explode("_", $arr);
			$temp  = explode(":", $cards[0]);
			$mid   = $temp[1];
			$members[$mid][] = $cards[5];
			$members[$mid][] = $cards[6];
			$members[$mid][] = $cards[7];
			$members[$mid][] = $cards[8];
			$members[$mid][] = $cards[9];
			
			if($mid > 1000){
				$userInfo = Member::factory()->getOneById($mid);
				$mnicks[$mid]['mnicks'] = $userInfo['mnick'];
				$ip_arr = Lib_Ip::find($userInfo['ip']);
				$mnicks[$mid]['ip'] = implode($ip_arr, ' ');
			}else{
				$mnicks[$mid]['mnicks'] = '机器人';
			}
		}
		
		$i=0;
		foreach ($playSub as $key=>$sub) {
			$arv = explode(" ", $sub);
			$flag = $arv[0];
			
			$_t = explode(":", $arv[1]);
			
			$info = explode(":", $arv[2]);
			$s = explode("_", $info[1]);
			$mid = $s[0];

			switch ($flag) {
				case '[C]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>跟注</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;跟注金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[R]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>加注</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;加注金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[A]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>梭哈</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;梭哈金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[L]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>不叫</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[LE]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>逃跑</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;扣金币数：".$s[2];
				break;
				case '[T]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>弃牌</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[LG]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>掉线</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[G]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>获取牌面信息</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[TO]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>下注超时</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
			}
			$i++;
		}
		
		$e = explode("U:", $endInfo);
		$e = array_slice($e, 1);
		
		$_arr_tmp = explode(" ", $endInfo);
		$_arr1    = explode(":", $_arr_tmp[1]);
		$endtime  = $_arr1[1];
				
		foreach ($e as $v) {
			$str  = "";
			$arg  = explode("_", $v);
			$mid  = $arg[0];
			$str .= $endtime."s&nbsp;&nbsp;最终结束牌型：".$arg[1]."&nbsp;&nbsp";
			$str .= "输赢金币数:";
			$money =  $arg[1] > 0 ? "$arg[2]" : "$arg[2]";
			$str .= $money."&nbsp;&nbsp";
			$str .= "最新金币数:".$arg[3];
			$ending[$mid][] = $str;
		}
		return array($members,$mnicks,$subPlay,$ending,$titleInfo,$others);
	}
	
	private static function poker($boardid){
		$behaviors = array(
		              "[S]"=>"游戏开始",
					  "[U]"=>"用户",
					  "[C]"=>"跟注",
					  "[R]"=>"加注",
					  "[A]"=>"ALL IN",
					  "[L]"=>"看牌",
					  "[LE]"=>"逃跑",
					  "[T]"=>"弃牌",
					  "[LG]"=>"掉线",
					  "[G]"=>"获取牌面信息",
					  "[O]"=>"游戏结束",
					  "[TO]"=>"下注超时",
					  );

		$v	 = explode('|',$boardid);
		//获取牌局日志所在的文件夹
		$fileName = $v[0];
		$fileName = substr($fileName,0,8);
		//获取日志文件名
		$logName  = str_replace("|","_",$boardid);
		
		//逐行读取
		$logContent = array();
		$file = fopen("http://log.play.com/Texas/".$fileName."/".$logName, "r") or exit("Unable to open file!");
		while(!feof($file))
		{
			$logContent[] = fgets($file);
		}
		fclose($file);
		
		$cardInfo = $logContent[0];//牌局主要信息
		$playSub  = array_slice($logContent, 1,-1);//行牌过程
		$endInfo  = end($logContent);//结算信息
		
		$members = $mnicks = $subPlay = $ending = array();

		$array = explode(" ", $cardInfo);
		
		$titleInfo = '';
		$time = $array[1];
		$t = explode(":", $time);
		$titleInfo .= '开始时间:'.date("Y-m-d h:i:s",$t[1])."&nbsp;&nbsp;";
		
		$info = $array[2];
		$s = explode(":", $info);
		$info = $s[1];
		$a = explode("_",$info);
		$titleInfo .= "服务器ID:".$a[0]."&nbsp;&nbsp;";
		$titleInfo .= "房间等级:".$a[2]."&nbsp;&nbsp;";
		$titleInfo .= "大盲:".$a[3]."&nbsp;&nbsp;";
		$titleInfo .= "台费:".$a[4]."&nbsp;&nbsp;";
		
		$publicCard[] = $a[5];
		$publicCard[] = $a[6];
		$publicCard[] = $a[7];
		$publicCard[] = $a[8];
		$publicCard[] = $a[9];

		$array = array_slice ( $array ,  3 ); 
		foreach ($array as $k=>$arr){
			$cards = explode("_", $arr);
			$temp  = explode(":", $cards[0]);
			$mid   = $temp[1];
			$cards[5] && $members[$mid][] = $cards[5];
			$cards[6] && $members[$mid][] = $cards[6];
			$cards[7] && $members[$mid][] = $cards[7];
			$cards[8] && $members[$mid][] = $cards[8];
			$cards[9] && $members[$mid][] = $cards[9];
			$cards[10] && $members[$mid][] = $cards[10];
			$cards[11] && $members[$mid][] = $cards[11];
			
			if($mid > 1000){
				$userInfo = Member::factory()->getOneById($mid);
				$mnicks[$mid]['mnicks'] = $userInfo['mnick'];
				$ip_arr = Lib_Ip::find($userInfo['ip']);
				$mnicks[$mid]['ip'] = implode($ip_arr, ' ');
			}else{
				$mnicks[$mid]['mnicks'] = '机器人';
			}
		}
		
		$i=0;
		foreach ($playSub as $key=>$sub) {
			$arv = explode(" ", $sub);
			$flag = $arv[0];
			$_t = explode(":", $arv[1]);
			$info = explode(":", $arv[2]);
			$s = explode("_", $info[1]);
			$mid = $s[0];

			switch ($flag) {
				case '[C]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>跟注</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;跟注金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[R]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>加注</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;加注金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[A]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>ALL IN</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;梭哈金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[L]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>不叫</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[LE]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>逃跑</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;扣金币数：".$s[2];
				break;
				case '[T]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>弃牌</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[LG]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>掉线</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[G]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>获取牌面信息</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[TO]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>下注超时</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
			}
			$i++;
		}
		
		$e = explode("U:", $endInfo);
		$e = array_slice($e, 1);
		
		$_arr_tmp = explode(" ", $endInfo);
		$_arr1    = explode(":", $_arr_tmp[1]);
		$endtime  = $_arr1[1];
				
		foreach ($e as $v) {
			$str = "";
			$arg = explode("_", $v);
			$mid = $arg[0];
			$str .= $endtime."s&nbsp;&nbsp;最终结束牌型：".$arg[1]."&nbsp;&nbsp";
			$str .= "输赢金币数:";
			$money =  $arg[1] > 0 ? "$arg[2]" : "$arg[2]";
			$str .= $money."&nbsp;&nbsp";
			$str .= "最新金币数:".$arg[3];
			$ending[$mid][] = $str;
		}
		return array($members,$mnicks,$subPlay,$ending,$titleInfo,$publicCard);
	}
	
	private static function flower($boardid){
		$behaviors = array(
		              "[S]"=>"游戏开始",
					  "[U]"=>"用户",
					  "[C]"=>"跟注",
					  "[R]"=>"加注",
					  "[A]"=>"ALL IN",
					  "[L]"=>"看牌",
					  "[LE]"=>"逃跑",
					  "[T]"=>"弃牌",
					  "[LG]"=>"掉线",
					  "[G]"=>"获取牌面信息",
					  "[O]"=>"游戏结束",
					  "[TO]"=>"下注超时",
					  "[CP]"=>"比牌",
					  "[FB]"=>"禁比",
					  "[SM]"=>"翻倍",
					  );

		$v	 = explode('|',$boardid);
		//获取牌局日志所在的文件夹
		$fileName = $v[0];
		$fileName = substr($fileName,0,8);
		//获取日志文件名
		$logName  = str_replace("|","_",$boardid);
		
		//逐行读取
		$logContent = array();
		$file = fopen("http://log.play.com/FlowerLog/".$fileName."/".$logName, "r") or exit("Unable to open file!");
		while(!feof($file))
		{
			$logContent[] = fgets($file);
		}
		fclose($file);
		
		$cardInfo = $logContent[0];//牌局主要信息
		$playSub  = array_slice($logContent, 1,-1);//行牌过程
		$endInfo  = end($logContent);//结算信息
		
		$members = $mnicks = $subPlay = $ending = array();

		$array = explode(" ", $cardInfo);
		
		$titleInfo = '';
		$time = $array[1];
		$t = explode(":", $time);
		$titleInfo .= '开始时间:'.date("Y-m-d h:i:s",$t[1])."&nbsp;&nbsp;";
		
		$info = $array[2];
		$s = explode(":", $info);
		$info = $s[1];
		$a = explode("_",$info);
		$titleInfo .= "服务器ID:".$a[0]."&nbsp;&nbsp;";
		$titleInfo .= "房间等级:".$a[2]."&nbsp;&nbsp;";
		$titleInfo .= "大盲:".$a[3]."&nbsp;&nbsp;";
		$titleInfo .= "台费:".$a[4]."&nbsp;&nbsp;";

		$array = array_slice ( $array ,  3 ); 

		foreach ($array as $k=>$arr){
			$cards = explode("_", $arr);
			$temp  = explode(":", $cards[0]);
			$mid   = $temp[1];
			$cards[4] && $members[$mid][] = $cards[4];
			$cards[5] && $members[$mid][] = $cards[5];
			$cards[6] && $members[$mid][] = $cards[6];
			
			if($mid > 1000){
				$userInfo = Member::factory()->getOneById($mid);
				$mnicks[$mid]['mnicks'] = $userInfo['mnick'];
				$ip_arr = Lib_Ip::find($userInfo['ip']);
				$mnicks[$mid]['ip'] = implode($ip_arr, ' ');
			}else{
				$mnicks[$mid]['mnicks'] = '机器人';
			}
		}
		
		$i=0;
		foreach ($playSub as $key=>$sub) {
			$arv = explode(" ", $sub);
			$flag = $arv[0];
			$_t = explode(":", $arv[1]);
			$info = explode(":", $arv[2]);
			$s = explode("_", $info[1]);
			$mid = $s[0];

			switch ($flag) {
				case '[C]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>跟注</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;跟注金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[R]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>加注</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;加注金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[A]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>ALL IN</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;梭哈金币数：".$s[2]."&nbsp;&nbsp;总下注数：".$s[3];
				break;
				case '[L]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>看牌</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[LE]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>逃跑</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;扣金币数：".$s[2];
				break;
				case '[T]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>弃牌</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[LG]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>掉线</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[G]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>获取牌面信息</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[TO]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>下注超时</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[CP]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>比牌</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[FB]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>禁比</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
				case '[SM]':
					$subPlay[$i][$mid] = $_t[1]."s&nbsp;&nbsp;行为：<a style='color:red'>翻倍</a> &nbsp;&nbsp;第".$s[1]."轮&nbsp;&nbsp;总下注数：".$s[2];
				break;
			}
			$i++;
		}
		
		$e = explode("U:", $endInfo);
		$e = array_slice($e, 1);
		
		$_arr_tmp = explode(" ", $endInfo);
		$_arr1    = explode(":", $_arr_tmp[1]);
		$endtime  = $_arr1[1];
				
		foreach ($e as $v) {
			$str = "";
			$arg = explode("_", $v);
			$mid = $arg[0];
			$str .= $endtime."s&nbsp;&nbsp;最终结束牌型：".$arg[1]."&nbsp;&nbsp";
			$str .= "输赢金币数:";
			$money =  $arg[1] > 0 ? "$arg[2]" : "$arg[2]";
			$str .= $money."&nbsp;&nbsp";
			$str .= "最新金币数:".$arg[3];
			$ending[$mid][] = $str;
		}
		return array($members,$mnicks,$subPlay,$ending,$titleInfo,$publicCard);
	}
	
	private static function landlord($boardid){
		$behaviors = array(
		              "[C]"=>"叫地主",
					  "[GS]"=>"游戏开始",
					  "[OT]"=>"出牌",
					  "[P]"=>"过牌",
					  "[A]"=>"托管",
					  "[L]"=>"掉线",
					  "[T]"=>"弃牌",
					  "[G]"=>"获取牌面信息",
					  "[O]"=>"游戏结束",
					  );
	
		$v	 = explode('|',$boardid);
		//获取牌局日志所在的文件夹
		$fileName = $v[0];
		$fileName = substr($fileName,0,8);
		//获取日志文件名
		$logName  = str_replace("|","_",$boardid);
		
		//逐行读取
		$logContent = array();
		
		$file = fopen("http://log.play.com/LandLog/".$fileName."/".$logName, "r") or exit("Unable to open file!");
		while(!feof($file))
		{
			$logContent[] = fgets($file);
		}
		fclose($file);
		
		$cardInfo = $logContent[0];//牌局主要信息
		$playSub  = array_slice($logContent, 1,-1);//行牌过程
		$endInfo  = array_slice($logContent,-1,1);//结算信息

		$members = $mnicks = $others = $subPlay = $ending = array();

		$array = explode(" ", $cardInfo);
		$titleInfo = '';
		$time = $array[1];
		$t = explode(":", $time);
		$titleInfo .= '开始时间:'.date("Y-m-d h:i:s",$t[1])."&nbsp;&nbsp;";
		$info = $array[2];
		$s = explode(":", $info);
		$info = $s[1];
		$a = explode("_",$info);
		$titleInfo .= "服务器ID:".$a[0]."&nbsp;&nbsp;";
		$titleInfo .= "桌子ID:".$a[1]."&nbsp;&nbsp;";
		$titleInfo .= "房间等级:".$a[2]."&nbsp;&nbsp;";
		$titleInfo .= "底注:".$a[3]."&nbsp;&nbsp;";
		$titleInfo .= "台费:".$a[4]."&nbsp;&nbsp;";
		$titleInfo .= "基础倍数:".$a[5]."&nbsp;&nbsp;";
		$titleInfo .= "本局先叫地主用户ID:".$a[6]."&nbsp;&nbsp;";
		
		
		$array = array_slice ( $array ,  3 ); 
		foreach ($array as $k=>$arr){
			$cards = explode("_", $arr);
			$temp = explode(":", $cards[0]);
			$mid  = $temp[1];
			$others[$mid]['money']  = $cards[1];
			$others[$mid]['seatid'] = $cards[2];
			$others[$mid]['src']    = $cards[3];
			
			$aCard  =  array_slice ( $cards ,  4 );
			foreach ($aCard as $c) {
				$members[$mid][] = $c;
			}
			
			if($mid > 1000){
				$userInfo = Member::factory()->getOneById($mid);
				$mnicks[$mid]['mnicks'] = $userInfo['mnick'];
				$ip_arr = Lib_Ip::find($userInfo['ip']);
				$mnicks[$mid]['ip'] = implode($ip_arr, ' ');
			}else{
				$mnicks[$mid]['mnicks'] = '机器人';
			}
		}
		
		$i=0;
		foreach ($playSub as $key=>$sub) {
			$arv = explode(" ", $sub);
			$flag = $arv[0];
			
			$info = explode(":", $arv[1]);
			$s = explode("_", $info[1]);
			$mid = $s[1];

			switch ($flag) {
				case '[C]':
					$subPlay[$i][$mid]  = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>叫地主</a> &nbsp;&nbsp;状态：<a style='color:red'>";
					$status =  $s[2] == 0  ? '不抢':'抢';
					$subPlay[$i][$mid] .= $status."</a>&nbsp;&nbsp;当前倍数：".$s[3];
				break;
				case '[GS]':
					$subPlay[$i][$mid] = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>游戏开始</a> &nbsp;&nbsp;总倍数：".$s[5]."&nbsp;&nbsp;三张底牌：&nbsp;&nbsp;<img src='statics/poker/".substr($s[2], 2).".png'>&nbsp;&nbsp;<img src='statics/poker/".substr($s[3], 2).".png'>&nbsp;&nbsp;<img src='statics/poker/".substr($s[4], 2).".png'>";
				break;
				case '[OT]':
					$subPlay[$i][$mid] = "";
					$subPlay[$i][$mid] .= $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>出牌</a> &nbsp;&nbsp;";
					$subPlay[$i][$mid] .= "总倍数：".$s[2]."&nbsp;&nbsp;";
					$aCard  =  array_slice ( $s ,  5 );
					foreach ($aCard as $c) {
						if(trim($c)){
							$subPlay[$i][$mid] .= "<img src='statics/poker/$c.png'>&nbsp;&nbsp;";
						}
					}
				break;
				case '[P]':
					$subPlay[$i][$mid] = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>过牌</a> &nbsp;&nbsp;";
				break;
				case '[A]':
					$_aa = $s[2] == 0 ? '取消托管':'托管';
					$subPlay[$i][$mid] = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>托管</a> &nbsp;&nbsp;状态：".$_aa;
				break;
				case '[L]':
					$subPlay[$i][$mid] = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>掉线</a> &nbsp;&nbsp;";
				break;
				case '[G]':
					$subPlay[$i][$mid] = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>获取牌面信息</a> &nbsp;&nbsp;";
				break;
			}
			$i++;
		}
		
		$e = explode("U:", $endInfo[0]);
		$e = array_slice($e, 1);
		
		$_arr_tmp = explode(" ", $endInfo[0]);
		$_arr1    = explode(":", $_arr_tmp[1]);
		$endtime  = $_arr1[1];

		foreach ($e as $v) {
			$str = "";
			$arg = explode("_", $v);
			$mid = $arg[0];
			$str .= $endtime."s&nbsp;&nbsp;输赢金币：".$arg[1]."&nbsp;&nbsp";
			$str .= "最新金币:".$arg[2]."&nbsp;&nbsp";
			$str .= "魔法表情扣金币:".$arg[3]."&nbsp;&nbsp";
			$ending[$mid][] = $str;
		}

		return array($members,$mnicks,$subPlay,$ending,$titleInfo,$others);
	}
	
	private static function bullfight($boardid){
		$behaviors = array(
		              "[C]"=>"叫庄",
					  "[SM]"=>"设置倍数",
					  "[CB]"=>"确认庄家",
					  "[OC]"=>"开牌",
					  "[A]"=>"托管",
					  "[L]"=>"掉线",
					  "[G]"=>"获取牌面信息",
					  "[O]"=>"游戏结束",
					  );
	
		$v	 = explode('|',$boardid);
		//获取牌局日志所在的文件夹
		$fileName = $v[0];
		$fileName = substr($fileName,0,8);
		//获取日志文件名
		$logName  = str_replace("|","_",$boardid);
		
		//逐行读取
		$logContent = array();
		
		$file = fopen("http://log.play.com/BullLog/".$fileName."/".$logName, "r") or exit("Unable to open file!");
		while(!feof($file))
		{
			$logContent[] = fgets($file);
		}
		fclose($file);
		
		$cardInfo = $logContent[0];//牌局主要信息
		$playSub  = array_slice($logContent, 1,-1);//行牌过程
		$endInfo  = array_slice($logContent,-1,1);//结算信息

		$members = $mnicks = $others = $subPlay = $ending = array();

		$array = explode(" ", $cardInfo);
		$titleInfo = '';
		$time = $array[1];
		$t = explode(":", $time);
		$titleInfo .= '开始时间:'.date("Y-m-d h:i:s",$t[1])."&nbsp;&nbsp;";
		$info = $array[2];
		$s = explode(":", $info);
		$info = $s[1];
		$a = explode("_",$info);
		$titleInfo .= "服务器ID:".$a[0]."&nbsp;&nbsp;";
		$titleInfo .= "桌子ID:".$a[1]."&nbsp;&nbsp;";
		$titleInfo .= "房间等级:".$a[2]."&nbsp;&nbsp;";
		$titleInfo .= "底注:".$a[3]."&nbsp;&nbsp;";
		$titleInfo .= "台费:".$a[4]."&nbsp;&nbsp;";
		$titleInfo .= "人数:".$a[5]."&nbsp;&nbsp;";
		
		
		$array = array_slice ( $array ,  3 ); 
		foreach ($array as $k=>$arr){
			$cards = explode("_", $arr);
			$temp = explode(":", $cards[0]);
			$mid  = $temp[1];
			$others[$mid]['money']  = $cards[1];
			$others[$mid]['seatid'] = $cards[2];
			$others[$mid]['src']    = $cards[3];
			
			$aCard  =  array_slice ( $cards ,  4 );
			foreach ($aCard as $c) {
				$members[$mid][] = substr($c, 2);
			}
			
			if($mid > 1000){
				$userInfo = Member::factory()->getOneById($mid);
				$mnicks[$mid]['mnicks'] = $userInfo['mnick'];
				$ip_arr = Lib_Ip::find($userInfo['ip']);
				$mnicks[$mid]['ip'] = implode($ip_arr, ' ');
			}else{
				$mnicks[$mid]['mnicks'] = '机器人';
			}
		}
		
		$i=0;
		foreach ($playSub as $key=>$sub) {
			$arv = explode(" ", $sub);
			$flag = $arv[0];
			
			$info = explode(":", $arv[1]);
			$s = explode("_", $info[1]);
			$mid = $s[1];

			switch ($flag) {
				case '[C]':
					$subPlay[$i][$mid]  = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>叫庄</a> &nbsp;&nbsp;状态：<a style='color:red'>";
					$status =  $s[2] == 2  ? '不抢':'抢';
					$subPlay[$i][$mid] .= $status."</a>";
				break;
				case '[SM]':
					$subPlay[$i][$mid] = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>设置倍数</a> &nbsp;&nbsp;倍数：".$s[2];
				break;
				case '[CB]':
					$subPlay[$i][$mid] = "";
					$subPlay[$i][$mid] .= $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>确认庄家</a> &nbsp;&nbsp;";
					$subPlay[$i][$mid] .= "庄家ID：".$s[1]."&nbsp;&nbsp;";
				break;
				case '[OC]':
					$subPlay[$i][$mid] = "";
					$subPlay[$i][$mid] .= $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>开牌</a> &nbsp;&nbsp;";
					$status =  $s[2] == 0  ? '没牛':'有牛&nbsp;&nbsp;&nbsp;&nbsp牛'.$s[3];
					$subPlay[$i][$mid] .= $status;
				break;
				case '[L]':
					$subPlay[$i][$mid] = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>掉线</a> &nbsp;&nbsp;";
				break;
				case '[G]':
					$subPlay[$i][$mid] = $s[0]."s&nbsp;&nbsp;行为：<a style='color:red'>获取牌面信息</a> &nbsp;&nbsp;";
				break;
			}
			$i++;
		}
		
		$e = explode("U:", $endInfo[0]);
		$e = array_slice($e, 1);

		$_arr_tmp = explode(" ", $endInfo[0]);
		$_arr1    = explode(":", $_arr_tmp[1]);
		$endtime  = $_arr1[1];

		foreach ($e as $v) {
			$str = "";
			$arg = explode("_", $v);
			$mid = $arg[0];
			$str .= $endtime."s&nbsp;&nbsp;结束牌型:".$arg[1]."&nbsp;&nbsp";
			$str .= "输赢金币：".$arg[2]."&nbsp;&nbsp";
			$str .= "最新金币:".$arg[3]."&nbsp;&nbsp";
			$ending[$mid][] = $str;
		}
		
		return array($members,$mnicks,$subPlay,$ending,$titleInfo,$others);
	}
	
	
}