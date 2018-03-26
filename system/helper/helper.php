<?php !defined('IN WEB') AND exit('Access Denied!');
class Helper
{
	const COOKIE_KEY = "$%^&*(dianler^FDShfs7）（*&……%sdfds8dfg7ds";
	
	static $_Bcookie = array();
	
	static $cache    = '';
	
	/**
	 * @return Helper
	 */
	public static function uint($num)
	{
	    return max(0, (int)$num);
	}
	
	/**
	 * 设置cookie
	 * @param string $name
	 * @param string $value
	 * @param int    $expire
	 * @param string $path
	 * @param string $domain
	 *
	 */
	public static function setCookie($name,$value,$expire=0,$path="/",$domain = '.dianler.com')
	{
	    header( 'P3P: CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE GOV"');
		
		$cookieName = "dianler";
		
		$expire = $expire == 0 ? null : time()+$expire;
		
		setcookie($cookieName."[$name]",$value,$expire,$path,$domain);
		
		setcookie($cookieName."[{$name}_lock]",md5($value.self::COOKIE_KEY),$expire,$path,$domain);
	}
	
	/**
	 * 获取cookie
	 *
	 * @param {str} $name
	 *
	 * @return string|null
	 */
	public static function getCookie($name)
	{
		$_COOKIE['dianler'][$name] = stripslashes($_COOKIE['dianler'][$name]);
		
		if ($_COOKIE['dianler'][$name."_lock"] == md5($_COOKIE['dianler'][$name].self::COOKIE_KEY))
		{
			return $_COOKIE['dianler'][$name];
		}
	}
	
	/**
	 * 删除cookie
	 * @param {str} $name
	 * @return void
	 */
	public static function delCookie($name)
	{
		unset($_COOKIE['dianler'][$name]);
		unset($_COOKIE['dianler'][$name]['lock']);
		
		$nameLock = "dianler[{$name}_lock]";
		$name     = "dianler[{$name}]";

		setcookie($nameLock,'',-10000);
		setcookie($name,'',-10000);
	}
    
    /**
     * 返回到第二天七点的时间差（秒）
     * 若在午夜，就返回到当天七点的时间差     
     *
     * @return int
     */
    public static function time2Seven() 
    {
        $whichDay = (int)date("G")>= 7?strtotime("tomorrow"):strtotime("today");
        
        return strtotime("7 hours", $whichDay)-time();
    } 
    
     /**
     * 返回到第二天凌晨0点的时间差（秒）
     * 若在午夜，就返回到当天七点的时间差     
     *
     * @return int
     */
    public static function time2morning() 
    {
        return strtotime("tomorrow") - time();
    } 
    
