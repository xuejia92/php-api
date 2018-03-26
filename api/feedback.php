<?php !defined('IN WEB') AND exit('Access Denied!');
class Feedback{	
	/**
	 * 反馈
	 */
	public function set($param){
		$mid     = Helper::uint($param['mid']);
		$sid     = Helper::uint($param['sid']);//账号类型ID	
		$cid     = Helper::uint($param['cid']);//渠道ID
		$ctype   = Helper::uint($param['ctype']);//客户端类型 	1 android 2 iphone 3 ipad 4 android pad	
		$pid     = Helper::uint($param['pid']);//客户端包类型ID
		$mnick   = $param['param']['mnick']   ? Loader_Mysql::DBMaster()->escape($param['param']['mnick']) : "";	
		$phoneno = $param['param']['phoneno'] ? Helper::filterInput($param['param']['phoneno']) : '';
		$pic     = $param['param']['picurl']  ? $param['param']['picurl'] : '';
		$content = $param['param']['content'] ? Helper::filterInput($param['param']['content']) : '';	
		$gameid  = $param['gameid'] ? Helper::uint($param['gameid']) : 1;;		
		$ip      = Helper::getip();
		$ret['result'] = 0;

		if(!$ctype || !$cid  ||!$mid ){
			return $ret;
		}
		
		if(!$pic && !$content){
			return $ret;
		}
		
		Logs::factory()->debug(array($param,$pic),'fackback');
		
		$ret['result']  = (int)Base::factory()->feedBack($gameid,$cid, $sid, $pid, $ctype, $content, $mid, $mnick, $phoneno, $pic,$ip);		
		return $ret;		
	}
	
	/**
	 * 上传图片
	 */
	public function uploadPic($param){
		$ret['result'] = 0;
	    $ret['picurl'] = '';
	    $mid = Helper::uint($param['mid']);
	
		$allowpictype = array('jpg','jpeg','gif','png'); //允许上传类型
		$fileext = strtolower(trim(substr(strrchr($_FILES["icon"]["name"], '.'), 1)));

	    if(!in_array($fileext, $allowpictype)) {
			$ret['result'] = -1;
			return $ret;
		}
		
		$time              = NOW;
		$iconPath          = Config_Inc::$iconPath; //本地文件夹路径
		$feedbackPicDomain = Config_Inc::$feedbackPicDomain; //域名路径

		if(!is_dir($iconPath . 'feedback/')) mkdir($iconPath . 'feedback/',0777);
		
		//本地上传
		$new_name = $iconPath . 'feedback/'.$mid .'_'.$time.'.jpg';
		$tmp_name = $_FILES["icon"]["tmp_name"];
		
		if(!is_uploaded_file($_FILES['icon']['tmp_name'])){
			$ret['result'] = -2;
			return $ret;
		}
	
		if(copy($tmp_name, $new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && move_uploaded_file($tmp_name, $new_name))) {
		} elseif(@rename($tmp_name, $new_name)) {
		} else {
			return $ret;
		}
		
		$ret['picurl'] = $feedbackPicDomain.$mid.'_'.$time.'.jpg';
		$ret['result'] = 1;
		return $ret;	
	}
	
	/**
	 * 获取历史记录
	 */
	public function getHistory($param){
		$ret['result'] = 0;
		$ret['msg']    = array();
		$gameid        = Helper::uint($param['gameid']);
		$mid           = Helper::uint($param['mid']);
 		if(!$mid || !$gameid){
			return $ret;		
		}
		
		$history = Base::factory()->getMyfeed($gameid,$mid);
		
		if($history){
			$ret['result'] = 1;
			$ret['msg'] = $history;
		}
		
		return $ret;
	}
	
	
	
}