<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>高级场</title>

<link href="statics/dwz/themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="statics/dwz/themes/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="statics/dwz/themes/css/print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="statics/dwz/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
<!--[if IE]>
<link href="themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->

<!--[if lte IE 9]>
<script src="js/speedup.js" type="text/javascript"></script>
<![endif]-->
<script src="statics/dwz/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="statics/dwz/js/jquery.cookie.js" type="text/javascript"></script>
<script src="statics/dwz/js/jquery.validate.js" type="text/javascript"></script>
<script src="statics/dwz/js/jquery.bgiframe.js" type="text/javascript"></script>
<script src="statics/dwz/xheditor/xheditor-1.1.14-zh-cn.min.js" type="text/javascript"></script>
<script src="statics/dwz/uploadify/scripts/jquery.uploadify.min.js" type="text/javascript"></script>

<script src="statics/dwz/bin/dwz.min.js" type="text/javascript"></script>
<script src="statics/dwz/js/dwz.regional.zh.js" type="text/javascript"></script>
<script src="statics/highcharts/js/highcharts.js"></script>
<script src="statics/highcharts/js/modules/exporting.js"></script>
<script type="text/javascript">
$(function(){
	DWZ.init("statics/dwz/dwz.frag.xml", {
		loginUrl:"login_dialog.html", loginTitle:"登录",	// 弹出登录对话框
//		loginUrl:"login.html",	// 跳到登录页面
		statusCode:{ok:200, error:300, timeout:301}, //【可选】
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
		debug:false,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"themes"});
			setTimeout(function() {$("#sidebar .toggleCollapse div").trigger("click");}, 10);
		}
	});

});

$.ajaxSettings.global=false; 
</script>
</head>

<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<a class="logo" >标志</a>
			</div>
		</div>

		<div id="leftside">
			<div id="sidebar">
				<div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>

				<div class="accordion" fillSpace="sideBar">
					<div class="accordionHeader">
						<h2><span>PayCenter</span>场次选择</h2>
					</div>
					<div class="accordionContent">
						<ul class="tree treeFolder">
							<li><a>场次</a>
								<ul>
									<!--  <li><a href="6549estujif.php?p=sh&act=shview&roomid=02&gameid=1" rel="zhong1" target="navTab">五张中级场</a></li> -->
									<li><a href="6549estujif.php?p=sh&act=shview&roomid=03&gameid=1" rel="gao1" target="navTab">(五张)高级场</a></li>
									<li><a href="6549estujif.php?p=sh&act=shview&roomid=04&gameid=1" rel="er1" target="navTab">(五张)二人场</a></li>
									<!--<li><a href="6549estujif.php?p=sh&act=shview&roomid=02&gameid=6" rel="zhong6" target="navTab">(德州)中级场</a></li> -->
									<li><a href="6549estujif.php?p=sh&act=shview&roomid=03&gameid=6" rel="gao6" target="navTab">(德州)高级场</a></li>
									<li><a href="6549estujif.php?p=sh&act=shview&roomid=04&gameid=6" rel="er6" target="navTab">(德州)二人场</a></li>
									<!-- <li><a href="6549estujif.php?p=sh&act=shview&roomid=02&gameid=4" rel="zhong4" target="navTab">斗牛中级场</a></li> -->
									<li><a href="6549estujif.php?p=sh&act=shview&roomid=03&gameid=4" rel="gao4" target="navTab">(斗牛)高级场</a></li>
									<li><a href="6549estujif.php?p=sh&act=shview&roomid=02&gameid=7" rel="zhong7" target="navTab">(炸金花)中级场</a></li>
									<li><a href="6549estujif.php?p=sh&act=shview&roomid=03&gameid=7" rel="gao7" target="navTab">(炸金花)高级场</a></li>
									<!--<li><a href="6549estujif.php?p=sh&act=shview&roomid=04&gameid=4" rel="er4" target="navTab">(斗牛)二人场</a></li> -->
									<li><a href="6549estujif.php?p=sh&act=bairen&type=1&navTabId=longhu" rel="longhu" target="navTab">龙虎斗</a></li>
									<li><a href="6549estujif.php?p=sh&act=bairen&type=2&navTabId=douniu" rel="douniu" target="navTab">斗牛百人场</a></li>
									<li><a href="6549estujif.php?p=shblacklist" rel="blacklist" target="navTab">百人场黑名单</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>

			</div>
		</div>
		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon">我的主页</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:;">我的主页</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						<div class="accountInfo">
							<div class="alertInfo">
							</div>
							<p><span>场次实时信息</span></p>
						</div>
						<div class="pageFormContent" layoutH="80">		
						</div>
					</div>					
				</div>
			</div>
		</div>

	</div>

	<div id="footer">Copyright &copy;  <a target="dialog">点乐游戏  版权所有</a></div>

</body>
</html>