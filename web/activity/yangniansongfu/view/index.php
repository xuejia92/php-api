<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>吉羊送福来，羊气过新年</title>
<script type="text/javascript" src="web/activity/yangniansongfu/js/jquery-2.1.1.min.js"></script>
<style type="text/css">
body {
	               margin: 0 auto;
	               padding: 0;
	               text-align: center;
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
.tab {
	               width: 189px; 
	               height: 66px;
	               margin-top:8px;
	               line-height: 66px;
	               display: inline-block;
                   color: #ecac64;
                   font-size: 32px;
}
.turnRight  {
                    transform: rotate(360deg);
                    -ms-transform:rotate(360deg);
	                -moz-transform:rotate(360deg);
                    -webkit-transform:rotate(360deg);
                    -o-transform:rotate(360deg);
	

}
.turnLeft  {
                    transform: rotate(-360deg);
                    -ms-transform:rotate(-360deg);
	                -moz-transform:rotate(-360deg);
                    -webkit-transform:rotate(-360deg);
                    -o-transform:rotate(-360deg);
	          
}
.transform {
	                 transition: transform 20s ease 0s;
	                 -ms-transition: -ms-transform 20s ease 0s;
	                 -moz-transition: -moz-transform 20s ease 0s;
	                 -webkit-transition: -webkit-transform 20s ease 0s;
	                 -o-transition: -o-transform 20s ease 0s;
}

</style>
</head>
<body>
    <div id="main" class="relative" style="width: 1280px;height: 800px; margin: 0 auto; background: url(web/activity/yangniansongfu/image/bg.jpg);">
        <div id="header" style="height: 74px;background: url(web/activity/image/head_ddz.png)">
            <a id="tab_2" class="tab float_right" href="javaScript:;" style="background: url(web/activity/image/caidan_leave.png);margin-right: 190px;">活动介绍</a>
            <a id="tab_1" class="tab float_right" href="javaScript:;" style="background: url(web/activity/image/caidan_on.png);margin-right: 15px;">活动</a>
        </div>
        <div id="content" class="relative">
            <div id="table_1" >
                <div style="background: url(web/activity/yangniansongfu/image/xh2_n.png) no-repeat; background-size: 930px; width: 930px; height: 627px; margin: 10px auto 0;">
                    <div class=" relative" style="background: url(web/activity/yangniansongfu/image/xh3_n.png); background-size: 589px; width: 589px; height: 304px; left: 163px; top: 145px;">
                        <div id="surplus" class="absolute" style="color: #BF3524; width: 40px; height: 23px; left: 272px; top: 13px; font-size: 23px; line-height: 23px; text-align: center;">
                            <?php echo $surplus;?>
                        </div>
                        <div id="cov" class="absolute" style=" width: 494px; height: 155px; left: 47px; top: 96px; z-index: 1;">
                            <img id="cover1" src="web/activity/yangniansongfu/image/xh4_n.png?v=1" style=" width: 494px; height: 155px; display: block;">
                            <img id="cover2" src="web/activity/yangniansongfu/image/xh5_n.png?v=1" style=" width: 494px; height: 155px; display: none;">
                            <img id="cover3" src="web/activity/yangniansongfu/image/xh6_n.png?v=1" style=" width: 494px; height: 155px; display: none;">
                            <img id="cover4" src="web/activity/yangniansongfu/image/xh7_n.png?v=1" style=" width: 494px; height: 155px; display: none;">
                        </div>
                        <div class="absolute" style=" left: 50px; top: 140px; z-index: 0;">
                            <img id="woed" src="web/activity/yangniansongfu/image/xh13_n.png">
                        </div>
                        <div id="need" class="absolute" style="color: #997127; width: 33px; height: 19px; left: 285px; top: 269px; font-size: 19px; line-height: 19px; text-align: center;">
                            <?php echo $need;?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 活动说明 -->
            <div id="table_2" style="display: none;">
                <div style="color: #fff; width: 1000px; text-align: left; margin: 100px auto; font-size: 36px;">
                    <div style="padding: 10px;">活动内容:</div>
                    <div style="padding: 10px;">1.每天玩牌可获得刮刮卡中奖机会，奖品如下</div>
                    <div style="padding: 10px;">一等奖：iphone6</div>
                    <div style="padding: 10px;">二等奖：话费礼包</div>
                    <div style="padding: 10px;">三等奖：游戏金币</div>
                    <div style="padding: 10px;">2.玩牌每玩一局积累1洋气值</div>
                    <div style="padding: 10px;">3.每天限刮五次，零点洋气值清零</div>
                    <div style="padding: 30px 10px;">最终解释权归点乐游戏所有</div>
                </div>
            </div>
        </div>
        <!-- 中奖提示 -->
        <div id="mess" class="absolute" style=" background-color: black; left: 0; top: 0; opacity: 0.6; z-index: 2; width: inherit; height: inherit; display: none;"></div>
        <div id="message" class="absolute" style="left: 460px; top: 220px; z-index: 99; display: none;">
            <div style=" width: 344px; height: 344px; margin: auto;">
                <div id="shine1">
                </div>
                <div id="shine2">
                </div>
            </div>
            <div id="bigger" class="absolute" style="left: -53px; top: 92px; display: none;">
                <img src="web/activity/yangniansongfu/image/xh8_n.png" style=" display: block; margin-left: 155px; padding-bottom: 20px;">
                <img src="web/activity/yangniansongfu/image/xh11_n.png" style=" display: block">
            </div>
            <div id="big" class="absolute" style="left: -53px; top: 120px; display: none;">
                <img src="web/activity/yangniansongfu/image/xh9_n.png" style=" display: block; margin: auto; padding-bottom: 45px;">
                <img src="web/activity/yangniansongfu/image/xh10_n.png" style=" display: block">
            </div>
        </div>
        <div id="caution" class="absolute" style="left:50%;top:50%; z-index: 4; margin: -50px 0 0 -175px; display: none;">
        	<div class="relative">
		        <div style="background-color:#000; border-radius:30px; width:350px; height:105px; opacity:0.8;"></div>
		        <span id="cmessage" class="absolute" style="color:#FFF; line-height:105px; font-size: 30px; z-index:100; left:0; top:0; width:350px; height:105px; line-height:105px;"></span>
	       	</div>
       	</div>
       	<div id="caution" class="absolute" style="left:50%;top:50%; z-index: 4; margin: -50px 0 0 -175px; display: none;">
        	<div class="relative">
		        <div style="background-color:#000; border-radius:30px; width:350px; height:105px; opacity:0.8;"></div>
		        <span id="cmessage" class="absolute" style="color:#FFF; line-height:105px; font-size: 30px; z-index:100; left:0; top:0; width:350px; height:105px; line-height:105px;"></span>
	       	</div>
       	</div>
    </div>
</body>
<script type="text/javascript">
$(this).ready(function(){
	//头部卡片切换
	$(".tab").click(function(){
		var now_tab = $(this).attr("id").replace("tab_","");
		$("#table_1").hide();
		$("#table_2").hide();
		$("#tab_1").css("background-image","url(web/activity/image/caidan_leave.png)");
		$("#tab_2").css("background-image","url(web/activity/image/caidan_leave.png)");
		if(now_tab==1){
			$("#tab_1").css("background-image","url(web/activity/image/caidan_on.png)");
			$("#table_1").show();
		}else {
			$("#tab_2").css("background-image","url(web/activity/image/caidan_on.png)");
			$("#table_2").show();
		}
	});
	
    //按键效果
    var i=0;
    var prize='';
    var surplus='';
    var need='';
	
    $("#cov").click(function(){
		i++;
	    switch(i){
	    case 1:
		    if(<?php echo $surplus;?> >= <?php echo $need;?>){
                $.ajax({
                	type: 'POST', 
                    url: "<?php echo $url ?>?m=activity&p=dispath&fpath=yangniansongfu&act=go", 
                    data:"gameid=<?php echo $gameid?>"
        				+"&mid=<?php echo $mid?>"
        				+"&sid=<?php echo $sid?>"
        				+"&cid=<?php echo $cid?>"
        				+"&pid=<?php echo $pid?>"
        				+"&ctype=<?php echo $ctype?>",
                    dataType: 'json',
                    error: function(){ 
                    	$("#cov").unbind('click').css("cursor","default");
                    	$("#cmessage").text("出错了!");
                    	$("#caution").stop(true,true).fadeIn(100);
        				$("#caution").stop(true,true).fadeOut(3000);
                    }, 
                    success:function(json){
                        var p = json.lottery;     //奖项 
                        var s = json.status;    //状态
                        var n = json.need;      //需要羊气
                        var su= json.surplus    //剩余羊气
                        if (s!='1'){
                        	$("#cov").unbind('click').css("cursor","default");
                        	if(s == '-1'){
                        		$("#cmessage").text("抽奖次数达到上限！");
                            	$("#caution").stop(true,true).fadeIn(100);
                				$("#caution").stop(true,true).fadeOut(3000);
                            }else if(s == '-2'){
                            	$("#cmessage").text("羊气不足！");
                            	$("#caution").stop(true,true).fadeIn(100);
                				$("#caution").stop(true,true).fadeOut(3000);
                            }else {
                            	$("#cmessage").text("出错了！("+s+")");
                            	$("#caution").stop(true,true).fadeIn(100);
                				$("#caution").stop(true,true).fadeOut(3000);
                            }
                        }else {
                        	$("#cover1").hide();
                	    	$("#cover2").show();
                	    	need    = n;
                	    	surplus = su;
                	    	prize   = p;
                        }
                    }
                });
		    }else {
		    	$("#cmessage").text("羊气不足!玩牌可获得羊气");
            	$("#caution").stop(true,true).fadeIn(100);
				$("#caution").stop(true,true).fadeOut(3000);
				i = 0;
			}
	    	break;
	    case 2:
	    	$("#cover2").hide();
	    	$("#cover3").show();
	    	break;
	    	
	    case 3:
	    	$("#cover3").hide();
	    	$("#cover4").show();
	    	$("#cover4").hide();
	    	setTimeout(function(){
		    	$("#cover1").show();
		    	$("#surplus").text(surplus);
		    	$("#need").text(need);
		    	shine(prize);
		    	i = 0;
	           },300);
        	break;
	    }
	});
	

    function shine(prize){
    	$("#bigger").hide();
    	$("#big").hide();
    	if (prize == '8888'){
    	    $("#bigger").show();
        }else {
            $("#big").show();
        }
    	$("#mess").show();
    	$("#message").show();
    	
      	$("#shine1").html("<img class='absolute transform' src='web/activity/yangniansongfu/image/jiesuan2-3_n.png' style='display: block; margin: auto; z-index: -1;'>");
      	$("#shine2").html("<img class='absolute transform' src='web/activity/yangniansongfu/image/jiesuan2-3_n.png' style='display: block; margin: auto; z-index: -1;'>");//.append(new2);

    	setTimeout(function(){
         $("#shine1").children("img").addClass("turnRight");
         $("#shine2").children("img").addClass("turnLeft");
		},1);

        setTimeout(function(){
         $("#mess").hide();
         $("#message").hide();
        },3000);
    };
	
});
</script>
</html>