    /**
     * curl 请求
     * @return string
     */
	static  function curl($url, $data, $memthod='post',$timeout = 20) 
	{
		$ch = curl_init();

		if($memthod == 'get'){
			$url = $url . '?' . http_build_query($data);	
		}
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		
		if(!empty($data) && $memthod == 'post')
		{
			curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$result = curl_exec ( $ch );
		curl_close($ch);

		return $result;
	}
	
	/**
     * 获取子函数的函数名称，并且运行
     * @param array $funName
     */
    public static function call_sub_function($funName,$param = array())
    {

    	if ($funName[1])
    	{
    		$func =$funName[1] ."_". APPID ."_" .PLATFORM_ID."_".APP_MORE;
    	}

    	if (method_exists($funName[0], $func))
    	{
    		$result = $func;
    	}					// 其他游戏可以在这里加以个elseif  搞优先级
    	else 
    	{
    		$result = $funName[1]."_common";
    	}
    	
		if (!is_array($param)){$param = array($param);}
		
		if (!method_exists($funName[0], $result)){return -1;}
		
    	return  call_user_func_array(array($funName[0],$result), $param);
    }
	/**
	 * 对数组信息进行加密，可以解密回来的
	 * @param array $array
	 */
	static function makeSigRequest($array)
	{
		$string = json_encode($array);
		return self::authcode($string,"Encode");
	}
	/**
	 * 对字符串进行解密
	 * @param string $string
	 */
	static function getUserInfoBySigRequest($string='')
	{
		static $userInfo;
		if (!$userInfo)
		{
			if ($string==''){$string=$_REQUEST['sigRequest'];}
			$string = self::authcode($string);
			$userInfo = json_decode($string,true);
		}
		return $userInfo;
	}
	/**
	 * 对字符串进行加密，必要的时候，可以解密回来
	 * @param {string} $string				需要加密的字符
	 * @param {string} $operation		操作方式 DECODE解密 | ENCODE 加密
	 * @param {string} $key				2着之间的通信密钥
	 * @param unknown_type $expiry
	 */
	static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) 
	{
		$ckey_length = 4;
		$key = md5($key ? $key : '123456!@###$%^*');
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
	
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
	
		$result = '';
		$box = range(0, 255);
	
		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
	
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
	
		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
	
	/**
	 * 跳转页面
	 * 
	 * @param string $url
	 * @param string $type
	 */
	static function redirect($url, $type = 'html') 
	{
		switch($type) 
		{
			case 'html':
				echo '<meta http-equiv="refresh" content="0;url=' . $url . '">';
				break;
			case 'js':
				echo '<script type="text/javascript">top.location.replace("' . $url . '");</script>';
				break;
		}

		exit;
	}
 
	/**
	 * 生成缩略图
	 */
	public static function makethumb($srcfile, $width, $height, $dstfile) {

		//判断文件是否存在
		if (!file_exists($srcfile)) {
			return '';
		}
		//缩略图大小
		$tow = intval($width);
		$toh = intval($height);
		if($tow < 42) $tow = 42;
		if($toh < 42) $toh = 42;
	
		$make_max = 0;
		$maxtow = 300;
		$maxtoh = 300;
		if($maxtow >= 300 && $maxtoh >= 300) {
			$make_max = 1;
		}
		//获取图片信息
		$im = '';
		if($data = getimagesize($srcfile)) {
			if($data[2] == 1) {
				$make_max = 0;//gif不处理
				if(function_exists("imagecreatefromgif")) {
					$im = imagecreatefromgif($srcfile);
				}
			} elseif($data[2] == 2) {
				if(function_exists("imagecreatefromjpeg")) {
					$im = imagecreatefromjpeg($srcfile);
				}
			} elseif($data[2] == 3) {
				if(function_exists("imagecreatefrompng")) {
					$im = imagecreatefrompng($srcfile);
				}
			}
		}
		if(!$im) return '';
		
		$srcw = imagesx($im);
		$srch = imagesy($im);
		
		$towh = $tow/$toh;
		$srcwh = $srcw/$srch;
		if($towh <= $srcwh){
			$ftow = $tow;
			$ftoh = $ftow*($srch/$srcw);
			
			$fmaxtow = $maxtow;
			$fmaxtoh = $fmaxtow*($srch/$srcw);
		} else {
			$ftoh = $toh;
			$ftow = $ftoh*($srcw/$srch);
			
			$fmaxtoh = $maxtoh;
			$fmaxtow = $fmaxtoh*($srcw/$srch);
		}
		if($srcw <= $maxtow && $srch <= $maxtoh) {
			$make_max && copy($srcfile, $dstfile);//copy 原图
			$make_max = 0;//不处理
			
		}

		if($srcw > $tow || $srch > $toh) {
			if(function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$ni = imagecreatetruecolor($ftow, $ftoh)) {
				imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftow, $ftoh, $srcw, $srch);
				//大图片
				if($make_max && @$maxni = imagecreatetruecolor($fmaxtow, $fmaxtoh)) {
					imagecopyresampled($maxni, $im, 0, 0, 0, 0, $fmaxtow, $fmaxtoh, $srcw, $srch);
				}
			} elseif(function_exists("imagecreate") && function_exists("imagecopyresized") && @$ni = imagecreate($ftow, $ftoh)) {
				imagecopyresized($ni, $im, 0, 0, 0, 0, $ftow, $ftoh, $srcw, $srch);
				//大图片
				if($make_max && @$maxni = imagecreate($fmaxtow, $fmaxtoh)) {
					imagecopyresized($maxni, $im, 0, 0, 0, 0, $fmaxtow, $fmaxtoh, $srcw, $srch);
				}
			} else {
				return '';
			}
			if(function_exists('imagejpeg')) {
				imagejpeg($ni, $dstfile);
				//大图片
				if($make_max) {
					imagejpeg($maxni, $srcfile);
				}
			} elseif(function_exists('imagepng')) {
				imagepng($ni, $dstfile);
				//大图片
				if($make_max) {
					imagepng($maxni, $srcfile);
				}
			}
			imagedestroy($ni);
			if($make_max) {
				imagedestroy($maxni);
			}
		}
		imagedestroy($im);

		if(!file_exists($dstfile)) {
			return '';
		} else {
			return $dstfile;
		}
	}
	
