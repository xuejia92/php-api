<div class="pageContent">	
	<form method="post" action="?m=setting&p=notice&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "nlist"?>&close=1" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)">
		<input type="hidden" name="id" value="<?php echo isset($item['id']) ? $item['id'] : ""?>"/>
		<div class="pageFormContent" layoutH="58">
			
			<div class="unit">
				<label>游戏：</label>
				<?php foreach(Config_Game::$game as $gameid=>$gameName):?>
					<label><input  <?php if(in_array($gameid,$aGame)):?>checked<?php endif;?>  type="checkbox" name="gameid[]" value="<?php echo $gameid?>" /><?php echo $gameName?></label>
				<?php endforeach;?>
			</div>
		
			<div class="unit">
				<label>客户端类型：</label>
				<select name="ctype" >
					<option <?php if($item['ctype'] == 0):?>selected="selected"<?php endif;?>  value="0">全部 </option>	
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
				<a class="btnLook" href="?m=setting&p=channel&a=notice&id=<?php echo isset($item['id']) ? $item['id'] : ""?>" lookupGroup="cid">查找</a>
				</div>
			</div>
			
			<div class="unit">
				<label>公告标题：</label>
				<input type="text" name="title" size="60" maxlength="200" value="<?php echo isset($item['title']) ? $item['title'] : ''?>"/>
			</div>
			
			<div class="unit">
			    <label>开始时间：</label>
		        <input type="text" name="stime" class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true" value="<?php echo isset($item['stime']) ? date('Y-m-d H:i:s',$item['stime']) : ''?>"/>
		        <a class="inputDateButton" href="javascript:;">选择</a>
		        <span class="info">yyyy-MM-dd HH:mm:ss</span>
			</div>
			
			<div class="unit">
				 <label>开始时间：</label>
		        <input type="text" name="etime" class="date" dateFmt="yyyy-MM-dd HH:mm:ss" readonly="true" value="<?php echo isset($item['etime']) ? date('Y-m-d H:i:s',$item['etime']) : ''?>"/>
		        <a class="inputDateButton" href="javascript:;">选择</a>
		        <span class="info">yyyy-MM-dd HH:mm:ss</span>
			</div>
			
			<div class="unit">
				<label>公告URL：</label>
				<input type="text" name="url" size="60" maxlength="200" value="<?php echo isset($item['url']) ? $item['url'] : ''?>"/>
			</div>

			<div class="unit">
				<label>公告内容：</label>
				<textarea name="content" cols="70" rows="20"><?php echo isset($item['content']) ? $item['content'] : ''  ?> </textarea>
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



