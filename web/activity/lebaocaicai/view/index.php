<!doctype html>
<html>
<head>
<meta charset="utf-8" >
<title>乐宝猜猜猜</title>
<script type="text/javascript" src="web/activity/lebaocaicai/js/jquery-2.1.1.js"></script>
<style type="text/css">
body {
					margin:0 auto;
					text-align:center;
					}
a {
					text-decoration:none;
					}
ul {
					margin:0;
					padding:0;
					}
li {
					list-style:none;
					}




.float_left {
					float:left;
					}
.float_right {
					float:right;
					}
.clear {
					clear:both;
					}
.relative {
					position:relative;
					}
.absolute {
					position:absolute;
					}
.anniu {
					}
.tab {
					margin-top:8px;
					width:189px;
					height:66px;
					line-height:66px;
					color:#ecac64;
					font-size:32px;
					display:block;
					}
.animal_num {
					width:34px;
					left:45px;
					top:60px;
					}
.bought_num {
					width:120px;
					left:30px;
					top:123px;
					color:#76410C;
					font-size:25px;
					font-weight:bold;
					}
.bought_icon {
					display:inline-block;
					}
.select_box {
					width:165px;
					height:165px;
					display:block;
					margin:5px;
					}
.cover {
					width:165px;
					height:165px;
					display:block;
					margin:5px;
					background:url(web/activity/lebaocaicai/image/cover.png); 
					background-size: 165px 165px; 
					background-repeat: no-repeat;
					}
.change_number {
					width:74px;
					height:74px;
					display:inline-block;
					}
.select_button {
					width:236px;
					height:101px;
					display:inline-block;
					}
.all {
					width:149px; 
					height:46px; 
					background:url(web/activity/lebaocaicai/image/all.png);
					font-size:0;
					left:0;
					top:3px;
}
.rule {
					width:70%;
					padding: 10px;
					color: #b8ff9c;
					font-size: 32px;
					margin: 0 auto;
					margin-top: 10px;
					text-align: left;
					}
					



#main {
					margin:0 auto;
					width:1280px;
					height:800px;
					background:url(web/activity/lebaocaicai/image/di.png);
					}
#title_head {
					margin:0 auto;
					height:74px;
					background:url(web/activity/lebaocaicai/image/head_ddz.png) repeat-x;
					}
#tab_1 {
					background:url(web/activity/lebaocaicai/image/caidan_on.png);
					margin-right:15px;
					}
#tab_2 {
					background:url(web/activity/lebaocaicai/image/caidan_leave.png);
					margin-right:15px;
					}
#tab_3 {
					background:url(web/activity/lebaocaicai/image/caidan_leave.png);
					margin-right:190px;
					}
#table_1 {
					
					}
#top_content {
	
					}
#warning {
					width:1280px;
					height:45px;
					background:url(web/activity/lebaocaicai/image/zitikuang.png);
					}
#left_content {	
					width:534px;
					height:623px;
					background:url(web/activity/lebaocaicai/image/lottery.png);
					background-size:auto 623px;
					background-repeat:no-repeat;
					left:155px;
					top:83px;
					}
#winning_frame {
					width:535px;
					height:319px;
					background:url(web/activity/lebaocaicai/image/flashlight1.png);
					top:36px;
					}
#winning_icon {
					left:150px;
					top:50px;
					}
#winning_info {
					left: 25px;
					top: 391px;
					}
#bought_frame {
					width:486px;
					}
#bonus {
					text-align:left;
			}
#bonus_money {
					margin:7px 0 0 240px;
					}
#get_bonus_frame {
					left:157px;
					top:520px;
					}
#get {
					background:url(web/activity/lebaocaicai/image/get-leave.png);
					width:220px;
					height:94px;
					display:block;
					background-size: 220px 94px;
					background-repeat: no-repeat;
					}
#get_cover {
					background:url(web/activity/lebaocaicai/image/1-.png);
					width:220px;
					height:94px;
					display:block;
					background-size: 220px 94px;
					background-repeat: no-repeat;
					left:0;
					top:0;
					display:none;
					}
#right_content {
					left:705px;
					top:117px;
					
					}
