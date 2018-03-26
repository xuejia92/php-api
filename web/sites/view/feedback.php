<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>无标题文档</title>
<style>
body{font:12px/1.5 Arial, Helvetica, sans-serif;}
ul,li{list-style:none;}
a{text-decoration:none;}
img{border:none;vertical-align:middle;}

/*=========help=======*/
	#wrap{
		height:357px;_height:355px;
		z-index:999;
		width:496px;
		background:url(statics/web/image/helpDivBg.png) no-repeat;
		margin:0 auto;
		text-align:center;
		left:250px;
		top:175px;
		position:absolute;
	} 
	#container { 
		height:252px;
		_height:250px;
		padding-left:15px;
		width:427px;
		background:url(statics/web/image/helpContentDivBg.png) no-repeat; 
	}	 

	#container #content{ 
		height:240px;width:400px;float:left;overflow:hidden;
		color:#666666;
	} 
 
	#container #scroll{ 
		height:200px;width:15px;float:right; 
	}

 
	.b4-2{font-size:0px; height:245px; width:15px; background-image:url(statics/web/image/scrollLine.png);position:relative;} 
	.b4-3{font-size:0px; height:57px; width:17px; background-image:url(statics/web/image/scrollButton.png);  position:absolute;  } 
	
/*=========feedback=======*/
	#container1 { 
		height:209px;
		_height:207px;
		padding-left:15px;
		width:427px;
		background:url(statics/web/image/feedbackContentDivBg.png) no-repeat; 
	} 

	#container1 #content1{
		padding-top:10px;
		padding-bottom:10px;
		height:179px;width:400px;float:left;overflow:hidden;
		color:#666666;
	}  
	#container1 #scroll1{ 
		height:200px;width:15px;float:right; 
	}

	/*history*/
	#container2 { 
		height:252px;
		_height:250px;
		padding-left:15px;
		width:427px;
		background:url(statics/web/image/helpContentDivBg.png) no-repeat; 
	} 

	#container2 #content2{ 
		height:240px;width:400px;float:left;overflow:hidden;
		color:#666666;
	} 
 
	#container2 #scroll2{ 
		height:200px;width:15px;float:right; 
	}
