<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>幸运30秒</title>

<style>
html,body{
   margin: 0; padding: 0;
   margin:0 auto;
   text-align : center;
   background-color:#00101d;
}
img,a,input{
	border:none;
}

a{
	text-decoration: none
}
input:focus{outline:none;}
textarea:focus{outline:none;}

/*背景*/
#main{
	
   width:100%;
   height:835px;
   margin:0 auto;
   background: #00101d url(statics/web/image/background.jpg?1=1) no-repeat 50% 0px;
   
}

/*用于居中*/
#content {
	position:relative;
	width:970px;
	height:835px;
	margin:0 auto;
	overflow:hidden;
	
	-moz-user-select:none;
}

/*注册*/
#login{
	width:950px;
	height:565px;
	margin:0 auto;
	background:url(statics/web/image/loginBg.png?1=1) no-repeat;
	
}

#login input{
	float:left;
	line-height:24px;
	overflow:hidden;
	margin-top:2px;
	margin-left:-1px;
	width:160px;
	height:28px;

}

#login div img{
	float:left;
	margin-top:2px;
}

#register div img{
	float:left;
}

.child_div{
  width:506px;
  height:429px;
  position:absolute;

  z-index:3;
}
.registerContent{
	float:left;
	width:438px;
	color:#686868;
	padding-left:10px;
	height:387px;
	background-color:#fff;
	font-size:12px;
}

.label{
	float:left;
	height:22px;
	line-height:22px;
	width:60px;
	text-align:left;
}

.textInput{
	width:145px;
	height:22px;
	float:left;
	background:url(statics/web/image/textline.png);
}


/*找回密码*/
input{
	font-weight:bold;
}
#findUser{
	font-size:13px;
}
#findUser img{
	float:left;
}
#findUser a{
	margin:0 auto;
	text-align:center;
	margin-top:20px;
	width:123px;
	height:40px;
	display:block;
}
#findUser .findConetent{
	margin:0 auto;
	text-align:center;
	width:260px;
	height:86px;
	padding-left:15px;
	padding-top:18px;
	background:url(statics/web/image/findMishiContent.png) no-repeat;
}
#findUser .menu_tab{
	margin:0 auto;
	text-align:center;
	margin-top:20px;
	width:275px;
	height:35px;
}
</style>

<script src="<?php echo Sites_Config::$appurl ?>js/jquery.min.js" type="text/javascript"></script>

