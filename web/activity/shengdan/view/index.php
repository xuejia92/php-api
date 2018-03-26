<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>圣诞嘉年华</title>
<script type="text/javascript" src="web/activity/shengdan/js/jquery-2.1.1.min.js"></script>
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
.butt {
	
}
.possible {
	               width: 52px; 
	               height: 38px; 
	               left: 110px; 
	               top: 20px;
}
.to {
	               width: 202px;
                   height: 33px;
	               color:#fff;
	               font-size: 33px;
                   left: 225px;
                   top: 45px;
	               line-height:33px;
}

</style>
</head>
<body>
    <div id="main" class="relative" style="width: 1280px;height: 800px; margin: 0 auto; background: url(web/activity/shengdan/image/bg.png);">
        <div id="header" style="height: 74px;background: url(web/activity/shengdan/image/head_ddz.png)">
            <a id="tab_2" class="tab float_right" href="javaScript:;" style="background: url(web/activity/shengdan/image/caidan_leave.png);margin-right: 190px;">活动介绍</a>
            <a id="tab_1" class="tab float_right" href="javaScript:;" style="background: url(web/activity/shengdan/image/caidan_on.png);margin-right: 15px;">活动</a>
        </div>
        <div id="content" class="relative">
            <div id="table_1" >
                <div id="left-content" class="absolute" style="width: 489px;left: 120px;top: 18px;">
                    <div class="relative" style="width: 489px; height: 201px; background: url(web/activity/shengdan/image/hat.png); margin-bottom: 13px;">
                        <div id="hat" class="absolute possible"></div>
                        <div id="toHat" class="absolute to"></div>
                        <a href="javaScript:;" id="getHat" class="absolute butt getM" style="width: 253px;height: 78px; background: url(web/activity/shengdan/image/getHat-leave.png); left: 200px; top: 100px; display: block;"></a>
                        <img id="hatCover" class="absolute" src="web/activity/shengdan/image/getHat-disable.png" style="width: 253px;height: 78px; left: 200px; top: 100px; z-index: 1; display: none;">
                    </div>
                    <div class="relative" style="width: 489px; height: 201px; background: url(web/activity/shengdan/image/clothes.png); margin-bottom: 13px;">
                        <div id="clothes" class="absolute possible"></div>
                        <div id="toClothes" class="absolute to"></div>
                        <a href="javaScript:;" id="getClothes" class="absolute butt getM"style="width: 253px;height: 78px; background: url(web/activity/shengdan/image/getClothes-leave.png); left: 200px; top: 100px; display: block;"></a>
                        <img id="clothesCover" class="absolute" src="web/activity/shengdan/image/getClothes-disable.png" style="width: 253px;height: 78px; left: 200px; top: 100px; z-index: 1; display: none;">
                    </div>
                    <div class="relative" style="width: 489px; height: 201px; background: url(web/activity/shengdan/image/boots.png);">
                        <div id="boots" class="absolute possible"></div>
                        <div id="toBoots" class="absolute to"></div>
                        <a href="javaScript:;" id="getBoots" class="absolute butt getM" style="width: 253px;height: 78px; background: url(web/activity/shengdan/image/getBoots-leave.png); left: 200px; top: 100px; display: block;"></a>
                        <img id="bootsCover" class="absolute" src="web/activity/shengdan/image/getBoots-disable.png" style="width: 253px;height: 78px; left: 200px; top: 100px; z-index: 1; display: none;">
                    </div>
                </div>
                <div id="right-conten" class="absolute" style="width: 547px; height: 629px; left: 620px; top: 18px;background: url(web/activity/shengdan/image/exchange.png);">
                    <div id="money" class="absolute" style="height: 25px; text-align: left; left: 145px; top: 107px; line-height: 25px; font-size: 25px; color: #76410c; font-weight: bolder;"><?php echo $money ?></div>
                    <div id="maozi" class="absolute" style="width: 142px; height: 109px; left: 34px; top: 143px; display: none;">
                        <img src="web/activity/shengdan/image/maozi-disable.png">
                    </div>
                    <div id="yifu" class="absolute" style="width: 132px; height: 115px; left: 218px; top: 142px; display: none;">
                        <img src="web/activity/shengdan/image/yifu-disable.png">
                    </div>
                    <div id="xiezi" class="absolute" style="width: 127px; height: 117px; left: 386px; top: 140px; display: none;">
                        <img src="web/activity/shengdan/image/xiezi-disable.png">
                    </div>
                    <div class="absolute" style="left: 155px; top: 310px;">
                        <img src="web/activity/shengdan/image/socks.png">
                    </div>
                    <div class="absolute" style="width: 253px;height: 78px; left: 150px; top: 530px;">
                        <a href="javaScript:;" id="get" class="absolute butt" style="width: 253px;height: 78px; background: url(web/activity/shengdan/image/get-leave.png); display: block;">
                            <div id="toSocks" style="width: 223px;height: 78px; line-height: 78px; color: #fff; font-size: 33px; text-align: right;">(<?php echo $have_socks?>)</div>
                        </a>
                        <img id="getCover" class="absolute" src="web/activity/shengdan/image/get-disable.png" style="width: 253px;height: 78px; left: 0; top: 0; z-index: 1; display: none;">
                    </div>
                </div>
            </div>
            <!-- 活动说明 -->
            <div id="table_2" style="display: none;">
                <div style="color: #fff; text-align: left; padding: 30px; font-size: 26px;">
                    <div style="padding: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;每年的12月25日，是基督教徒纪念耶稣诞生的日子，称为圣诞节，是西方国家一年中最盛大的节日，可以和新年相提并论，类似我国过春节。</div>
                    <div style="padding: 10px; color: red;">活动内容:</div>
                    <div style="padding: 10px;">圣诞节将至，圣诞老人准备圣诞礼物时发现少了些配送物资，我们现在就来帮助他吧，同时圣诞老人也准备了圣诞礼物回馈小伙伴哦~</div>
                    <div style="padding: 10px;">3个物资可合成一个装满礼物的圣诞袜哦！圣诞袜礼品包括：6888金币和2小喇叭和1天至尊会员等~</div>
                    <div style="padding: 10px;">同时圣诞爷爷也接受个别物资兑换金币哦！</div>
                    <div style="padding: 10px;">游戏5局获得1个圣诞帽子; 圣诞帽子可兑换688金币；游戏10局获得1件圣诞衣服; 圣诞衣服可兑换1288金币；游戏20局获得1双圣诞靴子; 圣诞衣服可兑换3888金币。</div>
                    <div style="padding: 10px; color: red;">温馨提示：</div>
                    <div style="padding: 10px;">每日最多兑换3次圣诞礼品袜礼品。</div>
                    <div style="padding: 10px;">每日每个物资最多获得5个。</div>
                    <div style="padding: 10px;">获得物资必须当日兑换，次日所有数据重置（重置的物资不予补偿哦）！</div>
                    <div style="padding: 10px; text-align: right;font-size: 18px; padding-right: 70px;">最终解释权归点乐游戏所有</div>
                </div>
            </div>
        </div>
        <!-- 兑换袜子成功提示 -->
        <div id="ff" class="absolute warning" style="background-color: black; left: 0; top: 0; opacity: 0.5; z-index: 2; width: inherit; height: inherit; display: none;"></div>
        <div id="warning" class="absolute warning" style="width: 862px; height:769px; background: url(web/activity/shengdan/image/reward.png); left: 209px; top: 15px; z-index: 3; display: none;"></div>
        <!-- 提示信息 -->
        <div id="mess" class="absolute" style="left:50%;top:50%; z-index: 4; margin: -50px 0 0 -130px; display: none;">
        	<div class="relative">
		        <div style="background-color:#666; border-radius:30px; width:300px; height:100px; opacity:0.7;"></div>
		        <span id="message" class="absolute" style="color:#FFF; line-height:100px; font-size: 30px; z-index:100; left:0; top:0; width:300px; height:100px; line-height:100px;"></span>
	       	</div>
       	</div>
       	<!-- 兑换金币确认 -->
       	<div id="con" class="absolute" style=" width: inherit; height: inherit; z-index: 1; display: none">
       	    <div class="absolute" style=" width: inherit; height: 726px; background-color: black; opacity:0.5;"></div>
       	    <div class="relative" style="width: 468px; height: 276px; left:50%;top:40%; z-index: 4; margin: -138px 0 0 -234px; background: url(web/activity/shengdan/image/confirm.png);">
       	        <div id="number" class="absolute" style="width: 324px; height: 40px; left: 72px; top: 70px;">
       	        </div>
       	        <div class="absolute" style="left: 28px; top: 170px;">
       	            <a href="javaScript:;" id="cancle" class="butt" style="width: 203px;height: 78px; background: url(web/activity/shengdan/image/cancle-leave.png); display: inline-block;"></a>
       	            <a href="javaScript:;" id="ensure" class="butt" style="width: 203px;height: 78px; background: url(web/activity/shengdan/image/ensure-leave.png); display: inline-block;"></a>
       	        </div>
       	    </div>
       	</div>
    </div>