	/**
	 * ios http数据验证
	 *
	 * @return bool 是否合法
	 */
	public static function getXTunnelVerify($sid){
		static $byteMap = array(
			0x70,0x2F,0x40,0x5F,0x44,0x8E,0x6E,0x45,0x7E,0xAB,0x2C,0x1F,0xB4,0xAC,0x9D,0x91,
			0x0D,0x36,0x9B,0x0B,0xD4,0xC4,0x39,0x74,0xBF,0x23,0x16,0x14,0x06,0xEB,0x04,0x3E,
			0x12,0x5C,0x8B,0xBC,0x61,0x63,0xF6,0xA5,0xE1,0x65,0xD8,0xF5,0x5A,0x07,0xF0,0x13,
			0xF2,0x20,0x6B,0x4A,0x24,0x59,0x89,0x64,0xD7,0x42,0x6A,0x5E,0x3D,0x0A,0x77,0xE0,
			0x80,0x27,0xB8,0xC5,0x8C,0x0E,0xFA,0x8A,0xD5,0x29,0x56,0x57,0x6C,0x53,0x67,0x41,
			0xE8,0x00,0x1A,0xCE,0x86,0x83,0xB0,0x22,0x28,0x4D,0x3F,0x26,0x46,0x4F,0x6F,0x2B,
			0x72,0x3A,0xF1,0x8D,0x97,0x95,0x49,0x84,0xE5,0xE3,0x79,0x8F,0x51,0x10,0xA8,0x82,
			0xC6,0xDD,0xFF,0xFC,0xE4,0xCF,0xB3,0x09,0x5D,0xEA,0x9C,0x34,0xF9,0x17,0x9F,0xDA,
			0x87,0xF8,0x15,0x05,0x3C,0xD3,0xA4,0x85,0x2E,0xFB,0xEE,0x47,0x3B,0xEF,0x37,0x7F,
			0x93,0xAF,0x69,0x0C,0x71,0x31,0xDE,0x21,0x75,0xA0,0xAA,0xBA,0x7C,0x38,0x02,0xB7,
			0x81,0x01,0xFD,0xE7,0x1D,0xCC,0xCD,0xBD,0x1B,0x7A,0x2A,0xAD,0x66,0xBE,0x55,0x33,
			0x03,0xDB,0x88,0xB2,0x1E,0x4E,0xB9,0xE6,0xC2,0xF7,0xCB,0x7D,0xC9,0x62,0xC3,0xA6,
			0xDC,0xA7,0x50,0xB5,0x4B,0x94,0xC0,0x92,0x4C,0x11,0x5B,0x78,0xD9,0xB1,0xED,0x19,
			0xE9,0xA1,0x1C,0xB6,0x32,0x99,0xA3,0x76,0x9E,0x7B,0x6D,0x9A,0x30,0xD6,0xA9,0x25,
			0xC7,0xAE,0x96,0x35,0xD0,0xBB,0xD2,0xC8,0xA2,0x08,0xF3,0xD1,0x73,0xF4,0x48,0x2D,
			0x90,0xCA,0xE2,0x58,0xC1,0x18,0x52,0xFE,0xDF,0x68,0x98,0x54,0xEC,0x60,0x43,0x0F
		);

		if(empty($_SERVER['HTTP_X_TUNNEL_VERIFY']))
			return false;
	
		list($version, $seed, $data, $sig) = explode('&', $_SERVER['HTTP_X_TUNNEL_VERIFY']);
		if(empty($version) || empty($seed) || empty($data) || empty($sig))
			return false;

		$seed		= hexdec($seed) % 256;
		$data		= base64_decode($data);
		$sig		= base64_decode($sig);
		$datalen	= strlen($data);
	
		for($i=0; $i<$datalen; $i++){
			$data{$i} = chr( $byteMap[ ord($data{$i}) ^ $seed ] );
		}

		if(function_exists('mhash'))
			$hash = mhash(MHASH_SHA1, $data, Core_Game::$clientHttpVerifyConfig[$sid][0]);
		elseif(function_exists('hash_hmac'))
			$hash = hash_hmac("sha1", $data, Core_Game::$clientHttpVerifyConfig[$sid][0], true);
		else
			return false;
		
		if( strcmp($hash, $sig) )
			return false;

		$data = json_decode($data, true);
		if(is_array($data) && !empty($data)){
			//兼容旧的appleHeaderDecrypt函数返回结果
			$data[0] = '2.0-x-tunnel-verify'; //标识新版本协议
			$data[1] = $data['macID'];
			$data[2] = $data['iOSType'];
			$data[3] = $data['iOSVer'];
			$data[4] = $data['iOSModel'];
		}else{
			$data = false;
		}

		return $data;
	}
	