</head>
<body>

	<div id="main">

	<div id="content">
		<!--登陆面板-->
		<div id="login"  style="margin-top:120px;">

			<!--账号-->
			<div style="float:left;margin-top:270px;margin-left:320px;">
				<img id="zhT" style="margin-top:6px;" src="statics/web/image/zh.png" />
				<img id="zhL" style="margin-left:20px;" src="statics/web/image/textlineLeft.png" />
				<input tabIndex="1" id="account_username"  id="zh" type="text" value="<?php echo $username ? $username : ''?>" />
				<img id="zhR" style="margin-left:-1px;" src="statics/web/image/textlineRight.png" />
				
				<!--注册新用户按钮-->
				<a id="zhB" style="float:left;margin-left:10px;color:#fff;width:110px;height:33px;line-height:33px;" href="javascript:;" onmousemove="document.getElementById('zhB').style.textDecoration ='underline'" onmouseout="document.getElementById('zhB').style.textDecoration='none';" onmousedown="document.getElementById('zhB').style.textDecoration ='underline';"   onclick="return registerClick();">
					<!--<img id="zhB" style="margin:0;" src="statics/web/image/zc.png"/>-->免费注册
				</a> 
				<div style="clear:both;"></div>
			</div>
		    
			<!--密码-->
			<div style="float:left;margin-top:30px;margin-left:320px;">
				<img id="miMaT" style="margin-top:6px;" src="statics/web/image/mima.png" />
				<img id="miMaL" style="margin-left:16px;" src="statics/web/image/textlineLeft.png" />
				<input tabIndex="2" id="account_password" id="miMa" type="password" value="<?php echo $password ? $password : ''?>" />
				<img id="miMaR" style="margin-left:-1px;" src="statics/web/image/textlineRight.png" />
				
				<!--找回密码按钮-->
				<a id="miMaB" href="javascript:;" style="float:left;margin-left:10px;color:#fff;width:110px;height:33px;line-height:33px;" href="" onmousemove="document.getElementById('miMaB').style.textDecoration ='underline'" onmouseout="document.getElementById('miMaB').style.textDecoration='none';" onmousedown="document.getElementById('miMaB').style.textDecoration ='underline';" onclick="return findUserClick();">
					<!--<img id="miMaB" style="margin:0;" src="statics/web/image/find.png"/>-->找回密码
				</a> 
				<div style="clear:both;"></div>		
			</div>
		
			<a tabIndex="3" onclick="checkaccount();" style="float:left;margin-top:50px;margin-left:370px;" href="javascript:;" onmousemove="document.getElementById('start').src='statics/web/image/start_inout.png';" onmouseout="document.getElementById('start').src='statics/web/image/start.png';" onmousedown="document.getElementById('start').src='statics/web/image/start_click.png';"  >
				<img id="start" style="margin:0;width:247px;height:123px;" src="statics/web/image/start.png" />
			</a>
			
			<div style="clear:both;"></div>
		</div>
		
		<div>  
    		<form id="login_submit" > </form>  
		</div> 
		
		<div style="color: #FFFFFF;margin-top: 100px;">  
    		沪ICP备14025896号-3 
		</div>   
		
		<!--注册面板-->
		<div id="register" class="child_div" style="left:25%;top:25%;display:none;">
			<!--标题栏-->
			<div>
				<img  src="statics/web/image/barL.png"/>
				
				<!--标题-->
				<div style="float:left; background:url(statics/web/image/barBg.png);width:406px;height:24px;padding-top:9px;padding-bottom:8px;">
					<img src="statics/web/image/bar.png" style="padding-left:140px;"/>
				</div>
			    
				<!--关闭按钮-->
				<div style="float:left;background:url(statics/web/image/barR.png);height:41px;width:50px;">
					<img style="float:left;margin-top:8px;" src="statics/web/image/line.png"/>
					<img id="closeB" style="float:left;margin-top:12px;margin-left:15px" src="statics/web/image/close.png" onmousemove="document.getElementById('closeB').src='statics/web/image/close_inout.png';" onmouseout="document.getElementById('closeB').src='statics/web/image/close.png';" onmousedown="document.getElementById('closeB').src='statics/web/image/close_click.png';" onclick="return closeRegister();"/>
				</div>
				<div style="clear:both;"></div>
			</div>
		    
			<!--注册项-->
			<div style="width:506px;height:387px;">
				<img  src="statics/web/image/tbL.png"/>
				<div class="registerContent">
					
					<!--游戏账号-->
					<div style="margin-top:12px;">
						<div class="label" style="text-align:right;">游戏账号:<span style="color:red">*</span></div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input onblur="checkRegisterName(this)" id="username" class="textInput" type="text" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div id="username_msg" class="label" style="margin-left:10px;width:180px;">由4-15个大小写字母或数字组成</div>
						<div style="clear:both;"></div>
					</div>
					
					<!--游戏昵称-->
					<div style="margin-top:10px">
						<div class="label" style="text-align:right;">游戏昵称:<span style="color:red">*</span></div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input onblur="checkmnick(this)" id="mnick" class="textInput" type="text" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div id="mnick_msg" class="label" style="margin-left:10px;width:180px;">请输入您的昵称</div>
						<div style="clear:both;"></div>
					</div>
					
					<!--用户性别-->
					<div style="margin-top:10px">
						<div class="label" style="text-align:right;">用户性别:<span style="color:red">*</span></div>
					
						<div style="height:22px;float:left;">
							<label class="label" style="width:20px;margin-left:40px;">男</label> <input checked onclick="checksex(this)" style="height:18px;float:left;margin-left:5px;" name=sex type="radio" value="1" />
							<label class="label" style="width:20px;margin-left:25px;">女</label> <input onclick="checksex(this)" style="height:18px;float:left;margin-left:5px;" name="sex" type="radio" value="2" />
						</div>
						<div id="sex_msg" class="label" style="margin-left:30px;width:180px;"></div>
						<div style="clear:both;"></div>
					</div>
					
					<!--账号密码-->
					<div style="margin-top:10px">
						<div class="label" style="text-align:right;">账号密码:<span style="color:red">*</span></div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input onblur="checkPWD(this)" id="password" class="textInput" type="password" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div id="password_msg"  class="label" style="margin-left:10px;width:180px;">由6-12个字母，数字或符号组成</div>
						<div style="clear:both;"></div>
					</div>
					
					<!--密码强度-->
					<!-- 
					<div style="margin-top:10px">
						<div class="label" style="text-align:right;">密码强度:</div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input class="textInput" type="text" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div style="clear:both;"></div>
					</div>
					-->
					<!--确认密码-->
					<div style="margin-top:10px">
						<div class="label" style="text-align:right;">确认密码:<span style="color:red">*</span></div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input onblur="recheckPWD(this)"  class="textInput" type="password" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div id="repassword_msg" class="label" style="margin-left:10px;width:180px;">请输入确认密码</div>
						<div style="clear:both;"></div>
					</div>
					
					<!--安全密匙-->
					<div style="margin-top:10px">
						<div class="label" style="text-align:right;">安全密钥:<span style="color:red">*</span></div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input id="secretkey" onblur="checkkey(this)" class="textInput" type="text" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div id="secretkey_msg"  class="label" style="margin-left:10px;width:180px;">谨记，找回登陆密码的主要依据</div>
						<div style="clear:both;"></div>
					</div>
					
					<!--验证码-->
					<div style="margin-top:10px">
						<div class="label" style="text-align:right;">验证码:<span style="color:red">*</span></div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input onkeyup="checkidcode(this)" id="idcode" class="textInput" type="text" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div class="label" style="margin-left:10px;width:180px;"><img id="code" src="?act=idcode" alt="看不清楚，换一张" style="cursor: pointer; vertical-align:middle;" onclick="this.src= document.location.protocol +'?act=idcode&v='+new Date().getTime()" /> <span id="idcode_msg"></span><a onclick="reCode();"  href="javascript:void(0)">换一张</a></div>
						<div style="clear:both;"></div>
					</div>
					
					<!--条款-->
					<div style="margin-top:10px">
						<input onclick="checkIagree()" id="iagree" checked type="checkbox" style="width:70px;height:17px;float:left;margin-left:40px;"/>
						<div class="label" style="width:180px;">我已阅读并同意<a target="_blank" href="?act=termsofservice">《服务协议》</a></div>
						<div style="clear:both;"></div>
					</div>
					
					<!--提交注册-->
					<div style="margin-top:20px;">
						<a id="submit" onclick="return checkinput()" href="javascript:;" onmousemove="document.getElementById('registerCommit').src='statics/web/image/register_inout.png';" onmouseout="document.getElementById('registerCommit').src='statics/web/image/register.png';" onmousedown="document.getElementById('registerCommit').src='statics/web/image/register_click.png';">
							<img id="registerCommit" src="statics/web/image/register.png" style="float:none;width:123px;height:40px;"/>
						</a>
					</div>
				</div>
				
				<img  src="statics/web/image/tbR.png"/>
				<div style="clear:both;"></div>
			
			</div>
		</div>
	    
		
		
		<!--账号找回-->
		<div id="findUser" class="child_div" style="left:30%;top:30%;display:none;width:416px;height:289px;background:url(statics/web/image/findUserBg.png) no-repeat;">
			<!--标题栏-->
			<div style="width:416px;height:41px;">
				<!--标题-->
				<img src="statics/web/image/findUserTitle.png" style="width:100px;padding-left:150px;padding-top:9px;"/>
				<!--关闭按钮-->
				<div style="float:right;background:url(statics/web/image/barR.png);height:41px;width:50px;">
					<img style="margin-top:8px;" src="statics/web/image/line.png"/>
					<img style="margin-top:12px;margin-left:15px" src="statics/web/image/close.png" onmousemove="this.src='statics/web/image/close_inout.png';" onmouseout="this.src='statics/web/image/close.png';" onmousedown="this.src='statics/web/image/close_click.png';" onclick="return closeFindUser();"/>
				</div>
				<div style="clear:both;"></div>
			</div>
			
			<div class="menu_tab">
				<img src="statics/web/image/findMishi.png" style="cursor:pointer" onclick="return changeTab(1);" onmousemove="this.src='statics/web/image/findMishi_inout.png';" onmouseout="this.src='statics/web/image/findMishi.png';" onmousedown="this.src='statics/web/image/findMishi_click.png';"/>
				<img src="statics/web/image/findPhone.png" style="float:right;cursor:pointer" onclick="return changeTab(2);" onmousemove="this.src='statics/web/image/findPhone_inout.png';" onmouseout="this.src='statics/web/image/findPhone.png';" onmousedown="this.src='statics/web/image/findPhone_click.png';"/>
				<div style="clear:both;"></div>
			</div>
			<!--安全码找回面板-->
			<div id="tab_1" class="findConetent">
					<!--输入账号-->
					<div style="margin-top:10px">
						<div class="label" style="width:75px;text-align:right;">输入账号:</div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input id="account" class="textInput" type="text" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div style="clear:both;"></div>
					</div>
					
					<!--输入按钮码-->
					<div style="margin-top:10px">
						<div class="label" style="width:75px;text-align:right;">输入安全码:</div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input id="secretkey_fine" class="textInput" type="text" value=""/>
						<img  src="statics/web/image/r.png"/>
						<div style="clear:both;"></div>
					</div>
				
			</div>
			
			<!--手机号找回面板-->
			<div id="tab_2" class="findConetent" style="display:none;background:url(statics/web/image/findPhoneContent.png) no-repeat;">
					<!--手机号-->
					<div style="margin-top:25px">
						<div class="label" style="width:75px;text-align:right;">填写手机号:</div>
						<img style="margin-left:10px;" src="statics/web/image/l.png"/>
						<input id="phone" class="textInput" type="text" value=""/>
						<img src="statics/web/image/r.png"/>
						<div style="clear:both;"></div>
					</div>
			</div>
			
			<!--安全码找回提交按钮-->
			<a onclick="checkSecretkeyCommit()" id="commit_1" href="javascript:;">
					<img id="findUserCommit_1" src="statics/web/image/findUserCommit.png" />
			</a>
			
			<!--手机号找回提交按钮-->
			<a onclick="checkPhoneommit()" id="commit_2" href="javascript:;" style="display:none;">
					<img id="findUserCommit_2" src="statics/web/image/findUserCommit.png"/>
			</a>
		</div>

	</div>