</style>
</head>
<body>

		<!--帮助弹框-->
		<div id="wrap" >
			<!--关闭按钮-->
			<div style="width:496px;height:21px;">
				<a href="javascript:;" onclick="showWindow()" style="float:right;margin-top:15px;margin-right:15px;width:20px;height:21px;background:url(statics/web/image/helpClose.png) no-repeat" onmouseover="this.style.background='url(statics/web/image/helpClose_inout.png?1=1) no-repeat';" onmouseout="this.style.background='url(statics/web/image/helpClose.png?1=1) no-repeat';"></a>
				<div style="clear:both;"></div>
			</div>
			
			<!--tab切换按钮-->
			<div style="width:496px;height:34px;margin-top:20px;">
				<a href="javascript:;" id="help" onclick="return changeTab(0);" style="float:left;margin-left:10px;width:120px;height:34px;background:url(statics/web/image/helpButton_click.png) no-repeat" onmouseover="this.style.background='url(statics/web/image/helpButton_inout.png) no-repeat';" onmouseout="tab()"></a>
				<a href="javascript:;" id="feedback" onclick="return changeTab(1);"style="float:left;margin-left:5px;width:120px;height:34px;background:url(statics/web/image/feedbackButton.png) no-repeat" onmouseover="this.style.background='url(statics/web/image/feedbackButton_inout.png) no-repeat';" onmouseout="tab()"></a>
				<a href="javascript:;" id="history" onclick="history_http()"style="float:left;margin-left:5px;width:120px;height:34px;background:url(statics/web/image/feedbackHistory.png) no-repeat" onmouseover="this.style.background='url(statics/web/image/feedbackHistory_inout.png) no-repeat';" onmouseout="tab()"></a>
				<div style="clear:both;"></div>
			</div> 
			
			<!--帮助面板-->
			<div id="container" style="margin:0 auto;text-align:center;margin-top:10px;"> 
				<div id="content" style="text-align:left;margin-top:10px;outline:none;font-size:14px;line-height:25px;"> 
					<div style="font-weight:bold;color:#3b7e49;">点乐客服电话：0755-86222391</div>
					<div style="font-weight:bold;color:#3b7e49;">游戏介绍</div>
					<div>
						斗地主是一款最初流行于湖北三人扑克游戏，两个农民联合对抗一名地主，由于其规则简单、娱乐性强，迅速风靡全国。点乐斗地主是在传统规则的基础上，增加抢地主、明牌等一系列新玩法，而推出的一款更紧张刺激、更富于变化的斗地主游戏。
					</div>
					<div style="margin-top:10px;font-weight:bold;color:#3b7e49;">游戏规则</div>
					<div style="margin-top:5px;">
						发牌：一副牌 54 张，一人 17 张，留 3 张做底牌，确认地主后可以查看底牌。<br>
						叫地主：第一次叫地主按入场的玩家优先选择 叫地主，下局则为地主的下一家拥有优先权。如果有玩家选择 叫地主下家可以抢地主，每个用户拥有一次抢地主的机会，最后一个抢地主的用户为地主，如果所有用户都‘不叫’则重新发牌，重新叫牌，直到有人叫地主。每次抢地主基础倍数*2。<br>
						明牌：明牌为亮明手上所有牌进行游戏，主要分为二种： 发牌明牌、明牌。<br>
						发牌明牌：在发牌的过程中可以开始选择是否明牌，当发的牌为7张前，选择明牌，倍数*5；当发的牌为13张前，选择明牌，倍数*4；当发的牌为17张前，选择明牌，倍数*3；<br>
						明牌：在抢完地主后，地主出牌时，可以选择明牌，倍数*2。<br>
						出牌：抢完地主后，将三张底牌亮明给所有玩家查看并交给地主。地主道先出牌，然后按逆时针依次出牌，轮到玩家跟牌时，玩家可以选择不出或出比上一个玩家大的牌。某一玩家出完牌时本局结束并获得胜利。<br>
						牌型：指牌的组合。<br>
						单张：单个牌（如红桃3）。<br>
						对子：指二张不同花色但同样数字的牌（如：红桃3+梅花3，即33）。<br>
						三张牌：指三张不同花色但同样数字的牌（如：红桃3+梅花3+黑桃3，即333）。<br>
						三带一：指三张不同花色但同样数字的牌+单张（如：333+4）。<br>
						三带对：指三张不同花色但同样数字的牌+对子（如：333+44）。<br>
						单顺：五张或更多的连续单牌（如：34567，不包括2与王）。<br>
						双顺：三对或更多连续对子（如：334455）。<br>
						飞机带翅膀：二个或更多的连续三张牌（如333444，333444555，333444+12，333444+1122）。<br>
						四带二：四张牌+两张牌。（注意：四带二不是炸弹）。<br>
						炸弹：四张相同数字的牌（7777），每出一个炸弹，倍数*2。<br>
						火箭：即大小王，最大的牌。出后，倍数*2。<br>
						牌型大小：火箭最大，可以打其他任意牌型；仅小于火箭的是炸弹，比其他牌大。都是炸弹时按牌的数值大小。其他牌必须要牌型相同且总张数相同才能比大小。单牌按数值大小比，依次是 大王 > 小王 >2>A>K>Q>J>10>9>8>7>6>5>4>3 ，不分花色。 对牌、三张牌都按分值比大小。 顺牌按最大的一张牌的分值来比大小。 飞机带翅膀和四带二按其中的三顺和四张部分来比，带的牌不影响大小。<br>  
						胜负判定：先出完牌胜利。<br>
						计分：本游戏使用金币为基础货币。<br>
						失败的玩家消耗：基础倍数*底注*倍数*N+台费（N为玩家身份：农民为1，地主为2）。<br>
						胜利的玩家获得：基础倍数*底注*倍数*N - 台费（N为玩家身份：农民为1，地主为2）。<br>
						等级规则：每级所需要经验满了则等级+1，满级为100级。<br>
						每级所需要经验=（等级-1）^3+49<br>
						胜每局+2EXP<br>
						负每局+1EXP<br>
						每出一个炸弹的用户+2EXP
					</div>
				</div> 
	
				<div id="scroll"> 
					<div id='p1' class="b4-1"></div> 
					<div id="scroll4" class="b4-2"> 
						<div id="block4" class="b4-3"> </div> 
					</div> 
					<div id='p2' class="b4-4"></div> 
				</div>
				
				<div style="clear:both;"></div>
			</div>

			<!--反馈面板-->
			<div id="container1" style="display:none;margin:0 auto;text-align:center;margin-top:10px;"> 
				<div id="content1" style="text-align:left;outline:none;" contenteditable="true" draggable="false"> </div> 
	
				<div id="scroll1"> 
					<div id='p11' class="b4-1"></div> 
					<div id="scroll41" class="b4-2"> 
						<div id="block41" class="b4-3"> </div> 
					</div> 
					<div id='p21' class="b4-4"></div> 
				</div>
				
				<div style="clear:both;"></div>
	
				<div id="CommitButton" style="width:496px;height:34px;margin-top:13px;">
					<a href="javascript:;" onclick="feedbackReset();" style="float:left;margin-left:58px;width:123px;height:40px;background:url(statics/web/image/feedbackReset.png) no-repeat"></a>
					<a href="javascript:;" onclick="feedbackCommit();" style="float:left;margin-left:50px;width:123px;height:40px;background:url(statics/web/image/feedbackCommit.png) no-repeat"></a>
					<div style="clear:both;"></div>
				</div> 

			</div>
			
			<!--历史反馈记录面板-->
			<div id="container2" style="display:none;margin:0 auto;text-align:center;margin-top:10px;"> 
				<div id="content2" style="text-align:left;outline:none;font-size:14px;"> 
				<div></div>
				<div style="margin-top:10px;">感谢您的反馈，我们将会在第一时间处理。</div> 
				</div> 
	
				<div id="scroll2"> 
					<div id='p12' class="b4-1"></div> 
					<div id="scroll42" class="b4-2"> 
						<div id="block42" class="b4-3"> </div> 
					</div> 
					<div id='p22' class="b4-4"></div> 
				</div>

				<div style="clear:both;"></div>
			</div>
		</div>