	/**
	 * 获取用户IP
	 */
	public static function getip(){
		
		if(self::$cache){
			return self::$cache;
		}
		
    	if($_SERVER['HTTP_CLIENT_IP']) {
    		$ip = $_SERVER['HTTP_CLIENT_IP'];
    	}else if($_SERVER['REMOTE_ADDR']){
    		$ip = $_SERVER['REMOTE_ADDR'];
    	}
    	
		if($_SERVER['HTTP_X_FORWARDED_FOR']){
    		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    	}
    	
    	self::$cache = $ip;
    	return $ip;
    }
        
    /**
     * 返回浏览器信�?ver为版本号,nav为浏览器�?     
     */
    public static function getbrowser(){
    	$browsers = "mozilla msie gecko firefox ";
		$browsers.= "konqueror safari netscape navigator ";
		$browsers.= "opera mosaic lynx amaya omniweb";
		$browsers = split(" ", $browsers);
		$nua = strToLower( $_SERVER['HTTP_USER_AGENT']);
		$l = strlen($nua);
		for ($i=0; $i<count($browsers); $i++){
		  $browser = $browsers[$i];
		  $n = stristr($nua, $browser);
		  if(strlen($n)>0){
		   $arrInfo["ver"] = "";
		   $arrInfo["nav"] = $browser;
		   $j=strpos($nua, $arrInfo["nav"])+$n+strlen($arrInfo["nav"])+1;
		   for (; $j<=$l; $j++){
		     $s = substr ($nua, $j, 1);
		     if(is_numeric($arrInfo["ver"].$s) )
		     $arrInfo["ver"] .= $s;
		     else
		     break;
		   }
		  }
		}
		return $arrInfo;
    }
    
    /**   
     * isUsername函数:检测是否符合用户名格式   
     * $Argv是要检测的用户名参数   
     * $RegExp是要进行检测的正则语句   
     * 返回值:符合用户名格式返回用户名,不是返回false   
     */ 
    public static function  isUsername($Argv){   
        $RegExp='/^[a-zA-Z0-9_]{3,30}$/'; //由大小写字母跟数字组成并且长度在3-16字符直接   
        return preg_match($RegExp,$Argv)?$Argv:false;   
    }   
            
    /**   
     * isMail函数:检测是否为正确的邮件格式   
     * 返回值:是正确的邮件格式返回邮件,不是返回false   
     */ 
    public static function  isMail($Argv){   
         $RegExp='/^[a-z0-9][a-z\.0-9-_] @[a-z0-9_-] (?:\.[a-z]{0,3}\.[a-z]{0,2}|\.[a-z]{0,3}|\.[a-z]{0,2})$/i';   
         return preg_match($RegExp,$Argv)?$Argv:false;   
    }   
            
    /**   
     * isSmae函数:检测参数的值是否相同   
     * 返回值:相同返回true,不相同返回false   
     */ 
    public static function isSame($ArgvOne,$ArgvTwo,$Force=false){   
          return $Force?$ArgvOne===$ArgvTwo:$ArgvOne==$ArgvTwo;   
     }   
            
