<div class="pageContent">	
	<form method="post" action="?m=feedback&p=send&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "f_send"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="fid" value="<?php echo isset($item['fid']) ? $item['fid'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			<div class="unit">
				<label>所属游戏：</label>
				<select name="gameid">
					<option value="">请选择</option>
					<?php foreach (Config_Game::$game as $gameid=>$gamename):?>
						<option <?php if($_REQUEST['gameid'] == $gameid):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gamename;?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="unit">
				<label>mid：</label>
				<input type="text" name="mid" size="10" maxlength="30"  value=<?php echo $item['mid'] ? $item['mid'] : ''?> >
			</div>
			<div class="unit">
				<label>描述：</label>
				<textarea name="content"  style="width:300px;" cols="50" rows="4"><?php echo $item['content'] ?></textarea>
			</div>
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['fid']) ? "修改" : "增加"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>	
</div>