<script type="text/javascript">

var mid   = " <?php echo $_REQUEST['mid']  ? $_REQUEST['mid']   : ''?>";
var mnick = "<?php echo $_REQUEST['mnick'] ? $_REQUEST['mnick'] : ''?>";
var content='';

//提交反馈
function submit_http(){
	var xmlhttp;
	if(window.XMLHttpRequest){
		try{
			xmlhttp = new XMLHttpRequest();
		}catch(e){
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}catch(e){
					xmlhttp = false;
				}
			}
		}
	}
	
	if(!xmlhttp){
		document.getElementById("content1").innerHTML = "反馈提交失败，系统异常，请稍后再尝试反馈！";
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4&& xmlhttp.status==200 ){

			 var str = xmlhttp.responseText;
             
			 if(str ==null|| str ==""){
			    document.getElementById("content1").innerHTML = "反馈提交失败，可能网络异常，请稍后再尝试反馈！";
			    return;
			 }else{
			   //alert(str);
			   //alert(11);
			   
			  
			   var obj = eval('('+str+')');
			   
			   if(obj.result==1){
				 document.getElementById("content1").innerHTML = "";
				 history_http();
				 document.getElementById("CommitButton").innerHTML = "<a href='javascript:;' onclick='feedbackReset();' style='float:left;margin-left:58px;width:123px;height:40px;background:url(statics/web/image/feedbackReset.png) no-repeat'></a><a href='javascript:;' onclick='feedbackCommit();' style='float:left;margin-left:50px;width:123px;height:40px;background:url(statics/web/image/feedbackCommit.png) no-repeat'></a><div style='clear:both;'></div>"; 
	
			   }else{
				 document.getElementById("content1").innerHTML = "反馈提交失败，可能网络异常，请稍后再尝试反馈！";
			   }

			 }
		}
	};
	var url = "?m=sites&p=feedback&mid="+mid+"&content="+content+"&mnick="+mnick;
	xmlhttp.open("GET", url, true);
	xmlhttp.setRequestHeader("Content-Type","text/plain");
	xmlhttp.send();


}