</div>	
	
<script type="text/javascript">

var now_tab = 1;
function changeTab(tab){
    var temp = tab;
	var m = document.getElementById("tab_"+temp);
	var n = document.getElementById("tab_"+now_tab);
	n.style.display = 'none';
	m.style.display = 'block';
	
	m = document.getElementById("commit_"+temp);
	n = document.getElementById("commit_"+now_tab);
	n.style.display = 'none';
	m.style.display = 'block';
	
	now_tab = temp;
}

function checkSecretkeyCommit(){
	var account = $('#account').val();
	var scekey  = $('#secretkey_fine').val();

	if(!account ){
		alert("请输入账号名");
		return false;
	}

	if(!scekey){
		alert("请输入安全密匙");
		return false;
	}

	$.ajax(
			{
				type: "POST",
				url: "?act=getPassword", 
				data:{ type: 1,username:account,secretkey:scekey },
				success: function(result){
					if(result == '-1'){
						alert("用户名或密匙为空！");
					}else if(result == '-2'){
						alert("你输入的用户名或密匙有误，请重新输入！");
					}else{
						alert(result);
					}
				}
			}
	);
	
}

function checkPhoneommit(){
	var phone = $('#phone').val();

	if(!phone ){
		alert("请输入手机号");
		return false;
	}

	$.ajax(
			{
				type: "POST",
				url: "?act=getPassword", 
				data:{ type: 2,phoneno:phone },
				success: function(result){
					if(result == '-3'){
						alert("手机号码格式不对！");
					}else if(result == '-4'){
						alert("超过限制次数！温馨提示：每天只能通过手机号码获取1次！");
					}else if(result == '-5'){
						alert("您还没绑定手机，请选择安全密匙获取！");
					}else{
						alert("获取成功，请留意短信！");
					}
				}
			}
	);
	
}

