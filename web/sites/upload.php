<?php !defined('IN WEB') AND exit('Access Denied!');

$act = $_REQUEST['act'];

switch ($act) {
	case 'upload':
		$allowpictype = array('swf'); //允许上传类型
		$fileext = strtolower(trim(substr(strrchr($_FILES["swf"]["name"], '.'), 1)));
		if(!in_array($fileext, $allowpictype)) {
			die("非法文件");
		}
		
		$path = '/data/dis/wwwroot/baccarat/statics/flash/'; //本地文件夹路径
		$new_name = $_FILES["swf"]["name"];
		$tmp_name = $_FILES["swf"]["tmp_name"];
		
		if(!is_uploaded_file($tmp_name)){
			die("非法上传");
		}
		
		if(copy($tmp_name, $path.$new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && move_uploaded_file($tmp_name, $path.$new_name))) {
		} elseif(@rename($tmp_name, $path.$new_name)) {
		} else {
			die("上传失败");
		}

		die("恭喜，上传成功");
	break;
	
	case 'uploadicon':
		
		$allowpictype = array('jpg','jpeg','png'); //允许上传类型
		$fileext = strtolower(trim(substr(strrchr($_FILES["Filedata"]["name"], '.'), 1)));
		if(!in_array($fileext, $allowpictype)) {
			die("-1");
		}

		$iconPath   = Config_Inc::$iconPath; //本地文件夹路径
		$iconDomain = Config_Inc::$iconDomain; //域名路径
		$mid = $_REQUEST['mid'];
		$subname = $mid % 10000;
		if(!is_dir($iconPath . 'icon/')) mkdir($iconPath . 'icon/',0777);
		if(!is_dir($iconPath . 'icon/' . $subname . '/')) mkdir($iconPath . 'icon/' . $subname  . '/',0777);
		
		//本地上传
		$new_name = $iconPath . 'icon/' . $subname  . '/' . $mid . '.jpg';
		$tmp_name = $_FILES["Filedata"]["tmp_name"];
		
		if(!is_uploaded_file($tmp_name)){
			die("-2");
		}
			
		if(copy($tmp_name, $new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && move_uploaded_file($tmp_name, $new_name))) {
		} elseif(@rename($tmp_name, $new_name)) {
		} else {
			return $ret;
		}

		Helper::makethumb( $iconPath . 'icon/' . $subname  .'/'. $mid . '.jpg', 160, 160, $iconPath.'icon/' .$subname .'/'   . $mid . '_icon.jpg' );
		Helper::makethumb( $iconPath . 'icon/' . $subname  .'/'. $mid . '.jpg', 180, 180, $iconPath.'icon/' .$subname .'/' . $mid . '_middle.jpg' );
		Helper::makethumb( $iconPath . 'icon/' . $subname  .'/'. $mid . '.jpg', 260, 260, $iconPath.'icon/' .$subname .'/' . $mid . '_big.jpg' );
		
		die("1");
		break;
		
	case 'uploadiconview':
		include 'view/uploadicon.php';
	break;	
	default:
		include 'view/uploadswf.php';
	break;
}