//获取历史记录
function history_http(){
	var xmlhttp;
	var element = document.getElementById('content2');
	element.innerHTML = "<div></div><div style='margin-top:10px;'>感谢您的反馈，我们将会在第一时间处理。</div> ";
	if(window.XMLHttpRequest){
		try{
			xmlhttp = new XMLHttpRequest();
		}catch(e){
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}catch(e){
					xmlhttp = false;
				}
			}
		}
	}
	
	if(!xmlhttp){
		element.innerHTML = "<div></div><div style='margin-top:10px;'>网络异常，请稍后重试。</div> ";
		changeTab(2);
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4&& xmlhttp.status==200 ){

			 var str = xmlhttp.responseText;
             
			 if(str ==null|| str ==""){
			    element.innerHTML = "<div></div><div style='margin-top:10px;'>网络异常，请稍后重试。</div> ";
				changeTab(2);
			    return;
			 }else{
			   
			   
			   var obj = eval('('+str+')');
			   
			   if(obj.result==1){
					var m = obj.msg;
					var total = m.length-1;
					for(var i=total;i>=0;i--){
					   var tmp = "<div style='margin-top:10px;color:#3b7e49'>"+m[i]['ctime']+" 您的反馈</div> <div style='margin-top:5px;'>"+m[i]['feedback']+"</div>";
					   var tmpReply="";
					   if(m[i]['reply']!=""){
							tmpReply = "<div style='margin-top:5px;color:red;'>"+m[i]['rtime']+" 系统回复</div><div style='margin-top:5px;color:red;'>"+m[i]['reply']+"</div>";
					   }
					   element.innerHTML = element.innerHTML+tmp+tmpReply;
					}
				}
			   changeTab(2);
			 }
		}
	};
	
	var url = "?m=sites&p=feedbackHistory&mid="+mid;

	xmlhttp.open("GET", url, true);
	xmlhttp.setRequestHeader("Content-Type","text/plain");
	xmlhttp.send();
}

//重置
function feedbackReset(){
	document.getElementById("content1").innerHTML = "";
	document.getElementById("CommitButton").innerHTML = "<a href='javascript:;' onclick='feedbackReset();' style='float:left;margin-left:58px;width:123px;height:40px;background:url(statics/web/image/feedbackReset.png) no-repeat'></a><a href='javascript:;' onclick='feedbackCommit();' style='float:left;margin-left:50px;width:123px;height:40px;background:url(statics/web/image/feedbackCommit.png) no-repeat'></a><div style='clear:both;'></div>"; 
}

//
function feedbackCommit(){
	document.getElementById("CommitButton").innerHTML = "<a href='javascript:;' onclick='feedbackReset();' style='float:left;margin-left:58px;width:123px;height:40px;background:url(statics/web/image/feedbackReset.png) no-repeat'></a><a href='javascript:;'  style='float:left;margin-left:50px;width:123px;height:40px;background:url(statics/web/image/feedbackCommit.png) no-repeat'></a><div style='clear:both;'></div>"; 
	content = document.getElementById("content1").innerHTML;
	
	content = content.replace(/<[^>].*?>/g,"");

	submit_http();
} 

function showWindow(){
	var mObj = document.getElementById("wrap");
	var css  = mObj.style.display;
	if(css=="none"){
		mObj.style.display = "block";
		var deom4=new Slider($('container'),$('block4'),$('scroll4'),{shapechange:false,topvalue:2,bottomvalue:6,border:1}); 
		addListener($('p1'),'click',Bind(deom4,deom4.Left)); 
		addListener($('p2'),'click',Bind(deom4,deom4.Right));
	}else{
		mObj.style.display = "none";
	}
}

