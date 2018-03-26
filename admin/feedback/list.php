<?php !defined('IN WEB') AND exit('Access Denied!');

switch ($_REQUEST['act']) {
	case "ignore":
		$ret  = Feedback_Model::factory()->ignore($_REQUEST['id']);
		if($ret){
			Main_Flag::ret_sucess("操作成功！");
		}else{
			Main_Flag::ret_fail("操作失败！");
		}
		break;
		
	case "excel":		
		$aCid   = Base::factory()->getChannel();
		$aCtype = Config_Game::$clientTyle;;
		$aSid   = Base::factory()->getAccountType();
		
		$page_code = "utf-8";
		header("content-type: text/html; charset=$page_code"); //页面编码
		header("content-type:application/vnd.ms-excel");		
		header("content-disposition:attachment;filename=".mb_convert_encoding("反馈记录","gbk",$page_code).".xls");		
		header("pragma:no-cache");		
		header("expires:0");
		echo iconv("utf-8", "gb2312", "编号")."\t";	
		echo iconv("utf-8", "gb2312", "游戏")."\t";	
		echo iconv("utf-8", "gb2312", "mid")."\t";	
		echo iconv("utf-8", "gb2312", "昵称")."\t";	
		echo iconv("utf-8", "gb2312", "渠道")."\t";	
		echo iconv("utf-8", "gb2312", "内容")."\t";	
		echo iconv("utf-8", "gb2312", "时间")."\t";	
		echo iconv("utf-8", "gb2312", "状态")."\t";	
		echo iconv("utf-8", "gb2312", "回复内容")."\t";
		echo iconv("utf-8", "gb2312", "回复时间")."\t";
		echo "\n"; //换行
		$items = Feedback_Model::factory()->get2excel($_REQUEST,$aCid,$aCtype);
		foreach ($items as $item) {
			echo iconv("utf-8", "gb2312",$item['id'])."\t";	
			echo iconv("utf-8", "gb2312",Config_Game::$game[$item['gameid']])."\t";
			echo iconv("utf-8", "gb2312",$item['mid'])."\t";
			echo iconv("utf-8", "gb2312",$item['mnick'])."\t";
			echo iconv("utf-8", "gb2312",$item['cname'])."\t";
			echo iconv("utf-8", "gb2312",$item['content'])."\t";
			echo iconv("utf-8", "gb2312",date("Y-m-d H:i:s",$item['ctime']))."\t";
			echo iconv("utf-8", "gb2312",$item['status'] == 0 ? "未回复" : ($item['status'] == 1 ? "已回复":"已忽略"))."\t";
			echo iconv("utf-8", "gb2312",$item['rcontent'])."\t";
			echo iconv("utf-8", "gb2312",date("Y-m-d H:i:s",$item['rtime']))."\t";
			echo "\n"; //换行
		}
		break;	

	default:		
		$aCid   = Base::factory()->getChannel();
		$aCtype = Config_Game::$clientTyle;;
		$aSid   = Base::factory()->getAccountType();
		
		$numPerPage  = $_REQUEST['numPerPage'] = $_REQUEST['numPerPage'] ? $_REQUEST['numPerPage'] : 30 ;
		$currentPage = $_REQUEST['pageNum'] = max(Helper::uint($_REQUEST['pageNum']),1);
		$totalCount  = Feedback_Model::factory()->getCount($_REQUEST);		
		$items = Feedback_Model::factory()->get($_REQUEST,$aCid,$aCtype);
		include 'view/feedback.list.php';
		break;
}

