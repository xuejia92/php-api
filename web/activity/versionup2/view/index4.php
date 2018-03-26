<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>升级回馈</title>
<script type="text/javascript" src="web/activity/js/jquery-2.1.1.min.js"></script>
<style type="text/css">
body {
	               margin: 0 auto;
	               padding: 0;
	               text-align: center;
	               background: #4D090A;
}
a {
	               text-decoration: none;
                   -webkit-tap-highlight-color: rgba(0,0,0,0);
}
.float_left {
	               float: left;
}
.float_right {
	               float: right;
}
.clear {
	               clear: both;
}
.relative {
	               position: relative;
}
.absolute {
	               position: absolute;
}

.bg {
	background: url(web/activity/versionup2/image/background.jpg) no-repeat;
	background-size: cover;
	position: absolute;
	width: 100%;
	height: 100%;
	z-index: -1;
	background-position: 50%;
}

</style>
</head>
<body>
    <div class="bg"></div>
    <div>
        <div class="absolute" style=" bottom: 0; width: 100%; height: 100%; background: url(web/activity/versionup2/image/ddn.png) no-repeat; background-size: 50%; background-position: 50%;">
            <div class="absolute" style="top: 60%; left: 48%;">
                <div style="width: 60%;">
                    <img id="reboot" style="display: none;" width="100%" src="web/activity/versionup2/image/button_0.png">
                    <img id="got" style="display: none;" width="100%" src="web/activity/versionup2/image/button_2.png">
                    <img id="get" style="display: none;" width="100%" src="web/activity/versionup2/image/button_1.png">
                </div>
                <div style="text-align: left; color: #d7a88c; font-size: 20px; margin-top: 5%;">
                    <div style="line-height: 30px">更新到<?php echo $version?>版本就可领取<span style="color: yellow; font-size: 30px"><?php echo $bonus?></span>金币!</div>
                    <div style="line-height: 30px">更畅快游戏!更刺激体验!</div>      
                </div>
            </div>
        </div>
        <div id="message" class="absolute" style=" background:url(http://user.dianler.com/web/activity/image/sh_mBg.png) no-repeat; width: 40%; left: 0; right: 0; top: 30%; margin: auto; background-size: 100%; display: none;">
            <img id="close" style="width: 12%; margin: -6% 0 0 93%;" src="web/activity/image/close.png">
            <div id="remind" class="mess" style="font-size:30px; color:#ffbd80; text-align: left; padding: 12% 9% 25% 9%; display: none;">请小伙伴重启游戏根据<br/>"提醒"更新<?php echo $version?>版本。</div>
            <div id="congra" class="mess" style="font-size:40px; color:#ffbd80; text-align: center; padding: 15% 0 25% 0; display: none;">恭喜您成功领取</div>
       	</div>
   	</div>
</body>
<script type="text/javascript">
$(this).ready(function(){
	
    var status = <?php echo $status ? $status : 0?>;
    switch(status){
        case -1:
            $("#reboot").show();
            break;
        case 0:
            $("#got").show();
            break;
        case 1:
            $("#get").show();
            break;
    }
    
    $("#reboot").click(function(){
        $("#message").show();
        $("#remind").show();
    });
	
    $("#close").click(function(){
        $("#message").hide();
        $(".mess").hide();
    });
    
    $("#get").click(function(){
        if (status==1){
            $.ajax({
                type: "POST",
                url: "<?php echo $url?>?m=activity&p=dispath&fpath=versionup2&mtkey=<?php echo $mtkey?>&act=set",
                data:"&gameid=<?php echo $gameid?>"
                    +"&mid=<?php echo $mid?>"
                    +"&sid=<?php echo $sid?>"
                    +"&cid=<?php echo $cid?>"
                    +"&pid=<?php echo $pid?>"
                    +"&ctype=<?php echo $ctype?>"
                    +"&versions=<?php echo $versions?>",
                success: function(state){
                    if(state==1) {
                    	$("#message").show();
                        $("#congra").show();
                        $("#get").hide();
                        $("#got").show();
                    }
                }
            });
        }
    });
});
</script>
</html>