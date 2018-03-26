<div class="pageContent">	
	<form method="post" action="?m=setting&p=msgpay&act=sort&navTabId=<?php echo $_GET['navTabId'] ? $_GET['navTabId'] : 'msgpaylist'?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			<input type="hidden" name="gameid"  value="<?php echo $_GET['gameid']?>"/>
			<input type="hidden" name="pid"  value="<?php echo $_GET['pid'] ? $_GET['pid'] : ''?>"/>
			<input type="hidden" name="mtype"  value="<?php echo $_GET['mtype']?>"/>
			<?php foreach($aChannel as $pos=>$name):?>
			<div class="unit">
				<input type="text" name="pos[]" size="5" maxlength="400" value="<?php echo $pos?>"/>
				<input type="hidden" name="ids[]"  value="<?php echo $name?>"/>
				<label><?php echo $aChannelName[$pos]?></label>
			</div>
			<?php endforeach;?>

		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['id']) ? "修改" : "修改"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
</div>