function checkaccount(){
	var username = $("#account_username").val();
	var password = $("#account_password").val();
	if(!username){
		alert("请输入账号名！");
		var content   = document.getElementById("account_username");
  		content.focus();
  		return false;
	}

	if(!password){
		alert("请输入密码！");
		var content   = document.getElementById("account_password");
  		content.focus();
  		return false;
	}

	$.ajax(
			{
				type: "POST",
				url: "?act=checkAccount", 
				data:{ username: username,password:password },
				success: function(result){
					if(result == '-2'){
						alert("用户名或密码错误！");
					  	return false;
					}else if(result == '-3'){
						alert("用户不在在！");
					  	return false;
					}
				}
			}
	);
	
	formSubmit("login_submit","?p=game","post",[{paramName:'username',paramValue:username},{paramName:'password',paramValue:password}]);
}

function formSubmit(formid,url,method,params){  
    var dynamicForm = document.getElementById(formid);  
    dynamicForm.setAttribute("method",method);  
    dynamicForm.setAttribute("action",url);  
    dynamicForm.innerHTML = "";  
    for(var i=0;i<params.length;i++){  
        var input = document.createElement("input");  
        input.setAttribute("type","hidden");  
        input.setAttribute("name",params[i].paramName);  
        input.setAttribute("value",params[i].paramValue);  
        dynamicForm.appendChild(input);  
    }  
    dynamicForm.submit();  
}  

