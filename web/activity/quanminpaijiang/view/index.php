<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>愚你同乐，礼惠全服</title>
<script type="text/javascript" src="web/activity/quanminpaijiang/js/jquery-2.1.1.min.js"></script>
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

</style>
</head>
<body>
    <div id="main" class="relative" style="width: 1280px;height: 800px; margin: 0 auto; background: url(web/activity/quanminpaijiang/image/bg.jpg);">
        <div id="header" class="relative" style="height: 74px;background: url(web/activity/image/head_ddz.png);  z-index: 1;">
            <a id="tab_2" class="tab float_right" href="javaScript:;" style="background: url(web/activity/image/caidan_leave.png);margin-right: 190px;">活动介绍</a>
            <a id="tab_1" class="tab float_right" href="javaScript:;" style="background: url(web/activity/image/caidan_on.png);margin-right: 15px;">活动</a>
        </div>
        <div id="content" class="relative" style=" width: 1280px; height: 726px;">
            <div id="table_1" >
                <div id="score" class="absolute" style="background: url(web/activity/quanminpaijiang/image/qmp11_n.png); width: 314px; height: 67px; left: 75px; top: 35px;">
                    <div style="color: #fff; line-height: 67px; font-size: 28px;">当前积分:<span id="sheng"><?php echo $sheng?></span></div>
                </div>
                <div class="absolute" style="left: 150px; top: 110px;">
                    <div id="lump2" class="lump">
                        <div class="relative" style="width: 248px; height: 249px; margin: auto;">
                            <img id="box2" class="absolute" style="left: 0; bottom: 0; z-index: 1;" src="web/activity/quanminpaijiang/image/silver.png">
                            <img id="box2_n" class="absolute" style="left: 10px; bottom: 0; z-index: 1; display: none;" src="web/activity/quanminpaijiang/image/silver_n.png">
                        </div>
                        <div class="relative" style="left: 0; top: -40px;">
                            <img src="web/activity/quanminpaijiang/image/qmp2_n.png">
                            <div id="need2" class="absolute" style="background: url(web/activity/quanminpaijiang/image/qmp3_n.png); width: 202px; height: 40px; z-index: 1; left: 40px; top: 80px;">
                                <div id="silver_need" style=" width: 80px; height: 40px; padding-left: 41px;"></div>
                            </div>
                        </div>
                        <div id="btn2" class="relative btn" style="left: 0; top: -60px;">
                            <img src="web/activity/quanminpaijiang/image/qmp4_n.png">
                        </div>
                    </div>
                    <div id="cover2" class="absolute" style="left: 0; top: 0; width: 280px; height: 518px; z-index: 2; display: none;"></div>
                </div>
                <div class="absolute" style="left: 500px; top: 75px;">
                    <div id="lump3" class="lump">
                        <div class="relative" style="width: 276px; height: 278px; margin: auto;">
                            <img id="box3" class="absolute" style="left: 0; bottom: 0; z-index: 1;" src="web/activity/quanminpaijiang/image/gold.png">
                            <img id="box3_n" class="absolute" style="left: 12px; bottom: 0; z-index: 1; display: none;" src="web/activity/quanminpaijiang/image/gold_n.png">
                        </div>
                        <div class="relative" style="left: 0; top: -40px;">
                            <img src="web/activity/quanminpaijiang/image/qmp2_n.png">
                            <div id="need3" class="absolute" style="background: url(web/activity/quanminpaijiang/image/qmp3_n.png); width: 202px; height: 40px; z-index: 1; left: 40px; top: 80px;">
                                <div id="gold_need" style=" width: 80px; height: 40px; padding-left: 41px;"></div>
                            </div>
                        </div>
                        <div id="btn3" class="relative btn" style="left: 0; top: -55px;">
                            <img src="web/activity/quanminpaijiang/image/qmp4_n.png">
                        </div>
                    </div>
                    <div id="cover3" class="absolute" style="left: 0; top: 0; width: 280px; height: 547px; z-index: 2; display: none;"></div>
                </div>
                <div class="absolute" style="left: 850px; top: 110px;">
                    <div id="lump1" class="lump">
                        <div class="relative" style="width: 248px; height: 249px; margin: auto;">
                            <img id="box1" class="absolute" style="left: 0; bottom: 0; z-index: 1;" src="web/activity/quanminpaijiang/image/bronze.png">
                            <img id="box1_n" class="absolute" style="left: 10px; bottom: 0; z-index: 1; display: none;" src="web/activity/quanminpaijiang/image/bronze_n.png">
                        </div>
                        <div class="relative" style="left: 0; top: -40px;">
                            <img src="web/activity/quanminpaijiang/image/qmp2_n.png">
                            <div id="need1" class="absolute" style="background: url(web/activity/quanminpaijiang/image/qmp3_n.png); width: 202px; height: 40px; z-index: 1; left: 40px; top: 80px;">
                                <div id="bronze_need" style=" width: 80px; height: 40px; padding-left: 41px;"></div>
                            </div>
                        </div>
                        <div id="btn1" class="relative btn" style="left: 0; top: -60px;">
                            <img src="web/activity/quanminpaijiang/image/qmp4_n.png">
                        </div>
                    </div>
                    <div id="cover1" class="absolute" style="left: 0; top: 0; width: 280px; height: 518px; z-index: 2; display: none;"></div>
                </div>
            </div>
            
            <!-- 活动说明 -->
            <div id="table_2" style="display: none;">
                <div style="color: #fff; width: 1000px; text-align: left; margin: 100px auto; font-size: 36px;">
                    <div style="padding: 10px;">活动内容:</div>
                    <div style="padding: 10px;">1.充值一元可获得一体力，消耗体力砸彩蛋</div>
                    <div style="padding: 10px;">2.消耗体力越高砸蛋，可获得价值越高的奖品</div>
                    <div style="padding: 10px;">3.每日零点体力清零，每天仅可砸两次</div>
                </div>
            </div>
        </div>
        <div id="caution" class="absolute" style="left:50%;top:45%; z-index: 10; margin: -105px 0 0 -275px; display: none;">
        	<div class="relative">
		        <div style="background-color:#000; border: solid 4px #FFD700; border-radius:30px; width:550px; height:200px; opacity:0.8;"></div>
		        <span id="cmessage" class="absolute" style="color:#FFF; line-height:105px; font-size: 40px; z-index:100; left:0; top:0; width:550px; height:200px; line-height:200px; margin: 4px;"></span>
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
				output+="<img src=web/activity/quanminpaijiang/image/"+imgs[j]+" style='margin: 3px -4px 0 -4px;'/>";
				break;
		   		}
		  	}
		}
		return output;
	}

	//需要积分显示
    $("#bronze_need").html(to_img(<?php echo $need[$gameid][1]?>));
	$("#silver_need").html(to_img(<?php echo $need[$gameid][2]?>));
	$("#gold_need").html(to_img(<?php echo $need[$gameid][3]?>));

	var score = <?php echo $sheng ?>;
    var bronze_need = <?php echo $need[$gameid][1] ?>;
    var silver_need = <?php echo $need[$gameid][2] ?>;
    var gold_need = <?php echo $need[$gameid][3] ?>;
    var bronze = <?php echo $bronze ?>;
    var silver = <?php echo $silver ?>;
    var gold = <?php echo $gold ?>;

    //领取宝箱
    function tapBox (){
        $(".lump").mouseup(function (){
            var taptype = $(this).attr('id').replace('lump','');
            switch (taptype){
                case '1':
                    var str = 'bronze';
                    var need = bronze_need;
                    break;
                case '2':
                    var str = 'silver';
                    var need = silver_need;
                    break;
                case '3':
                    var str = 'gold';
                    var need = gold_need;
                    break;
            }
            
            if (score>=need){
                $.ajax({
                    type: "POST",
                    url: "<?php echo $url ?>?m=activity&p=dispath&fpath=quanminpaijiang&mtkey=<?php echo $mtkey?>&act=set",
                    dataType: "json",
                    data: "gameid=<?php echo $gameid ?>"
                        +"&mid=<?php echo $mid ?>"
                        +"&sid=<?php echo $sid?>"
        				+"&cid=<?php echo $cid?>"
        				+"&pid=<?php echo $pid?>"
        				+"&ctype=<?php echo $ctype?>"
        				+"&taptype="+taptype,
        			success: function(json){
        			    var status = json.status;
        			    score      = json.sheng;
        			    money      = json.money;

        			    if (status == 1){
            			    $("#sheng").text(score);
            	            $("#btn"+taptype).hide();
            	            $("#box"+taptype).hide();
            	            $("#need"+taptype).hide();
            	            $("#cover"+taptype).show();
            	            $("#box"+taptype+"_n").show();

            	            $("#cmessage").text("恭喜您获得"+money+"金币！");
        	            	$("#caution").stop(true,true).fadeIn(1000);
        	            	
        	            	setTimeout(function(){
        						$("#caution").stop(true,true).fadeOut(1000);
        			        },3000);
        			    }else {
        			    	$("#cmessage").text("出错了！("+status+")");
        	            	$("#caution").stop(true,true).fadeIn(1000);
        	            	
        	            	setTimeout(function(){
        						$("#caution").stop(true,true).fadeOut(1000);
        			        },3000);
            			}
        			}
                });
            }else {
            	$("#cmessage").text("积分不足，赶紧去玩牌吧！");
            	$("#caution").stop(true,true).fadeIn(1000);
            	
            	setTimeout(function(){
					$("#caution").stop(true,true).fadeOut(1000);
		        },3000);
            }
        });
    }

    function btnHide (bronze,silver,gold){
        if(bronze>=1){
            $("#btn1").hide();
            $("#box1").hide();
            $("#need1").hide();
            $("#cover1").show();
            $("#box1_n").show();
        }
        if(silver>=1){
            $("#btn2").hide();
            $("#box2").hide();
            $("#need2").hide();
            $("#cover2").show();
            $("#box2_n").show();
        }
        if(gold>=1){
            $("#btn3").hide();
            $("#box3").hide();
            $("#need3").hide();
            $("#cover3").show();
            $("#box3_n").show();
        }
    }

    tapBox();
    btnHide (bronze,silver,gold)
	
});
</script>
</html>