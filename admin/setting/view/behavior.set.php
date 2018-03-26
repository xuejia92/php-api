<div class="pageContent">	
	<form method="post" action="?m=setting&p=behavior&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "behavior"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">

		    <div class="unit">
				<label>游戏ID：</label>
				<select  class="required" name="gameid">
				    <option value="0">所有</option>
				<?php foreach (Config_Game::$game as $gameid=>$gamename):?>
				    <option value="<?php echo $gameid?>"><?php echo $gamename?></option>
				<?php endforeach;?>
				</select>
			</div>
		
			<div class="unit">
				<label>命令符：</label>
				<input class="required"  type="text" name="beid" size="25" maxlength="400" value="<?php echo isset($item['beid']) ? $item['beid'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>命令名称：</label>
				<input class="required"  type="text" name="betitle" size="25" maxlength="400" value="<?php echo isset($item['betitle']) ? $item['betitle'] : ''?>"/>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo isset($item['id']) ? "增加" : "修改"?></button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
</div>



