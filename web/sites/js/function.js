
function closeDown(){
	document.getElementById("Downdiv").style.display="none";
}

//显示帮助主页面
function show_feedback_view(){
	 var url = GAME.urlRoot;
	 var mid = GAME.userInfo.mid;
	 var mnick = encodeURI(GAME.userInfo.mnick);

	 jQuery('#show_div').load( url+'?p=feedback&mid='+mid+'&mnick='+mnick+'&act=view', function(){
	    	jQuery('#show_div').show();
	  });
}

//显示上传图片
function show_uploadicon_view(){
	 var url = GAME.urlRoot;
	 var mid = GAME.userInfo.mid;

	 jQuery('#show_div').load( url+'?p=upload&mid='+mid+'&act=uploadiconview', function(){
	    	jQuery('#show_div').show();
	  });
}
		

/**
 * 加载时初始化一些信息
 */
function loadInit(){
	Weibo_api.init();
	
	$F("init",[],'stat');	//统计
	if(GAME.userInfo.identity_status != 2)
	{
		var int = setInterval("getEnthralment()", 1000*60*60);
	}
	//var FriendList = setInterval("getFriendList(1)", 1000*60*60);
	
}

/**
 * 发weibo调用方法
 */
function publish(params)
{	
	//debug('publish', params);
    if(typeof(WYX)=='undefined')
	{
	    setTimeout("publish(params)", 3000);
		return;
	}
	
	if(typeof(params.cb) != 'function')
	{
	    //params.cb = stat;    
	}
	Weibo_api.publish(params);
}

/**
 * 发邀请 调用方法
 */
function invite(params)
{	
    if(typeof(WYX)=='undefined')
	{
	    setTimeout("publish(params)", 3000);
		return;
	}
	
	if(typeof(params.cb) != 'function')
	{
	    //params.cb = stat;    
	}
	Weibo_api.invite(params);
}

/**
 * 批量邀请窗口
 */
function inviteMulti(){
	//showPage('friend','invite');
	$('#inviteMulti').css("display","block");
	$("#frame").attr("src", GAME.scripturl + '?m=friend&p=invite');
}

/**
 * 关闭邀请窗口
 */
function close(){
	$('#inviteMulti').css("display","none");
}

/**
 * 公告窗口
 */
function noticeMulti(){
	$.ajax({
		type: "POST",
		dataType: "html",
		url: GAME.scripturl+"?m=notice&p=content",
		data: "mid=" + GAME.userInfo.mid,
		error : function(html){
			$('#noticeMulti').hide();
		},
		success: function(html){
			$('#noticeMulti').html(html);
			$('#noticeMulti').show();
	   }
	});
}

/**
 * 关闭公告窗口
 */
function noticeclose(){
	$('#noticeMulti').hide();
}

function showEnthralment(){
	hideLoading();
	$.ajax({
		type: "POST",
		dataType: "html",
		url: GAME.scripturl+"?m=enthralment&p=index",
		data: "",
		success: function(html){
			
			$('#divEnthralment').html(html);
			$('#divEnthralment').show();
	   }
	});
}
/**
 * 设置成就
 */

function setAchievement(param){
	var OldScore = param.oldscore;
	var NewScore = param.newscore;
	/*暂时不弹出框
	$('#achievementMulti').html('<div align="center" style=" margin-top:8px; width:99%;padding-top:50px;"><div style="margin-top:10px;color: black;">加载中...</div><img src="http://cache.17c.cn/chessweibo/images/process.gif" style="margin-top:10px" ></div>')
	$('#achievementMulti').show();*/
	$.ajax({
		type: "POST",
		dataType: "json",
		url: GAME.scripturl+"?m=rank&p=achievement_rank&mid=" + GAME.userInfo.mid + "&" + GAME.sessionKey,
		data: "type=setachievement&oldscore=" + OldScore + "&newscore=" + NewScore,
		success: function(msg){
			//debug( 'Achievementmsg', msg );
			/*暂时不弹出框
			if(msg.error == 0){
				AchievementMulti(msg.level);
			}else{
				$('#achievementMulti').hide();
			}*/
	   }
	});
}
/**
 * 设置成就分享对话框
 */

function AchievementMulti(level){
	$.ajax({
		type: "POST",
		dataType: "html",
		url: GAME.scripturl+"?m=rank&p=achievement_share",
		data: "mid=" + GAME.userInfo.mid + "&level=" + level,
		success: function(html){
			$('#achievementMulti').html(html);
			$('#achievementMulti').show();
	   }
	});
}

/**
 * 设置排行
 */

function setRank(param){
	var RankId = param.RankId;
	var Value = param.Value;
	$.ajax({
		type: "POST",
		url: GAME.scripturl+"?m=rank&p=achievement_rank&"+ GAME.sessionKey,
		data: "type=setrank&rankid=" + RankId + "&value=" + Value,
		success: function(msg){
			debug( 'setRankmsg', msg );
	   }
	});
}

/**
 * 返回好友列表
 */

function getFriendList(cache){
	
	var iscache = 0;
	if(GAME.userInfo.istodayfirst || GAME.userInfo.isRegister || cache){
		iscache = 1;
	}
	//iscache = 1;
	//返回好友
	$.ajax({
		type: "POST",
		url: GAME.scripturl+"?m=friend&p=loadfriend&"+ GAME.sessionKey,
		data: "mid=" + GAME.userInfo.mid + "&iscache=" + iscache,
		success: function(data){
			//alert(data);
			getFlashMovieObject('flashBox').sendFriendsData(data);
	   }
	});
}

/**
 * 返回防沉迷信息
 */

function getEnthralment(){
	$.ajax({
		type: "POST",
		dataType: "json",
		url: GAME.scripturl+"?m=enthralment&p=getEnthralment&"+ GAME.sessionKey + "&newtime=" + Math.random(),
		data: "",
		success: function(data){
			//alert(data.identity_status + ":" + data.online_time + ":" + data.oldOnlineTime);
			if(data.identity_status == 2){
				clearInterval(int);
			}
			if((data.identity_status == 1 && data.online_time >= 1) || data.identity_status == 0){
				getEnthralmentDiv(data.identity_status, data.online_time);
			}
			
	   }
	});
}
/**
 * 返回防沉迷信提示框
 */

function getEnthralmentDiv(identity_status, online_time){
	$.ajax({
		type: "POST",
		dataType: "html",
		url: GAME.scripturl+"?m=enthralment&p=content",
		data: "identity_status=" + identity_status + "&online_time=" + online_time,
		success: function(html){
			//alert(html);
			$('#EnthralmentMulti').html(html);
			$('#EnthralmentMulti').show();
	   }
	});
}

/**
 * 返回关注提示框
 */

function showFollowDiv(param){
	/*var isshow = param.isshow;
	//debug( 'showFollowDiv', param );
	var sitemid = param.sitemid;
	if(isshow && sitemid){
		
		//$('#followbutton').attr("uid", sitemid);
		//alert($('#followbutton').attr("uid"));
		$("#followIframe").attr("src","http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&width=63&height=24&uid="+ sitemid +"&style=1&btn=light&dpc=1");
		$('#followDiv').show();
	}else{
		$('#followDiv').hide();
	}*/
}



