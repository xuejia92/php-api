<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width,initial-scale=0.3,minimum-scale=0.1,maximum-scale=1.0,user-scalable=yes" name="viewport">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo $title?></title>

<link href="statics/dwz/themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="statics/dwz/themes/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="statics/dwz/themes/css/print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="statics/dwz/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
<!--[if IE]>
<link href="themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->
<style type="text/css">
	#header{height:85px}
	#leftside, #container, #splitBar, #splitBarProxy{top:90px}
</style>

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
			$("#themeList").theme({themeBase:"themes"}); // themeBase 相对于index页面的主题base路径
		}
	});
});
</script>
</head>

<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<a class="logo" href="admin.php">标志</a>
				<ul class="nav">
					<li><a href="">您好！<?php echo $username?></a></li>
					<li><a href="http://sguser.dianler.com/admin.php">繁体后台</a></li>
					<li><a href="http://adm.dianler.com/admin/main/login.php?act=exit&redirect=<?php echo $url?>">退出</a></li>
				</ul>
				<ul class="themeList" id="themeList">	
				</ul>
			</div>
            <?php 
                $menu = Main_Menu::$menu;
                foreach ($menu as $menuid=>$menuname){
                  if ($permission[$menuid-1]){
                      $name = $menuname;
                      $no   = $menuid;
                      break;
                  }
                }
                
                if(!$permission){
                    die();
                }

            ?>
			<div id="navMenu">
				<ul>
				<?php if($permission):?>
				    <?php if ($permission[0]):?>
					<li class=<?php echo $no == 1 ? 'selected' : ''?>><a href="admin.php?m=main&p=setting"><span>设置</span></a></li>
					<?php endif;?>
					<?php if ($permission[1]):?>
					<li class=<?php echo $no == 2 ? 'selected' : ''?>><a href="admin.php?m=main&p=monitor"><span>监控</span></a></li>
					<?php endif;?>
					<?php if ($permission[2]):?>
					<li class=<?php echo $no == 3 ? 'selected' : ''?>><a href="admin.php?m=main&p=feedback"><span>反馈</span></a></li>
					<?php endif;?>
					<?php if ($permission[3]):?>
					<li class=<?php echo $no == 4 ? 'selected' : ''?>><a href="admin.php?m=main&p=user"><span>用户</span></a></li>
					<?php endif;?>
					<?php if ($permission[4]):?>
					<li class=<?php echo $no == 5 ? 'selected' : ''?>><a href="admin.php?m=main&p=data"><span>数据</span></a></li>
					<?php endif;?>
					<?php if ($permission[5]):?>
					<li class=<?php echo $no == 6 ? 'selected' : ''?>><a href="admin.php?m=main&p=action"><span>活动</span></a></li>
					<?php endif;?>
				<?php endif;?>	
				</ul>
			</div>
		</div>

		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse"><div></div></div>
				</div>
			</div>
			<div id="sidebar">
				<div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>
                <?php include $name.'.php';?>
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
							<p><span>梭哈游戏管理后台</span></p>
						</div>
						<div class="pageFormContent" layoutH="80">		
						</div>
					</div>					
				</div>
			</div>
		</div>

	</div>

	<div id="footer">Copyright &copy;  <a target="dialog">点乐游戏   版权所有</a></div>

</body>
</html>