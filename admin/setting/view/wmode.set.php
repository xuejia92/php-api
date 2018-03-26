<div class="pageContent">	
	<form method="post" action="?m=setting&p=wmode&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "wlist"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">

			<div class="unit">
				<label>前端游戏显示</label>
				<input type="text" name="gamedesc" size="60" maxlength="200" value="<?php echo isset($item['gamedesc']) ? $item['gamedesc'] : ''?>"/>
			</div>
			<div class="unit">
				<label>后台显示：</label>
				<input type="text" name="admindesc" size="60" maxlength="200" value="<?php echo isset($item['admindesc']) ? $item['admindesc'] : ''?>"/>
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