function reCode(){
	$('#code').attr('src', '?act=idcode&v=' + new Date().getTime());
}

var username = "";
var mnick    = "";	
var sex      = "";
var password = "";
var secretkey = "";
var idcode   = "";
var iagree   = "";
var repassword = "";

function showError(id,msg,flag){
	if(flag == 1){
		$("#"+id).html("<img src='statics/web/image/cg.png'>");
	}else{
		$("#"+id).html("<img src='statics/web/image/sb.png'>"+msg);
		$("#"+id).css("color","red");
	}
}

function checkRegisterName(obj){
	var val = obj.value;
	if(val.length > 15 || val.length <4){
		showError("username_msg","4-15个字母或数字组成",0);
		return false;
	}
	$.ajax(
			{
				type: "POST",
				url: "?act=checkUserName", 
				data:{ username: val },
				success: function(result){
					if(result == '-1'){
						showError("username_msg","用户名不合法",0);
					  	return false;
					}else if(result == '-2'){
						showError("username_msg","用户名已被注册，请重新输入",0);
					  	return false;
					}
				}
			}
	);

	username = val;
	showError("username_msg"," ",1);
	return true;
}

function checkmnick(obj){
	var val = obj.value;
	if(val.length > 20 || val.length <3){
		showError("mnick_msg","3-20个字母或数字组成",0);
		return false;
	}else{
		mnick = val;
		showError("mnick_msg"," ",1);
		return true;
	}
}

var sex = 1;
function checksex(obj){
	var val = obj.value;
	if(!val){
		showError("sex_msg","请选择性别",0);
	}else{
		sex = val;
		showError("sex_msg"," ",1);
		return true;
	}
}

function checkPWD(obj){
	var val = obj.value;
	if(val.length > 12 || val.length <6){
		showError("password_msg","6-12位数字或字母组成",0);
		return false;
	}else{
		password = val;
		showError("password_msg"," ",1);
		return true;
	}
}

function recheckPWD(obj){
	repassword = obj.value;
	if(repassword != password || repassword == ''){
		showError("repassword_msg","密码与之前输入的不同",0);
		return false;
	}else{
		showError("repassword_msg"," ",1);
		return true;
	}
}

function checkkey(obj){
	var val = obj.value;
	if(!val){
		showError("secretkey_msg","请输入密匙",0);
	}else{
		secretkey = val;
		showError("secretkey_msg"," ",1);
	}
}

