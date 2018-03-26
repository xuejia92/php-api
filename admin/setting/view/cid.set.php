<div class="pageContent">	
	<form method="post" action="?m=setting&p=cid&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "clist"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, dialogAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			<div class="unit">
				<div class="unit">
				<label>游戏：</label>
					<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
						<label><input  <?php if(in_array($gameid,$aGame) || $_REQUEST['gameid'] == $gameid ):?>checked<?php endif;?>  type="checkbox" name="gameid[]" value="<?php echo $gameid?>" /><?php echo $gameName?></label>
					<?php endforeach;?>
				</div>
				<label>客户端：</label>
				<select name="ctype">
					<?php foreach ($aCtype as $ctype=>$clientName):?>
						<option <?php if($ctype == $item['ctype'] || $_REQUEST['ctype'] == $ctype):?> selected="selected" <?php endif;?> value="<?php echo $ctype?>"><?php echo $clientName;?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="unit">
				<label>渠道商名称：</label>
				<input type="text" name="cname" size="60" maxlength="200" value="<?php echo isset($item['cname']) ? $item['cname'] : ''?>"/>
			</div>
			<div class="unit">
				<div class="unit">
				<label>渠道类型：</label>
					<?php foreach(Config_Game::$channelVertype as $verid=>$vername):?>
						<label><input  <?php if($verid == $item['vertype'] || 1 == $verid ):?>checked<?php endif;?>  type="radio" name="vertype" value="<?php echo $verid?>" /><?php echo $vername?></label>
					<?php endforeach;?>
				</div>
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



