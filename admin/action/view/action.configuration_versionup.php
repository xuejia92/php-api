<script type="text/javascript">
 $(".tabsPageContent").css('overflow','auto');
</script>
<div class="pageContent">
    <?php foreach (Config_Game::$game as $key=>$value):?>
    <form method="post" action="?m=action&p=actionconfig&act=set&id=<?php echo $id?>&tapid=<?php echo $tapid?>&gameid=<?php echo $key?>&navTabId=action_modify&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<div class="pageFormContent">
			<h1><?php echo $value?></h1>
			
			<div class="unit">
				<label>Android版本号：</label>
				<input type="text" name="version1" size="60" maxlength="200" value="<?php echo $action[$key][1] ? $action[$key][1] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>iOS版本号：</label>
				<input type="text" name="version2" size="60" maxlength="200" value="<?php echo $action[$key][2] ? $action[$key][2] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>奖励金币：</label>
				<input type="text" name="bonus" size="60" maxlength="200" value="<?php echo $action[$key]['bonus'] ? $action[$key]['bonus'] : ''?>"/>
			</div>
			
			<div class="unit">
				<button type="submit">修改</button>
			</div>
		</div>
    </form>
    <?php endforeach;?>
</div>