var now_tab = 0;

function tab(){
	var imgHelp   	= "url(statics/web/image/helpButton.png) no-repeat";
	var imgFeedback = "url(statics/web/image/feedbackButton.png) no-repeat";
	var imgHistory  = "url(statics/web/image/feedbackHistory.png) no-repeat";
	
	if(now_tab == 0){
		imgHelp   = "url(statics/web/image/helpButton_click.png) no-repeat";
	}else if(now_tab == 1){
		imgFeedback = "url(statics/web/image/feedbackButton_click.png) no-repeat";
	}else if(now_tab == 2){
		imgHistory  = "url(statics/web/image/feedbackHistory_click.png) no-repeat";
	}
	
	document.getElementById("help").style.background 	 = imgHelp;
	document.getElementById("feedback").style.background = imgFeedback;
	document.getElementById("history").style.background  = imgHistory;
}

function changeTab(tab){
	var temp = tab;
	var m;
	var n;
	
	if(temp == 0){
		m=document.getElementById("container");
	}else{
		m= document.getElementById("container"+temp);
	}
	if(now_tab == 0){
		n = document.getElementById("container");
	}else{
		n = document.getElementById("container"+now_tab);
	}
	
	n.style.display = 'none';
	m.style.display = 'block';
	
	
	if(temp == 1){
		var deom5=new Slider($('container1'),$('block41'),$('scroll41'),{shapechange:false,topvalue:2,bottomvalue:-35,border:1}); 
		addListener($('p11'),'click',Bind(deom5,deom5.Left)); 
		addListener($('p21'),'click',Bind(deom5,deom5.Right));
	}else if(temp == 2){
		var deom6=new Slider($('container2'),$('block42'),$('scroll42'),{shapechange:false,topvalue:2,bottomvalue:6,border:1}); 
		addListener($('p12'),'click',Bind(deom6,deom6.Left)); 
		addListener($('p22'),'click',Bind(deom6,deom6.Right));
	}
	now_tab = temp;
	tab();
}


//**************模拟scroll相关js**************//
var Sys = (function(ua){ 
	var s = {}; 
	s.IE = ua.match(/msie ([\d.]+)/)?true:false; 
	s.Firefox = ua.match(/firefox\/([\d.]+)/)?true:false; 
	s.Chrome = ua.match(/chrome\/([\d.]+)/)?true:false; 
	s.IE6 = (s.IE&&([/MSIE (\d)\.0/i.exec(navigator.userAgent)][0][1] == 6))?true:false; 
	s.IE7 = (s.IE&&([/MSIE (\d)\.0/i.exec(navigator.userAgent)][0][1] == 7))?true:false; 
	s.IE8 = (s.IE&&([/MSIE (\d)\.0/i.exec(navigator.userAgent)][0][1] == 8))?true:false; 
	return s; 
})(navigator.userAgent.toLowerCase()); 

Sys.IE6&&document.execCommand("BackgroundImageCache", false, true); 

var $ = function (id) { 
	return "string" == typeof id?document.getElementById(id):id; 
}; 

var $$ = function(p,e){ 
	return p.getElementsByTagName(e); 
}; 

function addListener(element,e,fn){ 
	element.addEventListener?element.addEventListener(e,fn,false):element.attachEvent("on" + e,fn) 
}; 

function removeListener(element,e,fn){ 
	element.removeEventListener?element.removeEventListener(e,fn,false):element.detachEvent("on" + e,fn) 
}; 

function create(elm,parent,fn){ 
	var element = document.createElement(elm);parent.appendChild(element);if(fn)fn(element); 
}; 

var Css = function(e,o){ 
	if(typeof o=="string") 
	{ 
		e.style.cssText=o; 
		return; 
	} 
	for(var i in o) 
		e.style[i] = o[i]; 
};

