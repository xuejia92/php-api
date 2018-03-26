<div class="pageContent">
	
	<form method="post" action="?m=setting&p=versions&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "vlist"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			<div class="unit">
				<label>游戏：</label>
				<select name="gameid" >
					<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
						<option <?php if($gameid == $item['gameid']):?>selected="selected"<?php endif;?>  value="<?php echo $gameid?>"><?php echo $gameName ?> </option>	
					<?php endforeach;?>
				</select>
			</div>
			<div class="unit">
				<label>客户端类型：</label>
				<select name="ctype" >
					<option <?php if($item['ctype'] == 1):?>selected="selected"<?php endif;?>  value="1">android </option>	
					<option <?php if($item['ctype'] == 2):?>selected="selected"<?php endif;?>  value="2">iphone </option>	
					<option <?php if($item['ctype'] == 3):?>selected="selected"<?php endif;?>  value="3">ipad </option>	
					<option <?php if($item['ctype'] == 4):?>selected="selected"<?php endif;?>  value="4">android-pad </option>	
				</select>
			</div>
			
			<div class="unit">
			    <div  class="nowrap">
				<label>渠道选择：</label>
				<input class="readonly" name="cid.cname" type="text" size="60" value="<?php echo isset($item['cname']) ? $item['cname'] : ""?>" />
				<input type="hidden"  name="cid.cid" value=<?php echo isset($item['cid']) ? $item['cid'] : ""?>  />
				<a class="btnLook" href="?m=setting&p=channel&a=version&id=<?php echo isset($item['id']) ? $item['id'] : ""?>" lookupGroup="cid">查找</a>
				</div>
			</div>

			<div class="unit">
				<label>更新方式：</label>
				<select name="updatetype" >
					<option value="0">可选更新</option>
					<option value="1">强制更新</option>
				</select>
			</div>
			
			<div class="unit">
				<label>下载URL：</label>
				<input type="text" name="url" size="60" maxlength="200" value="<?php echo isset($item['url']) ? $item['url'] : ''?>"/>
			</div>
			
			<div class="unit">
				<label>更新条件：</label>				
				<select name="con" >
					<option value=">" <?php if(">" == $item['con']):?>selected="selected"<?php endif;?>> > </option>
					<option value="<" <?php if("<" == $item['con']):?>selected="selected"<?php endif;?>> < </option>
					<option value=">=" <?php if(">=" == $item['con']):?>selected="selected"<?php endif;?>> >= </option>
					<option value="<=" <?php if("<=" == $item['con']):?>selected="selected"<?php endif;?>> <= </option>
					<option value="=" <?php if("=" == $item['con']):?>selected="selected"<?php endif;?>> = </option>
				</select>
				<input type="text" name="versions" size="10" maxlength="20"  value="<?php echo isset($item['versions']) ? $item['versions'] : ''?>"/>
			</div>

			<div class="unit">
				<label>更新描述：</label>
				<textarea name="description" cols="80" rows="2"><?php echo isset($item['description']) ? $item['description'] : ''  ?> </textarea>
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



