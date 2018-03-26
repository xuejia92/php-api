<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wb=“http://open.weibo.com/wb”>
  <head>
   <meta http-equiv="Content-Type"   content="text/html; charset=utf-8" />
    <title>海南斗地主</title>
    <script type="text/javascript">var GAME = <?php echo json_encode($GAME);?>;</script>
    <link type="text/css" rel="stylesheet" href="<?php echo Sites_Config::$appurl ?>css/public.css?v=2014-05-17" />
    <script src="<?php echo Sites_Config::$appurl ?>js/jquery.min.js" type="text/javascript"></script>
	<style>
		body{
			/*background-color:#f3c97d;*/
			background:url('statics/landlord_hainan/login_headerBg.jpg?2=2') repeat-x center top #f3c97d;
		} 
	</style>
  </head>
  <body>

	<div class="z-mcont z-pr" id="main">
		<div id="headerbox">
		</div>
		<!--start gameBody-->
		<div id = "bodyBox" class="z-pr" style="height:880px;">
			<div id="show_div" style="z-index:999;" ></div>
			<div id="gameBody" style="position:absolute;z-index:2;">
				<div id="flashBox"><p>Loading...</p></div>
				<div id="flashVersion"></div>
				<div class="z-ce" style="text-align:center;margin:5px;"> 
				          您的ID:<?php echo $userInfo['mid']; ?> <br> 
				</div>
			</div>
		</div>
		
	</div>	
	<!--end gameBody-->
	<div id = "pages" style="margin-bottom:10px;"></div>
	<script type="text/javascript" src="<?php echo Sites_Config::$appurl ?>js/swfobject.js"></script>
  	<script type="text/javascript" src="<?php echo Sites_Config::$appurl ?>js/game.js?<?php echo time();?>"></script>
  	<script type="text/javascript" src="<?php echo Sites_Config::$appurl ?>js/function.js?<?php echo time()?>"></script>
	<script type="text/javascript" src="<?php echo Sites_Config::$appurl ?>js/public.js?<?php echo time();?>"></script> 
    <script type="text/javascript">
	    flashVars_init({ 
			"userInfo" : <?php echo json_encode($userInfo); ?>,
			"version"  : <?php echo json_encode($flashver); ?>,
			"urlinfo"  : <?php echo json_encode($urlinfo); ?>,
			"gameinfo" : <?php echo json_encode($gameInfo); ?>,
			"request"  : <?php echo json_encode($_REQUEST); ?>
		});
	    var fls=checkFlash();
	    if(!fls.f || fls.v < 10){
	    	$("#flashVersion").html("<p align='center'>您浏览器没有安装Flash Player或者安装的版本过低,<a href='http://www.adobe.com/go/getflashplayer' target='_blank'>点击下载</a></p>")
	    }
        load_flash();
    </script>
    
  </body>
</html>