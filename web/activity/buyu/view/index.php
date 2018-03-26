<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>清爽夏日，捕鱼来袭</title>
<script type="text/javascript" src="web/activity/js/jquery-2.1.1.min.js"></script>
<style type="text/css">
    
body {
	               margin: 0 auto;
	               padding: 0;
	               text-align: center;
	               background-color: #D77133;
	               -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
}
a {
	               text-decoration: none;
                   -webkit-tap-highlight-color: rgba(0,0,0,0);
}
td  {
	               border: solid 1px;
	               padding: 10px;
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

.kuang {
	               background: url(web/activity/buyu/image/yh3_n.png);
	               width: 112px;
	               height: 110px;
	               vertical-align: middle;
	               margin: 0 10px;
	               display: inline-block;
	               position: relative;
}

.kuang2 {
	               background: url(web/activity/buyu/image/yh2_n.png);
	               width: 275px;
	               height: 72px;
	               vertical-align: middle;
	               text-align: left;
	               margin: 0 10px;
                   display: inline-block;
	               position: relative;
}

.kuang3 {
                   width: 112px;
                   height: 32px;
                   margin: auto;
	               text-align: center;
                   position: absolute;
                   top: 60px;
                   z-index: 1;
}

.kuang4 {
                   width: 115px;
                   height: 32px;
                   margin: auto;
	               text-align: center;
                   position: absolute;
	               left: 75px;
                   top: 0;
                   bottom: 0;
                   z-index: 1;
}

.button {
	               background: url(web/activity/buyu/image/yh10_n.png);
	               width: 177px;
	               height: 82px;
	               vertical-align: middle;
	               margin: 0 10px;
	               display: inline-block;
	
}

</style>
</head>
<body>
    <div id="main" class="relative" style="width: 1280px;height: 720px; margin: 0 auto; background: url(web/activity/buyu/image/bg.jpg);">
        <div id="content" style=" width: 1280px; height: 720px; left: 0; top: 0;">
            <div class="absolute" style="background: url(web/activity/buyu/image/yh1_n.png); width: 1280px; height: 668px; margin: auto; bottom: 20px;">
                <div style="width: 660px; margin: auto; margin-top: 185px;">
                    <div>
                        <img src="web/activity/buyu/image/yh8_n.png">
                    </div>
                    <div style="margin: 10px auto;">
                        <div class="kuang">
                            <img src="web/activity/buyu/image/yh4_n.png" style="margin-top: 5px;">
                            <div id="fish1Had" class="kuang3"></div>
                        </div>
                        <div class="kuang2">
                            <img src="web/activity/buyu/image/yh7_n.png" style="margin: 22px 0 22px 15px;">
                            <div id="fish1Bonus" class="kuang4"></div>
                        </div>
                        <a id="button1" class="button" href="javaScript:;"></a>
                    </div>
                    <div style="margin: 10px auto;">
                        <div class="kuang">
                            <img src="web/activity/buyu/image/yh5_n.png" style="margin-top: 7px;">
                            <div id="fish2Had" class="kuang3"></div>
                        </div>
                        <div class="kuang2">
                            <img src="web/activity/buyu/image/yh7_n.png" style="margin: 22px 0 22px 15px;">
                            <div id="fish2Bonus" class="kuang4"></div>
                        </div>
                        <a id="button2" class="button" href="javaScript:;"></a>
                    </div>
                    <div style="margin: 10px auto;">
                        <div class="kuang">
                            <img src="web/activity/buyu/image/yh6_n.png" style="margin-top: 7px;">
                            <div id="fish3Had" class="kuang3"></div>
                        </div>
                        <div class="kuang2">
                            <img src="web/activity/buyu/image/yh7_n.png" style="margin: 22px 0 22px 15px;">
                            <div id="fish3Bonus" class="kuang4"></div>
                        </div>
                        <a id="button3" class="button" href="javaScript:;"></a>
                    </div>
                </div>
            </div>
        </div>
        <div id="caution" class="absolute" style="width: 100%; height: 100%; left: 0; top: 0; display: none; z-index: 2;">
            <div style="width: 100%; height: 100%; left: 0; top: 0; background: #000; opacity:0.5;"></div>
            <div class="absolute" style="background: url(web/activity/duanwu/image/kuang.png); width: 502px; height: 260px; margin: auto; top: 0; left: 0; bottom: 0; right: 0;">
                <h1 id="head" style="color: #335c04; margin: 60px 10px 0 10px;">恭喜您获得888金币奖励</h1>
                <a id="closeButton" href="javaScript:;" style="background: url(web/activity/duanwu/image/get.png); width: 182px; height: 69px; display: block; position: absolute; bottom: 15%; left: 0; right: 0; margin: auto; font-size: 40px; color: #fff; line-height: 69px;">确定</a>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
$(this).ready(function(){

    $("#caution").mouseup(function(){
        $("#caution").hide();
    });
	
	//用图片替代数字
	function to_img (str,number){
		var number= number;
		var len   = number+'';
		
		var imgs=['0.png','1.png','2.png','3.png','4.png','5.png','6.png','7.png','8.png','9.png'];
		var ns='0123456789'.split('');
		var output='';
		var number=number+'';
		for(var i=0;i<number.length;i++){
			for(var j=0;j<ns.length;j++){
				if(number[i]==ns[j]){
				output+="<img src=web/activity/buyu/image/"+str+imgs[j]+" style='vertical-align: middle;'/>";
				break;
		   		}
		  	}
		}
		return output;
	}

	var fish1Bonus = to_img('yh13-',888);
	var fish2Bonus = to_img('yh13-',1888);
	var fish3Bonus = to_img('yh13-',2888);

	$("#fish1Bonus").html(fish1Bonus);
	$("#fish2Bonus").html(fish2Bonus);
	$("#fish3Bonus").html(fish3Bonus);

    var fish1       = <?php echo $fish1>20 ? 20 : $fish1?>;
	var fish2       = <?php echo $fish2>10 ? 10 : $fish2?>;
    var fish3       = <?php echo $fish3>3 ? 3 : $fish3?>;
	
    var fish1Had    = to_img('yh14-',fish1);
    var fish2Had    = to_img('yh14-',fish2);
    var fish3Had    = to_img('yh14-',fish3);
    
    var fish1Need   = to_img('yh14-',20);
    var fish2Need   = to_img('yh14-',10);
    var fish3Need   = to_img('yh14-',3);
    var gang        = "<img src='web/activity/buyu/image/yh14-10.png' style='vertical-align: middle;'/>";

    $("#fish1Had").html(fish1Had+gang+fish1Need);
	$("#fish2Had").html(fish2Had+gang+fish2Need);
	$("#fish3Had").html(fish3Had+gang+fish3Need);

    var button1Status = <?php echo $button1Status?>;
    var button2Status = <?php echo $button2Status?>;
    var button3Status = <?php echo $button3Status?>;
	
    function buttonStatusChange(button1Status, button2Status, button3Status){
        if (button1Status==1){
            $("#button1").css("background-image","url(web/activity/buyu/image/yh11_n.png)");
            $("#button1").attr("href","javaScript:;");
        }else if (button1Status==2){
        	$("#button1").css("background-image","url(web/activity/buyu/image/yh12_n.png)");
        }else {
        	$("#button1").css("background-image","url(web/activity/buyu/image/yh10_n.png)");
        	$("#button1").attr("href","callback://turntogame4");
        }
        
        if (button2Status==1){
            $("#button2").css("background-image","url(web/activity/buyu/image/yh11_n.png)");
            $("#button2").attr("href","javaScript:;");
        }else if (button2Status==2){
        	$("#button2").css("background-image","url(web/activity/buyu/image/yh12_n.png)");
        }else {
        	$("#button2").css("background-image","url(web/activity/buyu/image/yh10_n.png)");
        	$("#button2").attr("href","callback://turntogame4");
        }
        
        if (button3Status==1){
            $("#button3").css("background-image","url(web/activity/buyu/image/yh11_n.png)");
            $("#button3").attr("href","javaScript:;");
        }else if (button3Status==2){
        	$("#button3").css("background-image","url(web/activity/buyu/image/yh12_n.png)");
        }else {
        	$("#button3").css("background-image","url(web/activity/buyu/image/yh10_n.png)");
        	$("#button3").attr("href","callback://turntogame4");
        }
    }

    buttonStatusChange(button1Status, button2Status, button3Status);

    $(".button").mouseup(function(){
        var id = $(this).attr("id");
        
        var need;
        
    	switch (id){
	    	case 'button1':
		    	need = button1Status;
		    	break;
	    	case 'button2':
		    	need = button2Status;
		    	break;
	    	case 'button3':
		    	need = button3Status;
		    	break;
    	}
    	
    	if(need==1){
     	    var tapType = id.replace("button",'');
     	    
            $.ajax({
                type: "POST",
                url: "<?php echo $url?>?m=activity&p=dispath&fpath=buyu&mtkey=<?php echo $mtkey?>&act=set",
                dataType: "json", 	
    			data:"&gameid=<?php echo $gameid?>"
    				+"&mid=<?php echo $mid?>"
    				+"&sid=<?php echo $sid?>"
    				+"&cid=<?php echo $cid?>"
    				+"&pid=<?php echo $pid?>"
    				+"&ctype=<?php echo $ctype?>"
    				+"&tapType="+tapType,
    			success: function(json){
    			    var status = json.status;
    			    var bonus  = json.bonus;

    			    if (status==1){
    			    	switch (id){
        			    	case 'button1':
            			    	button1Status = 2;
            			    	break;
        			    	case 'button2':
            			    	button2Status = 2;
            			    	break;
        			    	case 'button3':
            			    	button3Status = 2;
            			    	break;
    			    	}
    			    	buttonStatusChange(button1Status, button2Status, button3Status);
    			    	$("#head").text('恭喜您获得'+bonus+'金币奖励');
    			        $("#caution").show();
    			    }else{
    			    	$("#head").text('出错了（'+status+')');
    			        $("#caution").show();
    			    }
    			}
            })
    	}
    })
});
</script>
</html>