#select_box {	
					width:350px;
				}
#totalmoney {	
					width:340px;
					height:62px;
					background:url(web/activity/lebaocaicai/image/totalmoney.png);
					margin:5px 0 0 5px;
					background-size: 340px;
					background-repeat: no-repeat;
					}
#mymoney {			
					text-align:left;
					width:210px;
					line-height:56px;
					font-size:36px;
					margin-left:142px;
					color:#76410C;
					}
#table_2 {			
					color:#b8ff9c;
					display:none;
					font-size:52px;
					}
#table_3 {
					display:none;
					}
#table_4 {
					background-color:black;
					left:0;
					top:0;
					opacity:0.5;
					z-index:5;
					width:inherit;
					height:inherit;
					display:none;
					}
#table_5 {
					background:url(web/activity/lebaocaicai/image/pop-up.png?v=1);
					width:579px;
					height:391px;
					left:50%;
					top:50%;
					margin:-195.5px 0 0 -289.5px;
					z-index:10;
					display:none;
					}

#clicked_icon {
					left:30px;
					top:65px;
					}
#change_number {
					left:228px;
					top:80px;
					}
#number {
					left:320px;
					top:100px;
					width:130px;
					height:74px;
					}
#max {
					width:134px;
					height:62px;
					display:block;
					}
#select_button {
					top:270px;
					left:35px;
					}
#recharge {
					background:url(web/activity/lebaocaicai/image/recharge.png);
					width:579px;
					height:326px;
					background-size:579px 326px;
					left:50%;
					top:50%;
					margin:-195.5px 0 0 -289.5px;
					z-index:11;
					display: none;
					}
#recharge_button {
					top:205px;
					left:35px;
					}
#mess {
					left:50%;
					top:50%;
					z-index:99;
					margin:-50px 0 0 -130px;
					display:none;
					}
#congra {
					width:1280px;
					height:720px;
					position: absolute;
					top: 0;
					display: none;
					}
</style>

