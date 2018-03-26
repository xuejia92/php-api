<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<script type="text/javascript" src="web/activity/js/jquery-2.1.1.min.js"></script>
<style type="text/css">
    
body {
	               margin: 0 auto;
	               padding: 0;
	               text-align: center;
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
    <div id="main" class="relative" style="width: 1280px;height: 800px; margin: 0 auto; background: url(web/activity/duanwu/image/bg.jpg);">
        <div id="content" class="relative" style=" width: 1280px; height: 726px;">
            <div id="header" style="height: 74px;background: url(web/activity/image/head_ddz.png)"></div>
            <div id="table_1" >
                <div style="margin-top: 90px;">
                    <div id="zongzi1" class="zongzi relative" style="display: inline-block; margin: 0 5px;">
                        <img id="picture1" src="web/activity/duanwu/image/01-disable.png">
                        <img id="had1" class="sign" src="web/activity/duanwu/image/had.png" style=" display: none;">
                        <img class="price" src="web/activity/duanwu/image/5.png">
                    </div>
                    <div id="zongzi2" class="zongzi relative" style="display: inline-block; margin: 0 5px;">
                        <img id="picture2" src="web/activity/duanwu/image/02-disable.png">
                        <img id="had2" class="sign" src="web/activity/duanwu/image/had.png" style=" display: none;">
                        <img class="price" src="web/activity/duanwu/image/14.png">
                    </div>
                    <div id="zongzi3" class="zongzi relative" style="display: inline-block; margin: 0 5px;">
                        <img id="picture3" src="web/activity/duanwu/image/03-disable.png">
                        <img id="had3" class="sign" src="web/activity/duanwu/image/had.png" style=" display: none;">
                        <img class="price" src="web/activity/duanwu/image/16.png">
                    </div>
                    <div id="zongzi4" class="zongzi relative" style="display: inline-block; margin: 0 5px;">
                        <img id="picture4" src="web/activity/duanwu/image/04-disable.png">
                        <img id="had4" class="sign" src="web/activity/duanwu/image/had.png" style=" display: none;">
                        <img class="price" src="web/activity/duanwu/image/30.png">
                    </div>
                    <div id="zongzi5" class="zongzi relative" style="display: inline-block; margin: 0 5px;">
                        <img id="picture5" src="web/activity/duanwu/image/05-disable.png">
                        <img id="had5" class="sign" src="web/activity/duanwu/image/had.png" style=" display: none;">
                        <img class="price" src="web/activity/duanwu/image/50.png">
                    </div>
                </div>
                <div>
                    <div style="background: url(web/activity/duanwu/image/asd.png); width: 637px; height: 89px; margin: 45px auto; text-align: left;">
                        <div style="display: inline-block; color: #E5D9B3;font-size: 30px; line-height: 89px; vertical-align: bottom; margin:0 10px 0 20px;">
                            <span id="tili">体力：<?php echo $tili ? $tili : 0?></span>
                            <span id="ticket">话费券：<?php echo $ticket ? $ticket : 0?></span>
                        </div>
                        <div style="display: none; margin: 10px auto; background: url(web/activity/duanwu/image/get.png); width: 182px; height: 69px; line-height:69px;"></div>
                        <div style="display: inline-block; margin: 10px auto; background: url(web/activity/duanwu/image/get-disable.png); width: 182px; height: 69px; line-height:69px; text-align: center; color: #E5D9B3; font-size: 40px;">兑换</div>
                    </div>
                </div>
            </div>
        </div>
        <div id="caution" class="absolute" style="width: 100%; height: 100%; left: 0; top: 0; display: none;">
            <div style="width: 100%; height: 100%; left: 0; top: 0; background: #000; opacity:0.5;"></div>
            <div class="absolute" style="background: url(web/activity/duanwu/image/kuang.png); width: 502px; height: 260px; margin: auto; top: 0; left: 0; bottom: 0; right: 0;">
                <h1 id="head" style="color: #335c04; margin: 60px 10px 0 10px;">体力不足，玩牌可获得体力</h1>
                <a id="closeButton" href="javaScript:;" style="background: url(web/activity/duanwu/image/get.png); width: 182px; height: 69px; display: block; position: absolute; bottom: 15%; left: 0; right: 0; margin: auto; font-size: 40px; color: #fff; line-height: 69px;">确定</a>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
$(this).ready(function(){
	var tili    = <?php echo $tili ? $tili : 0?>;
	var ticket  = <?php echo $ticket ? $ticket : 0?>;
	var cost    = <?php echo $cost ? $cost : 0?>;
    var one     = <?php echo $one?>;
    var two     = <?php echo $two?>;
    var three   = <?php echo $three?>;
    var four    = <?php echo $four?>;
    var five    = <?php echo $five?>;
    var sx      = <?php echo $SX?>;
    
    function hide(cost,one,two,three,four,five){
    	if(cost<5){
        	$("#picture1").attr('src','web/activity/duanwu/image/01.png');
        }else {
        	$("#picture1").attr('src','web/activity/duanwu/image/01-disable.png');
        }

        if(cost>=5 && cost<19){
            console.log(cost);
            $("#picture2").attr('src','web/activity/duanwu/image/02.png');
        }else {
        	$("#picture2").attr('src','web/activity/duanwu/image/02-disable.png');
        }

        if(cost>=19 && cost<35){
        	$("#picture3").attr('src','web/activity/duanwu/image/03.png');
        }else {
        	$("#picture3").attr('src','web/activity/duanwu/image/03-disable.png');
        }

        if(cost>=35 && cost<65){
        	$("#picture4").attr('src','web/activity/duanwu/image/04.png');
        }else {
        	$("#picture4").attr('src','web/activity/duanwu/image/04-disable.png');
        }
        
        if(cost>=65 && cost<115){
        	$("#picture5").attr('src','web/activity/duanwu/image/05.png');
        }else {
        	$("#picture5").attr('src','web/activity/duanwu/image/05-disable.png');
        }

        if(one){
            $("#had1").show();
        }
        if(two){
            $("#had2").show();
        }
        if(three){
            $("#had3").show();
        }
        if(four){
            $("#had4").show();
        }
        if(five){
            $("#had5").show();
        }
    }

    hide(cost,one,two,three,four,five);
    
	
    $("#closeButton").click(function(){
        $("#caution").hide();
    });


	function getPrize(){
        $(".zongzi").click(function(){
            var tapType = $(this).attr("id").replace("zongzi","");
            switch (tapType){
                case '1':
                    var number  = one;
                    var need    = 5;
                    break;
                case '2':
                    var number  = two;
                    var need    = 14;
                    break;
                case '3':
                    var number  = three;
                    var need    = 16;
                    break;
                case '4':
                    var number  = four;
                    var need    = 30;
                    break;
                case '5':
                    var number  = five;
                    var need    = 50;
                    break;
            }

            if (number==0 && tapType==sx + 1){
                if (need<=tili){
                	$(".zongzi").unbind('click').css("cursor","default");
                    $.ajax({
                    	type: "POST",
                        url: "<?php echo $url ?>?m=activity&p=dispath&fpath=duanwu&mtkey=<?php echo $mtkey?>&act=set",
                        dataType: "json",
                        data: "gameid=<?php echo $gameid ?>"
                            +"&mid=<?php echo $mid ?>"
                            +"&sid=<?php echo $sid?>"
            				+"&cid=<?php echo $cid?>"
            				+"&pid=<?php echo $pid?>"
            				+"&ctype=<?php echo $ctype?>"
            				+"&tapType="+tapType,
            			success: function(json){
                			if (json.status == 1){
                				tili    = json[0];
                				ticket  = json[1];
                				cost    = json[2];
                			    one     = json[3];
                			    two     = json[4];
                			    three   = json[5];
                			    four    = json[6];
                			    five    = json[7];
                			    sx      = json[8];

                			    hide(cost,one,two,three,four,five);
                			    
                			    $("#tili").text('体力：'+tili);
                			    $("#ticket").text('话费券：：'+ticket);
                			    
                			    if (json.type == 'money'){
                			        var type = ' 金币！';
                			    }else {
                			        var type = ' 话费券！';
                			    }
                			    $("#head").text('恭喜你获得 '+json.amount+type);
                                $("#caution").show()
                			}else {
                				$("#head").text('出错了 （ '+json.status+'）');
                                $("#caution").show()
                			}
                            getPrize();
            			}
                    });
                }else {
                    $("#head").text('体力不足，玩牌可获得体力');
                    $("#caution").show()
                }
            }else if (tapType>sx + 1){
            	$("#head").text('请先剥开前一个粽子');
                $("#caution").show()
            }
        });
	}

	getPrize();
});
</script>
</html>