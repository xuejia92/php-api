<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta content="海南斗地主" name="keywords">
<meta content="海南斗地主，专注于为海南人民开发的斗地主" name="description">
<title>海南斗地主</title>
<link rel="shortcut icon" href="dianerler.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="./static/common.css">
<style>
*{
	padding:0;
	margin:0;
 }
img,a,input{
	border:none;
}
input:focus{outline:none;}
body{
	/*background-color:#f3c97d;*/
	background:url('statics/landlord_hainan/login_headerBg.jpg?2=2') repeat-x center top #f3c97d;
} 

.content{
	width:1000px;
	/*background-color:red;*/
	margin:0 auto;
	/*padding-top:25px;*/
	
}


.inputWap{
	position:relative;margin:0 auto;width:310px;height:34px;padding-left:10px;padding-top:6px;background:url('statics/landlord_hainan/inputBg1.png') no-repeat;
}

.inputWap input{
	width:300px;height:30px;font-size:15px;color:#6d6d6d;
}

.textspan{
	float:right;margin-top:10px;font-size:17px;
}

.inputWap img{
	position:absolute;left:285px;top:7px;
}

.inputReWap{
	position:relative;margin:0 auto;width:348px;height:34px;padding-left:10px;padding-top:6px;background:url('statics/landlord_hainan/inputBg2.png') no-repeat;
}

.inputReWap input{
	width:300px;height:25px;font-size:15px;color:#6d6d6d;
}

.inputReWap img{
	position:absolute;left:315px;top:7px;
}

.inputReWap div{
	position:absolute;left:350px;top:0px;
}

.inputYanzhengWap{
	position:relative;margin:0 auto;width:148px;height:34px;padding-left:10px;padding-top:6px;background:url('statics/landlord_hainan/inputBg2.png') no-repeat;
}

.inputYanzhengWap input{
	width:100px;height:25px;font-size:15px;color:#6d6d6d;
}

.inputYanzhengWap span{
	position:absolute;left:352px;top:0px;
}

.loginTab{
	margin:0 auto;width:570px;height:245px;padding-top:181px;background:url('statics/landlord_hainan/login_wap.png?2=2') no-repeat;
}
.regTab{
	margin:0 auto;width:572px;height:477px;padding-top:20px;background:url('statics/landlord_hainan/regiser_wap.png?2=2') no-repeat;
}
.findTab{
	margin:0 auto;width:508px;height:337px;padding-top:20px;background:url('statics/landlord_hainan/findback_wap.png?2=2') no-repeat;
}

</style>
<script src="statics/landlord_hainan/jquery.min.js" type="text/javascript"></script>
</head>
<body>
<!--
<div class="logo" >
	<a href="index.html"><img src="statics/landlord_hainan/logo.png"></a>
	<div class="nav" >
		<ul>
			<li  style="border-right:1px solid #55a43b;"><a class="onChoose" href="index.html">首页</a></li>
			<li style="border-right:1px solid #55a43b;"><a href="games.html">游戏</a></li>
			<li style="border-right:1px solid #55a43b;"><a href="business.html">商务</a></li>
			<li style="border-right:1px solid #55a43b;"><a href="recruitment.html">招聘</a></li>
			<li ><a href="about.html">关于点乐</a></li>
			<li class="clear"></li>
		</ul>
	</div>
</div>
-->