     /**   
      * isQQ函数:检测参数的值是否符合QQ号码的格式   
      * 返回值:是正确的QQ号码返回QQ号码,不是返回false   
      */ 
    public static function isQQ($Argv){   
         $RegExp='/^[1-9][0-9]{5,11}$/';   
         return preg_match($RegExp,$Argv)?$Argv:false;   
     }   
            
     /**   
      * isMobile函数:检测参数的值是否为正确的中国手机号码格式   
      * 返回值:是正确的手机号码返回手机号码,不是返回false   
     */ 
    public static function isMobile($phone,$type = 'CHN'){   
     	switch($type){
            case "CHN":
                $ret = (preg_match('/^((\(\d{3}\))|(\d{3}\-))?1\d{10}$/', trim($phone)) ? true : false);
                break;
            case "INT":
                $ret = (preg_match('/^((\(\d{3}\))|(\d{3}\-))?\d{6,20}$/', trim($phone)) ? true : false);
                break;
        }
        
        return $ret == false ? false : $phone;
     }   
            
     /**   
      * isTel函数:检测参数的值是否为正取的中国电话号码格式包括区号   
      * 返回值:是正确的电话号码返回电话号码,不是返回false   
      */ 
    public static function isTel($Argv){   
         $RegExp='/[0-9]{3,4}-[0-9]{7,8}$/';   
         return preg_match($RegExp,$Argv)?$Argv:false;   
    }   
            
    /**   
     * isNickname函数:检测参数的值是否为正确的昵称格式(Beta)   
     * 返回值:是正确的昵称格式返回昵称格式,不是返回false   
     */ 
    public static function isNickname($Argv){   
        $RegExp = '/^\s*$|^c:\\con\\con$|[%,\*\&quot;\s\t\&lt;\&gt;\&amp;\'\(\)]|\xA1\xA1|\xAC\xA3|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8/is'; //Copy From DZ   
        return preg_match($RegExp,$Argv) ? $Argv : false;   
    }   
            
    /**   
     * isChinese函数:检测参数是否为中文   
     * 返回值:是返回参数,不是返回false   
     */ 
    public static function isChinese($Argv,$Encoding='utf8'){   
        $RegExp = $Encoding=='utf8'? '/^[\x{4e00}-\x{9fa5}] $/u' : '/^([\x80-\xFF][\x80-\xFF]) $/';   
        return preg_match($RegExp,$Argv)?$Argv:false;   
    }   
    
	/**
     * 判断字符串是否只包含英文字符
     * @param        string    $s, 字符串
     * @return        bool
     */
    public static function isAlpha($s) {
        $flag = preg_match('/^[A-Za-z]+$/', $s);
        $flag == false ? false : $s;
    }
    
    /**
     * 过滤用户输入字符中的不合法字符
     * 目前主要直接把一些特殊字符替换成空格，并去掉前后空格
     * @param    string        $str    用户输入的字符
     * @return    string        过滤后输出的字符
     */
    public static function filterInput($str){
        if(empty($str)) return '';
        // 需要替换的特殊字符
        $specialStr = array('\\', '\'', '"', '`', '&', '/', '<', '>');
        $str = str_replace($specialStr, '', $str);

        // 超过一定字符集范围的也需要替换成空格
        $str = trim($str);
        $asciiCode = '/[\x00-\x1f\x7f]/is';
        $str = preg_replace($asciiCode, '', $str);

        return $str;
    }
    
    /**
     * 获取本月最后一天
     */
	public static function getLastDay($year,$month){
		static $cache;	
		$key = $year.$month;
		if(!$cache[$key]){
			if($month == 12){
		        $year = $year + 1;
		        $month = 1;
		    }else{
		        $month = $month + 1;
		    }
		    
		    $t = mktime(0, 0, 0, $month , 1, $year);
		    $t = $t - 60 * 60 * 24;
		    $cache[$key] = $t;	
		}
	   
	    return $cache[$key];
	}
	
