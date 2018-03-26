<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>幸运充值,金喜不断</title>
<script type="text/javascript" src="web/activity/xingyunchongzhi/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="web/activity/xingyunchongzhi/js/jQueryRotate.2.2.js"></script>
<script type="text/javascript" src="web/activity/xingyunchongzhi/js/jquery.easing.min.js"></script>
<style type="text/css">
body {
	               margin: 0 auto;
	               padding: 0;
	               text-align: center;
}
a {
	               text-decoration: none;
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

</style>
</head>
<body>
    <div id="main" class="relative" style="width: 1280px;height: 800px; margin: 0 auto; background: url(web/activity/xingyunchongzhi/image/bg.jpg);">
        <div id="header" style="height: 74px;background: url(web/activity/xingyunchongzhi/image/head_ddz.png)">
            <a id="tab_2" class="tab float_right" href="javaScript:;" style="background: url(web/activity/xingyunchongzhi/image/caidan_leave.png);margin-right: 190px;">活动介绍</a>
            <a id="tab_1" class="tab float_right" href="javaScript:;" style="background: url(web/activity/xingyunchongzhi/image/caidan_on.png);margin-right: 15px;">活动</a>
        </div>
        <div id="content" class="relative">
            <div id="table_1" >
                <div class="absolute" style="left: 125px; top: 30px">
                    <div class="relative">
                        <div id="startbg" style=" z-index: 1;"><img style="display: block;" src="web/activity/xingyunchongzhi/image/xyzp.png"></div>
                        <div id="startbtn" class="absolute" style="left: 170px; top: 110px; z-index: 2;">
                            <img src="web/activity/xingyunchongzhi/image/yxpzz-leave.png">
                            <div id="cost" class="absolute" style="width: 199px; margin-left: 20px; top: 202px; font-size: 24px; color: #FFBF3F;"></div>
                        </div>
                    </div>
                </div>
                <div class="absolute" style="left: 652px; top: 16px;">
                    <div class="relative" style="text-align: center;">
                        <div>
                            <img src="web/activity/xingyunchongzhi/image/title.png">
                        </div>
                        <div class="relative" style="height: 150px;">
                            <div id="pl" class="absolute" style="left: 130px; top: 70px;">
                                <img src="web/activity/xingyunchongzhi/image/zdcq.png" style="display: block; margin:auto">
                            </div>
                            <div id='re' class="absolute" style="left: 115px; top: 5px; display: none">
                                <img src="web/activity/xingyunchongzhi/image/charge_rate.png" style="display: block; margin:auto">
                                <img id="rate" src="web/activity/xingyunchongzhi/image/1.1.png" style="display: block; margin:25px auto">
                            </div>
                        </div>
                        <div>
                            <a id="go" href="javaScript:;" style="background: url(web/activity/xingyunchongzhi/image/charge-leave.png); width: 354px; height: 120px; display: block; margin: auto;"></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 活动说明 -->
            <div id="table_2" style="display: none;">
                <div style="color: #fff; width: 1000px; text-align: left; margin: 100px auto; font-size: 36px;">
                    <div style="padding: 10px;">活动内容:</div>
                    <div style="padding: 10px;">1.玩家需要转动幸运轮盘，随机抽取今日充值的折扣率</div>
                    <div style="padding: 10px;">2.折扣率不叠加，以最后一次计算</div>
                    <div style="padding: 10px;">3.转动幸运轮盘所消耗的金币数，每次以200金币递增。</div>
                    <div style="padding: 10px;">4.每日充值返利的最大次数为5次。</div>
                    <div style="padding: 10px;">5.五次之后倍率清零，活动规则每日凌晨刷新。</div>
                </div>
            </div>
        </div>
        <div id="mess" class="absolute" style="left:50%;top:50%; z-index: 4; margin: 0 0 0 -225px; display: none;">
        	<div class="relative">
		        <div style="background-color:#000; border-radius:30px; width:450px; height:105px; opacity:0.8;"></div>
		        <span id="message" class="absolute" style="color:#FFF; line-height:100px; font-size: 34px; z-index:100; left:0; top:0; width:450px; height:105px; line-height:105px;"></span>
	       	</div>
       	</div>
       	<div id="congra" class="absolute" style="background: url(web/activity/xingyunchongzhi/image/xyczk.png); width: 492px; height: 165px; left: 50%; top: 50%; margin: -82px 0 0 -246px; z-index: 99; display: none;">
       	    <img style="margin: 40px auto 10px;" src="web/activity/xingyunchongzhi/image/get.png">
       	    <img src="web/activity/xingyunchongzhi/image/timeout.png">
       	    <img id="showrate" class="absolute" style="left: 150px; top: 27px;" src="web/activity/xingyunchongzhi/image/a1.1.png">
       	</div>
    </div>
</body>
<script type="text/javascript">
$(this).ready(function(){
	/*var j=0;
	
	function funa(){
		if(j%2==0){
			$("#pl").animate({left:'100px'},"500");
		}else {
			$("#pl").animate({left:'130px'},"500");
		}
		
		j++;
	}
	//循环执行，每隔0.4秒钟执行一次*/
	
	//头部卡片切换
	$(".tab").click(function(){
		var now_tab = $(this).attr("id").replace("tab_","");
		$("#table_1").hide();
		$("#table_2").hide();
		$("#tab_1").css("background-image","url(web/activity/xingyunchongzhi/image/caidan_leave.png)");
		$("#tab_2").css("background-image","url(web/activity/xingyunchongzhi/image/caidan_leave.png)");
		if(now_tab==1){
			$("#tab_1").css("background-image","url(web/activity/xingyunchongzhi/image/caidan_on.png)");
			$("#table_1").show();
		}else {
			$("#tab_2").css("background-image","url(web/activity/xingyunchongzhi/image/caidan_on.png)");
			$("#table_2").show();
		}
	});
	
    //按键效果
    $(".butt").mousedown(function(){
    	var id = $(this).attr("id");
		$(this).css("background-image","url(web/activity/xingyunchongzhi/image/"+id+"-on.png)");
        });
    $(".butt").mouseup(function(){
    	var id = $(this).attr("id");
		$(this).css("background-image","url(web/activity/xingyunchongzhi/image/"+id+"-leave.png)");
        });
	$("#close").mouseup(function(){
	    $("#warning").hide();
		});

	//抽奖消耗及中奖倍率显示
    function cost(money,rate){
        if(money>1000){
            $("#startbtn").unbind('click').css("cursor","default");
            $("#cost").html('抽奖机会已用完');
        }else {
        	$("#startbtn").click(function(){ 
                lottery(); 
            });
        	$("#cost").html('消耗'+money+'金币');
        }
        if(rate>0){
        	$('#pl').hide();
        	$('#rate').attr('src', 'web/activity/xingyunchongzhi/image/'+rate+'.png');
        	$('#re').show();
        }
    }
    
    cost(<?php echo $moneyNum?>*200+200,<?php echo $rate?>);
    
	function lottery(){
		$("#cost").html('');
        $.ajax({ 
            type: 'POST', 
            url: "<?php echo $url ?>?m=activity&p=dispath&fpath=xingyunchongzhi&mtkey=<?php echo $mtkey?>&act=go", 
            data:"gameid=<?php echo $gameid?>"
				+"&mid=<?php echo $mid?>"
				+"&sid=<?php echo $sid?>"
				+"&cid=<?php echo $cid?>"
				+"&pid=<?php echo $pid?>"
				+"&ctype=<?php echo $ctype?>",
            dataType: 'json',
            error: function(){ 
            	$("#message").text("出错了!");
				$("#mess").stop(true,true).fadeIn(100);
				$("#mess").stop(true,true).fadeOut(3000);
                return false; 
            }, 
            success:function(json){ 
            	$("#startbtn").unbind('click').css("cursor","default"); 
                var a = json.angle; //角度 
                var p = json.prize; //奖项 
                var m = json.moneyNum; //抽奖次数
                var s = json.status; //状态
                if(s=='1'){
                    $("#startbg").rotate({ 
                        duration:5000, //转动时间 
                        angle: 0, 
                        animateTo:3600+a, //转动角度 
                        easing: $.easing.easeOutSine, 
                        callback: function(){
                            cost(m*200+200,p);//改变抽奖消耗及中奖倍率
                            $("#showrate").attr('src', 'web/activity/xingyunchongzhi/image/a'+p+'.png');
            				$("#congra").stop(true,true).fadeIn();
            				setTimeout(function(){
            					$("#congra").stop(true,true).fadeOut(1000);
            				},2000);
            				
                        } 
                    }); 
                }else {
                	$("#message").text("出错了!("+s+")");
    				$("#mess").stop(true,true).fadeIn(100);
    				$("#mess").stop(true,true).fadeOut(3000);
                }
            } 
        }); 
	}
	
	$("#go").click(function(){
		if(<?php echo $cid?>!=121){
			  window.location.href="http://www.dianlergame.com/paycenter/index.php?m=pay&p=index&act=list&gameid=<?php echo $gameid?>&mid=<?php echo $mid?>&sid=<?php echo $sid?>&cid=<?php echo $cid?>&pid=<?php echo $pid?>&ctype=<?php echo $ctype?>&versions=&mnick=<?php echo $mnick?>&money=<?php echo $money?>"; 
		}else {
			  $("#message").text("请前往商城内进行充值哦！");
			  $("#mess").stop(true,true).fadeIn(100);
			  setTimeout(function(){
				  $("#mess").stop(true,true).fadeOut(3000);
			  },2000);
		}
	});
});

</script>
</html>