</head>
<body>
    <div id="main" class="relative">
    	<div id="title_head">        
			<a href="javaScript:;" id="tab_3" class="tab float_right">活动规则</a>
			<a href="javaScript:;" id="tab_2" class="tab float_right">历史开奖</a>
            <a href="javaScript:;" id="tab_1" class="tab float_right">活动</a>
        </div>
        <div id="table_1">
        	<div id="top_content">
        		<div id="warning">
        			<img src="web/activity/lebaocaicai/image/warning.png" style="margin-top:7px;">
        		</div>
        	</div>
        	<div id="left_content" class="absolute">
            	<div id="winning_frame" class="absolute">
                	<div id="winning_icon" class="absolute">
                	</div>
                </div>
                <div id="winning_info" class="absolute">
                    <ul id="bought_frame">
                        <li class="float_left relative">
                            <img src="web/activity/lebaocaicai/image/dog-2.png" style="width:81px;">
                            <div id="dog_yesnum" class="animal_num absolute"></div>
                        </li>
                        <li class="float_left relative">
                            <img src="web/activity/lebaocaicai/image/bird-2.png" style="width:81px;">
                            <div id="bird_yesnum" class="animal_num absolute"></div>
                        </li>
                        <li	class="float_left relative">
                            <img src="web/activity/lebaocaicai/image/fox-2.png" style="width:81px;">
                            <div id="fox_yesnum" class="animal_num absolute"></div>
                        </li>
                        <li	class="float_left relative">
                            <img src="web/activity/lebaocaicai/image/panda-2.png" style="width:81px;">
                            <div id="panda_yesnum" class="animal_num absolute"></div>
                        </li>
                        <li	class="float_left relative">
                            <img src="web/activity/lebaocaicai/image/giraffe-2.png" style="width:81px;">
                            <div id="giraffe_yesnum" class="animal_num absolute"></div>
                        </li>
                        <li	class="float_left relative">
                            <img src="web/activity/lebaocaicai/image/sheep-2.png" style="width:81px;">
                            <div id="sheep_yesnum" class="animal_num absolute"></div>
                        </li>
                        <li class=" clear"></li>
                    </ul>
                    <div id="bonus">
                    	<div id="bonus_money">
                        </div>
                    </div>
                </div>
                <div id="get_bonus_frame" class="absolute">
                	<a href="javaScript:;" id="get" class="anniu"></a>
                	<a href="javaScript:;" id="get_cover" class="absolute" ></a>
                </div>
            </div>
            <div id="right_content" class="absolute">
            	<div id="select_box">
                	<ul>
                    	<li class="float_left relative">
                        	<a href="javaScript:;" id="dog" class="select_box anniu" style="background:url(web/activity/lebaocaicai/image/dog-leave.png); background-size: 165px 165px; background-repeat: no-repeat;"></a>
                            <div id="dog_tonum" class="bought_num absolute"></div>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" id="panda" class="select_box anniu" style="background:url(web/activity/lebaocaicai/image/panda-leave.png); background-size: 165px 165px; background-repeat: no-repeat;"></a>
                            <!--<div id="panda_all" class="all absolute">
                            	<div style="height:27px;margin-top: 9px;padding-left: 40px;">
	                            	<img src="web/activity/lebaocaicai/image/all-1.png" width="undefined" height="undefined/">
	                            	<img src="web/activity/lebaocaicai/image/all-2.png" width="undefined" height="undefined/">
	                            	<img src="web/activity/lebaocaicai/image/all-3.png" width="undefined" height="undefined/">
	                            	<img src="web/activity/lebaocaicai/image/all-4.png" width="undefined" height="undefined/">
	                            	<img src="web/activity/lebaocaicai/image/all-5.png" width="undefined" height="undefined/">
                            	</div>
                            	
                            </div>-->
                            <div id="panda_tonum" class="bought_num absolute"></div>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" id="bird" class="select_box anniu" style="background:url(web/activity/lebaocaicai/image/bird-leave.png); background-size: 165px 165px; background-repeat: no-repeat;"></a>
                            <div id="bird_tonum" class="bought_num absolute"></div>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" id="giraffe" class="select_box anniu" style="background:url(web/activity/lebaocaicai/image/giraffe-leave.png); background-size: 165px 165px; background-repeat: no-repeat;"></a>
                            <div id="giraffe_tonum" class="bought_num absolute"></div>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" id="fox" class="select_box anniu" style="background:url(web/activity/lebaocaicai/image/fox-leave.png); background-size: 165px 165px; background-repeat: no-repeat;"></a>
                            <div id="fox_tonum" class="bought_num absolute"></div>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" id="sheep" class="select_box anniu" style="background:url(web/activity/lebaocaicai/image/sheep-leave.png); background-size: 165px 165px; background-repeat: no-repeat;"></a>
                            <div id="sheep_tonum" class="bought_num absolute"></div>
                        </li>
                        <li class="clear"></li>
                    </ul>
                </div>
                <div id="cover" class="absolute" style='width:350px; left:0px; top:0px; display:none;'>
                	<ul>
                    	<li class="float_left relative">
                    		<a href="javaScript:;" class="cover"></a>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" class="cover"></a>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" class="cover"></a>
                        </li>
                       	<li class="float_left relative">
                        	<a href="javaScript:;" class="cover"></a>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" class="cover"></a>
                        </li>
                        <li class="float_left relative">
                        	<a href="javaScript:;" class="cover"></a>
                        </li>
                        <li class="clear"></li>
                    </ul>
                </div>
                <div id="totalmoney">
                	<div class="relative">
	                	<div id="mymoney">
	                    </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="table_2">
        	<div style="background:url(web/activity/lebaocaicai/image/kaijiangdi.png);background-repeat:no-repeat;background-size:auto 641px;width:822px;height:641px;margin:5px auto 0 auto;"s>
        		<div style="width:411px;margin-top:75px;" class="float_left">
	        		<?php foreach($day as $l=>$n):?>
		        		<div style="height:113px; line-height:113px;">
		        			<?php echo $n ?>
		        		</div>
	        		<?php endforeach;?>
        		</div>
        		<div style="width:411px;margin-top:75px;" class="float_left">
        			<?php foreach($his as $t=>$ty):?>
        					<?php if($ty):?>
        						<img src="web/activity/lebaocaicai/image/<?php echo $ty ?>-4.png" style="height:113px; display:block; margin:0 auto">
        					<?php endif ?>
        		<?php endforeach;?>
        		</div>
        	</div>
        </div>
        <div id="table_3">
        	<div class="rule" style="margin-top:50px">活动规则：</div>
			<div class="rule">1、每份乐宝售价1万金币，中奖可获5万金币，中奖乐宝由系统随机产生。</div>
			<div class="rule">2、每天20:00点开奖，开奖后请在下次开奖前领取奖励，过时无法领取不予补偿。</div>
			<div class="rule">3、为了所有玩家数据同步，开奖前5分钟为锁盘时段，不能做任何操作哦。</div>
			<div class="rule">4、每个乐宝只能购买99个。</div>
        </div>
        
        <div id="table_4" class="absolute"></div>
		<!-- 数量选择 -->
        <div id="table_5" class="absolute">
        	<div>
            	<img id="clicked_icon" class="absolute" src="">
            </div>
            <div id="change_number" class="absolute">
            	<a href="javaScript:;" id="minus" class="change_number anniu" style="background:url(web/activity/lebaocaicai/image/minus-leave.png);"></a>
                <a href="javaScript:;" id="plus" class="change_number anniu" style="background:url(web/activity/lebaocaicai/image/plus-leave.png); margin-left:160px;"></a>
            </div>
            	<div id="number" class="absolute">
            </div>
            <div style="width:134px;height:62px;top:167px;left:318px;" class="absolute">
           		<a href="javaScript:;" id="max" class="anniu" style="background:url(web/activity/lebaocaicai/image/max-leave.png);"></a>
           	</div>
            <div id="select_button" class="absolute">
            	<a href="javaScript:;" id="cancel" class="select_button anniu" style="background:url(web/activity/lebaocaicai/image/cancel-leave.png)"></a>
                <a href="javaScript:;" id="ensure" class="select_button anniu" style="background:url(web/activity/lebaocaicai/image/ensure-leave.png); margin-left:30px;"></a>
            </div>
        </div>
        <!-- 充值提示 -->
       	<div id="recharge" class="absolute">
            <div id="recharge_button" class="absolute">
            	<a href="javaScript:;" id="close" class="select_button anniu" style="background:url(web/activity/lebaocaicai/image/close-leave.png)"></a>
                <a href="javaScript:;" id="go" class="select_button anniu" style="background:url(web/activity/lebaocaicai/image/go-leave.png); margin-left:30px;"></a>
            </div>
        </div>
		<!-- 中奖通知 -->
       	<div id="congra">
        	<div class="relative">
		        <img src="web/activity/lebaocaicai/image/congrats.png">
	       	</div>
       	</div>
        <!-- 提示信息 -->
        <div id="mess" class="absolute">
        	<div class="relative">
		        <div style="background-color:#666; border-radius:30px; width:300px; height:100px; opacity:0.7;"></div>
		        <span id="message" class="absolute" style="color:#FFF; line-height:100px; font-size: 30px; z-index:100; left:0; top:0; width:300px; height:100px; line-height:100px;">
		       	</span>
	       	</div>
       	</div>
       	
    </div>
