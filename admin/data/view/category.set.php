<div class="pageContent">	
	<form method="post" action="?m=data&p=category&act=set&navTabId=data_cat&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['catid']) ? $item['catid'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			
			<div class="unit">
				<label>分类名称：</label>
				<input type="text" name="catname" size="60" maxlength="200" value="<?php echo isset($item['catname']) ? $item['catname'] : ''?>"/>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['catid']) ? "修改" : "增加"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
</div>