function checkidcode(obj){
	var val = obj.value;
	if(!val){
		showError("idcode_msg","请输验证码",0);
		return false;
	}

	if(val.length == 4){
		$.ajax({
			type: "POST",
			url: "?act=checkIdcode", 
			data:{ idcode: val },
			success: function(result){
				if(result == 0){
					showError("idcode_msg","验证码错误",0);
			  		var content=document.getElementById("idcode");
			  		//content.value = idcode = "";
				  	content.focus();
				  	return false;
				}
			}
		}
		);
		idcode = val;
		showError("idcode_msg"," ",1);
	}
}

function checkIagree(){
	if($('#iagree').attr('checked') != true){
		alert('请阅读并同意<<服务协议>>');
		iagree = 0;
	}else{
		iagree = 1;
	}
}

function checkinput(){
	if(!username){
		showError("username_msg","4-15个字母或数字组成",0);
	}

	if(!mnick){
		showError("mnick_msg","3-20个字母或数字组成",0);
	}

	if(!sex){
		showError("sex_msg","请选择性别",0);
	}

	if(!password){
		showError("password_msg","6-12位数字或字母组成",0);
	}

	if(password != repassword){
		showError("repassword_msg","密码与之前输入的不同",0);
		return false;
	}

	if(!secretkey){
		showError("secretkey_msg","请输入密匙",0);
	}

	if(!idcode){
		showError("idcode_msg","请输验证码",0);
	}

	if( username && mnick && sex && password && secretkey && idcode ){
		ajax2register();
	}
}

$.ajaxStart(function(){
	$("#submit").attr("disabled","disabled");
}).ajaxStop(function(){
	$("#submit").removeAttr("disabled");
});

function ajax2register(){
	$.ajax(
			{
				type: "POST",
				url: "?act=register", 
				data:{ username: username, mnick: mnick,sex:sex,password:password,secretkey:secretkey,idcode:idcode },
				//data: "username="+username+"mnick="+mnick+"sex="+sex+"password="+password+"secretkey="+secretkey+"idcode="+idcode,
				success: function(result){
					  	switch(result){
					  		case '-2':
					  			showError("username_msg","用户名不合法",0);
					  			var content   = document.getElementById("username");
						  		content.value = username= "";
						  		content.focus();
						  		break;
					  		case '-3':
					  			showError("secretkey_msg","请重新输入密匙",0);
					  			var content   = document.getElementById("secretkey");
					  			content.value = secretkey = "";
						  		content.focus();
						  		break;	
					  		case '-5':
					  			showError("username_msg","用户名已被注册，请重输",0);
					  			var content=document.getElementById("username");
					  			content.value = username = "";
						  		content.focus();
						  		break;
					  		case '-4':
					  			showError("idcode_msg","验证码错误",0);
					  			var content=document.getElementById("idcode");
					  			content.value = idcode = "";
						  		content.focus();
						  		break;
					  		case '-6':
					  			showError("username_msg","注册账号超过当天限制，暂时不能注册",0);
					  			var content   = document.getElementById("username");
						  		content.value = username= "";
						  		content.focus();
						  		break;	
					  		case '0':
					  			alert("注册失败，请重试");
						  		break;	
					  		case '1':
					  			//alert("恭喜！注册成功，按确定后登陆!");
					  			closeRegister();
					  			var username = $("#username").val();
					  			var password = $("#password").val();
					  			formSubmit("login_submit","?p=game","post",[{paramName:'username',paramValue:username},{paramName:'password',paramValue:password}]);
						  		break;					
					  	}
				}
			}
	);
}

function findUserClick(){
	document.getElementById('findUser').style.display='block';
	document.getElementById('login').style.display='none';
}
function closeFindUser(){
	document.getElementById('findUser').style.display='none';
	document.getElementById('login').style.display='block';
}	
function registerClick(){
	document.getElementById('register').style.display='block';
	document.getElementById('login').style.display='none';
}
function closeRegister(){
	document.getElementById('register').style.display='none';
	document.getElementById('login').style.display='block';
}

</script>
</body>
</html>