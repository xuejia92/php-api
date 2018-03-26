<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>五星评价赢豪礼</title>
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

</style>
</head>
<body>
    <div id="main" class="relative" style=" width: 1280px; height: 800px; margin: 0 auto; background: url(web/activity/changshengjiangjun/image/landcard_bg.jpg); background-size: cover;">
        <div >
            <img style="padding-top: 70px; display: block; margin: auto;" src="web/activity/test_wuxingpingjia/image/wxpj_bgsh10000.png">
            <div class="absolute" style="left: 660px; top: 370px;">
                <img id="reheader" style="display: none;" src="web/activity/test_wuxingpingjia/image/wxpj_button_0.png">
                <img id="got" style="display: none;" src="web/activity/test_wuxingpingjia/image/wxpj_button_2.png">
                <img id="get" style="display: none;" src="web/activity/test_wuxingpingjia/image/wxpj_button_1.png">
            </div>
            <div class="absolute" style="left: 530px; top: 510px; text-align: left; color: #ffbd80; font-size: 28px; padding-right: 10px;">
                <div style="line-height: 40px">活动时间：新版发布期间</div>
                <div style="line-height: 40px">活动内容:凡在苹果商店对<?php switch ($cid){
                    case 113:
                        echo '《火拼梭哈》';
                        break;
                    case 126:
                        echo '《欢乐梭哈》';
                        break;
                    default:
                        echo '《点乐梭哈》';
                        break;
                }?>给予5星好评并在评论中留下美好寄语的玩家，即可在<五星评价赢豪礼>活动中领取<span style="color: yellow; font-size: 36px"><?php echo $bonus?></span>金币奖励，豪礼如此心动，小伙伴快去评论哦！</div>
                <div style="line-height: 40px">温馨提示:每个手机只能领取一次奖励哦^_^!</div>      
            </div>
        </div>
        <div id="message" class="absolute" style="width:556px; height:312px; background:url(http://user.dianler.com/web/activity/image/sh_mBg.png); left: 50%; top: 50%; margin: -126px 0  0 -228px; display: none;">
	        <img id="close" style="margin: -40px 0 0 515px;" src="web/activity/image/close.png">
	        <div id="congra" class="mess" style="font-size:40px; color:#ffbd80; text-align: center; padding: 90px 20px; display: none;">恭喜您成功领取!</div>
       	</div>
    </div>
</body>
<script type="text/javascript">
$(this).ready(function(){
	
    var status = <?php echo $status ? $status : 0?>;
    switch(status){
        case -1:
            $("#reheader").show();
            break;
        case 0:
            $("#got").show();
            break;
        case 1:
            $("#get").show();
            break;
    }
    
    $("#reheader").click(function(){
    	window.open("<?php echo $address?>");
    	$.ajax({
            type: "POST",
            url: "<?php echo $url?>?m=activity&p=dispath&fpath=test_wuxingpingjia&mtkey=<?php echo $mtkey?>&act=comment",
            data:"&gameid=<?php echo $gameid?>"
                +"&mid=<?php echo $mid?>"
                +"&sid=<?php echo $sid?>"
                +"&cid=<?php echo $cid?>"
                +"&pid=<?php echo $pid?>"
                +"&ctype=<?php echo $ctype?>"
                +"&versions=<?php echo $versions?>",
            success: function(){
                setInterval(function(){
                    location.reload()
                    },40000);
            }
        });
    });
	
    $("#close").click(function(){
        $("#message").hide();
        $(".mess").hide();
    });
    
    $("#get").click(function(){
        if (status==1){
            $.ajax({
                type: "POST",
                url: "<?php echo $url?>?m=activity&p=dispath&fpath=test_wuxingpingjia&mtkey=<?php echo $mtkey?>&act=set",
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
                    }else {
                        alert(state);
                    }
                }
            });
        }
    });
});
</script>
</html>