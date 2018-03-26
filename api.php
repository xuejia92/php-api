<?php 
/**
 * 移动端 API入口文件
 */





/* 
$checkarr = array(0x7B,0xE3,0x11,0x8E,0x31,0x97,0x57,0x19,0x34,0xDF,0x5B,0x41,0x93,0x10,0x45,0xFF,
	0x51,0xA1,0x9E,0xD7,0x5A,0x58,0x49,0xAA,0x0A,0xEF,0x88,0x01,0x6D,0xC9,0x20,0x2F,
	0xF5,0xCF,0x52,0xA8,0xD2,0xA4,0xB4,0x0B,0xE9,0x77,0x3D,0x13,0xDC,0x95,0xD4,0xAF,
	0x9D,0x16,0x61,0x8C,0x1E,0x83,0x1C,0x2D,0x84,0x3C,0x1F,0x5F,0x1B,0x82,0x1A,0x7D);

if($_POST["api"])
{
    $jstr = $_POST["api"];
    $jstr2 = json_decode($jstr);
    
    
    //hexdec
    $encode= $jstr2->num;
    $encodenum = $checkarr[$encode];

    //$checkcodenum = hexdec($_POST["checkcode"]);
    $checkcodenum = $jstr2->code;
    
    //$checkstr = $_POST["data"];
    $checkstr = base64_decode($jstr2->data);
    
}

$tmpstr = $checkstr;

$tt = "";

for ($i=0; $i < strlen($tmpstr); $i++)
{
     $tt .= chr(ord($tmpstr[$i]) ^ $encodenum);
     
}
 */


include 'common.php';

$param = Config_Flag::param( $_POST['api']);

$method = $param['param']['method'];

error_log("request : ".var_export($param, true));

list($class, $method) = explode('.', $method);

Lib_Apistat::tick($class, $method);	//开始记时

if( !Lib_Mobile::auth($param)){
    error_log("sig error");
	Config_Flag::mkret(Config_Flag::status_check_error);
}

error_log("11111 ");

$oClass = new $class();


error_log("22222 ");
method_exists($oClass, $method) or Config_Flag::mkret(Config_Flag::status_method_error);


error_log("3333 ");
if(!Base::factory()->checkMtkey($param)){//检查请求的合法性
    error_log("check mtkey error");
    Config_Flag::mkret(Config_Flag::status_check_error);
}

$param['lang'] = $lang = Config_Lang::getLang($param['lang']);//判断语言版本

$return = $oClass->$method($param);

$mid = $return['mid'] ?  Helper::uint($return['mid']) : Helper::uint($param['mid']);

Loader_Udp::behavior($param['gameid'])->sendLog($mid, $param['param']['method'], $param, $return);//记录用户行为

error_log("response : ".var_export($return, true));

Config_Flag::mkret(Config_Flag::status_ok, $return);
