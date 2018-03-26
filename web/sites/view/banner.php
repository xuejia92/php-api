<script type="text/javascript" src="<?php echo GAMENAME.'/'.PLATFORM;?>/core/js/slide.min.js"></script> 
<style type="text/css">
ul{list-style:none;}
a{text-decoration:none;}
img{border:none; vertical-align:middle;}
.svw{width:50px;height:20px;background:#fff;}.svw ul{position:relative;left:-999em;}div.subul{position:relative;right:0;bottom:20px;float:right;}div.subul span{float:left;width:15px;height:15px;margin:0 2px;line-height:15px;text-align:center;background:#EFEFEF;border:1px dotted red;}div.subul span.a{background:#999;color:#fff;}.stripViewer{position:relative;overflow:hidden;margin:10px 0;}.stripViewer  img{heightL:100px;}.stripViewer ul{margin:0;padding:0;position:relative;left:0;top:0;width:1%;list-style-type:none;}.stripViewer ul li{float:left;}.stripTransmitter{overflow:auto;width:1%;}.stripTransmitter ul{margin:0;padding:0;position:relative;list-style-type:none;}.stripTransmitter ul li{width:20px;float:left;margin:0 1px 1px 0;}.stripTransmitter a{font:bold 10px Verdana,Arial;text-align:center;line-height:22px;background:#ff0000;color:#fff;text-decoration:none;display:block;}.stripTransmitter a:hover,a.current{background:#fff;color:#ff0000;}.tooltip{padding:0.5em;background:#fff;color:#000;border:5px solid #dedede;}
</style>
<div id="bannerSlide" class="svw">
	<ul>
		<!-- li><a href="http://game.weibo.com/boyadoudizhu" target='_blank'><img style="width:1000px;height:100px;" src="http://cache.17c.cn/chessweibo/images/banner/banner3.jpg?v=01"></a></li-->
		<li><a href="javascript:;"  onclick="bannerMulti();"><img style="width:1000px;height:100px;" src="http://cache.17c.cn/chessweibo/images/banner/banner2.jpg?v=01"></a></li>
	</ul>
</div>
<div style="margin:auto;position:absolute;z-index:600;left:150px;top:150px;" id="bannerMulti"></div>
<script type="text/javascript">
	$("#bannerSlide").slideView1();
	function bannerMulti(){
		$.ajax({
			type: "POST",
			dataType: "html",
			url: GAME.scripturl+"?m=banner&p=content",
			data: "",
			error : function(html){
				$('#bannerMulti').hide();
			},
			success: function(html){
				$('#bannerMulti').html(html);
				$('#bannerMulti').show();
		   }
		});
	}
	function closeBannerMulti(){
		$('#bannerMulti').hide();
	}
</script>