</body>
<script type="text/javascript">
var have_hat        = <?php echo $have_hat?>;
var have_clothes    = <?php echo $have_clothes?>;
var have_boots      = <?php echo $have_boots?>;
var have_socks      = <?php echo $have_socks?>;
var to_hat          = <?php echo $to_hat?>;
var to_clo          = <?php echo $to_clo?>;
var to_boots        = <?php echo $to_boots?>;
$(this).ready(function(){
	//头部卡片切换
	$(".tab").click(function(){
		var now_tab = $(this).attr("id").replace("tab_","");
		$("#table_1").hide();
		$("#table_2").hide();
		$("#tab_1").css("background-image","url(web/activity/shengdan/image/caidan_leave.png)");
		$("#tab_2").css("background-image","url(web/activity/shengdan/image/caidan_leave.png)");
		if(now_tab==1){
			$("#tab_1").css("background-image","url(web/activity/shengdan/image/caidan_on.png)");
			$("#table_1").show();
		}else {
			$("#tab_2").css("background-image","url(web/activity/shengdan/image/caidan_on.png)");
			$("#table_2").show();
		}
	});
	
	//用图片替代数字
	function to_img (number){
		var number=number;
		var imgs=['0.png','1.png','2.png','3.png','4.png','5.png','6.png','7.png','8.png','9.png'];
		var ns='0123456789'.split('');
		var output='';
		var number=number+'';
		for(var i=0;i<number.length;i++){
			for(var j=0;j<ns.length;j++){
				if(number[i]==ns[j]){
				output+="<img src=web/activity/shengdan/image/"+imgs[j]+">";
				break;
		   		}
		  	}
		}
		return output;
	}

	//获得数量
	var hat        = to_img(have_hat); 
	var clothes    = to_img(have_clothes); 
	var boots      = to_img(have_boots);
	var x          = "<img src='web/activity/shengdan/image/x.png'>";
	var complete   = "<img src='web/activity/shengdan/image/complete.png'>";
    $("#hat").html(x+hat);
    $("#clothes").html(x+clothes);
    $("#boots").html(x+boots);

    //剩余场数
    if (to_hat>='5'){
        $("#toHat").html(complete);
    }else {
    	$("#toHat").html(to_hat+'/5');
    }
    if (to_clo>='10'){
        $("#toClothes").html(complete);
    }else {
    	$("#toClothes").html(to_clo+'/10');
    }
    if (to_boots>='20'){
        $("#toBoots").html(complete);
    }else {
    	$("#toBoots").html(to_boots+'/20');
    }
    
    //颜色变化
    switch (<?php echo $flag?>){
    case 1:
    	$("#toHat").css("color","#40f740");
    	break;
    case 2:
    	$("#toClothes").css("color","#40f740");
    	break;
    case 3:
    	$("#toBoots").css("color","#40f740");
    	break;
    }
	
    //按键效果
    $(".butt").mousedown(function(){
    	var id = $(this).attr("id");
		$(this).css("background-image","url(web/activity/shengdan/image/"+id+"-on.png)");
        });
    $(".butt").mouseup(function(){
    	var id = $(this).attr("id");
		$(this).css("background-image","url(web/activity/shengdan/image/"+id+"-leave.png)");
        });
	$("#close").mouseup(function(){
	    $("#warning").hide();
		});

    //cover
    function cover (have_hat, have_clothes, have_boots, have_socks){
        if (have_hat<=0){
            $("#hatCover").show();
            $("#maozi").show();
        }
        if (have_clothes<=0){
            $("#clothesCover").show();
            $("#yifu").show();
        }
        if (have_boots<=0){
            $("#bootsCover").show();
            $("#xiezi").show();
        }
        if (have_hat<=0 || have_clothes<=0 || have_boots<=0){
        	$("#getCover").show();
        }else {
            if (have_socks<=0){
            	$("#getCover").show();
            }
        }
    }

    cover(have_hat, have_clothes, have_boots, have_socks);
    
    //兑换袜子
    $("#get").mouseup(function(){
        if (have_hat>0 && have_clothes>0 && have_boots>0){
            $.ajax({
                type:"POST",
                url:"/ucenter/index.php?m=activity&p=dispath&fpath=shengdan&act=exchange",
                data:"gameid=<?php echo $gameid?>"
    				+"&mid=<?php echo $mid?>"
    				+"&sid=<?php echo $sid?>"
    				+"&cid=<?php echo $cid?>"
    				+"&pid=<?php echo $pid?>"
    				+"&ctype=<?php echo $ctype?>",
  				dataType: "json",
  				success:function(a){
        			var status       = a.status;
    				var money        = a.money;
        			var have_hat     = a.have_hat;
        			var have_clothes = a.have_clothes;
        			var have_boots   = a.have_boots;
        			var have_socks   = a.have_socks;
    			    if (status=='1'){
    			        cover(have_hat,have_clothes,have_boots,have_socks);
    			        $("#money").html(money);
        			    $("#ff").show();
    			        $("#warning").show();
    			        hat      = to_img(have_hat);
        			    clothes  = to_img(have_clothes);
        			    boots    = to_img(have_boots);
				        $("#hat").html(x+hat);
				        $("#clothes").html(x+clothes);
				        $("#boots").html(x+boots);
        			    $("#toSocks").html('('+have_socks+')');
    				}else {
    					$("#message").text("兑换失败("+status+")");
    					$("#mess").stop(true,true).fadeIn(100);
    					$("#mess").stop(true,true).fadeOut(2000);
    				}
    			}
            });
        }else {
        	$("#message").text("未达到兑换条件");
			$("#mess").stop(true,true).fadeIn(100);
			$("#mess").stop(true,true).fadeOut(2000);
        }
    });

    //兑换袜子成功提示
    $(".warning").mouseup(function(){
        $("#ff").hide();
    	$("#warning").hide();
    });
    
    //兑换金币
    var type;
    $(".getM").mouseup(function(){
        type = $(this).attr("id");
        switch (type){
        case 'getHat':
        	if (have_hat>0){
                $("#number").html("<img src='web/activity/shengdan/image/688.png'>");
                $("#con").show();
            }
            break;
        case 'getClothes':
        	if (have_clothes>0){
                $("#number").html("<img src='web/activity/shengdan/image/1288.png'>");
                $("#con").show();
            }
        	break;
        case 'getBoots':
        	if (have_boots>0){
                $("#number").html("<img src='web/activity/shengdan/image/3888.png'>");
                $("#con").show();
            }
        	break;
        } 
    });

    //确定按钮
    $("#ensure").mouseup(function(){
        $.ajax({
            type:"POST",
            url:"/ucenter/index.php?m=activity&p=dispath&fpath=shengdan&act=getMoney&type="+type,
            data:"gameid=<?php echo $gameid?>"
				+"&mid=<?php echo $mid?>"
				+"&sid=<?php echo $sid?>"
				+"&cid=<?php echo $cid?>"
				+"&pid=<?php echo $pid?>"
				+"&ctype=<?php echo $ctype?>",
			dataType: "json",
			success:function(e){
				var status       = e.status;
				var money        = e.money;
    			var have_hat     = e.have_hat;
    			var have_clothes = e.have_clothes;
    			var have_boots   = e.have_boots;
    			var have_socks   = e.have_socks;
			    if (status=='1'){
			    	cover(have_hat,have_clothes,have_boots,have_socks);
				    $("#money").html(money);
				    $("#con").hide();
			    	$("#message").text("兑换成功");
					$("#mess").stop(true,true).fadeIn(100);
					$("#mess").stop(true,true).fadeOut(2000);
				    switch (type){
				    case 'getHat':
				    	hat    = to_img(have_hat);
				        $("#hat").html(x+hat);
				        break;
				    case 'getClothes':
				    	clothes    = to_img(have_clothes);
				        $("#clothes").html(x+clothes);
				        break;
				    case 'getBoots':
				    	boots  = to_img(have_boots);
				        $("#boots").html(x+boots);
				        break;
				    }
				}else {
					$("#message").text("兑换失败("+status+")");
					$("#mess").stop(true,true).fadeIn(100);
					$("#mess").stop(true,true).fadeOut(2000);
				}
			}
        });
    });

    //取消按钮
    $("#cancle").mouseup(function(){
        $("#con").hide();
    });
});

</script>
</html>
