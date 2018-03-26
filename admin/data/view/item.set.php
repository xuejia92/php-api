<div class="pageContent">	
	<form method="post" action="?m=data&p=item&act=set&navTabId=data_item&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['itemid']) ? $item['itemid'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			
			<div class="unit">
				<label>所属分类：</label>
				<select name="catid" >
				<?php foreach($cats as $catid=>$catname):?>
					<option <?php if($item['catid'] == $catid || $_GET['catid'] == $catid):?>selected="selected"<?php endif;?>  value="<?php echo $catid?>"><?php echo $catname?> </option>	
				<?php endforeach;?>
				</select>
			</div>
			
			<div class="unit">
				<label>分类名称：</label>
				<input type="text" name="itemname" size="60" maxlength="200" value="<?php echo isset($item['itemname']) ? $item['itemname'] : ''?>"/>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['itemid']) ? "修改" : "增加"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
</div>
