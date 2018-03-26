<div class="pageContent">
	<script type="text/javascript">


function checktype(val){
	if(val == 1 ){
		document.getElementById("type1").style.display="block";
		document.getElementById("ptime1").style.display="block";
		document.getElementById("type2").style.display="none";
		document.getElementById("ptime2").style.display="none";
	}else if(val == 2){
		document.getElementById("type2").style.display="block";
		document.getElementById("ptime2").style.display="block";
		document.getElementById("type1").style.display="none";
		document.getElementById("ptime1").style.display="none";
	}else{
		document.getElementById("ptime2").style.display="block";
		document.getElementById("type1").style.display="none";
		document.getElementById("ptime1").style.display="none";
		document.getElementById("type2").style.display="none";
	}
}
	
</script>
	
	<form method="post" action="?m=setting&p=push&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "push"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			<div class="unit">
				<label>游戏：</label>
				<select name="gameid" >
					<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
						<option <?php if($gameid == $item['gameid']):?>selected="selected"<?php endif;?>  value="<?php echo $gameid?>"><?php echo $gameName ?> </option>	
					<?php endforeach;?>
				</select>
			</div>
			<div class="unit">
				<label>客户端类型：</label>
				<select name="ctype" >
					<option <?php if($item['ctype'] == 1):?>selected="selected"<?php endif;?>  value="1">android </option>	
					<option <?php if($item['ctype'] == 2):?>selected="selected"<?php endif;?>  value="2">IOS </option>	
				</select>
			</div>

			<div class="unit">
				<label>推送类型：</label>
				<select name="ptype" >
					<option onclick="checktype(1)"  <?php if($item['ptype'] == 1):?>selected="selected"<?php endif;?>  value="1">N天没登陆 </option>	
					<option onclick="checktype(2)"  <?php if($item['ptype'] == 2):?>selected="selected"<?php endif;?>  value="2">某段时间登陆过</option>	
					<option onclick="checktype(3)"  <?php if($item['ptype'] == 3):?>selected="selected"<?php endif;?>  value="3">全局推 </option>	
				</select>
			</div>

			<div id='type1'  style="display:none" class="unit">
				<label>推送条件</label>
				<input type="text" name="pcon" value="<?php echo isset($item['pcon'])?$item['pcon']:'';?>"/>
				<span style="color:red;float:left;">(N天没登陆的用户,输入天数)</span>
			</div>
			
			<div id='type2' style="display:none" class="unit">
				<label>推送条件</label>
				<span style="color:red;float:left;margin-left:5px;margin-right:5px">从</span>
				<input class="date" dateFmt="yyyy-MM-dd HH:mm"  type="text" name="ftime" value="<?php echo isset($item['ftime'])?$item['ftime']:'';?>"/>
				<span style="color:red;float:left; margin-left:5px;margin-right:5px">到</span>
				<input class="date" dateFmt="yyyy-MM-dd HH:mm"  type="text" name="etime" value="<?php echo isset($item['etime'])?$item['etime']:'';?>"/>
				<span style="color:red;float:left;">(专推某段时间登陆过的用户)</span>
			</div>
			
			<div class="unit">
				<label>推送内容</label>
				<input size="90" type="text" name="msg" value="<?php echo isset($item['msg']) ? $item['msg'] : '';?>"/>
			</div>
			
			<div id='ptime1' style="display:none" class="unit">
				<label>推送时间</label>
				<input class="date" dateFmt="HH:mm"  type="text" name="ptime1" value="<?php echo isset($item['ptime'])?$item['ptime']:'';?>"/>
			</div>
			<div id='ptime2' style="display:none" class="unit">
				<label>推送时间</label>
				<input  class="date" dateFmt="yyyy-MM-dd HH:mm"  type="text" name="ptime2" value="<?php echo isset($item['ptime']) ? date("Y-m-d H:i",$item['ptime']):'';?>"/>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['id']) ? "修改" : "增加"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
</div>
<script>


var type  = "<?php echo $item['ptype'];?>";
if(!type){
	document.getElementById("type1").style.display="block";
	document.getElementById("ptime1").style.display="block";
}


if(type == 1 ){
	document.getElementById("type1").style.display="block";
	document.getElementById("ptime1").style.display="block";
}else if(type == 2){
	document.getElementById("type2").style.display="block";
	document.getElementById("ptime2").style.display="block";
}


</script>


