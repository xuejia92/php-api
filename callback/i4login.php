<?php
include '../common.php';


$token = $_REQUEST['token'];

$data = array(
	'token'=>$token,
);

$output = urldecode(json_encode($data));
$result = HttpsPost('https://pay.i4.cn/member_third.action', $output);

$ret['status'] = 1;

if($result['status'] == 0){
	$ret['status'] = 0;	
	die(json_encode($ret));
}else{
	Logs::factory()->debug($result,'i4logon_error');
	die(json_encode($ret));
}


// 发送https请求
function HttpsPost($url,$data)
{
	$ch = curl_init();
	// 设置选项，包括URL
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);	// 对证书来源的检查
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);	// 从证书中检查SSL加密算法是否存在 
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);	// 模拟用户使用的浏览器
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	// 使用自动跳转
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);		// 自动设置Referer
	curl_setopt($ch, CURLOPT_POST, 1);		// 发送一个 常规的Post请求
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	// Post提交的数据包
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);		// 设置超时限制防止死循环
	curl_setopt($ch, CURLOPT_HEADER, 0);		// 显示返回的Header区域内容
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 	//获取的信息以文件流的形式返回

	$output = curl_exec($ch);	// 执行操作
	if(curl_errno($ch))
	{
		echo "Errno".curl_error($ch); 	// 捕抓异常
	}
	curl_close($ch);	// 关闭CURL
	return $output;
}
?>