<div  class="content" style="margin-top:200px;">

	<div id="table_0" class="loginTab">
		<div style="margin:0 auto;width:400px;">
			<div class="inputWap" style="float:left;">
				<input id="account_username"   placeholder="输入游戏账号" value="<?php echo $username ? $username : ''?>"  />
				<span id="account_username_img"></span>
				<div style="left: 300px;position: relative;top: 1px;" id="account_username_msg"></div>
			</div>
			<span class="textspan"><a href="javascript:;" onclick="openTab(1);" style="color:#fff;">立即注册</a></span>
			<div style="clear:both;"></div>
		</div>
		<div style="margin:0 auto;width:400px;margin-top:15px;">
			<div class="inputWap" style="float:left;">
				<input type='password' id="account_password"  placeholder="请输入密码" value="<?php echo $password ? $password : ''?>" />
				<span id="account_password_img"></span>
				<div   id="account_password_msg"></div>
			</div>
			<span class="textspan"><a href="javascript:;" onclick="openTab(2);" style="color:#fff;">找回密码</a></span>
			<div style="clear:both;"></div>
		</div>
		<a onclick="checkaccount();" href="#@" style="margin:0 auto;width:229px;height:81px;display:block;margin-top:30px;">
			<img src='statics/landlord_hainan/loginBtn_out.png?2=2'/>
		</a>
		<div>  
    		<form id="login_submit" > </form>  
		</div> 
	</div>
	
	<div id="table_1" class="regTab" style="display:none;">
		<div style="margin:0 auto;width:522px;height:30px;">
			<a href="javascript:;" onclick="closeTab();" style="float:right;">
				<img src='statics/landlord_hainan/close_out.png?2=2'/>
			</a>
		</div>
		
		<div class="inputReWap" style="margin-top:30px;">
			<input id="username"  type="text" onblur="checkRegisterName(this)" placeholder="请用手机号、QQ号、邮箱注册"  />
			<span id="username_img"></span>
			<div id="username_msg"></div>
		</div>
		
		<!-- 
		<div class="inputReWap" style="margin-top:10px;">
			<input id="mnick" onblur="checkmnick(this)"  type="text" placeholder="请创建您的游戏昵称" />
			<span id="mnick_img"></span>
			<div id="mnick_msg"></div>
		</div>
		 -->	
		<div class="inputReWap" style="margin-top:10px;">
			<input id="password" onblur="checkPWD(this)"  type="text" placeholder="请创建您的游戏密码(6-12个字母或符合组成)"/>
			<span id="password_img"></span>
			<div id="password_msg"></div>
		</div>
		
		<div class="inputReWap" style="margin-top:10px;">
			<input id="repassword"  onblur="recheckPWD(this)" type="text" placeholder="请再次输入账号密码"/>
			<span id="repassword_img"></span>
			<div id="repassword_msg"></div>
		</div>
		
		<div class="inputReWap" style="margin-top:10px;">
			<input id="secretkey" onblur="checkkey(this)"  type="text" placeholder="安全密匙(找回登陆密码的主要依据)"/>
			<span id="secretkey_img"></span>
			<div id="secretkey_msg"></div>
		</div>
		
		<div style="margin:0 auto;margin-top:10px;width:360px;">
			<div class="inputYanzhengWap" style="float:left;">
				<input onkeyup="checkidcode(this)" id="idcode"  type="text" placeholder="验证码"/>
				<span id="idcode_img"></span>
				<div id="idcode_msg"></div>
			</div>
			<div style="float:left;margin-left:5px;margin-top:3px;color:#fff;">
				<img id="code" src="?act=idcode" alt="看不清楚，换一张" style="cursor: pointer; vertical-align:middle;" onclick="this.src= document.location.protocol +'?act=idcode&v='+new Date().getTime()" /> 
				
				<a onclick="reCode();"  href="javascript:void(0)">换一张</a>
				</div>
			
			<div style="clear:both;"></div>
		</div>
		
		<div style="color:#fff;width:338px;margin:0 auto;font-size:15px;margin-top:10px;">
			<span style="float:left;">点击注册按钮意味着您已阅读并同意</span>
			<a href="#@" style="float:left;color:#ffdd56;">《服务协议》</a>
			<div style="clear:both;"></div>
		</div>
	
		<a id="submit" onclick="return checkinput()" href="#@" style="margin:0 auto;width:173px;height:64px;display:block;margin-top:15px;">
			<img src='statics/landlord_hainan/regiserBtn_out.png?2=2'/>
		</a>
	</div>
	
	
	<div id="table_2" class="findTab" style="display:none;">
		<div style="margin:0 auto;width:458px;height:30px;">
			<a href="javascript:;" onclick="closeTab();" style="float:right;">
				<img src='statics/landlord_hainan/close_out.png?2=2'/>
			</a>
		</div>
		<div style="margin:0 auto;width:333px;height:55px;margin-top:25px;">
			<a  href="javascript:;" onclick="changeFindTab(0);" style="float:left;">
				<img id="tabImg0" src='statics/landlord_hainan/mishi_on.png?2=2'/>
			</a>
			<a href="javascript:;" onclick="changeFindTab(1);" style="float:right;">
				<img id="tabImg1" src='statics/landlord_hainan/shouji_out.png?2=2'/>
			</a>
			<div style="clear:both;"></div>
		</div>
		
		<div id="findtab_0">
			<div style="margin:0 auto;width:333px;height:107px;padding-top:28px;background:url('statics/landlord_hainan/findTab1.png?2=2') no-repeat;">
				<div class="inputWap">
					<input id="account"  placeholder="输入账号" />
					<!--  <img src="statics/landlord_hainan/errorBg.png"/> -->
				</div>
				
				<div class="inputWap" style="margin-top:10px;">
					<input id="secretkey_fine"  placeholder="输入安全码" />
					<!-- <img src="statics/landlord_hainan/errorBg.png"/> -->
				</div>
			</div>
		
			<a onclick="checkSecretkeyCommit()" id="commit_1" href="#@" style="margin:0 auto;width:163px;height:61px;display:block;margin-top:15px;">
				<img src='statics/landlord_hainan/find_submit_out.png?2=2'/>
			</a>
		</div>
		
		<div id="findtab_1" style="display:none">
			<div style="margin:0 auto;width:333px;height:107px;padding-top:28px;background:url('statics/landlord_hainan/findTab1.png?2=2') no-repeat;">
				<!--  
				<div class="inputWap">
					<input  value="输入账号" />
					<img src="statics/landlord_hainan/errorBg.png"/>
				</div>
				-->
				<div class="inputWap" style="margin-top:10px;">
					<input id="phone"  placeholder="输入手机号" />
					<!--  <img src="statics/landlord_hainan/errorBg.png"/>  -->
				</div>
			</div>
		
			<a onclick="checkPhoneommit()" id="commit_2"  href="#@" style="margin:0 auto;width:163px;height:61px;display:block;margin-top:15px;">
				<img src='statics/landlord_hainan/find_submit_out.png?2=2'/>
			</a>
		</div>
	</div>
