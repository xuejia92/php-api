<div class="pageContent">	
	<form method="post" action="?m=setting&p=horn&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "horn"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
		
			<div class="unit">
				<label>游戏：</label>
				<select name="gameid" >
					<option  value="0">全局</option>
					<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
						<option <?php if($gameid == $item['gameid']):?>selected="selected"<?php endif;?>  value="<?php echo $gameid?>"><?php echo $gameName ?> </option>	
					<?php endforeach;?>
				</select>
			</div>

			<div class="unit">
				<label>喇叭信息：</label>
				<input type="text" name="content" size="90" maxlength="400" value="<?php echo isset($item['content']) ? $item['content'] : ''?>"/>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['id']) ? "重新发送" : "发送"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
</div>