</body>
<script type="text/javascript">
	var now = 0;
	$(this).ready(function() {
		
		var j=0;
		//开奖框背景切换
		function funa(){
			if(j%2==0){
				$("#winning_frame").css("background-image","url(web/activity/lebaocaicai/image/flashlight2.png)");
			}else{
			  	$("#winning_frame").css("background-image","url(web/activity/lebaocaicai/image/flashlight1.png)");
			}
			j++;
		}
		//循环执行，每隔0.4秒钟执行一次
		window.setInterval(funa, 400);
		
		//头部卡片切换
		$(".tab").click(function(){
			var now_tab = $(this).attr("id").replace("tab_","");
			$("#table_1").hide();
			$("#table_2").hide();
			$("#table_3").hide();
			$("#tab_1").css("background-image","url(web/activity/lebaocaicai/image/caidan_leave.png)");
			$("#tab_2").css("background-image","url(web/activity/lebaocaicai/image/caidan_leave.png)");
			$("#tab_3").css("background-image","url(web/activity/lebaocaicai/image/caidan_leave.png)");
			if(now_tab==1){
				$("#tab_1").css("background-image","url(web/activity/lebaocaicai/image/caidan_on.png)");
				$("#table_1").show();
			}else if(now_tab==2){
				$("#tab_2").css("background-image","url(web/activity/lebaocaicai/image/caidan_on.png)");
				$("#table_2").show();	
			}else {
				$("#tab_3").css("background-image","url(web/activity/lebaocaicai/image/caidan_on.png)");
				$("#table_3").show();
			}
		});
		
		//锁盘
		var time = <?php echo $now?>;
		if(time>=195500 && time<200000){
			$("#cover").show();
		}
		
		var ts = <?php echo NOW?>*1000;
		function getTime(){
			var t = new Date(ts);
		    var now = (t.getHours()<10 ? "0" :"") + t.getHours() + (t.getMinutes()<10 ? "0" :"") + t.getMinutes() + (t.getSeconds()<10 ? "0" :"") + t.getSeconds();
		    ts+=1000;
		    if(now==195500){
		    	window.location.reload();
		    }else if(now==200001) {
		    	window.location.reload();
		    }
		}
		window.setInterval(getTime,1000);
		
		
		
		//用图片替代数字
		function to_img (number,str,width,height){
			var number=number;
			var imgs=['0.png','1.png','2.png','3.png','4.png','5.png','6.png','7.png','8.png','9.png'];
			var ns='0123456789'.split('');
			var output='';
			var number=number+'';
			for(var i=0;i<number.length;i++){
				for(var j=0;j<ns.length;j++){
					if(number[i]==ns[j]){
					output+="<img src=web/activity/lebaocaicai/image/"+str+imgs[j]+" width="+width+" height="+height+"/>";
					break;
			   		}
			  	}
			}
			return output;
		}
		
		//用户总金币数量
		var totol_money = to_img(<?php echo $money ? $money : 0 ?>,'money-');
		$("#mymoney").html(totol_money);
		
	
		//今日购买数量
		var dog_today		= to_img(<?php echo $dog_today ?>,'num-');
		var bird_today		= to_img(<?php echo $bird_today ?>,'num-');
		var fox_today		= to_img(<?php echo $fox_today ?>,'num-');
		var giraffe_today	= to_img(<?php echo $giraffe_today ?>,'num-');
		var panda_today		= to_img(<?php echo $panda_today ?>,'num-');
		var sheep_today		= to_img(<?php echo $sheep_today ?>,'num-');
		var x				= "<img src='web/activity/lebaocaicai/image/x.png'>";
		$("#dog_tonum").html(x+dog_today);
		$("#bird_tonum").html(x+bird_today);
		$("#fox_tonum").html(x+fox_today);
		$("#giraffe_tonum").html(x+giraffe_today);
		$("#panda_tonum").html(x+panda_today);
		$("#sheep_tonum").html(x+sheep_today);
			
		//昨日购买数量
		var dog_yestoday		= to_img(<?php echo $dog_yesterday ?>,'num-',17,19);
		var bird_yestoday		= to_img(<?php echo $bird_yesterday ?>,'num-',17,19);
		var fox_yestoday		= to_img(<?php echo $fox_yesterday ?>,'num-',17,19);
		var giraffe_yestoday	= to_img(<?php echo $giraffe_yesterday ?>,'num-',17,19);
		var panda_yestoday		= to_img(<?php echo $panda_yesterday ?>,'num-',17,19);
		var sheep_yestoday		= to_img(<?php echo $sheep_yesterday ?>,'num-',17,19);
		$("#dog_yesnum").html(dog_yestoday);
		$("#bird_yesnum").html(bird_yestoday);
		$("#fox_yesnum").html(fox_yestoday);
		$("#giraffe_yesnum").html(giraffe_yestoday);
		$("#panda_yesnum").html(panda_yestoday);
		$("#sheep_yesnum").html(sheep_yestoday);
		
		//中奖金额
		var jiangjin  = <?php echo $bonus_money ?>;
		var	jine	  = <?php echo $bonus_money ?>;
		var zhuangtai = <?php echo $reward ?>;
		if(zhuangtai == -1 && jiangjin != 0){
			var bonus_money	= to_img(jine,'bonus-');
			$("#bonus_money").html(bonus_money);
			$("#get_cover").css("background-image","url(web/activity/lebaocaicai/image/2-.png)");
			$("#get_cover").show();
			jiangjin = 0;
		}else if(jiangjin == 0){
			var bonus_money	= to_img(jine,'bonus-');
			$("#bonus_money").html(bonus_money);
			$("#get_cover").show();
		}else{
			var bonus_money	= to_img(jine,'bonus-');
			$("#bonus_money").html(bonus_money);
		}
		
		
		//中奖提示
		if(zhuangtai == 0 && jiangjin != 0 ){
			setTimeout('$("#congra").show("fast")',1);
			setTimeout('$("#congra").hide("fast")',2000);
		}
		$("#congra").click(function(){
			$(this).hide();
		});
		
		
		//领取按钮
		var nowmoney = <?php echo $money ? $money : 0 ?>;
		
		$("#get").mouseup(function(){
			$.ajax({
				type:"POST",
				url:"http://user.dianler.com/index.php?m=activity&p=dispath&fpath=lebaocaicai&act=get_bonus",
				data:"gameid=<?php echo $gameid?>"
					+"&mid=<?php echo $mid?>"
					+"&sid=<?php echo $sid?>"
					+"&cid=<?php echo $cid?>"
					+"&pid=<?php echo $pid?>"
					+"&ctype=<?php echo $ctype?>",
				success:function(a){
					if(a=='1'){
						$("#get_cover").css("background-image","url(web/activity/lebaocaicai/image/2-.png)");
						$("#get_cover").show();
						var bonus_money = to_img(jine,'bonus-');
						$("#bonus_money").html(bonus_money);
						var bonus_plus = <?php echo $bonus_money ?>+nowmoney;
						nowmoney  = <?php echo $bonus_money ?>+nowmoney;
						var totol_money = to_img(bonus_plus,'money-');
						$("#mymoney").html(totol_money);
						$("#message").text("领取成功");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
					else{
						$("#message").text("领取失败("+a+")");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
				}
			});
		});
		//按钮按下效果
		$(".anniu").mousedown(function(){
			var id = $(this).attr("id");
			$(this).css("background-image","url(web/activity/lebaocaicai/image/"+id+"-on.png)");
		});
		
		$(".anniu").mouseup(function(){
			var id = $(this).attr("id");
			$(this).css("background-image","url(web/activity/lebaocaicai/image/"+id+"-leave.png)");
		});
		
		var number = 1;
		var totol_dog = <?php echo $dog_today?>;
		var totol_bird = <?php echo $bird_today?>;
		var totol_fox = <?php echo $fox_today?>;
		var totol_giraffe = <?php echo $giraffe_today?>;
		var totol_panda = <?php echo $panda_today?>;
		var totol_sheep = <?php echo $sheep_today?>;
		
		$(".select_box").mouseup(function(){
			$type = $(this).attr("id");
			$("#clicked_icon").attr("src","web/activity/lebaocaicai/image/"+$type+"-3.png");
			switch($type){
			case 'dog':
				if(totol_dog<99){
					number = 1;
					$("#table_4").show();
					$("#table_5").show();
				}else{
					$("#message").text("数量达到上限");
					$("#mess").stop(true,true).fadeIn(100);
					$("#mess").stop(true,true).fadeOut(2000);
				}
				break;
			case 'bird':
				if(totol_bird<99){
					number = 1;
					$("#table_4").show();
					$("#table_5").show();
				}else{
					$("#message").text("数量达到上限");
					$("#mess").stop(true,true).fadeIn(100);
					$("#mess").stop(true,true).fadeOut(2000);
				}
				break;
			case 'fox':
				if(totol_fox<99){
					number = 1;
					$("#table_4").show();
					$("#table_5").show();
				}else{
					$("#message").text("数量达到上限");
					$("#mess").stop(true,true).fadeIn(100);
					$("#mess").stop(true,true).fadeOut(2000);
				}
				break;
			case 'giraffe':
				if(totol_giraffe<99){
					number = 1;
					$("#table_4").show();
					$("#table_5").show();
				}else{
					$("#message").text("数量达到上限");
					$("#mess").stop(true,true).fadeIn(100);
					$("#mess").stop(true,true).fadeOut(2000);
				}
				break;
			case 'panda':
				if(totol_panda<99){
					number = 1;
					$("#table_4").show();
					$("#table_5").show();
				}else{
					$("#message").text("数量达到上限");
					$("#mess").stop(true,true).fadeIn(100);
					$("#mess").stop(true,true).fadeOut(2000);
				}
				break;
			case 'sheep':
				if(totol_sheep<99){
					number = 1;
					$("#table_4").show();
					$("#table_5").show();
				}else{
					$("#message").text("数量达到上限");
					$("#mess").stop(true,true).fadeIn(100);
					$("#mess").stop(true,true).fadeOut(2000);
				}
				break;
			}
			var num=to_img(number,'num-');
			$("#number").html(num);
		});
		
		//数量增加按钮
		$("#plus").mouseup(function(){
			switch($type){
				case 'dog':
					if (totol_dog+number<99){
						if(nowmoney-number*10000>10000){
							number++;
						}else{
							$("#message").text("金币不足");
							$("#mess").stop(true,true).fadeIn(100);
							$("#mess").stop(true,true).fadeOut(2000);
						}
					}else{
						$("#message").text("数量达到上限");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
					break;
				case 'bird':
					if (totol_bird+number<99){
						if(nowmoney-number*10000>10000){
							number++;
						}else{
							$("#message").text("金币不足");
							$("#mess").stop(true,true).fadeIn(100);
							$("#mess").stop(true,true).fadeOut(2000);
						}
					}else{
						$("#message").text("数量达到上限");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
					break;
				case 'fox':
					if (totol_fox+number<99){
						if(nowmoney-number*10000>10000){
							number++;
						}else{
							$("#message").text("金币不足");
							$("#mess").stop(true,true).fadeIn(100);
							$("#mess").stop(true,true).fadeOut(2000);
						}
					}else{
						$("#message").text("数量达到上限");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
					break;
				case 'giraffe':
					if (totol_giraffe+number<99){
						if(nowmoney-number*10000>10000){
							number++;
						}else{
							$("#message").text("金币不足");
							$("#mess").stop(true,true).fadeIn(100);
							$("#mess").stop(true,true).fadeOut(2000);
						}
					}else{
						$("#message").text("数量达到上限");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
					break;
				case 'panda':
					if (totol_panda+number<99){
						if(nowmoney-number*10000>10000){
							number++;
						}else{
							$("#message").text("金币不足");
							$("#mess").stop(true,true).fadeIn(100);
							$("#mess").stop(true,true).fadeOut(2000);
						}
					}else{
						$("#message").text("数量达到上限");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
					break;
				case 'sheep':
					if (totol_sheep+number<99){
						if(nowmoney-number*10000>10000){
							number++;
						}else{
							$("#message").text("金币不足");
							$("#mess").stop(true,true).fadeIn(100);
							$("#mess").stop(true,true).fadeOut(2000);
						}
					}else{
						$("#message").text("数量达到上限");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
					break;
			}
			var num=to_img(number,'num-');
			$("#number").html(num);
		});
		
		//数量减少按钮
		$("#minus").mouseup(function(){
			if(number>1){
				number--;
				var num=to_img(number,'num-');
				$("#number").html(num);
			}
		});
		
		//最大数量按钮
		$("#max").mouseup(function(){
			switch($type){
			case 'dog':
				number = 99-totol_dog;
				if(nowmoney-number*10000<0){
					number = parseInt(nowmoney/10000);
					if(number == 0){
						number = 1;
					}
				}
				break;
			case 'bird':
				number = 99-totol_bird;
				if(nowmoney-number*10000<0){
					number = parseInt(nowmoney/10000);
					if(number == 0){
						number = 1;
					}
				}
				break;
			case 'fox':
				number = 99-totol_fox;
				if(nowmoney-number*10000<0){
					number = parseInt(nowmoney/10000);
					if(number == 0){
						number = 1;
					}
				}
				break;
			case 'giraffe':
				number = 99-totol_giraffe;
				if(nowmoney-number*10000<0){
					number = parseInt(nowmoney/10000);
					if(number == 0){
						number = 1;
					}
				}
				break;
			case 'panda':
				number = 99-totol_panda;
				if(nowmoney-number*10000<0){
					number = parseInt(nowmoney/10000);
					if(number == 0){
						number = 1;
					}
				}
				break;
			case 'sheep':
				number = 99-totol_sheep;
				if(nowmoney-number*10000<0){
					number = parseInt(nowmoney/10000);
					if(number == 0){
						number = 1;
					}
				}
				break;
			}
			var num=to_img(number,'num-');
			$("#number").html(num);
		});
		
		var change_num;
		
		//确定按钮
		$("#ensure").mouseup(function(){
			$.ajax({
				type:"POST",
				url:"http://user.dianler.com/index.php?m=activity&p=dispath&fpath=lebaocaicai&act=buy&buy_type="+$type+"",
				dataType: "json", 	
				data:"number="+number
					+"&gameid=<?php echo $gameid?>"
					+"&mid=<?php echo $mid?>"
					+"&sid=<?php echo $sid?>"
					+"&cid=<?php echo $cid?>"
					+"&pid=<?php echo $pid?>"
					+"&ctype=<?php echo $ctype?>"
					+"&money=<?php echo $money?>",
				success:function(e){
					var status = e.status;
					var today_buy_count = e.today_count;
					var	money = e.money;
					if(status=='1'){
						$("#table_4").hide();
						$("#table_5").hide();
					
						switch($type){
							case 'dog':
								var change_num = today_buy_count.dog_today;
								totol_dog	   = today_buy_count.dog_today;
							break;
							case 'bird':
								var change_num = today_buy_count.bird_today;
								totol_bird	   = today_buy_count.bird_today;
							break;
							case 'fox':
								var change_num = today_buy_count.fox_today;
								totol_fox	   = today_buy_count.fox_today;
							break;
							case 'giraffe':
								var change_num = today_buy_count.giraffe_today;
								totol_giraffe  = today_buy_count.giraffe_today;
							break;
							case 'panda':
								var change_num = today_buy_count.panda_today;
								totol_panda	   = today_buy_count.panda_today;
							break;
							case 'sheep':
								var change_num = today_buy_count.sheep_today;
								totol_sheep	   = today_buy_count.sheep_today;
							break;
						}
						var change 	= to_img(change_num,'num-');
						var x		="<img src='web/activity/lebaocaicai/image/x.png'>";
						$("#"+$type+"_tonum").html(x+change);
						
						var minus = money;
						nowmoney  = money;
						var totol_money = to_img(minus,'money-');
						$("#mymoney").html(totol_money);
						
					}else if(status=='-1'){
						$("#table_4").hide();
						$("#recharge").show();
					}
					else {
						$("#message").text("购买失败");
						$("#mess").stop(true,true).fadeIn(100);
						$("#mess").stop(true,true).fadeOut(2000);
					}
				}
			});
		});
		
		
		
		//取消按钮
		$("#cancel").mouseup(function(){
			$("#table_4").hide();
			$("#table_5").hide();			
		});
		$("#close").mouseup(function(){
			$("#table_4").hide();
			
			$("#recharge").hide();		
		});
		
		var which  = <?php echo $which ? $which : 0 ?>;
		if(which){
			var source = "<img src='web/activity/lebaocaicai/image/<?php echo $which?>.png' style='width:223px; height:220px;'>";
			$("#winning_icon").html(source);
		}
		
		//充值跳转
		$("#go").mouseup(function(){
			window.location.href="http://www.dianlergame.com/paycenter/index.php?m=pay&p=index&act=list&gameid=<?php echo $gameid?>&mid=<?php echo $mid?>&sid=<?php echo $sid?>&cid=<?php echo $cid?>&pid=<?php echo $pid?>&ctype=<?php echo $ctype?>&versions=&mnick=<?php echo $mnick?>&money=<?php echo $money?>"; 
		});
	});
</script>
</html>
