<div class="pageContent">	
	<form method="post" action="?m=setting&p=privilege&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "privilege"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">

			<div class="unit">
				<label>用户名：</label>
				<input class="required" type="text" name="username" size="60" maxlength="200" value="<?php echo isset($item['username']) ? $item['username'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>密码：</label>
				<input  type="password" name="password" size="60" maxlength="200" value="<?php echo isset($item['password']) ? $item['password'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label></label>
				<label>模块权限：</label>
			</div>
			
			<div class="unit">
				<label>设置：</label>
				<label><input onclick="checkget('setting')" type="checkbox" id="setting_get" name="setting_get" value="1" <?php if($item['privilege']['setting'] >=1 ):?>checked<?php endif;?> />查看</label>
				<label><input onclick="check('setting')" type="checkbox" id="setting_set" name="setting_set" value="2" <?php if($item['privilege']['setting'] == 3):?>checked<?php endif;?> />更改</label>
			</div>
			
			<div class="unit">
				<label>监控：</label>
				<label><input onclick="checkget('monitor')" type="checkbox" id="monitor_get" name="monitor_get" value="1" <?php if($item['privilege']['monitor'] >=1 ):?>checked<?php endif;?> />查看</label>
				<label><input onclick="check('monitor')" type="checkbox" id="monitor_set" name="monitor_set" value="2" <?php if($item['privilege']['monitor'] ==3 ):?>checked<?php endif;?> />更改</label>
			</div>
			
			<div class="unit">
				<label>反馈：</label>
				<label><input onclick="checkget('feedback')" type="checkbox" id="feedback_get" name="feedback_get" value="1" <?php if($item['privilege']['feedback'] >=1 ):?>checked<?php endif;?> />查看</label>
				<label><input onclick="check('feedback')" type="checkbox" id="feedback_set" name="feedback_set" value="2" <?php if($item['privilege']['feedback'] ==3 ):?>checked<?php endif;?> />更改</label>
			</div>
			
			<div class="unit">
				<label>用户：</label>
				<label><input onclick="checkget('user')"  type="checkbox" id="user_get" name="user_get" value="1" <?php if($item['privilege']['user'] >=1 ):?>checked<?php endif;?> />查看</label>
				<label><input onclick="check('user')" type="checkbox" id="user_set" name="user_set" value="2" <?php if($item['privilege']['user'] ==3 ):?>checked<?php endif;?> />更改</label>
			</div>
			
			<div class="unit">
				<label>数据：</label>
				<label><input onclick="checkget('data')" type="checkbox" id="data_get" name="data_get" value="1" <?php if($item['privilege']['data'] >=1 ):?>checked<?php endif;?> />查看</label>
				<label><input onclick="check('data')" type="checkbox" id="data_set" name="data_set" value="2" <?php if($item['privilege']['data'] ==3 ):?>checked<?php endif;?> />更改</label>
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

function check(param){
	$("#"+param+"_get").attr("checked",true); 
}

function checkget(param){
	$("#"+param+"_set").attr("checked",false);	
}

</script>



