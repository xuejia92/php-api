<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>梦想百家乐</title>

<style>
html,body{
   width:100%;
   margin: 0; padding: 0
   margin:0 auto;
   text-align : center;
}

html,body{
	background:url(./statics/web/download/backgroud.jpg?1=1) no-repeat 50% 0px;
}

#main{
	
   width:909px;
   height:675px;
   margin:0 auto;
   text-align : center;
   background:url(./statics/web/download/logo.png?1=1) no-repeat;
   
}

.imgT{
	width:175px;
	height:175px;
	border:1px solid #a5a6a8;
	display:block;
}

.aButton{
	display:block;
	margin-top:15px;
	width:176px;
	height:55px;
}
</style>
</head>
<body>
<div id="main">
	<div style="margin:0 auto;width:909px;text-align:center;"> 
		<div style="width:480px;margin:0 auto;text-align:center;">
			
			<div style="float:left;width:176px;margin-top:670px;"0>
				<img class="imgT" src='./statics/web/download/appleD.png'>
				<a target="_Blank" href="https://itunes.apple.com/us/app/meng-xiang-bai-jia-le/id860402884?ls=1&mt=8" class="aButton" style="background:url('./statics/web/download/appstore.png') no-repeat;">
				</a>
			</div>
			
			<div style="float:right;width:176px;margin-top:670px;">
				<img class="imgT" src='./statics/web/download/androidD.png'>
				<a href="http://xy30m.com/statics/apk/baccarat100%280422%29.apk" class="aButton" style="background:url('./statics/web/download/android.png') no-repeat;">
				</a>
			</div>
			<div style="clear:both;"></div>
		
		</div>
	</div>
</div>

<script type="text/javascript"> 
// 判断是否为移动端运行环境
// wukong.name 20130716
if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){
	if(window.location.href.indexOf("?mobile")<0){
		try{
			if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
				// 判断访问环境是 Android|webOS|iPhone|iPod|BlackBerry 则加载以下样式
				//setActiveStyleSheet("style_mobile_a.css");
				//document.write("<style>html,body{background:url(./statics/web/download/backgroud_mobile.jpg?1=1) no-repeat 50% 0px;}</style>");
			}
			else if(/iPad/i.test(navigator.userAgent)){
				// 判断访问环境是 iPad 则加载以下样式
				setActiveStyleSheet("style_mobile_iPad.css");
			}
			else{
				// 判断访问环境是 其他移动设备 则加载以下样式
				setActiveStyleSheet("style_mobile_other.css");
			}
		}
		catch(e){}
	}
}else{
// 如果以上都不是，则加载以下样式
setActiveStyleSheet("style_mobile_no.css");
}
// 判断完毕后加载样式
function setActiveStyleSheet(filename){document.write("<link href="+filename+" rel=stylesheet>");}

</script>

</body>
</html>