	/**
	 * 
	 * 解析XML
	 */
	public static function parseXml($sXmlData){
		if( empty( $sXmlData ) ){
			return false;
		}
		$oXmlParse = xml_parser_create();
		xml_parse_into_struct($oXmlParse, stripcslashes( $sXmlData ), $aProfileData ); //解析数据到数组中
		xml_parser_free( $oXmlParse );
	
		$aXmlData = array();
		foreach( (array)$aProfileData as $pf ){
			if(isset($pf['value'])){
				$aXmlData[strtolower( $pf['tag'] )] = $pf['value'];
			}
		}
		return $aXmlData;
	}
	
	/**
	 * 强制转换成整形（格式化返回给客户端，最多支持四维数组）
	 */
	public static function convertInt($data){	

		
		
		$no_allow_var = array('name','price','desc','img','reply','username','mnick','password','pdealno','sitemid','feedback','phoneno','odds','mtkey','rate');
		foreach ($data as $k1=>$v1) {
			if(is_numeric($v1)){
				if($k1 == 'money'){
					$data[$k1] = floatval($v1);
				}elseif(!in_array($k1,$no_allow_var)){
					$data[$k1] = (int)$v1;
				}
			}
			if(is_array($v1) && $v1){
				foreach ($v1 as $k2=>$v2){
					if(is_numeric($v2)){
						!in_array($k2,$no_allow_var)  && $data[$k1][$k2] = (int)$v2;
					}
					if(is_array($v2) && $v2){
						foreach ($v2 as $k3=>$v3) {
							if(is_numeric($v3)){
								!in_array($k3,$no_allow_var)  && $data[$k1][$k2][$k3] = (int)$v3;
							}
							if(is_array($v3) && $v3){
								foreach ($v3 as $k4=>$v4) {
									if(is_numeric($v4)){
										!in_array($k4,$no_allow_var) && $data[$k1][$k2][$k3][$k4] = (int)$v4;
									}
								}
							}
						}
					}
				}
			}
		}
		return $data;
	}
	
	/**
	 * 获取flash版本
	 *	
	 * @return $flashver['num'] 版本号, $flashver['swf'] Flash文件
	 */
	public static function versionPlugin($version_dir)
	{
		$flashver = array();

		if(!is_dir($version_dir))
		{
			die('version path error!');
		}
		
		$version_dir   = substr($version_dir, -1)=='/'?$version_dir:$version_dir.'/';
		$version_path = $version_dir . 'v.php';
		
		$flashver['num'] = file_exists($version_path) ? trim(file_get_contents($version_path)) : '0.1';
		$flashver['swf']= require_once $version_dir . ( $uselocal ? "v.local.php" : "{$flashver['num']}.php" );
		
		return $flashver;
	}
	
	public static function getFlashVersions($version_dir){
		$data['swf'] = array();
		$data['num'] = 0;

	    $d = dir($version_dir);
	    while( false !== ( $entry = $d->read() ) ) {
	        if(  ! preg_match('/^\..*/', $entry) ) {
	            if( is_file( $d->path .'/'. $entry ) && $entry !='v.php' ) {
	                $data['swf'][] = substr($entry, 0,14);
	            }
	        }
	    }
	    $d->close();
	    
	    $data['num'] = file_exists($version_dir.'/v.php') ? trim(file_get_contents($version_dir.'/v.php')) : '0.1';
	
	    return $data;
	}
	
	public static function delFlashVerions($ver_file_path,$swf_flie_path){
		if(is_file($ver_file_path)){
			@unlink($ver_file_path);
		}
		
		self::delDirAndFile($swf_flie_path);
		
		return true;
	}
	
	public static function resetFlashVersions($versionsNum){
		$file = FLASH_VER_PATH.'/v.php';
		if(is_file($file)){
			file_put_contents($file, $versionsNum);
			return true;
		}
		
		return false;
	}
	
	public static function delDirAndFile( $dirName ){
		if ( $handle = opendir( "$dirName" ) ) {
		   while ( false !== ( $item = readdir( $handle ) ) ) {
			   if ( $item != "." && $item != ".." ) {
				   if ( is_dir( "$dirName/$item" ) ) {
				   		delDirAndFile( "$dirName/$item" );
				   } else {
				   		unlink( "$dirName/$item" );
				   }
			   }
		   }
		   closedir( $handle );
		   rmdir( $dirName );
		}
	}
}


?>