</div>

<script style="text/javascript">
	var nowTab = 0;
	
	function openTab(tabId){
		for(var i=0;i<3;i++){
			document.getElementById("table_"+i).style.display = "none";
			//document.getElementById("btn"+i).className = "Btnout";

		}
		//document.getElementById("btn"+tabId).className = "Btnon";
		document.getElementById("table_"+tabId).style.display = "block";

		nowTab = tabId;
	}
	
	function closeTab(){
		for(var i=0;i<3;i++){
			document.getElementById("table_"+i).style.display = "none";
		}
		document.getElementById("table_"+0).style.display = "block";
		nowTab = 0;
	}
	
	var nowfindTab = 0;
	function changeFindTab(findtabId){
		document.getElementById("findtab_"+nowfindTab).style.display = "none";
		document.getElementById("findtab_"+findtabId).style.display = "block";
		var imgShow = "";
		var imgHide = "";
		if(findtabId == 0){
			imgShow = 'statics/landlord_hainan/mishi_on.png';
			imgHide = 'statics/landlord_hainan/shouji_out.png';
		}else{
			imgShow = 'statics/landlord_hainan/shouji_on.png';
			imgHide = 'statics/landlord_hainan/mishi_out.png';
		}
		
		document.getElementById("tabImg"+nowfindTab).src = imgHide;
		document.getElementById("tabImg"+findtabId).src = imgShow;
		
		nowfindTab = findtabId;
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
			showError("account_username","请输入账号名！",0);
			var content   = document.getElementById("account_username");
	  		content.focus();
	  		return false;
		}

		if(!password){
			showError("account_username","请输入密码！",0);
			var content   = document.getElementById("account_password");
	  		content.focus();
	  		return false;
		}

		$("#username_img").html("");
		$("#username_msg").css("display","none");//清除之前的错误提示

		$.ajax(
				{
					type: "POST",
					url: "?act=checkAccount", 
					data:{ username: username,password:password },
					success: function(result){
						if(result == '-2'){
							showError("account_username","用户名或密码错误！",0);
						  	return false;
						}else if(result == '-3'){
							showError("account_username","用户不在在！",0);
						  	return false;
						}

						formSubmit("login_submit","?p=game","post",[{paramName:'username',paramValue:username},{paramName:'password',paramValue:password}]);
					}
				}
		);
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
			$("#"+id+"_img").html("<img src='statics/landlord_hainan/rightBg.png'/>");
			$("#"+id+"_msg").html(msg);
		}else{
			$("#"+id+"_img").html("<img src='statics/landlord_hainan/errorBg.png'>");
			$("#"+id+"_msg").css("display","block");
			$("#"+id+"_msg").css("color","red");
			$("#"+id+"_msg").html(msg);
			$("#"+id+"_msg").css("color","red");
			$("#"+id+"_msg").css("font-size","14px");
			$("#"+id+"_msg").css("background","url('statics/landlord_hainan/error_msg.png') no-repeat scroll 0 0 rgba(0, 0, 0, 0)");
			$("#"+id+"_msg").css("height","34px");
			$("#"+id+"_msg").css("line-height","34px");
			$("#"+id+"_msg").css("padding","0 0px 0 20px");
			$("#"+id+"_msg").css("margin-top","0px");
			$("#"+id+"_msg").css("width","210px");
		}
	}

	function checkRegisterName(obj){
		var val = obj.value;
		if(val.length > 25 || val.length <4){
			showError("username","4-25个字母或数字组成",0);
			return false;
		}
		$("#username_img").html("");
		$("#username_msg").css("display","none");//清除之前的错误提示
		$.ajax(
				{
					type: "POST",
					url: "?act=checkUserName", 
					data:{ username: val },
					success: function(result){
						if(result == '-1'){
							showError("username","用户名不合法",0);
						  	return false;
						}else if(result == '-2'){
							showError("username","用户名已被注册，请重新输入",0);
						  	return false;
						}

						if(result == '1'){
							username = val;
							showError("username","",1);
						}
					}
				}
		);
		return true;
	}

	function checkmnick(obj){
		$("#mnick_img").html("");
		$("#mnick_msg").css("display","none");//清除之前的错误提示
		var val = obj.value;
		if(val.length > 20 || val.length <3){
			showError("mnick","3-20个字母或数字组成",0);
			return false;
		}else{
			mnick = val;
			showError("mnick"," ",1);
			return true;
		}
	}


	function checkPWD(obj){
		$("#password_img").html("");
		$("#password_msg").css("display","none");//清除之前的错误提示
		var val = obj.value;
		if(val.length > 12 || val.length <6){
			showError("password","6-12位数字或字母组成",0);
			return false;
		}else{
			password = val;
			showError("password"," ",1);
			return true;
		}
	}

	function recheckPWD(obj){
		$("#repassword_img").html("");
		$("#repassword_msg").css("display","none");//清除之前的错误提示
		repassword = obj.value;
		if(repassword != password || repassword == ''){
			showError("repassword","密码与之前输入的不同",0);
			return false;
		}else{
			showError("repassword"," ",1);
			return true;
		}
	}

	function checkkey(obj){
		$("#secretkey_img").html("");
		$("#secretkey_msg").css("display","none");//清除之前的错误提示
		var val = obj.value;
		if(!val){
			showError("secretkey","请输入密匙",0);
		}else{
			secretkey = val;
			showError("secretkey"," ",1);
		}
	}

	function checkidcode(obj){
		$("#idcode_img").html("");
		$("#idcode_msg").css("display","none");//清除之前的错误提示
		var val = obj.value;
		if(!val){
			showError("idcode","请输验证码",0);
			return false;
		}

		if(val.length == 4){
			$.ajax({
				type: "POST",
				url: "?act=checkIdcode", 
				data:{ idcode: val },
				success: function(result){
					if(result == 0){
						showError("idcode","验证码错误",0);
				  		var content=document.getElementById("idcode");
				  		//content.value = idcode = "";
					  	content.focus();
					  	return false;
					}else{
						idcode = val;
						showError("idcode"," ",1);
					}
				}
			}
			);
			
		}
	}


	function checkinput(){

		if(!username){
			showError("username","4-15个字母或数字组成",0);
			return false;
		}

		if(!password){
			showError("password","6-12位数字或字母组成",0);
			return false;
		}

		if(password != repassword){
			showError("repassword","密码与之前输入的不同",0);
			return false;
		}

		if(!secretkey){
			showError("secretkey","请输入密匙",0);
			return false;
		}

		if(!idcode){
			showError("idcode","请输验证码",0);
			return false;
		}

		if( username  && password && secretkey && idcode ){
			ajax2register();
		}
	}


	function ajax2register(){
		$.ajax(
				{
					type: "POST",
					url: "?act=register", 
					data:{ username: username, mnick: mnick,password:password,secretkey:secretkey,idcode:idcode },
					//data: "username="+username+"mnick="+mnick+"sex="+sex+"password="+password+"secretkey="+secretkey+"idcode="+idcode,
					success: function(result){
						  	switch(result){
						  		case '-2':
						  			showError("username","用户名不合法",0);
						  			var content   = document.getElementById("username");
							  		content.value = username= "";
							  		content.focus();
							  		break;
						  		case '-3':
						  			showError("secretkey","请重新输入密匙",0);
						  			var content   = document.getElementById("secretkey");
						  			content.value = secretkey = "";
							  		content.focus();
							  		break;	
						  		case '-5':
						  			showError("username","用户名已被注册，请重输",0);
						  			var content=document.getElementById("username");
						  			content.value = username = "";
							  		content.focus();
							  		break;
						  		case '-4':
						  			showError("idcode","验证码错误",0);
						  			var content=document.getElementById("idcode");
						  			content.value = idcode = "";
							  		content.focus();
							  		break;
						  		case '-6':
						  			showError("username","注册账号超过当天限制，暂时不能注册",0);
						  			var content   = document.getElementById("username");
							  		content.value = username= "";
							  		content.focus();
							  		break;	
						  		case '0':
						  			alert("注册失败，请重试");
							  		break;	
						  		case '1':
						  			//alert("恭喜！注册成功，按确定后登陆!");
						  			closeTab();
						  			var username = $("#username").val();
						  			var password = $("#password").val();
						  			formSubmit("login_submit","?p=game","post",[{paramName:'username',paramValue:username},{paramName:'password',paramValue:password}]);
							  		break;					
						  	}
					}
				}
		);
	}

	$(document).ready(function () {
       
        var doc = document;
        var inputs = doc.getElementsByTagName('input')
        var supportPlaceholder = 'placeholder' in doc.createElement('input');
        var placeholder = function (input) {
	        var text = input.getAttribute('placeholder'), defaultValue = input.defaultValue;
	        if (defaultValue == '') {
	            input.value = text
	            input.style.color = "#666";
	        }
            input.onfocus = function () {
                if (input.value === text) { this.value = ''; this.style.color = "#666"; }
                else {
                    input.style.color = "#000";
                }
            };
            input.onblur = function () {
                if (input.value === '') { this.value = text; this.style.color = "#666"; } else {
                    this.style.color = "#000";
                }
            };
            input.onkeyup = function () {
                if (input.value !== "" && input.value !== text)
                { this.style.color = "#000"; }
                else {
                    this.style.color = "#666";
                }
            }
        };
        if (!supportPlaceholder) {
            for (var i = 0, len = inputs.length; i < len; i++) {
                var input = inputs[i];
                var text = input.getAttribute('placeholder');
                if ((input.type === 'text') && text) { placeholder(input) }
            }
        }
     
    });
	
</script>
</body>
</html>