function getobjpos(el,left){ 
	var val = 0; 
	while (el !=null) { 
		val += el["offset" + (left? "Left": "Top")]; 
		el = el.offsetParent; 
	} 
	return val; 
}; 

var CurrentStyle = function(element){ 
	return element.currentStyle || document.defaultView.getComputedStyle(element, null); 
}; 

var Bind = function(object, fun){ 
	var args = Array.prototype.slice.call(arguments).slice(2); 
	return function() { 
		return fun.apply(object, args); 
	} 
}; 

var BindAsEventListener = function(object, fun,args) { 

	var args = Array.prototype.slice.call(arguments).slice(2); 
	return function(event) { 
		return fun.apply(object, [event || window.event].concat(args)); 
	} 
}; 

var Tween = {
	Linear: function(t,b,c,d){ return c*((t=t/d-1)*t*t*t*t + 1) + b; } 
}; 
var Extend = function(destination, source) { 
	for (var property in source) { 
		destination[property] = source[property]; 
	} 
}; 

var Class = function(properties){ 
	var _class = function(){return (arguments[0] !== null && this.initialize && typeof(this.initialize) == 'function') ? this.initialize.apply(this, arguments) : this;}; 
	_class.prototype = properties; 
	return _class; 
}; 

var Drag = new Class({ 
options:{ 
	Limit : false, 
	mxLeft : 0, 
	mxRight : 9999, 
	mxTop : 0, 
	mxBottom : 9999, 
	mxContainer : null, 
	LockX : false, 
	LockY : false, 
	zIndex : 2, 
	Onstart : function(){}, 
	Onmove : function(){}, 
	Onstop : function(){} 
},

initialize:function(obj,options){ 
	this._obj = obj; 
	this._x = 0; 
	this._y = 0; 
	this._marginLeft = 0; 
	this._marginTop = 0; 
	this._fM = BindAsEventListener(this, this.Move); 
	this._fS = Bind(this, this.Stop); 
	var o ={}; 
	Extend(o,this.options); 
	Extend(o,options||{}); 
	Extend(this,o); 
	this.zIndex = Math.max(this.zIndex,Drag.zIndex||0); 
	if(this.mxContainer&&this.Limit) //设置了限制 和 容器限制后 计算边界直 
	{ 
		this.mxLeft   = getobjpos(this.mxContainer,1); 
		this.mxTop 	  = 0;//getobjpos(this.mxContainer,0); 
		this.mxRight  = getobjpos(this.mxContainer,1) + this.mxContainer.offsetWidth-this._obj.offsetWidth; 
		this.mxBottom =this.mxContainer.offsetHeight-this._obj.offsetHeight; 
	} 
	addListener(this._obj,"mousedown",BindAsEventListener(this,this.Start)); 
	Drag.zIndex = this.zIndex; 
}, 

Start:function(e){ 
	this._obj.style.zIndex = ++Drag.zIndex; 
	this._x = e.clientX - this._obj.offsetLeft ; 
	this._y = e.clientY - this._obj.offsetTop; 
	this._marginLeft = parseInt(this._obj.style.marginLeft)||0; 
	this._marginTop = parseInt(this._obj.style.marginTop)||0; 
	if(Sys.IE) 
	{ 
		addListener(this._obj, "losecapture", this._fS); 
		this._obj.setCapture(); 
	} else { 
		e.preventDefault(); 
		addListener(window, "blur", this._fS); 
	} 
	addListener(document,"mousemove",this._fM); 
	addListener(document,"mouseup",this._fS); 
	this.Onstart(); 
}, 
Move:function(e){ 
	window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty(); 
	var iLeft = e.clientX - this._x, iTop = e.clientY - this._y; 
	if(this.Limit){ 
		iLeft= Math.min(Math.max(iLeft,this.mxLeft),this.mxRight); 
		iTop = Math.min(Math.max(iTop,this.mxTop),this.mxBottom); 
	} 
	if(!this.LockX)this._obj.style.left = iLeft - this._marginLeft + "px"; 
	if(!this.LockY)this._obj.style.top = iTop - this._marginTop + "px"; 
		this.Onmove(); 
}, 

Stop:function(){ 
	removeListener(document,'mousemove',this._fM); 
	removeListener(document,'mouseup',this._fS); 
	if(Sys.IE){ 
		removeListener(this._obj, "losecapture", this._fS); 
		this._obj.releaseCapture(); 
	}else 
		removeListener(window, "blur", this._fS); 
		this.Onstop(); 
} 
});

