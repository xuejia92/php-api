<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>礼包兑换</title>
<script type="text/javascript" src="web/activity/js/jquery-2.1.1.min.js"></script>
<style type="text/css">
    
body {
	               margin: 0 auto;
	               padding: 0;
	               text-align: center;
	               -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
	               color: #f9d56a;
	               background-color: #350304;
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

.price {
	position: absolute;
	left: 0;
    right: 0;
    bottom: -10px;
    margin: auto;
}

.sign {
	position: absolute;
    left: -10px;
    top: 20px;
}


</style>
</head>
<body>
    <div class="absolute" style="width: 100%; height: 100%; margin: 0 auto; background: url(web/activity/cardEmploy/image/bg.jpg) no-repeat; background-size: 100%; z-index: -1; top: 0;"></div>
    <div id="main" style="position: absolute; width: 100%; height: 100%;">
        <div id="content" class="relative" style="top: 37%;">
            <div style="background: url(web/activity/cardEmploy/image/input_bg.png) no-repeat; width: 517px; height: 188px; margin: auto; left: 0; right: 0; top: 50%; padding: 1px;">
                <div style="background: url(web/activity/cardEmploy/image/input.png) no-repeat; width: 462px; height: 67px; margin: auto; margin-top:18px;">
                    <input id="cardno" type="text" maxlength="8" value="请输入礼包号" onfocus="this.value=''" oninput="value=value.replace(/[\W]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" style="background-color: transparent; border: 0; outline: none; width: 426px; height: 67px; line-height: 67px; color: #f9d56a; font-size: 23px; text-align: center;">
                </div>
                <div style=" margin-top:18px;">
                    <a id="getBonus" href="javaScript:;" style="background: url(web/activity/cardEmploy/image/employ.png); width: 462px; height: 67px; display: block; margin: auto;"></a>
                </div>
            </div>
            <div class="absolute" style="top: 210px; left: 0; right: 0; font-size: 23px; color:#9b2f0a;">注：活动期间每个玩家仅限兑换一次</div>
        </div>
        <div id="caution" class="absolute" style="width: 100%; height: 100%; left: 0; top: 0; display: none;">
             <div style="width: 100%; height: 100%; left: 0; top: 0; background: #000; opacity:0.7;"></div>
             <div style="background: url(web/activity/cardEmploy/image/caution.png); width: 534px; height: 303px; margin: auto; position: absolute; left: 0; right: 0; top: 30%;">
                <div id="success" style="display: none;">
                    <p style="font-size: 30px; margin: 60px 0 10px 0;">恭喜您获得礼包奖励</p>
                    <p id="bonus" style="font-size: 40px; margin-top: 0;"></p>
                </div>
                <div id="fail">
                    <p style="height: 155px; line-height: 155px; font-size: 35px; margin-bottom: 0;">请输入正确的礼包号</p>
                </div>
                <div>
                    <a id="ensure" href="javaScript:;" style="background: url(web/activity/cardEmploy/image/ensure.png); width: 222px; height: 67px; display: block; margin: auto;"></a>
                </div>
             </div>
        </div>
    </div>
</body>
<script type="text/javascript">

$(this).ready(function(){
	var status = <?php echo $status?>;

    function cover(status){
        if(status==0){
            $("#getBonus").css("background-image","url(web/activity/cardEmploy/image/employ.png)");
            getBonus();
        }else {
        	$("#getBonus").css("background-image","url(web/activity/cardEmploy/image/employ-disable.png)");
        }
    }

    cover(status);
	
	$("#ensure").mouseup(function(){
	    $("#caution").hide();
	});
	
	function getBonus(){
	    $("#getBonus").mouseup(function(){
		    var cardno = $("#cardno").val();
		    var length = cardno.length;
		    if (length==8 && status==0){
		    	$("#getBonus").unbind('mouseup').css("cursor","default");
		        $.ajax({
		            type: "POST",
		            url: "<?php echo $url?>?m=activity&p=dispath&fpath=cardEmploy&mtkey=<?php echo $mtkey?>&act=employ",
		            dataType: 'json',
		            data: "gameid=<?php echo $gameid ?>"
                        +"&mid=<?php echo $mid ?>"
                        +"&sid=<?php echo $sid?>"
        				+"&cid=<?php echo $cid?>"
        				+"&pid=<?php echo $pid?>"
        				+"&ctype=<?php echo $ctype?>"
        				+"&cardno="+cardno,
    				success: function(json){
        				var result = json.result;
        				var money  = json.exchangenum;
        				var vip    = json.exptime;

        				var viptime = '';
        			    if(vip!='0'){
        			        viptime = ' + 会员'+vip+'天'
        			    }
        				
    				    if (result==1){
        				    $("#bonus").text(money+'金币'+viptime);
    				        $("#caution").show();
    				        $("#fail").hide();
    				        $("#success").show();
    				        status = 1;
    				        cover(1);
    				    }else {
    				    	$("#caution").show();
    				        $("#success").hide();
    				        $("#fail").show();
    				        cover(0);
    				    }
    				}
		        })
		    }else {
		    	$("#caution").show();
		        $("#success").hide();
		        $("#fail").show();
		    }
	    });
	}
});
</script>
</html>