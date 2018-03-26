<script type="text/javascript">

function getcid(obj){
	var gameid=obj.value;
	if(gameid == 0){
		return false;
	}
	$.ajax({
		url: '?m=action&p=actionconfig&act=getcid',
		type: 'GET',
		data:{gameid:gameid},
		dataType: 'json',
		success: function(result){
			$("#p_cid").html('');
			$("#p_cid").append("<option value='0'>请选择</option>");
			var obj = eval(result);
			$(obj).each(function(index) {
				var val = obj[index];
				$("#p_cid").append("<option value="+val.cid+" >"+val.cname+"</option>");
			})
		}
	});
}

var gameid='1';
$.ajax({
	url: '?m=action&p=actionconfig&act=getcid',
	type: 'GET',
	data:{gameid:gameid},
	dataType: 'json',
	success: function(result){
		$("#p_cid").html('');
		$("#p_cid").append("<option value='0'>请选择</option>");
		var obj = eval(result);
		$(obj).each(function(index) {
			var val = obj[index];
			$("#p_cid").append("<option value="+val.cid+" >"+val.cname+"</option>");
		})
	}
});

</script>

<div class="pageContent">	
	<form method="post" action="?m=action&p=actionconfig&act=deleteCid&tapid=<?php echo $tapid?>&navTabId=action_modify&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['itemid']) ? $item['itemid'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			
			<div class="unit">
				<label>所属分类：</label>
				<select name="gameid" onchange="getcid(this)">
				<?php foreach (Config_Game::$game as $gameid=>$gamename):?>
					<option value="<?php echo $gameid?>"><?php echo $gamename?> </option>	
				<?php endforeach;?>
				</select>
			</div>
			
			<div class="unit">
				<label>渠道：</label>
				<select name="cid" id="p_cid" >
					<option value="0">请选择</option>
				</select>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">删除</button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
</div>