var Slider = new Class({ 
options :{ 
direction : true, //true为纵,false为横 
type : "Y", //分别为X,Y,N 
shapechange : true, //是否该变滚动条的形状 
topvalue : 0, //上或左偏差值 
bottomvalue : 0, //下或右偏差值 
border : 0, //处理ie下border的问题 
step : 1, //键盘操作时候的步长 
t : 0, 
b : 0, 
c : 0, 
d : 40, 
Onmove : function(){} 
}, 

initialize :function(container,block,blockcontainer,options){ 
	this.container = container; 
	this.block = block; 
	this.blockcontainer = blockcontainer; 
	var o ={}; 
	Extend(o,this.options); 
	Extend(o,options||{}); 
	Extend(this,o); 
	this.timer = null; 
	this.ismove = false; 
	var _self = this; 

	var xc = this.blockcontainer?this.blockcontainer:this.container; 
	addListener(xc,'click',BindAsEventListener(this,this.Start)); 
	addListener(this.block,'click',BindAsEventListener(this,this.Bubble)); 

	
	//this.KeyBind(this.container); //键盘与鼠标滑轮部分参考的cloudgamer的 这里注释掉 影响到了文本框的输入
	
	this.WheelBind(this.container); 
	var oFocus = Sys.IE ? this.block : this.container; 
	addListener(this.block, "mousedown", function(){ _self.Stopmove();oFocus.focus();}); 

	this.drag = new Drag(this.block,{Limit:true,mxContainer:xc,Onmove:Bind(this,this.Move)}); 
	this.drag[this.direction?"LockX":"LockY"] = true; 
	this.border = (Sys.IE6||Sys.IE7)?this.border:0; //ie6,7下 border也要算进去 不然有误差 ie8,却又不需要算 真是纠结............ 
	if(this.direction) 
	{ 
		this.drag.mxTop = this.drag.mxTop - this.topvalue + this.border; 
		this.drag.mxBottom = this.drag.mxBottom + this.bottomvalue + this.border; 
		this.block.style.top = this.drag.mxTop+"px"; 
	}else{ 
		this.drag.mxLeft = this.drag.mxLeft - this.topvalue + this.border; 
		this.drag.mxRight = this.drag.mxRight + this.bottomvalue + this.border; 
		this.block.style.left = this.drag.mxLeft+"px"; 
	} 
	this.Move(); 
}, 

Keycontrol : function(e){ 
	this.Stopdefault(e); 
	this.Stopmove(); 
	var l1 =this.direction?"top":"left",l2 = this.direction?"offsetTop":"offsetLeft",l3=this.direction?"mxTop":"mxLeft",l4=this.direction?"mxBottom":"mxRight"; 
	if(e.keyCode==37||e.keyCode==38)this.block.style[l1] = Math.max(this.block[l2]-this.step,this.drag[l3]) +'px'; 
	if(e.keyCode==39||e.keyCode==40)this.block.style[l1] = Math.min(this.block[l2]+this.step,this.drag[l4]) +'px'; 
	this.Move(); 
},

Wheelcontrol :function(e){ 
	this.Stopmove(); 
	var t = Sys.Firefox?e.detail:e.wheelDelta; 
	var l1 =this.direction?"top":"left",l2 = this.direction?"offsetTop":"offsetLeft",l3=this.direction?"mxTop":"mxLeft",l4=this.direction?"mxBottom":"mxRight"; 
	this.block.style[l1] = Sys.Firefox?(t<0?Math.max(this.block[l2]-5,this.drag[l3])+'px':Math.min(this.block[l2]+5,this.drag[l4]) +'px'):(t>0?Math.max(this.block[l2]-5,this.drag[l3])+'px':Math.min(this.block[l2]+5,this.drag[l4]) +'px'); 
	this.Move(); 
	this.Stopdefault(e); 
}, 

WheelBind : function(o){ 
	addListener(o, Sys.Firefox? "DOMMouseScroll" : "mousewheel", BindAsEventListener(this,this.Wheelcontrol)); 
}, 

KeyBind : function(o){ 
	addListener(o,'keydown',BindAsEventListener(this,this.Keycontrol)); 
	o.tabIndex = -1; 
	Sys.IE || (o.style.outline = "none"); 
}, 

Move : function(){ 
	var c = $$(this.container,"div")[0] ; 
	/*=========================================================================================================================*/ 
	if(this.type=="Y")c.scrollTop = (c.scrollHeight - c.offsetHeight)*(this.block.offsetTop - this.drag.mxTop)/(this.blockcontainer.offsetHeight - this.block.offsetHeight +this.topvalue+this.bottomvalue); 
	if(this.type=="X")c.scrollLeft = (c.scrollWidth - c.offsetWidth)*(this.block.offsetLeft - this.drag.mxLeft)/(this.blockcontainer.offsetWidth-this.block.offsetWidth+this.topvalue+this.bottomvalue); 
	/*上面为滚动条的计算方式*/ 
	/*=========================================================================================================================*/ 
	this.Onmove(); 
}, 

Start : function(e){ 
	this.ismove = true; 
	this.b = this.direction?this.block.offsetTop:this.block.offsetLeft; 
	if(this.direction) 
	{ 
		var t = Sys.Chrome?document.body.scrollTop:document.documentElement.scrollTop; 
		var l = (e.clientY+t)<=this.block.offsetTop?0:this.block.offsetHeight; 
	}else{ 
		var t = Sys.Chrome?document.body.scrollLeft:document.documentElement.scrollLeft; 
		var l = (e.clientX+t)<=this.block.offsetLeft?0:this.block.offsetWidth; 
	} 
	this.c = this.direction?(e.clientY+t-l):(e.clientX+t-l); 
	this.t = 0; 
	this.Run(); 
},

Run : function(){ 
	if(!this.ismove)return; 
	clearTimeout(this.timer); 
	if(this.t<this.d) 
	{ 
		this.Runto(Math.round(Tween.Linear(this.t++,this.b,(this.c-this.b),this.d))); 
		this.timer = setTimeout(Bind(this,this.Run),5); 
	}else{ 
		this.Runto(this.c); 
		this.t=0; 
		this.ismove = true; 
	} 
},

Runto : function(i){ 
	this.block.style[this.direction?"top":"left"] = i + "px"; 
	this.Move(); 
}, 

Bubble : function(e){ 
	Sys.IE?(e.cancelBubble=true):(e.stopPropagation()); 
}, 

Stopdefault : function(e){ 
	Sys.IE?(e.returnValue = false):(e.preventDefault()); 
},

Left : function(o){ 
	this.Lrmove("mxTop","mxLeft"); 
}, 

Right : function(){ 
	this.Lrmove("mxBottom","mxRight"); 
}, 

Lrmove : function(t,b){ 
	if(this.t!=0)return; 
	this.ismove = true; 
	this.b = this.direction?this.block.offsetTop:this.block.offsetLeft; 
	this.c = this.direction?this.drag[t]:this.drag[b]; 
	this.Run(); 
},

Stopmove : function(){ 
	this.ismove=false;clearTimeout(this.time);this.t = 0; 
} 
}) 
var deom4=new Slider($('container'),$('block4'),$('scroll4'),{shapechange:false,topvalue:2,bottomvalue:6,border:1}); 
addListener($('p1'),'click',Bind(deom4,deom4.Left)); 
addListener($('p2'),'click',Bind(deom4,deom4.Right));
</script>	
</body>
</html>
		
